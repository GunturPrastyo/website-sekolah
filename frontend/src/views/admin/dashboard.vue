<script setup>
import { ref } from "vue";
import {
  PhStudent,
  PhChalkboardTeacher,
  PhEye,
  PhChartLine,
  PhClockClockwise,
  PhNewspaper,
} from "@phosphor-icons/vue";

const stats = ref([
  {
    title: "Total Siswa",
    value: "1,123",
    icon: PhStudent,
    color: "text-blue-500",
    bgColor: "bg-blue-100 dark:bg-blue-900/40",
  },
  {
    title: "Total Guru",
    value: "75",
    icon: PhChalkboardTeacher,
    color: "text-emerald-500",
    bgColor: "bg-emerald-100 dark:bg-emerald-900/40",
  },
  {
    title: "Artikel Diterbitkan",
    value: "48",
    icon: PhNewspaper,
    color: "text-amber-500",
    bgColor: "bg-amber-100 dark:bg-amber-900/40",
  },
  {
    title: "Pengunjung Hari Ini",
    value: "2,456",
    icon: PhEye,
    color: "text-indigo-500",
    bgColor: "bg-indigo-100 dark:bg-indigo-900/40",
  },
]);
</script>

<template>
  <div class="flex h-screen bg-gray-100 dark:bg-slate-900 font-sans">
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- MAIN -->
      <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
        <!-- HEADER -->
        <div class="mb-8 flex items-center justify-between">
          <div>
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Dashboard</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
              Ringkasan data & aktivitas terbaru
            </p>
          </div>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
          <div
            v-for="stat in stats"
            :key="stat.title"
            class="group bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-xl transition-all duration-300"
          >
            <div class="flex items-center justify-between mb-4">
              <div
                class="w-14 h-14 rounded-xl flex items-center justify-center"
                :class="[stat.bgColor, stat.color]"
              >
                <component :is="stat.icon" :size="28" />
              </div>
            </div>

            <div class="text-3xl font-bold text-gray-800 dark:text-white">
              {{ stat.value }}
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
              {{ stat.title }}
            </div>

            <!-- subtle hover line -->
            <div
              class="mt-4 h-1 w-0 group-hover:w-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-300"
            ></div>
          </div>
        </div>

        <!-- CONTENT -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mt-10">
          <!-- CHART -->
          <div
            class="xl:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm"
          >
            <div class="flex items-center justify-between mb-6">
              <h3 class="font-semibold text-gray-800 dark:text-white flex items-center">
                <PhChartLine :size="20" class="mr-2 text-blue-500" />
                Statistik Pengunjung
              </h3>
            </div>

            <div
              class="h-80 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 dark:from-slate-700/40 dark:to-slate-800 flex items-center justify-center"
            >
              <p class="text-gray-400 text-sm">[Chart Area]</p>
            </div>
          </div>

          <!-- ACTIVITY -->
          <div
            class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm"
          >
            <h3
              class="font-semibold text-gray-800 dark:text-white mb-6 flex items-center"
            >
              <PhClockClockwise :size="20" class="mr-2 text-blue-500" />
              Aktivitas Terbaru
            </h3>

            <div class="space-y-5">
              <div class="flex gap-3 group">
                <div
                  class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-500 text-xs font-bold"
                >
                  AF
                </div>
                <div>
                  <p class="text-sm text-gray-700 dark:text-gray-300">
                    <span class="font-semibold">Ahmad Fauzi</span> menerbitkan artikel
                  </p>
                  <p class="text-xs text-gray-400 mt-1">2 jam lalu</p>
                </div>
              </div>

              <div class="flex gap-3">
                <div
                  class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-500 text-xs font-bold"
                >
                  SA
                </div>
                <div>
                  <p class="text-sm text-gray-700 dark:text-gray-300">
                    <span class="font-semibold">Siti Aminah</span> update halaman
                  </p>
                  <p class="text-xs text-gray-400 mt-1">5 jam lalu</p>
                </div>
              </div>

              <div class="flex gap-3">
                <div
                  class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-500 text-xs font-bold"
                >
                  RP
                </div>
                <div>
                  <p class="text-sm text-gray-700 dark:text-gray-300">
                    <span class="font-semibold">Rizky Pratama</span> daftar siswa
                  </p>
                  <p class="text-xs text-gray-400 mt-1">1 hari lalu</p>
                </div>
              </div>

              <div class="flex gap-3">
                <div
                  class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-500 text-xs font-bold"
                >
                  AF
                </div>
                <div>
                  <p class="text-sm text-gray-700 dark:text-gray-300">
                    <span class="font-semibold">Ahmad Fauzi</span> hapus komentar
                  </p>
                  <p class="text-xs text-gray-400 mt-1">2 hari lalu</p>
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
.font-sans {
  font-family: "Poppins", sans-serif;
}
</style>
