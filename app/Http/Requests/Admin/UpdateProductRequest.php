<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateProductRequest extends StoreProductRequest
{
    /**
     * Normalize the price; the slug stays unchanged after the product is created.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'price' => str_replace([',', ' '], ['.', ''], (string) $this->input('price')),
            'is_published' => $this->boolean('is_published'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            ...$this->productRules(),
        ];
    }
}
