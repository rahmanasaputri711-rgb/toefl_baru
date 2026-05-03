{{-- Firebase Push Notification — @include('partials.firebase-push') sebelum </body> --}}
@if(auth()->check() && config('services.firebase.project_id'))
<script type="module">
import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js';
import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js';

const app       = initializeApp({
    apiKey:            "{{ config('services.firebase.api_key') }}",
    authDomain:        "{{ config('services.firebase.auth_domain') }}",
    projectId:         "{{ config('services.firebase.project_id') }}",
    storageBucket:     "{{ config('services.firebase.storage') }}",
    messagingSenderId: "{{ config('services.firebase.sender_id') }}",
    appId:             "{{ config('services.firebase.app_id') }}",
});
const messaging = getMessaging(app);
const VAPID     = "{{ config('services.firebase.vapid_key') }}";

if ('Notification' in window) {
    Notification.requestPermission().then(async perm => {
        if (perm !== 'granted') return;
        try {
            const token = await getToken(messaging, { vapidKey: VAPID });
            if (token) {
                fetch('/fcm/save-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({ token }),
                });
            }
        } catch(e) { console.warn('FCM token error:', e); }
    });
}

onMessage(messaging, payload => {
    const { title, body } = payload.notification || {};
    const link = payload.data?.deep_link || '/dashboard';
    const el   = Object.assign(document.createElement('div'), {
        innerHTML: `<b class="block text-sm">${title}</b><span class="text-xs opacity-90">${body}</span>`,
        className: 'fixed top-4 right-4 z-[9999] bg-blue-600 text-white px-5 py-3 rounded-xl shadow-xl max-w-xs cursor-pointer',
        onclick:   () => location.href = link,
    });
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 7000);
});
</script>
@endif
