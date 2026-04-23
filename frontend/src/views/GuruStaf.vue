<script setup>
import { ref, computed, onMounted, nextTick, watch } from "vue";
import { createIcons, icons } from "lucide";
import PageHeader from "@/components/PageHeader.vue";

const changeCategory = (id) => {
  activeCategory.value = id;
  // Otomatis scroll ke bagian konten jika di mobile (lebar layar < 768px)
  if (window.innerWidth < 768) {
    const contentEl = document.getElementById("staff-content");
    if (contentEl)
      window.scrollTo({ top: contentEl.offsetTop - 100, behavior: "smooth" });
  }
};

const categories = ref([
  { id: "semua", name: "Struktur Organisasi" },
  { id: "pimpinan", name: "Pimpinan Sekolah" },
  { id: "informatika", name: "Guru Informatika" },
  { id: "matematika", name: "Guru Matematika" },
  { id: "olahraga", name: "Guru Olahraga" },
  { id: "bahasa_inggris", name: "Guru Bahasa Inggris" },
  { id: "pustakawan", name: "Pustakawan" },
  { id: "tata_usaha", name: "Staff Tata Usaha" },
]);

const activeCategory = ref("semua");

const searchQuery = ref("");
const matchesSearch = (staff) => {
  if (!searchQuery.value) return true;
  return staff.name.toLowerCase().includes(searchQuery.value.toLowerCase());
};

// Contoh data dummy lengkap dengan atribut 'category'
const staffList = ref([
  {
    id: 1,
    name: "Dr. H. Budi Santoso, M.Pd",
    role: "Kepala Sekolah",
    category: "pimpinan",
    image:
      "https://ui-avatars.com/api/?name=Budi+Santoso&background=0D8ABC&color=fff&size=256",
    nip: "19750817 200003 1 004",
    bio:
      "Berpengalaman lebih dari 15 tahun dalam manajemen pendidikan dan memimpin sekolah menuju akreditasi A dengan program-program inovatif.",
  },
  {
    id: 2,
    name: "Siti Aminah, M.Pd",
    role: "Wakil Kepala Sekolah",
    category: "pimpinan",
    image:
      "https://ui-avatars.com/api/?name=Siti+Aminah&background=0D8ABC&color=fff&size=256",
  },
  {
    id: 3,
    name: "Ahmad Fauzi, S.Kom",
    role: "Guru Informatika",
    category: "informatika",
    image:
      "https://ui-avatars.com/api/?name=Ahmad+Fauzi&background=0D8ABC&color=fff&size=256",
  },
  {
    id: 4,
    name: "Rina Rahmawati, S.E",
    role: "Guru Matematika",
    category: "matematika",
    image:
      "https://ui-avatars.com/api/?name=Rina+Rahmawati&background=0D8ABC&color=fff&size=256",
  },
  {
    id: 5,
    name: "Dwi Saputra, S.Or",
    role: "Guru Olahraga",
    category: "olahraga",
    image:
      "https://ui-avatars.com/api/?name=Dwi+Saputra&background=0D8ABC&color=fff&size=256",
  },
  {
    id: 6,
    name: "Rudi Hermawan, S.Pd",
    role: "Guru Bahasa Inggris",
    category: "bahasa_inggris",
    image:
      "https://ui-avatars.com/api/?name=Rudi+Hermawan&background=0D8ABC&color=fff&size=256",
  },
  {
    id: 7,
    name: "Maya Indah, S.Pust",
    role: "Pustakawan",
    category: "pustakawan",
    image:
      "https://ui-avatars.com/api/?name=Maya+Indah&background=0D8ABC&color=fff&size=256",
  },
  {
    id: 8,
    name: "Joko Widodo",
    role: "Staff Tata Usaha",
    category: "tata_usaha",
    image:
      "https://ui-avatars.com/api/?name=Joko+Widodo&background=0D8ABC&color=fff&size=256",
  },
]);

// Fitur Pagination & Loading
const itemsPerPage = 6;
const itemsToShow = ref(itemsPerPage);
const isFetching = ref(true);
const isLoadingMore = ref(false);

const fetchData = () => {
  isFetching.value = true;
  setTimeout(() => {
    isFetching.value = false;
    nextTick(() => {
      createIcons({ icons });
    });
  }, 800); // Simulasi delay memuat data 800ms
};

watch([activeCategory, searchQuery], () => {
  itemsToShow.value = itemsPerPage;
  fetchData();
});

const loadMore = () => {
  isLoadingMore.value = true;
  setTimeout(() => {
    itemsToShow.value += itemsPerPage;
    isLoadingMore.value = false;
    nextTick(() => {
      createIcons({ icons });
    });
  }, 800);
};

// Base Computed properties
const basePimpinanList = computed(() =>
  staffList.value.filter((s) => s.category === "pimpinan" && matchesSearch(s))
);
const baseGuruList = computed(() =>
  staffList.value.filter(
    (s) =>
      ["informatika", "matematika", "olahraga", "bahasa_inggris"].includes(s.category) &&
      matchesSearch(s)
  )
);
const baseStafList = computed(() =>
  staffList.value.filter(
    (s) => ["pustakawan", "tata_usaha"].includes(s.category) && matchesSearch(s)
  )
);

const baseFilteredStaff = computed(() => {
  if (activeCategory.value === "semua") return []; // Jika "semua", kita tangani secara terpisah di template
  return staffList.value.filter(
    (s) => s.category === activeCategory.value && matchesSearch(s)
  );
});

const getCategoryCount = (categoryId) => {
  if (categoryId === "semua") return staffList.value.length;
  return staffList.value.filter((s) => s.category === categoryId).length;
};

// Paginated Computed properties
const pimpinanList = computed(() => basePimpinanList.value.slice(0, itemsToShow.value));
const guruList = computed(() => baseGuruList.value.slice(0, itemsToShow.value));
const stafList = computed(() => baseStafList.value.slice(0, itemsToShow.value));
const filteredStaff = computed(() => baseFilteredStaff.value.slice(0, itemsToShow.value));

const hasMoreItems = computed(() => {
  if (activeCategory.value === "semua") {
    return (
      pimpinanList.value.length < basePimpinanList.value.length ||
      guruList.value.length < baseGuruList.value.length ||
      stafList.value.length < baseStafList.value.length
    );
  }
  return filteredStaff.value.length < baseFilteredStaff.value.length;
});

const activeCategoryName = computed(() => {
  return categories.value.find((c) => c.id === activeCategory.value)?.name || "";
});

onMounted(() => {
  fetchData();
});
</script>

<template>
  <div>
    <PageHeader
      badge="Profil Sekolah"
      title="Guru & Staf Sekolah"
      description="Mengenal lebih dekat para pendidik dan tenaga kependidikan yang berperan penting dalam membimbing serta mendukung perkembangan siswa."
    />

    <!-- Main Content -->
    <section class="py-16 md:py-24 px-6 bg-gray-50 dark:bg-slate-900">
      <div class="container mx-auto max-w-7xl">
        <div class="flex flex-col w-full">
          <!-- Content -->
          <main id="staff-content" class="w-full">
            <!-- Search Bar & Filter Group -->
            <div class="flex flex-col gap-6 mb-12">
              <!-- Search Bar -->
              <div class="relative w-full lg:w-1/2 group">
                <div
                  class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                >
                  <svg
                    class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                    ></path>
                  </svg>
                </div>
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Cari nama guru atau staf..."
                  class="block w-full pl-12 pr-4 py-3.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all dark:text-white placeholder-gray-400 focus:outline-none"
                />
              </div>

              <!-- Category Filter Card -->
              <div
                class="w-full bg-white dark:bg-slate-800 p-5 lg:p-6 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col gap-4"
              >
                <h4
                  class="text-sm font-bold text-gray-900 dark:text-white flex items-center"
                >
                  <svg
                    class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                    ></path>
                  </svg>
                  Kategori:
                </h4>
                <div class="flex flex-wrap items-center gap-2 md:gap-2.5">
                  <button
                    v-for="category in categories"
                    :key="category.id"
                    @click="changeCategory(category.id)"
                    class="px-3.5 md:px-4 py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-300 focus:outline-none flex items-center border"
                    :class="
                      activeCategory === category.id
                        ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/30'
                        : 'bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-slate-600 hover:border-blue-300 hover:text-blue-600 dark:hover:text-blue-400'
                    "
                  >
                    {{ category.name }}
                    <span class="ml-1 text-[11px] font-bold opacity-70">
                      ({{ getCategoryCount(category.id) }})
                    </span>
                  </button>
                </div>
              </div>
            </div>

            <Transition
              mode="out-in"
              enter-active-class="transition-all duration-500 ease-out"
              enter-from-class="opacity-0 translate-y-8 sm:scale-95"
              enter-to-class="opacity-100 translate-y-0 sm:scale-100"
              leave-active-class="transition-all duration-200 ease-in"
              leave-from-class="opacity-100 translate-y-0 sm:scale-100"
              leave-to-class="opacity-0 -translate-y-4 sm:scale-95"
            >
              <!-- SKELETON LOADER -->
              <div
                v-if="isFetching"
                key="loader"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
              >
                <div
                  v-for="i in 6"
                  :key="`skel-${i}`"
                  class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col h-[320px] relative animate-pulse"
                >
                  <div class="h-24 bg-gray-200 dark:bg-slate-700 w-full"></div>
                  <div
                    class="px-6 pb-6 flex flex-col items-center text-center flex-1 relative z-10"
                  >
                    <div
                      class="w-24 h-24 rounded-full bg-gray-300 dark:bg-slate-600 -mt-12 mb-4 border-4 border-white dark:border-slate-800"
                    ></div>
                    <div
                      class="h-5 w-3/4 bg-gray-200 dark:bg-slate-700 rounded-full mb-3"
                    ></div>
                    <div
                      class="h-4 w-1/2 bg-gray-200 dark:bg-slate-700 rounded-full mb-6"
                    ></div>
                    <div
                      class="w-10 h-1 bg-gray-200 dark:bg-slate-700 rounded-full mb-6 mt-auto"
                    ></div>
                    <div class="flex gap-3">
                      <div
                        class="w-9 h-9 rounded-full bg-gray-200 dark:bg-slate-700"
                      ></div>
                      <div
                        class="w-9 h-9 rounded-full bg-gray-200 dark:bg-slate-700"
                      ></div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- REAL DATA -->
              <div v-else :key="activeCategory" class="space-y-10">
                <!-- Tampilan 'Semua' (Struktur Organisasi) -->
                <template v-if="activeCategory === 'semua'">
                  <!-- Pimpinan -->
                  <div v-if="pimpinanList.length > 0" class="mb-12">
                    <div class="flex items-center gap-4 mb-8">
                      <h2
                        class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white shrink-0"
                      >
                        Pimpinan Sekolah
                      </h2>
                      <div class="h-px bg-gray-200 dark:bg-slate-700 flex-1"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                      <div
                        v-for="(staff, index) in pimpinanList"
                        :key="staff.id"
                        class="group bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col h-full relative transform hover:-translate-y-2 animate-fade-in-up"
                        :style="{
                          animationDelay: `${index * 100}ms`,
                          animationFillMode: 'both',
                        }"
                      >
                        <div
                          class="h-24 relative overflow-hidden bg-gradient-to-r from-blue-700 to-blue-900 dark:from-slate-700 dark:to-slate-800"
                        >
                          <div
                            class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:12px_12px]"
                          ></div>
                        </div>
                        <div
                          class="px-6 pb-6 flex flex-col items-center text-center flex-1 relative z-10"
                        >
                          <div class="relative -mt-12 mb-4">
                            <div
                              class="w-24 h-24 rounded-full p-1 bg-white dark:bg-slate-800 shadow-md group-hover:scale-110 group-hover:-translate-y-1 transition-all duration-500"
                            >
                              <img
                                :src="staff.image"
                                class="w-full h-full object-cover rounded-full border-2 border-gray-100 dark:border-slate-700"
                              />
                            </div>
                          </div>
                          <h3
                            class="font-bold text-gray-900 dark:text-white text-lg mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-1"
                            :title="staff.name"
                          >
                            {{ staff.name }}
                          </h3>
                          <div class="mb-3">
                            <span
                              class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[11px] font-bold tracking-wide"
                            >
                              {{ staff.role }}
                            </span>
                          </div>
                          <p
                            class="text-xs text-gray-500 dark:text-gray-400 mb-5 flex items-center justify-center gap-1.5"
                          >
                            <svg
                              class="w-3.5 h-3.5 opacity-70"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"
                              ></path>
                            </svg>
                            NIP. {{ staff.nip || "-" }}
                          </p>
                          <div
                            class="w-10 h-1 bg-gray-100 dark:bg-slate-700 rounded-full mb-5 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors mt-auto"
                          ></div>
                          <div class="flex gap-3">
                            <button
                              class="w-9 h-9 rounded-full bg-gray-50 hover:bg-blue-50 dark:bg-slate-700 dark:hover:bg-blue-900/50 flex items-center justify-center text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors shadow-sm"
                              title="Email"
                            >
                              <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                              >
                                <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                ></path>
                              </svg>
                            </button>
                            <button
                              @click.stop
                              class="w-9 h-9 rounded-full bg-gray-50 hover:bg-blue-50 dark:bg-slate-700 dark:hover:bg-blue-900/50 flex items-center justify-center text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors shadow-sm"
                              title="LinkedIn"
                            >
                              <svg
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                              >
                                <path
                                  d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"
                                />
                              </svg>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Guru -->
                  <div v-if="guruList.length > 0" class="mb-12">
                    <div class="flex items-center gap-4 mb-8">
                      <h2
                        class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white shrink-0"
                      >
                        Tenaga Pendidik
                      </h2>
                      <div class="h-px bg-gray-200 dark:bg-slate-700 flex-1"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                      <div
                        v-for="(staff, index) in guruList"
                        :key="staff.id"
                        class="group bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col h-full relative transform hover:-translate-y-2 animate-fade-in-up"
                        :style="{
                          animationDelay: `${index * 100}ms`,
                          animationFillMode: 'both',
                        }"
                      >
                        <div
                          class="h-24 relative overflow-hidden bg-gradient-to-r from-blue-700 to-blue-900 dark:from-slate-700 dark:to-slate-800"
                        >
                          <div
                            class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:12px_12px]"
                          ></div>
                        </div>
                        <div
                          class="px-6 pb-6 flex flex-col items-center text-center flex-1 relative z-10"
                        >
                          <div class="relative -mt-12 mb-4">
                            <div
                              class="w-24 h-24 rounded-full p-1 bg-white dark:bg-slate-800 shadow-md group-hover:scale-110 group-hover:-translate-y-1 transition-all duration-500"
                            >
                              <img
                                :src="staff.image"
                                class="w-full h-full object-cover rounded-full border-2 border-gray-100 dark:border-slate-700"
                              />
                            </div>
                          </div>
                          <h3
                            class="font-bold text-gray-900 dark:text-white text-lg mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-1"
                            :title="staff.name"
                          >
                            {{ staff.name }}
                          </h3>
                          <div class="mb-3">
                            <span
                              class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[11px] font-bold tracking-wide"
                            >
                              {{ staff.role }}
                            </span>
                          </div>
                          <p
                            class="text-xs text-gray-500 dark:text-gray-400 mb-5 flex items-center justify-center gap-1.5"
                          >
                            <svg
                              class="w-3.5 h-3.5 opacity-70"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"
                              ></path>
                            </svg>
                            NIP. {{ staff.nip || "-" }}
                          </p>
                          <div
                            class="w-10 h-1 bg-gray-100 dark:bg-slate-700 rounded-full mb-5 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors mt-auto"
                          ></div>
                          <div class="flex gap-3">
                            <button
                              class="w-9 h-9 rounded-full bg-gray-50 hover:bg-blue-50 dark:bg-slate-700 dark:hover:bg-blue-900/50 flex items-center justify-center text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors shadow-sm"
                              title="Email"
                            >
                              <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                              >
                                <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                ></path>
                              </svg>
                            </button>
                            <button
                              @click.stop
                              class="w-9 h-9 rounded-full bg-gray-50 hover:bg-blue-50 dark:bg-slate-700 dark:hover:bg-blue-900/50 flex items-center justify-center text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors shadow-sm"
                              title="LinkedIn"
                            >
                              <svg
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                              >
                                <path
                                  d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"
                                />
                              </svg>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Staf -->
                  <div v-if="stafList.length > 0" class="mb-12">
                    <div class="flex items-center gap-4 mb-8">
                      <h2
                        class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white shrink-0"
                      >
                        Tenaga Kependidikan
                      </h2>
                      <div class="h-px bg-gray-200 dark:bg-slate-700 flex-1"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                      <div
                        v-for="(staff, index) in stafList"
                        :key="staff.id"
                        class="group bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col h-full relative transform hover:-translate-y-2 animate-fade-in-up"
                        :style="{
                          animationDelay: `${index * 100}ms`,
                          animationFillMode: 'both',
                        }"
                      >
                        <div
                          class="h-24 relative overflow-hidden bg-gradient-to-r from-blue-700 to-blue-900 dark:from-slate-700 dark:to-slate-800"
                        >
                          <div
                            class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:12px_12px]"
                          ></div>
                        </div>
                        <div
                          class="px-6 pb-6 flex flex-col items-center text-center flex-1 relative z-10"
                        >
                          <div class="relative -mt-12 mb-4">
                            <div
                              class="w-24 h-24 rounded-full p-1 bg-white dark:bg-slate-800 shadow-md group-hover:scale-110 group-hover:-translate-y-1 transition-all duration-500"
                            >
                              <img
                                :src="staff.image"
                                class="w-full h-full object-cover rounded-full border-2 border-gray-100 dark:border-slate-700"
                              />
                            </div>
                          </div>
                          <h3
                            class="font-bold text-gray-900 dark:text-white text-lg mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-1"
                            :title="staff.name"
                          >
                            {{ staff.name }}
                          </h3>
                          <div class="mb-3">
                            <span
                              class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[11px] font-bold tracking-wide"
                            >
                              {{ staff.role }}
                            </span>
                          </div>
                          <p
                            class="text-xs text-gray-500 dark:text-gray-400 mb-5 flex items-center justify-center gap-1.5"
                          >
                            <svg
                              class="w-3.5 h-3.5 opacity-70"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"
                              ></path>
                            </svg>
                            NIP. {{ staff.nip || "-" }}
                          </p>
                          <div
                            class="w-10 h-1 bg-gray-100 dark:bg-slate-700 rounded-full mb-5 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors mt-auto"
                          ></div>
                          <div class="flex gap-3">
                            <button
                              class="w-9 h-9 rounded-full bg-gray-50 hover:bg-blue-50 dark:bg-slate-700 dark:hover:bg-blue-900/50 flex items-center justify-center text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors shadow-sm"
                              title="Email"
                            >
                              <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                              >
                                <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                ></path>
                              </svg>
                            </button>
                            <button
                              @click.stop
                              class="w-9 h-9 rounded-full bg-gray-50 hover:bg-blue-50 dark:bg-slate-700 dark:hover:bg-blue-900/50 flex items-center justify-center text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors shadow-sm"
                              title="LinkedIn"
                            >
                              <svg
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                              >
                                <path
                                  d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"
                                />
                              </svg>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Jika Data Kosong di Kategori 'Semua' -->
                  <div
                    v-if="
                      pimpinanList.length === 0 &&
                      guruList.length === 0 &&
                      stafList.length === 0
                    "
                    class="py-16 flex flex-col items-center justify-center text-center bg-white dark:bg-slate-800 rounded-lg border border-dashed border-gray-200 dark:border-slate-700"
                  >
                    <div
                      class="w-16 h-16 rounded-full bg-gray-50 dark:bg-slate-700 flex items-center justify-center text-gray-400 mb-4"
                    >
                      <svg
                        class="w-8 h-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                        ></path>
                      </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                      Data tidak ditemukan
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1" v-if="searchQuery">
                      Tidak ada staf dengan nama "{{ searchQuery }}".
                    </p>
                    <p class="text-gray-500 dark:text-gray-400 mt-1" v-else>
                      Belum ada data anggota.
                    </p>
                  </div>
                </template>

                <!-- Tampilan Spesifik per Kategori (Selain 'Semua') -->
                <template v-else>
                  <div class="mb-12">
                    <div class="flex items-center gap-4 mb-8">
                      <h2
                        class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white shrink-0"
                      >
                        {{ activeCategoryName }}
                      </h2>
                      <div class="h-px bg-gray-200 dark:bg-slate-700 flex-1"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                      <div
                        v-for="(staff, index) in filteredStaff"
                        :key="staff.id"
                        class="group bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col h-full relative transform hover:-translate-y-2 animate-fade-in-up"
                        :style="{
                          animationDelay: `${index * 100}ms`,
                          animationFillMode: 'both',
                        }"
                      >
                        <div
                          class="h-24 relative overflow-hidden bg-gradient-to-r from-blue-700 to-blue-900 dark:from-slate-700 dark:to-slate-800"
                        >
                          <div
                            class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:12px_12px]"
                          ></div>
                        </div>
                        <div
                          class="px-6 pb-6 flex flex-col items-center text-center flex-1 relative z-10"
                        >
                          <div class="relative -mt-12 mb-4">
                            <div
                              class="w-24 h-24 rounded-full p-1 bg-white dark:bg-slate-800 shadow-md group-hover:scale-110 group-hover:-translate-y-1 transition-all duration-500"
                            >
                              <img
                                :src="staff.image"
                                class="w-full h-full object-cover rounded-full border-2 border-gray-100 dark:border-slate-700"
                              />
                            </div>
                          </div>
                          <h3
                            class="font-bold text-gray-900 dark:text-white text-lg mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-1"
                            :title="staff.name"
                          >
                            {{ staff.name }}
                          </h3>
                          <div class="mb-3">
                            <span
                              class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[11px] font-bold tracking-wide"
                            >
                              {{ staff.role }}
                            </span>
                          </div>
                          <p
                            class="text-xs text-gray-500 dark:text-gray-400 mb-5 flex items-center justify-center gap-1.5"
                          >
                            <svg
                              class="w-3.5 h-3.5 opacity-70"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"
                              ></path>
                            </svg>
                            NIP. {{ staff.nip || "-" }}
                          </p>
                          <div
                            class="w-10 h-1 bg-gray-100 dark:bg-slate-700 rounded-full mb-5 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors mt-auto"
                          ></div>
                          <div class="flex gap-3">
                            <button
                              class="w-9 h-9 rounded-full bg-gray-50 hover:bg-blue-50 dark:bg-slate-700 dark:hover:bg-blue-900/50 flex items-center justify-center text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors shadow-sm"
                              title="Email"
                            >
                              <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                              >
                                <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                ></path>
                              </svg>
                            </button>
                            <button
                              @click.stop
                              class="w-9 h-9 rounded-full bg-gray-50 hover:bg-blue-50 dark:bg-slate-700 dark:hover:bg-blue-900/50 flex items-center justify-center text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors shadow-sm"
                              title="LinkedIn"
                            >
                              <svg
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                              >
                                <path
                                  d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"
                                />
                              </svg>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Jika Data Kosong -->
                    <div
                      v-if="filteredStaff.length === 0"
                      class="py-16 flex flex-col items-center justify-center text-center bg-white dark:bg-slate-800 rounded-lg border border-dashed border-gray-200 dark:border-slate-700"
                    >
                      <div
                        class="w-16 h-16 rounded-full bg-gray-50 dark:bg-slate-700 flex items-center justify-center text-gray-400 mb-4"
                      >
                        <svg
                          class="w-8 h-8"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                          ></path>
                        </svg>
                      </div>
                      <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Data tidak ditemukan
                      </h3>
                      <p class="text-gray-500 dark:text-gray-400 mt-1" v-if="searchQuery">
                        Tidak ada staf dengan nama "{{ searchQuery }}" di kategori ini.
                      </p>
                      <p class="text-gray-500 dark:text-gray-400 mt-1" v-else>
                        Belum ada data anggota untuk kategori ini.
                      </p>
                    </div>
                  </div>
                </template>

                <!-- Load More Button -->
                <div
                  v-if="hasMoreItems"
                  class="flex justify-center items-center mt-12 pb-8 w-full"
                >
                  <div
                    class="h-px bg-gray-200 dark:bg-slate-700 flex-grow max-w-[100px] md:max-w-[150px]"
                  ></div>
                  <button
                    @click="loadMore"
                    :disabled="isLoadingMore"
                    class="mx-5 inline-flex items-center text-sm md:text-base font-semibold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors group focus:outline-none disabled:opacity-70 disabled:cursor-not-allowed"
                  >
                    <span v-if="!isLoadingMore" class="flex items-center">
                      Muat Lebih Banyak
                      <svg
                        class="w-4 h-4 ml-1.5 transform group-hover:translate-y-1 transition-transform"
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
                    </span>
                    <span v-else class="flex items-center">
                      <svg
                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600 dark:text-blue-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                      >
                        <circle
                          class="opacity-25"
                          cx="12"
                          cy="12"
                          r="10"
                          stroke="currentColor"
                          stroke-width="4"
                        ></circle>
                        <path
                          class="opacity-75"
                          fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                      </svg>
                      Memuat...
                    </span>
                  </button>
                  <div
                    class="h-px bg-gray-200 dark:bg-slate-700 flex-grow max-w-[100px] md:max-w-[150px]"
                  ></div>
                </div>
              </div>
            </Transition>
          </main>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-fade-in-up {
  animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}
</style>
