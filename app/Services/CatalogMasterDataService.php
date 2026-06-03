<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CatalogMasterDataService
{
    public function __construct(
        protected MasterDataService $masterData,
    ) {}

    /**
     * @return array<string, \Illuminate\Support\Collection>
     */
    public function optionsForCatalog(string $catalogKey, ?Model $item = null): array
    {
        $relations = MasterDataRegistry::catalogRelations($catalogKey);
        $options = [];

        foreach ($relations as $relation => $config) {
            $options[$relation] = $this->masterData->activeOptions($config['model']);
        }

        return $options;
    }

    /**
     * @return array<string, array<int>>
     */
    public function selectedIdsForCatalog(string $catalogKey, Model $item): array
    {
        $selected = [];

        foreach (MasterDataRegistry::catalogRelations($catalogKey) as $relation => $config) {
            if (method_exists($item, $relation)) {
                $selected[$relation] = $item->{$relation}()->pluck(
                    $item->{$relation}()->getRelated()->getTable().'.id'
                )->all();
            }
        }

        return $selected;
    }

    public function syncFromRequest(Model $item, Request $request, string $catalogKey): void
    {
        foreach (MasterDataRegistry::catalogRelations($catalogKey) as $relation => $config) {
            if (! method_exists($item, $relation)) {
                continue;
            }

            $param = $config['param'];
            $ids = array_filter(array_map('intval', (array) $request->input($param, [])));

            $item->{$relation}()->sync($ids);
        }
    }

    public function applyMasterFilters($query, Request $request, string $catalogKey): void
    {
        foreach (MasterDataRegistry::catalogRelations($catalogKey) as $relation => $config) {
            $ids = array_filter(array_map('intval', (array) $request->input($config['param'], [])));

            if ($ids === []) {
                continue;
            }

            if (method_exists($query->getModel(), $relation)) {
                $query->whereHas($relation, function ($q) use ($ids) {
                    $q->whereIn($q->qualifyColumn('id'), $ids);
                });
            }
        }
    }

    /**
     * @return array<int, array{key: string, label: string, param: string, options: \Illuminate\Support\Collection}>
     */
    public function filterGroupsForCatalog(string $catalogKey): array
    {
        $groups = [];

        foreach (MasterDataRegistry::catalogRelations($catalogKey) as $relation => $config) {
            $groups[] = [
                'key' => $relation,
                'label' => $config['label'],
                'param' => $config['param'],
                'options' => $this->masterData->activeOptions($config['model']),
            ];
        }

        return $groups;
    }
}
