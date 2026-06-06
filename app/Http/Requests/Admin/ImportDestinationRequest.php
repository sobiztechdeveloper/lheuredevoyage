<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class ImportDestinationRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return [
            'dataset' => ['required', Rule::in(array_keys(config('destinations.import_mappings', [])))],
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ];
    }
}
