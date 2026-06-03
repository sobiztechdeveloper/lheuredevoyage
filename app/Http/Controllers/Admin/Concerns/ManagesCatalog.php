<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Services\CatalogAdminService;
use App\Services\CatalogMasterDataService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

trait ManagesCatalog
{
    abstract protected function catalogModel(): string;

    abstract protected function catalogLabel(): string;

    abstract protected function catalogFields(): array;

    abstract protected function catalogRules(): array;

    abstract protected function catalogUploadDirectory(): string;

    public function index(Request $request): View
    {
        $this->authorizeCatalog('viewAny');

        $model = $this->catalogModel();

        $items = $model::query()
            ->search($request->input('q'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.catalog.index', [
            'label' => $this->catalogLabel(),
            'resource' => $this->resourceKey(),
            'items' => $items,
            'search' => $request->input('q'),
        ]);
    }

    public function create(): View
    {
        $this->authorizeCatalog('create');

        return view('admin.catalog.form', array_merge([
            'label' => $this->catalogLabel(),
            'resource' => $this->resourceKey(),
            'item' => new ($this->catalogModel()),
            'extraFields' => $this->catalogFields(),
        ], $this->masterDataFormVars()));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeCatalog('create');

        $data = $this->validated($request);
        $item = $this->catalogModel()::query()->create($data);
        $this->syncCatalogMasterData($item, $request);

        return redirect()->route('admin.'.$this->resourceKey().'.index')
            ->with('success', $this->catalogLabel().' created.');
    }

    public function edit(string $key): View
    {
        $item = $this->resolveCatalogItem($key);
        $this->authorizeCatalog('update', $item);

        return view('admin.catalog.form', array_merge([
            'label' => $this->catalogLabel(),
            'resource' => $this->resourceKey(),
            'item' => $item,
            'extraFields' => $this->catalogFields(),
        ], $this->masterDataFormVars($item)));
    }

    public function update(Request $request, string $key): RedirectResponse
    {
        $item = $this->resolveCatalogItem($key);
        $this->authorizeCatalog('update', $item);
        $item->update($this->validated($request, $item));
        $this->syncCatalogMasterData($item, $request);

        return redirect()->route('admin.'.$this->resourceKey().'.index')
            ->with('success', $this->catalogLabel().' updated.');
    }

    public function destroy(string $key): RedirectResponse
    {
        $item = $this->resolveCatalogItem($key);
        $this->authorizeCatalog('delete', $item);
        $item->delete();

        return redirect()->route('admin.'.$this->resourceKey().'.index')
            ->with('success', $this->catalogLabel().' deleted.');
    }

    protected function resolveCatalogItem(string $key): Model
    {
        $query = $this->catalogModel()::query();

        if (ctype_digit($key)) {
            return $query->findOrFail((int) $key);
        }

        return $query->where('slug', $key)->firstOrFail();
    }

    protected function resourceKey(): string
    {
        return Str::snake(class_basename($this->catalogModel()), '-');
    }

    protected function authorizeCatalog(string $ability, ?Model $item = null): void
    {
        $model = $this->catalogModel();

        $args = $item ? [$item] : [$model];

        $this->authorize($ability, ...$args);
    }

    protected function validated(Request $request, ?Model $item = null): array
    {
        $rules = array_merge([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:'.(new ($this->catalogModel()))->getTable().',slug,'.($item?->id ?? 'NULL')],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'price_unit' => ['nullable', 'string', 'max:50'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'featured_image_path' => ['nullable', 'string', 'max:255'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:4096'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'review_count' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'featured' => ['nullable', 'boolean'],
        ], $this->catalogRules());

        $data = $request->validate($rules);

        $catalogAdmin = app(CatalogAdminService::class);
        $catalogAdmin->prepareFlags($request, $data);
        $catalogAdmin->prepareSlug($data, $item);
        $catalogAdmin->normalizePricing($data);
        app(CatalogAdminService::class)->applyImages(
            $request,
            $data,
            $this->catalogUploadDirectory(),
            $item
        );

        unset($data['featured_image_path'], $data['gallery_images']);

        if ($this->catalogModel() === \App\Models\Flight::class && isset($data['name'])) {
            $data['title'] = $data['name'];
            unset($data['name']);
        }

        return $data;
    }

    protected function catalogMasterDataKey(): ?string
    {
        return null;
    }

    /**
     * @return array<string, mixed>
     */
    protected function masterDataFormVars(?Model $item = null): array
    {
        $key = $this->catalogMasterDataKey();

        if (! $key) {
            return [
                'masterRelations' => [],
                'masterSelected' => [],
            ];
        }

        $service = app(CatalogMasterDataService::class);

        return [
            'masterRelations' => $service->optionsForCatalog($key, $item),
            'masterRelationConfig' => \App\Services\MasterDataRegistry::catalogRelations($key),
            'masterSelected' => $item?->exists
                ? $service->selectedIdsForCatalog($key, $item)
                : [],
            'catalogMasterKey' => $key,
        ];
    }

    protected function syncCatalogMasterData(Model $item, Request $request): void
    {
        $key = $this->catalogMasterDataKey();

        if ($key) {
            app(CatalogMasterDataService::class)->syncFromRequest($item, $request, $key);
        }
    }
}
