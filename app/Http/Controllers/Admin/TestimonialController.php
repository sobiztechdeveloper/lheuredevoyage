<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestimonialRequest;
use App\Http\Requests\Admin\UpdateTestimonialRequest;
use App\Models\Testimonial;
use App\Services\CmsImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::query()->latest()->paginate(15);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('admin.testimonials.create', ['testimonial' => new Testimonial]);
    }

    public function store(StoreTestimonialRequest $request, CmsImageUploader $uploader): RedirectResponse
    {
        Testimonial::query()->create($this->prepareData($request, $uploader));

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial, CmsImageUploader $uploader): RedirectResponse
    {
        $testimonial->update($this->prepareData($request, $uploader, $testimonial));

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }

    private function prepareData(StoreTestimonialRequest $request, CmsImageUploader $uploader, ?Testimonial $existing = null): array
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);
        $data['image'] = $uploader->upload($request->file('image'), 'testimonials', $existing?->image);

        return $data;
    }
}
