<script setup>
import { ref } from 'vue';
import AdminSidebar from '@/views/admin/components/AdminSidebar.vue';
import AdminNavbar from '@/views/admin/components/AdminNavbar.vue';

const isSidebarOpen = ref(true); // Default untuk terbuka di desktop

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
  <div class="flex h-screen bg-gray-100 dark:bg-slate-900 font-sans">
    <AdminSidebar :is-open="isSidebarOpen" />

    <!-- Konten utama -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <AdminNavbar @toggle-sidebar="toggleSidebar" />

      <!-- Konten Dasbor -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-slate-900 p-6 md:p-8">
        <router-view />
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Menggunakan font Poppins dari file HTML, jika tidak ada, gunakan sans-serif */
.font-sans {
  font-family: 'Poppins', sans-serif;
}
</style>