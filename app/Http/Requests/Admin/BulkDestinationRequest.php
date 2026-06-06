<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class BulkDestinationRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return [
            'action' => ['required', Rule::in(['activate', 'deactivate', 'delete'])],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:travel_destinations,id'],
        ];
    }
}
