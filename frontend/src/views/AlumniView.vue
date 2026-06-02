<template>
  <div class="overflow-x-hidden w-full bg-gray-50 dark:bg-slate-900 min-h-screen">
    <!-- Header / Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden bg-blue-950">
      <!-- Pattern & Overlay -->
      <div
        class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1600&auto=format&fit=crop')] bg-cover bg-center opacity-20"
      ></div>
      <div class="absolute inset-0 bg-gradient-to-b from-blue-950/80 to-blue-950"></div>

      <div class="container relative z-10 mx-auto px-6 text-center">
        <span
          class="inline-block py-1 px-3 rounded-full bg-blue-800/50 border border-blue-400/30 text-blue-200 text-sm font-semibold mb-4 tracking-wider uppercase"
        >
          Jejak Langkah Lulusan
        </span>
        <h1
          class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6"
          style="font-family: 'Oswald', sans-serif"
        >
          Direktori & Persebaran Alumni
        </h1>
        <p
          class="text-blue-200 text-lg md:text-xl max-w-3xl mx-auto font-light leading-relaxed"
        >
          Temukan jejak sukses para alumni SMAN 1 Nogosari yang tersebar di berbagai
          perguruan tinggi favorit dan instansi bergengsi di seluruh Indonesia.
        </p>
      </div>
    </section>

    <!-- Map Section -->
    <section
      class="mx-auto max-w-full px-0 lg:px-8 mt-0 md:mt-12 relative z-10 container"
    >
      <div
        class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 p-0 md:p-8"
      >
        <div class="mb-8 mt-8 text-center">
          <h2
            class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2"
            style="font-family: 'Oswald', sans-serif"
          >
            Peta Persebaran Alumni
          </h2>
          <p
            class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto text-sm md:text-base"
          >
            Visualisasi interaktif lokasi studi dan karir lulusan. Klik titik pada peta
            untuk melihat detail instansi.
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
          <!-- Map Area -->
          <div
            class="lg:col-span-2 relative aspect-[4/3] sm:aspect-[2/1] bg-blue-50/50 dark:bg-slate-900/50 rounded-lg border border-blue-100 dark:border-slate-700 overflow-hidden flex items-center justify-center p-2"
          >
            <img
              src="/img/indonesia.svg"
              alt="Peta Indonesia"
              class="w-full h-full object-fill opacity-40 dark:opacity-20 pointer-events-none drop-shadow-md"
              style="filter: invert(40%) sepia(80%) saturate(300%) hue-rotate(180deg)"
            />

            <template v-if="isLoadingMap">
              <div
                class="absolute inset-0 flex items-center justify-center bg-white/30 dark:bg-slate-900/30 backdrop-blur-sm z-20"
              >
                <div
                  class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600"
                ></div>
              </div>
            </template>
            <template v-else>
              <div
                v-for="loc in mapLocations"
                :key="loc.id"
                class="absolute flex justify-center items-center group cursor-pointer w-8 h-8 -translate-x-1/2 -translate-y-1/2 transition-transform duration-300 hover:scale-125 z-10"
                :style="{ top: loc.top, left: loc.left }"
                @click="selectedLocation = loc"
              >
                <!-- Radar / Ping effect -->
                <span
                  v-if="selectedLocation?.id === loc.id"
                  class="absolute w-6 h-6 bg-yellow-400 rounded-full animate-ping opacity-75"
                ></span>
                <span
                  v-else
                  class="absolute w-4 h-4 bg-blue-500 rounded-full animate-ping opacity-75 group-hover:bg-blue-400"
                ></span>

                <!-- Center Dot -->
                <div
                  class="relative flex items-center justify-center shadow-lg rounded-full border-2 border-white dark:border-slate-800 transition-colors duration-300"
                  :class="
                    selectedLocation?.id === loc.id
                      ? 'bg-yellow-400 w-4 h-4'
                      : 'bg-blue-600 w-3 h-3 group-hover:bg-blue-500'
                  "
                ></div>
              </div>
            </template>
          </div>

          <!-- Location Detail Sidebar -->
          <div
            class="lg:col-span-1 bg-gray-50 dark:bg-slate-700/30 rounded-lg p-5 md:p-6 border border-gray-100 dark:border-slate-700 shadow-inner h-[400px] sm:h-[450px] flex flex-col transition-all duration-300"
          >
            <template v-if="selectedLocation">
              <div class="border-b border-gray-200 dark:border-slate-600 pb-4 mb-4">
                <div class="flex justify-between items-start mb-2">
                  <h3
                    class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white leading-tight"
                  >
                    {{ selectedLocation.name }}
                  </h3>
                  <div
                    class="bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap"
                  >
                    {{ selectedLocation.totalAlumni }} Alumni
                  </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                  Rincian instansi dan perguruan tinggi:
                </p>
              </div>

              <div class="flex-1 overflow-y-auto pr-2 space-y-3 custom-scrollbar">
                <div
                  v-for="inst in selectedLocation.institutions"
                  :key="inst.name"
                  class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-600 shadow-sm hover:shadow-md transition-shadow"
                >
                  <div
                    class="w-10 h-10 shrink-0 bg-gray-50 dark:bg-slate-700 rounded-lg border border-gray-100 dark:border-slate-600 flex items-center justify-center overflow-hidden p-1"
                  >
                    <img
                      v-if="inst.logo"
                      :src="inst.logo"
                      class="w-full h-full object-contain"
                      :alt="inst.name"
                    />
                    <PhBuildings v-else class="w-5 h-5 text-gray-400" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <h4
                      class="font-bold text-sm text-gray-800 dark:text-gray-200 truncate"
                      :title="inst.name"
                    >
                      {{ inst.name }}
                    </h4>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">
                      {{
                        inst.type === "ptn"
                          ? "PTN"
                          : inst.type === "kedinasan"
                          ? "Kedinasan"
                          : "BUMN/Instansi"
                      }}
                      <span class="mx-1">•</span>
                      <span class="text-blue-600 dark:text-blue-400"
                        >{{ inst.alumni }} org</span
                      >
                    </p>
                  </div>
                </div>
                <div
                  v-if="
                    !selectedLocation.institutions ||
                    selectedLocation.institutions.length === 0
                  "
                  class="text-center py-8 text-sm text-gray-500"
                >
                  Tidak ada detail instansi.
                </div>
              </div>
            </template>
            <template v-else>
              <div
                class="flex-1 flex flex-col items-center justify-center text-center text-gray-400 dark:text-gray-500"
              >
                <div
                  class="w-20 h-20 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4"
                >
                  <PhMapTrifold class="w-10 h-10 text-gray-300 dark:text-slate-500" />
                </div>
                <p class="text-sm">
                  Klik salah satu titik pada peta untuk melihat detail persebaran alumni
                  di wilayah tersebut.
                </p>
              </div>
            </template>
          </div>
        </div>
      </div>
    </section>

    <!-- Alumni Directory Section -->
    <section class="py-10 md:py-16 container mx-auto px-4 md:px-6">
      <div class="mb-8 md:mb-12">
        <h2
          class="text-2xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4 pl-4 border-l-4 border-blue-600"
          style="font-family: 'Oswald', sans-serif"
        >
          Direktori Pencarian Alumni
        </h2>
        <p class="text-gray-600 dark:text-gray-400 ml-4 max-w-2xl text-sm md:text-base">
          Cari data alumni berdasarkan nama, instansi, status, atau tahun kelulusan untuk
          memperluas koneksi dan jaringan (networking).
        </p>
      </div>

      <!-- Filters -->
      <div
        class="bg-white dark:bg-slate-800 p-4 md:p-5 rounded-2xl shadow-md border border-gray-100 dark:border-slate-700 mb-8 flex flex-col md:flex-row gap-4 items-center"
      >
        <div class="relative flex-1 w-full">
          <div
            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
          >
            <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
          </div>
          <input
            type="text"
            v-model="searchQuery"
            @input="resetPagination"
            placeholder="Cari nama atau nama instansi..."
            class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-700 border-none rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-white text-sm transition-shadow"
          />
        </div>

        <div class="flex gap-4 w-full md:w-auto">
          <div class="relative w-full md:w-36">
            <select
              v-model="selectedYear"
              @change="resetPagination"
              class="w-full pl-4 pr-10 py-3 bg-gray-50 dark:bg-slate-700 border-none rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-white text-sm appearance-none cursor-pointer"
            >
              <option value="">Semua Tahun</option>
              <option v-for="year in uniqueYears" :key="year" :value="year">
                {{ year }}
              </option>
            </select>
            <div
              class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none"
            >
              <PhCaretDown class="w-4 h-4 text-gray-400" />
            </div>
          </div>
        </div>
      </div>

      <!-- Alumni Grid -->
      <div class="relative min-h-[300px]">
        <template v-if="isLoadingAlumni">
          <div class="absolute inset-0 flex items-center justify-center">
            <div
              class="animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-600"
            ></div>
          </div>
        </template>

        <template v-else-if="paginatedAlumni.length > 0">
          <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
          >
            <div
              v-for="alumni in paginatedAlumni"
              :key="alumni.id"
              class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm hover:shadow-xl border border-gray-100 dark:border-slate-700 transition-all duration-300 group hover:-translate-y-1 flex flex-col h-full"
            >
              <div class="flex items-start gap-4 mb-4">
                <div
                  class="w-14 h-14 shrink-0 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/50 dark:to-blue-800/50 text-blue-700 dark:text-blue-300 flex items-center justify-center font-bold text-xl shadow-inner border border-white dark:border-slate-700 group-hover:scale-105 transition-transform"
                >
                  {{ getInitials(alumni.name) }}
                </div>
                <div class="flex-1 min-w-0">
                  <h3
                    class="font-bold text-gray-900 dark:text-white text-base leading-tight truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                    :title="alumni.name"
                  >
                    {{ alumni.name }}
                  </h3>
                  <div
                    class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1"
                  >
                    <PhGraduationCap class="w-3.5 h-3.5 mr-1" /> Lulusan {{ alumni.year }}
                  </div>
                </div>
              </div>

              <div
                class="mt-auto flex flex-col gap-3 pt-4 border-t border-gray-50 dark:border-slate-700/50"
              >
                <div class="flex items-start text-sm">
                  <div class="mt-0.5 mr-2 shrink-0">
                    <component
                      :is="getStatusIcon(alumni.status)"
                      class="w-4 h-4 text-gray-400"
                    />
                  </div>
                  <span
                    class="font-medium text-gray-700 dark:text-gray-300 line-clamp-2"
                    :title="alumni.instansi"
                  >
                    {{ alumni.instansi || "Belum ada data instansi" }}
                  </span>
                </div>
                <div>
                  <span
                    class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider"
                    :class="getStatusColor(alumni.status)"
                  >
                    {{ alumni.status }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="totalPages > 1" class="mt-12 flex justify-center items-center gap-2">
            <button
              @click="prevPage"
              :disabled="currentPage === 1"
              class="p-2 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <PhCaretLeft class="w-5 h-5" />
            </button>

            <div class="flex gap-1">
              <button
                v-for="page in totalPages"
                :key="page"
                @click="currentPage = page"
                v-show="
                  Math.abs(page - currentPage) < 3 || page === 1 || page === totalPages
                "
                class="w-10 h-10 rounded-lg text-sm font-semibold transition-colors"
                :class="
                  currentPage === page
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800'
                "
              >
                <template
                  v-if="
                    Math.abs(page - currentPage) === 2 &&
                    page !== 1 &&
                    page !== totalPages
                  "
                  >...</template
                >
                <template v-else>{{ page }}</template>
              </button>
            </div>

            <button
              @click="nextPage"
              :disabled="currentPage === totalPages"
              class="p-2 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <PhCaretRight class="w-5 h-5" />
            </button>
          </div>
        </template>

        <template v-else>
          <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
            <div
              class="w-24 h-24 bg-gray-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6"
            >
              <PhUsers class="w-12 h-12 text-gray-300 dark:text-slate-600" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
              Data tidak ditemukan
            </h3>
            <p class="text-gray-500 dark:text-gray-400 max-w-md">
              Maaf, kami tidak dapat menemukan data alumni yang sesuai dengan filter
              pencarian Anda. Coba ubah kata kunci atau filter.
            </p>
            <button
              @click="
                searchQuery = '';
                selectedYear = '';
                resetPagination();
              "
              class="mt-6 text-blue-600 font-semibold hover:text-blue-700"
            >
              Reset Pencarian
            </button>
          </div>
        </template>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import api from "@/api/index.js";
import {
  PhMapTrifold,
  PhMagnifyingGlass,
  PhBuildings,
  PhGraduationCap,
  PhBriefcase,
  PhStorefront,
  PhUsers,
  PhCaretLeft,
  PhCaretRight,
  PhCaretDown,
} from "@phosphor-icons/vue";

const mapLocations = ref([]);
const alumniList = ref([]);
const selectedLocation = ref(null);
const isLoadingMap = ref(true);
const isLoadingAlumni = ref(true);

const searchQuery = ref("");
const selectedYear = ref("");
const currentPage = ref(1);
const itemsPerPage = 12;

const fetchMapLocations = async () => {
  isLoadingMap.value = true;
  try {
    const response = await api.get("/api/public-map-locations");
    mapLocations.value = response.data.data;
    if (mapLocations.value.length > 0) {
      selectedLocation.value = [...mapLocations.value].sort(
        (a, b) => b.totalAlumni - a.totalAlumni
      )[0];
    }
  } catch (error) {
    console.error("Gagal mengambil data peta alumni:", error);
  } finally {
    isLoadingMap.value = false;
  }
};

const fetchAlumnis = async () => {
  isLoadingAlumni.value = true;
  try {
    const response = await api.get("/api/public-alumnis");
    alumniList.value = response.data.data;
  } catch (error) {
    console.error("Gagal mengambil data alumni:", error);
  } finally {
    isLoadingAlumni.value = false;
  }
};

onMounted(() => {
  fetchMapLocations();
  fetchAlumnis();
  window.scrollTo(0, 0);
});

const uniqueYears = computed(() => {
  const years = alumniList.value.map((a) => a.year);
  return [...new Set(years)].sort((a, b) => b - a);
});

const filteredAlumni = computed(() => {
  return alumniList.value.filter((a) => {
    const q = searchQuery.value.toLowerCase();
    const matchName = a.name?.toLowerCase().includes(q);
    const matchInstansi = a.instansi?.toLowerCase().includes(q);

    const matchYear = selectedYear.value
      ? a.year.toString() === selectedYear.value.toString()
      : true;

    return (matchName || matchInstansi) && matchYear;
  });
});

const paginatedAlumni = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredAlumni.value.slice(start, start + itemsPerPage);
});

const totalPages = computed(() => Math.ceil(filteredAlumni.value.length / itemsPerPage));

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};
const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};
const resetPagination = () => {
  currentPage.value = 1;
};

const getInitials = (name) => {
  if (!name) return "A";
  return name
    .split(" ")
    .map((n) => n[0])
    .join("")
    .substring(0, 2)
    .toUpperCase();
};

const getStatusIcon = (status) => {
  if (status === "Kuliah") return PhGraduationCap;
  if (status === "Bekerja") return PhBriefcase;
  if (status === "Wirausaha") return PhStorefront;
  return PhUsers;
};

const getStatusColor = (status) => {
  if (status === "Kuliah")
    return "bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400";
  if (status === "Bekerja")
    return "bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400";
  if (status === "Wirausaha")
    return "bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-400";
  return "bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300";
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 20px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #475569;
}
</style>
