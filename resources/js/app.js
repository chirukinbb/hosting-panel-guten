<<<<<<< HEAD
require('./bootstrap');

window.Vue = require('vue').default;

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

const app = new Vue({
    el: '#app'
});
=======
import Echo from "laravel-echo"

window.Pusher = require('pusher-js')

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '4326370c5eb04b2329d3',
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: false,
})
    .channel('article-publish-channel')
    .listen('ArticlePublishEvent',function (e) {
        console.log(e)
    })
>>>>>>> e9bac50 (pusher on own socket)
