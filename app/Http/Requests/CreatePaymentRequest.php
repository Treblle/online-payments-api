<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
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
            'captureMethod' => 'in:NOW,LATER',
            'merchant.merchantCategoryCode' => 'required|string',
            'merchant.merchantSoftware.companyName' => 'string',
            'merchant.merchantSoftware.productName' => 'string',
            'merchant.merchantSoftware.version' => 'string',
            'paymentMethodType.card.accountNumber' => 'required_if:paymentMethodType.card,exists|string|min:13|max:19',
            'paymentMethodType.card.expiry.month' => 'required_if:paymentMethodType.card,exists|string|size:2',
            'paymentMethodType.card.expiry.year' => 'required_if:paymentMethodType.card,exists|string|size:4',
            'paymentMethodType.card.cvv' => 'string|min:3|max:4',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'request_id' => $this->header('request-id'),
            'merchant_id' => $this->header('merchant-id'),
        ]);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'Payment amount is required',
            'amount.integer' => 'Payment amount must be an integer in cents',
            'currency.required' => 'Currency code is required',
            'currency.size' => 'Currency code must be exactly 3 characters',
            'paymentMethodType.card.accountNumber.required_if' => 'Card number is required for card payments',
            'paymentMethodType.card.expiry.month.required_if' => 'Card expiry month is required',
            'paymentMethodType.card.expiry.year.required_if' => 'Card expiry year is required',
        ];
    }
}
