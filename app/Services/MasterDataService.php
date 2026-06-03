<?php

namespace App\Services;

use App\Models\Master\MasterDataModel;
use Illuminate\Support\Str;

class MasterDataService
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function prepareForSave(array $data, ?MasterDataModel $existing = null): array
    {
        $data['is_active'] = filter_var($data['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if (empty($data['slug']) && ! empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (! $existing && $data['sort_order'] === 0) {
            $max = $existing?->query()->max('sort_order') ?? 0;
            $data['sort_order'] = $max + 1;
        }

        return $data;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, MasterDataModel>
     */
    public function activeOptions(string $modelClass)
    {
        return $modelClass::query()->active()->ordered()->get(['id', 'name', 'slug']);
    }
}
