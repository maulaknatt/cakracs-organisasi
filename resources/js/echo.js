import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
// Only enable Pusher logging in development, NOT production
Pusher.logToConsole = import.meta.env.DEV;
window.Pusher = Pusher;

const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;
const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER;

// Only initialize Echo if we have valid Pusher credentials
if (pusherKey && pusherKey !== 'null' && pusherKey !== 'undefined' && !pusherKey.includes('$')) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: pusherKey,
        cluster: pusherCluster,
        forceTLS: true,
        // Limit retries to prevent browser performance issues
        activityTimeout: 30000,
        pongTimeout: 10000,
    });
} else {
    // Create a dummy Echo object to prevent errors
    window.Echo = {
        channel: () => ({ listen: () => ({ listen: () => { } }) }),
        private: () => ({ listen: () => ({}) }),
        leave: () => { },
        connector: { pusher: { connection: { state: 'disconnected' } } }
    };
    if (import.meta.env.DEV) {
        console.warn('Echo: Pusher credentials missing, realtime disabled.');
    }
}

