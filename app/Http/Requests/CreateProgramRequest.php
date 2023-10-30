<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProgramRequest extends FormRequest
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
            'name'            => ['required', 'string'],
            'type_id'         => ['nullable', 'integer'],
            'allocation_rate' => ['nullable', 'integer'],
            'status'          => ['nullable', 'integer'],
            'latest_payment'  => ['nullable', 'string'],
            'payment_date'    => ['nullable', 'string'],
            'total_month'     => ['nullable', 'integer'],
            'total_year'      => ['nullable', 'integer'],
            'end_date'        => ['nullable', 'string'],
            'allocation'      => ['nullable', 'integer'],
            'frequency'       => ['nullable', 'integer'],
            'amount[]'        => ['nullable', 'string'],

            // 'installment.*.amount' => 'nullable|number|min:0'
        ];
    }
}
