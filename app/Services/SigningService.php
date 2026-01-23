<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class SigningService
{
    protected string $baseUrl = 'https://api.pandadoc.com/public/v1';

    /**
     * Common headers for PandaDoc API requests.
     */
    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'API-Key ' . config('services.pandadoc.api_key'),
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Get the current status and details of a document.
     */
    public function getDocumentStatus(string $documentId): array
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/documents/{$documentId}");

        return $this->handleResponse($response);
    }

    /**
     * Create a document from a template.
     */
    public function createDocument(array $documentData): array
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

        return $this->handleResponse($response);
    }

    /**
     * Send the created document to recipients.
     */
    public function sendDocument(string $documentId, string $subject = '', string $message = '', bool $silent = false): array
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/documents/{$documentId}/send", [
                'subject' => $subject,
                'message' => $message,
                'silent'  => $silent,
            ]);

        return $this->handleResponse($response);
    }

    /**
     * Creates a signing session for a specific recipient.
     */
    public function createSession(string $documentId, string $recipientEmail, int $lifetime = 3600): ?array
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/documents/{$documentId}/session", [
                'recipient' => $recipientEmail,
                'lifetime'  => $lifetime,
            ]);

        return $response->successful() ? $response->json() : null;
    }

    /**
     * Download the completed document PDF.
     * Note: Document must be in "document.completed" status.
     */
    public function downloadDocument(string $documentId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/documents/{$documentId}/download");

        if ($response->failed()) {
            return null;
        }

        return $response->body(); // Returns the raw PDF binary
    }

    /**
     * Internal helper to standardize response formatting.
     */
    protected function handleResponse(Response $response): array
    {
        if ($response->failed()) {
            return [
                'success' => false,
                'status'  => $response->status(),
                'error'   => $response->json('detail') ?? $response->json('message') ?? 'API Request Failed',
            ];
        }

        return array_merge(['success' => true], $response->json() ?? []);
    }
}
