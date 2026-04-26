<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class StartCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'street' => ['required', 'string', 'max:150'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:30'],
            'country' => ['required', 'string', 'size:2'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'country' => strtoupper((string) $this->input('country')),
        ]);
    }
}

