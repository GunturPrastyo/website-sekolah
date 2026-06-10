<script setup>
import { ref, computed, onMounted, watch } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhChalkboard,
  PhX,
  PhUsers,
  PhUser,
  PhCaretDown,
  PhCheck,
} from "@phosphor-icons/vue";
import api from "@/api/index.js";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const classList = ref([]);
const staffList = ref([]);

const grades = ["X", "XI", "XII"];
const majors = ref([]);

const isLoadingData = ref(true);

const form = ref({
  id: null,
  name: "",
  grade: "X",
  program_id: "",
  homeroom_id: "",
  capacity: 36,
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");
const filterGrade = ref("semua");
const filterMajor = ref("semua");

// State untuk Custom Dropdown Wali Kelas
const isHomeroomDropdownOpen = ref(false);
const homeroomSearchQuery = ref("");
const homeroomPage = ref(1);
const homeroomItemsPerPage = 10;
const isHomeroomPageLoading = ref(false);
let loadingTimeout = null;

const filteredStaffList = computed(() => {
  if (!homeroomSearchQuery.value) return staffList.value;
  const query = homeroomSearchQuery.value.toLowerCase();
  return staffList.value.filter(
    (staff) =>
      staff.name.toLowerCase().includes(query) || staff.role.toLowerCase().includes(query)
  );
});

const homeroomTotalPages = computed(() => {
  return Math.ceil(filteredStaffList.value.length / homeroomItemsPerPage) || 1;
});

const displayedStaffList = computed(() => {
  const start = (homeroomPage.value - 1) * homeroomItemsPerPage;
  return filteredStaffList.value.slice(start, start + homeroomItemsPerPage);
});

const triggerHomeroomLoading = () => {
  isHomeroomPageLoading.value = true;
  if (loadingTimeout) clearTimeout(loadingTimeout);
  loadingTimeout = setTimeout(() => {
    isHomeroomPageLoading.value = false;
  }, 300);
};

watch(homeroomSearchQuery, () => {
  if (homeroomPage.value !== 1) {
    homeroomPage.value = 1;
  } else {
    triggerHomeroomLoading();
  }
});

watch(homeroomPage, () => {
  triggerHomeroomLoading();
});

const selectHomeroom = (id) => {
  form.value.homeroom_id = id;
  isHomeroomDropdownOpen.value = false;
  homeroomSearchQuery.value = "";
  homeroomPage.value = 1;
};

const fetchClasses = async () => {
  try {
    const response = await api.get("/api/school-classes");
    classList.value = response.data.data;
  } catch (error) {
    console.error("Gagal mengambil data kelas:", error);
    triggerToast("Gagal", "Gagal mengambil data kelas dari server.", "error");
  }
};

const fetchStaff = async () => {
  try {
    const response = await api.get("/api/staff");
    staffList.value = response.data.data;
  } catch (error) {
    console.error("Gagal mengambil data staf:", error);
  }
};

const fetchMajors = async () => {
  try {
    const response = await api.get("/api/programs");
    // Menyimpan keseluruhan data object jurusan untuk mengambil id-nya
    majors.value = response.data.data;
  } catch (error) {
    console.error("Gagal mengambil data jurusan:", error);
    triggerToast("Gagal", "Gagal mengambil data jurusan dari server.", "error");
  }
};

onMounted(async () => {
  isLoadingData.value = true;
  await Promise.all([fetchClasses(), fetchStaff(), fetchMajors()]);
  isLoadingData.value = false;
});

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

const resetForm = () => {
  form.value = {
    id: null,
    name: "",
    grade: "X",
    program_id: "",
    homeroom_id: "",
    capacity: 36,
  };
  isEditing.value = false;
};

const hideForm = () => {
  resetForm();
  isFormVisible.value = false;
  document.body.style.overflow = "";
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const addEntry = async () => {
  if (!form.value.name || !form.value.homeroom_id || !form.value.program_id) {
    triggerToast(
      "Gagal Menyimpan",
      "Nama Kelas, Jurusan, dan Wali Kelas wajib diisi!",
      "error"
    );
    return;
  }

  try {
    await api.post("/api/school-classes", form.value);
    await fetchClasses();
    hideForm();
    triggerToast("Berhasil Ditambahkan", "Data kelas baru berhasil ditambahkan.");
  } catch (error) {
    console.error(error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat menyimpan data.", "error");
  }
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = { ...item };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = async () => {
  if (!form.value.name || !form.value.homeroom_id || !form.value.program_id) {
    triggerToast(
      "Gagal Menyimpan",
      "Nama Kelas, Jurusan, dan Wali Kelas wajib diisi!",
      "error"
    );
    return;
  }

  try {
    await api.put(`/api/school-classes/${form.value.id}`, form.value);
    await fetchClasses();
    hideForm();
    triggerToast("Perubahan Disimpan", "Data kelas berhasil diperbarui.");
  } catch (error) {
    console.error(error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat memperbarui data.", "error");
  }
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = async () => {
  if (itemToDelete.value !== null) {
    try {
      await api.delete(`/api/school-classes/${itemToDelete.value}`);
      await fetchClasses();
      itemToDelete.value = null;
      triggerToast("Data Dihapus", "Data kelas berhasil dihapus dari sistem.", "info");
    } catch (error) {
      console.error(error);
      triggerToast("Gagal Menghapus", "Terjadi kesalahan saat menghapus data.", "error");
    }
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredClasses = computed(() => {
  let result = classList.value;
  if (filterGrade.value !== "semua") {
    result = result.filter((item) => item.grade === filterGrade.value);
  }
  if (filterMajor.value !== "semua") {
    result = result.filter((item) => item.program_id === filterMajor.value);
  }
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(
      (item) =>
        item.name.toLowerCase().includes(query) ||
        (item.homeroom && item.homeroom.name.toLowerCase().includes(query))
    );
  }
  return result;
});

const getSelectedHomeroomName = computed(() => {
  const staff = staffList.value.find((s) => s.id === form.value.homeroom_id);
  return staff ? `${staff.name} (${staff.role})` : "Pilih Wali Kelas";
});
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2
          class="text-3xl font-bold text-gray-800 dark:text-white"
          style="font-family: 'Oswald', sans-serif"
        >
          Data Kelas (Rombel)
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data rombongan belajar, wali kelas, dan kapasitas siswa.
        </p>
      </div>
      <button
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Kelas
      </button>
    </div>

    <!-- Banner Peringatan Kekurangan Data Relasi -->
    <div
      v-if="!isLoadingData && (majors.length === 0 || staffList.length === 0)"
      class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 rounded-r-lg shadow-sm"
    >
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
            <path
              fill-rule="evenodd"
              d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
              clip-rule="evenodd"
            />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
            Perhatian: Data Induk Belum Lengkap
          </h3>
          <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
            <p>
              Untuk dapat membuat data Kelas (Rombel), Anda perlu melengkapi data berikut
              terlebih dahulu:
            </p>
            <ul class="list-disc pl-5 mt-1 space-y-1">
              <li v-if="majors.length === 0">
                Data Jurusan masih kosong.
                <router-link
                  to="/admin/program-jurusan"
                  class="font-bold underline hover:text-yellow-600"
                  >Tambah Jurusan</router-link
                >
              </li>
              <li v-if="staffList.length === 0">
                Data Guru/Staf (Wali Kelas) masih kosong.
                <router-link
                  to="/admin/guru-staf"
                  class="font-bold underline hover:text-yellow-600"
                  >Tambah Guru/Staf</router-link
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Tambah/Edit -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isFormVisible"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="hideForm"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-2xl transform transition-all flex flex-col"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 rounded-t-xl"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
              {{ isEditing ? "Edit Data Kelas" : "Tambah Kelas Baru" }}
            </h3>
            <button
              @click="hideForm"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6 overflow-visible">
            <form id="classForm" @submit.prevent="isEditing ? saveEntry() : addEntry()">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2 relative z-[110]">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Wali Kelas</label
                  >
                  <!-- Custom Dropdown Wali Kelas -->
                  <button
                    type="button"
                    @click="
                      !isLoadingData && staffList.length > 0
                        ? (isHomeroomDropdownOpen = !isHomeroomDropdownOpen)
                        : null
                    "
                    :disabled="isLoadingData || staffList.length === 0"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-left focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex justify-between items-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="
                      form.homeroom_id
                        ? 'text-gray-900 dark:text-white'
                        : 'text-gray-500 dark:text-gray-400'
                    "
                  >
                    <span class="truncate">{{
                      isLoadingData
                        ? "Memuat Data..."
                        : staffList.length === 0
                        ? "Data Guru/Staf Kosong"
                        : getSelectedHomeroomName
                    }}</span>
                    <PhCaretDown
                      class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                      :class="{ 'rotate-180': isHomeroomDropdownOpen }"
                    />
                  </button>

                  <!-- Invisible Overlay to close dropdown -->
                  <div
                    v-if="isHomeroomDropdownOpen"
                    @click="isHomeroomDropdownOpen = false"
                    class="fixed inset-0 z-40"
                  ></div>

                  <!-- Dropdown Content (Arah ke bawah, Lazy Loading, Search) -->
                  <Transition
                    enter-active-class="transition ease-out duration-100"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition ease-in duration-100"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                  >
                    <div
                      v-if="isHomeroomDropdownOpen"
                      class="absolute top-full left-0 right-0 z-[120] mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-2xl overflow-hidden flex flex-col"
                    >
                      <!-- Search Bar Inside Dropdown -->
                      <div
                        class="p-2 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50"
                      >
                        <div class="relative">
                          <div
                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none"
                          >
                            <PhMagnifyingGlass class="w-4 h-4 text-gray-400" />
                          </div>
                          <input
                            type="text"
                            v-model="homeroomSearchQuery"
                            class="block w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm placeholder-gray-400"
                            placeholder="Cari nama atau peran..."
                          />
                        </div>
                      </div>

                      <!-- Paginated List -->
                      <ul
                        class="max-h-60 overflow-y-auto custom-scrollbar py-1 text-sm relative"
                      >
                        <!-- Loading State -->
                        <li
                          v-if="isHomeroomPageLoading"
                          class="px-4 py-8 flex flex-col items-center justify-center text-center"
                        >
                          <svg
                            class="animate-spin h-6 w-6 text-blue-500 mb-2"
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
                          <span class="text-xs text-gray-500 dark:text-gray-400"
                            >Memuat data...</span
                          >
                        </li>

                        <template v-else>
                          <li
                            v-if="displayedStaffList.length === 0"
                            class="px-4 py-3 text-gray-500 dark:text-gray-400 text-center"
                          >
                            Tidak ada wali kelas yang cocok.
                          </li>
                          <li
                            v-for="staff in displayedStaffList"
                            :key="staff.id"
                            @click="selectHomeroom(staff.id)"
                            class="px-4 py-2.5 cursor-pointer hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors flex justify-between items-center"
                            :class="
                              form.homeroom_id === staff.id
                                ? 'bg-blue-50/50 dark:bg-slate-700/50 font-medium text-blue-600 dark:text-blue-400'
                                : 'text-gray-700 dark:text-gray-300'
                            "
                          >
                            <div class="flex flex-col">
                              <span>{{ staff.name }}</span>
                              <span class="text-xs opacity-70">{{ staff.role }}</span>
                            </div>
                            <PhCheck
                              v-if="form.homeroom_id === staff.id"
                              class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0"
                            />
                          </li>
                        </template>
                      </ul>

                      <!-- Pagination Controls -->
                      <div
                        v-if="homeroomTotalPages > 1"
                        class="p-2 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-between items-center text-xs"
                      >
                        <button
                          type="button"
                          @click.stop="homeroomPage > 1 ? homeroomPage-- : null"
                          :disabled="homeroomPage === 1 || isHomeroomPageLoading"
                          class="px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded text-gray-700 dark:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
                        >
                          Sebelumnya
                        </button>
                        <span class="text-gray-500 dark:text-gray-400 font-medium">
                          Hal {{ homeroomPage }} dari {{ homeroomTotalPages }}
                        </span>
                        <button
                          type="button"
                          @click.stop="
                            homeroomPage < homeroomTotalPages ? homeroomPage++ : null
                          "
                          :disabled="
                            homeroomPage === homeroomTotalPages || isHomeroomPageLoading
                          "
                          class="px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded text-gray-700 dark:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
                        >
                          Selanjutnya
                        </button>
                      </div>
                    </div>
                  </Transition>

                  <p
                    v-if="!isLoadingData && staffList.length === 0"
                    class="mt-1 text-xs text-red-500"
                  >
                    * Anda harus
                    <router-link
                      to="/admin/guru-staf"
                      class="text-blue-600 dark:text-blue-400 hover:underline cursor-pointer"
                      @click="hideForm"
                      >menambah Guru/Staf</router-link
                    >
                    terlebih dahulu.
                  </p>
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Tingkat Kelas</label
                  >
                  <select
                    v-model="form.grade"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option v-for="grade in grades" :key="grade" :value="grade">
                      {{ grade }}
                    </option>
                  </select>
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Jurusan</label
                  >
                  <select
                    v-model="form.program_id"
                    :disabled="isLoadingData || majors.length === 0"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="" disabled>Pilih Jurusan</option>
                    <option v-if="isLoadingData" value="" disabled>
                      -- Memuat Data... --
                    </option>
                    <option v-else-if="majors.length === 0" value="" disabled>
                      -- Data Jurusan Kosong --
                    </option>
                    <option v-for="major in majors" :key="major.id" :value="major.id">
                      {{ major.title || major.name || major.program_name }}
                    </option>
                  </select>
                  <p
                    v-if="!isLoadingData && majors.length === 0"
                    class="mt-1 text-xs text-red-500"
                  >
                    * Anda harus
                    <router-link
                      to="/admin/program-jurusan"
                      class="text-blue-600 dark:text-blue-400 hover:underline cursor-pointer"
                      @click="hideForm"
                      >menambah Jurusan</router-link
                    >
                    terlebih dahulu.
                  </p>
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Nama Kelas (Rombel)</label
                  >
                  <input
                    type="text"
                    v-model="form.name"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: X MIPA 1"
                  />
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Kapasitas Siswa</label
                  >
                  <input
                    type="number"
                    v-model="form.capacity"
                    required
                    min="1"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="36"
                  />
                </div>
              </div>
            </form>
          </div>
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3 rounded-b-xl"
          >
            <button
              type="button"
              @click="hideForm"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhXCircle class="w-5 h-5 mr-2" /> Batal
            </button>
            <button
              form="classForm"
              type="submit"
              :disabled="isLoadingData || majors.length === 0 || staffList.length === 0"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
              <PhPlusCircle v-else class="w-5 h-5 mr-2" />
              {{ isEditing ? "Simpan Perubahan" : "Simpan Data" }}
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Data Table & Filters -->
    <div
      class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden"
    >
      <div
        class="p-6 border-b border-gray-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
      >
        <div class="flex flex-col md:flex-row gap-4 w-full">
          <div class="relative flex-1 md:max-w-xs">
            <div
              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
            >
              <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
            </div>
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Cari nama kelas atau wali kelas..."
              class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>
          <select
            v-model="filterGrade"
            class="block w-full md:w-auto md:min-w-[160px] px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Kelas</option>
            <option v-for="grade in grades" :key="grade" :value="grade">
              Kelas {{ grade }}
            </option>
          </select>
          <select
            v-model="filterMajor"
            class="block w-full md:w-auto md:min-w-[160px] px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Jurusan</option>
            <option v-for="major in majors" :key="major.id" :value="major.id">
              {{ major.title || major.name || major.program_name }}
            </option>
          </select>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr
              class="bg-gray-50 dark:bg-slate-700/50 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              <th class="px-6 py-4">Nama Kelas</th>
              <th class="px-6 py-4">Tingkat</th>
              <th class="px-6 py-4">Jurusan</th>
              <th class="px-6 py-4">Wali Kelas</th>
              <th class="px-6 py-4">Siswa / Kapasitas</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            <tr v-if="filteredClasses.length === 0">
              <td
                colspan="6"
                class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
              >
                <PhChalkboard
                  class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-500 mb-3"
                />
                <p>Tidak ada data kelas yang ditemukan.</p>
              </td>
            </tr>
            <tr
              v-for="cls in filteredClasses"
              :key="cls.id"
              class="hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors group"
            >
              <td class="px-6 py-4">
                <span
                  class="block text-sm font-semibold text-gray-800 dark:text-gray-200"
                  >{{ cls.name }}</span
                >
              </td>
              <td class="px-6 py-4">
                <span
                  class="inline-flex items-center justify-center w-8 h-8 rounded-md text-xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400"
                >
                  {{ cls.grade }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300"
                >
                  {{
                    cls.program
                      ? cls.program.title || cls.program.name
                      : "Belum Ditentukan"
                  }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                  <PhUser class="w-4 h-4 mr-1.5 text-gray-400" />
                  {{ cls.homeroom ? cls.homeroom.name : "Belum Ditentukan" }}
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                  <PhUsers class="w-4 h-4 mr-1.5 text-gray-400" />
                  {{ cls.currentStudents }} / {{ cls.capacity }}
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <div
                  class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <button
                    @click="startEdit(cls)"
                    class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors"
                    title="Edit Data"
                  >
                    <PhPencilSimple class="w-4 h-4" />
                  </button>
                  <button
                    @click="deleteEntry(cls.id)"
                    class="p-1.5 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-md transition-colors"
                    title="Hapus Data"
                  >
                    <PhTrash class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Data Kelas"
      message="Yakin ingin menghapus data kelas ini secara permanen dari sistem?"
      @confirm="confirmDelete"
      @cancel="cancelDelete"
    />
    <ToastNotification
      :isOpen="showToast"
      :title="toastData.title"
      :message="toastData.message"
      :type="toastData.type"
      @close="showToast = false"
    />
  </main>
</template>
