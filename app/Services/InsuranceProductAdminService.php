<?php

namespace App\Services;

use App\Models\InsurancePlanBenefit;
use App\Models\InsurancePlanExclusion;
use App\Models\InsurancePlanGalleryImage;
use App\Models\TravelInsurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InsuranceProductAdminService
{
    public function __construct(
        protected CmsImageUploader $uploader,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function validatedProductData(Request $request, ?TravelInsurance $existing = null): array
    {
        $data = $request->validate([
            'insurance_company' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'plan_code' => ['nullable', 'string', 'max:80'],
            'slug' => ['nullable', 'string', 'max:255'],
            'plan_type' => ['nullable', 'string', 'max:40'],
            'short_description' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string'],
            'coverage' => ['nullable', 'string', 'max:255'],
            'coverage_currency' => ['nullable', 'string', 'max:3'],
            'premium_currency' => ['nullable', 'string', 'max:3'],
            'base_premium' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'annual_price' => ['nullable', 'numeric', 'min:0'],
            'student_price' => ['nullable', 'numeric', 'min:0'],
            'child_price' => ['nullable', 'numeric', 'min:0'],
            'senior_price' => ['nullable', 'numeric', 'min:0'],
            'medical_coverage_amount' => ['nullable', 'numeric', 'min:0'],
            'min_age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'max_age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'nationality_restrictions' => ['nullable', 'string'],
            'covered_regions' => ['nullable', 'string'],
            'covered_countries' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'schengen_covered' => ['nullable', 'boolean'],
            'worldwide_covered' => ['nullable', 'boolean'],
            'student_eligible' => ['nullable', 'boolean'],
            'family_eligible' => ['nullable', 'boolean'],
            'covid_coverage' => ['nullable', 'boolean'],
            'adventure_sports_coverage' => ['nullable', 'boolean'],
            'winter_sports_coverage' => ['nullable', 'boolean'],
            'price_per_person' => ['nullable', 'boolean'],
            'price_per_family' => ['nullable', 'boolean'],
            'featured' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'brochure_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'policy_wording_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'terms_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'benefits' => ['nullable', 'array'],
            'benefits.*.title' => ['nullable', 'string', 'max:255'],
            'benefits.*.description' => ['nullable', 'string'],
            'benefits.*.icon' => ['nullable', 'string', 'max:120'],
            'exclusions' => ['nullable', 'array'],
            'exclusions.*.title' => ['nullable', 'string', 'max:255'],
            'exclusions.*.description' => ['nullable', 'string'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:4096'],
        ]);

        $data['slug'] = $data['slug'] ?: TravelInsurance::generateSlug($data['name'], $existing?->id);
        $data['title'] = $data['name'];
        $data['status'] = $request->boolean('status', true);
        $data['featured'] = $request->boolean('featured', false);
        $data['is_active'] = $data['status'];
        $data['is_featured'] = $data['featured'];
        $data['price'] = $data['base_premium'] ?? $data['price'] ?? 0;
        $data['updated_by'] = auth()->id();

        if (! $existing) {
            $data['created_by'] = auth()->id();
        }

        foreach ([
            'schengen_covered', 'worldwide_covered', 'student_eligible', 'family_eligible',
            'covid_coverage', 'adventure_sports_coverage', 'winter_sports_coverage',
            'price_per_person', 'price_per_family',
        ] as $flag) {
            $data[$flag] = $request->boolean($flag, false);
        }

        return $data;
    }

    public function save(Request $request, ?TravelInsurance $existing = null): TravelInsurance
    {
        return DB::transaction(function () use ($request, $existing) {
            $data = $this->validatedProductData($request, $existing);

            $data['logo'] = $this->uploader->upload($request->file('logo'), 'insurance-products', $existing?->logo);
            $data['featured_image'] = $this->uploader->upload($request->file('featured_image'), 'insurance-products', $existing?->featured_image);
            $data['image'] = $data['featured_image'] ?? $existing?->image;
            $data['brochure_pdf'] = $this->uploader->upload($request->file('brochure_pdf'), 'insurance-products', $existing?->brochure_pdf);
            $data['policy_wording_pdf'] = $this->uploader->upload($request->file('policy_wording_pdf'), 'insurance-products', $existing?->policy_wording_pdf);
            $data['terms_pdf'] = $this->uploader->upload($request->file('terms_pdf'), 'insurance-products', $existing?->terms_pdf);

            unset($data['benefits'], $data['exclusions'], $data['gallery_images']);

            $product = $existing ?? new TravelInsurance;
            $product->fill($data);
            $product->save();

            $this->syncBenefits($product, $request->input('benefits', []));
            $this->syncExclusions($product, $request->input('exclusions', []));
            $this->syncGallery($product, $request->file('gallery_images', []));

            return $product->fresh(['benefits', 'exclusions', 'galleryImages']);
        });
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    protected function syncBenefits(TravelInsurance $product, array $items): void
    {
        $product->benefits()->delete();
        foreach (array_values($items) as $i => $item) {
            if (empty($item['title'])) {
                continue;
            }
            InsurancePlanBenefit::query()->create([
                'travel_insurance_id' => $product->id,
                'title' => $item['title'],
                'description' => $item['description'] ?? null,
                'icon' => $item['icon'] ?? null,
                'sort_order' => $i,
            ]);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    protected function syncExclusions(TravelInsurance $product, array $items): void
    {
        $product->exclusions()->delete();
        foreach (array_values($items) as $i => $item) {
            if (empty($item['title'])) {
                continue;
            }
            InsurancePlanExclusion::query()->create([
                'travel_insurance_id' => $product->id,
                'title' => $item['title'],
                'description' => $item['description'] ?? null,
                'sort_order' => $i,
            ]);
        }
    }

    /**
     * @param  array<int, \Illuminate\Http\UploadedFile>  $files
     */
    protected function syncGallery(TravelInsurance $product, array $files): void
    {
        $sort = (int) $product->galleryImages()->max('sort_order');
        foreach ($files as $file) {
            if (! $file) {
                continue;
            }
            $path = $this->uploader->upload($file, 'insurance-products/gallery', null);
            if ($path) {
                InsurancePlanGalleryImage::query()->create([
                    'travel_insurance_id' => $product->id,
                    'path' => $path,
                    'sort_order' => ++$sort,
                ]);
            }
        }
    }
}
