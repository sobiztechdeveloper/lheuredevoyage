<?php

namespace App\Services;

use App\Models\HomeBlock;
use Illuminate\Http\Request;

class HomeBlockAdminService
{
    public function __construct(
        protected CatalogImageUploader $uploader,
    ) {}

    public function prepareFromRequest(Request $request, ?HomeBlock $block = null): array
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? (HomeBlock::query()->where('section', $data['section'])->max('sort_order') + 1);

        $directory = 'home-blocks/'.$data['section'];

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploader->uploadFeatured(
                $request->file('image'),
                $directory,
                $block?->image
            );
        } elseif ($request->filled('image_path')) {
            $data['image'] = $request->input('image_path');
        }

        if ($request->hasFile('icon')) {
            $data['icon'] = $this->uploader->uploadFeatured(
                $request->file('icon'),
                $directory.'/icons',
                $block?->icon
            );
        } elseif ($request->filled('icon_path')) {
            $data['icon'] = $request->input('icon_path');
        }

        unset($data['image_path'], $data['icon_path']);

        return $data;
    }
}
