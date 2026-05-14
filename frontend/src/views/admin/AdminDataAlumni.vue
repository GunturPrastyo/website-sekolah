<script setup>
import { ref, computed } from "vue";
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
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const statusAlumniList = ["Kuliah", "Bekerja", "Wirausaha", "Lainnya"];

// Simulasi Data Master Siswa (Hanya yang berstatus Alumni di Data Siswa)
const masterSiswaAlumni = ref([
  { nisn: "0059876543", name: "Budi Santoso", year: "2023" },
  { nisn: "0061234567", name: "Siti Rahma", year: "2022" },
  { nisn: "0071112223", name: "Ahmad Dahlan", year: "2023" },
  { nisn: "0089998887", name: "Putri Larasati", year: "2023" },
]);

// Dummy data simulasi alumni
const alumniList = ref([
  {
    id: 1,
    nisn: "0059876543",
    name: "Budi Santoso",
    year: "2023",
    status: "Kuliah",
    instansi: "Universitas Gadjah Mada",
  },
  {
    id: 2,
    nisn: "0061234567",
    name: "Siti Rahma",
    year: "2022",
    status: "Bekerja",
    instansi: "PT Pertamina",
  },
]);

const form = ref({
  id: null,
  nisn: "",
  name: "",
  year: new Date().getFullYear().toString(),
  status: "Kuliah",
  instansi: "",
});

// Data Peta Persebaran (Untuk Home View)
const mapLocations = ref([
  {
    id: 1,
    name: "Jabodetabek & Sekitarnya",
    type: "mixed",
    totalAlumni: 194,
    top: "73%",
    left: "27%",
    institutions: [
      { name: "Universitas Indonesia", type: "ptn", alumni: 45, logo: "/img/ui.png" },
      {
        name: "Politeknik Keuangan Negara STAN",
        type: "kedinasan",
        alumni: 64,
        logo: "https://img.icons8.com/color/96/bank-building.png",
      },
      {
        name: "Instansi BUMN & Kementerian",
        type: "instansi",
        alumni: 85,
        logo: "https://img.icons8.com/color/96/city-buildings.png",
      },
    ],
  },
  {
    id: 2,
    name: "Jawa Barat",
    type: "ptn",
    totalAlumni: 70,
    top: "77%",
    left: "31%",
    institutions: [
      { name: "Institut Pertanian Bogor", type: "ptn", alumni: 38, logo: "/img/ipb.png" },
      {
        name: "Institut Teknologi Bandung",
        type: "ptn",
        alumni: 32,
        logo: "/img/itb.png",
      },
    ],
  },
  {
    id: 3,
    name: "Jawa Tengah & DIY",
    type: "mixed",
    totalAlumni: 175,
    top: "80%",
    left: "38%",
    institutions: [
      { name: "Universitas Gadjah Mada", type: "ptn", alumni: 50, logo: "/img/ugm.png" },
      {
        name: "Akademi Kepolisian (AKPOL)",
        type: "kedinasan",
        alumni: 125,
        logo: "https://img.icons8.com/color/96/police-badge.png",
      },
    ],
  },
]);

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

const autoFillCount = (inst) => {
  const found = availableInstitutions.value.find((i) => i.name === inst.name);
  if (found) {
    inst.alumni = found.count;
  }
};

const mapContainerRef = ref(null);

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
  isMapModalOpen.value = true;
  document.body.style.overflow = "hidden";
};

const openEditMap = (loc) => {
  mapForm.value = JSON.parse(JSON.stringify(loc));
  isMapEditing.value = true;
  isMapModalOpen.value = true;
  document.body.style.overflow = "hidden";
};

const saveMapLocation = () => {
  if (!mapForm.value.name) {
    triggerToast("Gagal", "Nama wilayah harus diisi!", "error");
    return;
  }
  mapForm.value.totalAlumni = mapForm.value.institutions.reduce(
    (sum, inst) => sum + (Number(inst.alumni) || 0),
    0
  );
  if (isMapEditing.value) {
    const idx = mapLocations.value.findIndex((l) => l.id === mapForm.value.id);
    if (idx !== -1) mapLocations.value[idx] = JSON.parse(JSON.stringify(mapForm.value));
  } else {
    mapForm.value.id = Date.now();
    mapLocations.value.push(JSON.parse(JSON.stringify(mapForm.value)));
  }
  isMapModalOpen.value = false;
  document.body.style.overflow = "";
  triggerToast("Disimpan", "Data persebaran alumni berhasil disimpan.");
};

const deleteMapLocation = (id) => {
  if (confirm("Hapus lokasi ini dari peta?")) {
    mapLocations.value = mapLocations.value.filter((l) => l.id !== id);
    triggerToast("Dihapus", "Lokasi berhasil dihapus.", "info");
  }
};

const addInstitution = () => {
  mapForm.value.institutions.push({ name: "", type: "ptn", alumni: 0, logo: "" });
};

const removeInstitution = (idx) => {
  mapForm.value.institutions.splice(idx, 1);
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

// Memfilter agar siswa yang sudah dilacak tidak muncul lagi di dropdown
const unassignedAlumni = computed(() => {
  const trackedNisn = alumniList.value.map((a) => a.nisn);
  return masterSiswaAlumni.value.filter((s) => !trackedNisn.includes(s.nisn));
});

const filteredUnassignedAlumni = computed(() => {
  if (!searchStudent.value) return unassignedAlumni.value;
  const q = searchStudent.value.toLowerCase();
  return unassignedAlumni.value.filter(
    (s) => s.name.toLowerCase().includes(q) || s.nisn.toLowerCase().includes(q)
  );
});

const selectStudent = (siswa) => {
  form.value.nisn = siswa.nisn;
  form.value.name = siswa.name;
  form.value.year = siswa.year;
  searchStudent.value = `${siswa.nisn} - ${siswa.name}`;
  isDropdownOpen.value = false;
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
    nisn: "",
    name: "",
    year: new Date().getFullYear().toString(),
    status: "Kuliah",
    instansi: "",
  };
  searchStudent.value = "";
  isDropdownOpen.value = false;
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

const addEntry = () => {
  if (!form.value.name || !form.value.nisn) {
    triggerToast("Gagal Menyimpan", "Nama dan NISN wajib diisi!", "error");
    return;
  }

  const newId =
    alumniList.value.length > 0 ? Math.max(...alumniList.value.map((s) => s.id)) + 1 : 1;

  alumniList.value.unshift({
    id: newId,
    nisn: form.value.nisn,
    name: form.value.name,
    year: form.value.year,
    status: form.value.status,
    instansi: form.value.instansi,
  });

  hideForm();
  triggerToast("Berhasil Ditambahkan", "Data alumni baru berhasil ditambahkan.");
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = { ...item };
  searchStudent.value = `${item.nisn} - ${item.name}`;
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = () => {
  if (!form.value.name || !form.value.nisn) {
    triggerToast("Gagal Menyimpan", "Nama dan NISN wajib diisi!", "error");
    return;
  }

  const index = alumniList.value.findIndex((s) => s.id === form.value.id);
  if (index !== -1) {
    alumniList.value[index] = { ...form.value };
  }

  hideForm();
  triggerToast("Perubahan Disimpan", "Data alumni berhasil diperbarui.");
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
  if (itemToDelete.value !== null) {
    alumniList.value = alumniList.value.filter((s) => s.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast("Data Dihapus", "Data alumni berhasil dihapus dari sistem.", "info");
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
      <button
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Data Alumni
      </button>
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
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="hideForm"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
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
          <div class="p-6 overflow-y-auto custom-scrollbar max-h-[70vh]">
            <form id="alumniForm" @submit.prevent="isEditing ? saveEntry() : addEntry()">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-if="!isEditing" class="md:col-span-2 relative">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Cari Siswa (Dari Data Siswa Berstatus Alumni)
                  </label>
                  <input
                    type="text"
                    v-model="searchStudent"
                    @focus="isDropdownOpen = true"
                    @blur="closeDropdown"
                    placeholder="Ketik NISN atau Nama Siswa..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  />
                  <div
                    v-if="isDropdownOpen"
                    class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg max-h-48 overflow-y-auto"
                  >
                    <div
                      v-if="filteredUnassignedAlumni.length === 0"
                      class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400"
                    >
                      Siswa tidak ditemukan
                    </div>
                    <div
                      v-for="siswa in filteredUnassignedAlumni"
                      :key="siswa.nisn"
                      @click="selectStudent(siswa)"
                      class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-50 dark:hover:bg-slate-700 text-gray-800 dark:text-gray-200"
                    >
                      {{ siswa.nisn }} - {{ siswa.name }}
                    </div>
                  </div>
                </div>
                <div class="md:col-span-1">
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
                <div class="md:col-span-1">
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
                  <input
                    type="text"
                    v-model="form.instansi"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: Universitas Gadjah Mada"
                  />
                </div>
              </div>
            </form>
          </div>
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
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
              placeholder="Cari NISN atau Nama..."
              class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>
          <select
            v-model="filterStatus"
            class="block w-full md:w-40 px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Status</option>
            <option v-for="status in statusAlumniList" :key="status" :value="status">
              {{ status }}
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
                    'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-400':
                      alumni.status === 'Wirausaha',
                    'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300':
                      alumni.status === 'Lainnya',
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
            <PhMapTrifold class="w-6 h-6 mr-2 text-blue-600" />
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
          class="absolute inset-0 w-full h-full object-fill opacity-50 pointer-events-none"
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
                    class="absolute inset-0 w-full h-full object-fill opacity-60 pointer-events-none"
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

              <div class="space-y-3 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar">
                <div
                  v-for="(inst, idx) in mapForm.institutions"
                  :key="idx"
                  class="p-4 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700/30 relative group"
                >
                  <button
                    @click="removeInstitution(idx)"
                    class="absolute top-2 right-2 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 rounded p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                  >
                    <PhTrash class="w-4 h-4" />
                  </button>
                  <div class="space-y-3 pr-6">
                    <div>
                      <label class="text-xs text-gray-500">Nama Instansi</label>
                      <input
                        type="text"
                        v-model="inst.name"
                        @input="autoFillCount(inst)"
                        list="available-instansi"
                        placeholder="Universitas / Instansi"
                        class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-slate-600 rounded dark:bg-slate-700 dark:text-white focus:ring-1 focus:ring-blue-500"
                      />
                      <datalist id="available-instansi">
                        <option
                          v-for="item in availableInstitutions"
                          :key="item.name"
                          :value="item.name"
                        ></option>
                      </datalist>
                    </div>
                    <div class="flex gap-3">
                      <div class="flex-1">
                        <label class="text-xs text-gray-500">Jenis</label>
                        <select
                          v-model="inst.type"
                          class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-slate-600 rounded dark:bg-slate-700 dark:text-white focus:ring-1 focus:ring-blue-500"
                        >
                          <option value="ptn">PTN</option>
                          <option value="kedinasan">Kedinasan</option>
                          <option value="instansi">BUMN / Instansi</option>
                        </select>
                      </div>
                      <div class="w-24">
                        <label class="text-xs text-gray-500">Jml Alumni</label>
                        <input
                          type="number"
                          v-model="inst.alumni"
                          placeholder="0"
                          class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-slate-600 rounded dark:bg-slate-700 dark:text-white focus:ring-1 focus:ring-blue-500"
                        />
                      </div>
                    </div>
                    <div>
                      <label class="text-xs text-gray-500">URL Logo (Opsional)</label>
                      <input
                        type="text"
                        v-model="inst.logo"
                        placeholder="/img/logo.png"
                        class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-slate-600 rounded dark:bg-slate-700 dark:text-white focus:ring-1 focus:ring-blue-500"
                      />
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
