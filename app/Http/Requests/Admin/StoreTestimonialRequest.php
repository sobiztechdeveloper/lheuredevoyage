<?php

namespace App\Http\Requests\Admin;

class StoreTestimonialRequest extends AdminFormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'review' => ['required', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'max:2048'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
