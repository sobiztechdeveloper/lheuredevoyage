<?php

namespace App\Support;

use App\Models\WebsiteSetting;
use App\Services\CmsImageUploader;

class PageBanner
{
    /**
     * @return array<string, string>
     */
    public static function catalogAdminLabels(): array
    {
        $labels = config('page-banners.catalog_admin_labels');

        if (is_array($labels) && $labels !== []) {
            return $labels;
        }

        return [
            'tourpackage' => 'Tour Packages',
            'flight' => 'Flights',
            'hotel' => 'Hotels',
            'cruise' => 'Cruises',
            'rentalcar' => 'Cars',
            'travelinsurance' => 'Travel Insurance',
        ];
    }

    /**
     * Resolve breadcrumb background URL: uploaded path → site default → page config → theme default.
     */
    public static function breadcrumbUrl(?string $pageKey = null, ?string $uploadedPath = null): string
    {
        $uploader = app(CmsImageUploader::class);

        $settings = WebsiteSetting::cached();

        if ($uploadedPath) {
            return $uploader->url($uploadedPath);
        }

        if ($pageKey) {
            $pageImages = $settings->page_breadcrumb_images ?? [];
            if (! empty($pageImages[$pageKey])) {
                return $uploader->url($pageImages[$pageKey]);
            }
        }

        if (! empty($settings->default_breadcrumb_image)) {
            return $uploader->url($settings->default_breadcrumb_image);
        }

        $pages = config('page-banners.pages', []);
        $relative = ($pageKey && isset($pages[$pageKey]))
            ? $pages[$pageKey]
            : config('page-banners.default', 'assets/img/breadcrumb/01.jpg');

        return $uploader->url(null, $relative);
    }
}
