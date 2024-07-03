<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\ValidationRule;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $value,
            // 'remoteip' => \request()->ip()
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            if (!$responseData['success']) {
                $fail("The {$attribute} is invalid");
            }
        } else {
            $fail("Something went wrong");
        }
    }
}
