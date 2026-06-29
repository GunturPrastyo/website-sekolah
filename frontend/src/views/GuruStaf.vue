<script setup>
import { ref, computed, onMounted, watch, onBeforeUnmount } from "vue";
import PageHeader from "@/components/PageHeader.vue";
import api from "@/api/index.js";

const categories = computed(() => {
  const uniqueRoles = [...new Set(staffList.value.map((s) => s.role).filter(Boolean))];

  const dynamicCategories = uniqueRoles
    .map((role) => ({
      id: role,
      name: role,
    }))
    .sort((a, b) => a.name.localeCompare(b.name));

  return [{ id: "semua", name: "Struktur Organisasi" }, ...dynamicCategories];
});

const organizationCategories = computed(() => {
  const uniqueCategories = [
    ...new Set(staffList.value.map((s) => s.category).filter(Boolean)),
  ];

  const dynamicCategories = [];
  uniqueCategories.forEach((cat) => {
    let name = cat;
    const knownNames = {
      pimpinan: "Pimpinan Sekolah",
      pendidik: "Tenaga Pendidik",
      tenaga_kependidikan: "Tenaga Kependidikan",
    };

    if (knownNames[cat]) {
      name = knownNames[cat];
    } else {
      name = String(cat)
        .split(/_|-|\s/)
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
    }
    dynamicCategories.push({ id: cat, name });
  });

  const order = ["pimpinan", "pendidik", "tenaga_kependidikan"];
  dynamicCategories.sort((a, b) => {
    const indexA = order.indexOf(a.id);
    const indexB = order.indexOf(b.id);
    if (indexA !== -1 && indexB !== -1) return indexA - indexB;
    if (indexA !== -1) return -1;
    if (indexB !== -1) return 1;
    return a.name.localeCompare(b.name);
  });

  return dynamicCategories;
});

// Dropdown state
const isDropdownOpen = ref(false);
const dropdownRef = ref(null);
const dropdownItemsPerPage = 7;
const dropdownCurrentPage = ref(1);

const paginatedCategories = computed(() => {
  const start = (dropdownCurrentPage.value - 1) * dropdownItemsPerPage;
  const end = start + dropdownItemsPerPage;
  return categories.value.slice(start, end);
});

const totalDropdownPages = computed(() => {
  return Math.ceil(categories.value.length / dropdownItemsPerPage);
});

const selectCategory = (id) => {
  activeCategory.value = id;
  isDropdownOpen.value = false;
};

const changeDropdownPage = (direction) => {
  if (direction === "next" && dropdownCurrentPage.value < totalDropdownPages.value) {
    dropdownCurrentPage.value++;
  } else if (direction === "prev" && dropdownCurrentPage.value > 1) {
    dropdownCurrentPage.value--;
  }
};

const activeCategory = ref("semua");
const searchQuery = ref("");

const matchesSearch = (staff) => {
  if (!searchQuery.value) return true;
  return staff.name.toLowerCase().includes(searchQuery.value.toLowerCase());
};

const staffList = ref([]);
const appearanceSettings = ref({});

// Fitur Pagination & Loading
const itemsPerPage = 6;
const itemsToShow = ref(itemsPerPage);
const isFetching = ref(true);
const isLoadingMore = ref(false);

const getImageUrl = (path, name) => {
  if (!path)
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(
      name || "User"
    )}&background=0D8ABC&color=fff&size=256`;
  if (path.startsWith("http") || path.startsWith("data:")) return path;
  const backendUrl = api.defaults.baseURL;
  return `${backendUrl.replace(/\/+$/, "")}/${path.replace(/^\/+/, "")}`;
};

const fetchInitialData = async () => {
  isFetching.value = true;
  try {
    const [staffResponse, settingsResponse] = await Promise.all([
      api.get("/api/guru-staf"),
      api.get("/api/settings"), // Ambil setingan terkompresi dari VPS
    ]);

    // Set Data Guru & Staf
    if (staffResponse.data && staffResponse.data.data) {
      staffList.value = staffResponse.data.data.map((staff) => ({
        ...staff,
        image: getImageUrl(staff.image, staff.name),
      }));
    }

    // Set Data Global Settings untuk Gambar Banner
    if (settingsResponse.data?.success) {
      appearanceSettings.value = settingsResponse.data.data;
    }
  } catch (error) {
    console.error("Gagal memuat data awal halaman guru & staf:", error);
  } finally {
    isFetching.value = false;
  }
};

watch([activeCategory, searchQuery], () => {
  itemsToShow.value = itemsPerPage;
  dropdownCurrentPage.value = 1;
  // Data difilter di sisi klien, tidak perlu mengambil ulang.
  // Perilaku scroll asli dipindahkan ke sini untuk seluler.
  if (window.innerWidth < 768) {
    const contentEl = document.getElementById("staff-content");
    if (contentEl) {
      window.scrollTo({ top: contentEl.offsetTop - 100, behavior: "smooth" });
    }
  }
});

const loadMore = () => {
  isLoadingMore.value = true;
  itemsToShow.value += itemsPerPage;
  isLoadingMore.value = false;
};

// Base Computed properties
const baseAllStaffList = computed(() => staffList.value.filter((s) => matchesSearch(s)));

const baseFilteredStaff = computed(() => {
  if (activeCategory.value === "semua") return [];
  return staffList.value.filter(
    (s) => s.role === activeCategory.value && matchesSearch(s)
  );
});

const getCategoryCount = (categoryId) => {
  if (categoryId === "semua") return staffList.value.length;
  return staffList.value.filter((s) => s.role === categoryId).length;
};

// Paginated Computed properties
const allStaffList = computed(() => baseAllStaffList.value.slice(0, itemsToShow.value));
const filteredStaff = computed(() => baseFilteredStaff.value.slice(0, itemsToShow.value));

const getStaffByCategoryInSemua = (categoryId) => {
  return allStaffList.value.filter((s) => s.category === categoryId);
};

const hasMoreItems = computed(() => {
  if (activeCategory.value === "semua") {
    return allStaffList.value.length < baseAllStaffList.value.length;
  }
  return filteredStaff.value.length < baseFilteredStaff.value.length;
});

const activeCategoryName = computed(() => {
  return categories.value.find((c) => c.id === activeCategory.value)?.name || "";
});

const closeDropdownOnOutsideClick = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isDropdownOpen.value = false;
  }
};

onMounted(() => {
  fetchInitialData();
  document.addEventListener("click", closeDropdownOnOutsideClick);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", closeDropdownOnOutsideClick);
});
</script>

<template>
  <div>
    <PageHeader
      v-if="!isFetching && appearanceSettings"
      badge="Profil Sekolah"
      title="Guru & Staf Sekolah"
      description="Mengenal lebih dekat para pendidik dan tenaga kependidikan yang berperan penting dalam membimbing serta mendukung perkembangan siswa."
      :bgImage="appearanceSettings.headerGuruStaf"
    />

    <!-- Main Content -->
    <section class="pt-0 pb-0 py-4 sm:py-8 md:px-6 bg-gray-50 dark:bg-slate-900">
      <div class="container mx-auto max-w-8xl">
        <div
          class="bg-white dark:bg-slate-800 md:rounded-lg shadow-sm md:shadow-md border-y md:border border-gray-100 dark:border-slate-700 p-5 md:p-10 lg:p-12 flex flex-col w-full"
        >
          <!-- Content -->
          <main id="staff-content" class="w-full">
            <!-- Search Bar & Filter Group -->
            <div class="flex flex-col md:flex-row items-center gap-4 mb-12">
              <!-- Search Bar -->
              <div class="relative w-full flex-1 group">
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
                  class="block w-full pl-12 pr-4 py-3.5 bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-slate-700 rounded-lg text-sm shadow-inner focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all dark:text-white placeholder-gray-400 focus:outline-none"
                />
              </div>

              <!-- Custom Category Filter Dropdown -->
              <div class="relative w-full md:w-72 shrink-0" ref="dropdownRef">
                <!-- Dropdown Button -->
                <button
                  @click="isDropdownOpen = !isDropdownOpen"
                  class="relative block w-full pl-12 pr-10 py-3.5 bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-slate-700 rounded-lg text-sm shadow-inner focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all dark:text-white placeholder-gray-400 focus:outline-none cursor-pointer font-medium text-left"
                  style="font-family: 'Plus Jakarta Sans', sans-serif"
                >
                  <div
                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                  >
                    <svg
                      class="w-5 h-5 text-gray-400"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.5"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                      ></path>
                    </svg>
                  </div>
                  <span class="truncate block"
                    >{{ activeCategoryName }} ({{
                      getCategoryCount(activeCategory)
                    }})</span
                  >
                  <div
                    class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none"
                  >
                    <svg
                      class="w-5 h-5 text-gray-400 transition-transform duration-300"
                      :class="{ 'rotate-180': isDropdownOpen }"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.5"
                        d="M19 9l-7 7-7-7"
                      ></path>
                    </svg>
                  </div>
                </button>

                <!-- Dropdown Panel -->
                <Transition
                  enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <div
                    v-if="isDropdownOpen"
                    class="absolute top-full mt-2 w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg shadow-2xl z-20 overflow-hidden flex flex-col"
                  >
                    <div class="overflow-y-auto" style="max-height: 280px">
                      <button
                        v-for="cat in paginatedCategories"
                        :key="cat.id"
                        @click="selectCategory(cat.id)"
                        class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-gray-100 dark:hover:bg-slate-700/50 transition-colors flex justify-between items-center"
                        :class="{
                          'bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400':
                            activeCategory === cat.id,
                          'text-gray-700 dark:text-gray-300': activeCategory !== cat.id,
                        }"
                      >
                        <span class="truncate">{{ cat.name }}</span>
                        <span class="text-xs font-bold opacity-70 ml-2"
                          >({{ getCategoryCount(cat.id) }})</span
                        >
                      </button>
                    </div>
                    <!-- Pagination -->
                    <div
                      v-if="totalDropdownPages > 1"
                      class="flex items-center justify-between p-2 border-t border-gray-100 dark:border-slate-700/50 bg-gray-50/50 dark:bg-slate-900/20 shrink-0"
                    >
                      <button
                        @click="changeDropdownPage('prev')"
                        :disabled="dropdownCurrentPage === 1"
                        class="p-2 rounded-md text-gray-500 hover:bg-gray-200 dark:hover:bg-slate-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
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
                            d="M15 19l-7-7 7-7"
                          ></path>
                        </svg>
                      </button>
                      <span class="text-xs font-semibold text-gray-600 dark:text-gray-400"
                        >{{ dropdownCurrentPage }} / {{ totalDropdownPages }}</span
                      >
                      <button
                        @click="changeDropdownPage('next')"
                        :disabled="dropdownCurrentPage === totalDropdownPages"
                        class="p-2 rounded-md text-gray-500 hover:bg-gray-200 dark:hover:bg-slate-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
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
                            d="M9 5l7 7-7 7"
                          ></path>
                        </svg>
                      </button>
                    </div>
                  </div>
                </Transition>
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
                  class="bg-gray-50 dark:bg-slate-700/30 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden flex flex-col h-[320px] relative animate-pulse"
                >
                  <div class="h-24 bg-gray-200 dark:bg-slate-700 w-full"></div>
                  <div
                    class="px-6 pb-6 flex flex-col items-center text-center flex-1 relative z-10"
                  >
                    <div
                      class="w-28 h-28 rounded-full bg-gray-300 dark:bg-slate-600 -mt-14 mb-4 border-4 border-gray-50 dark:border-slate-800"
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
                  <template v-for="cat in organizationCategories" :key="cat.id">
                    <div
                      v-if="getStaffByCategoryInSemua(cat.id).length > 0"
                      class="mb-12"
                    >
                      <div class="flex items-center gap-4 mb-8">
                        <h2
                          class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white shrink-0"
                          style="font-family: 'Oswald', sans-serif"
                        >
                          {{ cat.name }}
                        </h2>
                        <div class="h-px bg-gray-200 dark:bg-slate-700 flex-1"></div>
                      </div>
                      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                          v-for="(staff, index) in getStaffByCategoryInSemua(cat.id)"
                          :key="staff.id"
                          class="group bg-gray-50 dark:bg-slate-700/30 rounded-lg shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-200 dark:border-slate-700 overflow-hidden flex flex-col h-full relative transform hover:-translate-y-2 animate-fade-in-up"
                          :style="{
                            animationDelay: `${index * 100}ms`,
                            animationFillMode: 'both',
                          }"
                        >
                          <div
                            class="h-24 relative overflow-hidden bg-blue-600 dark:bg-slate-700"
                          ></div>
                          <div
                            class="px-6 pb-6 flex flex-col items-center text-center flex-1 relative z-10"
                          >
                            <div class="relative -mt-14 mb-4">
                              <div
                                class="w-28 h-28 rounded-full p-1 bg-white dark:bg-slate-800 shadow-md group-hover:scale-110 group-hover:-translate-y-1 transition-all duration-500"
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
                                class="inline-flex items-center px-3 py-1 rounded-md bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[12px] font-bold tracking-wide"
                                style="font-family: 'Kalam', cursive"
                              >
                                {{ staff.role }}
                              </span>
                            </div>
                            <p
                              class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-5 flex items-center justify-center gap-1.5"
                            >
                              <svg
                                class="w-4 h-4 text-gray-800 dark:text-gray-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                              >
                                <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2.5"
                                  d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"
                                ></path>
                              </svg>
                              NIP. {{ staff.nip || "-" }}
                            </p>
                            <div
                              class="w-10 h-1 bg-gray-200 dark:bg-slate-700 rounded-full mb-5 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors mt-auto"
                            ></div>
                            <div class="flex gap-3">
                              <button
                                class="w-9 h-9 rounded-full bg-white hover:bg-red-50 dark:bg-slate-800 dark:hover:bg-red-900/50 flex items-center justify-center text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 transition-colors shadow-sm"
                                title="Email"
                              >
                                <svg
                                  class="w-4 h-4"
                                  fill="currentColor"
                                  viewBox="0 0 24 24"
                                >
                                  <path
                                    d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4.236l-8 4.882-8-4.882V6.095l8 4.882 8-4.882v2.141z"
                                  ></path>
                                </svg>
                              </button>
                              <button
                                @click.stop
                                class="w-9 h-9 rounded-full bg-white hover:bg-blue-50 dark:bg-slate-800 dark:hover:bg-blue-900/50 flex items-center justify-center text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors shadow-sm"
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
                  </template>

                  <!-- Jika Data Kosong di Kategori 'Semua' -->
                  <div
                    v-if="allStaffList.length === 0"
                    class="py-16 flex flex-col items-center justify-center text-center bg-gray-50 dark:bg-slate-900/50 rounded-lg border-2 border-dashed border-gray-200 dark:border-slate-700"
                  >
                    <div
                      class="w-16 h-16 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center text-gray-400 mb-4 shadow-sm"
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
                        style="font-family: 'Oswald', sans-serif"
                      >
                        {{ activeCategoryName }}
                      </h2>
                      <div class="h-px bg-gray-200 dark:bg-slate-700 flex-1"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                      <div
                        v-for="(staff, index) in filteredStaff"
                        :key="staff.id"
                        class="group bg-gray-50 dark:bg-slate-700/30 rounded-lg shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-200 dark:border-slate-700 overflow-hidden flex flex-col h-full relative transform hover:-translate-y-2 animate-fade-in-up"
                        :style="{
                          animationDelay: `${index * 100}ms`,
                          animationFillMode: 'both',
                        }"
                      >
                        <div
                          class="h-24 relative overflow-hidden bg-blue-600 dark:bg-slate-700"
                        ></div>
                        <div
                          class="px-6 pb-6 flex flex-col items-center text-center flex-1 relative z-10"
                        >
                          <div class="relative -mt-14 mb-4">
                            <div
                              class="w-28 h-28 rounded-full p-1 bg-white dark:bg-slate-800 shadow-md group-hover:scale-110 group-hover:-translate-y-1 transition-all duration-500"
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
                            class="text-xs font-bold text-gray-700 dark:text-gray-300 mb-5 flex items-center justify-center gap-1.5"
                          >
                            <svg
                              class="w-4 h-4 text-gray-800 dark:text-gray-200"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.5"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"
                              ></path>
                            </svg>
                            NIP. {{ staff.nip || "-" }}
                          </p>
                          <div
                            class="w-10 h-1 bg-gray-200 dark:bg-slate-700 rounded-full mb-5 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors mt-auto"
                          ></div>
                          <div class="flex gap-3">
                            <button
                              class="w-9 h-9 rounded-full bg-white hover:bg-red-50 dark:bg-slate-800 dark:hover:bg-red-900/50 flex items-center justify-center text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 transition-colors shadow-sm"
                              title="Email"
                            >
                              <svg
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                              >
                                <path
                                  d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4.236l-8 4.882-8-4.882V6.095l8 4.882 8-4.882v2.141z"
                                ></path>
                              </svg>
                            </button>
                            <button
                              @click.stop
                              class="w-9 h-9 rounded-full bg-white hover:bg-blue-50 dark:bg-slate-800 dark:hover:bg-blue-900/50 flex items-center justify-center text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors shadow-sm"
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
                      class="py-16 flex flex-col items-center justify-center text-center bg-gray-50 dark:bg-slate-900/50 rounded-lg border-2 border-dashed border-gray-200 dark:border-slate-700"
                    >
                      <div
                        class="w-16 h-16 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center text-gray-400 mb-4 shadow-sm"
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
@import url("https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap");

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
