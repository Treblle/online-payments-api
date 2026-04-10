<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCaptureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->hasHeader('merchant-id') && $this->hasHeader('request-id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|integer|min:1',
            'currency' => 'required|string|size:3',
            'finalCapture' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'Capture amount is required',
            'amount.integer' => 'Capture amount must be an integer in cents',
            'currency.required' => 'Currency code is required',
            'currency.size' => 'Currency code must be exactly 3 characters',
        ];
    }
}
