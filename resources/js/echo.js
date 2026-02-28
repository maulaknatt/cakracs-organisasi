import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
// Only enable Pusher logging in development, NOT production
Pusher.logToConsole = import.meta.env.DEV;
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

