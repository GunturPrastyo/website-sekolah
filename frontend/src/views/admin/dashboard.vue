<script setup>
import { ref, markRaw } from "vue";
import {
  PhUsers,
  PhChalkboardTeacher,
  PhEye,
  PhChartLine,
  PhClock,
  PhNewspaper,
} from "@phosphor-icons/vue";

const stats = ref([
  {
    title: "Total Siswa",
    value: "1,123",
    icon: markRaw(PhUsers),
    color: "text-blue-500",
    bgColor: "bg-blue-100 dark:bg-blue-900/40",
  },
  {
    title: "Total Guru",
    value: "75",
    icon: markRaw(PhChalkboardTeacher),
    color: "text-emerald-500",
    bgColor: "bg-emerald-100 dark:bg-emerald-900/40",
  },
  {
    title: "Artikel Diterbitkan",
    value: "48",
    icon: markRaw(PhNewspaper),
    color: "text-amber-500",
    bgColor: "bg-amber-100 dark:bg-amber-900/40",
  },
  {
    title: "Pengunjung Hari Ini",
    value: "2,456",
    icon: markRaw(PhEye),
    color: "text-indigo-500",
    bgColor: "bg-indigo-100 dark:bg-indigo-900/40",
  },
]);
</script>

<template>
  <!-- MAIN -->
  <main
    class="flex-1 overflow-y-auto px-6 md:px-8 py-8 bg-gray-50/30 dark:bg-slate-900/50"
  >
    <!-- HEADER -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2
          class="text-2xl font-bold text-gray-800 dark:text-white"
          style="font-family: 'Oswald', sans-serif"
        >
          Dashboard
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Ringkasan data & aktivitas terbaru sistem.
        </p>
      </div>
      <div class="flex items-center gap-3">
        <button
          class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm"
        >
          Unduh Laporan
        </button>
        <button
          class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors shadow-sm"
        >
          Buat Pengumuman
        </button>
      </div>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
      <div
        v-for="stat in stats"
        :key="stat.title"
        class="group bg-white dark:bg-slate-800 p-6 rounded-lg border border-gray-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden"
      >
        <div class="flex items-center justify-between mb-4">
          <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
            {{ stat.title }}
          </div>
          <div
            class="w-10 h-10 rounded-lg flex items-center justify-center"
            :class="[stat.bgColor, stat.color]"
          >
            <component :is="stat.icon" :size="20" weight="fill" />
          </div>
        </div>

        <div class="text-3xl font-bold text-gray-800 dark:text-white">
          {{ stat.value }}
        </div>

        <div class="mt-2 flex items-center text-xs">
          <span class="text-emerald-500 font-medium flex items-center">
            <svg
              class="w-3 h-3 mr-1"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
              />
            </svg>
            12%
          </span>
          <span class="text-gray-400 ml-2">dari bulan lalu</span>
        </div>

        <!-- subtle hover line -->
        <div
          class="absolute bottom-0 left-0 h-1 w-0 group-hover:w-full bg-blue-600 transition-all duration-300"
        ></div>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-6">
      <!-- CHART -->
      <div
        class="xl:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-lg border border-gray-200 dark:border-slate-700 shadow-sm flex flex-col"
      >
        <div class="flex items-center justify-between mb-6">
          <div>
            <h3 class="font-bold text-gray-800 dark:text-white flex items-center text-lg">
              <PhChartLine :size="20" class="mr-2 text-gray-500" />
              Statistik Pengunjung
            </h3>
            <p class="text-xs text-gray-500 mt-1">
              Data kunjungan website 30 hari terakhir
            </p>
          </div>
          <select
            class="text-sm border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 py-2 pl-3 pr-8"
          >
            <option>Bulan Ini</option>
            <option>Bulan Lalu</option>
            <option>Tahun Ini</option>
          </select>
        </div>

        <div
          class="flex-1 min-h-[300px] rounded-lg border border-dashed border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 flex flex-col items-center justify-center"
        >
          <PhChartLine :size="48" class="text-gray-300 dark:text-slate-600 mb-2" />
          <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">
            Grafik pengunjung akan tampil di sini
          </p>
          <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">
            Integrasikan dengan Google Analytics atau Chart.js
          </p>
        </div>
      </div>

      <!-- ACTIVITY -->
      <div
        class="bg-white dark:bg-slate-800 p-6 rounded-lg border border-gray-200 dark:border-slate-700 shadow-sm flex flex-col"
      >
        <div class="flex items-center justify-between mb-6">
          <h3 class="font-bold text-gray-800 dark:text-white flex items-center text-lg">
            <PhClock :size="20" class="mr-2 text-gray-500" />
            Aktivitas Terbaru
          </h3>
          <button
            class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium transition-colors"
          >
            Lihat Semua
          </button>
        </div>
        <div class="space-y-6 flex-1">
          <div class="flex gap-4 relative">
            <div
              class="absolute top-8 left-4 -ml-px h-full w-0.5 bg-gray-100 dark:bg-slate-700"
            ></div>
            <div
              class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/40 border-2 border-white dark:border-slate-800 flex items-center justify-center text-blue-600 text-xs font-bold z-10 shrink-0"
            >
              AF
            </div>
            <div class="pt-1.5 pb-2">
              <p class="text-sm text-gray-800 dark:text-gray-200 leading-snug">
                <span class="font-semibold text-gray-900 dark:text-white"
                  >Ahmad Fauzi</span
                >
                menerbitkan artikel baru "Prestasi Siswa Tingkat Nasional".
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                2 jam yang lalu
              </p>
            </div>
          </div>
          <div class="flex gap-4 relative">
            <div
              class="absolute top-8 left-4 -ml-px h-full w-0.5 bg-gray-100 dark:bg-slate-700"
            ></div>
            <div
              class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/40 border-2 border-white dark:border-slate-800 flex items-center justify-center text-emerald-600 text-xs font-bold z-10 shrink-0"
            >
              SA
            </div>
            <div class="pt-1.5 pb-2">
              <p class="text-sm text-gray-800 dark:text-gray-200 leading-snug">
                <span class="font-semibold text-gray-900 dark:text-white"
                  >Siti Aminah</span
                >
                memperbarui halaman Sejarah Sekolah.
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                5 jam yang lalu
              </p>
            </div>
          </div>
          <div class="flex gap-4 relative">
            <div
              class="absolute top-8 left-4 -ml-px h-full w-0.5 bg-gray-100 dark:bg-slate-700"
            ></div>
            <div
              class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/40 border-2 border-white dark:border-slate-800 flex items-center justify-center text-amber-600 text-xs font-bold z-10 shrink-0"
            >
              RP
            </div>
            <div class="pt-1.5 pb-2">
              <p class="text-sm text-gray-800 dark:text-gray-200 leading-snug">
                <span class="font-semibold text-gray-900 dark:text-white"
                  >Rizky Pratama</span
                >
                menambahkan 15 data siswa baru.
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                Kemarin, 14:30
              </p>
            </div>
          </div>
          <div class="flex gap-4 relative">
            <div
              class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/40 border-2 border-white dark:border-slate-800 flex items-center justify-center text-purple-600 text-xs font-bold z-10 shrink-0"
            >
              TR
            </div>
            <div class="pt-1.5 pb-2">
              <p class="text-sm text-gray-800 dark:text-gray-200 leading-snug">
                <span class="font-semibold text-gray-900 dark:text-white"
                  >Tim Redaksi</span
                >
                menghapus 2 komentar spam pada artikel.
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                2 hari yang lalu
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped>
.font-sans {
  font-family: "Poppins", sans-serif;
}
</style>
