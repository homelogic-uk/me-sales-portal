@extends('layouts.master')

@section('content')
{{-- Prevents modal flashing on page load --}}
<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<section class="max-w-6xl mx-auto py-8 px-4 sm:px-6" x-data="{ discountModalOpen: false, activeQuoteId: {{ old('quote_id') ?? 'null' }}, activeQuotePrice: 0 }">

    {{-- Error Alerts --}}
    @if($errors->has('error'))
    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-sm rounded-r-lg flex items-center">
        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <p class="text-sm font-semibold leading-relaxed">{{ $errors->first('error') }}</p>
    </div>
    @endif

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
                        <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-xl bg-blue-500"></div>

                        <div class="flex-1 ml-4">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $quote->product->name }}</h3>
                                    <span class="text-xs text-gray-400">{{ $quote->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    {{-- STATIC HIGH-CONTRAST BUTTON --}}
                                    @if(!($quote->discounts->sum('amount') > 0))
                                    <button type="button"
                                        @click="discountModalOpen = true; activeQuoteId = {{ $quote->id }}; activeQuotePrice = {{ $quote->total_price }}"
                                        class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg shadow-sm transition-colors focus:outline-none"
                                        title="Apply Discount">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        ADD DISCOUNT
                                    </button>
                                    @else
                                    <button type="button"
                                        @click="discountModalOpen = true; activeQuoteId = {{ $quote->id }}; activeQuotePrice = {{ $quote->total_price }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold rounded-md border border-gray-300 transition-colors">
                                        Edit Discount
                                    </button>
                                    @endif

                                    {{-- Delete button --}}
                                    <form action="" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" name="delete_quote" value="{{ $quote->id }}"
                                            class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition-colors"
                                            onclick="return confirm('Are you sure you want to delete this quote?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach (json_decode($quote->extras) as $extra)
                                <span class="bg-blue-50 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full border border-blue-100">
                                    {{ $extra->name }}
                                </span>
                                @endforeach
                            </div>

                            <hr class="border-gray-100 mb-4">

                            {{-- Pricing Breakdown --}}
                            <div class="space-y-1 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                @if($quote->discounts->sum('amount') > 0)
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span>Subtotal</span>
                                    <span>£{{ number_format($quote->total_price, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm text-red-600 font-semibold">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        <span>Discount</span>
                                        <form action="{{ route('leads.quote.removeDiscount', ['id' => $lead->id]) }}" method="POST" class="ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="quote_id" value="{{ $quote->id }}">
                                            <button type="submit" class="text-[10px] text-red-400 hover:text-red-700 underline uppercase font-bold tracking-tight">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                    <span>-£{{ number_format($quote->discounts->sum('amount'), 2) }}</span>
                                </div>
                                @endif

                                <div class="flex justify-between items-center font-bold text-gray-900 text-xl pt-1">
                                    <span>Total Price</span>
                                    <div class="text-right">
                                        @if($quote->discounts->sum('amount') > 0)
                                        <span class="text-xs text-gray-400 line-through block font-normal">£{{ number_format($quote->total_price, 2) }}</span>
                                        @endif
                                        <span class="text-blue-600">£{{ number_format($quote->total_price - ($quote->discounts->sum('amount') ?? 0), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-sm italic">No quotes available yet.</p>
                    </div>
                    @endforelse

                    {{-- Bottom UI Elements --}}
                    <hr class="border-t border-gray-100 my-6">
                    <div class="flex justify-end">
                        @if($lead->quotes->count() === 1)
                        <a href="{{ route('leads.contract.details', ['id' => $lead->id]) }}"
                            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-3 px-8 rounded-lg shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:-translate-y-0.5">
                            <span class="mr-2">Continue to Contract</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        @else
                        <div class="flex items-center p-4 text-amber-800 border border-amber-200 rounded-lg bg-amber-50 w-full shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="text-xs font-semibold uppercase tracking-tight">You can only start a contract for one product at a time.</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Discount Modal --}}
    <div x-show="discountModalOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100">

        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all"
            @click.away="discountModalOpen = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900">Apply Discount</h3>
                <button @click="discountModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('leads.quote.addDiscount', ['id' => $lead->id]) }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="quote_id" :value="activeQuoteId">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Current Total: <span class="text-gray-900 font-bold">£<span x-text="activeQuotePrice.toFixed(2)"></span></span>
                    </label>
                    <div class="relative group">
                        <span class="absolute left-4 top-3 text-gray-500 font-bold">£</span>
                        <input type="number" step="0.01" name="discount_amount" required autofocus
                            class="block w-full pl-8 pr-4 py-3 text-lg border {{ $errors->has('discount_amount') ? 'border-red-500' : 'border-gray-200' }} rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
                            placeholder="0.00" value="{{ old('discount_amount') }}">
                    </div>
                    <p class="mt-2 text-[11px] text-gray-500">Enter the amount to deduct from the total price.</p>
                    @error('discount_amount')
                    <p class="mt-2 text-xs text-red-600 font-medium italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" name="apply_discount" class="w-full py-3 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-md transition-all">
                        Confirm Discount
                    </button>
                    <button type="button" @click="discountModalOpen = false" class="w-full py-3 text-sm font-medium text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
