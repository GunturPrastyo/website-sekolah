import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import VisiMisi from '@/views/VisiMisi.vue'
import Fasilitas from '@/views/FasilitasView.vue'
import GuruStaf from '@/views/GuruStaf.vue'
import Kurikulum from '@/views/KurikulumView.vue'
import Alumni from '@/views/AlumniView.vue'
import AdminLayout from '@/layouts/AdminLayout.vue' // Import the new AdminLayout

import PublicLayout from '@/layouts/PublicLayout.vue' // Import PublicLayout

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
      children: [
        {
          path: 'dashboard', // Child route for the dashboard
          name: 'dashboard',
          component: () => import('../views/admin/dashboard.vue')
        },
        {
          path: 'sejarah', // Child route for sejarah
          name: 'admin-sejarah',
          component: () => import('../views/admin/AdminSejarah.vue')
        },
        {
          path: 'visi-misi', // Child route for Visi & Misi
          name: 'admin-visi-misi',
          component: () => import('../views/admin/AdminVisiMisi.vue')
        },
        {
          path: 'guru-staf',
          name: 'admin-guru-staf',
          component: () => import('../views/admin/AdminGuruStaf.vue')
        },
        {
          path: 'fasilitas', // Child route for fasilitas
          name: 'admin-fasilitas',
          component: () => import('../views/admin/AdminFasilitas.vue')
        },
        {
          path: 'kurikulum', // Child route for kurikulum
          name: 'admin-kurikulum',
          component: () => import('../views/admin/AdminKurikulum.vue')
        },
        {
          path: 'program-jurusan', // Child route for Program Jurusan
          name: 'admin-program-jurusan',
          component: () => import('../views/admin/AdminProgramJurusan.vue')
        },
        {
          path: 'jadwal-pelajaran',
          name: 'admin-jadwal-pelajaran',
          component: () => import('../views/admin/AdminJadwalPelajaran.vue')
        },
        {
          path: 'ekstrakurikuler',
          name: 'admin-ekstrakurikuler',
          component: () => import('../views/admin/AdminEkstrakurikuler.vue')
        },
        {
          path: 'prestasi',
          name: 'admin-prestasi',
          component: () => import('../views/admin/AdminPrestasi.vue')
        },
        {
          path: 'berita',
          name: 'admin-berita',
          component: () => import('../views/admin/AdminBerita.vue')
        },
        {
          path: 'galeri',
          name: 'admin-galeri',
          component: () => import('../views/admin/AdminGaleri.vue')
        },
        {
          path: 'unduhan',
          name: 'admin-unduhan',
          component: () => import('../views/admin/AdminUnduhan.vue')
        },
        {
          path: 'validasi-konten',
          name: 'admin-validasi-konten',
          component: () => import('../views/admin/AdminValidasiKonten.vue')
        },
        {
          path: 'info-ppdb',
          name: 'admin-info-ppdb',
          component: () => import('../views/admin/AdminInfoPPDB.vue')
        },
        {
          path: 'pengguna',
          name: 'admin-pengguna',
          component: () => import('../views/admin/AdminPengguna.vue')
        },
        {
          path: 'data-kelas',
          name: 'admin-data-kelas',
          component: () => import('../views/admin/AdminDataKelas.vue')
        },
        {
          path: 'data-siswa',
          name: 'admin-data-siswa',
          component: () => import('../views/admin/AdminDataSiswa.vue')
        },
        {
          path: 'data-alumni',
          name: 'admin-data-alumni',
          component: () => import('../views/admin/AdminDataAlumni.vue')
        },
        {
          path: 'agenda',
          name: 'admin-agenda',
          component: () => import('../views/admin/AdminAgenda.vue')
        },
        {
          path: 'pengaturan',
          name: 'admin-pengaturan',
          component: () => import('../views/admin/AdminPengaturan.vue')
        }

      ]
    }


  ]
})

export default router