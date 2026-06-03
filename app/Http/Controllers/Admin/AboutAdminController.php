<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutAdminController extends Controller
{
    public function edit(): View
    {
        $about = About::query()->first() ?? new About;

        return view('admin.about.edit', compact('about'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'image_primary' => ['nullable', 'string', 'max:255'],
            'image_secondary' => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        About::query()->updateOrCreate(['id' => 1], $data);

        return redirect()->route('admin.about.edit')->with('success', 'About page updated.');
    }
}
