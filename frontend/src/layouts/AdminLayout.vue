<script setup>
import { ref } from "vue";
import AdminSidebar from "@/components/admin/AdminSidebar.vue"; // Corrected path
import AdminNavbar from "@/components/admin/AdminNavbar.vue"; // Corrected path

const isSidebarOpen = ref(true); // Default untuk terbuka di desktop

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
  <div class="flex h-screen bg-gray-100 dark:bg-slate-900 font-sans">
    <AdminSidebar :is-open="isSidebarOpen" />

    <!-- Konten utama -->
    <div
      class="flex-1 flex flex-col overflow-hidden transition-all duration-300 ease-in-out"
      :class="{ 'lg:ml-64': isSidebarOpen, 'lg:ml-0': !isSidebarOpen }"
    >
      <AdminNavbar @toggle-sidebar="toggleSidebar" />

      <!-- Konten Dasbor -->
      <main
        class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-slate-900"
      >
        <router-view />
      </main>
    </div>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&display=swap");

/* Menggunakan font Poppins dari file HTML, jika tidak ada, gunakan sans-serif */
.font-sans {
  font-family: "Poppins", sans-serif;
}
</style>
