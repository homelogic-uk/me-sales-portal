<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EmailValidationService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = 'https://api.quickemailverification.com/v1/';
        $this->token = config('services.email.api_key');
    }

    /**
     * Checks whether an email is valid
     */
    public function checkEmail($emailAddress)
    {
        $request = $this->client()->get('verify', [
            'email' => $emailAddress,
            'apikey' => $this->token
        ]);

        if ($request->successful()) {
            return in_array($request->json('result'), ['valid', 'unknown']);
        }

        return true;
    }

    /**
     * Base HTTP configuration.
     */
    protected function client()
    {
        return Http::baseUrl($this->baseUrl)
            ->withOptions([
                'verify' => false, // Note: In production, try to avoid disabling SSL verification
            ])
            ->acceptJson();
    }
}
