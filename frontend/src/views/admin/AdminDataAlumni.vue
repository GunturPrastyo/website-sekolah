<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhGraduationCap,
  PhX,
  PhMapPin,
  PhMapTrifold,
  PhCaretDown,
  PhCheck,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";
import ImageUploader from "@/components/admin/ImageUploader.vue";
import api from "@/api/index.js";

const statusAlumniList = ["Kuliah", "Bekerja"];

const alumniList = ref([]);
const unassignedAlumni = ref([]);
const unassignedStudentsPage = ref(1);
const unassignedStudentsLastPage = ref(1);
const isLoadingUnassigned = ref(false);

const fetchAlumnis = async () => {
  try {
    const response = await api.get("/api/alumnis");
    alumniList.value = response.data.data;
    mergeInstitutions();
  } catch (error) {
    console.error("Gagal memuat data alumni", error);
    triggerToast(
      "Gagal Memuat",
      "Tidak dapat mengambil data alumni dari server.",
      "error"
    );
  }
};

const fetchUnassignedStudents = async (isLoadMore = false) => {
  if (isLoadingUnassigned.value) return;
  if (!isLoadMore) unassignedStudentsPage.value = 1;
  isLoadingUnassigned.value = true;
  try {
    const response = await api.get("/api/alumnis/unassigned-students", {
      params: {
        page: unassignedStudentsPage.value,
        per_page: 10,
        search: searchStudent.value,
      },
    });
    if (isLoadMore) {
      unassignedAlumni.value = [...unassignedAlumni.value, ...response.data.data];
    } else {
      unassignedAlumni.value = response.data.data;
    }
    unassignedStudentsLastPage.value = response.data.pagination.last_page;
  } catch (error) {
    console.error("Gagal mengambil data siswa unassigned:", error);
  } finally {
    isLoadingUnassigned.value = false;
  }
};

const fetchMapLocations = async () => {
  try {
    const response = await api.get("/api/map-locations");
    mapLocations.value = response.data.data;
  } catch (error) {
    console.error("Gagal memuat data peta:", error);
  }
};

onMounted(() => {
  fetchAlumnis();
  fetchUnassignedStudents();
  fetchMapLocations();
});

// State dan Logic untuk Dropdown CRUD Instansi
const institutionList = ref([]);

const mergeInstitutions = () => {
  const unique = new Set(institutionList.value);
  alumniList.value.forEach((a) => {
    if (a.instansi && a.instansi.trim()) {
      unique.add(a.instansi.trim());
    }
  });
  institutionList.value = Array.from(unique).sort();
};

const showNewInstansiInput = ref(false);
const newInstansiName = ref("");
const isInstansiDropdownOpen = ref(false);
const editingInstansiIndex = ref(null);
const editingInstansiName = ref("");

const selectInstansi = (name) => {
  if (name === "ADD_NEW") {
    showNewInstansiInput.value = true;
    form.value.instansi = "";
  } else {
    form.value.instansi = name;
  }
  isInstansiDropdownOpen.value = false;
};

const addNewInstansi = () => {
  const name = newInstansiName.value.trim();
  if (name) {
    if (!institutionList.value.includes(name)) {
      institutionList.value.push(name);
      institutionList.value.sort();
    }
    form.value.instansi = name;
    showNewInstansiInput.value = false;
    newInstansiName.value = "";
  } else {
    triggerToast("Gagal", "Nama instansi tidak boleh kosong!", "error");
  }
};

const cancelNewInstansi = () => {
  showNewInstansiInput.value = false;
  newInstansiName.value = "";
  form.value.instansi = "";
};

const startEditInstansi = (index, name) => {
  editingInstansiIndex.value = index;
  editingInstansiName.value = name;
};

const saveEditInstansi = (index) => {
  const newName = editingInstansiName.value.trim();
  if (newName) {
    const oldName = institutionList.value[index];
    alumniList.value.forEach((a) => {
      if (a.instansi === oldName) {
        a.instansi = newName;
      }
    });
    if (form.value.instansi === oldName) form.value.instansi = newName;
    institutionList.value[index] = newName;
    institutionList.value.sort();
    editingInstansiIndex.value = null;
    editingInstansiName.value = "";
    triggerToast("Berhasil", "Nama instansi berhasil diperbarui.", "success");
  } else {
    triggerToast("Gagal", "Nama instansi tidak boleh kosong!", "error");
  }
};

const cancelEditInstansi = () => {
  editingInstansiIndex.value = null;
  editingInstansiName.value = "";
};

const handleDeleteInstansi = (index) => {
  const instToDelete = institutionList.value[index];
  const isInUse = alumniList.value.some((a) => a.instansi === instToDelete);

  if (isInUse) {
    triggerToast(
      "Gagal",
      `Instansi "${instToDelete}" sedang digunakan oleh alumni!`,
      "error"
    );
    return;
  }

  if (window.confirm(`Apakah Anda yakin ingin menghapus instansi '${instToDelete}'?`)) {
    institutionList.value.splice(index, 1);
    if (form.value.instansi === instToDelete) form.value.instansi = "";
    triggerToast("Dihapus", `Instansi "${instToDelete}" berhasil dihapus.`, "info");
  }
  isInstansiDropdownOpen.value = false;
};

const form = ref({
  id: null,
  student_id: null,
  nisn: "",
  name: "",
  year: new Date().getFullYear().toString(),
  status: "Kuliah",
  instansi: "",
});

// Data Peta Persebaran (Untuk Home View)
const mapLocations = ref([]);

const isMapModalOpen = ref(false);
const isMapEditing = ref(false);
const mapForm = ref({
  id: null,
  name: "",
  type: "mixed",
  totalAlumni: 0,
  top: "50%",
  left: "50%",
  institutions: [],
});

const mapInstDropdownOpen = ref(null);

// Hitung daftar instansi unik yang ada di daftar alumni beserta jumlah alumninya
const availableInstitutions = computed(() => {
  const counts = {};
  alumniList.value.forEach((a) => {
    const inst = a.instansi?.trim();
    if (inst) {
      counts[inst] = (counts[inst] || 0) + 1;
    }
  });
  return Object.keys(counts)
    .map((name) => ({ name, count: counts[name] }))
    .sort((a, b) => a.name.localeCompare(b.name));
});

const autoFillInstansi = (inst) => {
  const found = availableInstitutions.value.find((i) => i.name === inst.name);
  if (found) {
    inst.alumni = found.count;
  } else {
    inst.alumni = 0;
  }

  // Secara otomatis mengisi jenis instansi dan logo jika sebelumnya pernah diatur di titik peta lain
  for (const loc of mapLocations.value) {
    const existingInst = loc.institutions.find((i) => i.name === inst.name);
    if (existingInst) {
      inst.type = existingInst.type || "ptn";
      inst.logo = existingInst.logo || "";
      break;
    }
  }
};

const mapContainerRef = ref(null);
const instansiListContainer = ref(null);

const handleMapClick = (e) => {
  if (!mapContainerRef.value) return;
  const rect = mapContainerRef.value.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;
  const leftPercent = ((x / rect.width) * 100).toFixed(2);
  const topPercent = ((y / rect.height) * 100).toFixed(2);
  mapForm.value.left = leftPercent + "%";
  mapForm.value.top = topPercent + "%";
};

const openAddMap = () => {
  mapForm.value = {
    id: null,
    name: "",
    type: "mixed",
    totalAlumni: 0,
    top: "50%",
    left: "50%",
    institutions: [],
  };
  isMapEditing.value = false;
  mapInstDropdownOpen.value = null;
  isMapModalOpen.value = true;
  document.body.style.overflow = "hidden";
};

const openEditMap = (loc) => {
  mapForm.value = JSON.parse(JSON.stringify(loc));
  isMapEditing.value = true;
  mapInstDropdownOpen.value = null;
  isMapModalOpen.value = true;
  document.body.style.overflow = "hidden";
};

const saveMapLocation = async () => {
  if (!mapForm.value.name) {
    triggerToast("Gagal", "Nama wilayah harus diisi!", "error");
    return;
  }

  try {
    if (isMapEditing.value) {
      const response = await api.put(
        `/api/map-locations/${mapForm.value.id}`,
        mapForm.value
      );
      const idx = mapLocations.value.findIndex((l) => l.id === mapForm.value.id);
      if (idx !== -1) mapLocations.value[idx] = response.data.data;
    } else {
      const response = await api.post("/api/map-locations", mapForm.value);
      mapLocations.value.push(response.data.data);
    }
    isMapModalOpen.value = false;
    document.body.style.overflow = "";
    triggerToast("Disimpan", "Data persebaran alumni berhasil disimpan.");
  } catch (error) {
    console.error(error);
    triggerToast("Gagal", error.response?.data?.message || "Terjadi kesalahan.", "error");
  }
};

const deleteMapLocation = async (id) => {
  if (confirm("Hapus lokasi ini dari peta?")) {
    try {
      await api.delete(`/api/map-locations/${id}`);
      mapLocations.value = mapLocations.value.filter((l) => l.id !== id);
      triggerToast("Dihapus", "Lokasi berhasil dihapus.", "info");
    } catch (error) {
      console.error(error);
      triggerToast("Gagal", "Terjadi kesalahan saat menghapus data.", "error");
    }
  }
};

const addInstitution = () => {
  mapForm.value.institutions.push({ name: "", type: "ptn", alumni: 0, logo: "" });
  nextTick(() => {
    if (instansiListContainer.value) {
      instansiListContainer.value.scrollTop = instansiListContainer.value.scrollHeight;
    }
  });
};

const removeInstitution = (idx) => {
  if (mapInstDropdownOpen.value === idx) {
    mapInstDropdownOpen.value = null;
  }
  mapForm.value.institutions.splice(idx, 1);
};

const handleLogoUpload = (e, inst) => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (event) => {
    inst.logo = event.target.result;
  };
  reader.readAsDataURL(file);
};

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");
const filterStatus = ref("semua");
const searchStudent = ref("");
const isDropdownOpen = ref(false);
const selectedStudentsForAdd = ref([]);

const filteredUnassignedAlumni = computed(() => {
  let list = unassignedAlumni.value;
  if (!isEditing.value && selectedStudentsForAdd.value.length > 0) {
    const selectedIds = selectedStudentsForAdd.value.map((s) => s.id);
    list = list.filter((s) => !selectedIds.includes(s.id));
  }
  return list;
});

let searchTimeout;
const onSearchInput = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchUnassignedStudents(false);
  }, 500);
};

const onDropdownScroll = (event) => {
  const { scrollTop, scrollHeight, clientHeight } = event.target;
  if (scrollTop + clientHeight >= scrollHeight - 10) {
    if (unassignedStudentsPage.value < unassignedStudentsLastPage.value) {
      unassignedStudentsPage.value++;
      fetchUnassignedStudents(true);
    }
  }
};

const selectStudent = (siswa) => {
  if (!isEditing.value) {
    if (!selectedStudentsForAdd.value.find((s) => s.id === siswa.id)) {
      selectedStudentsForAdd.value.push(siswa);
      if (siswa.year && !form.value.year) form.value.year = siswa.year;
    }
    searchStudent.value = "";
    fetchUnassignedStudents(false);
  } else {
    form.value.student_id = siswa.id;
    form.value.nisn = siswa.nisn;
    form.value.name = siswa.name;
    if (siswa.year) form.value.year = siswa.year;
    searchStudent.value = `${siswa.nisn} - ${siswa.name}`;
    isDropdownOpen.value = false;
  }
};

const removeSelectedStudent = (index) => {
  selectedStudentsForAdd.value.splice(index, 1);
};

const closeDropdown = () => {
  setTimeout(() => {
    isDropdownOpen.value = false;
  }, 200);
};

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
    student_id: null,
    nisn: "",
    name: "",
    year: new Date().getFullYear().toString(),
    status: "Kuliah",
    instansi: "",
  };
  searchStudent.value = "";
  fetchUnassignedStudents(false);
  selectedStudentsForAdd.value = [];
  isDropdownOpen.value = false;
  isEditing.value = false;
  showNewInstansiInput.value = false;
  newInstansiName.value = "";
  isInstansiDropdownOpen.value = false;
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
  if (selectedStudentsForAdd.value.length === 0 || !form.value.year) {
    triggerToast(
      "Gagal Menyimpan",
      "Pilih minimal satu siswa dan isi Tahun Lulus!",
      "error"
    );
    return;
  }

  try {
    let addedCount = 0;
    for (const siswa of selectedStudentsForAdd.value) {
      const payload = {
        student_id: siswa.id,
        year: form.value.year,
        status: form.value.status,
        instansi: form.value.instansi,
      };
      const response = await api.post("/api/alumnis", payload);
      alumniList.value.unshift(response.data.data);
      addedCount++;
    }

    hideForm();
    fetchUnassignedStudents();
    triggerToast(
      "Berhasil Ditambahkan",
      `${addedCount} data alumni baru berhasil ditambahkan.`
    );
  } catch (error) {
    console.error(error);
    triggerToast(
      "Gagal Menambahkan",
      error.response?.data?.message || "Terjadi kesalahan",
      "error"
    );
  }
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = { ...item };
  searchStudent.value = `${item.nisn} - ${item.name}`;
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = async () => {
  if (!form.value.year) {
    triggerToast("Gagal Menyimpan", "Tahun Lulus wajib diisi!", "error");
    return;
  }

  try {
    const response = await api.put(`/api/alumnis/${form.value.id}`, form.value);
    const index = alumniList.value.findIndex((s) => s.id === form.value.id);
    if (index !== -1) {
      alumniList.value[index] = response.data.data;
    }
    hideForm();
    triggerToast("Perubahan Disimpan", "Data alumni berhasil diperbarui.");
  } catch (error) {
    console.error(error);
    triggerToast(
      "Gagal Memperbarui",
      error.response?.data?.message || "Terjadi kesalahan",
      "error"
    );
  }
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = async () => {
  if (itemToDelete.value !== null) {
    try {
      await api.delete(`/api/alumnis/${itemToDelete.value}`);
      alumniList.value = alumniList.value.filter((s) => s.id !== itemToDelete.value);
      itemToDelete.value = null;
      fetchUnassignedStudents();
      triggerToast("Data Dihapus", "Data alumni berhasil dihapus dari sistem.", "info");
    } catch (error) {
      console.error(error);
      triggerToast(
        "Gagal Menghapus",
        error.response?.data?.message || "Terjadi kesalahan",
        "error"
      );
    }
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredAlumni = computed(() => {
  let result = alumniList.value;

  if (filterStatus.value !== "semua") {
    result = result.filter((item) => item.status === filterStatus.value);
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(
      (item) =>
        item.name.toLowerCase().includes(query) || item.nisn.toLowerCase().includes(query)
    );
  }
  return result;
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
          Data Tracking Alumni
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data pelacakan alumni, status karir, maupun kelanjutan studi (Tracer
          Study).
        </p>
      </div>
    </div>

    <!-- Form Tambah/Edit Manual -->
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
        class="fixed inset-0 z-[100] overflow-y-auto bg-black/50 backdrop-blur-sm"
        @click="hideForm"
      >
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
          <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-2xl flex flex-col transform transition-all relative"
            @click.stop
          >
            <div
              class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 rounded-t-xl"
            >
              <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                {{ isEditing ? "Edit Data Alumni" : "Tambah Alumni Baru" }}
              </h3>
              <button
                @click="hideForm"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
              >
                <PhX class="w-6 h-6" />
              </button>
            </div>
            <div class="p-6 flex-1">
              <form
                id="alumniForm"
                @submit.prevent="isEditing ? saveEntry() : addEntry()"
              >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div v-if="!isEditing" class="md:col-span-2 relative">
                    <div class="flex justify-between items-end mb-1">
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                      >
                        Pilih Siswa (Bisa lebih dari satu)
                        <span
                          v-if="selectedStudentsForAdd.length > 0"
                          class="ml-1 text-blue-600 dark:text-blue-400 font-bold"
                          >({{ selectedStudentsForAdd.length }} dipilih)</span
                        >
                      </label>
                      <button
                        v-if="selectedStudentsForAdd.length > 1"
                        type="button"
                        @click="selectedStudentsForAdd = []"
                        class="text-xs text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium transition-colors"
                      >
                        Kosongkan Pilihan
                      </button>
                    </div>
                    <div
                      class="flex flex-wrap gap-2 mb-2 max-h-32 overflow-y-auto custom-scrollbar p-1 rounded-lg"
                      :class="
                        selectedStudentsForAdd.length > 6
                          ? 'border border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50'
                          : ''
                      "
                      v-if="selectedStudentsForAdd.length > 0"
                    >
                      <span
                        v-for="(siswa, index) in selectedStudentsForAdd"
                        :key="siswa.id"
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 border border-blue-200 dark:border-blue-800"
                      >
                        {{ siswa.nisn }} - {{ siswa.name }}
                        <button
                          type="button"
                          @click="removeSelectedStudent(index)"
                          class="flex-shrink-0 ml-1.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-blue-400 hover:bg-blue-200 hover:text-blue-600 focus:outline-none focus:bg-blue-500 focus:text-white dark:hover:bg-blue-800 dark:hover:text-blue-200"
                        >
                          <PhX class="h-3 w-3" />
                        </button>
                      </span>
                    </div>
                    <input
                      type="text"
                      v-model="searchStudent"
                      @input="onSearchInput"
                      @focus="isDropdownOpen = true"
                      @blur="closeDropdown"
                      placeholder="Ketik NISN atau Nama Siswa lalu klik untuk memilih..."
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    />
                    <div
                      v-if="isDropdownOpen"
                      @scroll="onDropdownScroll"
                      class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg max-h-48 overflow-y-auto"
                    >
                      <div
                        v-if="
                          filteredUnassignedAlumni.length === 0 && !isLoadingUnassigned
                        "
                        class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400"
                      >
                        Siswa tidak ditemukan
                      </div>
                      <div
                        v-for="siswa in filteredUnassignedAlumni"
                        :key="siswa.nisn"
                        @mousedown.prevent="selectStudent(siswa)"
                        class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-50 dark:hover:bg-slate-700 text-gray-800 dark:text-gray-200"
                      >
                        {{ siswa.nisn }} - {{ siswa.name }}
                      </div>
                      <div
                        v-if="isLoadingUnassigned"
                        class="px-4 py-2 text-sm text-center text-blue-500 dark:text-blue-400"
                      >
                        Memuat data...
                      </div>
                    </div>
                  </div>

                  <div v-if="isEditing" class="md:col-span-2 relative">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                      Cari Siswa
                    </label>
                    <input
                      type="text"
                      v-model="searchStudent"
                      @input="onSearchInput"
                      @focus="isDropdownOpen = true"
                      @blur="closeDropdown"
                      placeholder="Ketik NISN atau Nama Siswa..."
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    />
                    <div
                      v-if="isDropdownOpen"
                      @scroll="onDropdownScroll"
                      class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg max-h-48 overflow-y-auto"
                    >
                      <div
                        v-if="
                          filteredUnassignedAlumni.length === 0 && !isLoadingUnassigned
                        "
                        class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400"
                      >
                        Siswa tidak ditemukan
                      </div>
                      <div
                        v-for="siswa in filteredUnassignedAlumni"
                        :key="siswa.nisn"
                        @mousedown.prevent="selectStudent(siswa)"
                        class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-50 dark:hover:bg-slate-700 text-gray-800 dark:text-gray-200"
                      >
                        {{ siswa.nisn }} - {{ siswa.name }}
                      </div>
                      <div
                        v-if="isLoadingUnassigned"
                        class="px-4 py-2 text-sm text-center text-blue-500 dark:text-blue-400"
                      >
                        Memuat data...
                      </div>
                    </div>
                  </div>

                  <div v-if="isEditing" class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >NISN</label
                    >
                    <input
                      type="text"
                      v-model="form.nisn"
                      disabled
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                      placeholder="005XXXXXXX"
                    />
                  </div>
                  <div v-if="isEditing" class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Nama Lengkap</label
                    >
                    <input
                      type="text"
                      v-model="form.name"
                      disabled
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                      placeholder="Nama alumni"
                    />
                  </div>
                  <div class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Tahun Lulus</label
                    >
                    <input
                      type="text"
                      v-model="form.year"
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="2023"
                    />
                  </div>
                  <div class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Status</label
                    >
                    <select
                      v-model="form.status"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    >
                      <option
                        v-for="status in statusAlumniList"
                        :key="status"
                        :value="status"
                      >
                        {{ status }}
                      </option>
                    </select>
                  </div>
                  <div class="md:col-span-2">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Instansi / Tempat (Kampus / Perusahaan)</label
                    >
                    <div v-show="!showNewInstansiInput" class="relative">
                      <button
                        type="button"
                        @click="isInstansiDropdownOpen = !isInstansiDropdownOpen"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-left focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex justify-between items-center transition-colors"
                        :class="
                          form.instansi
                            ? 'text-gray-900 dark:text-white'
                            : 'text-gray-500 dark:text-gray-400'
                        "
                      >
                        <span class="truncate">{{
                          form.instansi || "Pilih Instansi..."
                        }}</span>
                        <PhCaretDown
                          class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                          :class="{ 'rotate-180': isInstansiDropdownOpen }"
                        />
                      </button>

                      <div
                        v-if="isInstansiDropdownOpen"
                        @click="isInstansiDropdownOpen = false"
                        class="fixed inset-0 z-40"
                      ></div>

                      <Transition
                        enter-active-class="transition ease-out duration-100"
                        enter-from-class="opacity-0 translate-y-[-10px]"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition ease-in duration-100"
                        leave-from-class="opacity-100 translate-y-0"
                        leave-to-class="opacity-0 translate-y-[-10px]"
                      >
                        <div
                          v-if="isInstansiDropdownOpen"
                          class="absolute top-full z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-xl max-h-60 overflow-y-auto custom-scrollbar"
                        >
                          <ul class="py-1 text-sm">
                            <li
                              v-for="(inst, index) in institutionList"
                              :key="index"
                              class="hover:bg-blue-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 transition-colors group"
                            >
                              <div
                                v-if="editingInstansiIndex === index"
                                class="flex items-center gap-2 w-full px-4 py-2"
                                @click.stop
                              >
                                <input
                                  type="text"
                                  v-model="editingInstansiName"
                                  class="flex-1 px-2 py-1 text-sm border border-gray-300 dark:border-slate-600 rounded bg-white dark:bg-slate-700 focus:ring-blue-500 focus:border-blue-500"
                                  @keydown.enter.prevent="saveEditInstansi(index)"
                                  @keydown.esc.prevent="cancelEditInstansi()"
                                />
                                <button
                                  type="button"
                                  @click="saveEditInstansi(index)"
                                  class="p-1 text-green-600 hover:bg-green-100 rounded"
                                  title="Simpan"
                                >
                                  <PhCheck class="w-4 h-4" />
                                </button>
                                <button
                                  type="button"
                                  @click="cancelEditInstansi()"
                                  class="p-1 text-gray-500 hover:bg-gray-200 rounded"
                                  title="Batal"
                                >
                                  <PhX class="w-4 h-4" />
                                </button>
                              </div>
                              <div
                                v-else
                                class="flex items-center justify-between w-full px-4 py-2.5 cursor-pointer"
                                @click="selectInstansi(inst)"
                              >
                                <span class="truncate pr-2">{{ inst }}</span>
                                <div
                                  class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 shrink-0"
                                  @click.stop
                                >
                                  <button
                                    type="button"
                                    @click="startEditInstansi(index, inst)"
                                    class="p-1 text-blue-600 hover:bg-blue-100 rounded transition-colors"
                                    title="Edit Instansi"
                                  >
                                    <PhPencilSimple class="w-4 h-4" />
                                  </button>
                                  <button
                                    type="button"
                                    @click="handleDeleteInstansi(index)"
                                    class="p-1 text-red-600 hover:bg-red-100 rounded transition-colors"
                                    title="Hapus Instansi"
                                  >
                                    <PhTrash class="w-4 h-4" />
                                  </button>
                                </div>
                              </div>
                            </li>
                            <li
                              @click="selectInstansi('ADD_NEW')"
                              class="px-4 py-2.5 hover:bg-blue-50 dark:hover:bg-slate-700 cursor-pointer font-semibold text-blue-600 dark:text-blue-400 border-t border-gray-100 dark:border-slate-700 transition-colors sticky bottom-0 bg-white dark:bg-slate-800"
                            >
                              + Tambah Instansi Baru...
                            </li>
                          </ul>
                        </div>
                      </Transition>
                    </div>
                    <div v-show="showNewInstansiInput" class="flex gap-2">
                      <input
                        type="text"
                        v-model="newInstansiName"
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ketik nama instansi baru..."
                        @keydown.enter.prevent="addNewInstansi"
                      />
                      <button
                        type="button"
                        @click="addNewInstansi"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                      >
                        Simpan
                      </button>
                      <button
                        type="button"
                        @click="cancelNewInstansi"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 dark:bg-slate-600 dark:text-gray-300 dark:hover:bg-slate-500 transition-colors text-sm font-medium"
                      >
                        Batal
                      </button>
                    </div>
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
                form="alumniForm"
                type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
                <PhPlusCircle v-else class="w-5 h-5 mr-2" />
                {{ isEditing ? "Simpan Perubahan" : "Simpan Data" }}
              </button>
            </div>
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
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
          <div class="relative flex-1 md:max-w-xs">
            <div
              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
            >
              <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
            </div>
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Cari NISN atau Nama..."
              class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>
          <select
            v-model="filterStatus"
            class="block w-full md:w-auto md:min-w-[160px] px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Status</option>
            <option v-for="status in statusAlumniList" :key="status" :value="status">
              {{ status }}
            </option>
          </select>
        </div>
        <button
          @click="showAddForm"
          class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors w-full md:w-auto"
        >
          <PhPlusCircle class="w-5 h-5 mr-2" />
          Tambah Data Alumni
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr
              class="bg-gray-50 dark:bg-slate-700/50 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              <th class="px-6 py-4">NISN</th>
              <th class="px-6 py-4">Nama</th>
              <th class="px-6 py-4">Tahun Lulus</th>
              <th class="px-6 py-4">Status Karir</th>
              <th class="px-6 py-4">Instansi / Kampus</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            <tr v-if="filteredAlumni.length === 0">
              <td
                colspan="6"
                class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
              >
                <PhGraduationCap
                  class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-500 mb-3"
                />
                <p>Tidak ada data alumni yang ditemukan.</p>
              </td>
            </tr>
            <tr
              v-for="alumni in filteredAlumni"
              :key="alumni.id"
              class="hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors group"
            >
              <td class="px-6 py-4">
                <span class="block text-sm text-gray-600 dark:text-gray-400 font-mono">{{
                  alumni.nisn
                }}</span>
              </td>
              <td class="px-6 py-4">
                <span
                  class="block text-sm font-semibold text-gray-800 dark:text-gray-200"
                  >{{ alumni.name }}</span
                >
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                {{ alumni.year }}
              </td>
              <td class="px-6 py-4">
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                  :class="{
                    'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400':
                      alumni.status === 'Kuliah',
                    'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400':
                      alumni.status === 'Bekerja',
                  }"
                >
                  {{ alumni.status }}
                </span>
              </td>
              <td
                class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 truncate max-w-[200px]"
                :title="alumni.instansi"
              >
                {{ alumni.instansi || "-" }}
              </td>
              <td class="px-6 py-4 text-right">
                <div
                  class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <button
                    @click="startEdit(alumni)"
                    class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors"
                    title="Edit Data"
                  >
                    <PhPencilSimple class="w-4 h-4" />
                  </button>
                  <button
                    @click="deleteEntry(alumni.id)"
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

    <!-- Bagian Peta Persebaran Alumni -->
    <div
      class="mt-12 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden p-6"
    >
      <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4"
      >
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
            <PhMapTrifold class="w-6 h-6 mr-2 text-gray-800 dark:text-white" />
            Manajemen Peta Persebaran Alumni
          </h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Atur titik lokasi (pin) dan daftar instansi untuk ditampilkan di Halaman Utama
            (Home).
          </p>
        </div>
        <button
          @click="openAddMap"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors"
        >
          <PhPlusCircle class="w-5 h-5 mr-2" />
          Tambah Lokasi (Pin)
        </button>
      </div>

      <!-- Preview Peta -->
      <div
        class="relative w-full aspect-[2.5/1] md:aspect-[3/1] bg-blue-50 dark:bg-slate-900 rounded-xl overflow-hidden border border-gray-200 dark:border-slate-700 mb-6"
      >
        <img
          src="/img/indonesia.svg"
          class="absolute inset-0 w-full h-full object-fill opacity-50 dark:opacity-80 dark:invert pointer-events-none"
        />

        <div
          v-for="loc in mapLocations"
          :key="loc.id"
          class="absolute flex justify-center items-end group cursor-pointer w-8 h-10 -translate-x-1/2 -translate-y-full"
          :style="{ top: loc.top, left: loc.left }"
          @click="openEditMap(loc)"
          title="Klik untuk mengedit"
        >
          <PhMapPin
            weight="fill"
            class="w-8 h-8 text-blue-600 dark:text-blue-400 drop-shadow-md group-hover:-translate-y-1 transition-transform"
          />
          <div
            class="absolute top-10 whitespace-nowrap bg-white dark:bg-slate-800 text-xs px-2 py-1 rounded shadow border border-gray-100 dark:border-slate-600 opacity-0 group-hover:opacity-100 transition-opacity z-10 font-medium"
          >
            {{ loc.name }} ({{ loc.totalAlumni }})
          </div>
        </div>
      </div>

      <!-- Tabel Daftar Lokasi -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr
              class="bg-gray-50 dark:bg-slate-700/50 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              <th class="px-6 py-4">Nama Wilayah</th>
              <th class="px-6 py-4">Titik (Top/Left)</th>
              <th class="px-6 py-4">Total Instansi</th>
              <th class="px-6 py-4">Total Alumni</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            <tr
              v-for="loc in mapLocations"
              :key="loc.id"
              class="hover:bg-blue-50/50 dark:hover:bg-slate-700/30"
            >
              <td
                class="px-6 py-4 text-sm font-semibold text-gray-800 dark:text-gray-200"
              >
                {{ loc.name }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-mono">
                {{ loc.top }}, {{ loc.left }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                {{ loc.institutions.length }} Instansi
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                {{ loc.totalAlumni }} Alumni
              </td>
              <td class="px-6 py-4 text-right">
                <button
                  @click="openEditMap(loc)"
                  class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors mr-1"
                  title="Edit"
                >
                  <PhPencilSimple class="w-4 h-4" />
                </button>
                <button
                  @click="deleteMapLocation(loc.id)"
                  class="p-1.5 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-md transition-colors"
                  title="Hapus"
                >
                  <PhTrash class="w-4 h-4" />
                </button>
              </td>
            </tr>
            <tr v-if="mapLocations.length === 0">
              <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                Belum ada titik peta persebaran alumni.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Edit/Tambah Lokasi Peta -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isMapModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="
          isMapModalOpen = false;
          document.body.style.overflow = '';
        "
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col overflow-hidden"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
              {{ isMapEditing ? "Edit Lokasi Peta" : "Tambah Lokasi Peta Baru" }}
            </h3>
            <button
              @click="
                isMapModalOpen = false;
                document.body.style.overflow = '';
              "
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>

          <div
            class="p-6 overflow-y-auto custom-scrollbar flex-1 grid grid-cols-1 lg:grid-cols-2 gap-8"
          >
            <!-- Kolom Kiri: Form Basic & Peta -->
            <div class="space-y-4">
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Nama Wilayah</label
                >
                <input
                  type="text"
                  v-model="mapForm.name"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Contoh: Jawa Tengah"
                />
              </div>

              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Atur Posisi Titik (Klik pada peta)</label
                >
                <div
                  ref="mapContainerRef"
                  @click="handleMapClick"
                  class="relative w-full aspect-[2/1] bg-blue-50 dark:bg-slate-900 rounded-lg overflow-hidden border border-gray-300 dark:border-slate-600 cursor-crosshair"
                >
                  <img
                    src="/img/indonesia.svg"
                    class="absolute inset-0 w-full h-full object-fill opacity-60 dark:opacity-80 dark:invert pointer-events-none"
                  />
                  <div
                    class="absolute flex justify-center items-end w-6 h-8 -translate-x-1/2 -translate-y-full pointer-events-none"
                    :style="{ top: mapForm.top, left: mapForm.left }"
                  >
                    <PhMapPin weight="fill" class="w-6 h-8 text-red-500 drop-shadow" />
                  </div>
                </div>
                <div class="flex gap-4 mt-3">
                  <div class="flex-1">
                    <label class="text-xs text-gray-500 font-medium">Top (%)</label>
                    <input
                      type="text"
                      v-model="mapForm.top"
                      class="w-full px-2 py-1 text-sm border rounded bg-gray-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                    />
                  </div>
                  <div class="flex-1">
                    <label class="text-xs text-gray-500 font-medium">Left (%)</label>
                    <input
                      type="text"
                      v-model="mapForm.left"
                      class="w-full px-2 py-1 text-sm border rounded bg-gray-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Kolom Kanan: Daftar Instansi -->
            <div class="space-y-4">
              <div
                class="flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg border border-gray-100 dark:border-slate-600"
              >
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300"
                  >Daftar Universitas / Instansi</label
                >
                <button
                  @click="addInstitution"
                  class="text-xs px-2.5 py-1.5 bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 rounded hover:bg-blue-200 transition-colors flex items-center font-bold"
                >
                  <PhPlusCircle class="w-3.5 h-3.5 mr-1" /> Tambah
                </button>
              </div>

              <div
                class="space-y-4 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar"
                ref="instansiListContainer"
              >
                <div
                  v-for="(inst, idx) in mapForm.institutions"
                  :key="idx"
                  class="p-4 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/30 relative group shadow-sm"
                >
                  <button
                    @click="removeInstitution(idx)"
                    class="absolute top-2 right-2 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 rounded p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                  >
                    <PhTrash class="w-4 h-4" />
                  </button>
                  <div class="space-y-3 pr-6">
                    <div>
                      <label class="text-xs text-gray-500 mb-1 block"
                        >Nama Instansi</label
                      >
                      <div class="relative">
                        <button
                          type="button"
                          @click="
                            mapInstDropdownOpen = mapInstDropdownOpen === idx ? null : idx
                          "
                          class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-slate-600 rounded bg-white dark:bg-slate-700 dark:text-white focus:ring-1 focus:ring-blue-500 flex justify-between items-center text-left transition-colors"
                        >
                          <span class="truncate">{{
                            inst.name || "Pilih Instansi..."
                          }}</span>
                          <PhCaretDown
                            class="w-4 h-4 text-gray-400 shrink-0 transition-transform"
                            :class="{ 'rotate-180': mapInstDropdownOpen === idx }"
                          />
                        </button>
                        <div
                          v-if="mapInstDropdownOpen === idx"
                          @click="mapInstDropdownOpen = null"
                          class="fixed inset-0 z-40"
                        ></div>
                        <Transition
                          enter-active-class="transition ease-out duration-100"
                          enter-from-class="opacity-0 translate-y-[-10px]"
                          enter-to-class="opacity-100 translate-y-0"
                          leave-active-class="transition ease-in duration-100"
                          leave-from-class="opacity-100 translate-y-0"
                          leave-to-class="opacity-0 translate-y-[-10px]"
                        >
                          <div
                            v-if="mapInstDropdownOpen === idx"
                            class="absolute top-full z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-xl max-h-48 overflow-y-auto custom-scrollbar"
                          >
                            <ul class="py-1 text-sm">
                              <li
                                v-for="(item, i) in availableInstitutions"
                                :key="i"
                                class="hover:bg-blue-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 transition-colors"
                              >
                                <div
                                  class="px-4 py-2 cursor-pointer flex justify-between items-center"
                                  @click="
                                    inst.name = item.name;
                                    autoFillInstansi(inst);
                                    mapInstDropdownOpen = null;
                                  "
                                >
                                  <span class="truncate pr-2">{{ item.name }}</span>
                                  <span
                                    class="text-[10px] bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 px-1.5 py-0.5 rounded font-bold shrink-0"
                                    >{{ item.count }} Alumni</span
                                  >
                                </div>
                              </li>
                              <li
                                v-if="availableInstitutions.length === 0"
                                class="px-4 py-3 text-gray-500 text-center"
                              >
                                Belum ada instansi yang terdaftar di data alumni
                              </li>
                            </ul>
                          </div>
                        </Transition>
                      </div>
                    </div>
                    <div class="flex gap-3">
                      <div class="flex-1">
                        <label class="text-xs text-gray-500 mb-1 block">Jenis</label>
                        <select
                          v-model="inst.type"
                          class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-slate-600 rounded bg-white dark:bg-slate-700 dark:text-white focus:ring-1 focus:ring-blue-500"
                        >
                          <option value="ptn">PTN</option>
                          <option value="kedinasan">Kedinasan</option>
                          <option value="instansi">BUMN / Instansi</option>
                        </select>
                      </div>
                      <div class="w-24">
                        <label class="text-xs text-gray-500 mb-1 block">Jml Alumni</label>
                        <input
                          type="number"
                          v-model="inst.alumni"
                          placeholder="0"
                          class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-slate-600 rounded bg-white dark:bg-slate-700 dark:text-white focus:ring-1 focus:ring-blue-500"
                        />
                      </div>
                    </div>
                    <div class="mt-1">
                      <label class="text-xs text-gray-500 block mb-1"
                        >Logo Instansi (Opsional)</label
                      >
                      <div class="flex items-center gap-3">
                        <div
                          class="w-12 h-12 border border-gray-300 dark:border-slate-600 rounded bg-white dark:bg-slate-800 flex items-center justify-center shrink-0 overflow-hidden relative group"
                        >
                          <img
                            v-if="inst.logo"
                            :src="inst.logo"
                            class="w-full h-full object-contain p-1"
                          />
                          <span v-else class="text-[10px] text-gray-400">Logo</span>
                          <div
                            v-if="inst.logo"
                            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity cursor-pointer"
                            @click="inst.logo = ''"
                          >
                            <PhTrash class="w-4 h-4 text-white" />
                          </div>
                        </div>
                        <div class="flex-1">
                          <label
                            class="cursor-pointer inline-flex items-center px-2.5 py-1.5 border border-gray-300 dark:border-slate-600 rounded shadow-sm text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors"
                          >
                            Pilih Gambar
                            <input
                              type="file"
                              accept="image/*"
                              class="sr-only"
                              @change="(e) => handleLogoUpload(e, inst)"
                            />
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  v-if="mapForm.institutions.length === 0"
                  class="text-center text-sm text-gray-500 py-8 border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-lg"
                >
                  Belum ada instansi yang ditambahkan.
                </div>
              </div>
            </div>
          </div>

          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              @click="
                isMapModalOpen = false;
                document.body.style.overflow = '';
              "
              class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-slate-700 dark:border-slate-600 dark:text-gray-300 dark:hover:bg-slate-600"
            >
              Batal
            </button>
            <button
              @click="saveMapLocation"
              class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
            >
              <PhFloppyDisk class="w-5 h-5 mr-2" /> Simpan Titik Peta
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Data Alumni"
      message="Yakin ingin menghapus data tracking alumni ini secara permanen dari sistem?"
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
