<?php

namespace App\Http\Requests\Admin;

class UpdateAboutRequest extends AdminFormRequest
{

    public function rules(): array
    {
        return [
            'heading' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'image_primary' => ['nullable', 'image', 'max:4096'],
            'image_secondary' => ['nullable', 'image', 'max:4096'],
            'breadcrumb_image' => ['nullable', 'image', 'max:4096'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
