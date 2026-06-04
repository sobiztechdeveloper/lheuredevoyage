<?php

namespace App\Support;

use App\Models\WebsiteSetting;
use App\Services\CmsImageUploader;

class PageBanner
{
    /**
     * Resolve breadcrumb background URL: uploaded path → site default → page config → theme default.
     */
    public static function breadcrumbUrl(?string $pageKey = null, ?string $uploadedPath = null): string
    {
        $uploader = app(CmsImageUploader::class);

        if ($uploadedPath) {
            return $uploader->url($uploadedPath);
        }

        $settings = WebsiteSetting::cached();
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
