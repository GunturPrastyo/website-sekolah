<template>
  <div class="overflow-x-hidden w-full bg-gray-50 dark:bg-slate-900 min-h-screen">
    <!-- Header / Hero Section -->
    <PageHeader
      badge="Jejak Langkah Lulusan"
      title="Direktori & Persebaran Alumni"
      description="Temukan jejak sukses para alumni SMAN 1 Nogosari yang tersebar di berbagai perguruan tinggi favorit dan instansi bergengsi di seluruh Indonesia."
      bgImage="https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1600&auto=format&fit=crop"
    />

    <!-- Map Section -->
    <section
      class="mx-auto max-w-full px-0 lg:px-8 mt-0 md:mt-12 relative z-10 container"
    >
      <div
        class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 p-0 md:p-8"
      >
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
          <!-- Map Area -->
          <div
            class="lg:col-span-2 relative aspect-4/3 sm:aspect-2/1 bg-blue-50/50 dark:bg-slate-900/50 rounded-lg border border-blue-100 dark:border-slate-700 overflow-hidden flex items-center justify-center p-2"
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
                class="absolute flex justify-center items-end group cursor-pointer hover:z-50 focus:z-50 w-8 h-10 md:w-10 md:h-12 -translate-x-1/2 -translate-y-full focus:outline-none"
                :style="{
                  top: loc.top,
                  left: loc.left,
                  zIndex: selectedLocation?.id === loc.id ? 50 : 10,
                }"
                @click="selectedLocation = loc"
              >
                <!-- Tooltip Hover -->
                <div
                  class="absolute bottom-full mb-2 opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none z-20 flex flex-col items-center translate-y-2 group-hover:translate-y-0"
                >
                  <div
                    class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs rounded-lg py-1.5 px-3 whitespace-nowrap shadow-xl font-medium flex items-center gap-2"
                  >
                    <span>{{ loc.name }}</span>
                    <span
                      class="bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded-full font-bold"
                      >{{ loc.totalAlumni }}</span
                    >
                  </div>
                  <div
                    class="w-2.5 h-2.5 bg-gray-900 dark:bg-white rotate-45 -mt-1.5"
                  ></div>
                </div>

                <!-- Shadow Map -->
                <div
                  class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-4 h-1.5 md:w-5 md:h-2 bg-black/40 rounded-[100%] blur-[2px] transition-transform duration-300"
                  :class="
                    selectedLocation?.id === loc.id ? 'scale-50' : 'group-hover:scale-50'
                  "
                ></div>

                <!-- Pin Icon (Bounce effect on hover) -->
                <div
                  class="relative drop-shadow-[0_5px_8px_rgba(0,0,0,0.4)] transition-transform duration-300 origin-bottom"
                  :class="
                    selectedLocation?.id === loc.id
                      ? '-translate-y-2'
                      : 'group-hover:-translate-y-2'
                  "
                >
                  <PhMapPin
                    weight="fill"
                    class="w-8 h-8 md:w-10 md:h-10 transition-colors duration-300"
                    :class="
                      selectedLocation?.id === loc.id
                        ? 'text-yellow-400'
                        : loc.type === 'ptn'
                        ? 'text-sky-500'
                        : loc.type === 'kedinasan'
                        ? 'text-yellow-500'
                        : loc.type === 'instansi'
                        ? 'text-emerald-500'
                        : 'text-blue-500'
                    "
                  />
                  <!-- Inner Dot -->
                  <div
                    class="absolute top-[8px] left-1/2 -translate-x-1/2 w-2.5 h-2.5 md:top-[10px] md:w-3 md:h-3 bg-white rounded-full"
                  ></div>
                </div>
              </div>
            </template>
          </div>

          <!-- Location Detail Sidebar -->
          <div
            class="lg:col-span-1 bg-gray-50 dark:bg-slate-700/30 rounded-lg p-5 md:p-6 border border-gray-100 dark:border-slate-700 shadow-inner h-100 sm:h-112.5 flex flex-col transition-all duration-300"
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
      <!-- Header & Filters Combined Area -->
      <div
        class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 mb-8 overflow-hidden"
      >
        <!-- Title Area inside the box -->
        <div
          class="p-6 md:p-8 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50"
        >
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <h2
                class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2"
                style="font-family: 'Oswald', sans-serif"
              >
                Direktori Pencarian Alumni
              </h2>
              <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base max-w-2xl">
                Cari data alumni berdasarkan nama, instansi, status, atau tahun kelulusan
                untuk memperluas koneksi dan jaringan (networking).
              </p>
            </div>
            <!-- Total Alumni Badge -->
            <div
              class="hidden md:flex items-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg font-semibold text-sm border border-blue-100 dark:border-blue-800/50"
            >
              <PhUsers class="w-5 h-5" />
              <span>{{ filteredAlumni.length }} Alumni Ditemukan</span>
            </div>
          </div>
        </div>

        <!-- Filter Area -->
        <div
          class="p-4 md:p-6 bg-white dark:bg-slate-800 flex flex-col md:flex-row gap-4"
        >
          <div class="relative flex-1 group">
            <div
              class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500"
            >
              <PhMagnifyingGlass
                class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500"
              />
            </div>
            <input
              type="text"
              v-model="searchQuery"
              @input="resetPagination"
              placeholder="Cari berdasarkan nama atau instansi..."
              class="w-full pl-11 pr-4 py-3 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm transition-all shadow-sm"
            />
          </div>

          <div class="flex gap-4 w-full md:w-56 shrink-0">
            <div class="relative w-full group">
              <div
                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500"
              >
                <PhCalendarBlank
                  class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500"
                />
              </div>
              <select
                v-model="selectedYear"
                @change="resetPagination"
                class="w-full pl-11 pr-10 py-3 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm appearance-none cursor-pointer transition-all shadow-sm"
              >
                <option value="">Semua Tahun Lulus</option>
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
      </div>

      <!-- Alumni Grid -->
      <div class="relative min-h-75">
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
              class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 transition-all duration-300 hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-800 flex flex-col h-full group relative overflow-hidden"
            >
              <!-- Card Highlight Line -->
              <div
                class="absolute top-0 left-0 w-full h-1 bg-linear-to-r from-blue-500 to-blue-400 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"
              ></div>

              <div class="flex items-start gap-4 mb-5">
                <div
                  class="w-14 h-14 shrink-0 rounded-2xl bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-gray-300 flex items-center justify-center font-bold text-xl border border-gray-100 dark:border-slate-600 group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:border-blue-200 dark:group-hover:bg-blue-900/30 dark:group-hover:text-blue-400 transition-colors"
                >
                  {{ getInitials(alumni.name) }}
                </div>
                <div class="flex-1 min-w-0 pt-1">
                  <h3
                    class="font-bold text-gray-900 dark:text-white text-lg leading-tight truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                    :title="alumni.name"
                  >
                    {{ alumni.name }}
                  </h3>
                  <div
                    class="flex items-center text-sm text-gray-500 dark:text-gray-400 mt-1.5"
                  >
                    <PhGraduationCap class="w-4 h-4 mr-1.5" /> Lulusan {{ alumni.year }}
                  </div>
                </div>
              </div>

              <div
                class="mt-auto pt-5 border-t border-gray-100 dark:border-slate-700 flex flex-col gap-3"
              >
                <div class="flex items-start">
                  <div class="mt-0.5 mr-3 shrink-0">
                    <div
                      class="w-8 h-8 rounded-full bg-gray-50 dark:bg-slate-700 flex items-center justify-center border border-gray-100 dark:border-slate-600"
                    >
                      <component
                        :is="getStatusIcon(alumni.status)"
                        class="w-4 h-4 text-gray-500 dark:text-gray-400"
                      />
                    </div>
                  </div>
                  <div class="flex-1 min-w-0 flex flex-col justify-center min-h-8">
                    <span
                      class="font-medium text-gray-700 dark:text-gray-300 text-sm line-clamp-2 leading-snug"
                      :title="alumni.instansi"
                    >
                      {{ alumni.instansi || "Belum ada data instansi" }}
                    </span>
                  </div>
                </div>
                <div class="flex">
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
import PageHeader from "@/components/PageHeader.vue";
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
  PhCalendarBlank,
  PhMapPin,
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
