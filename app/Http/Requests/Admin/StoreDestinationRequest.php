<?php

namespace App\Http\Requests\Admin;

use App\Enums\DestinationType;
use Illuminate\Validation\Rule;

class StoreDestinationRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(array_column(DestinationType::cases(), 'value'))],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:travel_destinations,slug'],
            'code' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('travel_destinations', 'code')->where(fn ($q) => $q->where('type', $this->input('type'))),
            ],
            'country' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'region' => ['nullable', 'string', 'max:120'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
