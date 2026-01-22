<?php

namespace App\Models\Local\Products;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    public function sections() {
        return $this->hasMany(Section::class, 'product_id', 'id');
    }
}
