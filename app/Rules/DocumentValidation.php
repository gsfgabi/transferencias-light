<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DocumentValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!\App\Models\User::isValidDocument($value)) {
            $fail('O :attribute não é um CPF ou CNPJ válido.');
        }
    }
}

