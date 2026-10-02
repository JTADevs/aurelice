<?php

namespace App\Http\Requests\Admin;

use App\Enums\ProductCategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Maximum number of images uploaded in a single request.
     */
    public const int MAX_IMAGES = 10;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize the price to a dot decimal separator and derive the slug from the name.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'price' => str_replace([',', ' '], ['.', ''], (string) $this->input('price')),
            'is_published' => $this->boolean('is_published'),
            'slug' => Str::slug((string) $this->input('name')),
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
            'slug' => ['required', Rule::unique('products', 'slug')],
            ...$this->productRules(),
        ];
    }

    /**
     * Get the rules shared by creating and updating a product.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    protected function productRules(): array
    {
        return [
            'category' => ['required', Rule::enum(ProductCategory::class)],
            'price' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:100000'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_published' => ['boolean'],
            'images' => ['array', 'max:'.self::MAX_IMAGES],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ];
    }

    /**
     * Get the price converted to grosze.
     */
    public function priceInGrosze(): int
    {
        return (int) round((float) $this->validated('price') * 100);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Podaj nazwę produktu.',
            'name.max' => 'Nazwa może mieć maksymalnie :max znaków.',
            'slug.required' => 'Nazwa musi zawierać litery lub cyfry.',
            'slug.unique' => 'Produkt o takiej nazwie już istnieje.',
            'category.required' => 'Wybierz kategorię.',
            'category.enum' => 'Wybierz kategorię z listy.',
            'price.required' => 'Podaj cenę.',
            'price.numeric' => 'Cena musi być liczbą, np. 249 lub 249,99.',
            'price.decimal' => 'Cena może mieć maksymalnie 2 miejsca po przecinku.',
            'price.min' => 'Cena nie może być ujemna.',
            'price.max' => 'Cena nie może przekraczać :max zł.',
            'description.max' => 'Opis może mieć maksymalnie :max znaków.',
            'images.max' => 'Możesz dodać naraz maksymalnie :max zdjęć.',
            'images.*.image' => 'Plik musi być zdjęciem.',
            'images.*.mimes' => 'Dozwolone formaty zdjęć: JPG, PNG, WebP.',
            'images.*.max' => 'Zdjęcie może mieć maksymalnie 10 MB.',
            'images.*.uploaded' => 'Nie udało się przesłać zdjęcia. Sprawdź jego rozmiar.',
        ];
    }
}
