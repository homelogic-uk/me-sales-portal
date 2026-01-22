@extends('layouts.master')

@section('content')
<div class="fixed inset-0 flex flex-col items-center justify-center px-4 bg-gray-50/50 backdrop-blur-sm">
    <div class="max-w-md w-full text-center space-y-8">

        <div class="relative flex justify-center">
            <div class="absolute inset-0 rounded-full bg-blue-100 animate-ping opacity-20 h-24 w-24 mx-auto mt-4"></div>
            <div class="relative bg-white rounded-full p-6 shadow-xl border border-blue-50 h-32 w-32 flex items-center justify-center">
                <svg id="status-icon" class="w-16 h-16 text-blue-600 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>

        <div class="space-y-3">
            <h1 id="status-title" class="text-2xl font-bold text-gray-900 tracking-tight">Preparing Your Contract</h1>
            <p id="status-desc" class="text-gray-500 text-sm leading-relaxed max-w-xs mx-auto">
                We're generating your document. This usually takes 5-60 seconds.
            </p>
        </div>

        <div class="bg-gray-200 rounded-full h-2 overflow-hidden shadow-inner">
            <div id="loader-bar" class="bg-blue-600 h-full w-0 transition-all duration-700 ease-out shadow-[0_0_10px_rgba(37,99,235,0.5)]"></div>
        </div>

        <div class="flex flex-col gap-3 text-left bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 text-sm" id="step-1">
                <div class="h-2 w-2 rounded-full bg-green-500"></div>
                <span class="text-gray-700 font-medium">Validating document data</span>
            </div>
            <div class="flex items-center gap-3 text-sm" id="step-2">
                <div id="dot-2" class="h-2 w-2 rounded-full bg-green-500"></div>
                <span class="text-gray-700 font-medium">Generating PDF</span>
            </div>
            <div class="flex items-center gap-3 text-sm" id="step-3">
                <div id="dot-3" class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></div>
                <span class="text-gray-700 font-medium">Processing & Sending</span>
            </div>
        </div>
    </div>
</div>

<script>
    const bar = document.getElementById('loader-bar');
    const dot3 = document.getElementById('dot-3');
    const statusTitle = document.getElementById('status-title');
    const statusDesc = document.getElementById('status-desc');

    let progress = 30;
    let isRedirecting = false;

    // Fake progress to keep the user engaged
    const progressInterval = setInterval(() => {
        if (progress < 95) {
            progress += Math.random() * 2;
            bar.style.width = progress + '%';
        }
    }, 1000);

    function checkStatus() {
        if (isRedirecting) return;

        fetch("{{ route('leads.contract.status', ['id' => $lead->id, 'uuid' => $document->uuid]) }}")
            .then(response => response.json())
            .then(data => {
                console.log('Document Status:', data.status);

                if (data.status === 'ready') {
                    isRedirecting = true;
                    clearInterval(progressInterval);

                    // Update UI to completion
                    bar.style.width = '100%';
                    dot3.classList.remove('animate-pulse', 'bg-blue-500');
                    dot3.classList.add('bg-green-500');
                    statusTitle.innerText = "Contract Ready!";
                    statusDesc.innerText = "Redirecting you to the signing page...";

                    // Redirect to the actual signing URL or dashboard
                    setTimeout(() => {
                        window.location.href = data.signing_url || "{{ route('dashboard.index') }}";
                    }, 1000);
                }

                if (data.status === 'error') {
                    statusTitle.innerText = "Something went wrong";
                    statusDesc.innerText = "Please refresh the page to try again.";
                    statusTitle.classList.add('text-red-600');
                }
            })
            .catch(error => console.error('Error checking status:', error));
    }

    // Start polling immediately, then every 4 seconds
    checkStatus();
    const pollInterval = setInterval(checkStatus, 4000);
</script>
@endsection