<?php

namespace App\Http\Controllers;

use App\Models\CRM\Lead;
use App\Models\Local\Products\Product;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function view(Request $request, $id)
    {
        $lead = Lead::where('id', $id)->first();

        if (!$lead)
            abort(404);

        $products = Product::where('enabled', 'Y')
            ->get();

        if ($request->isMethod('POST')) {
            $product = $request->product;

            if(!Product::where('enabled', 'Y')->where('id', $product)->count())
                abort(404);

            return redirect()->route('leads.quote.create', ['id' => $id, 'product' => $product]);
        }

        return view('leads.view', compact('lead', 'products'));
    }
}
