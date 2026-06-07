<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdateAirlineRequest extends AdminFormRequest
{
    public function rules(): array
    {
        $airline = $this->route('airline');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:8', 'alpha_num'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('airlines', 'slug')->ignore($airline)],
            'logo' => ['nullable', 'image', 'max:2048'],
            'aliases' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'remove_logo' => ['nullable', 'boolean'],
        ];
    }
}
