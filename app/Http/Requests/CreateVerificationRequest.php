<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVerificationRequest extends FormRequest
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
            'currency' => 'required|string|size:3',
            'paymentMethodType' => 'required|array',
            'paymentMethodType.card.accountNumber' => 'required_with:paymentMethodType.card|string|min:13|max:19',
            'paymentMethodType.card.expiry.month' => 'required_with:paymentMethodType.card|string|size:2',
            'paymentMethodType.card.expiry.year' => 'required_with:paymentMethodType.card|string|size:4',
            'paymentMethodType.card.cvv' => 'string|min:3|max:4',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'currency.required' => 'Currency code is required',
            'currency.size' => 'Currency code must be exactly 3 characters',
            'paymentMethodType.required' => 'Payment method type is required',
            'paymentMethodType.card.accountNumber.required_with' => 'Card number is required for card verifications',
            'paymentMethodType.card.expiry.month.required_with' => 'Card expiry month is required',
            'paymentMethodType.card.expiry.year.required_with' => 'Card expiry year is required',
        ];
    }
}
