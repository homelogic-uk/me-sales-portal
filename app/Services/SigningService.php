<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SigningService
{
    protected string $baseUrl = 'https://api.pandadoc.com/public/v1';

    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'API-Key ' . config('services.pandadoc.api_key'),
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Get the current status and details of a document.
     * * @param string $documentId The UUID of the document
     * @return array
     */
    public function getDocumentStatus(string $documentId): array
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/documents/{$documentId}");

        if ($response->failed()) {
            return [
                'success' => false,
                'status'  => $response->status(),
                'error'   => $response->json('get.error') ?? 'Failed to fetch status',
            ];
        }

        return $response->json();
    }

    public function createDocument($documentData)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/documents", [
                'name'           => $documentData['name'] ?? 'New Document',
                'template_uuid'  => $documentData['template_uuid'],
                'recipients'     => $documentData['recipients'],
                'tokens'         => $documentData['tokens'] ?? [],
                'fields'         => $documentData['fields'] ?? [],
                'pricing_tables' => $documentData['pricing_tables'] ?? [],
                'metadata'       => (object)($documentData['metadata'] ?? []),
            ]);

        return $response->json();
    }

    public function sendDocument(string $documentId, string $subject = '', string $message = '', bool $silent = false)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/documents/{$documentId}/send", [
                'subject' => $subject,
                'message' => $message,
                'silent'  => $silent,
            ]);

        // dd($response->json());

        if ($response->failed()) {
            return [
                'success' => false,
                'status'  => $response->status(),
                'message' => $response->body(),
            ];
        }

        return $response->json();
    }

    /**
     * Creates a signing session for a specific recipient.
     * * @param string $documentId The PandaDoc document UUID
     * @param string $recipientEmail The email of the recipient who will sign
     * @param int $lifetime Session lifetime in seconds (default 3600)
     */
    public function createSession(string $documentId, string $recipientEmail, int $lifetime = 3600)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/documents/{$documentId}/session", [
                'recipient' => $recipientEmail,
                'lifetime'  => $lifetime,
            ]);

        if ($response->failed()) {
            return null;
        }

        // This returns an object containing 'id' and 'session_view_url'
        return $response->json();
    }
}
