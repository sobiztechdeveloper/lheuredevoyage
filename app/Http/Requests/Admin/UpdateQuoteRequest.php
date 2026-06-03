<?php

namespace App\Http\Requests\Admin;

class UpdateQuoteRequest extends StoreQuoteRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['status'] = ['required', \Illuminate\Validation\Rule::in(\App\Models\Quote::STATUSES)];
        $rules['valid_until'] = ['required', 'date'];

        return $rules;
    }
}
