@extends('layouts.master')

@section('content')
<section class="max-w-6xl mx-auto py-8 px-4 sm:px-6">

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('dashboard.index') }}" class="flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Appointments
        </a>
        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium uppercase tracking-wide">
            Lead #{{ $lead->id }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @if($lead->quotes->count())
        <div class="col-span-3 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10 10 0 0012 2z" />
                </svg>
                <span class="text-sm text-yellow-700 font-medium">
                    You already have a quotation saved.
                </span>
            </div>
            <a href="{{ route('leads.quote.view', ['id' => $lead->id]) }}" class="text-sm font-semibold text-yellow-800 hover:text-yellow-900 underline">
                View
            </a>
        </div>
        @endif

        <div class="lg:col-span-1 space-y-6">

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-lg">
                        {{ substr($lead->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900 leading-tight">{{ $lead->name }} {{ $lead->surname }}</h1>
                        <p class="text-sm text-gray-500">Customer</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Email</p>
                            <a href="mailto:{{ $lead->email }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors">{{ $lead->email ?? 'Not provided' }}</a>
                        </div>
                    </div>

                    <hr class="border-gray-100 my-4">

                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Property Address</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">
                                {{ $lead->address_line_1 }}<br>
                                {{ $lead->city }}<br>
                                <span class="uppercase">{{ $lead->postcode }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 rounded-xl border border-blue-100 p-4 flex items-center gap-4">
                <div class="bg-white p-2 rounded-lg shadow-sm text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-blue-600 font-bold uppercase tracking-wide">Appointment</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $lead->survey_dt->format('l, M d • h:i A') }}</p>
                </div>
            </div>

        </div>

        <div class="lg:col-span-2">
            <form action="" method="POST">
                @csrf
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-lg font-semibold text-gray-800">Products</h2>
                    </div>

                    <div class="p-6 space-y-8">

                        <div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($products as $product)
                                <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-blue-400 transition-all peer-checked:ring-2 peer-checked:ring-blue-500">
                                    <input type="radio" name="product" value="{{ $product->id }}" class="peer sr-only" required>
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-gray-900">{{ $product->name }}</span>
                                            <span class="mt-1 flex items-center text-xs text-gray-500">{{ $product->description }}</span>
                                        </span>
                                    </span>
                                    <svg class="h-5 w-5 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent peer-checked:border-blue-500" aria-hidden="true"></span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center gap-3">
                        <a href="{{ route('dashboard.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Cancel</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-6 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Create Quote
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </div>
</section>
@endsection
