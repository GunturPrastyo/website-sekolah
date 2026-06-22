<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from "vue";
import {
  PhSquaresFour,
  PhActivity,
  PhPalette,
  PhFlask,
  PhUsers,
  PhCalendar,
  PhCaretDown,
  PhMagnifyingGlass,
  PhFunnel,
  PhClock,
  PhUser,
  PhArrowUpRight,
  PhCaretLeft,
  PhCaretRight,
  PhLightbulb,
  PhDownloadSimple,
  PhClipboardText,
  PhFileX,
} from "@phosphor-icons/vue";
import PageHeader from "@/components/PageHeader.vue";
import EkskulModal from "@/components/EkskulModal.vue";
import api from "@/api/index.js";

const activeCategory = ref("semua");

const categories = [
  { id: "semua", name: "Semua Kategori", icon: PhSquaresFour },
  { id: "olahraga", name: "Olahraga", icon: PhActivity },
  { id: "seni", name: "Seni & Budaya", icon: PhPalette },
  { id: "akademik", name: "Akademik & Sains", icon: PhFlask },
  { id: "kepemimpinan", name: "Kepemimpinan", icon: PhUsers },
];

const activeDay = ref("semua");
const isDayDropdownOpen = ref(false);

const days = [
  { id: "semua", name: "Semua Hari" },
  { id: "senin", name: "Senin" },
  { id: "selasa", name: "Selasa" },
  { id: "rabu", name: "Rabu" },
  { id: "kamis", name: "Kamis" },
  { id: "jumat", name: "Jumat" },
  { id: "sabtu", name: "Sabtu" },
];

const ekskulList = ref([]);
const appearanceSettings = ref({});
const isFetching = ref(true);

const getImageUrl = (path) => {
  if (!path)
    return "https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=800";
  if (path.startsWith("http") || path.startsWith("data:")) return path;
  const backendUrl =
    import.meta.env.VITE_API_URL || "https://api-sekolah-sma.duckdns.org";
  return `${backendUrl.replace(/\/+$/, "")}/${path.replace(/^\/+/, "")}`;
};

const parseJSON = (data) => {
  if (!data) return {};
  if (typeof data === "string") {
    try {
      return JSON.parse(data);
    } catch (e) {
      return {};
    }
  }
  return data;
};

const fetchInitialData = async () => {
  isFetching.value = true;
  try {
    const [ekskulResponse, settingsResponse] = await Promise.all([
      api.get("/api/public-extracurriculars"),
      api.get("/api/settings"),
    ]);

    if (ekskulResponse.data && ekskulResponse.data.data) {
      ekskulList.value = ekskulResponse.data.data.map((item) => ({
        id: item.id,
        name: item.name,
        category: item.category || "semua",
        image: getImageUrl(item.image),
        schedule: item.schedule || "-",

        desc: item.story || "",
        story: item.story || "",

        pembina: item.pembina || item.coach || "-",
        members: item.members || item.members_count || 0,
        socials: parseJSON(item.socials),
      }));
    }

    if (settingsResponse.data?.success) {
      appearanceSettings.value = settingsResponse.data.data;
    }
  } catch (error) {
    console.error("Gagal memuat data inisialisasi halaman ekstrakurikuler:", error);
  } finally {
    isFetching.value = false;
  }
};

const searchQuery = ref("");

const filteredEkskul = computed(() => {
  let filtered = ekskulList.value;

  if (activeCategory.value !== "semua") {
    filtered = filtered.filter((ekskul) => ekskul.category === activeCategory.value);
  }

  if (activeDay.value !== "semua") {
    filtered = filtered.filter((ekskul) =>
      ekskul.schedule.toLowerCase().includes(activeDay.value.toLowerCase())
    );
  }

  if (searchQuery.value.trim()) {
    const query = searchQuery.value.trim().toLowerCase();
    filtered = filtered.filter((ekskul) => ekskul.name.toLowerCase().includes(query));
  }

  return filtered;
});

// --- Fitur Pagination ---
const itemsPerPage = 4;
const currentPage = ref(1);
const isLoading = ref(false);

const totalPages = computed(() => {
  return Math.ceil(filteredEkskul.value.length / itemsPerPage);
});

const paginatedEkskul = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredEkskul.value.slice(start, end);
});

watch([activeCategory, activeDay, searchQuery], () => {
  isLoading.value = true;
  currentPage.value = 1;
  setTimeout(() => {
    isLoading.value = false;
  }, 400);
});

const changePage = (page) => {
  if (page < 1 || page > totalPages.value) return;
  isLoading.value = true;
  setTimeout(() => {
    currentPage.value = page;
    isLoading.value = false;
    const container = document.getElementById("ekskul-list-container");
    if (container) {
      window.scrollTo({ top: container.offsetTop - 120, behavior: "smooth" });
    }
  }, 400);
};

const skeletonCount = computed(() => {
  if (!isLoading.value && !isFetching.value) return 0;
  return itemsPerPage;
});
// ------------------------

const getCategoryCount = (categoryId) => {
  if (categoryId === "semua") return ekskulList.value.length;
  return ekskulList.value.filter((ekskul) => ekskul.category === categoryId).length;
};

const scheduleByDay = computed(() => {
  const daysList = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
  const result = [];
  daysList.forEach((day) => {
    const ekskuls = ekskulList.value.filter((e) =>
      e.schedule.toLowerCase().includes(day.toLowerCase())
    );
    if (ekskuls.length > 0) {
      result.push({
        day,
        items: ekskuls.map((e) => {
          const timeMatch = e.schedule.match(/\d{2}\.\d{2}\s*-\s*\d{2}\.\d{2}/);
          return {
            name: e.name,
            time: timeMatch ? timeMatch[0] : e.schedule,
          };
        }),
      });
    }
  });
  return result;
});

const isModalOpen = ref(false);
const selectedEkskul = ref(null);

const openModal = (ekskul) => {
  selectedEkskul.value = ekskul;
  isModalOpen.value = true;
  document.body.style.overflow = "hidden";
};

const closeModal = () => {
  isModalOpen.value = false;
  document.body.style.overflow = "";
};

let observer;

onMounted(() => {
  fetchInitialData();

  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("opacity-100", "translate-y-0");
          entry.target.classList.remove("opacity-0", "translate-y-10");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 }
  );

  document.querySelectorAll(".fade-on-scroll").forEach((el) => {
    observer.observe(el);
  });
});

watch(paginatedEkskul, () => {
  if (observer) {
    document.querySelectorAll(".fade-on-scroll").forEach((el) => {
      if (el.classList.contains("opacity-0")) {
        observer.observe(el);
      }
    });
  }
});

onBeforeUnmount(() => {
  if (observer) observer.disconnect();
  document.body.style.overflow = "";
});
</script>

<template>
  <div>
    <PageHeader
      v-if="!isFetching && appearanceSettings"
      badge="Akademik"
      title="Ekstrakurikuler & Klub Siswa"
      description="Kembangkan bakat, minat, dan potensimu di luar jam pelajaran melalui berbagai pilihan kegiatan ekstrakurikuler yang seru dan inspiratif."
      :bgImage="appearanceSettings.headerEkskul"
    />

    <!-- Gallery Section -->
    <section
      class="relative pt-0 md:pt-6 pb-0 md:px-6 min-h-screen bg-gradient-to-b from-blue-50/50 to-white dark:from-slate-900 dark:to-slate-950 overflow-hidden"
    >
      <!-- Elemen Dekorasi Latar Belakang (Blob Cahaya) -->
      <div
        class="absolute top-0 right-0 w-96 h-96 bg-blue-400/10 dark:bg-blue-600/10 rounded-full blur-3xl -translate-y-1/4 translate-x-1/4 pointer-events-none"
      ></div>

      <div class="container relative z-10 mx-auto max-w-full">
        <div
          id="ekskul-list-container"
          class="bg-white dark:bg-slate-800 shadow-sm md:shadow-md border-y md:border border-gray-100 dark:border-slate-700 p-5 md:p-8 lg:p-10 flex flex-col lg:flex-row items-start gap-8 lg:gap-12 w-full lg:mt-8"
        >
          <!-- KIRI: Daftar Card Ekstrakurikuler -->
          <div class="flex-1 w-full order-2 lg:order-1">
            <!-- Judul Pembuka Daftar Ekskul -->
            <div
              class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-3 fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out"
            >
              <h2
                class="text-2xl sm:text-3xl font-bold text-blue-950 dark:text-white tracking-wide"
                style="font-family: 'Kalam', cursive"
              >
                Eksplorasi Klub
              </h2>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Temukan ekstrakurikuler yang tepat untuk mengembangkan potensimu.
              </p>
            </div>

            <TransitionGroup
              name="gallery"
              tag="div"
              class="grid grid-cols-1 sm:grid-cols-2 gap-10 sm:gap-8 lg:gap-10 relative w-full"
            >
              <template v-if="!isFetching && !isLoading">
                <div
                  v-for="ekskul in paginatedEkskul"
                  :key="ekskul.id"
                  class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out relative group cursor-pointer max-w-[340px] lg:max-w-[300px] xl:max-w-[340px] mx-auto w-full"
                  @click="openModal(ekskul)"
                >
                  <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 dark:from-slate-700 dark:to-slate-800 rounded-xl transform translate-x-3 translate-y-3 transition-all duration-500 lg:group-hover:translate-x-4 lg:group-hover:translate-y-4 z-0"
                  ></div>

                  <div
                    class="relative z-10 bg-slate-900 rounded-xl overflow-hidden shadow-lg lg:group-hover:shadow-2xl transition-all duration-500 aspect-[3/4] transform lg:group-hover:-translate-y-1 lg:group-hover:-translate-x-1 border border-gray-200 dark:border-slate-700 h-full w-full"
                  >
                    <img
                      :src="ekskul.image"
                      :alt="ekskul.name"
                      class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 lg:group-hover:scale-110 opacity-80 lg:opacity-95 lg:group-hover:opacity-100"
                    />

                    <div
                      class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 lg:from-slate-900/95 lg:via-slate-900/30 to-transparent transition-all duration-500 lg:group-hover:via-slate-900/70 lg:group-hover:from-slate-900"
                    ></div>

                    <div class="absolute top-5 right-5">
                      <div
                        class="px-3.5 py-1.5 backdrop-blur-md bg-white/20 border border-white/30 rounded-full flex items-center gap-1.5 text-white text-[12px] font-bold capitalize tracking-wide shadow-lg"
                        style="font-family: 'Kalam', cursive"
                      >
                        <component
                          :is="categories.find((c) => c.id === ekskul.category)?.icon"
                          class="w-3 h-3"
                        />
                        {{ categories.find((c) => c.id === ekskul.category)?.name }}
                      </div>
                    </div>

                    <div
                      class="absolute bottom-0 left-0 w-full p-6 md:p-8 transition-all duration-500 ease-out z-20 bg-slate-900/50 backdrop-blur-sm lg:bg-transparent lg:backdrop-blur-none lg:group-hover:bg-slate-900/60 lg:group-hover:backdrop-blur-md"
                    >
                      <h3
                        class="text-2xl font-extrabold text-white mb-2 leading-tight tracking-wide drop-shadow-md"
                      >
                        {{ ekskul.name }}
                      </h3>

                      <div
                        class="flex items-center text-blue-300 text-xs font-bold uppercase tracking-wider mb-2"
                      >
                        <PhClock class="w-3.5 h-3.5 mr-1.5" />
                        {{ ekskul.schedule }}
                      </div>

                      <div
                        class="max-h-[300px] opacity-100 lg:max-h-0 lg:opacity-0 overflow-hidden transition-all duration-500 ease-in-out lg:group-hover:max-h-[300px] lg:group-hover:opacity-100"
                      >
                        <div class="pt-4 mt-4 border-t border-white/20">
                          <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span
                              class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider text-blue-100 bg-blue-900/60 border border-blue-400/30 px-2.5 py-1 rounded backdrop-blur-md shadow-sm"
                            >
                              <PhUsers class="w-3 h-3 mr-1.5" />
                              {{ ekskul.members }} Anggota
                            </span>
                            <span
                              class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider text-emerald-100 bg-emerald-900/60 border border-emerald-400/30 px-2.5 py-1 rounded backdrop-blur-md shadow-sm"
                            >
                              <PhUser class="w-3 h-3 mr-1.5" />
                              {{
                                typeof ekskul.pembina === "string"
                                  ? ekskul.pembina.split(",")[0]
                                  : ekskul.pembina || "-"
                              }}
                            </span>
                          </div>

                          <p
                            class="text-gray-200 text-sm leading-relaxed mb-5 line-clamp-3"
                          >
                            {{ ekskul.desc }}
                          </p>

                          <button
                            class="inline-flex items-center text-xs font-bold text-white uppercase tracking-widest hover:text-blue-300 transition-colors focus:outline-none"
                          >
                            Jelajahi Klub
                            <PhArrowUpRight
                              class="w-4 h-4 ml-2 transform lg:group-hover:translate-x-1 lg:group-hover:-translate-y-1 transition-transform"
                            />
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </template>

              <template v-if="isLoading || isFetching">
                <div
                  v-for="n in skeletonCount"
                  :key="'skeleton-' + n"
                  class="relative transition-all duration-500 ease-out max-w-[340px] lg:max-w-[300px] xl:max-w-[340px] mx-auto w-full"
                >
                  <div
                    class="absolute inset-0 bg-gray-200 dark:bg-slate-700 rounded-xl transform translate-x-3 translate-y-3 z-0 animate-pulse"
                  ></div>
                  <div
                    class="relative z-10 bg-gray-100 dark:bg-slate-800 rounded-xl overflow-hidden shadow-lg aspect-[3/4] border border-gray-200 dark:border-slate-700 h-full w-full animate-pulse"
                  >
                    <div class="absolute top-5 right-5">
                      <div
                        class="w-24 h-6 bg-gray-300 dark:bg-slate-600 rounded-full"
                      ></div>
                    </div>
                    <div class="absolute bottom-0 left-0 w-full p-6 md:p-8">
                      <div
                        class="w-3/4 h-8 bg-gray-300 dark:bg-slate-600 rounded mb-3"
                      ></div>
                      <div class="w-1/2 h-4 bg-gray-300 dark:bg-slate-600 rounded"></div>
                    </div>
                  </div>
                </div>
              </template>
            </TransitionGroup>

            <!-- Pagination Ekstrakurikuler -->
            <div
              v-if="totalPages > 1 && !isFetching"
              class="flex justify-between items-center gap-2 mt-10 mb-6 sm:mb-0 relative z-10 w-full fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-300 ease-out"
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

            <!-- Empty State -->
            <div
              v-if="filteredEkskul.length === 0 && !isFetching && !isLoading"
              class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out col-span-full py-20 text-center bg-gray-50 dark:bg-slate-900/50 backdrop-blur-sm rounded-3xl border-2 border-dashed border-gray-200 dark:border-slate-700 shadow-sm mt-4"
            >
              <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white dark:bg-slate-800 mb-4 text-gray-400 shadow-sm"
              >
                <PhFileX class="w-8 h-8" />
              </div>
              <h3 class="text-lg font-bold text-blue-950 dark:text-white">
                Tidak Ditemukan
              </h3>
              <p class="text-gray-500 dark:text-gray-400 mt-1" v-if="searchQuery">
                Tidak ada ekstrakurikuler dengan nama "{{ searchQuery }}".
              </p>
              <p class="text-gray-500 dark:text-gray-400 mt-1" v-else>
                Belum ada data ekstrakurikuler yang didaftarkan untuk kategori ini.
              </p>
            </div>
          </div>

          <!-- KANAN: Sidebar Filter & Search -->
          <div class="w-full lg:w-1/3 shrink-0 order-1 lg:order-2">
            <div class="flex flex-col gap-6 lg:sticky lg:top-32">
              <!-- Search Bar & Dropdown -->
              <div class="flex flex-row gap-3 sm:gap-4 w-full">
                <!-- Search Bar -->
                <div class="relative flex-1">
                  <PhMagnifyingGlass
                    class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                  />
                  <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Cari nama ekstrakurikuler..."
                    class="w-full pl-12 pr-5 py-4 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-inner text-sm"
                  />
                </div>

                <!-- Dropdown Hari -->
                <div class="relative w-14 shrink-0" title="Filter Hari">
                  <!-- Toggle Button -->
                  <button
                    @click="isDayDropdownOpen = !isDayDropdownOpen"
                    class="w-full h-full py-4 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-inner flex items-center justify-center cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-800"
                  >
                    <PhCalendar
                      class="w-5 h-5 transition-colors"
                      :class="
                        activeDay !== 'semua'
                          ? 'text-blue-500 dark:text-blue-400'
                          : 'text-gray-400'
                      "
                    />
                  </button>

                  <!-- Indikator filter aktif -->
                  <div
                    v-if="activeDay !== 'semua'"
                    class="absolute top-3 right-3 w-2.5 h-2.5 bg-blue-500 rounded-full border-2 border-gray-50 dark:border-slate-900/50 pointer-events-none"
                  ></div>

                  <!-- Dropdown Card Menu -->
                  <Transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 translate-y-1 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="opacity-100 translate-y-0 scale-100"
                    leave-to-class="opacity-0 translate-y-1 scale-95"
                  >
                    <div
                      v-if="isDayDropdownOpen"
                      class="absolute top-full right-0 mt-3 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-100 dark:border-slate-700 p-2 z-50 origin-top-right"
                    >
                      <!-- Transparent Overlay for clicking outside -->
                      <div
                        class="fixed inset-0 z-[-1]"
                        @click="isDayDropdownOpen = false"
                      ></div>
                      <div class="flex flex-col gap-1 relative z-10">
                        <button
                          v-for="day in days"
                          :key="day.id"
                          @click="
                            activeDay = day.id;
                            isDayDropdownOpen = false;
                          "
                          class="w-full text-left px-4 py-2.5 rounded-lg text-sm font-bold transition-colors"
                          :class="
                            activeDay === day.id
                              ? 'bg-blue-50 text-blue-600 dark:bg-slate-700 dark:text-blue-400'
                              : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/50'
                          "
                        >
                          {{ day.name }}
                        </button>
                      </div>
                    </div>
                  </Transition>
                </div>
              </div>

              <!-- Filter Card (Kategori) -->
              <div
                class="w-full bg-gray-50 dark:bg-slate-900/50 p-5 lg:p-6 rounded-xl shadow-inner border border-gray-200 dark:border-slate-700 flex flex-col gap-4"
              >
                <h4
                  class="text-sm sm:text-md font-bold text-blue-950 dark:text-white flex items-center"
                >
                  <PhFunnel class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" />
                  Kategori Ekskul
                </h4>
                <div class="flex flex-wrap items-center gap-2 md:gap-2.5">
                  <button
                    v-for="cat in categories"
                    :key="cat.id"
                    @click="activeCategory = cat.id"
                    class="px-3.5 md:px-4 py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-300 focus:outline-none flex items-center border"
                    :class="
                      activeCategory === cat.id
                        ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/30'
                        : 'bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-slate-700 hover:border-blue-300 hover:text-blue-600 dark:hover:text-blue-400 shadow-sm'
                    "
                  >
                    <component
                      :is="cat.icon"
                      class="w-3.5 h-3.5 mr-1.5 hidden sm:block"
                    />
                    {{ cat.name }}
                    <span class="ml-1 text-[11px] font-bold opacity-70">
                      ({{ getCategoryCount(cat.id) }})
                    </span>
                  </button>
                </div>
              </div>

              <!-- Widget Jadwal Mingguan -->
              <div
                class="w-full bg-white dark:bg-slate-800 p-5 lg:p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col gap-4 mt-2 lg:mt-0"
              >
                <h4
                  class="text-lg font-bold text-blue-950 dark:text-white flex items-center border-b border-gray-100 dark:border-slate-700 pb-3 tracking-wide"
                  style="font-family: 'Kalam', cursive"
                >
                  <PhCalendar class="w-6 h-6 mr-2 text-blue-950 dark:text-white" />
                  Jadwal Kegiatan
                </h4>
                <div
                  class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar"
                >
                  <div
                    v-for="(daySchedule, index) in scheduleByDay"
                    :key="index"
                    class="relative pl-4 border-l-2 border-blue-100 dark:border-slate-700"
                  >
                    <div
                      class="absolute -left-[5px] top-0.5 w-2 h-2 rounded-full bg-blue-500"
                    ></div>
                    <h5
                      class="text-sm font-bold text-gray-900 dark:text-white mb-2 uppercase tracking-widest"
                    >
                      {{ daySchedule.day }}
                    </h5>
                    <div class="space-y-2">
                      <div
                        v-for="(item, idx) in daySchedule.items"
                        :key="idx"
                        class="bg-gray-50 dark:bg-slate-900/50 p-2.5 rounded-lg border border-gray-100 dark:border-slate-700"
                      >
                        <div class="flex justify-between items-start mb-1">
                          <span
                            class="text-sm font-bold text-blue-900 dark:text-blue-400"
                            >{{ item.name }}</span
                          >
                        </div>
                        <div
                          class="flex items-center text-xs text-gray-500 dark:text-gray-400"
                        >
                          <PhClock class="w-3.5 h-3.5 mr-1 text-gray-400" />
                          {{ item.time }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal Detail Ekskul -->
    <EkskulModal
      :is-open="isModalOpen"
      :ekskul="selectedEkskul"
      :categories="categories"
      @close="closeModal"
    />
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap");

.gallery-move,
.gallery-enter-active,
.gallery-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.gallery-enter-from,
.gallery-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(20px);
}

.gallery-leave-active {
  position: absolute;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
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
</style>
