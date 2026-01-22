import { Signing } from "pandadoc-signing";

document.addEventListener("DOMContentLoaded", async () => {
    const container = document.getElementById('pandadoc-container');
    const loader = document.getElementById('loader');

    console.log(window.signingConfig.sessionId);

    if (!container || !window.signingConfig) return;

    try {
        // 1. Initialize
        const signing = new Signing('pandadoc-container',
        {
            sessionId: window.signingConfig.sessionId,
            // Optional: add styling or settings
            height: 800,
            // width: '100%',
        },
        {
            region: "com", // Optional: 'com' or 'eu'
        });

        // 2. Event Listeners
        signing.on('session.created', (payload) => {
            console.log('Session Created', payload);
        });

        signing.on('document.loaded', () => {
            console.log('test');
            loader.classList.add('hidden');
        });

        signing.on('document.completed', (payload) => {
            window.location.href = window.signingConfig.redirectUrl;
        });

        signing.on('session.error', (error) => {
            console.error('PandaDoc Error:', error);
            loader.innerHTML = '<p class="text-red-500 p-4">Error loading the document. Please refresh.</p>';
        });

        // 3. Start
        await signing.open();

    } catch (err) {
        console.error('Initialization failed', err);
    }
});
