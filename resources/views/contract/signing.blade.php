@extends('layouts.master')

@section('content')
<style>
    #pandadoc-container {
        width: 100%;
    }
</style>

<section class="max-w-7xl mx-auto py-8 px-4 sm:px-6">
    {{-- Admin Controls: Only visible to authenticated users --}}
    @if(!Route::is('external.signing'))
    <div class="mb-8 bg-blue-50 border border-blue-200 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">

            {{-- External URL Field --}}
            <div class="flex-1">
                <label for="signing_url" class="block text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">
                    External Signing URL
                </label>
                <div class="relative flex items-center">
                    <input type="text"
                           id="signing_url"
                           readonly
                           value="{{ $signingUrl ?? 'URL not generated' }}"
                           class="w-full bg-white border border-blue-200 rounded-xl px-4 py-2.5 text-sm text-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all pr-12">

                    <button onclick="navigator.clipboard.writeText('{{ $signingUrl ?? '' }}'); alert('URL Copied!')"
                            class="absolute right-2 p-1.5 text-blue-500 hover:bg-blue-100 rounded-lg transition-colors"
                            title="Copy to Clipboard">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Admin Buttons --}}
            <div class="flex flex-wrap items-center">
                {{-- Send to Client --}}
                <form action="" method="POST" class="gap-3">
                    @csrf
                    <button name="type" value="send" type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Send to Client
                    </button>

                                  {{-- Amend Contract --}}
                    <button name="type" value="amend" type="submit" class="inline-flex items-center px-6 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Amend Contract
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

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
        redirectUrl: "{{ Route::is('external.signing') ? route('external.complete', ['documentId' => $document->uuid ]) : route('leads.contract.complete', ['id' => $lead->id, 'uuid' => $document->uuid ]) }}"
    };
</script>

{{-- Full Page Redirect Loader --}}
<div id="redirect-overlay" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-white/90 backdrop-blur-md hidden">
    <div class="flex flex-col items-center">
        <div class="w-16 h-16 border-4 border-green-100 border-t-green-600 rounded-full animate-spin"></div>
        <h2 class="mt-6 text-2xl font-bold text-gray-900">Document Signed!</h2>
        <p class="mt-2 text-gray-600">Please wait while we finalize your records...</p>
    </div>
</div>

@vite(['resources/js/signing.js'])
@endsection
