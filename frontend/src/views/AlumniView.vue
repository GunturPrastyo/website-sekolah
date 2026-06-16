<template>
  <div
    class="overflow-x-hidden w-full bg-slate-50 dark:bg-slate-900 min-h-screen relative"
  >
    <!-- Header / Hero Section -->
    <PageHeader
      badge="Jejak Langkah Lulusan"
      title="Direktori & Persebaran Alumni"
      description="Temukan jejak sukses para alumni sekolah kami yang tersebar di berbagai perguruan tinggi favorit dan instansi bergengsi di seluruh Indonesia."
    />

    <!-- Direktori & Persebaran Alumni Section -->
    <section
      class="py-8 md:py-12 container mx-auto px-0 md:px-4 lg:px-8 relative z-10 bg-white dark:bg-slate-800"
    >
      <div class="md:rounded-xl flex flex-col w-full overflow-hidden">
        <!-- Search Area -->
        <div class="px-5 md:px-10 pt-5 md:pt-8">
          <div class="flex flex-col md:flex-row gap-4 mb-8 max-w-4xl">
            <!-- Search Input -->
            <div class="relative group flex-1">
              <div
                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
              >
                <PhMagnifyingGlass
                  class="w-5 h-5 text-slate-400 group-focus-within:text-blue-500 transition-colors"
                />
              </div>
              <input
                type="text"
                v-model="searchQuery"
                placeholder="Cari alumni berdasarkan Nama, NISN, Angkatan, atau Instansi..."
                class="w-full pl-12 pr-12 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-900 dark:text-white text-base transition-all outline-none"
              />
              <div
                v-if="isLoadingAlumni"
                class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none"
              >
                <div
                  class="animate-spin rounded-full h-6 w-6 border-2 border-slate-300 border-t-blue-600"
                ></div>
              </div>
              <button
                v-else-if="searchQuery.trim().length > 0"
                @click="searchQuery = ''"
                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors focus:outline-none"
              >
                <PhXCircle class="w-5 h-5" weight="fill" />
              </button>
            </div>

            <!-- Filter Tahun -->
            <div class="w-full md:w-56 shrink-0 relative">
              <select
                v-model="selectedYear"
                class="w-full py-3.5 pl-4 pr-10 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-900 dark:text-white text-base transition-all outline-none appearance-none cursor-pointer"
              >
                <option value="">Semua Angkatan</option>
                <option v-for="year in availableYears" :key="year" :value="year">
                  Angkatan {{ year }}
                </option>
              </select>
              <div
                class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  ></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Inline Search Results -->
          <div
            v-if="searchQuery.trim().length > 0"
            class="mb-10 transition-all duration-300"
          >
            <template v-if="searchQuery.trim().length < 3">
              <div
                class="bg-slate-50 dark:bg-slate-800 p-8 rounded-lg text-center text-slate-500 dark:text-slate-400 shadow-sm border border-slate-200 dark:border-slate-700"
              >
                <p class="text-base md:text-lg font-medium">
                  Ketik minimal 3 karakter untuk mulai mencari alumni...
                </p>
              </div>
            </template>
            <template v-else-if="filteredAlumni.length > 0">
              <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[600px] overflow-y-auto custom-scrollbar pr-2 pb-2"
              >
                <div
                  v-for="alumni in filteredAlumni.slice(0, 24)"
                  :key="alumni.id"
                  class="bg-white dark:bg-slate-800 p-5 rounded-lg shadow-sm hover:shadow border border-slate-200 dark:border-slate-700 flex items-start gap-4 transition-all duration-300 hover:-translate-y-0.5"
                >
                  <div
                    class="w-12 h-12 md:w-14 md:h-14 shrink-0 rounded-lg bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-lg md:text-xl border border-blue-100 dark:border-slate-600"
                  >
                    {{ getInitials(alumni.name) }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <h4
                      class="font-bold text-slate-800 dark:text-slate-100 text-base md:text-lg truncate"
                    >
                      {{ alumni.name }}
                    </h4>
                    <div
                      class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 mb-2.5 font-medium"
                    >
                      NISN: {{ alumni.nisn }}
                    </div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                      <span
                        class="inline-flex px-2 py-0.5 rounded text-[10px] md:text-xs font-bold uppercase tracking-wider"
                        :class="getStatusColor(alumni.status)"
                      >
                        {{ alumni.status }}
                      </span>
                      <span class="text-xs text-slate-600 dark:text-slate-300 truncate">
                        Angkatan: <strong>{{ alumni.year }}</strong>
                      </span>
                    </div>
                    <div
                      v-if="alumni.instansi"
                      class="text-xs md:text-sm text-slate-600 dark:text-slate-300 truncate bg-slate-50 dark:bg-slate-700/50 rounded-lg px-3 py-2 border border-slate-100 dark:border-slate-600 flex items-center mt-1"
                    >
                      <PhBuildings class="shrink-0 w-4 h-4 mr-1.5 text-slate-400" />
                      <span class="truncate">{{ alumni.instansi }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div
                v-if="filteredAlumni.length > 24"
                class="mt-6 text-center text-slate-500 dark:text-slate-400 text-sm font-medium bg-slate-50 dark:bg-slate-800 py-2 px-4 rounded-lg inline-block mx-auto border border-slate-200 dark:border-slate-700"
              >
                Menampilkan 24 dari {{ filteredAlumni.length }} hasil. Gunakan kata kunci
                spesifik.
              </div>
            </template>
            <template v-else>
              <div
                class="bg-slate-50 dark:bg-slate-800 p-10 rounded-lg text-center text-slate-500 dark:text-slate-400 shadow-sm border border-slate-200 dark:border-slate-700"
              >
                <PhUsers
                  class="w-16 h-16 mx-auto mb-4 text-slate-300 dark:text-slate-600"
                />
                <p class="text-lg md:text-xl font-medium">
                  Tidak menemukan alumni dengan kriteria yang dicari.
                </p>
              </div>
            </template>
          </div>
        </div>

        <!-- Main Content Area -->
        <div class="px-5 md:px-10 pb-5 md:pb-10" v-show="searchQuery.trim().length === 0">
          <!-- Map Area -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
            <div
              class="lg:col-span-2 relative aspect-[4/3] sm:aspect-[2/1] bg-slate-100 dark:bg-slate-800/80 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden flex items-center justify-center p-2 shadow-inner bg-grid-pattern group"
            >
              <img
                src="/img/indonesia.svg"
                alt="Peta Indonesia"
                class="w-full h-full object-fill opacity-80 dark:opacity-50 pointer-events-none transition-transform duration-700 group-hover:scale-[1.02]"
                style="
                  filter: invert(30%) sepia(70%) saturate(2000%) hue-rotate(200deg)
                    brightness(80%) contrast(110%)
                    drop-shadow(0px 8px 12px rgba(0, 0, 0, 0.25));
                "
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
                    class="absolute bottom-full mb-3 opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none z-20 flex flex-col items-center translate-y-3 group-hover:translate-y-0"
                  >
                    <div
                      class="bg-slate-900/90 dark:bg-white/95 backdrop-blur-md text-white dark:text-slate-900 text-xs rounded-xl py-2 px-3 whitespace-nowrap shadow-2xl font-bold flex items-center gap-2.5 border border-white/10 dark:border-slate-200"
                    >
                      <span>{{ loc.name }}</span>
                      <span
                        class="bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded-full shadow-inner"
                        >{{ loc.totalAlumni }}</span
                      >
                    </div>
                    <div
                      class="w-2.5 h-2.5 bg-slate-900/90 dark:bg-white/95 rotate-45 -mt-1.5 border-r border-b border-white/10 dark:border-slate-200"
                    ></div>
                  </div>

                  <!-- Shadow Map -->
                  <div
                    class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-4 h-1.5 md:w-5 md:h-2 bg-black/40 rounded-[100%] blur-[2px] transition-transform duration-300"
                    :class="
                      selectedLocation?.id === loc.id
                        ? 'scale-50'
                        : 'group-hover:scale-50'
                    "
                  ></div>

                  <!-- Pin Icon (Bounce effect on hover) -->
                  <div
                    class="relative drop-shadow-[0_8px_12px_rgba(0,0,0,0.3)] transition-transform duration-300 origin-bottom"
                    :class="
                      selectedLocation?.id === loc.id
                        ? '-translate-y-3 scale-110'
                        : 'group-hover:-translate-y-3 group-hover:scale-110'
                    "
                  >
                    <PhMapPin
                      weight="fill"
                      class="w-8 h-8 md:w-10 md:h-10 transition-colors duration-300"
                      :class="
                        selectedLocation?.id === loc.id
                          ? 'text-blue-600 dark:text-blue-500'
                          : 'text-blue-400 dark:text-slate-500'
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
            <div class="lg:col-span-1 relative h-[450px] sm:h-[500px] lg:h-auto">
              <div
                class="absolute inset-0 bg-white dark:bg-slate-800 rounded-lg p-6 md:p-8 border border-slate-200 dark:border-slate-700 flex flex-col transition-all duration-300 overflow-hidden shadow-sm"
              >
                <template v-if="selectedLocation">
                  <div class="border-b border-slate-200 dark:border-slate-700 pb-5 mb-5">
                    <h3
                      class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white leading-tight tracking-tight mb-3"
                    >
                      {{ selectedLocation.name }}
                    </h3>
                    <div class="mb-3">
                      <span
                        class="inline-block bg-blue-100/80 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap border border-blue-200 dark:border-blue-800/50"
                      >
                        {{ selectedLocation.totalAlumni }} Alumni
                      </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                      Rincian instansi dan perguruan tinggi:
                    </p>
                  </div>

                  <div class="relative flex-1 min-h-0">
                    <div
                      class="absolute inset-0 overflow-y-auto pr-3 space-y-3.5 custom-scrollbar pb-6"
                    >
                      <div
                        v-for="inst in selectedLocation.institutions"
                        :key="inst.name"
                        class="flex items-center gap-4 p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/80 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow hover:-translate-y-0.5 transition-all duration-300 group"
                      >
                        <div
                          class="w-12 h-12 shrink-0 bg-white dark:bg-slate-700/50 rounded-md border border-slate-200 dark:border-slate-600 flex items-center justify-center overflow-hidden p-2 group-hover:scale-105 transition-transform"
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
                            class="font-bold text-sm text-slate-800 dark:text-slate-200 truncate"
                            :title="inst.name"
                          >
                            {{ inst.name }}
                          </h4>
                          <p class="text-xs text-slate-500 font-semibold mt-1">
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
                        class="text-center py-8 text-sm text-slate-500 font-medium"
                      >
                        Tidak ada detail instansi.
                      </div>
                    </div>

                    <!-- Bottom Fade & Scroll Indicator -->
                    <div
                      v-if="
                        selectedLocation.institutions &&
                        selectedLocation.institutions.length > 3
                      "
                      class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white dark:from-slate-800 to-transparent pointer-events-none z-10 flex justify-center items-end"
                    >
                      <div
                        class="text-xs font-semibold text-slate-500 flex items-center animate-bounce pb-1"
                      >
                        <PhArrowDown class="w-4 h-4 mr-1" />
                        Scroll ke bawah
                      </div>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <div
                    class="flex-1 flex flex-col items-center justify-center text-center text-slate-400 dark:text-slate-500"
                  >
                    <div
                      class="w-24 h-24 bg-white/50 dark:bg-slate-800/50 rounded-full shadow-inner flex items-center justify-center mb-5 border border-slate-200 dark:border-slate-700"
                    >
                      <PhMapTrifold
                        class="w-12 h-12 text-slate-300 dark:text-slate-500"
                      />
                    </div>
                    <p class="text-sm font-medium leading-relaxed px-4">
                      Klik salah satu titik pada peta untuk melihat detail persebaran
                      alumni di wilayah tersebut.
                    </p>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
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
  PhUsers,
  PhMapPin,
  PhXCircle,
  PhArrowDown,
} from "@phosphor-icons/vue";

const mapLocations = ref([]);
const alumniList = ref([]);
const selectedLocation = ref(null);
const isLoadingMap = ref(true);
const isLoadingAlumni = ref(true);

const searchQuery = ref("");
const selectedYear = ref("");

const availableYears = computed(() => {
  const years = alumniList.value.map((a) => a.year).filter((y) => y);
  return [...new Set(years)].sort((a, b) => b - a);
});

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

const filteredAlumni = computed(() => {
  const query = searchQuery.value.trim().toLowerCase();
  const year = selectedYear.value;

  if (query.length < 3) return [];

  return alumniList.value.filter((a) => {
    const matchName = a.name?.toLowerCase().includes(query);
    const matchInstansi = a.instansi?.toLowerCase().includes(query);
    const matchNisn = a.nisn?.toLowerCase().includes(query);
    const matchYearQuery = a.year?.toString().includes(query);
    const matchQuery = matchName || matchInstansi || matchNisn || matchYearQuery;

    let matchYearFilter = true;
    if (year) {
      matchYearFilter = a.year?.toString() === year.toString();
    }

    return matchQuery && matchYearFilter;
  });
});

const getInitials = (name) => {
  if (!name) return "A";
  return name
    .split(" ")
    .map((n) => n[0])
    .join("")
    .substring(0, 2)
    .toUpperCase();
};

const getStatusColor = (status) => {
  if (status === "Kuliah")
    return "bg-blue-50 text-blue-600 border border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800/50";
  if (status === "Bekerja")
    return "bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800/50";
  if (status === "Wirausaha")
    return "bg-purple-50 text-purple-600 border border-purple-200 dark:bg-purple-900/20 dark:text-purple-400 dark:border-purple-800/50";
  return "bg-slate-50 text-slate-600 border border-slate-200 dark:bg-slate-800/50 dark:text-slate-400 dark:border-slate-700/50";
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

/* Grid Pattern Background for Map Area */
.bg-grid-pattern {
  background-image: linear-gradient(
      to right,
      rgba(148, 163, 184, 0.1) 1px,
      transparent 1px
    ),
    linear-gradient(to bottom, rgba(148, 163, 184, 0.1) 1px, transparent 1px);
  background-size: 24px 24px;
}
.dark .bg-grid-pattern {
  background-image: linear-gradient(
      to right,
      rgba(255, 255, 255, 0.05) 1px,
      transparent 1px
    ),
    linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
}
</style>
