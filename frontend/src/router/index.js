import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import VisiMisi from '@/views/VisiMisi.vue'
import Fasilitas from '@/views/FasilitasView.vue'
import GuruStaf from '@/views/GuruStaf.vue'
import Kurikulum from '@/views/KurikulumView.vue'
import Alumni from '@/views/AlumniView.vue'
import AdminLayout from '@/layouts/AdminLayout.vue' // Import the new AdminLayoutuse Illuminate\Database\Migrations\Migration;


import PublicLayout from '@/layouts/PublicLayout.vue' // Import PublicLayout
import api from '@/api/index.js' // Import instance api untuk tracking

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      // Menggunakan lazy-loading agar komponen hanya dimuat saat rute diakses
      component: () => import('../views/auth/Login.vue'),
      meta: {
        // (Opsional) Jika Anda menggunakan middleware/auth guard nantinya
        requiresGuest: true,
        title: 'Login Administrator'
      }
    },

    {
      path: '/',
      // name: 'home', // Removed duplicate name
      component: PublicLayout, // Use PublicLayout for public routes
      children: [
        {
          path: '/', // Default child route for '/', will inherit parent's path '/'
          name: 'home',
          component: HomeView
        },
        {
          path: 'sejarah',
          name: 'sejarah',
          component: () => import('../views/Sejarah.vue')
        },
        {
          path: 'visi-misi',
          name: 'visi-misi',
          component: VisiMisi
        },
        {
          path: 'fasilitas',
          name: 'fasilitas',
          component: Fasilitas
        },
        {
          path: 'guru-staf',
          name: 'guru-staf',
          component: GuruStaf
        },
        {
          path: 'kurikulum',
          name: 'kurikulum',
          component: Kurikulum
        },
        {
          path: 'alumni',
          name: 'alumni',
          component: Alumni
        },
        {
          path: 'program-jurusan',
          name: 'program-jurusan',
          component: () => import('../views/ProgramJurusan.vue')
        },
        {
          path: 'ekstrakurikuler',
          name: 'ekstrakurikuler',
          component: () => import('../views/Ekstrakurikuler.vue')
        },
        {
          path: 'prestasi',
          name: 'prestasi',
          component: () => import('../views/Prestasi.vue')
        },
        {
          path: 'pendaftaran',
          name: 'pendaftaran',
          component: () => import('../views/Pendaftaran.vue')
        },
        {
          path: 'berita',
          name: 'berita',
          component: () => import('../views/Berita.vue')
        },
        {
          path: 'galeri',
          name: 'galeri',
          component: () => import('../views/Galeri.vue')
        },
        {
          path: 'artikel',
          name: 'artikel',
          component: () => import('../views/Artikel.vue')
        },
        {
          path: 'unduhan',
          name: 'unduhan',
          component: () => import('../views/UnduhanView.vue')
        },
      ]
    },
    {
      path: '/admin', // Parent route for admin section
      name: 'admin',
      component: AdminLayout, // Use the new AdminLayout
      meta: {
        requiresAuth: true,
        roles: ['super_admin', 'admin'] // Keduanya bisa akses area parent ini
      },
      children: [
        {
          path: 'dashboard', // Child route for the dashboard
          name: 'dashboard',
          component: () => import('../views/admin/dashboard.vue')
        },
        {
          path: 'sejarah', // Child route for sejarah
          name: 'admin-sejarah',
          component: () => import('../views/admin/AdminSejarah.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'visi-misi', // Child route for Visi & Misi
          name: 'admin-visi-misi',
          component: () => import('../views/admin/AdminVisiMisi.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'guru-staf',
          name: 'admin-guru-staf',
          component: () => import('../views/admin/AdminGuruStaf.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'fasilitas', // Child route for fasilitas
          name: 'admin-fasilitas',
          component: () => import('../views/admin/AdminFasilitas.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'kurikulum', // Child route for kurikulum
          name: 'admin-kurikulum',
          component: () => import('../views/admin/AdminKurikulum.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'program-jurusan', // Child route for Program Jurusan
          name: 'admin-program-jurusan',
          component: () => import('../views/admin/AdminProgramJurusan.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'jadwal-pelajaran',
          name: 'admin-jadwal-pelajaran',
          component: () => import('../views/admin/AdminJadwalPelajaran.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'ekstrakurikuler',
          name: 'admin-ekstrakurikuler',
          component: () => import('../views/admin/AdminEkstrakurikuler.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'prestasi',
          name: 'admin-prestasi',
          component: () => import('../views/admin/AdminPrestasi.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'berita',
          name: 'admin-berita',
          component: () => import('../views/admin/AdminBerita.vue')
          // Admin biasa diizinkan akses
        },
        {
          path: 'galeri',
          name: 'admin-galeri',
          component: () => import('../views/admin/AdminGaleri.vue')
          // Admin biasa diizinkan akses
        },
        {
          path: 'unduhan',
          name: 'admin-unduhan',
          component: () => import('../views/admin/AdminUnduhan.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'validasi-konten',
          name: 'admin-validasi-konten',
          component: () => import('../views/admin/AdminValidasiKonten.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'info-ppdb',
          name: 'admin-info-ppdb',
          component: () => import('../views/admin/AdminInfoPPDB.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'pengguna',
          name: 'admin-pengguna',
          component: () => import('../views/admin/AdminPengguna.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'data-kelas',
          name: 'admin-data-kelas',
          component: () => import('../views/admin/AdminDataKelas.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'data-siswa',
          name: 'admin-data-siswa',
          component: () => import('../views/admin/AdminDataSiswa.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'data-alumni',
          name: 'admin-data-alumni',
          component: () => import('../views/admin/AdminDataAlumni.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'agenda',
          name: 'admin-agenda',
          component: () => import('../views/admin/AdminAgenda.vue'),
          meta: { roles: ['super_admin'] }
        },
        {
          path: 'pengaturan',
          name: 'admin-pengaturan',
          component: () => import('../views/admin/AdminPengaturan.vue')
          // Admin biasa diizinkan akses
        }

      ]
    }


  ]
})

router.beforeEach((to, from, next) => {
  // Ambil status login dari localStorage (atau bisa dari Pinia/Vuex jika ada)
  const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
  const userRole = localStorage.getItem('user_role'); // Ambil role aktif

  // Cek apakah rute memerlukan autentikasi (seperti halaman admin)
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isLoggedIn) {
      // Jika belum login, arahkan kembali ke halaman login
      next({ name: 'login' });
    } else {
      // Evaluasi RBAC (Role-Based Access Control)
      // Cari meta spesifik dari child route yang cocok
      const matchedRoute = to.matched.slice().reverse().find(r => r.meta && r.meta.roles);
      const roles = matchedRoute ? matchedRoute.meta.roles : (to.meta.roles || []);
      
      // Jika rute memiliki batasan roles dan role pengguna tidak termasuk
      if (roles.length > 0 && !roles.includes(userRole)) {
        next({ name: 'dashboard' }); // Tendang kembali ke dashboard / fallback
      } else {
        next(); // Diizinkan
      }
    }
  } else if (to.matched.some(record => record.meta.requiresGuest)) {
    if (isLoggedIn) {
      next({ name: 'dashboard' }); // Jika sudah login, jangan izinkan akses halaman login lagi
    } else {
      next();
    }
  } else {
    next(); // Untuk rute publik seperti beranda, fasiltias, dll
  }
});

// Global after hook untuk tracking pengunjung
router.afterEach((to, from) => {
  // Catat kunjungan jika mengakses rute publik (bukan halaman admin)
  if (!to.path.startsWith('/admin') && to.path !== '/login') {
    // Panggil API secara asinkron tanpa perlu menunggu respon (fire and forget)
    api.post('/api/track-visitor').catch(err => {
      console.error('Gagal mencatat pengunjung:', err);
    });
  }
});

export default router