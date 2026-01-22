<?php

namespace App\Models\CRM;

use App\Models\Local\Leads\Quote;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $connection = 'mysql-crm';
    protected $table = 'leads';

    public $timestamps = false;

    public function quotes()
    {
        return $this->hasMany(Quote::class, 'lead_id', 'id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch',
        'product',
        'agent',
        'rep',
        'marketing_manager',
        'sales_manager',
        'field_sales_manager',
        'dt',
        'survey_dt',
        'appointment_dt',
        'appointment_type',
        'survey_confirmed',
        'lead_confirmed',
        'source',
        'status',
        'input_method',
        'lead_index',
        // 'price_net',
        'title',
        'name',
        'surname',
        'city',
        'postcode',
        'address_line_1',
        'address_line_2',
        'email',
        'phone_landline',
        'phone_mobile',
        'installation_city',
        'installation_postcode',
        'installation_address_line_1',
        'installation_address_line_2',
        'invoice_city',
        'invoice_postcode',
        'invoice_address_line_1',
        'invoice_address_line_2',
        'epc_installation',
        'epc_client',
        'survey_q1',
        'survey_q2',
        'survey_q3',
        'survey_q4',
        'survey_q5',
        'survey_q6',
        'survey_q7',
        'survey_q8',
        'survey_q9',
        'survey_q10',
        'survey_q11',
        'survey_q12',
        'survey_q13',
        'survey_q14',
        'vendor_id',
        'data_source',
        'call_id',
        'call_recording',
        'prop_type',
        'conf_owner',
        'age_range',
        'app_type',
        'discount_code',
        'client_signature',
        'contact_by',
        'optin_id',
        'optin_date',
        'optin_phone',
        'optin_email',
        'xero_ref',
        'employment_status',
        'is_invalid',
        'trust_pilot',
        'voucher',
        'voucher_sent',
        'outcome',
        'deal_meterage',
        'deal_amount',
        'manager_note',
        'blow_out_reason',
        'return_reason',
        'last_call',
        'is_priority',
        'is_hotkey',
        'flg_id',
        'external_lead_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'survey_dt' => 'datetime',
        ];
    }
}
