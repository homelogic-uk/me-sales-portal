<?php

namespace App\Models\Local\Leads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Models\CRM\Lead;

class Document extends Model
{
    protected $table = 'lead_documents';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'uuid',
        'status',
        'lead_id',
        'provider',
        'document_id',
        'uploaded',
    ];

    /**
     * The "booted" method of the model.
     * Automatically generates a UUID when a new record is created.
     */
    protected static function booted(): void
    {
        static::creating(function (Document $leadDocument) {
            $leadDocument->uuid = (string) Str::uuid();
        });
    }

    /**
     * Relationship: A document belongs to a Lead.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Scope to quickly find documents that have been uploaded.
     */
    public function scopeUploaded($query)
    {
        return $query->where('uploaded', 'Y');
    }
}
