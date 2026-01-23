<?php

namespace App\Models\Local\Leads;

use App\Models\Local\Leads\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'lead_quote_discounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quote_id',
        'user_id',
        'amount',
    ];

    /**
     * Get the quote that owns the discount.
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Get the user who applied the discount.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
