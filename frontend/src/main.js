import { createApp } from 'vue'
import App from './App.vue'
import router from './router/index.js'
import axios from 'axios'

// Impor file CSS global
import './assets/css/main.css'


// --- Konfigurasi Global Axios ---
// Mengambil base URL dari environment variable Vite
axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL;

// Konfigurasi untuk otentikasi via Laravel Sanctum (SPA)
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
axios.defaults.headers.common["Accept"] = "application/json";
// --------------------------------

const app = createApp(App)

app.use(router)

app.mount('#app')
