<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('pages.publicView.about', [
            'about' => About::query()->where('is_active', true)->latest()->first()
                ?? About::query()->first(),
            'faqs' => Faq::query()->active()->ordered()->get(),
            'testimonials' => Testimonial::query()->active()->latest()->get(),
        ]);
    }
}
