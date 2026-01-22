<?php

namespace App\Models\Local\Leads;

use App\Models\CRM\Lead;
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

    public function buildPricingTable()
    {
        $lineItems = [];

        switch ($this->product_id) {
            case 1: {
                    $lineItems[] = [
                        'options' => ['optional' => false, 'optional_selected' => false, 'qty_editable' => false],
                        'data' => [
                            'name' => 'Insulation Installation',
                            'description' => 'Installation of new insulation and removal & disposal of existing insulation.',
                            'price' => ($this->total_price ?: 0),
                            'qty' => 1,
                        ],
                    ];

                    break;
                }
        }

        // foreach (json_decode($this->extras) as $extra) {
        //     $lineItems[] = [
        //         'options' => ['optional' => false, 'optional_selected' => false, 'qty_editable' => false],
        //         'data' => [
        //             'name' => $extra->name,
        //             'description' => '',
        //             'price' => $extra->price / $extra->amount,
        //             'qty' => intval($extra->amount),
        //         ],
        //     ];
        // }

        // // dd(json_encode($lineItems));
        // dd($lineItems);

        return $lineItems;
    }
}
