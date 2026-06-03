<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReorderHeroSectionsRequest;
use App\Http\Requests\Admin\StoreHeroSectionRequest;
use App\Http\Requests\Admin\UpdateHeroSectionRequest;
use App\Models\HeroSection;
use App\Services\CmsImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HeroSectionController extends Controller
{
    public function index(): View
    {
        $heroSections = HeroSection::query()->ordered()->get();

        return view('admin.hero-sections.index', compact('heroSections'));
    }

    public function create(): View
    {
        return view('admin.hero-sections.create', ['heroSection' => new HeroSection]);
    }

    public function store(StoreHeroSectionRequest $request, CmsImageUploader $uploader): RedirectResponse
    {
        $data = $this->prepareData($request, $uploader);

        HeroSection::query()->create($data);

        return redirect()->route('admin.hero-sections.index')->with('success', 'Hero section created.');
    }

    public function edit(HeroSection $heroSection): View
    {
        return view('admin.hero-sections.edit', compact('heroSection'));
    }

    public function update(UpdateHeroSectionRequest $request, HeroSection $heroSection, CmsImageUploader $uploader): RedirectResponse
    {
        $heroSection->update($this->prepareData($request, $uploader, $heroSection));

        return redirect()->route('admin.hero-sections.index')->with('success', 'Hero section updated.');
    }

    public function destroy(HeroSection $heroSection): RedirectResponse
    {
        $heroSection->delete();

        return redirect()->route('admin.hero-sections.index')->with('success', 'Hero section deleted.');
    }

    public function reorder(ReorderHeroSectionsRequest $request): RedirectResponse
    {
        foreach ($request->validated('order') as $position => $id) {
            HeroSection::query()->whereKey($id)->update(['sort_order' => $position]);
        }

        return redirect()->route('admin.hero-sections.index')->with('success', 'Hero sections reordered.');
    }

    private function prepareData(StoreHeroSectionRequest $request, CmsImageUploader $uploader, ?HeroSection $existing = null): array
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);
        $data['sort_order'] = $data['sort_order'] ?? (HeroSection::query()->max('sort_order') + 1);
        $data['image'] = $uploader->upload($request->file('image'), 'heroes', $existing?->image);

        return $data;
    }
}
