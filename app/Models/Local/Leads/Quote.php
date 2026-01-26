<?php

namespace App\Models\Local\Leads;

use App\Models\CRM\Lead;
use App\Models\Local\Leads\Discount;
use App\Models\Local\Products\Product;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $connection = 'mysql';

    protected $table = 'lead_quote';

    protected $fillable = ['lead_id', 'product_id', 'extras', 'total_price'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function lead()
    {
        return $this->hasOne(Lead::class, 'id', 'id');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class, 'quote_id');
    }

    public function extras()
    {
        return json_decode($this->extras);
    }

    public function buildPricingTable()
    {
        $lineItems = [];

        // 1. Cleanly join extra names using a collection
        $extrasString = collect($this->extras())->pluck('name')->implode(', ');

        // 2. Calculate final price (ensure it doesn't go below 0)
        $discountedPrice = max(0, $this->total_price - $this->discounts->sum('amount'));

        switch ($this->product_id) {
            case 1:
                $lineItems[] = [
                    'options' => [
                        'optional' => false,
                        'optional_selected' => false,
                        'qty_editable' => false
                    ],
                    'data' => [
                        'name' => 'Insulation Installation',
                        'description' => 'Installation of new insulation and removal & disposal of existing insulation.' .
                            ($extrasString ? PHP_EOL . PHP_EOL . 'This includes: ' . $extrasString : ''),
                        'price' => $discountedPrice,
                        'qty' => 1,
                    ],
                ];
                break;

            case 2:
                $lineItems[] = [
                    'options' => [
                        'optional' => false,
                        'optional_selected' => false,
                        'qty_editable' => false
                    ],
                    'data' => [
                        'name' => 'Foil Installation',
                        'description' => 'Installation of new foil insulation.' .
                            ($extrasString ? PHP_EOL . PHP_EOL . 'This includes: ' . $extrasString : ''),
                        'price' => $discountedPrice,
                        'qty' => 1,
                    ],
                ];
                break;

                // You can add more cases here as your product line expands
        }

        return $lineItems;
    }
}
