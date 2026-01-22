<?php

namespace App\Models;

use App\Models\CRM\Lead;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CRMUser extends Authenticatable
{
    protected $connection = 'mysql-crm';

    protected $table = 'users'; // change if different

    protected $primaryKey = 'user_id';

    public $timestamps = false; // only if your table has no timestamps

    protected $fillable = [
        'user_email',
        'user_password',
    ];

    protected $hidden = [
        'user_password',
    ];

    /**
     * Tell Laravel which column is the password
     */
    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function leads()
    {
        // hasMany(RelatedModel, foreignKey, localKey)
        return $this->hasMany(Lead::class, 'rep', 'user_id')
            ->whereDate('survey_dt', '>=', Carbon::today()->subDays(2));
    }
}
