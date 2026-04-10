<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRefundRequest extends FormRequest
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
            'parentTransactionId' => 'required|string|max:64',
            'refundType' => 'string|in:REFERENCED,STANDALONE',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'Refund amount is required',
            'amount.integer' => 'Refund amount must be an integer in cents',
            'currency.required' => 'Currency code is required',
            'currency.size' => 'Currency code must be exactly 3 characters',
            'parentTransactionId.required' => 'Parent transaction ID is required',
        ];
    }
}
