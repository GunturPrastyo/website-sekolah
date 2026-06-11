// src/api/index.js
import axios from 'axios';

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
  async (error) => {
    if (error.response && (error.response.status === 401 || error.response.status === 419)) {
      localStorage.removeItem('user');
      localStorage.removeItem('isLoggedIn');

      // Import router secara dinamis untuk menghindari circular dependency
      const { default: router } = await import('../router/index.js');

      // Hanya redirect ke halaman login jika pengguna sedang berada di rute admin
      if (router.currentRoute.value.path.startsWith('/admin')) {
        router.push({ name: 'login' });
      }
    }
    return Promise.reject(error);
  }
);

export default api;