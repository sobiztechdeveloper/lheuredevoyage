<?php

namespace App\Services;

use App\Models\Cruise;
use App\Models\CruiseCabin;
use App\Models\CruiseGalleryImage;
use App\Models\CruiseItineraryDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CruiseProductAdminService
{
    public function __construct(
        protected CmsImageUploader $uploader,
    ) {}

    public function save(Request $request, ?Cruise $existing = null): Cruise
    {
        return DB::transaction(function () use ($request, $existing) {
            $data = $this->validated($request, $existing);

            $data['featured_image'] = $this->uploader->upload($request->file('featured_image'), 'cruises', $existing?->featured_image);
            $data['image'] = $data['featured_image'] ?? $existing?->image;
            $data['brochure_pdf'] = $this->uploader->upload($request->file('brochure_pdf'), 'cruises', $existing?->brochure_pdf);
            $data['deck_plan_pdf'] = $this->uploader->upload($request->file('deck_plan_pdf'), 'cruises', $existing?->deck_plan_pdf);
            $data['terms_pdf'] = $this->uploader->upload($request->file('terms_pdf'), 'cruises', $existing?->terms_pdf);

            unset($data['itinerary'], $data['cabins'], $data['gallery_images'], $data['included_services'], $data['not_included_services']);

            $product = $existing ?? new Cruise;
            $product->fill($data);
            $product->save();

            app(CatalogMasterDataService::class)->syncFromRequest($product, $request, 'cruise');

            $this->syncItinerary($product, $request->input('itinerary', []));
            $this->syncCabins($product, $request->input('cabins', []));
            $this->syncGallery($product, $request->file('gallery_images', []));

            return $product->fresh(['itineraryDays', 'cabins', 'galleryImages']);
        });
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request, ?Cruise $existing = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cruise_code' => ['nullable', 'string', 'max:80'],
            'slug' => ['nullable', 'string', 'max:255'],
            'cruise_line' => ['nullable', 'string', 'max:255'],
            'ship_name' => ['nullable', 'string', 'max:255'],
            'ship_class' => ['nullable', 'string', 'max:40'],
            'ship_capacity' => ['nullable', 'integer', 'min:0'],
            'launch_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'cruise_region' => ['nullable', 'string', 'max:40'],
            'departure_port' => ['nullable', 'string', 'max:255'],
            'arrival_port' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'duration_nights' => ['nullable', 'integer', 'min:1'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'duration' => ['nullable', 'string', 'max:100'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'brochure_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'deck_plan_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'terms_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'itinerary' => ['nullable', 'array'],
            'cabins' => ['nullable', 'array'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:4096'],
        ]);

        $data['slug'] = $data['slug'] ?: Cruise::generateSlug($data['name'], $existing?->id);
        $data['title'] = $data['name'];
        $data['status'] = $request->boolean('status', true);
        $data['featured'] = $request->boolean('featured', false);
        $data['is_active'] = $data['status'];
        $data['is_featured'] = $data['featured'];
        $data['included_services'] = array_values($request->input('included_services', []));
        $data['not_included_services'] = array_values($request->input('not_included_services', []));
        $data['updated_by'] = auth()->id();

        if (! $existing) {
            $data['created_by'] = auth()->id();
        }

        return $data;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    protected function syncItinerary(Cruise $cruise, array $rows): void
    {
        $cruise->itineraryDays()->delete();
        foreach (array_values($rows) as $i => $row) {
            if (empty($row['port_name'])) {
                continue;
            }
            CruiseItineraryDay::query()->create([
                'cruise_id' => $cruise->id,
                'day_number' => (int) ($row['day_number'] ?? ($i + 1)),
                'port_name' => $row['port_name'],
                'arrival_time' => $row['arrival_time'] ?? null,
                'departure_time' => $row['departure_time'] ?? null,
                'description' => $row['description'] ?? null,
                'sort_order' => $i,
            ]);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    protected function syncCabins(Cruise $cruise, array $rows): void
    {
        $cruise->cabins()->delete();
        foreach (array_values($rows) as $i => $row) {
            if (empty($row['name'])) {
                continue;
            }
            CruiseCabin::query()->create([
                'cruise_id' => $cruise->id,
                'cabin_type' => $row['cabin_type'] ?? 'interior',
                'name' => $row['name'],
                'description' => $row['description'] ?? null,
                'max_adults' => (int) ($row['max_adults'] ?? 2),
                'max_children' => (int) ($row['max_children'] ?? 0),
                'max_occupancy' => (int) ($row['max_occupancy'] ?? 2),
                'size' => $row['size'] ?? null,
                'starting_price' => $row['starting_price'] ?? null,
                'featured' => ! empty($row['featured']),
                'sort_order' => $i,
            ]);
        }
    }

    /**
     * @param  array<int, \Illuminate\Http\UploadedFile>  $files
     */
    protected function syncGallery(Cruise $cruise, array $files): void
    {
        $sort = (int) $cruise->galleryImages()->max('sort_order');
        foreach ($files as $file) {
            if (! $file) {
                continue;
            }
            $path = $this->uploader->upload($file, 'cruises/gallery', null);
            if ($path) {
                CruiseGalleryImage::query()->create([
                    'cruise_id' => $cruise->id,
                    'path' => $path,
                    'sort_order' => ++$sort,
                ]);
            }
        }
    }
}
