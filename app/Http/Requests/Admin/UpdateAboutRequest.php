<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAboutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'heading' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'image_primary' => ['nullable', 'string', 'max:255'],
            'image_secondary' => ['nullable', 'string', 'max:255'],
            'breadcrumb_image' => ['nullable', 'image', 'max:4096'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
