import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import VisiMisi from '@/views/VisiMisi.vue'
import Fasilitas from '@/views/FasilitasView.vue'
import GuruStaf from '@/views/GuruStaf.vue'
import Kurikulum from '@/views/KurikulumView.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/sejarah',
      name: 'sejarah',
      component: () => import('../views/Sejarah.vue')
    },
    {
      path: '/visi-misi',
      name: 'visi-misi',
      component: VisiMisi
    },
    {
      path: '/fasilitas',
      name: 'fasilitas',
      component: Fasilitas
    },
    {
      path: '/guru-staf',
      name: 'guru-staf',
      component: GuruStaf
    },
    {
      path: '/kurikulum',
      name: 'kurikulum',
      component: Kurikulum
    },
    {
      path: '/program-jurusan',
      name: 'program-jurusan',
      component: () => import('../views/ProgramJurusan.vue')
    },
    {
      path: '/ekstrakurikuler',
      name: 'ekstrakurikuler',
      component: () => import('../views/Ekstrakurikuler.vue')
    },
    {
      path: '/prestasi',
      name: 'prestasi',
      component: () => import('../views/Prestasi.vue')
    }


  ]
})

export default router