// src/api/index.js
import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
  }
});

// ======================================================================
// INTERCEPTOR REQUEST: Otomatis Menyisipkan Token Bearer di Setiap Request
// ======================================================================
api.interceptors.request.use(
  (config) => {
    // Ambil token stateless dari localStorage
    const token = localStorage.getItem('auth_token');
    
    // Jika token ditemukan, masukkan ke dalam Header Authorization
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// ======================================================================
// INTERCEPTOR RESPONSE: Otomatis Menendang ke Login Jika Token Kedaluwarsa
// ======================================================================
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    // Jika server merespons 401 (Artinya token tidak sah atau sudah kedaluwarsa)
    if (error.response && error.response.status === 401) {
      // Bersihkan semua data sesi di lokal browser
      localStorage.removeItem('auth_token');
      localStorage.removeItem('isLoggedIn');
      localStorage.removeItem('user_role');

      // Import router secara dinamis untuk menghindari circular dependency
      const { default: router } = await import('../router/index.js');

      // Hanya alihkan ke halaman login jika pengguna sedang berada di area admin
      if (router.currentRoute.value.path.startsWith('/admin')) {
        router.push({ name: 'login' });
      }
    }
    return Promise.reject(error);
  }
);

export default api;