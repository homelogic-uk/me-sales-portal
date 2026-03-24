<?php

namespace App\Models\Local\Products;

use App\Models\Local\Leads\Survey\Question;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    public function sections()
    {
        return $this->hasMany(Section::class, 'product_id', 'id');
    }

    public function survey()
    {
        return $this->hasMany(Question::class, 'product_id', 'id');
    }
}
