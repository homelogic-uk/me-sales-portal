@extends('layouts.master')

@section('content')
<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }

    .input-error {
        animation: shake 0.2s ease-in-out 0s 2;
    }
</style>

@php
function maskEmail($email) {
    if (!$email) return '';
    $parts = explode("@", $email);
    $name = substr($parts[0], 0, 2) . str_repeat('*', max(strlen($parts[0]) - 2, 0));
    return $name . '@' . $parts[1];
}

function maskPhone($phone) {
    if (!$phone) return '';
    $length = strlen($phone);
    if ($length <= 4) return str_repeat('*', $length);
    return substr($phone, 0, 2) . str_repeat('*', $length - 4) . substr($phone, -2);
}
$titleOptions = ['Mr', 'Mrs', 'Miss', 'Ms', 'Master', 'Dr', 'Prof', 'Rev', 'Sir', 'Lady', 'Lord', 'Dame'];
@endphp

<section class="max-w-6xl mx-auto py-8 px-4 sm:px-6 overscroll-none">

    {{-- Back Navigation --}}
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('dashboard.index') }}" class="flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Appointments
        </a>
        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium uppercase tracking-wide">
            Lead #{{ $lead->id }}
        </span>
    </div>

    {{-- Global Error Alert --}}
    @if ($errors->any())
    <div class="mb-8 flex items-center p-4 text-red-800 border border-red-200 rounded-xl bg-red-50 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300" role="alert">
        <div class="flex-shrink-0 bg-red-100 p-2 rounded-lg">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-4 text-sm font-medium">
            <span class="font-bold">Submission Failed:</span> There are {{ $errors->count() }} fields that require your attention.
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- LEFT COLUMN: Lead Profile --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-lg">
                        {{ substr($lead->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900 leading-tight">
                            {{ $lead->name }} {{ $lead->surname }}
                        </h1>
                        <p class="text-sm text-gray-500">Customer Profile</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Email</p>
                            <span class="text-sm font-medium text-gray-900 truncate block max-w-[180px]">{{ $lead->email ?? 'Not provided' }}</span>
                        </div>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Property Address</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5 leading-relaxed">
                                {{ $lead->address_line_1 }}<br>
                                {{ $lead->city }}<br>
                                <span class="uppercase tracking-widest text-gray-600">{{ $lead->postcode }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Form --}}
        <div class="lg:col-span-2">
            <form method="POST" id="contractForm" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 text-lg font-semibold text-gray-800">
                        Check & Update Details
                    </div>

                    <div class="p-6 space-y-8">
                        {{-- Section 1: Personal Info --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                            <div class="sm:col-span-2 flex flex-wrap gap-4">
                                <div class="w-28 flex-shrink-0">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Title</label>
                                    <select name="title" required class="w-full rounded-lg border @error('title') border-red-300 bg-red-50/50 input-error @else border-gray-300 bg-white @enderror px-3 py-2.5 text-sm focus:ring-2 @error('title') focus:ring-red-200 @else focus:ring-blue-500 @enderror outline-none transition-all">
                                        <option value="" disabled selected hidden>Select a title...</option>
                                        @foreach($titleOptions as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-1 min-w-[140px]">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">First Name</label>
                                    <input type="text" name="name" value="{{ old('name', $lead->name) }}" class="w-full rounded-lg border @error('name') border-red-300 bg-red-50/50 input-error @else border-gray-300 @enderror px-4 py-2.5 text-sm focus:ring-2 @error('name') focus:ring-red-200 @else focus:ring-blue-500 @enderror outline-none transition-all">
                                    @error('name')
                                    <div class="flex items-center mt-1.5 text-red-600">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-[11px] font-medium leading-none">{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                                <div class="flex-1 min-w-[140px]">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Surname</label>
                                    <input type="text" name="surname" value="{{ old('surname', $lead->surname) }}" class="w-full rounded-lg border @error('surname') border-red-300 bg-red-50/50 input-error @else border-gray-300 @enderror px-4 py-2.5 text-sm focus:ring-2 @error('surname') focus:ring-red-200 @else focus:ring-blue-500 @enderror outline-none transition-all">
                                    @error('surname')
                                    <div class="flex items-center mt-1.5 text-red-600">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-[11px] font-medium leading-none">{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $lead->email) }}" class="w-full rounded-lg border @error('email') border-red-300 bg-red-50/50 input-error @else border-gray-300 @enderror px-4 py-2.5 text-sm focus:ring-2 @error('email') focus:ring-red-200 @else focus:ring-blue-500 @enderror outline-none transition-all">
                                @error('email')
                                <div class="flex items-center mt-1.5 text-red-600">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-[11px] font-medium leading-none">{{ $message }}</span>
                                </div>
                                @enderror
                            </div>

                            <div class="flex gap-4 sm:col-span-2">
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Landline</label>
                                    <input type="text" name="phone_landline" value="{{ old('phone_landline') }}" placeholder="{{ maskPhone($lead->phone_landline) }}" class="w-full rounded-lg border @error('phone_landline') border-red-300 bg-red-50/50 input-error @else border-gray-300 @enderror px-4 py-2.5 text-sm outline-none transition-all">
                                    @error('phone_landline')
                                    <p class="mt-1.5 text-[11px] text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Mobile</label>
                                    <input type="text" name="phone_mobile" value="{{ old('phone_mobile') }}" placeholder="{{ maskPhone($lead->phone_mobile) }}" class="w-full rounded-lg border @error('phone_mobile') border-red-300 bg-red-50/50 input-error @else border-gray-300 @enderror px-4 py-2.5 text-sm outline-none transition-all">
                                    @error('phone_mobile')
                                    <p class="mt-1.5 text-[11px] text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Address --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Address Line 1</label>
                                <input type="text" name="address_line_1" value="{{ old('address_line_1', $lead->address_line_1) }}" class="w-full rounded-lg border @error('address_line_1') border-red-300 bg-red-50/50 input-error @else border-gray-300 @enderror px-4 py-2.5 text-sm focus:ring-2 @error('address_line_1') focus:ring-red-200 @else focus:ring-blue-500 @enderror outline-none transition-all">
                                @error('address_line_1')
                                <p class="mt-1.5 text-[11px] text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">City</label>
                                <input type="text" name="city" value="{{ old('city', $lead->city) }}" class="w-full rounded-lg border @error('city') border-red-300 bg-red-50/50 @else border-gray-300 @enderror px-4 py-2.5 text-sm outline-none transition-all">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Postcode</label>
                                <input type="text" name="postcode" value="{{ old('postcode', $lead->postcode) }}" class="w-full rounded-lg border @error('postcode') border-red-300 bg-red-50/50 @else border-gray-300 @enderror px-4 py-2.5 text-sm outline-none transition-all">
                            </div>
                        </div>

                        {{-- Section 3: Before Photos --}}
                        <div class="pt-6 border-t border-gray-100">
                            <div class="mb-4">
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Before Photos</h3>
                                <p class="text-xs text-gray-400 mt-1">Please upload at least 3 clear photos of the work space.</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @for ($i = 0; $i < 3; $i++)
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 tracking-tight">Photo #{{ $i + 1 }}</label>

                                    {{-- Preview Container --}}
                                    <div id="preview-container-{{ $i }}" class="hidden relative aspect-square w-full rounded-lg border border-gray-200 overflow-hidden bg-gray-50 group">
                                        <img id="preview-{{ $i }}" src="" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <button type="button" onclick="removeImage({{ $i }})" class="bg-red-500 text-white rounded-full p-2 hover:bg-red-600 shadow-lg transform scale-90 group-hover:scale-100 transition-transform">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="relative" id="input-container-{{ $i }}">
                                        <input type="file"
                                               required
                                               accept="image/*"
                                               onchange="handleImageSelect(event, {{ $i }})"
                                               class="w-full text-xs text-gray-500
                                                      file:mr-3 file:py-2 file:px-4
                                                      file:rounded-lg file:border-0
                                                      file:text-xs file:font-bold
                                                      file:bg-blue-50 file:text-blue-700
                                                      hover:file:bg-blue-100
                                                      border rounded-lg @error('before_photos.'.$i) border-red-300 bg-red-50/30 input-error @else border-gray-200 @enderror
                                                      transition-all outline-none" />
                                    </div>
                                    @error('before_photos.'.$i)
                                        <p class="mt-1 text-[10px] text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Section 4: Deposit --}}
                        <div class="pt-6 border-t border-gray-100">
                            <div class="bg-blue-50 border @error('deposit_amount') border-red-200 bg-red-50/30 @else border-blue-100 @enderror rounded-xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex-grow">
                                    <label for="deposit_amount" class="block text-sm font-bold text-gray-900">Contract Deposit</label>
                                    <p class="text-xs text-blue-700 mt-0.5 @error('deposit_amount') text-red-700 @enderror">Enter the amount required to secure the contract.</p>
                                </div>
                                <div class="w-full sm:w-48 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="@error('deposit_amount') text-red-500 @else text-gray-500 @enderror sm:text-sm font-bold">£</span>
                                    </div>
                                    <input type="number" name="deposit_amount" id="deposit_amount" step="0.01" min="0"
                                        value="{{ old('deposit_amount') }}"
                                        placeholder="{{ 0.3 * $lead->quotes->sum('total_price') }}" class="block w-full rounded-lg border @error('deposit_amount') border-red-400 bg-white input-error @else border-gray-300 @enderror bg-white py-3 pl-8 pr-4 text-gray-900 font-bold focus:ring-2 @error('deposit_amount') focus:ring-red-200 @else focus:ring-blue-500 @enderror outline-none sm:text-sm shadow-sm transition-all">
                                </div>
                            </div>
                            @error('deposit_amount')
                            <p class="text-red-600 text-right text-xs mt-2 font-bold flex items-center justify-end">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end">
                        <button type="submit" id="startContractBtn" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-3 px-10 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-3">
                            <span>Start Contract</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    const form = document.getElementById('contractForm');
    const submitBtn = document.getElementById('startContractBtn');

    // This will hold our final compressed files ready for upload
    const compressedFiles = {};

    /**
     * Triggered when a file is chosen
     */
    async function handleImageSelect(event, index) {
        const file = event.target.files[0];
        if (!file) return;

        // 1. Show Preview immediately for UX
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById(`preview-${index}`).src = e.target.result;
            document.getElementById(`preview-container-${index}`).classList.remove('hidden');
            document.getElementById(`input-container-${index}`).classList.add('hidden');
        };
        reader.readAsDataURL(file);

        // 2. Compress the image in the background
        compressedFiles[index] = await compressImage(file, {
            maxWidth: 1600, // Max width in pixels
            quality: 0.7    // 70% quality JPEG
        });
    }

    /**
     * Removes the image and allows the user to pick again
     */
    function removeImage(index) {
        document.getElementById(`preview-container-${index}`).classList.add('hidden');
        document.getElementById(`input-container-${index}`).classList.remove('hidden');

        // Clear both the input and the stored compression
        const input = document.querySelectorAll('input[type="file"]')[index];
        if(input) input.value = '';
        delete compressedFiles[index];
    }

    /**
     * Compression Engine
     */
    async function compressImage(file, { maxWidth, quality }) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (event) => {
                const img = new Image();
                img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    // Calculate new dimensions
                    if (width > height) {
                        if (width > maxWidth) {
                            height *= maxWidth / width;
                            width = maxWidth;
                        }
                    } else {
                        if (height > maxWidth) {
                            width *= maxWidth / height;
                            height = maxWidth;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    // Output as JPEG Blob
                    canvas.toBlob((blob) => {
                        resolve(new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now(),
                        }));
                    }, 'image/jpeg', quality);
                };
            };
        });
    }

    /**
     * Handle Submit
     */
    form.onsubmit = async (e) => {
        e.preventDefault();

        // UI Loading State
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
        submitBtn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Processing...</span>`;

        // Gather all form data
        const formData = new FormData(form);

        // The original file inputs in the form are actually empty (we didn't name them 'before_photos[]'
        // to avoid double-sending). We add the compressed ones manually.
        Object.keys(compressedFiles).forEach(key => {
            formData.append('before_photos[]', compressedFiles[key]);
        });

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (response.redirected) {
                // Success: Laravel did a redirect
                window.location.href = response.url;
            } else {
                // Failure: Validation error or 500. Reload to let Laravel display errors.
                location.reload();
            }
        } catch (error) {
            console.error("Upload failed", error);
            alert("An error occurred during submission. Please try again.");
            submitBtn.disabled = false;
            submitBtn.innerHTML = "<span>Start Contract</span>";
        }
    };
</script>
@endsection
