<?php

namespace App\Http\Controllers;

use App\Models\CRM\Lead;
use App\Models\Local\Leads\Discount;
use App\Models\Local\Leads\Quote;
use App\Models\Local\Products\Option;
use App\Models\Local\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Helper\QuestionHelper;

class QuoteController extends Controller
{
    public function create(Request $request, $id, $product)
    {
        $lead = Auth::user()->leads->where('id', $id)->firstOrFail();

        if (!$lead)
            abort(404);

        $product = Product::with('sections')
            ->where('id', $product)
            ->where('enabled', 'Y')
            ->first();

        if (!$product)
            abort(404);

        if ($request->isMethod('POST')) {
            $options = $request->options;
            $totalPrice = $product->base_price;

            $extras = [];

            foreach ($request->options as $key => $value) {
                $option = Option::find($key);
                $optionPrice = 0;

                if ($option) {
                    $amount = 1;

                    if (in_array($option->type, ['RANGE', 'NUMBER'])) {
                        $optionPrice = $value * $option->base_cost;
                        $amount = $value;
                    }

                    if ($option->type == 'CHECKBOX')
                        $optionPrice = $option->base_cost;

                    if ($option->name != 'Meterage') {
                        $extras[] = [
                            'name' => $option->name,
                            'price' => $optionPrice,
                            'amount' => $amount
                        ];
                    }
                }

                $totalPrice += $optionPrice;
            }

            $quote = Quote::create([
                'lead_id' => $lead->id,
                'product_id' => $product->id,
                'extras' => json_encode($extras),
                'total_price' => $totalPrice
            ]);

            return redirect()->route('leads.quote.view', ['id' => $lead->id]);
        }

        return view('quote.create', compact('lead', 'product'));
    }

    public function view(Request $request, $id)
    {
        $lead = Auth::user()->leads->where('id', $id)->firstOrFail();

        if (!$lead)
            abort(404);

        if (!$lead->quotes->count())
            return redirect()->route('leads.view', ['id' => $id]);

        if ($request->isMethod('DELETE')) {
            $quoteId = $request->delete_quote;

            if ($quoteId) {
                $lead->quotes()->where('id', $quoteId)->first()?->discounts()->delete();
                $lead->quotes()->where('id', $quoteId)->delete();
            }

            return redirect()->route('leads.quote.view', $lead->id);
        }

        return view('quote.view', compact('lead'));
    }

    public function addDiscount(Request $request, $id)
    {
        // 1. Use relationship chaining to ensure the quote belongs to the authorized user's lead
        $quote = Auth::user()->leads()->findOrFail($id)
            ->quotes()->findOrFail($request->quote_id);

        // 2. Validate input properly
        $request->validate([
            'discount_amount' => 'required|numeric|min:0.01'
        ]);

        $newTotalDiscount = $quote->discounts()->sum('amount') + $request->discount_amount;

        // 3. Guard clause for business logic
        if ($newTotalDiscount > $quote->total_price) {
            return back()->withErrors(['error' => 'Total discount cannot exceed the quote price.']);
        }

        // 4. Create record via relationship
        $quote->discounts()->create([
            'user_id' => Auth::id(),
            'amount'  => $request->discount_amount,
        ]);

        return back()->with('success', 'Discount applied.');
    }

    public function removeDiscount(Request $request, $id)
    {
        // Ensure the user owns the lead/quote before deleting
        $quote = Auth::user()->leads()->findOrFail($id)
            ->quotes()->findOrFail($request->quote_id);

        $quote->discounts()->delete();

        return back()->with('success', 'Discounts removed.');
    }
}
