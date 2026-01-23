import { Signing } from "pandadoc-signing";

document.addEventListener("DOMContentLoaded", async () => {
    const container = document.getElementById('pandadoc-container');
    const loader = document.getElementById('loader');
    const redirectOverlay = document.getElementById('redirect-overlay'); // Get the new overlay

    if (!container || !window.signingConfig) return;

    try {
        const signing = new Signing('pandadoc-container', {
            sessionId: window.signingConfig.sessionId,
            height: 800,
        }, {
            region: "com",
        });

        signing.on('document.loaded', () => {
            loader.classList.add('hidden');
        });

        // Updated Completion Logic
        signing.on('document.completed', (payload) => {
            console.log('Document completed, initiating redirect...');

            // 1. Show the full-page loader
            redirectOverlay.classList.remove('hidden');

            // 2. Perform the redirect
            window.location.href = window.signingConfig.redirectUrl;
        });

        signing.on('session.error', (error) => {
            console.error('PandaDoc Error:', error);
            loader.innerHTML = '<p class="text-red-500 p-4">Error loading the document. Please refresh.</p>';
        });

        await signing.open();

    } catch (err) {
        console.error('Initialization failed', err);
    }
});
