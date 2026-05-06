<script setup>
import { ref } from "vue";
import {
  PhHouse,
  PhNewspaper,
  PhNote,
  PhUsers,
  PhGear,
  PhSignOut,
  PhMagnifyingGlass,
  PhBell,
  PhCaretDown,
  PhList,
  PhStudent,
  PhChalkboardTeacher,
  PhEye,
  PhChartLine,
  PhClockClockwise,
} from "@phosphor-icons/vue";

const isSidebarOpen = ref(true); // Default untuk terbuka di desktop

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const stats = ref([
  {
    title: "Total Siswa",
    value: "1,123",
    icon: PhStudent,
    color: "text-blue-500",
    bgColor: "bg-blue-100 dark:bg-blue-900/50",
  },
  {
    title: "Total Guru",
    value: "75",
    icon: PhChalkboardTeacher,
    color: "text-emerald-500",
    bgColor: "bg-emerald-100 dark:bg-emerald-900/50",
  },
  {
    title: "Artikel Diterbitkan",
    value: "48",
    icon: PhNewspaper,
    color: "text-amber-500",
    bgColor: "bg-amber-100 dark:bg-amber-900/50",
  },
  {
    title: "Pengunjung Hari Ini",
    value: "2,456",
    icon: PhEye,
    color: "text-indigo-500",
    bgColor: "bg-indigo-100 dark:bg-indigo-900/50",
  },
]);
</script>

<template>
  <div class="flex h-screen bg-gray-100 dark:bg-slate-900 font-sans">
    <!-- Sidebar -->
    <aside
      :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
      class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-slate-800 shadow-lg transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 lg:shadow-none"
    >
      <div
        class="flex items-center justify-center h-20 border-b border-gray-100 dark:border-slate-700"
      >
        <h1 class="text-2xl font-bold text-blue-600 dark:text-blue-400">Admin Panel</h1>
      </div>
      <nav class="mt-6 flex-1">
        <a
          href="#"
          class="flex items-center px-6 py-3 text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-300 border-l-4 border-blue-600"
        >
          <PhHouse :size="20" class="mr-3" />
          <span class="font-semibold">Dashboard</span>
        </a>
        <a
          href="#"
          class="flex items-center px-6 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors"
        >
          <PhNewspaper :size="20" class="mr-3" />
          <span>Berita & Artikel</span>
        </a>
        <a
          href="#"
          class="flex items-center px-6 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors"
        >
          <PhNote :size="20" class="mr-3" />
          <span>Halaman</span>
        </a>
        <a
          href="#"
          class="flex items-center px-6 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors"
        >
          <PhUsers :size="20" class="mr-3" />
          <span>Pengguna</span>
        </a>
        <a
          href="#"
          class="flex items-center px-6 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors"
        >
          <PhGear :size="20" class="mr-3" />
          <span>Pengaturan</span>
        </a>
      </nav>
      <div
        class="absolute bottom-0 w-full border-t border-gray-100 dark:border-slate-700"
      >
        <a
          href="#"
          class="flex items-center px-6 py-4 text-gray-600 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition-colors"
        >
          <PhSignOut :size="20" class="mr-3" />
          <span>Keluar</span>
        </a>
      </div>
    </aside>

    <!-- Konten utama -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Bilah Navigasi Atas -->
      <header
        class="flex items-center justify-between h-20 px-6 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 shadow-sm"
      >
        <div class="flex items-center">
          <button
            @click="toggleSidebar"
            class="lg:hidden text-gray-600 dark:text-gray-300 focus:outline-none mr-4"
          >
            <PhList :size="24" />
          </button>
          <div class="relative hidden md:block">
            <PhMagnifyingGlass
              class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
            />
            <input
              type="text"
              placeholder="Cari sesuatu..."
              class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
            />
          </div>
        </div>
        <div class="flex items-center gap-4">
          <button
            class="p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
          >
            <PhBell :size="20" />
          </button>
          <div class="relative group">
            <button class="flex items-center gap-2">
              <img
                src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop"
                alt="Admin"
                class="w-10 h-10 rounded-full object-cover border-2 border-blue-500"
              />
              <div class="hidden md:block text-left">
                <div class="font-semibold text-sm text-gray-800 dark:text-white">
                  Budi Santoso
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Admin Utama</div>
              </div>
              <PhCaretDown :size="16" class="text-gray-500 hidden md:block" />
            </button>
            <div
              class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-md shadow-lg py-1 hidden group-hover:block z-20 border border-gray-100 dark:border-slate-700"
            >
              <a
                href="#"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700"
                >Profil Saya</a
              >
              <a
                href="#"
                class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/50"
                >Keluar</a
              >
            </div>
          </div>
        </div>
      </header>

      <!-- Konten Dasbor -->
      <main
        class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-slate-900 p-6 md:p-8"
      >
        <div class="container mx-auto">
          <h2 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-6">
            Dashboard
          </h2>

          <!-- Kartu Statistik -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
              v-for="stat in stats"
              :key="stat.title"
              class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex items-center gap-5"
            >
              <div
                class="w-14 h-14 rounded-full flex items-center justify-center"
                :class="[stat.bgColor, stat.color]"
              >
                <component :is="stat.icon" :size="28" />
              </div>
              <div>
                <div class="text-3xl font-bold text-gray-800 dark:text-white">
                  {{ stat.value }}
                </div>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                  {{ stat.title }}
                </div>
              </div>
            </div>
          </div>

          <!-- Grafik dan Aktivitas Terbaru -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
            <!-- Grafik Pengunjung -->
            <div
              class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700"
            >
              <h3
                class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center"
              >
                <PhChartLine :size="22" class="mr-2 text-blue-500" />
                Grafik Pengunjung Website
              </h3>
              <div
                class="h-80 bg-gray-50 dark:bg-slate-700/50 rounded-lg flex items-center justify-center"
              >
                <p class="text-gray-400">[Placeholder untuk Grafik]</p>
              </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div
              class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700"
            >
              <h3
                class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center"
              >
                <PhClockClockwise :size="22" class="mr-2 text-blue-500" />
                Aktivitas Terbaru
              </h3>
              <div class="space-y-4">
                <div class="flex items-start gap-3">
                  <div
                    class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-500 flex items-center justify-center shrink-0 text-xs font-bold"
                  >
                    AF
                  </div>
                  <div>
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                      <span class="font-bold">Ahmad Fauzi</span> menerbitkan artikel baru.
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                      2 jam yang lalu
                    </p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <div
                    class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-500 flex items-center justify-center shrink-0 text-xs font-bold"
                  >
                    SA
                  </div>
                  <div>
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                      <span class="font-bold">Siti Aminah</span> memperbarui halaman 'Visi
                      & Misi'.
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                      5 jam yang lalu
                    </p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <div
                    class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-500 flex items-center justify-center shrink-0 text-xs font-bold"
                  >
                    RP
                  </div>
                  <div>
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                      <span class="font-bold">Rizky Pratama</span> mendaftar sebagai siswa
                      baru.
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                      1 hari yang lalu
                    </p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <div
                    class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-500 flex items-center justify-center shrink-0 text-xs font-bold"
                  >
                    AF
                  </div>
                  <div>
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                      <span class="font-bold">Ahmad Fauzi</span> menghapus sebuah
                      komentar.
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                      2 hari yang lalu
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Menggunakan font Poppins dari file HTML, jika tidak ada, gunakan sans-serif */
.font-sans {
  font-family: "Poppins", sans-serif;
}
</style>
