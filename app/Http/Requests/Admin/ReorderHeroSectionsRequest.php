<?php

namespace App\Http\Requests\Admin;

class ReorderHeroSectionsRequest extends AdminFormRequest
{

    public function rules(): array
    {
        return [
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:hero_sections,id'],
        ];
    }
}
