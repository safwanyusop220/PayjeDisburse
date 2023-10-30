<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAllocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'allocation_source' => ['required', 'string'],
            'total_allocation' => ['required', 'decimal:2'],
            'reject_reason' => ['string', 'max:255'],
            'bank' => ['integer', 'max:255'],
            'account_number' => ['string', 'max:255'],

        ];
    }
}
