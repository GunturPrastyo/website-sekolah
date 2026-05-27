// src/api/index.js
import axios from 'axios';
import router from '../router/index.js';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
  }
});

// Interceptor dipasang pada instans 'api', bukan axios global
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && (error.response.status === 401 || error.response.status === 419)) {
      localStorage.removeItem('user');
      localStorage.removeItem('isLoggedIn');

      // Hanya redirect ke halaman login jika pengguna sedang berada di rute admin
      if (router.currentRoute.value.path.startsWith('/admin')) {
        router.push({ name: 'login' });
      }
    }
    return Promise.reject(error);
  }
);

export default api;