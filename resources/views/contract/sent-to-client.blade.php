@extends('layouts.master')

@section('content')
<div class="fixed inset-0 flex flex-col items-center justify-center px-4 bg-gray-50/50">
    <div class="max-w-md w-full text-center">

        <div class="mb-8 relative flex justify-center">
            <div class="bg-green-100 rounded-full p-6 h-24 w-24 flex items-center justify-center shadow-sm">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <div class="space-y-4 mb-10">
            <h1 class="text-2xl font-bold text-gray-900">Contract Sent</h1>
            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm inline-block w-full">
                <p class="text-sm text-gray-500 uppercase tracking-wider font-semibold mb-1">Recipient Email</p>
                <p class="text-lg text-gray-800 font-mono break-all">{{ $lead->email }}</p>
            </div>
            <p class="text-gray-500 text-sm">
                The external signing URL has been successfully generated and sent to the client.
                The lead status will update automatically once they sign.
            </p>
        </div>

        <div class="flex flex-col gap-3">
            <a href="{{ route('dashboard.index') }}"
               class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                Go to Dashboard
            </a>
        </div>

    </div>
</div>
@endsection
