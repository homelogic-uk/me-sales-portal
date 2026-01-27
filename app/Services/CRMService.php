<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class CRMService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = 'https://crmhm.myenergy.co.uk/api/';
        $this->token = '2apxtkPN7E2Rg3A4Zx8AAttEjgDRxUsTE3jsHypzUqHTfsEpEm8DC';
    }

    /**
     * Upload a document to the CRM.
     */
    public function uploadDocument(
        int|string $leadId,
        int|string $repId,
        string $document,
        string $fileName,
        string $fileType,
        int $clientId = -1
    ): string|bool|array {
        $response = $this->client()->asForm()->post('uploadDocument', [
            'token'     => $this->token,
            'lead_id'   => $leadId,
            'rep_id'    => $repId,
            'document'  => $document,
            'file_name' => $fileName,
            'client_id' => $clientId,
            'type'      => $fileType,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return false;
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
