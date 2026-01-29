<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mysql-crm';
    protected $table = 'products';
}
