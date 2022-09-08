import './bootstrap';
import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "7144a5608611641f3ca2",
    cluster: "mt1",
    forceTLS: true
});