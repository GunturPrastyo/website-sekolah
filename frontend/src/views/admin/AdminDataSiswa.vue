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
  PhUploadSimple,
  PhX,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const grades = ["X", "XI", "XII"];
const majors = ["MIPA", "IPS", "Bahasa"];

// Data dummy untuk simulasi
const studentsList = ref([
  {
    id: 1,
    nisn: "0051234567",
    name: "Andi Saputra",
    gender: "L",
    grade: "X",
    major: "MIPA",
    status: "aktif",
  },
  {
    id: 2,
    nisn: "0057654321",
    name: "Siti Aminah",
    gender: "P",
    grade: "XI",
    major: "IPS",
    status: "aktif",
  },
  {
    id: 3,
    nisn: "0059876543",
    name: "Budi Santoso",
    gender: "L",
    grade: "XII",
    major: "Bahasa",
    status: "alumni",
  },
]);

const form = ref({
  id: null,
  nisn: "",
  name: "",
  gender: "L",
  grade: "X",
  major: "MIPA",
  status: "aktif",
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const isImportModalOpen = ref(false);
const isImporting = ref(false);
const fileInput = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");
const filterGrade = ref("semua");
const filterMajor = ref("semua");
const filterStatus = ref("semua");

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
    gender: "L",
    grade: "X",
    major: "MIPA",
    status: "aktif",
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

const showImportModal = () => {
  isImportModalOpen.value = true;
  document.body.style.overflow = "hidden";
};

const closeImportModal = () => {
  isImportModalOpen.value = false;
  document.body.style.overflow = "";
  if (fileInput.value) fileInput.value.value = "";
};

const handleFileUpload = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  isImporting.value = true;

  try {
    /* 
    // --- CARA IMPLEMENTASI ASLI DENGAN LIBRARY 'xlsx' (SheetJS) ---
    // 1. Install library: npm install xlsx
    // 2. Import di atas file: import * as xlsx from 'xlsx';
    
    const data = await file.arrayBuffer();
    const workbook = xlsx.read(data);
    const firstSheetName = workbook.SheetNames[0]; // Biasanya sheet pertama
    const worksheet = workbook.Sheets[firstSheetName];
    
    // Ubah data sheet ke format JSON Array
    const jsonData = xlsx.utils.sheet_to_json(worksheet);
    
    // 3. Lakukan mapping data dari kolom Dapodik ke properti sistem Anda
    const mappedData = jsonData.map((row, index) => ({
      id: Date.now() + index,
      nisn: row['NISN'] || row['nisn'] || '',
      name: row['Nama Peserta Didik'] || row['nama'] || '',
      gender: (row['L/P'] || row['jenis_kelamin']) === 'L' ? 'L' : 'P',
      grade: row['Tingkat Kelas'] || row['kelas'] || 'X',
      major: row['Jurusan'] || row['rombel'] || 'MIPA',
      status: row['Status'] || row['status'] || 'aktif'
    }));
    
    // 4. Masukkan ke dalam list / kirim bulk insert ke backend API
    studentsList.value = [...mappedData, ...studentsList.value];
    */

    // --- SIMULASI PROSES IMPORT UNTUK DEMO UI ---
    setTimeout(() => {
      studentsList.value.unshift({
        id: Date.now(),
        nisn: Math.floor(1000000000 + Math.random() * 9000000000).toString(),
        name: "Siswa Baru (Hasil Import Dapodik)",
        gender: Math.random() > 0.5 ? "L" : "P",
        grade: grades[Math.floor(Math.random() * grades.length)],
        major: majors[Math.floor(Math.random() * majors.length)],
        status: "aktif",
      });
      isImporting.value = false;
      closeImportModal();
      triggerToast(
        "Import Berhasil",
        `Data siswa berhasil dibaca dari file ${file.name}.`
      );
    }, 2000);
  } catch (error) {
    isImporting.value = false;
    triggerToast(
      "Gagal Import",
      "Terjadi kesalahan saat membaca file atau format tidak didukung.",
      "error"
    );
  }
};

const addEntry = () => {
  if (!form.value.name || !form.value.nisn) {
    triggerToast("Gagal Menyimpan", "Nama dan NISN wajib diisi!", "error");
    return;
  }

  const newId =
    studentsList.value.length > 0
      ? Math.max(...studentsList.value.map((s) => s.id)) + 1
      : 1;

  studentsList.value.unshift({
    id: newId,
    nisn: form.value.nisn,
    name: form.value.name,
    gender: form.value.gender,
    grade: form.value.grade,
    major: form.value.major,
    status: form.value.status,
  });

  hideForm();
  triggerToast("Berhasil Ditambahkan", "Data siswa baru berhasil ditambahkan.");
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = { ...item };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = () => {
  if (!form.value.name || !form.value.nisn) {
    triggerToast("Gagal Menyimpan", "Nama dan NISN wajib diisi!", "error");
    return;
  }

  const index = studentsList.value.findIndex((s) => s.id === form.value.id);
  if (index !== -1) {
    studentsList.value[index] = { ...form.value };
  }

  hideForm();
  triggerToast("Perubahan Disimpan", "Data siswa berhasil diperbarui.");
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
  if (itemToDelete.value !== null) {
    studentsList.value = studentsList.value.filter((s) => s.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast("Data Dihapus", "Data siswa berhasil dihapus dari sistem.", "info");
  }
  isDeleteModalOpen.value = false;
};

const filteredStudents = computed(() => {
  let result = studentsList.value;

  if (filterGrade.value !== "semua") {
    result = result.filter((item) => item.grade === filterGrade.value);
  }

  if (filterMajor.value !== "semua") {
    result = result.filter((item) => item.major === filterMajor.value);
  }

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
          Data Induk Siswa
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data siswa aktif, tambah secara manual, atau import massal dari file
          Dapodik.
        </p>
      </div>
      <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
        <button
          @click="showImportModal"
          class="inline-flex items-center justify-center px-4 py-2.5 border border-green-600 dark:border-green-500 text-sm font-medium rounded-lg text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
        >
          <PhUploadSimple class="w-5 h-5 mr-2" />
          Import Dapodik
        </button>
        <button
          @click="showAddForm"
          class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
        >
          <PhPlusCircle class="w-5 h-5 mr-2" />
          Tambah Siswa
        </button>
      </div>
    </div>

    <!-- Form Import Modal -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isImportModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closeImportModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
              Import Data dari Dapodik
            </h3>
            <button
              @click="closeImportModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6">
            <div
              class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-xl p-8 text-center flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-700/30"
            >
              <PhUploadSimple
                class="w-12 h-12 text-blue-500 mb-4"
                :class="isImporting ? 'animate-bounce' : ''"
              />
              <p class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                Pilih atau Seret File Kesini
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                Format yang didukung: Excel (.xls, .xlsx) atau CSV
              </p>

              <input
                type="file"
                ref="fileInput"
                accept=".xlsx, .xls, .csv"
                class="hidden"
                @change="handleFileUpload"
              />
              <button
                @click="$refs.fileInput.click()"
                :disabled="isImporting"
                class="px-5 py-2 bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-semibold rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/60 transition-colors focus:outline-none"
              >
                <span v-if="isImporting" class="flex items-center">
                  <svg
                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-700 dark:text-blue-300"
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
                  Sedang Membaca...
                </span>
                <span v-else>Cari File</span>
              </button>
            </div>

            <div
              class="mt-6 bg-blue-50 dark:bg-slate-700/60 p-4 rounded-lg text-sm text-gray-800 dark:text-gray-200 border border-blue-100 dark:border-slate-600"
            >
              <p class="font-bold text-blue-800 dark:text-blue-300 mb-2">
                Panduan Import Mudah:
              </p>
              <ol class="list-decimal pl-5 space-y-1.5 text-xs md:text-sm">
                <li>Buka aplikasi Dapodik / Manajemen Sekolah Anda.</li>
                <li>Masuk ke menu <strong>Peserta Didik</strong>.</li>
                <li>
                  Klik tombol <strong>Unduh Profil</strong> atau
                  <strong>Export Excel</strong>.
                </li>
                <li>
                  Masukkan file tersebut ke form di atas, sistem akan membaca secara
                  otomatis.
                </li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </Transition>

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
              {{ isEditing ? "Edit Data Siswa" : "Tambah Siswa Baru" }}
            </h3>
            <button
              @click="hideForm"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6 overflow-y-auto custom-scrollbar max-h-[70vh]">
            <form id="studentForm" @submit.prevent="isEditing ? saveEntry() : addEntry()">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >NISN</label
                  >
                  <input
                    type="text"
                    v-model="form.nisn"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
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
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Nama siswa"
                  />
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Jenis Kelamin</label
                  >
                  <select
                    v-model="form.gender"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="L">Laki-Laki (L)</option>
                    <option value="P">Perempuan (P)</option>
                  </select>
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
                    >Jurusan / Peminatan</label
                  >
                  <select
                    v-model="form.major"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option v-for="major in majors" :key="major" :value="major">
                      {{ major }}
                    </option>
                  </select>
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Status Siswa</label
                  >
                  <select
                    v-model="form.status"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="aktif">Aktif</option>
                    <option value="alumni">Alumni</option>
                  </select>
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
              form="studentForm"
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
            v-model="filterGrade"
            class="block w-full md:w-32 px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Kelas</option>
            <option v-for="grade in grades" :key="grade" :value="grade">
              Kelas {{ grade }}
            </option>
          </select>
          <select
            v-model="filterMajor"
            class="block w-full md:w-40 px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Jurusan</option>
            <option v-for="major in majors" :key="major" :value="major">
              {{ major }}
            </option>
          </select>
          <select
            v-model="filterStatus"
            class="block w-full md:w-32 px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Status</option>
            <option value="aktif">Aktif</option>
            <option value="alumni">Alumni</option>
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
              <th class="px-6 py-4">L/P</th>
              <th class="px-6 py-4">Kelas</th>
              <th class="px-6 py-4">Jurusan</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            <tr v-if="filteredStudents.length === 0">
              <td
                colspan="7"
                class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
              >
                <PhGraduationCap
                  class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-500 mb-3"
                />
                <p>Tidak ada data siswa yang ditemukan.</p>
              </td>
            </tr>
            <tr
              v-for="student in filteredStudents"
              :key="student.id"
              class="hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors group"
            >
              <td class="px-6 py-4">
                <span class="block text-sm text-gray-600 dark:text-gray-400 font-mono">{{
                  student.nisn
                }}</span>
              </td>
              <td class="px-6 py-4">
                <span
                  class="block text-sm font-semibold text-gray-800 dark:text-gray-200"
                  >{{ student.name }}</span
                >
              </td>
              <td class="px-6 py-4">
                <span
                  class="inline-flex items-center justify-center w-6 h-6 rounded-md text-xs font-bold"
                  :class="
                    student.gender === 'L'
                      ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400'
                      : 'bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-400'
                  "
                >
                  {{ student.gender }}
                </span>
              </td>
              <td
                class="px-6 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300"
              >
                {{ student.grade }}
              </td>
              <td class="px-6 py-4">
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300"
                >
                  {{ student.major }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                  :class="
                    student.status === 'aktif'
                      ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400'
                      : 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300'
                  "
                >
                  {{ student.status === "aktif" ? "Aktif" : "Alumni" }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <div
                  class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <button
                    @click="startEdit(student)"
                    class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors"
                    title="Edit Data"
                  >
                    <PhPencilSimple class="w-4 h-4" />
                  </button>
                  <button
                    @click="deleteEntry(student.id)"
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
      title="Hapus Data Siswa"
      message="Yakin ingin menghapus data siswa ini secara permanen dari sistem?"
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
