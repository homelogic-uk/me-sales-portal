<?php

namespace App\Http\Controllers;

use App\Mail\ProductSurveyMail;
use App\Models\Local\Leads\Survey\Survey;
use App\Models\Local\Products\Product;
use App\Services\CRMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SurveyController extends Controller
{
    public function view(Request $request, CRMService $crm, $id, $productId)
    {
        $lead = Auth::user()->scopedLeads()->where('id', $id)->firstOrFail();
        $product = Product::where('id', $productId)->firstOrFail();

        if (!$product->survey->count())
            return redirect()->back();

        $questions = $product->survey;

        if ($request->isMethod('POST')) {

            if ($request->filled(['email_address', 'first_name', 'last_name'])) {
                $lead->update([
                    'name'    => $request->first_name,
                    'surname' => $request->last_name,
                    'email'   => $request->email_address,
                ]);
            }

            $survey = Survey::create([
                'lead_id' => $id,
                'product_id' => $product->id,
                'user_id' => Auth::user()->user_id,
                'answers' => $request->questions,
                'rep_signature' => $request->representative_signature,
                'client_signature' => $request->client_signature
            ]);

            if ($filePath = $survey->generate()) {
                Mail::to($lead->email)
                    ->bcc('info@myenergy.co.uk')
                    ->send(new ProductSurveyMail('', $filePath));

                $fileContent = Storage::disk('local')->get($filePath);

                if ($fileContent) {
                    $crm->uploadDocument($lead->id, -1, $fileContent, 'property-survey.pdf', 'DOCUMENT');
                }

                Storage::disk('local')->delete($filePath);
            }

            return redirect()->route('leads.quote.create', ['id' => $id, 'product' => $productId]);
        }

        return view('survey.view', compact('lead', 'questions'));
    }
}
