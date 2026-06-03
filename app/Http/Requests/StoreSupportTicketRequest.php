<?php

namespace App\Http\Requests;

use App\Models\SupportTicket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupportTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:200'],
            'category' => ['required', Rule::in(SupportTicket::CATEGORIES)],
            'priority' => ['nullable', Rule::in(SupportTicket::PRIORITIES)],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }
}
