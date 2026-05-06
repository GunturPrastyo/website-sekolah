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
          component: () => import('../views/admin/Dashboard.vue')
        }
        // Add other admin routes here as children
      ]
    }


  ]
})

export default router