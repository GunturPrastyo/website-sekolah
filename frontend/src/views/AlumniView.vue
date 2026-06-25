<template>
  <div
    class="overflow-x-hidden w-full bg-slate-50/50 dark:bg-slate-900 min-h-screen relative"
  >
    <!-- Header / Hero Section -->
    <PageHeader
      badge="Jejak Langkah Lulusan"
      title="Direktori & Persebaran Alumni"
      description="Temukan jejak sukses para alumni sekolah kami yang tersebar di berbagai perguruan tinggi favorit dan instansi bergengsi di seluruh Indonesia."
      :bgImage="appearanceSettings.headerAlumni"
    />

    <!-- Direktori & Persebaran Alumni Section -->
    <section class="py-8 md:py-16 relative z-10 bg-white dark:bg-white">
      <div
        class="container mx-auto md:rounded-2xl flex flex-col w-full overflow-hidden bg-white dark:bg-white shadow-sm border border-slate-100 dark:border-slate-700/60"
      >
        <!-- Search Area -->
        <div class="px-6 md:px-12 pt-8 md:pt-10">
          <div class="flex flex-col md:flex-row gap-4 mb-10 max-w-4xl">
            <!-- Search Input -->
            <div class="relative group flex-1">
              <div
                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
              >
                <PhMagnifyingGlass
                  class="w-5 h-5 text-slate-400 group-focus-within:text-blue-600 transition-colors"
                />
              </div>
              <input
                type="text"
                v-model="searchQuery"
                placeholder="Cari berdasarkan Nama, NISN, atau Instansi..."
                class="w-full pl-12 pr-12 py-4 bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-900 dark:text-white text-base transition-all outline-none"
                style="font-family: 'Plus Jakarta Sans', sans-serif"
              />
              <div
                v-if="isLoadingAlumni"
                class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none"
              >
                <div
                  class="animate-spin rounded-full h-5 w-5 border-2 border-slate-300 border-t-blue-600"
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
            <div class="w-full md:w-64 shrink-0 relative">
              <select
                v-model="selectedYear"
                class="w-full py-4 pl-5 pr-10 bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-900 dark:text-white text-base transition-all outline-none appearance-none cursor-pointer font-medium"
                style="font-family: 'Plus Jakarta Sans', sans-serif"
              >
                <option value="">Semua Angkatan</option>
                <option v-for="year in availableYears" :key="year" :value="year">
                  Angkatan {{ year }}
                </option>
              </select>
              <div
                class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400"
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
            class="mb-12 transition-all duration-300"
          >
            <template v-if="searchQuery.trim().length < 3">
              <div
                class="bg-slate-50/50 dark:bg-slate-900/50 p-8 rounded-2xl text-center text-slate-500 dark:text-slate-400 border border-slate-100 dark:border-slate-700/50"
              >
                <p class="text-base font-medium">
                  Ketik minimal 3 karakter untuk mulai mencari alumni...
                </p>
              </div>
            </template>
            <template v-else-if="filteredAlumni.length > 0">
              <div
                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 max-h-[600px] overflow-y-auto custom-scrollbar pr-2 pb-2"
              >
                <div
                  v-for="alumni in filteredAlumni.slice(0, 24)"
                  :key="alumni.id"
                  class="group flex items-start gap-4 p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 hover:shadow-lg hover:border-slate-200 dark:hover:border-slate-600 transition-all duration-300"
                >
                  <!-- Avatar -->
                  <div
                    class="w-14 h-14 shrink-0 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-700 dark:to-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xl border border-blue-100/50 dark:border-slate-600/50"
                  >
                    {{ getInitials(alumni.name) }}
                  </div>

                  <div class="flex-1 min-w-0 pt-0.5">
                    <h4
                      class="font-bold text-slate-900 dark:text-white text-base md:text-lg truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                      style="font-family: 'Plus Jakarta Sans', sans-serif"
                    >
                      {{ alumni.name }}
                    </h4>
                    <p
                      class="text-xs text-slate-500 dark:text-slate-400 mb-2.5 font-medium tracking-wide"
                    >
                      NISN: {{ alumni.nisn }}
                    </p>

                    <div class="flex flex-wrap items-center gap-2 mb-3">
                      <span
                        class="inline-flex px-2.5 py-0.5 rounded text-[10px] md:text-[11px] font-bold uppercase tracking-wider"
                        :class="getStatusColor(alumni.status)"
                      >
                        {{ alumni.status }}
                      </span>
                      <span
                        class="text-xs text-slate-600 dark:text-slate-300 font-medium"
                      >
                        Angkatan
                        <span class="font-bold text-slate-900 dark:text-white">{{
                          alumni.year
                        }}</span>
                      </span>
                    </div>

                    <div
                      v-if="alumni.instansi"
                      class="flex items-center text-xs md:text-sm text-slate-600 dark:text-slate-300 font-medium bg-slate-50 dark:bg-slate-900/50 rounded-lg px-3 py-2 border border-slate-100 dark:border-slate-700/50"
                    >
                      <PhBuildings class="shrink-0 w-4 h-4 mr-2 text-slate-400" />
                      <span class="truncate">{{ alumni.instansi }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="filteredAlumni.length > 24" class="mt-8 text-center">
                <span
                  class="text-slate-500 dark:text-slate-400 text-sm font-medium bg-slate-50/50 dark:bg-slate-900/50 py-2.5 px-5 rounded-full inline-block border border-slate-200/60 dark:border-slate-700/50"
                >
                  Menampilkan 24 dari {{ filteredAlumni.length }} hasil. Gunakan kata
                  kunci lebih spesifik.
                </span>
              </div>
            </template>
            <template v-else>
              <div
                class="bg-slate-50/50 dark:bg-slate-900/50 py-12 px-6 rounded-2xl text-center text-slate-500 dark:text-slate-400 border border-slate-100 dark:border-slate-700/50"
              >
                <div
                  class="w-16 h-16 rounded-full bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-slate-700"
                >
                  <PhUsers class="w-8 h-8 text-slate-400 dark:text-slate-500" />
                </div>
                <p class="text-lg font-medium text-slate-600 dark:text-slate-300">
                  Tidak ditemukan alumni dengan kriteria tersebut.
                </p>
                <p class="text-sm mt-1">Coba gunakan nama panggilan atau ejaan lain.</p>
              </div>
            </template>
          </div>
        </div>

        <!-- Main Content Area -->
        <div class="px-6 md:px-12 pb-8 md:pb-12" v-show="searchQuery.trim().length === 0">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-stretch">
            <!-- Map Area -->
            <!-- Dihapus group & hover:scale untuk mematikan interaksi pada bg peta -->
            <div
              class="lg:col-span-8 relative aspect-[4/3] sm:aspect-[2/1] lg:aspect-[1.8/1] bg-slate-50/80 dark:bg-slate-900/50 rounded-2xl border border-slate-200/60 dark:border-slate-700/50 overflow-hidden flex items-center justify-center p-2 lg:p-4 bg-grid-pattern shadow-inner"
            >
              <!-- Gambar Peta Statis -->
              <img
                src="/img/indonesia.svg"
                alt="Peta Indonesia"
                class="w-full h-full object-fill opacity-40 dark:opacity-30 pointer-events-none"
                style="
                  filter: invert(45%) sepia(10%) saturate(1000%) hue-rotate(180deg)
                    brightness(95%) contrast(85%);
                "
              />

              <template v-if="isLoadingMap">
                <div
                  class="absolute inset-0 flex items-center justify-center bg-white/40 dark:bg-slate-900/40 backdrop-blur-sm z-20"
                >
                  <div
                    class="animate-spin rounded-full h-10 w-10 border-4 border-slate-200 border-t-blue-600"
                  ></div>
                </div>
              </template>

              <template v-else>
                <!-- Pin Lokasi (Tetap interaktif) -->
                <div
                  v-for="loc in mapLocations"
                  :key="loc.id"
                  class="absolute flex justify-center items-end group/pin cursor-pointer hover:z-50 focus:z-50 w-8 h-10 md:w-10 md:h-12 -translate-x-1/2 -translate-y-full focus:outline-none"
                  :style="{
                    top: loc.top,
                    left: loc.left,
                    zIndex: selectedLocation?.id === loc.id ? 50 : 10,
                  }"
                  @click="selectedLocation = loc"
                >
                  <!-- Tooltip Hover Clean Design -->
                  <div
                    class="absolute bottom-full mb-3 opacity-0 group-hover/pin:opacity-100 transition-all duration-300 pointer-events-none z-20 flex flex-col items-center translate-y-2 group-hover/pin:translate-y-0"
                  >
                    <div
                      class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs rounded-lg py-1.5 px-3 whitespace-nowrap shadow-xl font-semibold flex items-center gap-2"
                      style="font-family: 'Plus Jakarta Sans', sans-serif"
                    >
                      {{ loc.name }}
                      <span
                        class="bg-blue-500/20 text-blue-300 dark:text-blue-600 text-[10px] px-1.5 py-0.5 rounded font-bold"
                      >
                        {{ loc.totalAlumni }}
                      </span>
                    </div>
                    <div
                      class="w-2.5 h-2.5 bg-slate-900 dark:bg-white rotate-45 -mt-1.5 rounded-sm"
                    ></div>
                  </div>

                  <!-- Shadow Map (Pin) -->
                  <div
                    class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-4 h-1.5 md:w-5 md:h-2 bg-black/30 rounded-[100%] blur-[2px] transition-transform duration-300"
                    :class="
                      selectedLocation?.id === loc.id
                        ? 'scale-50'
                        : 'group-hover/pin:scale-50'
                    "
                  ></div>

                  <!-- Pin Icon (Solid Color Design) -->
                  <div
                    class="relative drop-shadow-md transition-transform duration-300 origin-bottom"
                    :class="
                      selectedLocation?.id === loc.id
                        ? '-translate-y-2 scale-110'
                        : 'group-hover/pin:-translate-y-2 group-hover/pin:scale-110'
                    "
                  >
                    <PhMapPin
                      weight="fill"
                      class="w-8 h-8 md:w-10 md:h-10 transition-colors duration-300"
                      :class="
                        selectedLocation?.id === loc.id
                          ? 'text-blue-600 dark:text-blue-500'
                          : 'text-slate-400 dark:text-slate-500'
                      "
                    />
                    <!-- Inner Dot -->
                    <div
                      class="absolute top-[8px] left-1/2 -translate-x-1/2 w-2 h-2 md:top-[10px] md:w-2.5 md:h-2.5 bg-white rounded-full transition-colors"
                      :class="
                        selectedLocation?.id === loc.id ? 'bg-white' : 'bg-slate-100'
                      "
                    ></div>
                  </div>
                </div>
              </template>
            </div>

            <!-- Location Detail Sidebar (Professional Clean Layout) -->
            <div class="lg:col-span-4 relative h-[450px] sm:h-[500px] lg:h-auto">
              <div
                class="absolute inset-0 bg-white dark:bg-slate-800 rounded-2xl p-6 lg:p-8 flex flex-col transition-all duration-300 overflow-hidden shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 dark:border-slate-700/60"
              >
                <template v-if="selectedLocation">
                  <div
                    class="pb-5 mb-5 border-b border-slate-100 dark:border-slate-700/50"
                  >
                    <h4
                      class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5"
                    >
                      Wilayah Terpilih
                    </h4>
                    <h3
                      class="text-xl md:text-2xl font-extrabold text-slate-900 dark:text-white leading-tight mb-3"
                      style="font-family: 'Plus Jakarta Sans', sans-serif"
                    >
                      {{ selectedLocation.name }}
                    </h3>
                    <div class="flex items-center gap-2">
                      <span
                        class="inline-flex items-center bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 px-3 py-1 rounded-md text-xs font-bold border border-blue-100 dark:border-blue-500/20"
                      >
                        <PhUsers class="w-3.5 h-3.5 mr-1.5" />
                        {{ selectedLocation.totalAlumni }} Alumni
                      </span>
                    </div>
                  </div>

                  <!-- Institution List -->
                  <div class="relative flex-1 min-h-0">
                    <div
                      class="absolute inset-0 overflow-y-auto pr-3 space-y-3 custom-scrollbar pb-6"
                    >
                      <div
                        v-for="inst in selectedLocation.institutions"
                        :key="inst.name"
                        class="flex items-center gap-4 p-3.5 rounded-xl bg-slate-50/80 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-700/50 hover:bg-white dark:hover:bg-slate-800 hover:border-slate-200 dark:hover:border-slate-600 hover:shadow-sm transition-all duration-200"
                      >
                        <div
                          class="w-12 h-12 shrink-0 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-center p-2"
                        >
                          <img
                            v-if="inst.logo"
                            :src="inst.logo"
                            class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal"
                            :alt="inst.name"
                          />
                          <PhBuildings
                            v-else
                            class="w-5 h-5 text-slate-300 dark:text-slate-600"
                          />
                        </div>
                        <div class="flex-1 min-w-0 pt-0.5">
                          <h4
                            class="font-bold text-sm text-slate-800 dark:text-slate-100 truncate mb-1"
                            :title="inst.name"
                          >
                            {{ inst.name }}
                          </h4>
                          <div class="flex items-center text-[11px] font-semibold">
                            <span class="text-slate-500 dark:text-slate-400">
                              {{
                                inst.type === "ptn"
                                  ? "PTN"
                                  : inst.type === "kedinasan"
                                  ? "Kedinasan"
                                  : "BUMN/Instansi"
                              }}
                            </span>
                            <span class="mx-2 text-slate-300 dark:text-slate-600">•</span>
                            <span class="text-blue-600 dark:text-blue-400"
                              >{{ inst.alumni }} orang</span
                            >
                          </div>
                        </div>
                      </div>

                      <div
                        v-if="
                          !selectedLocation.institutions ||
                          selectedLocation.institutions.length === 0
                        "
                        class="text-center py-10"
                      >
                        <p class="text-sm text-slate-500 font-medium">
                          Belum ada detail instansi dicatat.
                        </p>
                      </div>
                    </div>

                    <!-- Scroll Indicator -->
                    <div
                      v-if="
                        selectedLocation.institutions &&
                        selectedLocation.institutions.length > 4
                      "
                      class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white dark:from-slate-800 to-transparent pointer-events-none z-10 flex justify-center items-end"
                    ></div>
                  </div>
                </template>

                <template v-else>
                  <!-- Empty State yang lebih bersih -->
                  <div
                    class="flex-1 flex flex-col items-center justify-center text-center px-4"
                  >
                    <div
                      class="w-20 h-20 bg-slate-50 dark:bg-slate-900/50 rounded-2xl flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-700/50"
                    >
                      <PhMapTrifold
                        class="w-10 h-10 text-slate-300 dark:text-slate-600"
                      />
                    </div>
                    <h3
                      class="text-base font-bold text-slate-700 dark:text-slate-200 mb-2"
                    >
                      Pilih Lokasi
                    </h3>
                    <p
                      class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed max-w-[250px]"
                    >
                      Pilih salah satu pin pada peta untuk melihat persebaran detail
                      alumni.
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

// Reactive State
const mapLocations = ref([]);
const alumniList = ref([]);
const appearanceSettings = ref({});
const selectedLocation = ref(null);
const isLoadingMap = ref(true);
const isLoadingAlumni = ref(true);

const searchQuery = ref("");
const selectedYear = ref("");

// Computed: Ekstrak tahun angkatan unik dari daftar alumni
const availableYears = computed(() => {
  const years = alumniList.value.map((a) => a.year).filter((y) => y);
  return [...new Set(years)].sort((a, b) => b - a);
});

const fetchInitialData = async () => {
  isLoadingMap.value = true;
  isLoadingAlumni.value = true;

  try {
    const [mapRes, alumniRes, settingsRes] = await Promise.all([
      api.get("/api/public-map-locations"),
      api.get("/api/public-alumnis"),
      api.get("/api/settings"),
    ]);

    mapLocations.value = mapRes.data.data;
    if (mapLocations.value.length > 0) {
      selectedLocation.value = [...mapLocations.value].sort(
        (a, b) => b.totalAlumni - a.totalAlumni
      )[0];
    }

    alumniList.value = alumniRes.data.data;

    if (settingsRes.data?.success) {
      appearanceSettings.value = settingsRes.data.data;
    }
  } catch (error) {
    console.error("Gagal mengambil data inisialisasi alumni:", error);
  } finally {
    isLoadingMap.value = false;
    isLoadingAlumni.value = false;
  }
};

onMounted(() => {
  fetchInitialData();
  window.scrollTo(0, 0);
});

// Computed: Filter pencarian alumni
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
    return "bg-blue-100/50 text-blue-700 border border-blue-200/60 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50";
  if (status === "Bekerja")
    return "bg-emerald-100/50 text-emerald-700 border border-emerald-200/60 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50";
  if (status === "Wirausaha")
    return "bg-purple-100/50 text-purple-700 border border-purple-200/60 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800/50";
  return "bg-slate-100/50 text-slate-600 border border-slate-200/60 dark:bg-slate-800/50 dark:text-slate-400 dark:border-slate-700/50";
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap");

.custom-scrollbar::-webkit-scrollbar {
  width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #475569;
}
.custom-scrollbar:hover::-webkit-scrollbar-thumb {
  background-color: #94a3b8;
}

/* Grid Pattern Halus untuk Peta */
.bg-grid-pattern {
  background-image: linear-gradient(
      to right,
      rgba(148, 163, 184, 0.05) 1px,
      transparent 1px
    ),
    linear-gradient(to bottom, rgba(148, 163, 184, 0.05) 1px, transparent 1px);
  background-size: 24px 24px;
}
.dark .bg-grid-pattern {
  background-image: linear-gradient(
      to right,
      rgba(255, 255, 255, 0.03) 1px,
      transparent 1px
    ),
    linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
}
</style>
