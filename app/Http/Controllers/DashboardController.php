<?php

namespace App\Http\Controllers;

use App\Models\CRM\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Define the start date (3 days ago)
        $startDate = Carbon::today()->subDays(3);

        // 2. Define the end date (Normally +1 day, but if Friday, +3 days to include Monday)
        $endDate = Carbon::today()->isFriday()
            ? Carbon::today()->addDays(3)
            : Carbon::today()->addDay();

        $leads = $request->user()
            ->leads()
            ->whereBetween('survey_dt', [$startDate, $endDate->endOfDay()]) // Filter the range
            ->orderByRaw('DATE(survey_dt) DESC')
            ->orderBy('survey_dt', 'asc')
            ->get()
            ->groupBy(function ($lead) {
                return $lead->survey_dt->format('Y-m-d');
            });

        return view('dashboard.index', compact('leads'));
    }
}
