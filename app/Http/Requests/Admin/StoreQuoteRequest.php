<?php

namespace App\Http\Requests\Admin;

use App\Models\Quote;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['nullable', 'exists:users,id'],
            'flight_booking_request_id' => ['nullable', 'exists:flight_booking_requests,id'],
            'quote_type' => ['required', Rule::in(Quote::TYPES)],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'currency' => ['required', 'string', 'max:10'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'service_fee' => ['nullable', 'numeric', 'min:0'],
            'valid_until' => ['required', 'date', 'after_or_equal:today'],
            'status' => ['nullable', Rule::in(Quote::STATUSES)],
            'notes' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string', 'max:2000'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
