<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCard implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) return;

        $digits = preg_replace('/\D/', '', $value);

        if (strlen($digits) < 13) {
            $fail('The :attribute is too short.');
            return;
        }

        $sum = 0;
        $alternate = false;
        for ($i = strlen($digits) - 1; $i >= 0; $i--) {
            $n = (int) $digits[$i];
            if ($alternate) {
                $n *= 2;
                if ($n > 9) $n -= 9;
            }
            $sum += $n;
            $alternate = !$alternate;
        }

        if ($sum % 10 !== 0) {
            $fail('The :attribute is invalid.');
        }
    }
}
