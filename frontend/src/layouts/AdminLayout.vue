<script setup>
import { ref, onMounted } from "vue";
import AdminSidebar from "@/components/admin/AdminSidebar.vue"; // Corrected path
import AdminNavbar from "@/components/admin/AdminNavbar.vue"; // Corrected path

// Set default true untuk desktop, akan disesuaikan untuk mobile saat mounted
const isSidebarOpen = ref(true);

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

// Fungsi untuk memeriksa ukuran layar dan mengatur status sidebar saat pertama kali dimuat
const checkScreenSize = () => {
  if (typeof window !== "undefined" && window.innerWidth < 1024) {
    isSidebarOpen.value = false;
  }
};

onMounted(() => {
  checkScreenSize(); // Cek saat komponen pertama kali dimuat
});
</script>

<template>
  <div class="flex h-screen bg-gray-100 dark:bg-slate-900 font-sans">
    <!-- Tambahkan listener @close -->
    <AdminSidebar :is-open="isSidebarOpen" @close="isSidebarOpen = false" />

    <!-- Konten utama -->
    <div
      :class="[
        'flex-1 flex flex-col w-full overflow-hidden transition-all duration-300 ease-in-out',
        { 'lg:ml-64': isSidebarOpen },
      ]"
    >
      <AdminNavbar @toggle-sidebar="toggleSidebar" />

      <!-- Konten Dasbor -->
      <main
        class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-slate-900"
      >
        <router-view v-slot="{ Component, route }">
          <keep-alive :max="10">
            <component :is="Component" :key="route.path" />
          </keep-alive>
        </router-view>
      </main>
    </div>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Merriweather:wght@700;900&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap");

/* Menggunakan font Poppins dari file HTML, jika tidak ada, gunakan sans-serif */
.font-sans {
  font-family: "Poppins", sans-serif;
}
</style>
