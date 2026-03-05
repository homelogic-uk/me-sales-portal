<?php

namespace App\Http\Controllers;

use App\Mail\CustomerWelcomeMail;
use App\Models\Local\Leads\Document;
use App\Services\SigningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExternalController extends Controller
{
    #region Signing
    public function signing(Request $request, SigningService $signingService, $documentId)
    {
        $document = Document::where('uuid', $documentId)
            ->where('status', '!=', 'document.completed')
            ->firstOrFail();

        $lead = $document->lead;

        if (!$lead)
            abort(404);

        $sessionId = null;
        $cacheKey = $lead->id . '_signing_session_' . $documentId;

        if (!$sessionId = Cache::get($cacheKey)) {
            if ($session = $signingService->createSession($document->document_id, $lead->email, 3600)) {
                Cache::put($cacheKey, $session['id'], 3600);
                $sessionId = $session['id'];
            }
        }

        $signingUrl = route('external.signing', ['documentId' => $document->uuid]);

        return view('contract.signing', compact('lead', 'document', 'sessionId', 'signingUrl'));
    }

    public function complete(Request $request, SigningService $signingService, $documentId)
    {
        // 1. Use a more concise query or route model binding
        $document = Document::where('uuid', $documentId)
            ->where('status', '!=', 'document.completed')
            ->firstOrFail();

        $lead = $document->lead;

        if (!$lead)
            abort(404);

        if ($document->status == 'document.completed')
            abort(404);

        $document->update(['status' => 'document.completed', 'mail_sent' => 'Y']);

        $fileName = Str::uuid() . '.pdf';

        if (Storage::disk('local')->put($fileName, $signingService->downloadDocument($document->document_id))) {
            Mail::to($lead->email)
                ->bcc('info@myenergy.co.uk')
                ->send(new CustomerWelcomeMail(strtolower(ucwords($lead->name . ' ' . $lead->surname)), $fileName));
        }

        return view('contract.complete', compact('lead'));
    }
    #endregion
}
