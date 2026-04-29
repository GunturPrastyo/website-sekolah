<script setup>
import { ref, computed, onMounted, onUpdated, nextTick } from "vue";
import { createIcons, icons } from "lucide";
import PageHeader from "@/components/PageHeader.vue";

const activeFilter = ref("semua");
const activeType = ref("semua");
const activeYear = ref("semua");

const searchQuery = ref("");

const types = [
  { id: "semua", name: "Semua Bidang" },
  { id: "Akademik", name: "Akademik" },
  { id: "Non-Akademik", name: "Non-Akademik" },
];

const years = [
  { id: "semua", name: "Semua Tahun" },
  { id: 2026, name: "2026" },
  { id: 2025, name: "2025" },
  { id: 2024, name: "2024" },
  { id: 2023, name: "2023" },
];

const filters = [
  { id: "semua", name: "Semua Tingkat" },
  { id: "internasional", name: "Internasional" },
  { id: "nasional", name: "Nasional" },
  { id: "provinsi", name: "Provinsi" },
  { id: "kabupaten", name: "Kabupaten/Kota" },
];

// Data Dummy Prestasi
const prestasiList = ref([
  {
    id: 1,
    title: "Olimpiade Sains Nasional (OSN) Matematika",
    winner: "Budi Santoso",
    rank: 1,
    level: "nasional",
    year: 2025,
    type: "Akademik",
    image: "https://images.unsplash.com/photo-1567057419565-4349c49d8a04?q=80&w=800",
    newsLink: "/berita",
  },
  {
    id: 2,
    title: "Lomba Inovasi Robotika Pelajar Internasional",
    winner: "Tim RoboTech SMAN 1",
    rank: 2,
    level: "internasional",
    year: 2025,
    type: "Non-Akademik",
    image: "https://images.unsplash.com/photo-1561557944-6e7860d1a7eb?q=80&w=800",
    newsLink: "/berita",
  },
  {
    id: 3,
    title: "Kejuaraan Pencak Silat Tingkat Pelajar",
    winner: "Andi Darmawan",
    rank: 1,
    level: "provinsi",
    year: 2024,
    type: "Non-Akademik",
    image: "https://images.unsplash.com/photo-1555597673-b21d5c935865?q=80&w=800",
  },
  {
    id: 4,
    title: "Lomba Debat Bahasa Inggris Nasional",
    winner: "Siti Aminah & Tim",
    rank: 3,
    level: "nasional",
    year: 2024,
    type: "Akademik",
    image: "https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=800",
  },
  {
    id: 5,
    title: "Festival Tari Tradisional Daerah",
    winner: "Ekskul Tari Kinasih",
    rank: 1,
    level: "kabupaten",
    year: 2024,
    type: "Non-Akademik",
    image: "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800",
  },
  {
    id: 6,
    title: "Lomba Karya Tulis Ilmiah (LKIR) Nasional",
    winner: "Dina & Risa",
    rank: 2,
    level: "nasional",
    year: 2023,
    type: "Akademik",
    image: "https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800",
    newsLink: "/berita",
  },
]);

const filteredPrestasi = computed(() => {
  let filtered = prestasiList.value;

  if (activeFilter.value !== "semua") {
    filtered = filtered.filter((p) => p.level === activeFilter.value);
  }

  if (activeType.value !== "semua") {
    filtered = filtered.filter((p) => p.type === activeType.value);
  }

  if (activeYear.value !== "semua") {
    filtered = filtered.filter((p) => p.year === activeYear.value);
  }

  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(
      (p) =>
        p.title.toLowerCase().includes(query) || p.winner.toLowerCase().includes(query)
    );
  }

  // Sorting Bawaan (Selalu yang Terbaru di atas)
  filtered = [...filtered].sort((a, b) => {
    return b.year - a.year;
  });

  return filtered;
});

// Hitung jumlah prestasi berdasarkan tingkat
const counts = computed(() => ({
  internasional: prestasiList.value.filter((p) => p.level === "internasional").length,
  nasional: prestasiList.value.filter((p) => p.level === "nasional").length,
  provinsi: prestasiList.value.filter((p) => p.level === "provinsi").length,
  kabupaten: prestasiList.value.filter((p) => p.level === "kabupaten").length,
}));

const animatedCounts = ref({
  internasional: 0,
  nasional: 0,
  provinsi: 0,
  kabupaten: 0,
});

const animateValue = (key, target, duration = 2000) => {
  if (target === 0) return;
  let startTimestamp = null;
  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    // Efek easing (easeOutQuart) agar perlahan melambat di akhir
    const easeProgress = 1 - Math.pow(1 - progress, 4);
    animatedCounts.value[key] = Math.floor(easeProgress * target);
    if (progress < 1) {
      window.requestAnimationFrame(step);
    } else {
      animatedCounts.value[key] = target;
    }
  };
  window.requestAnimationFrame(step);
};

// Fungsi untuk menentukan warna dan badge berdasarkan ranking juara
const getRankStyle = (rank) => {
  switch (rank) {
    case 1:
      return {
        badge:
          "bg-gradient-to-r from-yellow-400 to-yellow-600 text-white shadow-yellow-500/50",
        text: "Juara 1 (Emas)",
        icon: "medal",
        border: "border-yellow-400",
      };
    case 2:
      return {
        badge: "bg-gradient-to-r from-gray-300 to-gray-500 text-white shadow-gray-500/50",
        text: "Juara 2 (Perak)",
        icon: "medal",
        border: "border-gray-400",
      };
    case 3:
      return {
        badge:
          "bg-gradient-to-r from-amber-600 to-orange-700 text-white shadow-orange-500/50",
        text: "Juara 3 (Perunggu)",
        icon: "medal",
        border: "border-orange-500",
      };
    default:
      return {
        badge: "bg-gradient-to-r from-blue-400 to-blue-600 text-white shadow-blue-500/50",
        text: "Finalis / Penghargaan",
        icon: "award",
        border: "border-blue-400",
      };
  }
};

onMounted(() => {
  createIcons({ icons });
  animateValue("internasional", counts.value.internasional);
  animateValue("nasional", counts.value.nasional);
  animateValue("provinsi", counts.value.provinsi);
  animateValue("kabupaten", counts.value.kabupaten);
});

// Re-render icon lucide setiap kali filter berpindah (DOM berubah)
onUpdated(() => {
  nextTick(() => {
    createIcons({ icons });
  });
});
</script>

<template>
  <div>
    <PageHeader
      badge="Hall of Fame"
      title="Prestasi & Penghargaan"
      description="Dedikasi, kerja keras, dan semangat pantang menyerah siswa-siswi kami yang berhasil menorehkan tinta emas di berbagai ajang perlombaan bergengsi."
    >
      <template #bg-ornament>
        <div
          class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-[0.03] text-yellow-500 pointer-events-none"
        >
          <i data-lucide="trophy" class="w-[400px] h-[400px]"></i>
        </div>
      </template>
    </PageHeader>

    <!-- Main Content Section -->
    <section class="pb-24 px-6 bg-gray-50 dark:bg-slate-900 min-h-screen">
      <div class="container mx-auto max-w-full px-0 lg:px-8">
        <!-- Statistik Prestasi (Angka Berjalan) -->
        <div
          class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-10 -mt-12 md:-mt-16 relative z-20"
        >
          <!-- Internasional -->
          <div
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-5 md:p-6 flex flex-col items-center border border-gray-100 dark:border-slate-700 transform transition-transform hover:-translate-y-1"
          >
            <div
              class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-blue-50 dark:bg-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-3 md:mb-4"
            >
              <i data-lucide="globe-2" class="w-5 h-5 md:w-6 md:h-6"></i>
            </div>
            <p class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-1">
              {{ animatedCounts.internasional }}
            </p>
            <h4
              class="text-gray-500 dark:text-gray-400 text-xs md:text-sm font-semibold uppercase tracking-wider text-center"
            >
              Internasional
            </h4>
          </div>

          <!-- Nasional -->
          <div
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-5 md:p-6 flex flex-col items-center border border-gray-100 dark:border-slate-700 transform transition-transform hover:-translate-y-1"
          >
            <div
              class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-red-50 dark:bg-slate-700 flex items-center justify-center text-red-600 dark:text-red-400 mb-3 md:mb-4"
            >
              <i data-lucide="flag" class="w-5 h-5 md:w-6 md:h-6"></i>
            </div>
            <p class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-1">
              {{ animatedCounts.nasional }}
            </p>
            <h4
              class="text-gray-500 dark:text-gray-400 text-xs md:text-sm font-semibold uppercase tracking-wider text-center"
            >
              Nasional
            </h4>
          </div>

          <!-- Provinsi -->
          <div
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-5 md:p-6 flex flex-col items-center border border-gray-100 dark:border-slate-700 transform transition-transform hover:-translate-y-1"
          >
            <div
              class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-50 dark:bg-slate-700 flex items-center justify-center text-green-600 dark:text-green-400 mb-3 md:mb-4"
            >
              <i data-lucide="map" class="w-5 h-5 md:w-6 md:h-6"></i>
            </div>
            <p class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-1">
              {{ animatedCounts.provinsi }}
            </p>
            <h4
              class="text-gray-500 dark:text-gray-400 text-xs md:text-sm font-semibold uppercase tracking-wider text-center"
            >
              Provinsi
            </h4>
          </div>

          <!-- Kabupaten -->
          <div
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-5 md:p-6 flex flex-col items-center border border-gray-100 dark:border-slate-700 transform transition-transform hover:-translate-y-1"
          >
            <div
              class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-amber-50 dark:bg-slate-700 flex items-center justify-center text-amber-600 dark:text-amber-400 mb-3 md:mb-4"
            >
              <i data-lucide="building-2" class="w-5 h-5 md:w-6 md:h-6"></i>
            </div>
            <p class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-1">
              {{ animatedCounts.kabupaten }}
            </p>
            <h4
              class="text-gray-500 dark:text-gray-400 text-xs md:text-sm font-semibold uppercase tracking-wider text-center"
            >
              Kabupaten
            </h4>
          </div>
        </div>

        <!-- Search Bar, Filter Selects, & Filter Tabs -->
        <div class="flex flex-col gap-5 md:gap-6 mb-12 relative z-20">
          <!-- Top Row: Selects and Search -->
          <div class="flex flex-row items-center gap-2.5 md:gap-4 w-full">
            <!-- Filter Bidang -->
            <div class="relative w-12 md:w-48 shrink-0">
              <i
                data-lucide="book-open"
                class="absolute left-1/2 md:left-4 top-1/2 -translate-x-1/2 md:translate-x-0 -translate-y-1/2 w-5 h-5 md:w-4 md:h-4 pointer-events-none transition-colors"
                :class="
                  activeType !== 'semua'
                    ? 'text-blue-600 dark:text-blue-400'
                    : 'text-gray-500 md:text-gray-400'
                "
              ></i>
              <select
                v-model="activeType"
                class="w-full md:pl-10 md:pr-10 py-3.5 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-transparent md:text-gray-900 dark:md:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm text-sm appearance-none cursor-pointer"
              >
                <option
                  v-for="t in types"
                  :key="t.id"
                  :value="t.id"
                  class="text-gray-900 dark:text-white"
                >
                  {{ t.name }}
                </option>
              </select>
              <div
                class="absolute inset-y-0 right-4 hidden md:flex items-center pointer-events-none"
              >
                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
              </div>
            </div>

            <!-- Filter Tahun -->
            <div class="relative w-12 md:w-52 shrink-0">
              <i
                data-lucide="calendar"
                class="absolute left-1/2 md:left-4 top-1/2 -translate-x-1/2 md:translate-x-0 -translate-y-1/2 w-5 h-5 md:w-4 md:h-4 pointer-events-none transition-colors"
                :class="
                  activeYear !== 'semua'
                    ? 'text-blue-600 dark:text-blue-400'
                    : 'text-gray-500 md:text-gray-400'
                "
              ></i>
              <select
                v-model="activeYear"
                class="w-full md:pl-10 md:pr-10 py-3.5 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-transparent md:text-gray-900 dark:md:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm text-sm appearance-none cursor-pointer"
              >
                <option
                  v-for="y in years"
                  :key="y.id"
                  :value="y.id"
                  class="text-gray-900 dark:text-white"
                >
                  {{ y.name }}
                </option>
              </select>
              <div
                class="absolute inset-y-0 right-4 hidden md:flex items-center pointer-events-none"
              >
                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
              </div>
            </div>

            <!-- Search Bar -->
            <div class="relative flex-1 shrink-0 min-w-0">
              <i
                data-lucide="search"
                class="absolute left-3 md:left-4 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-gray-400"
              ></i>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari nama siswa atau judul perlombaan..."
                class="w-full pl-9 md:pl-11 pr-3 md:pr-4 py-3.5 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm text-sm text-ellipsis"
              />
            </div>
          </div>

          <!-- Bottom Row: Filter Tabs (Tingkat) -->
          <div
            class="w-full bg-white dark:bg-slate-800 p-4 lg:p-5 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center gap-4"
          >
            <h4
              class="text-sm font-bold text-gray-900 dark:text-white whitespace-nowrap shrink-0 flex items-center sm:pl-2"
            >
              <i
                data-lucide="filter"
                class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400"
              ></i>
              Tingkat Lomba:
            </h4>
            <div class="flex flex-wrap items-center gap-2 md:gap-2.5">
              <button
                v-for="filter in filters"
                :key="filter.id"
                @click="activeFilter = filter.id"
                class="px-3.5 md:px-4 py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-300 focus:outline-none flex items-center border"
                :class="
                  activeFilter === filter.id
                    ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/30'
                    : 'bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-slate-600 hover:border-blue-300 hover:text-blue-600 dark:hover:text-blue-400'
                "
              >
                {{ filter.name }}
              </button>
            </div>
          </div>
        </div>

        <!-- Daftar Prestasi dengan Efek Papan Penghargaan -->
        <TransitionGroup
          name="list"
          tag="div"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative"
        >
          <div
            v-for="prestasi in filteredPrestasi"
            :key="prestasi.id"
            class="group relative bg-white dark:bg-slate-800 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 dark:border-slate-700 flex flex-col h-full cursor-default transform hover:-translate-y-2"
          >
            <!-- Gambar Sertifikat / Lomba -->
            <div
              class="relative h-48 overflow-hidden shrink-0 border-b-[6px]"
              :class="getRankStyle(prestasi.rank).border"
            >
              <img
                :src="prestasi.image"
                :alt="prestasi.title"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-80"
              ></div>

              <!-- Lencana Juara Pojok Atas Kanan -->
              <div
                class="absolute top-4 right-4 flex flex-col items-center justify-center w-14 h-16 rounded-b-full shadow-lg z-10"
                :class="getRankStyle(prestasi.rank).badge"
              >
                <i
                  :data-lucide="getRankStyle(prestasi.rank).icon"
                  class="w-6 h-6 mt-1 mb-0.5"
                  :class="prestasi.rank === 1 ? 'fill-yellow-100' : ''"
                ></i>
                <span class="text-[10px] font-black uppercase">{{
                  prestasi.rank === 1 ? "1st" : prestasi.rank === 2 ? "2nd" : "3rd"
                }}</span>
              </div>

              <!-- Kategori Tag -->
              <div
                class="absolute bottom-3 left-4 px-2.5 py-1 bg-black/50 backdrop-blur-md text-white text-[10px] font-semibold rounded uppercase tracking-wider"
              >
                Tingkat <span class="text-yellow-400">{{ prestasi.level }}</span>
              </div>
            </div>

            <!-- Konten Kartu Prestasi -->
            <div
              class="p-6 flex flex-col flex-1 relative bg-white dark:bg-slate-800 z-10"
            >
              <div
                class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3 font-semibold"
              >
                <span class="bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded">{{
                  prestasi.type
                }}</span>
                <span class="flex items-center"
                  ><i data-lucide="calendar" class="w-3.5 h-3.5 mr-1"></i>
                  {{ prestasi.year }}</span
                >
              </div>

              <h3
                class="text-lg font-bold text-gray-900 dark:text-white mb-2 leading-snug"
              >
                {{ prestasi.title }}
              </h3>

              <!-- Tautan Berita Terkait (Opsional) -->
              <router-link
                v-if="prestasi.newsLink"
                :to="prestasi.newsLink"
                class="inline-flex items-center text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors mb-4 w-fit"
              >
                Baca Liputan Berita
                <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1"></i>
              </router-link>

              <div
                class="mt-auto pt-5 border-t border-gray-100 dark:border-slate-700 flex items-center gap-3"
              >
                <div
                  class="w-10 h-10 rounded-full bg-blue-100 dark:bg-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0"
                >
                  <i data-lucide="user" class="w-5 h-5"></i>
                </div>
                <div>
                  <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                    Peraih Penghargaan
                  </p>
                  <p
                    class="text-sm font-bold text-gray-900 dark:text-white leading-tight"
                  >
                    {{ prestasi.winner }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </TransitionGroup>

        <!-- Empty State -->
        <div
          v-if="filteredPrestasi.length === 0"
          class="py-20 text-center bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm mt-4"
        >
          <div
            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-700 mb-4 text-gray-400"
          >
            <i data-lucide="award" class="w-8 h-8"></i>
          </div>
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tidak Ditemukan</h3>
          <p class="text-gray-500 dark:text-gray-400 mt-1">
            Data prestasi yang Anda cari tidak ditemukan. Coba kata kunci atau filter
            lain.
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.list-move,
.list-enter-active,
.list-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: scale(0.9) translateY(30px);
}
.list-leave-active {
  position: absolute;
}
</style>
