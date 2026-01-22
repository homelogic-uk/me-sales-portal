@extends('layouts.master')

@section('content')
<section class="max-w-6xl mx-auto py-8 px-4 sm:px-6">

    {{-- Back navigation --}}
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('dashboard.index') }}"
            class="flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Appointments
        </a>
        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium uppercase tracking-wide">
            Lead #{{ $lead->id }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- LEFT COLUMN --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Lead Card --}}
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
                            <a href="mailto:{{ $lead->email }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors">
                                {{ $lead->email ?? 'Not provided' }}
                            </a>
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
        </div>

        {{-- RIGHT COLUMN: Quotes --}}
        <div class="lg:col-span-2">
            <div class="flex flex-col h-full bg-white rounded-xl shadow border border-gray-100 overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800">Quotes</h2>
                </div>

                <div class="p-6 flex-1 flex flex-col space-y-6">
                    @forelse ($lead->quotes ?? [] as $quote)
                    <div class="relative flex bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-200 p-6">
                        {{-- Accent strip --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-xl bg-blue-500"></div>

                        <div class="flex-1 ml-4">
                            {{-- Header: Product + Date + Delete --}}
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $quote->product->name }}</h3>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">{{ $quote->created_at->format('d M Y H:i:s') }}</span>

                                    {{-- Delete button --}}
                                    <form action="" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" name="delete_quote" value="{{ $quote->id }}"
                                            class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full shadow-sm flex items-center justify-center transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Extras as pill badges --}}
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach (json_decode($quote->extras) as $extra)
                                <span class="bg-blue-50 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                    {{ $extra->name }}
                                </span>
                                @endforeach
                            </div>

                            <hr class="border-gray-200 mb-4">

                            {{-- Total --}}
                            <div class="flex justify-between items-center font-semibold text-gray-900 text-base">
                                <span>Total</span>
                                <span>£{{ number_format($quote->total_price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">No quotes available yet.</p>
                    @endforelse

                    {{-- Continue to Contract button --}}
                    <hr class="border-t border-gray-100 my-6">

                    <div class="flex justify-end">
                        @if($lead->quotes->count() === 1)
                        <a href="{{ route('leads.contract.details', ['id' => $lead->id]) }}"
                            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-6 rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span class="mr-2">Continue to Contract</span>
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px; height:16px; display:inline-block;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        @else
                        <div class="flex items-center p-4 text-amber-800 border border-amber-200 rounded-lg bg-amber-50 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px; margin-right:12px; flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="text-xs">
                                You can only start a contract for one product at a time, remove the additional products and try again.
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
</section>
@endsection