@extends('layouts.master')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }

    .input-error {
        animation: shake 0.2s ease-in-out 0s 2;
    }

    .step-content {
        display: none;
        animation: fadeIn 0.3s ease-in-out;
    }

    .step-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Compact Signature Styles */
    .signature-wrapper {
        position: relative;
        width: 100%;
        height: 140px;
        background-color: #fdfdfd;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        touch-action: none;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    canvas {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        cursor: crosshair;
        z-index: 10;
    }

    .signature-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d1d5db;
        font-size: 0.875rem;
        pointer-events: none;
        z-index: 1;
        transition: opacity 0.3s ease; /* Ensures smooth fading */
    }
</style>

@php
    $questionsPerPage = 1;
    $questionChunks = $questions->chunk($questionsPerPage);
    // +2 to account for the Client Details Step AND the Final Signature Step
    $totalSteps = $questionChunks->count() + 2;
@endphp

<section class="max-w-3xl mx-auto py-8 px-4 sm:px-6 overscroll-none">

    {{-- Back Navigation --}}
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ url()->previous() }}" class="flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    {{-- Global Error Alert --}}
    @if ($errors->any())
    <div class="mb-8 flex items-center p-4 text-red-800 border border-red-200 rounded-xl bg-red-50 shadow-sm" role="alert">
        <div class="flex-shrink-0 bg-red-100 p-2 rounded-lg">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-4 text-sm font-medium">
            <span class="font-bold">Error:</span> Please check your inputs and signatures.
        </div>
    </div>
    @endif

    <form method="POST" id="surveyForm" class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden relative">
        @csrf

        {{-- Progress Header --}}
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Property Survey</h2>
                    <p class="text-sm text-gray-500 mt-1" id="step-description">Step 1 of {{ $totalSteps }}</p>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase tracking-wide" id="step-counter">0%</span>
            </div>
            <div class="relative mt-4 h-1.5 w-full bg-gray-200 rounded overflow-hidden">
                <div id="progress-bar" class="h-full bg-blue-600 transition-all duration-300" style="width: 0%;"></div>
            </div>
        </div>

        <div class="p-6 sm:p-8">

            {{-- STEP 1: Client Details --}}
            <div id="step-1" class="step-content active">
                <div>
                    <label class="block text-lg sm:text-xl font-bold text-gray-800 mb-6 leading-relaxed">
                        <span class="text-blue-600 mr-2">1.</span> Please provide the clients details
                    </label>

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- First Name --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">First Name</label>
                                <input type="text" name="first_name" required placeholder="e.g. Jane" value="{{ old('first_name') }}" class="w-full rounded-xl border border-gray-300 px-5 py-3 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm @error('first_name') input-error border-red-500 @enderror">
                            </div>

                            {{-- Last Name --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Last Name</label>
                                <input type="text" name="last_name" required placeholder="e.g. Doe" value="{{ old('last_name') }}" class="w-full rounded-xl border border-gray-300 px-5 py-3 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm @error('last_name') input-error border-red-500 @enderror">
                            </div>
                        </div>

                        {{-- Email Address --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="email_address" required placeholder="client@example.com" value="{{ old('email_address') }}" class="w-full rounded-xl border border-gray-300 px-5 py-3 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm @error('email_address') input-error border-red-500 @enderror">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dynamic Question Steps (Offset by +2) --}}
            @foreach($questionChunks as $stepIndex => $chunk)
                <div id="step-{{ $stepIndex + 2 }}" class="step-content">
                    @foreach($chunk as $question)
                        <div class="@error('questions.'.$question->id.'.answer') input-error @enderror">
                            <label class="block text-lg sm:text-xl font-bold text-gray-800 mb-6 leading-relaxed">
                                <span class="text-blue-600 mr-2">{{ $stepIndex + 2 }}.</span> {{ $question->question_text }}
                            </label>

                            <input type="hidden" name="questions[{{ $question->id }}][question]" value="{{ $question->question_text }}">

                            @if($question->question_type === 'radio' || $question->question_type === 'dropdown')
                                <div class="space-y-3">
                                    @foreach($question->answers as $answer)
                                        <label class="flex items-start p-4 border border-gray-200 bg-white rounded-xl hover:bg-blue-50 cursor-pointer transition-all has-[:checked]:border-blue-500 shadow-sm">
                                            <input type="radio" name="questions[{{ $question->id }}][answer]" value="{{ $answer->answer_text }}" class="mt-0.5 h-4 w-4 text-blue-600" required>
                                            <span class="ml-3 text-sm text-gray-700 font-medium">{{ $answer->answer_text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($question->question_type === 'text')
                                <input type="text" name="questions[{{ $question->id }}][answer]" required class="w-full rounded-xl border border-gray-300 px-5 py-4 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach

            {{-- FINAL STEP: SIGNATURES --}}
            <div id="step-{{ $totalSteps }}" class="step-content">
                <div class="text-center mb-8">
                    <h3 class="text-xl font-bold text-gray-800">Final Confirmation</h3>
                    <p class="text-sm text-gray-500">Sign in the boxes below to complete the survey.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Client Signature</label>
                        <div class="signature-wrapper" id="client-pad-container">
                            <div class="signature-placeholder" id="client-placeholder">Sign Here</div>
                            <canvas id="client-canvas"></canvas>
                        </div>
                        <div class="flex justify-between">
                            <input type="hidden" name="client_signature" id="client_signature_base64">
                            <button type="button" class="text-[10px] text-red-500 font-bold uppercase clear-sig" data-target="client">Clear</button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Representative Signature</label>
                        <div class="signature-wrapper" id="rep-pad-container">
                            <div class="signature-placeholder" id="rep-placeholder">Sign Here</div>
                            <canvas id="rep-canvas"></canvas>
                        </div>
                        <div class="flex justify-between">
                            <input type="hidden" name="representative_signature" id="rep_signature_base64">
                            <button type="button" class="text-[10px] text-red-500 font-bold uppercase clear-sig" data-target="rep">Clear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <button type="button" id="prevBtn" class="hidden text-gray-600 font-bold text-sm px-4 py-2">
                &larr; Previous
            </button>
            <div class="flex-1"></div>
            <button type="button" id="nextBtn" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-10 rounded-xl shadow transition-all">
                Next &rarr;
            </button>
            <button type="submit" id="submitBtn" class="hidden bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-10 rounded-xl shadow items-center gap-2">
                <span>Complete Survey</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </button>
        </div>
    </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = {{ $totalSteps }};
    const form = document.getElementById('surveyForm');

    // Initialize Signature Pads
    const clientCanvas = document.getElementById('client-canvas');
    const repCanvas = document.getElementById('rep-canvas');

    const clientPad = new SignaturePad(clientCanvas, { backgroundColor: 'rgba(255, 255, 255, 0)' });
    const repPad = new SignaturePad(repCanvas, { backgroundColor: 'rgba(255, 255, 255, 0)' });

    clientPad.addEventListener("beginStroke", () => {
        document.getElementById('client-placeholder').style.opacity = '0';
    });
    repPad.addEventListener("beginStroke", () => {
        document.getElementById('rep-placeholder').style.opacity = '0';
    });

    function resizeCanvas() {
        [clientCanvas, repCanvas].forEach(canvas => {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const parent = canvas.parentElement;
            canvas.width = parent.offsetWidth * ratio;
            canvas.height = parent.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        });
        clientPad.clear();
        repPad.clear();
        document.querySelectorAll('.signature-placeholder').forEach(p => p.style.opacity = '1');
    }

    window.addEventListener("resize", resizeCanvas);

    function updateUI() {
        document.querySelectorAll('.step-content').forEach((el, index) => {
            el.classList.toggle('active', (index + 1) === currentStep);
        });

        const percentage = Math.round((currentStep / totalSteps) * 100);
        document.getElementById('progress-bar').style.width = `${percentage}%`;
        document.getElementById('step-counter').innerText = `${percentage}%`;
        document.getElementById('step-description').innerText = currentStep === totalSteps ? 'Final Step: Signatures' : `Step ${currentStep} of ${totalSteps}`;

        document.getElementById('prevBtn').style.display = currentStep === 1 ? 'none' : 'block';

        if (currentStep === totalSteps) {
            document.getElementById('nextBtn').style.display = 'none';
            document.getElementById('submitBtn').style.display = 'flex';
            setTimeout(resizeCanvas, 100);
        } else {
            document.getElementById('nextBtn').style.display = 'block';
            document.getElementById('submitBtn').style.display = 'none';
        }
    }

    document.getElementById('nextBtn').addEventListener('click', () => {
        if (validateStep()) {
            currentStep++;
            updateUI();
        }
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
        currentStep--;
        updateUI();
    });

    function validateStep() {
        const activeStep = document.querySelector('.step-content.active');
        const inputs = activeStep.querySelectorAll('[required]');
        for (let input of inputs) {
            if (!input.checkValidity()) {
                input.reportValidity();
                return false;
            }
        }
        return true;
    }

    document.querySelectorAll('.clear-sig').forEach(btn => {
        btn.addEventListener('click', () => {
            if(btn.dataset.target === 'client') {
                clientPad.clear();
                document.getElementById('client-placeholder').style.opacity = '1';
            } else {
                repPad.clear();
                document.getElementById('rep-placeholder').style.opacity = '1';
            }
        });
    });

    form.addEventListener('submit', (e) => {
        if (clientPad.isEmpty() || repPad.isEmpty()) {
            e.preventDefault();
            alert("Please provide both signatures.");
            return;
        }

        document.getElementById('client_signature_base64').value = clientPad.toDataURL();
        document.getElementById('rep_signature_base64').value = repPad.toDataURL();

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span>Saving...</span>`;
    });

    updateUI();
});
</script>
@endsection
