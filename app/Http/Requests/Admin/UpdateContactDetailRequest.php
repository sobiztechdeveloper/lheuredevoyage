<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'google_map_embed' => ['nullable', 'string', 'max:5000'],
            'whatsapp_number' => ['nullable', 'string', 'max:50'],
            'breadcrumb_image' => ['nullable', 'image', 'max:4096'],
            'form_title' => ['nullable', 'string', 'max:255'],
            'form_subtitle' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
