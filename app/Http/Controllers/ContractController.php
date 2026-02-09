<?php

namespace App\Http\Controllers;

use App\Mail\CustomerWelcomeMail;
use App\Models\CRM\Lead;
use App\Models\Local\Leads\Document;
use App\Services\SigningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContractController extends Controller
{
    public function details(Request $request, SigningService $signingService, $id)
    {
        $lead = Auth::user()->leads->where('id', $id)->firstOrFail();

        if ($request->isMethod('put')) {
            // 1. Validation Logic
            $validated = $request->validate([
                'title'          => 'required|string|max:10',
                'name'           => 'required|string|max:255',
                'surname'        => 'required|string|max:255',
                'email'          => 'required|email|max:255',
                'phone_landline' => 'nullable|string|max:20',
                'phone_mobile'   => 'nullable|string|max:20',
                'address_line_1' => 'required|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'city'           => 'required|string|max:255',
                'postcode'       => 'required|string|max:10',
                'deposit_amount' => [
                    'required',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) use ($lead) {
                        $total = $lead->quotes->first()?->total_price ?? 0;
                        if ($value > $total) {
                            $fail('The deposit cannot be greater than the total price (£' . number_format($total, 2) . ').');
                        }
                    },
                ],
            ]);

            // 2. Sanitize and Update Request Data before Fill
            // Strip non-letters from start and convert to Title Case
            $cleanName = (string) str(preg_replace('/^[^a-zA-Z]+/', '', $request->name))->lower()->title();
            $cleanSurname = (string) str(preg_replace('/^[^a-zA-Z]+/', '', $request->surname))->lower()->title();

            // Merge back into the request so fill() picks up the clean versions
            $request->merge([
                'name' => $cleanName,
                'surname' => $cleanSurname,
            ]);

            // 3. Update Model
            // Now $request->name and $request->surname are clean
            $lead->fill(array_filter($request->except(['_method', '_token', 'deposit_amount']), fn($v) => filled($v)));

            // Ensure email is explicitly handled if it wasn't caught in the filter
            $lead->email = $request->email;

            if ($lead->isDirty() && !$lead->save()) {
                return back()->withErrors(['lead' => 'Unable to save lead data.']);
            }

            // 4. Build Pricing Table
            $quote = $lead->quotes->first();
            if (!$quote) abort(404);

            $lineItems = $quote->buildPricingTable();

            $lineItems[] = [
                'options' => ['optional' => false, 'optional_selected' => false, 'qty_editable' => false],
                'data' => [
                    'name' => 'Admin Fee',
                    'description' => '',
                    'price' => env('ADMIN_FEE', 250),
                    'qty' => 1,
                ],
            ];

            if ($request->deposit_amount > 0) {
                $lineItems[] = [
                    'options' => ['optional' => false, 'optional_selected' => false, 'qty_editable' => false],
                    'data' => [
                        'name' => 'Deposit',
                        'description' => 'Initial deposit payment',
                        'price' => -abs($request->deposit_amount),
                        'qty' => 1,
                    ],
                ];
            }

            // 5. Signing Service Data
            $fullName = "{$lead->name} {$lead->surname}";

            $fileData = [
                'template_uuid' => '2WTtXSJnTYTiJFVvXvt2P6',
                'name' => "MyEnergy - Contract - {$lead->surname}",
                'recipients' => [[
                    'email' => $lead->email,
                    'first_name' => $lead->name,
                    'last_name' => $lead->surname,
                    'role' => 'Client',
                    'signing_order' => 1
                ]],
                'fields' => [
                    "customer_name" => ["value" => $fullName]
                ],
                'tokens' => [
                    ["name" => "clientName", "value" => $fullName],
                    ["name" => "clientAddressLine1", "value" => (string) $lead->address_line_1],
                    ["name" => "clientCity", "value" => (string) $lead->city],
                    ["name" => "clientPostcode", "value" => (string) $lead->postcode]
                ],
                'pricing_tables' => [[
                    'name' => 'Contract Pricing',
                    'data_merge' => false,
                    'sections' => [[
                        'title' => 'Pricing',
                        'default' => true,
                        'rows' => $lineItems,
                    ]],
                ]]
            ];

            $document = $signingService->createDocument($fileData);

            if (isset($document['type']) && $document['type'] === 'validation_error') {
                return back()->withErrors(['api' => 'PandaDoc Error: ' . ($document['detail']['metadata'][0] ?? 'Check field formatting.')])->withInput();
            }

            if (!isset($document['id'])) {
                return back()->with('error', 'Document creation failed.')->withInput();
            }

            $doc = Document::create([
                'status' => $document['status'],
                'lead_id' => $lead->id,
                'document_id' => $document['id']
            ]);

            return redirect()->route('leads.contract.generate', ['id' => $lead->id, 'uuid' => $doc->uuid]);
        }

        return view('contract.details', compact('lead'));
    }

    public function generate(Request $request, $id, $documentId)
    {
        $lead = Auth::user()->leads->where('id', $id)->firstOrFail();
        $document = Document::where('lead_id', $id)
            ->where('uuid', $documentId)
            ->first();

        if (!$document)
            abort(404);

        return view('contract.generate', compact('lead', 'document'));
    }

    public function status(Request $request, SigningService $signingService, $id, $documentId)
    {
        // 1. Use a more concise query or route model binding
        $document = Document::where('lead_id', $id)
            ->where('uuid', $documentId)
            ->firstOrFail();

        $lead = Auth::user()->leads->where('id', $id)->firstOrFail();

        // 2. Fetch the status from the service
        $response = $signingService->getDocumentStatus($document->document_id);

        // Ensure the response contains the data we expect
        if (!isset($response['status'])) {
            return response()->json(['error' => 'Could not retrieve status'], 400);
        }

        $newStatus = $response['status'];

        // 3. Update local database if status changed
        if ($document->status !== $newStatus) {
            $document->update(['status' => $newStatus]);
        }

        // 4. Handle logic based on status
        return match ($newStatus) {
            'document.draft' => $this->handleDraftStatus($document, $signingService),
            'document.sent', 'document.viewed' => response()->json([
                'status' => 'ready',
                // Often, the signing URL is inside the API response if requested
                'signing_url' => route('leads.contract.signing', ['id' => $lead->id, 'uuid' => $documentId])
            ]),
            'document.completed' => response()->json(['status' => 'completed']),
            default => response()->json(['status' => 'waiting']),
        };
    }

    public function signing(Request $request, SigningService $signingService, $id, $documentId)
    {
        // 1. Use a more concise query or route model binding
        $document = Document::where('lead_id', $id)
            ->where('status', '!=', 'document.completed')
            ->where('uuid', $documentId)
            ->firstOrFail();

        $lead = Auth::user()->leads->where('id', $id)->firstOrFail();

        $sessionId = null;
        $cacheKey = $id . '_signing_session_' . $documentId;

        if (!$sessionId = Cache::get($cacheKey)) {
            if ($session = $signingService->createSession($document->document_id, $lead->email, 3600)) {
                Cache::put($cacheKey, $session['id'], 3600);
                $sessionId = $session['id'];
            }
        }

        return view('contract.signing', compact('lead', 'document', 'sessionId'));
    }

    public function complete(Request $request, SigningService $signingService, $id, $documentId)
    {
        // 1. Use a more concise query or route model binding
        $document = Document::where('lead_id', $id)
            ->where('status', '!=', 'document.completed')
            ->where('uuid', $documentId)
            ->firstOrFail();

        $lead = Auth::user()->leads->where('id', $id)->firstOrFail();

        if ($document->status == 'document.completed')
            abort(404);

        $document->update(['status' => 'document.completed']);

        $fileName = Str::uuid() . '.pdf';

        if (Storage::disk('local')->put($fileName, $signingService->downloadDocument($document->document_id))) {
            Mail::to($lead->email)
                ->bcc('info@logicfoam.com')
                ->send(new CustomerWelcomeMail(strtolower(ucwords($lead->name . ' ' . $lead->surname)), $fileName));
        }

        return view('contract.complete', compact('lead'));
    }

    /**
     * Helper to keep the main method clean
     */
    protected function handleDraftStatus($document, $signingService)
    {
        // Use the PandaDoc ID, not the local database ID
        $res = $signingService->sendDocument($document->document_id, '', '', true);

        return response()->json(['status' => 'sending']);
    }
}
