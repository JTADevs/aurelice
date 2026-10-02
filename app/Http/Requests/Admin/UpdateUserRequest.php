<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends StoreUserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * The password is optional; changing your own password requires the current one.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'password' => ['nullable', 'confirmed', Password::min(self::MIN_PASSWORD_LENGTH)],
            'current_password' => [
                Rule::requiredIf($this->isChangingOwnPassword()),
                'nullable',
                'current_password',
            ],
        ];
    }

    /**
     * Determine whether the signed-in administrator is changing their own password.
     */
    private function isChangingOwnPassword(): bool
    {
        return filled($this->input('password')) && $this->route('user')?->is($this->user());
    }
}
