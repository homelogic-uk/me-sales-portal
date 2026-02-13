@extends('layouts.master')

@section('content')
<section class="max-w-5xl mx-auto py-8 px-4 sm:px-6">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Your Appointments</h2>
                <p class="text-xs text-gray-500 mt-1">Manage your upcoming appointments.</p>
            </div>
        </div>

        <div class="p-6">
            @if($leads->isEmpty())

            <div class="text-center py-12 px-4 rounded-lg border-2 border-dashed border-gray-200 bg-gray-50">
                <h3 class="text-sm font-medium text-gray-900">No appointments scheduled</h3>
            </div>

            @else

            @foreach($leads as $date => $dailyLeads)

            {{-- Date header --}}
            <h3 class="text-sm font-semibold text-gray-700 mb-3 mt-6 first:mt-0">
                {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
            </h3>

            <div class="overflow-hidden rounded-lg border border-gray-200 mb-6">
                <ul class="divide-y divide-gray-200">

                    @foreach($dailyLeads as $lead)
                    <li class="hover:bg-gray-50 transition-colors">
                        <a href="{{ route('leads.view', ['id' => $lead->id]) }}" class="block p-4 sm:px-6">
                            <div class="flex items-center justify-between">

                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm">
                                        {{ substr($lead->name ?? 'U', 0, 1) }}
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-blue-600 truncate">
                                            {{ $lead->name ?? '-' }} {{ $lead->surname ?? '-' }}
                                        </p>

                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1">

                                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>
                                                    {{ $lead->survey_dt->format('h:i A') }}
                                                </span>
                                            </div>

                                            @if($lead->postcode)
                                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                                <span class="uppercase tracking-wide">
                                                    {{ $lead->postcode }}
                                                </span>
                                            </div>
                                            @endif

                                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                                <span class="uppercase tracking-wide">
                                                    {{ $lead->getProduct->name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </a>
                    </li>
                    @endforeach

                </ul>
            </div>

            @endforeach

            @endif
        </div>
    </div>
</section>
@endsection
