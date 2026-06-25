// resources/js/bootstrap.js
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// 👇 Add this for Echo
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Listen to private channel
window.Echo.private(`user.${window.user?.id || 0}`)
    .listen('SaleCompleted', (e) => {
        console.log('New sale notification:', e);
        // Show toast
        if (window.showToast) {
            window.showToast(e.message || 'New sale completed!', 'success');
        }
        // Update notification badge
        updateNotificationBadge();
    })
    .listen('LowStockDetected', (e) => {
        console.log('Low stock alert:', e);
        if (window.showToast) {
            window.showToast(e.message || 'Low stock alert!', 'warning');
        }
        updateNotificationBadge();
    })
    .listen('ExpiryApproaching', (e) => {
        console.log('Expiry alert:', e);
        if (window.showToast) {
            window.showToast(e.message || 'Medicine expiring soon!', 'error');
        }
        updateNotificationBadge();
    });

function updateNotificationBadge() {
    fetch('/notifications/unread-count')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const badge = document.querySelector('.topbar-btn .dot');
                if (badge) {
                    if (data.unread_count > 0) {
                        badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }
        })
        .catch(err => console.error('Failed to update badge', err));
}