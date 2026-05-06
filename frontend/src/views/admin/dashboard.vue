<script setup>
import { ref } from "vue";
import {
  PhStudent,
  PhChalkboardTeacher,
  PhEye,
  PhChartLine,
  PhClockClockwise,
  PhNewspaper, // Keep PhNewspaper if used in stats
} from "@phosphor-icons/vue";

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
    <!-- Konten utama -->
    <div class="flex-1 flex flex-col overflow-hidden">
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
