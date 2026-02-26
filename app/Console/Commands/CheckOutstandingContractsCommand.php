<?php

namespace App\Console\Commands;

use App\Mail\CustomerWelcomeMail;
use App\Models\Local\Leads\Document;
use App\Services\SigningService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckOutstandingContractsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'contracts:check-outstanding';

    /**
     * The console command description.
     */
    protected $description = 'Check pending document signatures and process completed contracts.';

    /**
     * Execute the console command.
     */
    public function handle(SigningService $signing)
    {
        $this->info('Starting outstanding contracts check...');

        // 1. Process in chunks to prevent memory crashes
        Document::with('lead')
            ->where('uploaded', 'N')
            ->chunkById(100, function ($documents) use ($signing) {
                foreach ($documents as $document) {
                    $this->processDocument($document, $signing);
                }
            });

        $this->info('Finished checking contracts.');
    }

    /**
     * Process an individual document.
     */
    private function processDocument(Document $document, SigningService $signing): void
    {
        $lead = $document->lead;

        if (! $lead) {
            $this->warn("Document {$document->id} has no associated lead.");
            return;
        }

        try {
            $status = $signing->getDocumentStatus($document->document_id)['status'];

            if ($status === 'document.completed') {
                $document->update([
                    'status' => 'document.completed',
                ]);

                $fileName = 'temp_contracts/' . Str::uuid() . '.pdf';
                $fileContent = $signing->downloadDocument($document->document_id);

                if (Storage::disk('local')->put($fileName, $fileContent)) {

                    // 3. Fix the capitalization bug
                    $formattedName = Str::title("{$lead->name} {$lead->surname}");

                    // 4. Send synchronously ONLY if you must delete immediately
                    Mail::to($lead->email)
                        ->bcc('info@myenergy.co.uk')
                        ->send(new CustomerWelcomeMail($formattedName, $fileName));

                    // 5. Delete after sending
                    Storage::disk('local')->delete($fileName);
                }
            }
        } catch (\Exception $e) {
            $this->error("Failed to process document {$document->id}: " . $e->getMessage());
        }
    }
}
