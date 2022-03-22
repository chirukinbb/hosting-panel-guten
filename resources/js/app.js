require('./bootstrap');

import { createApp } from 'vue'
import Echo from "laravel-echo"

window.Pusher = require('pusher-js')

import GameComponent from "./components/GameComponent"

const app = createApp(GameComponent)
//    .use(
        // window.Echo = new Echo({
        //     broadcaster: 'pusher',
        //     key: '4326370c5eb04b2329d3',
        //     wsHost: window.location.hostname,
        //     wsPort: 6001,
        //     forceTLS: false,
        //     disableStats: false
        // })
  //  )
    .mount('#game')


window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '1',
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: false,
})
    .channel('turn-1647934735')
    .listen('turns',function (e) {
        console.log(e)
    })
