@extends('layouts.master')

@section('content')
<section class="max-w-6xl mx-auto py-8 px-4 sm:px-6">

    {{-- Back navigation --}}
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('dashboard.index') }}"
            class="flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Appointments
        </a>

        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium uppercase tracking-wide">
            Lead #{{ $lead->id }}
        </span>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT COLUMN --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Lead Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-lg">
                        {{ substr($lead->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900 leading-tight">
                            {{ $lead->name }} {{ $lead->surname }}
                        </h1>
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
                            <a href="mailto:{{ $lead->email }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">
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
                            <p class="text-sm font-medium text-gray-900 mt-0.5 leading-tight">
                                {{ $lead->address_line_1 }}<br>
                                {{ $lead->city }}<br>
                                <span class="uppercase">{{ $lead->postcode }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product Summary --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wide mb-2">Selected Product</p>
                <h2 class="text-lg font-semibold text-gray-900 mb-1">{{ $product->name }}</h2>
                <p class="text-sm text-gray-600">{{ $product->description }}</p>
            </div>
        </div>

        {{-- RIGHT COLUMN: Quote Builder --}}
        <div class="lg:col-span-2">
            <form action="" method="POST" id="quoteForm" data-base-price="{{ $product->base_price ?? 0 }}">
                @csrf
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                    {{-- Header --}}
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-lg font-semibold text-gray-800">Quote Builder</h2>
                    </div>

                    {{-- Body --}}
                    <div class="p-6 space-y-8">

                        {{-- Dynamic Sections --}}
                        @foreach ($product->sections as $section)
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold uppercase text-gray-600 mb-6">{{ $section->name }}</h3>

                            @if($section->options->isEmpty())
                            <div class="rounded-lg border-2 border-dashed border-gray-200 p-6 text-center bg-gray-50/50">
                                <p class="text-sm font-medium text-gray-500">No options available</p>
                                <p class="text-xs text-gray-400">Standard features apply.</p>
                            </div>
                            @else
                            @foreach($section->options as $option)
                            @switch($option->type)

                            {{-- HIDDEN --}}
                            @case('HIDDEN')
                            <input type="hidden" name="options[{{ $option->id }}]" value="1" class="calc-input" data-price="{{ $option->base_cost }}">
                            @break

                            {{-- NUMBER --}}
                            @case('NUMBER')
                            <div class="input-group mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $option->name }}</label>
                                <div class="flex items-center gap-3">
                                    <button type="button" class="btn-control btn-minus w-10 h-10 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-bold">−</button>
                                    <input type="number" name="options[{{ $option->id }}]" value="0" min="0" step="1"
                                        class="calc-input flex-1 text-center p-3 border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        data-price="{{ $option->base_cost }}">
                                    <button type="button" class="btn-control btn-plus w-10 h-10 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-bold">+</button>
                                </div>
                            </div>
                            @break

                            {{-- RANGE --}}
                            @case('RANGE')
                            <div class="input-group mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $option->name }}</label>
                                <div class="flex items-center gap-3">
                                    <button type="button" class="btn-control range-minus w-10 h-10 bg-gray-200 rounded-lg font-bold">−</button>
                                    <input type="range" name="options[{{ $option->id }}]" min="{{ $option->min_value ?? 0 }}" max="{{ $option->max_value ?? 100 }}" step="1"
                                        value="{{ $option->default_value ?? 0 }}"
                                        class="calc-input flex-1 accent-blue-600"
                                        data-price="{{ $option->base_cost }}">
                                    <button type="button" class="btn-control range-plus w-10 h-10 bg-gray-200 rounded-lg font-bold">+</button>
                                    <span class="value-display w-10 text-center font-semibold">{{ $option->default_value ?? 0 }}</span>
                                </div>
                            </div>
                            @break

                            {{-- CHECKBOX --}}
                            @case('CHECKBOX')
                            <label class="block mb-6 cursor-pointer group relative">
                                <input type="checkbox" name="options[{{ $option->id }}]" value="1"
                                    class="calc-input absolute opacity-0 w-0 h-0"
                                    data-price="{{ $option->base_cost }}">
                                <div class="card-ui flex items-center gap-4 p-4 border border-gray-200 rounded-xl transition">
                                    <div class="icon-unchecked w-5 h-5 border-2 border-gray-300 rounded"></div>
                                    <div class="icon-checked w-5 h-5 bg-blue-600 rounded hidden items-center justify-center text-white">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $option->name }}</p>
                                        <p class="text-xs text-gray-500">Adds £{{ number_format($option->base_cost,2) }}</p>
                                    </div>
                                </div>
                            </label>
                            @break

                            {{-- RADIO --}}
                            @case('RADIO')
                            <div class="mb-6">
                                <label class="block text-sm font-bold uppercase text-gray-600 mb-4">{{ $option->name }}</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($option->choices as $i => $choice)
                                    <label class="cursor-pointer group relative">
                                        <input type="radio" name="options[{{ $option->id }}]" value="{{ $choice['label'] }}" class="calc-input absolute opacity-0 w-0 h-0"
                                            data-price="{{ $choice['price'] }}" {{ $i===0?'checked':'' }}>
                                        <div class="card-ui flex items-center gap-4 p-4 border border-gray-200 rounded-xl transition">
                                            <div class="icon-unchecked w-4 h-4 border-2 border-gray-300 rounded-full"></div>
                                            <div class="icon-checked w-4 h-4 bg-blue-600 rounded-full hidden"></div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $choice['label'] }}</p>
                                                <p class="text-xs text-gray-500">{{ $choice['price']>0?'+£'.$choice['price']:'Included' }}</p>
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @break

                            @endswitch
                            @endforeach
                            @endif
                        </div>
                        @if(!$loop->last)
                        <hr class="border-t border-gray-100 my-4">
                        @endif
                        @endforeach

                        {{-- TOTAL PRICE --}}
                        <div id="priceContainer" class="p-4 bg-blue-50 border border-blue-100 rounded-lg transition transform duration-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-blue-700">Estimated Total</p>
                                </div>
                                <p id="priceTotal" class="text-2xl font-bold text-blue-700 leading-tight">£0.00</p>
                            </div>
                        </div>



                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center gap-3">
                        <a href="{{ route('dashboard.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Cancel</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-6 rounded-lg shadow-sm transition-colors">Save Quote</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</section>

{{-- CARD STYLES --}}
<style>
    .calc-input:checked+.card-ui {
        background: #EFF6FF;
        border-color: #BFDBFE;
    }

    .calc-input:checked+.card-ui .icon-unchecked {
        display: none;
    }

    .calc-input:checked+.card-ui .icon-checked {
        display: flex;
    }
</style>

{{-- JS --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById('quoteForm');
        const totalEl = document.getElementById("priceTotal");
        const priceBox = document.getElementById("priceContainer");

        let lastTotal = 0;
        const basePrice = parseFloat(form.dataset.basePrice) || 0;
        let holdInterval = null;

        function changeValue(input, dir) {
            let v = parseFloat(input.value) || 0;
            const min = parseFloat(input.min) || 0;
            const max = parseFloat(input.max) || 1000;
            const step = parseFloat(input.step) || 1;
            input.value = Math.min(Math.max(v + dir * step, min), max);
            input.dispatchEvent(new Event('input', {
                bubbles: true
            }));
        }

        function startHold(btn, dir) {
            const input = btn.closest('.flex').querySelector('.calc-input');
            changeValue(input, dir);
            holdInterval = setInterval(() => changeValue(input, dir), 150);
        }

        function stopHold() {
            clearInterval(holdInterval);
        }

        form.addEventListener('pointerdown', e => {
            const btn = e.target.closest('.btn-control');
            if (!btn) return;
            e.preventDefault();
            startHold(btn, btn.classList.contains('btn-plus') || btn.classList.contains('range-plus') ? 1 : -1);
        });
        form.addEventListener('pointerup', stopHold);
        form.addEventListener('pointerleave', stopHold);
        form.addEventListener('pointercancel', stopHold);

        form.addEventListener('input', e => {
            if (!e.target.classList.contains('calc-input')) return;
            const display = e.target.closest('.flex')?.querySelector('.value-display');
            if (display) display.textContent = e.target.value;
            updatePrice();
        });

        function updatePrice() {
            let total = basePrice;
            form.querySelectorAll('.calc-input').forEach(input => {
                const price = parseFloat(input.dataset.price) || 0;
                if (input.type === 'checkbox' || input.type === 'radio') {
                    if (input.checked) total += price;
                } else total += (parseFloat(input.value) || 0) * price;
            });
            if (total !== lastTotal) {
                priceBox.classList.add('ring-2', 'ring-blue-400', 'scale-102');
                setTimeout(() => priceBox.classList.remove('ring-2', 'ring-blue-400', 'scale-102'), 150);
                animate(totalEl, lastTotal, total, 400);
                lastTotal = total;
            }
        }

        function animate(el, start, end, duration) {
            let startTime = null;

            function step(ts) {
                if (!startTime) startTime = ts;
                const progress = Math.min((ts - startTime) / duration, 1);
                const value = start + (end - start) * progress;
                el.textContent = '£' + value.toLocaleString('en-GB', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                if (progress < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        }

        // --- CALCULATE PRICE IMMEDIATELY ON PAGE LOAD ---
        updatePrice();
    });
</script>
@endsection
