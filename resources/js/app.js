require('./bootstrap');

import { createApp } from 'vue'

import GameComponent from "./components/GameComponent"

localStorage.csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
localStorage.token = document.querySelector('meta[name="api-token"]').getAttribute('content')

const app = createApp(GameComponent).mount('#game')
