import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import VisiMisi from '../views/VisiMisi.vue'
import FasilitasView from '../views/FasilitasView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/visi-misi',
      name: 'visi-misi',
      component: VisiMisi
    },
    {
      path: '/fasilitas',
      name: 'fasilitas',
      component: FasilitasView
    }
  ]
})

export default router