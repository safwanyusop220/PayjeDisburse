<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceiverRequest extends FormRequest
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
            'name'                  => ['nullable', 'string'],
            'identification_number' => ['nullable', 'string'],
            'address'               => ['nullable', 'string'],
            'phone_number'          => ['nullable', 'string'],
            'email'                 => ['nullable', 'string'],
            'program_id'            => ['nullable', 'integer'],
            'bank'                  => ['nullable', 'integer'],
            'account_number'        => ['nullable', 'string'],
        ];
    }
}
