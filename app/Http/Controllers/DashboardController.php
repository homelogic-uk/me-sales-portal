<?php

namespace App\Http\Controllers;

use App\Models\CRM\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $leads = $request->user()
            ->scopedLeads()
            ->orderByRaw('DATE(survey_dt) DESC')
            ->orderBy('survey_dt', 'asc')
            ->get()
            ->groupBy(function ($lead) {
                return $lead->survey_dt->format('Y-m-d');
            });

        return view('dashboard.index', compact('leads'));
    }
}
