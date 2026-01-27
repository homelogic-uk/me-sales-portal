<?php

namespace App\Console\Commands;

use App\Models\Local\Leads\Document;
use App\Services\CRMService;
use App\Services\SigningService;
use Illuminate\Console\Command;
use Exception;

class UploadContractsCommand extends Command
{
    protected $signature = 'app:upload-contracts'; // Simplified signature
    protected $description = 'Uploads pending completed contracts to the CRM';

    public function __construct(
        protected SigningService $signingService,
        protected CRMService $crmService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $documents = Document::where('status', 'document.completed')
            ->where('uploaded', 'N')
            ->get();

        if ($documents->isEmpty()) {
            $this->info('No pending documents found.');
            return;
        }

        $this->info("Found {$documents->count()} documents to upload.");
        $bar = $this->output->createProgressBar($documents->count());

        foreach ($documents as $document) {
            try {
                $documentData = $this->signingService->downloadDocument($document->document_id);

                if (!$documentData) {
                    $this->error("\nCould not download document ID: {$document->document_id}");
                    continue;
                }

                $res = $this->crmService->uploadDocument(
                    $document->lead_id,
                    -1,
                    $documentData,
                    'contract.pdf',
                    'ORDER',
                    -1
                );

                // Check for your specific success response
                if (isset($res['success']) && $res['success'] === true) {
                    $document->update(['uploaded' => 'Y']);
                } else {
                    $this->error("\nCRM failed to accept document ID: {$document->document_id}");
                }
            } catch (Exception $e) {
                $this->error("\nError processing document {$document->id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Upload process complete.');
    }
}
