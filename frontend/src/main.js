// main.js
import { createApp } from 'vue'
import App from './App.vue'
import router from './router/index.js'
import api from './api/index.js'

// Impor file CSS global
import './assets/css/main.css'

const app = createApp(App)

app.use(router)

// Mengambil pengaturan web (Title & Favicon) secara dinamis
api.get('/api/settings')
  .then(response => {
    const result = response.data;
    if (result.success && result.data) {
      const settings = result.data;
      
      const appTitle = settings.namaSekolah || 'Website Sekolah';
      document.title = appTitle;

      // Mengatur Favicon dengan mencari elemen tag link rel="icon"
      if (settings.favicon) {
        let link = document.querySelector("link[rel~='icon']");
        if (link) {
          link.href = settings.favicon;
        }
      }
    }
  })
  .catch(error => console.error('Gagal memuat pengaturan website:', error));

app.mount('#app')