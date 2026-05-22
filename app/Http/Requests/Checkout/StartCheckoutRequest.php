<?php

namespace App\Http\Requests\Checkout;

use App\Enums\DeliveryMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StartCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name'       => ['required', 'string', 'max:120'],
            'email'           => ['required', 'email', 'max:120'],
            'phone'           => ['required', 'regex:/^\+[0-9]{12}$/'],
            'street'          => ['required', 'string', 'max:150'],
            'city'            => ['required', 'string', 'max:100'],
            'postal_code'     => ['required', 'integer', 'digits_between:1,7'],
            'country'         => ['required', 'string', 'size:2'],
            'delivery_method' => ['required', Rule::enum(DeliveryMethod::class)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'country'         => strtoupper((string) $this->input('country')),
            'phone'           => preg_replace('/\s+/', '', (string) $this->input('phone')),
            'delivery_method' => (string) $this->input('delivery_method'),
        ]);
    }
}
