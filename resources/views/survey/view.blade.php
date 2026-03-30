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
        border: 2px solid #ef4444 !important;
        border-radius: 0.75rem;
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

    .signature-wrapper {
        position: relative;
        width: 100%;
        height: 140px;
        background-color: #fdfdfd;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        touch-action: none;
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
    }
</style>

@php
    $questionsPerPage = 1;
    $questionChunks = $questions->chunk($questionsPerPage);
    $totalSteps = $questionChunks->count() + 2;
@endphp

<section class="max-w-3xl mx-auto py-8 px-4 sm:px-6">

    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ url()->previous() }}" class="flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Back to Dashboard
        </a>
    </div>

    {{-- How to use this survey form --}}
    <div class="mb-8 p-6 bg-blue-50 border border-blue-100 rounded-xl shadow-sm">
        <h3 class="text-blue-900 font-bold mb-3 flex items-center text-base">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            How to use this survey form
        </h3>
        <ul class="text-blue-800 text-sm space-y-2 ml-1">
            <li class="flex items-start"><span class="mr-2">•</span> Read the question first.</li>
            <li class="flex items-start"><span class="mr-2">•</span> Read the guidance underneath before choosing an answer.</li>
            <li class="flex items-start"><span class="mr-2">•</span> Use the option descriptions to understand what each answer really means on-site.</li>
            <li class="flex items-start font-semibold text-blue-900"><span class="mr-2">•</span> Do not guess any answers.</li>
        </ul>
    </div>

    @if ($errors->any())
    <div class="mb-8 p-4 text-red-800 border border-red-200 rounded-xl bg-red-50 flex items-center">
        <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
        <span class="font-medium">Please check all required fields and signatures.</span>
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
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold" id="step-counter">0%</span>
            </div>
            <div class="relative mt-4 h-1.5 w-full bg-gray-200 rounded overflow-hidden">
                <div id="progress-bar" class="h-full bg-blue-600 transition-all duration-300" style="width: 0%;"></div>
            </div>
        </div>

        <div class="p-6 sm:p-8">

            {{-- STEP 1: Client Details --}}
            <div id="step-1" class="step-content active">
                <div class="question-container space-y-5">
                    <label class="block text-lg sm:text-xl font-bold text-gray-800 mb-6">
                        <span class="text-blue-600 mr-2">1.</span> Client Details
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <input type="text" name="first_name" required placeholder="First Name" value="{{ old('first_name') }}" class="w-full rounded-xl border border-gray-300 px-5 py-3 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                        <input type="text" name="last_name" required placeholder="Last Name" value="{{ old('last_name') }}" class="w-full rounded-xl border border-gray-300 px-5 py-3 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                    </div>
                    <input type="email" name="email_address" required placeholder="Email Address" value="{{ old('email_address') }}" class="w-full rounded-xl border border-gray-300 px-5 py-3 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                </div>
            </div>

            {{-- Dynamic Questions --}}
            @foreach($questionChunks as $stepIndex => $chunk)
                <div id="step-{{ $stepIndex + 2 }}" class="step-content">
                    @foreach($chunk as $question)
                        <div class="question-container">
                            {{-- Question Text --}}
                            <label class="block text-lg sm:text-xl font-bold text-gray-800 mb-2 leading-relaxed">
                                <span class="text-blue-600 mr-2">{{ $stepIndex + 2 }}.</span> {{ $question->question_text }}
                            </label>

                            {{-- Question Guidance --}}
                            @if(!empty($question->question_guidance))
                                <div class="text-sm text-gray-600 mb-6 leading-relaxed bg-gray-50 p-4 rounded-xl border-l-4 border-blue-200">
                                    {!! $question->question_guidance !!}
                                </div>
                            @endif

                            <input type="hidden" name="questions[{{ $question->id }}][question]" value="{{ $question->question_text }}">

                            @if($question->question_type === 'radio' || $question->question_type === 'checkbox')
                                <div class="space-y-3">
                                    @foreach($question->answers as $answer)
                                        <label class="flex flex-col p-4 border border-gray-200 bg-white rounded-xl hover:bg-blue-50 cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                            <div class="flex items-start">
                                                <input type="{{ $question->question_type }}"
                                                       name="questions[{{ $question->id }}][answer]{{ $question->question_type === 'checkbox' ? '[]' : '' }}"
                                                       value="{{ $answer->answer_text }}"
                                                       class="mt-1 h-4 w-4 text-blue-600 {{ $question->question_type === 'checkbox' ? 'rounded' : '' }}"
                                                       {{ $question->question_type === 'radio' ? 'required' : '' }}
                                                       {{ (is_array(old("questions.{$question->id}.answer")) && in_array($answer->answer_text, old("questions.{$question->id}.answer"))) || old("questions.{$question->id}.answer") == $answer->answer_text ? 'checked' : '' }}>

                                                <div class="ml-3 flex flex-col">
                                                    <span class="text-sm text-gray-700 font-bold">{{ $answer->answer_text }}</span>

                                                    {{-- Answer Guidance --}}
                                                    @if(!empty($answer->answer_guidance))
                                                        <span class="mt-1 text-xs text-gray-500 leading-normal">
                                                            {!! $answer->answer_guidance !!}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->question_type === 'dropdown')
                                <select name="questions[{{ $question->id }}][answer]" required class="w-full rounded-xl border border-gray-300 px-5 py-4 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm bg-white appearance-none">
                                    <option value="" disabled {{ old("questions.{$question->id}.answer") ? '' : 'selected' }}>Select an option...</option>
                                    @foreach($question->answers as $answer)
                                        <option value="{{ $answer->answer_text }}" {{ old("questions.{$question->id}.answer") == $answer->answer_text ? 'selected' : '' }}>{{ $answer->answer_text }}</option>
                                    @endforeach
                                </select>

                            @elseif($question->question_type === 'text')
                                <input type="text" name="questions[{{ $question->id }}][answer]" required value="{{ old("questions.{$question->id}.answer") }}" placeholder="Type your answer..." class="w-full rounded-xl border border-gray-300 px-5 py-4 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">

                            @elseif($question->question_type === 'textarea')
                                <textarea name="questions[{{ $question->id }}][answer]" required rows="4" placeholder="Enter detailed response..." class="w-full rounded-xl border border-gray-300 px-5 py-4 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">{{ old("questions.{$question->id}.answer") }}</textarea>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach

            {{-- Final Step: Signatures --}}
            <div id="step-{{ $totalSteps }}" class="step-content">
                <div class="text-center mb-8"><h3 class="text-xl font-bold text-gray-800">Final Confirmation</h3></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Client Signature</label>
                        <div class="signature-wrapper"><div class="signature-placeholder" id="client-placeholder">Sign Here</div><canvas id="client-canvas"></canvas></div>
                        <input type="hidden" name="client_signature" id="client_signature_base64">
                        <button type="button" class="text-[10px] text-red-500 font-bold uppercase clear-sig" data-target="client">Clear</button>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Rep Signature</label>
                        <div class="signature-wrapper"><div class="signature-placeholder" id="rep-placeholder">Sign Here</div><canvas id="rep-canvas"></canvas></div>
                        <input type="hidden" name="representative_signature" id="rep_signature_base64">
                        <button type="button" class="text-[10px] text-red-500 font-bold uppercase clear-sig" data-target="rep">Clear</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <button type="button" id="prevBtn" class="hidden text-gray-600 font-bold text-sm px-4 py-2 hover:bg-gray-100 rounded-lg">&larr; Previous</button>
            <div class="flex-1"></div>
            <button type="button" id="nextBtn" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-10 rounded-xl shadow transition-all">Next &rarr;</button>
            <button type="submit" id="submitBtn" class="hidden bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-10 rounded-xl shadow items-center gap-2"><span>Complete Survey</span></button>
        </div>
    </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = {{ $totalSteps }};
    const form = document.getElementById('surveyForm');

    const clientCanvas = document.getElementById('client-canvas');
    const repCanvas = document.getElementById('rep-canvas');
    const clientPad = new SignaturePad(clientCanvas);
    const repPad = new SignaturePad(repCanvas);

    clientPad.addEventListener("beginStroke", () => document.getElementById('client-placeholder').style.opacity = '0');
    repPad.addEventListener("beginStroke", () => document.getElementById('rep-placeholder').style.opacity = '0');

    function resizeCanvas() {
        [clientCanvas, repCanvas].forEach(canvas => {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.parentElement.offsetWidth * ratio;
            canvas.height = canvas.parentElement.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        });
        clientPad.clear(); repPad.clear();
        document.querySelectorAll('.signature-placeholder').forEach(p => p.style.opacity = '1');
    }

    window.addEventListener("resize", resizeCanvas);

    function updateUI() {
        document.querySelectorAll('.step-content').forEach((el, i) => el.classList.toggle('active', (i + 1) === currentStep));
        const percentage = Math.round((currentStep / totalSteps) * 100);
        document.getElementById('progress-bar').style.width = `${percentage}%`;
        document.getElementById('step-counter').innerText = `${percentage}%`;
        document.getElementById('step-description').innerText = `Step ${currentStep} of ${totalSteps}`;
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

    function validateStep() {
        const activeStep = document.querySelector('.step-content.active');

        // Combined selector for text inputs, selects, and textareas
        const basicInputs = activeStep.querySelectorAll('input[required]:not([type="radio"]):not([type="checkbox"]), select[required], textarea[required]');
        for (let input of basicInputs) {
            if (!input.value.trim() || !input.checkValidity()) {
                input.reportValidity();
                triggerError(input);
                return false;
            }
        }

        const radios = activeStep.querySelectorAll('input[type="radio"][required]');
        if (radios.length > 0) {
            const name = radios[0].name;
            if (!activeStep.querySelector(`input[name="${name}"]:checked`)) {
                triggerError(radios[0]);
                alert("Please select an option.");
                return false;
            }
        }

        const checkboxes = activeStep.querySelectorAll('input[type="checkbox"]');
        if (checkboxes.length > 0) {
            const oneChecked = Array.from(checkboxes).some(cb => cb.checked);
            if (!oneChecked) {
                triggerError(checkboxes[0]);
                alert("Please select at least one option.");
                return false;
            }
        }

        return true;
    }

    function triggerError(el) {
        const container = el.closest('.question-container');
        if (container) {
            container.classList.add('input-error');
            setTimeout(() => container.classList.remove('input-error'), 600);
        }
    }

    document.getElementById('nextBtn').addEventListener('click', () => {
        if (validateStep()) { currentStep++; updateUI(); window.scrollTo(0,0); }
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
        currentStep--; updateUI(); window.scrollTo(0,0);
    });

    document.querySelectorAll('.clear-sig').forEach(btn => {
        btn.addEventListener('click', () => {
            const pad = btn.dataset.target === 'client' ? clientPad : repPad;
            pad.clear();
            document.getElementById(`${btn.dataset.target}-placeholder`).style.opacity = '1';
        });
    });

    form.addEventListener('submit', (e) => {
        if (clientPad.isEmpty() || repPad.isEmpty()) {
            e.preventDefault();
            alert("Both signatures are required.");
            return;
        }
        document.getElementById('client_signature_base64').value = clientPad.toDataURL();
        document.getElementById('rep_signature_base64').value = repPad.toDataURL();
        const btn = document.getElementById('submitBtn');
        btn.disabled = true; btn.innerText = "Saving...";
    });

    updateUI();
});
</script>
@endsection
