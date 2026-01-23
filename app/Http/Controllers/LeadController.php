<?php

namespace App\Http\Controllers;

use App\Models\CRM\Lead;
use App\Models\Local\Leads\Document;
use App\Models\Local\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function view(Request $request, $id)
    {
        $liveSigningDocument = null;
        $lead = Auth::user()->leads->where('id', $id)->firstOrFail();

        if (!$lead)
            abort(404);

        $products = Product::where('enabled', 'Y')
            ->get();

        if ($liveSigningDocument = Document::where('lead_id', $id)->where('status', '!=', 'document.completed')->first()) {
            return redirect()->route('leads.contract.generate', ['id' => $lead->id, 'uuid' => $liveSigningDocument->uuid]);
        }

        if ($request->isMethod('POST')) {
            $product = $request->product;

            if (!Product::where('enabled', 'Y')->where('id', $product)->count())
                abort(404);

            return redirect()->route('leads.quote.create', ['id' => $id, 'product' => $product]);
        }

        return view('leads.view', compact('lead', 'products'));
    }
}
