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

    public function getFullName()
    {
        return $this->user_name . ' ' . $this->user_surname;
    }

    public function leads()
    {
        // hasMany(RelatedModel, foreignKey, localKey)
        return $this->hasMany(Lead::class, 'rep', 'user_id')
            ->whereDate('survey_dt', '>=', Carbon::today()->subDays(2));
    }

    public function scopedLeads()
    {
        $days = 3;
        $cutoffDate = now()->subDays($days)->toDateString();

        // Case 1: ROOT - Access to everything
        if ($this->user_group === 'ROOT') {
            return Lead::whereDate('survey_dt', '>=', $cutoffDate);
        }

        // Case 2: FIELD_SALES_MANAGER - Self + Team
        if ($this->user_group === 'FIELD_SALES_MANAGER') {
            return Lead::whereDate('survey_dt', '>=', $cutoffDate)
                ->whereIn('rep', function ($query) {
                    $query->select('user_id')
                        ->from('users')
                        ->where('team_leader_id', $this->id) // Leads for their team
                        ->orWhere('user_id', $this->user_id); // Leads for themselves
                });
        }

        // Default: Return only their personal leads (the standard relationship)
        return $this->leads();
    }
}
