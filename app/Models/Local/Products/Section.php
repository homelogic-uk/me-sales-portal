<?php

namespace App\Models\Local\Products;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'product_section';

    public function options() {
        return $this->hasMany(Option::class, 'section_id', 'id');
    }
}
