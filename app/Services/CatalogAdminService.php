<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogAdminService
{
    public function __construct(
        protected CatalogImageUploader $uploader,
    ) {}

    /**
     * @param  array<string, mixed>  $validated
     */
    public function applyImages(Request $request, array &$validated, string $directory, ?Model $item = null): void
    {
        $existingFeatured = $item?->featured_image ?? $item?->image;

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $this->uploader->uploadFeatured(
                $request->file('featured_image'),
                $directory,
                $existingFeatured
            );
        } elseif ($request->filled('featured_image_path')) {
            $validated['featured_image'] = $request->input('featured_image_path');
        }

        $existingGallery = $item?->gallery_json ?? [];
        if (is_string($existingGallery)) {
            $existingGallery = json_decode($existingGallery, true) ?: [];
        }

        $gallery = $this->uploader->uploadGallery(
            $request->file('gallery_images', []),
            $directory,
            $existingGallery
        );

        if ($gallery !== []) {
            $validated['gallery_json'] = $gallery;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function prepareSlug(array &$data, ?Model $item = null): void
    {
        $name = $data['name'] ?? $data['title'] ?? '';

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($name);
        }

        if ($item && ($data['slug'] ?? '') === $item->slug) {
            unset($data['slug']);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function prepareFlags(Request $request, array &$data): void
    {
        $data['status'] = $request->boolean('status', $request->boolean('is_active', true));
        $data['featured'] = $request->boolean('featured', $request->boolean('is_featured'));
        $data['is_active'] = $data['status'];
        $data['is_featured'] = $data['featured'];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function normalizePricing(array &$data): void
    {
        if (isset($data['price_per_day']) && empty($data['price'])) {
            $data['price'] = $data['price_per_day'];
        }

        if (isset($data['star_rating']) && empty($data['stars'])) {
            $data['stars'] = $data['star_rating'];
        }

        if (isset($data['vehicle_type']) && empty($data['car_type'])) {
            $data['car_type'] = $data['vehicle_type'];
        }

        if (isset($data['passenger_capacity']) && empty($data['seats'])) {
            $data['seats'] = $data['passenger_capacity'];
        }

        if (isset($data['coverage']) && empty($data['coverage_type'])) {
            $data['coverage_type'] = $data['coverage'];
        }
    }
}
