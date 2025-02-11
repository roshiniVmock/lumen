window._ = require('lodash');
import Pusher from "pusher-js"
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
// window.Pusher = require('pusher-js');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "022a175aa6fe0e01fbce",
    cluster: "mt1",
    forceTLS: true,
    encrypted: true,
});
// import Echo from 'laravel-echo';
 
// window.Pusher = require('pusher-js');
 
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: "AYPYMg.QOUg1Q",
//     wsHost: 'realtime-pusher.ably.io',
//     wsPort: 443,
//     disableStats: true,
//     encrypted: true,
// });