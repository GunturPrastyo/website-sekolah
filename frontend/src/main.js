import { createApp } from 'vue'
import App from './App.vue'
import router from './router/index.js'

// Impor file CSS global
import './assets/css/main.css'

const app = createApp(App)

app.use(router)

app.mount('#app')
