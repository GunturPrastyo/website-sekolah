<script setup>
import { ref, computed, onMounted, watch } from "vue";
import {
  PhTrophy,
  PhGlobe,
  PhFlag,
  PhMapPin,
  PhBuildings,
  PhBookOpen,
  PhCaretDown,
  PhMagnifyingGlass,
  PhCalendarBlank,
  PhFunnel,
  PhMedal,
  PhCertificate,
  PhArrowUpRight,
  PhUser,
  PhCaretLeft,
  PhCaretRight,
} from "@phosphor-icons/vue";
import api from "@/api/index.js";
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

const years = computed(() => {
  const yearsSet = new Set(prestasiList.value.map((p) => p.year));
  const sortedYears = Array.from(yearsSet).sort((a, b) => b - a);
  if (sortedYears.length === 0) {
    return [{ id: "semua", name: "Semua Tahun" }];
  }
  return [
    { id: "semua", name: "Semua Tahun" },
    ...sortedYears.map((y) => ({ id: y, name: y.toString() })),
  ];
});

const filters = [
  { id: "semua", name: "Semua Tingkat" },
  { id: "internasional", name: "Internasional" },
  { id: "nasional", name: "Nasional" },
  { id: "provinsi", name: "Provinsi" },
  { id: "kabupaten", name: "Kabupaten/Kota" },
];

const prestasiList = ref([]);
const isFetching = ref(true);

const getImageUrl = (path) => {
  if (!path)
    return "https://images.unsplash.com/photo-1567057419565-4349c49d8a04?q=80&w=800";
  if (path.startsWith("http") || path.startsWith("data:")) return path;
  const backendUrl = import.meta.env.VITE_API_URL || "http://localhost:8000";
  return `${backendUrl.replace(/\/+$/, "")}/${path.replace(/^\/+/, "")}`;
};

const fetchPrestasi = async () => {
  isFetching.value = true;
  try {
    const response = await api.get("/api/public-achievements");
    if (response.data && response.data.data) {
      prestasiList.value = response.data.data.map((item) => ({
        id: item.id,
        title: item.title || item.name,
        winner: item.winner || item.student_name || "Siswa",
        rank: parseInt(item.rank) || 1,
        level: (item.level || "nasional").toLowerCase(),
        year: parseInt(item.year) || new Date().getFullYear(),
        type: item.type || "Akademik",
        image: getImageUrl(item.image),
        newsLink: item.newsLink || null,
      }));
    }
  } catch (error) {
    console.error("Gagal memuat data prestasi:", error);
  } finally {
    isFetching.value = false;
  }
};

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

// Fitur Pagination
const currentPage = ref(1);
const itemsPerPage = 6;

const totalPages = computed(() => {
  return Math.ceil(filteredPrestasi.value.length / itemsPerPage);
});

const paginatedPrestasi = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredPrestasi.value.slice(start, end);
});

const changePage = (page) => {
  if (page < 1 || page > totalPages.value) return;
  isLoading.value = true;
  setTimeout(() => {
    currentPage.value = page;
    isLoading.value = false;
    window.scrollTo({ top: 400, behavior: "smooth" });
  }, 400);
};

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
        badge: "bg-[#FFD700] text-yellow-950 shadow-[#FFD700]/50",
        text: "Juara 1 (Emas)",
        icon: PhMedal,
        border: "border-[#FFD700]",
        iconFill: "fill-yellow-700",
      };
    case 2:
      return {
        badge: "bg-[#C0C0C0] text-gray-900 shadow-[#C0C0C0]/50",
        text: "Juara 2 (Perak)",
        icon: PhMedal,
        border: "border-[#C0C0C0]",
        iconFill: "fill-gray-600",
      };
    case 3:
      return {
        badge: "bg-[#CD7F32] text-white shadow-[#CD7F32]/50",
        text: "Juara 3 (Perunggu)",
        icon: PhMedal,
        border: "border-[#CD7F32]",
        iconFill: "fill-orange-200",
      };
    default:
      return {
        badge: "bg-blue-500 text-white shadow-blue-500/50",
        text: "Finalis / Penghargaan",
        icon: PhCertificate,
        border: "border-blue-500",
        iconFill: "fill-blue-200",
      };
  }
};

const isLoading = ref(false);
let filterTimeout = null;

watch([activeFilter, activeType, activeYear, searchQuery], () => {
  currentPage.value = 1;
  isLoading.value = true;
  if (filterTimeout) clearTimeout(filterTimeout);
  filterTimeout = setTimeout(() => {
    isLoading.value = false;
  }, 800); // Simulasi loading selama 800ms
});

onMounted(() => {
  fetchPrestasi();
});
</script>

<template>
  <div>
    <PageHeader
      badge="Hall of Fame"
      title="Prestasi & Penghargaan"
      description="Dedikasi, kerja keras, dan semangat pantang menyerah siswa-siswi kami yang berhasil menorehkan tinta emas di berbagai ajang perlombaan bergengsi."
      bgImage="https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=1600&auto=format&fit=crop"
    >
      <template #bg-ornament>
        <div
          class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-[0.03] text-yellow-500 pointer-events-none"
        >
          <PhTrophy class="w-[400px] h-[400px]" />
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
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-5 md:p-6 flex flex-col items-center border border-gray-100 dark:border-slate-700 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-blue-500/20"
          >
            <div
              class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-blue-600 dark:bg-blue-500 flex items-center justify-center text-white shadow-md mb-3 md:mb-4"
            >
              <PhGlobe class="w-5 h-5 md:w-6 md:h-6" />
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
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-5 md:p-6 flex flex-col items-center border border-gray-100 dark:border-slate-700 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-blue-500/20"
          >
            <div
              class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-blue-600 dark:bg-blue-500 flex items-center justify-center text-white shadow-md mb-3 md:mb-4"
            >
              <PhFlag class="w-5 h-5 md:w-6 md:h-6" />
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
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-5 md:p-6 flex flex-col items-center border border-gray-100 dark:border-slate-700 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-blue-500/20"
          >
            <div
              class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-blue-600 dark:bg-blue-500 flex items-center justify-center text-white shadow-md mb-3 md:mb-4"
            >
              <PhMapPin class="w-5 h-5 md:w-6 md:h-6" />
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
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-5 md:p-6 flex flex-col items-center border border-gray-100 dark:border-slate-700 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-blue-500/20"
          >
            <div
              class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-blue-600 dark:bg-blue-500 flex items-center justify-center text-white shadow-md mb-3 md:mb-4"
            >
              <PhBuildings class="w-5 h-5 md:w-6 md:h-6" />
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
              <PhBookOpen
                class="absolute left-1/2 md:left-4 top-1/2 -translate-x-1/2 md:translate-x-0 -translate-y-1/2 w-5 h-5 md:w-4 md:h-4 pointer-events-none transition-colors"
                :class="
                  activeType !== 'semua'
                    ? 'text-blue-600 dark:text-blue-400'
                    : 'text-gray-500 md:text-gray-400'
                "
              />
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
                <PhCaretDown class="w-4 h-4 text-gray-400" />
              </div>
            </div>

            <!-- Filter Tahun -->
            <div class="relative w-12 md:w-52 shrink-0">
              <PhCalendarBlank
                class="absolute left-1/2 md:left-4 top-1/2 -translate-x-1/2 md:translate-x-0 -translate-y-1/2 w-5 h-5 md:w-4 md:h-4 pointer-events-none transition-colors"
                :class="
                  activeYear !== 'semua'
                    ? 'text-blue-600 dark:text-blue-400'
                    : 'text-gray-500 md:text-gray-400'
                "
              />
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
                <PhCaretDown class="w-4 h-4 text-gray-400" />
              </div>
            </div>

            <!-- Search Bar -->
            <div class="relative flex-1 shrink-0 min-w-0">
              <PhMagnifyingGlass
                class="absolute left-3 md:left-4 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-gray-400"
              />
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
              <PhFunnel class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" />
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

        <!-- Skeleton Loading -->
        <div
          v-if="isLoading || isFetching"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative"
        >
          <div
            v-for="i in 6"
            :key="'skeleton-' + i"
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-gray-100 dark:border-slate-700 flex flex-col h-full overflow-hidden animate-pulse"
          >
            <div
              class="h-48 bg-gray-200 dark:bg-slate-700 w-full shrink-0 border-b-[6px] border-gray-300 dark:border-slate-600"
            ></div>
            <div class="p-6 flex flex-col flex-1">
              <div class="flex justify-between mb-4">
                <div class="h-6 w-24 bg-gray-200 dark:bg-slate-700 rounded"></div>
                <div class="h-6 w-28 bg-gray-200 dark:bg-slate-700 rounded"></div>
              </div>
              <div class="h-6 w-full bg-gray-200 dark:bg-slate-700 rounded mb-3"></div>
              <div class="h-6 w-3/4 bg-gray-200 dark:bg-slate-700 rounded mb-6"></div>
              <div
                class="mt-auto pt-5 border-t border-gray-100 dark:border-slate-700 flex items-center gap-3"
              >
                <div
                  class="w-10 h-10 rounded-full bg-gray-200 dark:bg-slate-700 shrink-0"
                ></div>
                <div class="flex-1 space-y-2">
                  <div class="h-3 w-20 bg-gray-200 dark:bg-slate-700 rounded"></div>
                  <div class="h-4 w-32 bg-gray-200 dark:bg-slate-700 rounded"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Daftar Prestasi dengan Efek Papan Penghargaan -->
        <div v-else-if="filteredPrestasi.length > 0">
          <TransitionGroup
            name="list"
            tag="div"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative"
          >
            <div
              v-for="prestasi in paginatedPrestasi"
              :key="prestasi.id"
              class="group relative bg-white dark:bg-slate-800 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col h-full cursor-default transform hover:-translate-y-2"
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
                  <component
                    :is="getRankStyle(prestasi.rank).icon"
                    class="w-6 h-6 mt-1 mb-0.5"
                    :class="getRankStyle(prestasi.rank).iconFill"
                  />
                  <span
                    class="text-xs font-bold tracking-wider"
                    style="font-family: 'Kalam', cursive"
                    >{{
                      prestasi.rank === 1 ? "1st" : prestasi.rank === 2 ? "2nd" : "3rd"
                    }}</span
                  >
                </div>

                <!-- Kategori Tag -->
                <div
                  class="absolute bottom-3 left-4 px-2.5 py-1 bg-black/50 backdrop-blur-md text-white text-xs font-bold rounded capitalize tracking-wider"
                  style="font-family: 'Kalam', cursive"
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
                  <span
                    class="bg-gray-100 dark:bg-slate-700 px-2.5 py-1 rounded text-sm tracking-wide"
                    style="font-family: 'Kalam', cursive"
                    >{{ prestasi.type }}</span
                  >
                  <span
                    class="flex items-center bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 px-2.5 py-1 rounded text-sm tracking-wide"
                    style="font-family: 'Kalam', cursive"
                    ><PhCalendarBlank class="w-3.5 h-3.5 mr-1" /> Tahun
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
                  <PhArrowUpRight class="w-3.5 h-3.5 ml-1" />
                </router-link>

                <div
                  class="mt-auto pt-5 border-t border-gray-100 dark:border-slate-700 flex items-center gap-3"
                >
                  <div
                    class="w-10 h-10 rounded-full bg-blue-100 dark:bg-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0"
                  >
                    <PhUser class="w-5 h-5" />
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

          <!-- Pagination Controls -->
          <div
            v-if="totalPages > 1"
            class="flex justify-between items-center gap-2 mt-12 mb-6 sm:mb-0 relative z-10 w-full"
          >
            <button
              @click="changePage(currentPage - 1)"
              :disabled="currentPage === 1 || isLoading"
              class="flex items-center px-4 py-2 rounded-lg text-base tracking-wide font-bold transition-colors border"
              style="font-family: 'Kalam', cursive"
              :class="
                currentPage === 1
                  ? 'bg-gray-50 text-gray-400 border-gray-200 cursor-not-allowed dark:bg-slate-800 dark:border-slate-700 dark:text-gray-500'
                  : 'bg-white text-blue-600 border-gray-200 hover:bg-blue-50 hover:border-blue-300 dark:bg-slate-800 dark:text-blue-400 dark:border-slate-700 dark:hover:border-blue-500'
              "
            >
              <PhCaretLeft class="w-4 h-4 mr-1" />
              Sebelumnya
            </button>

            <div class="flex items-center gap-1 hidden sm:flex">
              <button
                v-for="page in totalPages"
                :key="page"
                @click="changePage(page)"
                :disabled="isLoading"
                class="w-10 h-10 rounded-lg text-base font-bold transition-colors flex items-center justify-center border"
                style="font-family: 'Kalam', cursive"
                :class="
                  currentPage === page
                    ? 'bg-blue-600 text-white border-blue-600 shadow-md'
                    : 'bg-white text-gray-600 border-gray-200 hover:bg-blue-50 hover:text-blue-600 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-700 dark:hover:border-blue-500 dark:hover:text-blue-400'
                "
              >
                {{ page }}
              </button>
            </div>

            <button
              @click="changePage(currentPage + 1)"
              :disabled="currentPage === totalPages || isLoading"
              class="flex items-center px-4 py-2 rounded-lg text-base tracking-wide font-bold transition-colors border"
              style="font-family: 'Kalam', cursive"
              :class="
                currentPage === totalPages
                  ? 'bg-gray-50 text-gray-400 border-gray-200 cursor-not-allowed dark:bg-slate-800 dark:border-slate-700 dark:text-gray-500'
                  : 'bg-white text-blue-600 border-gray-200 hover:bg-blue-50 hover:border-blue-300 dark:bg-slate-800 dark:text-blue-400 dark:border-slate-700 dark:hover:border-blue-500'
              "
            >
              Selanjutnya
              <PhCaretRight class="w-4 h-4 ml-1" />
            </button>
          </div>
        </div>

        <!-- Empty State -->
        <div
          v-else
          class="py-20 text-center bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm mt-4"
        >
          <div
            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-700 mb-4 text-gray-400"
          >
            <PhCertificate class="w-8 h-8" />
          </div>
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tidak Ditemukan</h3>
          <p class="text-gray-500 dark:text-gray-400 mt-1">
            Data prestasi yang Anda cari tidak ditemukan. Coba kata kunci atau filter
            lain.
          </p>
          <button
            @click="
              searchQuery = '';
              activeFilter = 'semua';
              activeType = 'semua';
              activeYear = 'semua';
            "
            class="mt-6 px-6 py-2.5 bg-blue-50 text-blue-600 dark:bg-slate-700 dark:text-blue-400 rounded-xl text-sm font-semibold hover:bg-blue-100 transition-colors"
          >
            Reset Filter
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");

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
