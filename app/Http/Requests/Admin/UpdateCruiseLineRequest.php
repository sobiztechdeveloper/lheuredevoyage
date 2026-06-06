<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdateCruiseLineRequest extends AdminFormRequest
{
    public function rules(): array
    {
        $line = $this->route('cruise_line');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('cruise_lines', 'slug')->ignore($line)],
            'logo' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'remove_logo' => ['nullable', 'boolean'],
        ];
    }
}
