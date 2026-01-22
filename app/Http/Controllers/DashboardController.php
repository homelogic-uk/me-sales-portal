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
            ->leads()
            ->orderByRaw('DATE(survey_dt) DESC') // date descending
            ->orderBy('survey_dt', 'asc')        // time ascending (within date)
            ->get()
            ->groupBy(function ($lead) {
                return $lead->survey_dt->format('Y-m-d');
            });

        return view('dashboard.index', compact('leads'));
    }
}
