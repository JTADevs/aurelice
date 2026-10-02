<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Minimum password length for administrator accounts.
     */
    public const int MIN_PASSWORD_LENGTH = 12;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Store logins in lowercase and treat an empty email as missing.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => Str::lower(trim((string) $this->input('username'))),
            'email' => filled($this->input('email')) ? trim((string) $this->input('email')) : null,
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
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9._-]+$/', Rule::unique('users', 'username')->ignore($this->route('user'))],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'password' => ['required', 'confirmed', Password::min(self::MIN_PASSWORD_LENGTH)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Podaj imię i nazwisko.',
            'name.max' => 'Imię i nazwisko może mieć maksymalnie :max znaków.',
            'username.required' => 'Podaj login.',
            'username.max' => 'Login może mieć maksymalnie :max znaków.',
            'username.regex' => 'Login może zawierać tylko litery bez polskich znaków, cyfry, kropki, myślniki i podkreślenia.',
            'username.unique' => 'Ten login jest już zajęty.',
            'email.email' => 'Podaj poprawny adres e-mail.',
            'email.max' => 'E-mail może mieć maksymalnie :max znaków.',
            'email.unique' => 'Ten e-mail jest już przypisany do innego konta.',
            'password.required' => 'Podaj hasło.',
            'password.confirmed' => 'Hasła nie są takie same.',
            'password.min' => 'Hasło musi mieć co najmniej :min znaków.',
            'current_password.required' => 'Podaj obecne hasło, aby ustawić nowe.',
            'current_password.current_password' => 'Obecne hasło jest nieprawidłowe.',
        ];
    }
}
