import './assets/base.css'

import axios from "axios";

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(router)

app.mount('#app')
