@extends('layouts.master')

@section('content')
<style>
    #pandadoc-container {
        width: 100%;
    }
</style>
<section class="max-w-7xl mx-auto py-8 px-4 sm:px-6">

    {{-- Header with Navigation & Status --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 leading-tight">Contract Review & Signing</h1>
            <p class="text-sm text-gray-500">Please review the details for <span class="font-semibold text-gray-700">{{ $lead->name }} {{ $lead->surname }}</span></p>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden relative">

        {{-- Loading Overlay --}}
        <div id="loader" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50/90 z-20 backdrop-blur-sm transition-opacity duration-500">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin"></div>
            </div>
            <p class="mt-4 text-gray-600 font-semibold animate-pulse">Initializing Secure Document...</p>
        </div>

        {{-- PandaDoc Iframe Target --}}
        <div id="pandadoc-container" class="w-full min-h-[800px] bg-gray-50">
            {{-- The SDK will inject the iframe here --}}
        </div>
    </div>
</section>

<script>
    window.signingConfig = {
        sessionId: "{{ $sessionId }}",
        redirectUrl: "{{ route('leads.contract.complete', ['id' => $lead->id, 'uuid' => $document->uuid ]) }}"
    };
</script>
{{-- Full Page Redirect Loader --}}
<div id="redirect-overlay" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-white/90 backdrop-blur-md hidden">
    <div class="flex flex-col items-center">
        {{-- Success Checkmark or Spinner --}}
        <div class="w-16 h-16 border-4 border-green-100 border-t-green-600 rounded-full animate-spin"></div>
        <h2 class="mt-6 text-2xl font-bold text-gray-900">Document Signed!</h2>
        <p class="mt-2 text-gray-600">Please wait while we finalize your records...</p>
    </div>
</div>
@vite(['resources/js/signing.js'])
@endsection