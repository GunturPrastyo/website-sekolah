<template>
  <div
    class="overflow-x-hidden w-full bg-slate-50 dark:bg-slate-900 min-h-screen relative"
  >
    <!-- Ambient Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
      <div
        class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-400/10 dark:bg-blue-600/10 blur-[120px]"
      ></div>
      <div
        class="absolute top-[40%] -right-[10%] w-[40%] h-[40%] rounded-full bg-indigo-400/10 dark:bg-indigo-600/10 blur-[120px]"
      ></div>
    </div>

    <!-- Header / Hero Section -->
    <PageHeader
      badge="Jejak Langkah Lulusan"
      title="Direktori & Persebaran Alumni"
      description="Temukan jejak sukses para alumni SMAN 1 Nogosari yang tersebar di berbagai perguruan tinggi favorit dan instansi bergengsi di seluruh Indonesia."
    />

    <!-- Main Content Section -->
    <section class="py-8 md:py-12 container mx-auto px-0 md:px-4 lg:px-8 relative z-10">
      <div
        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-2xl md:rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-white/60 dark:border-slate-700/50 flex flex-col w-full overflow-hidden"
      >
        <!-- Main Content Area -->
        <div class="p-5 md:p-10 flex flex-col gap-10 md:gap-14">
          <!-- Map Area -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Map Area -->
            <div
              class="lg:col-span-2 relative aspect-[4/3] sm:aspect-[2/1] bg-slate-50/50 dark:bg-slate-900/50 rounded-3xl border border-slate-200/80 dark:border-slate-700 overflow-hidden flex items-center justify-center p-2 shadow-inner bg-grid-pattern group"
            >
              <img
                src="/img/indonesia.svg"
                alt="Peta Indonesia"
                class="w-full h-full object-fill opacity-30 dark:opacity-20 pointer-events-none drop-shadow-lg transition-transform duration-700 group-hover:scale-[1.02]"
                style="filter: invert(45%) sepia(60%) saturate(250%) hue-rotate(190deg)"
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
                          ? 'text-yellow-400'
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
              class="lg:col-span-1 bg-slate-50/80 dark:bg-slate-800/50 rounded-3xl p-6 md:p-8 border border-slate-200/60 dark:border-slate-700 h-100 sm:h-112.5 flex flex-col transition-all duration-300 relative overflow-hidden"
            >
              <template v-if="selectedLocation">
                <div class="border-b border-slate-200 dark:border-slate-700 pb-5 mb-5">
                  <div class="flex justify-between items-start mb-2">
                    <h3
                      class="text-xl md:text-2xl font-extrabold text-slate-900 dark:text-white leading-tight tracking-tight"
                    >
                      {{ selectedLocation.name }}
                    </h3>
                    <div
                      class="bg-blue-100/80 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap border border-blue-200 dark:border-blue-800/50"
                    >
                      {{ selectedLocation.totalAlumni }} Alumni
                    </div>
                  </div>
                  <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    Rincian instansi dan perguruan tinggi:
                  </p>
                </div>

                <div class="flex-1 overflow-y-auto pr-3 space-y-3.5 custom-scrollbar">
                  <div
                    v-for="inst in selectedLocation.institutions"
                    :key="inst.name"
                    class="flex items-center gap-4 p-3.5 rounded-2xl bg-white dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:shadow-[0_8px_20px_-6px_rgba(6,81,237,0.12)] hover:-translate-y-0.5 transition-all duration-300 group"
                  >
                    <div
                      class="w-12 h-12 shrink-0 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-100 dark:border-slate-600 flex items-center justify-center overflow-hidden p-2 group-hover:scale-105 transition-transform"
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
              </template>
              <template v-else>
                <div
                  class="flex-1 flex flex-col items-center justify-center text-center text-slate-400 dark:text-slate-500"
                >
                  <div
                    class="w-24 h-24 bg-white/50 dark:bg-slate-800/50 rounded-full shadow-inner flex items-center justify-center mb-5 border border-slate-200 dark:border-slate-700"
                  >
                    <PhMapTrifold class="w-12 h-12 text-slate-300 dark:text-slate-500" />
                  </div>
                  <p class="text-sm font-medium leading-relaxed px-4">
                    Klik salah satu titik pada peta untuk melihat detail persebaran alumni
                    di wilayah tersebut.
                  </p>
                </div>
              </template>
            </div>
          </div>

          <!-- Command Bar / Filter Area -->
          <div
            class="flex flex-col md:flex-row gap-3 p-2 bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl md:rounded-full rounded-3xl border border-slate-200/80 dark:border-slate-700 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] px-2 md:px-4 py-2 mx-4 md:mx-0"
          >
            <div class="relative flex-1 group">
              <div
                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600"
              >
                <PhMagnifyingGlass
                  class="w-5 h-5 text-slate-400 group-focus-within:text-blue-600"
                />
              </div>
              <input
                type="text"
                v-model="searchQuery"
                @input="resetPagination"
                placeholder="Cari nama alumni atau instansi..."
                class="w-full pl-11 pr-4 py-3 bg-transparent border-none focus:ring-0 text-slate-900 dark:text-white text-sm font-medium transition-all placeholder-slate-400 dark:placeholder-slate-500 outline-none"
              />
            </div>

            <div
              class="w-full md:w-px h-px md:h-8 bg-slate-200 dark:bg-slate-700 my-auto hidden md:block"
            ></div>

            <div class="flex gap-4 w-full md:w-64 shrink-0">
              <div class="relative w-full group">
                <div
                  class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600"
                >
                  <PhCalendarBlank
                    class="w-5 h-5 text-slate-400 group-focus-within:text-blue-600"
                  />
                </div>
                <select
                  v-model="selectedYear"
                  @change="resetPagination"
                  class="w-full pl-11 pr-10 py-3 bg-transparent border-none focus:ring-0 text-slate-900 dark:text-white text-sm font-medium appearance-none cursor-pointer outline-none"
                >
                  <option value="">Semua Angkatan</option>
                  <option v-for="year in uniqueYears" :key="year" :value="year">
                    Angkatan {{ year }}
                  </option>
                </select>
                <div
                  class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none"
                >
                  <PhCaretDown class="w-4 h-4 text-slate-400" />
                </div>
              </div>
            </div>
          </div>

          <!-- Alumni Grid -->
          <div class="relative min-h-75 px-4 md:px-0">
            <template v-if="isLoadingAlumni">
              <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8"
              >
                <div
                  v-for="n in 8"
                  :key="n"
                  class="bg-white/50 dark:bg-slate-800/50 rounded-3xl p-6 border border-slate-200/50 dark:border-slate-700/50 flex flex-col h-full relative overflow-hidden animate-pulse"
                >
                  <div class="flex items-start gap-4 mb-5">
                    <div
                      class="w-16 h-16 shrink-0 rounded-full bg-slate-200 dark:bg-slate-700"
                    ></div>
                    <div class="flex-1 min-w-0 pt-1 space-y-2">
                      <div class="h-5 bg-gray-200 dark:bg-slate-700 rounded w-3/4"></div>
                      <div class="h-4 bg-gray-200 dark:bg-slate-700 rounded w-1/2"></div>
                    </div>
                  </div>
                  <div
                    class="mt-auto pt-5 border-t border-gray-100 dark:border-slate-700 flex flex-col gap-3"
                  >
                    <div class="flex items-start gap-3">
                      <div
                        class="w-8 h-8 rounded-full bg-gray-200 dark:bg-slate-700 shrink-0"
                      ></div>
                      <div class="flex-1 space-y-2 py-1">
                        <div
                          class="h-4 bg-gray-200 dark:bg-slate-700 rounded w-full"
                        ></div>
                        <div
                          class="h-4 bg-gray-200 dark:bg-slate-700 rounded w-5/6"
                        ></div>
                      </div>
                    </div>
                    <div
                      class="h-5 bg-gray-200 dark:bg-slate-700 rounded w-1/4 mt-2"
                    ></div>
                  </div>
                </div>
              </div>
            </template>

            <template v-else-if="paginatedAlumni.length > 0">
              <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8"
              >
                <div
                  v-for="alumni in paginatedAlumni"
                  :key="alumni.id"
                  class="bg-white dark:bg-slate-800/90 backdrop-blur-xl rounded-3xl p-6 shadow-sm border border-slate-200/80 dark:border-slate-700/80 transition-all duration-500 hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-1 hover:border-blue-300/50 dark:hover:border-blue-700/50 flex flex-col h-full group relative overflow-hidden z-10"
                >
                  <!-- Decorative top glow -->
                  <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                  ></div>

                  <div class="flex items-start gap-5 mb-6">
                    <div
                      class="w-16 h-16 shrink-0 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-2xl shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-500 ring-4 ring-white dark:ring-slate-800"
                    >
                      {{ getInitials(alumni.name) }}
                    </div>
                    <div class="flex-1 min-w-0 pt-2">
                      <h3
                        class="font-extrabold text-slate-900 dark:text-white text-lg leading-tight truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors tracking-tight"
                        :title="alumni.name"
                      >
                        {{ alumni.name }}
                      </h3>
                      <div
                        class="flex items-center text-sm text-slate-500 dark:text-slate-400 mt-1.5 font-medium"
                      >
                        <PhGraduationCap class="w-4 h-4 mr-1.5 text-blue-500" /> Lulusan
                        {{ alumni.year }}
                      </div>
                    </div>
                  </div>

                  <div
                    class="mt-auto pt-5 border-t border-slate-100 dark:border-slate-700/80 flex flex-col gap-4"
                  >
                    <div class="flex items-start">
                      <div class="mt-0.5 mr-3 shrink-0">
                        <div
                          class="w-9 h-9 rounded-full bg-slate-50 dark:bg-slate-700/50 flex items-center justify-center border border-slate-200/60 dark:border-slate-600"
                        >
                          <component
                            :is="getStatusIcon(alumni.status)"
                            class="w-4.5 h-4.5 text-slate-500 dark:text-slate-400"
                          />
                        </div>
                      </div>
                      <div
                        class="flex-1 min-w-0 flex flex-col justify-center min-h-[36px]"
                      >
                        <span
                          class="font-semibold text-slate-700 dark:text-slate-300 text-sm line-clamp-2 leading-relaxed"
                          :title="alumni.instansi"
                        >
                          {{ alumni.instansi || "Belum ada data instansi" }}
                        </span>
                      </div>
                    </div>
                    <div class="flex">
                      <span
                        class="inline-flex px-3 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-widest shadow-sm"
                        :class="getStatusColor(alumni.status)"
                      >
                        {{ alumni.status }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Pagination -->
              <div
                v-if="totalPages > 1"
                class="mt-12 flex justify-center items-center gap-2"
              >
                <button
                  @click="prevPage"
                  :disabled="currentPage === 1"
                  class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  <PhCaretLeft class="w-5 h-5" />
                </button>

                <div class="flex gap-1">
                  <button
                    v-for="page in totalPages"
                    :key="page"
                    @click="currentPage = page"
                    v-show="
                      Math.abs(page - currentPage) < 3 ||
                      page === 1 ||
                      page === totalPages
                    "
                    class="w-10 h-10 rounded-full text-sm font-bold transition-all duration-300"
                    :class="
                      currentPage === page
                        ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30'
                        : 'text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700'
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
                  class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  <PhCaretRight class="w-5 h-5" />
                </button>
              </div>
            </template>

            <template v-else>
              <div
                class="flex flex-col items-center justify-center py-16 px-4 text-center"
              >
                <div
                  class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6 shadow-inner"
                >
                  <PhUsers class="w-12 h-12 text-slate-300 dark:text-slate-600" />
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                  Data tidak ditemukan
                </h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-md">
                  Maaf, kami tidak dapat menemukan data alumni yang sesuai dengan filter
                  pencarian Anda. Coba ubah kata kunci atau filter.
                </p>
                <button
                  @click="
                    searchQuery = '';
                    selectedYear = '';
                    resetPagination();
                  "
                  class="mt-6 text-blue-600 font-bold hover:text-blue-700 bg-blue-50 dark:bg-blue-900/20 px-6 py-2.5 rounded-full transition-colors"
                >
                  Reset Pencarian
                </button>
              </div>
            </template>
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
