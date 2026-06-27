<script setup>
import { ref, computed, onMounted, watch } from "vue";
import {
  PhTrophy,
  PhGlobe,
  PhFlag,
  PhMapPin,
  PhBuildings,
  PhBookOpen,
  PhMagnifyingGlass,
  PhCalendarBlank,
  PhFunnel,
  PhMedal,
  PhCertificate,
  PhUser,
  PhCaretLeft,
  PhCaretRight,
  PhNewspaperClipping,
  PhArrowUpRight,
} from "@phosphor-icons/vue";
import api from "@/api/index.js";
import PageHeader from "@/components/PageHeader.vue";

const activeFilter = ref("semua");
const activeType = ref("semua");
const activeYear = ref("semua");

const searchQuery = ref("");

const types = [
  { id: "semua", name: "Semua Bidang" },
  { id: "akademik", name: "Akademik" },
  { id: "non-akademik", name: "Non-Akademik" },
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
const appearanceSettings = ref({});
const isFetching = ref(true);

const newsArticles = ref([]);

const fetchNewsArticles = async () => {
  try {
    const response = await api.get("/api/public-news?category=prestasi&per_page=999");
    if (response.data && response.data.data) {
      newsArticles.value = response.data.data;
    }
  } catch (error) {
    console.error("Gagal mengambil data berita:", error);
    newsArticles.value = [];
  }
};

const getNewsSlug = (newsId) => {
  if (!newsArticles.value || !Array.isArray(newsArticles.value)) return newsId;
  const news = newsArticles.value.find((n) => String(n.id) === String(newsId));
  return news?.slug || newsId;
};

const getImageUrl = (
  path,
  defaultUrl = "https://images.unsplash.com/photo-1517840901100-8179e98d84ae?q=80&w=800"
) => {
  if (!path) return defaultUrl;
  if (path.startsWith("http") || path.startsWith("data:")) return path;
  const backendUrl = api.defaults.baseURL;
  return `${backendUrl.replace(/\/+$/, "")}/${path.replace(/^\/+/, "")}`;
};

const handleImageError = (e) => {
  e.target.src =
    "https://images.unsplash.com/photo-1517840901100-8179e98d84ae?q=80&w=800";
};

const fetchInitialData = async () => {
  isFetching.value = true;
  try {
    const [prestasiResponse, settingsResponse] = await Promise.all([
      api.get("/api/public-achievements"),
      api.get("/api/settings"),
    ]);

    if (prestasiResponse.data && prestasiResponse.data.data) {
      prestasiList.value = prestasiResponse.data.data.map((item) => ({
        id: item.id,
        title: item.title || item.name,
        winner: item.winner || item.studentName || item.student_name || "Siswa",
        description: item.description || "",
        rank: item.rank ? parseInt(item.rank) : null,
        level: (item.level || "nasional").toLowerCase(),
        year: parseInt(item.year) || new Date().getFullYear(),
        type: item.category || "akademik",
        image: getImageUrl(item.image),
        internalNewsId: item.internalNewsId || item.internal_news_id || null,
        externalNewsUrl: item.externalNewsUrl || item.external_news_url || null,
      }));
    }

    if (settingsResponse.data?.success) {
      appearanceSettings.value = settingsResponse.data.data;
    }

    animateValue("internasional", counts.value.internasional);
    animateValue("nasional", counts.value.nasional);
    animateValue("provinsi", counts.value.provinsi);
    animateValue("kabupaten", counts.value.kabupaten);
  } catch (error) {
    console.error("Gagal memuat data awal halaman prestasi:", error);
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

  filtered = [...filtered].sort((a, b) => b.year - a.year);
  return filtered;
});

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

const counts = computed(() => ({
  internasional: prestasiList.value.filter((p) => p.level === "internasional").length,
  nasional: prestasiList.value.filter((p) => p.level === "nasional").length,
  provinsi: prestasiList.value.filter((p) => p.level === "provinsi").length,
  kabupaten: prestasiList.value.filter((p) => p.level === "kabupaten").length,
}));

const animatedCounts = ref({ internasional: 0, nasional: 0, provinsi: 0, kabupaten: 0 });

const animateValue = (key, target, duration = 2000) => {
  if (target === 0) return;
  let startTimestamp = null;
  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
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

// Konversi Warna Badge Dinamis untuk Juara (Emas, Perak, Perunggu, Umum)
const getRankStyle = (rank) => {
  if (!rank) {
    return {
      badge:
        "bg-blue-100/90 dark:bg-blue-500/20 border border-blue-200 dark:border-blue-500/30 text-blue-700 dark:text-blue-400 backdrop-blur-md shadow-sm",
      text: "Penghargaan",
      icon: PhCertificate,
    };
  }

  const rankNum = parseInt(rank);
  switch (rankNum) {
    case 1:
      return {
        badge:
          "bg-yellow-100/90 dark:bg-yellow-600/30 border border-yellow-400 dark:border-yellow-500/50 text-yellow-800 dark:text-yellow-400 backdrop-blur-md shadow-sm",
        text: "Juara 1 (Emas)",
        icon: PhMedal,
      };
    case 2:
      return {
        badge:
          "bg-slate-200/90 dark:bg-slate-500/30 border border-slate-300 dark:border-slate-400/50 text-slate-700 dark:text-slate-300 backdrop-blur-md shadow-sm",
        text: "Juara 2 (Perak)",
        icon: PhMedal,
      };
    case 3:
      return {
        badge:
          "bg-orange-100/90 dark:bg-orange-700/30 border border-orange-300 dark:border-orange-500/50 text-orange-800 dark:text-orange-400 backdrop-blur-md shadow-sm",
        text: "Juara 3 (Perunggu)",
        icon: PhMedal,
      };
    default:
      return {
        badge:
          "bg-emerald-100/90 dark:bg-emerald-500/20 border border-emerald-300 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-400 backdrop-blur-md shadow-sm",
        text: `Juara ${rankNum}`,
        icon: PhMedal,
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
  }, 600);
});

onMounted(() => {
  fetchInitialData();
  fetchNewsArticles();
});
</script>

<template>
  <div class="bg-slate-50/50 dark:bg-slate-900 min-h-screen">
    <PageHeader
      v-if="!isFetching && appearanceSettings"
      badge="Hall of Fame"
      title="Prestasi & Penghargaan"
      description="Dedikasi, kerja keras, dan semangat pantang menyerah siswa-siswi kami yang berhasil menorehkan tinta emas di berbagai ajang perlombaan bergengsi."
      :bgImage="getImageUrl(appearanceSettings.headerPrestasi)"
    >
      <template #bg-ornament>
        <div
          class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-[0.03] text-yellow-500 pointer-events-none"
        >
          <PhTrophy class="w-[400px] h-[400px]" />
        </div>
      </template>
    </PageHeader>

    <section class="pb-24 px-6 relative z-10">
      <div class="container mx-auto max-w-7xl px-0 lg:px-4">
        <div
          class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-12 -mt-16 md:-mt-20 relative z-20"
        >
          <div
            class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 dark:border-slate-700/50 p-6 flex flex-col items-center group hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300"
          >
            <div
              class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300"
            >
              <PhGlobe class="w-6 h-6" />
            </div>
            <p
              class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-1 tracking-tight"
              style="font-family: 'Plus Jakarta Sans', sans-serif"
            >
              {{ animatedCounts.internasional }}
            </p>
            <h4
              class="text-slate-500 dark:text-slate-400 text-[10px] md:text-xs font-bold uppercase tracking-widest text-center"
            >
              Internasional
            </h4>
          </div>

          <div
            class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 dark:border-slate-700/50 p-6 flex flex-col items-center group hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300"
          >
            <div
              class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300"
            >
              <PhFlag class="w-6 h-6" />
            </div>
            <p
              class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-1 tracking-tight"
              style="font-family: 'Plus Jakarta Sans', sans-serif"
            >
              {{ animatedCounts.nasional }}
            </p>
            <h4
              class="text-slate-500 dark:text-slate-400 text-[10px] md:text-xs font-bold uppercase tracking-widest text-center"
            >
              Nasional
            </h4>
          </div>

          <div
            class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 dark:border-slate-700/50 p-6 flex flex-col items-center group hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300"
          >
            <div
              class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300"
            >
              <PhMapPin class="w-6 h-6" />
            </div>
            <p
              class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-1 tracking-tight"
              style="font-family: 'Plus Jakarta Sans', sans-serif"
            >
              {{ animatedCounts.provinsi }}
            </p>
            <h4
              class="text-slate-500 dark:text-slate-400 text-[10px] md:text-xs font-bold uppercase tracking-widest text-center"
            >
              Provinsi
            </h4>
          </div>

          <div
            class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 dark:border-slate-700/50 p-6 flex flex-col items-center group hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300"
          >
            <div
              class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300"
            >
              <PhBuildings class="w-6 h-6" />
            </div>
            <p
              class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-1 tracking-tight"
              style="font-family: 'Plus Jakarta Sans', sans-serif"
            >
              {{ animatedCounts.kabupaten }}
            </p>
            <h4
              class="text-slate-500 dark:text-slate-400 text-[10px] md:text-xs font-bold uppercase tracking-widest text-center"
            >
              Kabupaten / Kota
            </h4>
          </div>
        </div>

        <div
          class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-700/60 p-4 md:p-6 mb-12 flex flex-col gap-4"
        >
          <div class="flex flex-col md:flex-row items-center gap-3 w-full">
            <div class="relative w-full md:w-52 shrink-0">
              <PhBookOpen
                class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none text-slate-400"
              />
              <select
                v-model="activeType"
                class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm appearance-none cursor-pointer font-medium"
                style="font-family: 'Plus Jakarta Sans', sans-serif"
              >
                <option v-for="t in types" :key="t.id" :value="t.id">{{ t.name }}</option>
              </select>
            </div>

            <div class="relative w-full md:w-48 shrink-0">
              <PhCalendarBlank
                class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none text-slate-400"
              />
              <select
                v-model="activeYear"
                class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm appearance-none cursor-pointer font-medium"
                style="font-family: 'Plus Jakarta Sans', sans-serif"
              >
                <option v-for="y in years" :key="y.id" :value="y.id">{{ y.name }}</option>
              </select>
            </div>

            <div class="relative flex-1 w-full min-w-0">
              <PhMagnifyingGlass
                class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"
              />
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Ketik nama siswa atau judul lomba..."
                class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm text-ellipsis font-medium placeholder-slate-400"
                style="font-family: 'Plus Jakarta Sans', sans-serif"
              />
            </div>
          </div>

          <div
            class="pt-4 border-t border-slate-100 dark:border-slate-700/60 flex flex-col sm:flex-row sm:items-center gap-3"
          >
            <h4
              class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest flex items-center shrink-0"
            >
              <PhFunnel class="w-3.5 h-3.5 mr-1.5" /> Tingkat:
            </h4>
            <div class="flex flex-wrap items-center gap-2">
              <button
                v-for="filter in filters"
                :key="filter.id"
                @click="activeFilter = filter.id"
                class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all duration-300 border"
                :class="
                  activeFilter === filter.id
                    ? 'bg-blue-600 text-white border-blue-600 shadow-sm'
                    : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700'
                "
                style="font-family: 'Plus Jakarta Sans', sans-serif"
              >
                {{ filter.name }}
              </button>
            </div>
          </div>
        </div>

        <div
          v-if="isLoading || isFetching"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8"
        >
          <div
            v-for="i in 6"
            :key="i"
            class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/60 rounded-2xl h-[420px] animate-pulse"
          ></div>
        </div>

        <div v-else-if="filteredPrestasi.length > 0">
          <TransitionGroup
            name="list"
            tag="div"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8"
          >
            <div
              v-for="prestasi in paginatedPrestasi"
              :key="prestasi.id"
              class="group bg-white dark:bg-slate-800 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 dark:border-slate-700/60 hover:shadow-xl hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-500 overflow-hidden flex flex-col h-full transform hover:-translate-y-1.5"
            >
              <div class="relative h-56 overflow-hidden shrink-0">
                <img
                  :src="prestasi.image"
                  :alt="prestasi.title"
                  class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                  @error="handleImageError"
                />
                <div
                  class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-90"
                ></div>

                <div
                  v-if="prestasi.rank"
                  class="absolute top-4 right-4 flex items-center gap-1.5 px-3 py-1.5 rounded-full z-10"
                  :class="getRankStyle(prestasi.rank).badge"
                >
                  <component
                    :is="getRankStyle(prestasi.rank).icon"
                    class="w-4 h-4"
                    weight="fill"
                  />
                  <span
                    class="text-xs font-bold"
                    style="font-family: 'Plus Jakarta Sans', sans-serif"
                  >
                    {{ getRankStyle(prestasi.rank).text }}
                  </span>
                </div>

                <div class="absolute bottom-4 left-4 flex gap-2 z-10">
                  <span
                    class="px-2.5 py-1 bg-white/20 backdrop-blur-md text-white text-[10px] font-bold rounded uppercase tracking-wider border border-white/20"
                  >
                    {{ prestasi.level }}
                  </span>
                  <span
                    class="px-2.5 py-1 bg-white/20 backdrop-blur-md text-white text-[10px] font-bold rounded uppercase tracking-wider border border-white/20 flex items-center gap-1"
                  >
                    <PhCalendarBlank class="w-3 h-3" /> {{ prestasi.year }}
                  </span>
                </div>
              </div>

              <div
                class="p-6 flex flex-col flex-1 relative bg-white dark:bg-slate-800 z-10"
              >
                <div class="mb-3">
                  <span
                    class="text-[10px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded"
                  >
                    {{ prestasi.type }}
                  </span>
                </div>

                <h3
                  class="text-lg md:text-xl font-bold text-slate-900 dark:text-white mb-4 leading-snug group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                  style="font-family: 'Plus Jakarta Sans', sans-serif"
                >
                  {{ prestasi.title }}
                </h3>

                <p
                  class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-3 mb-4"
                >
                  {{ prestasi.description }}
                </p>

                <div
                  class="mt-auto mb-4 flex items-center gap-3 bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700/50"
                >
                  <div
                    class="w-10 h-10 rounded-full bg-white dark:bg-slate-700 flex items-center justify-center text-slate-400 dark:text-slate-500 shrink-0 border border-slate-200 dark:border-slate-600"
                  >
                    <PhUser class="w-5 h-5" weight="fill" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <p
                      class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider"
                    >
                      Peraih Penghargaan
                    </p>
                    <p
                      class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate"
                    >
                      {{ prestasi.winner }}
                    </p>
                  </div>
                </div>

                <div
                  v-if="prestasi.internalNewsId || prestasi.externalNewsUrl"
                  class="pt-2 border-t border-slate-100 dark:border-slate-700/60"
                >
                  <a
                    :href="
                      prestasi.internalNewsId
                        ? `/artikel/${getNewsSlug(prestasi.internalNewsId)}`
                        : prestasi.externalNewsUrl
                    "
                    target="_blank"
                    class="inline-flex items-center text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors group/link w-fit"
                  >
                    <PhNewspaperClipping class="w-4 h-4 mr-2" />
                    Baca Liputan
                    <PhArrowUpRight
                      class="w-4 h-4 ml-1 transform group-hover/link:translate-x-1 transition-transform"
                    />
                  </a>
                </div>
              </div>
            </div>
          </TransitionGroup>

          <div
            v-if="totalPages > 1"
            class="flex justify-center items-center gap-2 mt-12 mb-6 sm:mb-0 w-full"
          >
            <button
              @click="changePage(currentPage - 1)"
              :disabled="currentPage === 1 || isLoading"
              class="flex items-center justify-center w-10 h-10 rounded-full text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm"
            >
              <PhCaretLeft class="w-4 h-4" weight="bold" />
            </button>
            <div class="flex items-center gap-1.5 mx-2">
              <button
                v-for="page in totalPages"
                :key="page"
                @click="changePage(page)"
                :disabled="isLoading"
                class="w-10 h-10 rounded-full text-sm font-bold transition-all shadow-sm flex items-center justify-center"
                style="font-family: 'Plus Jakarta Sans', sans-serif"
                :class="
                  currentPage === page
                    ? 'bg-blue-600 text-white border border-blue-600'
                    : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:text-blue-600'
                "
              >
                {{ page }}
              </button>
            </div>
            <button
              @click="changePage(currentPage + 1)"
              :disabled="currentPage === totalPages || isLoading"
              class="flex items-center justify-center w-10 h-10 rounded-full text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm"
            >
              <PhCaretRight class="w-4 h-4" weight="bold" />
            </button>
          </div>
        </div>

        <div
          v-else
          class="py-20 px-6 text-center bg-white dark:bg-slate-800 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700 mt-4 flex flex-col items-center justify-center"
        >
          <div
            class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-900/50 flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-700/50"
          >
            <PhCertificate class="w-10 h-10 text-slate-300 dark:text-slate-600" />
          </div>
          <h3
            class="text-xl font-bold text-slate-800 dark:text-white mb-2"
            style="font-family: 'Plus Jakarta Sans', sans-serif"
          >
            Tidak Ada Data Ditemukan
          </h3>
          <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto mb-6">
            Maaf, kami tidak dapat menemukan prestasi yang sesuai dengan kriteria filter
            atau pencarian Anda.
          </p>
          <button
            @click="
              searchQuery = '';
              activeFilter = 'semua';
              activeType = 'semua';
              activeYear = 'semua';
            "
            class="px-6 py-2.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-full text-sm font-bold hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors"
          >
            Reset Semua Filter
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap");

.list-move,
.list-enter-active,
.list-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(20px);
}
.list-leave-active {
  position: absolute;
}
</style>
