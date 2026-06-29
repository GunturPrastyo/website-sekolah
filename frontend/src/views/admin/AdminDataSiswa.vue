<script setup>
import { ref, computed, onMounted, watch } from "vue";
import api from "@/api/index.js";
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
  PhSpinner,
  PhCaretDown,
  PhDownloadSimple,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const grades = ["X", "XI", "XII"];
const majors = ref([]); // Mengambil dari data API

const isLoadingData = ref(true);

// State Data Siswa
const studentsList = ref([]);
const classesList = ref([]);

const currentPage = ref(1);
const totalPages = ref(1);
const paginationMeta = ref({});

const form = ref({
  id: null,
  nisn: "",
  name: "",
  gender: "L",
  grade: "",
  major: "",
  school_class_id: null,
  status: "aktif",
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

// State Bulk Action
const selectedStudents = ref([]);
const isBulkEditModalOpen = ref(false);
const isBulkDeleteModalOpen = ref(false);
const bulkEditForm = ref({ grade: "", major: "", school_class_id: null, status: "" });

const isImportModalOpen = ref(false);
const isImporting = ref(false);
const isExporting = ref(false);
const fileInput = ref(null);

const showMapping = ref(false);
const excelHeaders = ref([]);
const excelData = ref([]);
const columnMapping = ref({
  nisn: "",
  name: "",
  gender: "",
  grade: "",
  major: "",
  status: "",
});

const mappingFields = [
  { key: "nisn", label: "NISN", required: true },
  { key: "name", label: "Nama Lengkap", required: true },
  { key: "gender", label: "Jenis Kelamin", required: false },
  { key: "grade", label: "Tingkat Kelas", required: false },
  { key: "major", label: "Jurusan", required: false },
  { key: "status", label: "Status", required: false },
];

const mappingDropdowns = ref({
  nisn: false,
  name: false,
  gender: false,
  grade: false,
  major: false,
  status: false,
});

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");
const filterGrade = ref("semua");
const filterMajor = ref("semua");
const filterStatus = ref("semua");
const itemsPerPage = ref(10);

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

// Mengambil data siswa dari API
const fetchData = async (page = 1) => {
  isLoadingData.value = true;
  try {
    const params = {
      page: page,
      per_page: itemsPerPage.value === "semua" ? 999999 : itemsPerPage.value,
    };
    if (searchQuery.value) params.search = searchQuery.value;
    if (filterGrade.value !== "semua") params.grade = filterGrade.value;
    if (filterMajor.value !== "semua") params.major = filterMajor.value;
    if (filterStatus.value !== "semua") params.status = filterStatus.value;

    const response = await api.get("/api/students", { params });
    studentsList.value = response.data.data;

    if (response.data.pagination) {
      paginationMeta.value = response.data.pagination;
      currentPage.value = response.data.pagination.current_page;
      totalPages.value = response.data.pagination.last_page;
    }
  } catch (error) {
    console.error("Gagal memuat data siswa", error);
    triggerToast(
      "Gagal Memuat",
      "Tidak dapat mengambil data siswa dari server.",
      "error"
    );
  } finally {
    isLoadingData.value = false;
  }
};

let searchTimeout;
watch(searchQuery, () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchData(1);
  }, 500);
});

watch([filterGrade, filterMajor, filterStatus], () => {
  fetchData(1);
});

const fetchClasses = async () => {
  try {
    const response = await api.get("/api/school-classes");
    classesList.value = response.data.data;
  } catch (error) {
    console.error("Gagal memuat data kelas", error);
  }
};

const fetchMajors = async () => {
  try {
    const response = await api.get("/api/programs");
    // Mengambil nama jurusan (title atau name berdasarkan properti API)
    majors.value = response.data.data.map(
      (item) => item.title || item.name || item.program_name
    );
  } catch (error) {
    console.error("Gagal memuat data jurusan", error);
  }
};

onMounted(async () => {
  isLoadingData.value = true;
  await Promise.all([fetchData(1), fetchClasses(), fetchMajors()]);
  isLoadingData.value = false;
});

const resetForm = () => {
  form.value = {
    id: null,
    nisn: "",
    name: "",
    gender: "L",
    grade: "",
    major: "",
    school_class_id: null,
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
  showMapping.value = false;
  excelData.value = [];
  excelHeaders.value = [];
  for (const key in mappingDropdowns.value) {
    mappingDropdowns.value[key] = false;
  }
};

const handleFileUpload = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  isImporting.value = true;

  try {
    let xlsx;
    try {
      xlsx = await import(/* @vite-ignore */ "xlsx");
    } catch (err) {
      triggerToast(
        "Library Tidak Ditemukan",
        "Silakan install library xlsx di frontend dengan perintah: npm install xlsx",
        "error"
      );
      isImporting.value = false;
      return;
    }

    const data = await file.arrayBuffer();
    const workbook = xlsx.read(data);
    const worksheet = workbook.Sheets[workbook.SheetNames[0]];
    const jsonData = xlsx.utils.sheet_to_json(worksheet, { defval: "" });

    if (jsonData.length === 0) {
      triggerToast("Gagal", "File Excel kosong atau format tidak didukung.", "error");
      isImporting.value = false;
      return;
    }

    excelData.value = jsonData;
    excelHeaders.value = Object.keys(jsonData[0]);

    // Auto-guess (tebakan otomatis) untuk membantu pengguna
    const findHeader = (keywords) =>
      excelHeaders.value.find((h) => keywords.some((k) => h.toLowerCase().includes(k))) ||
      "";

    columnMapping.value.nisn = findHeader(["nisn"]);
    columnMapping.value.name = findHeader(["nama", "name", "peserta didik"]);
    columnMapping.value.gender = findHeader(["jenis kelamin", "j.k", "l/p", "gender"]);
    columnMapping.value.grade = findHeader(["kelas", "tingkat", "rombel", "grade"]);
    columnMapping.value.major = findHeader(["jurusan", "peminatan", "major"]);
    columnMapping.value.status = findHeader(["status"]);

    showMapping.value = true;
    isImporting.value = false;
  } catch (error) {
    isImporting.value = false;
    triggerToast(
      "Gagal Import",
      "Terjadi kesalahan saat membaca file atau format tidak didukung.",
      "error"
    );
  }
};

const processImport = async () => {
  if (!columnMapping.value.nisn || !columnMapping.value.name) {
    triggerToast(
      "Peringatan",
      "Kolom NISN dan Nama wajib dipilih untuk melakukan mapping!",
      "warning"
    );
    return;
  }

  isImporting.value = true;

  const formattedData = excelData.value
    .map((row) => {
      let gender = "L";
      const rawGender = String(row[columnMapping.value.gender] || "").toUpperCase();
      if (rawGender === "P" || rawGender.includes("PEREMPUAN")) gender = "P";

      let grade = "";
      const rawGrade = String(row[columnMapping.value.grade] || "")
        .trim()
        .toUpperCase();

      if (rawGrade) {
        if (/(12|XII)\b/.test(rawGrade)) grade = "XII";
        else if (/(11|XI)\b/.test(rawGrade)) grade = "XI";
        else if (/(10|X)\b/.test(rawGrade)) grade = "X";
        else grade = rawGrade;
      }

      let status = "aktif";
      const rawStatus = String(row[columnMapping.value.status] || "").toLowerCase();
      if (rawStatus.includes("alumni") || rawStatus.includes("lulus")) status = "alumni";

      return {
        nisn: String(row[columnMapping.value.nisn] || "").trim(),
        name: String(row[columnMapping.value.name] || "").trim(),
        gender: gender,
        grade: grade,
        major: String(row[columnMapping.value.major] || "").trim(),
        status: status,
      };
    })
    .filter((item) => item.nisn && item.name); // Hanya kirim yang NISN & Nama tidak kosong

  if (formattedData.length === 0) {
    triggerToast("Peringatan", "Tidak ada data yang valid untuk diimport.", "warning");
    isImporting.value = false;
    return;
  }

  try {
    const response = await api.post("/api/students/import", { students: formattedData });
    triggerToast(
      "Import Berhasil",
      response.data.message || `${formattedData.length} data siswa berhasil diimport.`
    );
    closeImportModal();
    fetchData(1); // Reload data ke tabel
  } catch (error) {
    console.error(error);
    triggerToast(
      "Gagal Import",
      error.response?.data?.message ||
        "Terjadi kesalahan pada server saat import massal.",
      "error"
    );
  } finally {
    isImporting.value = false;
  }
};

const exportData = async () => {
  try {
    isExporting.value = true;
    let xlsx;
    try {
      xlsx = await import(/* @vite-ignore */ "xlsx");
    } catch (err) {
      triggerToast(
        "Library Tidak Ditemukan",
        "Silakan install library xlsx di frontend dengan perintah: npm install xlsx",
        "error"
      );
      isExporting.value = false;
      return;
    }

    // Mengambil seluruh data siswa yang difilter saat ini
    const params = {
      page: 1,
      per_page: 999999, // Angka besar agar semua data terambil
    };
    if (searchQuery.value) params.search = searchQuery.value;
    if (filterGrade.value !== "semua") params.grade = filterGrade.value;
    if (filterMajor.value !== "semua") params.major = filterMajor.value;
    if (filterStatus.value !== "semua") params.status = filterStatus.value;

    const response = await api.get("/api/students", { params });
    const dataToExport = response.data.data.map((student) => ({
      NISN: student.nisn || "-",
      "Nama Lengkap": student.name || "-",
      "Jenis Kelamin": student.gender || "-",
      "Tingkat Kelas": student.grade || "-",
      Jurusan: student.major || "-",
      Rombel: student.school_class ? student.school_class.name : "-",
      Status: student.status === "aktif" ? "Aktif" : "Alumni",
    }));

    if (dataToExport.length === 0) {
      triggerToast("Peringatan", "Tidak ada data untuk dieksport.", "warning");
      isExporting.value = false;
      return;
    }

    const worksheet = xlsx.utils.json_to_sheet(dataToExport);
    const workbook = xlsx.utils.book_new();
    xlsx.utils.book_append_sheet(workbook, worksheet, "Data Siswa");

    // Generate nama file dengan tanggal
    const date = new Date();
    const dateStr = `${date.getFullYear()}${(date.getMonth() + 1)
      .toString()
      .padStart(2, "0")}${date.getDate().toString().padStart(2, "0")}`;
    xlsx.writeFile(workbook, `Data_Siswa_${dateStr}.xlsx`);

    triggerToast("Berhasil", "Data siswa berhasil dieksport ke Excel.");
  } catch (error) {
    console.error("Gagal export data", error);
    triggerToast("Gagal Export", "Terjadi kesalahan saat mengeksport data.", "error");
  } finally {
    isExporting.value = false;
  }
};

const downloadTemplate = async () => {
  try {
    let xlsx;
    try {
      xlsx = await import(/* @vite-ignore */ "xlsx");
    } catch (err) {
      triggerToast(
        "Library Tidak Ditemukan",
        "Silakan install library xlsx di frontend dengan perintah: npm install xlsx",
        "error"
      );
      return;
    }

    const templateData = [
      {
        NISN: "0051234567",
        "Nama Lengkap": "Budi Santoso",
        "Jenis Kelamin": "L",
        "Tingkat Kelas": "X",
        Jurusan: "MIPA",
        Status: "Aktif",
      },
      {
        NISN: "0051234568",
        "Nama Lengkap": "Siti Aminah",
        "Jenis Kelamin": "P",
        "Tingkat Kelas": "XI",
        Jurusan: "IPS",
        Status: "Aktif",
      },
    ];

    const worksheet = xlsx.utils.json_to_sheet(templateData);
    const workbook = xlsx.utils.book_new();
    xlsx.utils.book_append_sheet(workbook, worksheet, "Template Siswa");
    xlsx.writeFile(workbook, "Template_Data_Siswa.xlsx");
  } catch (error) {
    console.error(error);
    triggerToast("Gagal", "Terjadi kesalahan saat mengunduh template.", "error");
  }
};

const addEntry = async () => {
  if (!form.value.name || !form.value.nisn) {
    triggerToast("Gagal Menyimpan", "Nama dan NISN wajib diisi!", "error");
    return;
  }

  try {
    const response = await api.post("/api/students", form.value);
    fetchData(1);
    hideForm();
    triggerToast("Berhasil Ditambahkan", "Data siswa baru berhasil ditambahkan.");
  } catch (error) {
    console.error(error);
    triggerToast(
      "Gagal Menambahkan",
      error.response?.data?.message || "Terjadi kesalahan pada server",
      "error"
    );
  }
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = { ...item };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = async () => {
  if (!form.value.name || !form.value.nisn) {
    triggerToast("Gagal Menyimpan", "Nama dan NISN wajib diisi!", "error");
    return;
  }

  try {
    const response = await api.put(`/api/students/${form.value.id}`, form.value);
    const index = studentsList.value.findIndex((s) => s.id === form.value.id);
    if (index !== -1) {
      studentsList.value[index] = response.data.data;
    }
    hideForm();
    triggerToast("Perubahan Disimpan", "Data siswa berhasil diperbarui.");
  } catch (error) {
    console.error(error);
    triggerToast(
      "Gagal Memperbarui",
      error.response?.data?.message || "Terjadi kesalahan pada server",
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
      await api.delete(`/api/students/${itemToDelete.value}`);
      fetchData(currentPage.value);
      triggerToast("Data Dihapus", "Data siswa berhasil dihapus dari sistem.", "info");
    } catch (error) {
      console.error(error);
      triggerToast(
        "Gagal Menghapus",
        error.response?.data?.message || "Terjadi kesalahan saat menghapus data.",
        "error"
      );
    }
  }
  isDeleteModalOpen.value = false;
  itemToDelete.value = null;
};

const cancelDelete = () => {
  isDeleteModalOpen.value = false;
  itemToDelete.value = null;
};

const selectAll = computed({
  get: () => {
    if (studentsList.value.length === 0) return false;
    return studentsList.value.every((s) => selectedStudents.value.includes(s.id));
  },
  set: (val) => {
    const currentPageIds = studentsList.value.map((s) => s.id);
    if (val) {
      // Hanya tambahkan ID di halaman ini yang belum dicentang (menyimpan state lintas halaman)
      const newIds = currentPageIds.filter((id) => !selectedStudents.value.includes(id));
      selectedStudents.value.push(...newIds);
    } else {
      // Hanya hapus centangan dari halaman yang sedang aktif
      selectedStudents.value = selectedStudents.value.filter(
        (id) => !currentPageIds.includes(id)
      );
    }
  },
});

const isIndeterminate = computed(() => {
  if (studentsList.value.length === 0) return false;
  const selectedInCurrentPage = studentsList.value.filter((s) =>
    selectedStudents.value.includes(s.id)
  ).length;
  return selectedInCurrentPage > 0 && selectedInCurrentPage < studentsList.value.length;
});

const openBulkEditModal = () => {
  bulkEditForm.value = { grade: "", major: "", school_class_id: null, status: "" };
  isBulkEditModalOpen.value = true;
  document.body.style.overflow = "hidden";
};

const closeBulkEditModal = () => {
  isBulkEditModalOpen.value = false;
  document.body.style.overflow = "";
};

const executeBulkEdit = async () => {
  const payload = {};
  if (bulkEditForm.value.grade)
    payload.grade =
      bulkEditForm.value.grade === "kosong" ? null : bulkEditForm.value.grade;
  if (bulkEditForm.value.major)
    payload.major =
      bulkEditForm.value.major === "kosong" ? null : bulkEditForm.value.major;
  if (bulkEditForm.value.school_class_id !== null)
    payload.school_class_id =
      bulkEditForm.value.school_class_id === ""
        ? null
        : bulkEditForm.value.school_class_id;
  if (bulkEditForm.value.status) payload.status = bulkEditForm.value.status;

  if (Object.keys(payload).length === 0) {
    triggerToast("Info", "Tidak ada perubahan yang dipilih.", "info");
    return;
  }

  payload.ids = selectedStudents.value;

  try {
    const response = await api.post("/api/students/bulk-update", payload);

    // Perbarui data state Vue tanpa me-reload list kembali
    selectedStudents.value.forEach((id) => {
      const student = studentsList.value.find((s) => s.id === id);
      if (student) {
        if (payload.hasOwnProperty("grade")) student.grade = payload.grade;
        if (payload.hasOwnProperty("major")) student.major = payload.major;
        if (payload.hasOwnProperty("status")) student.status = payload.status;
        if (payload.hasOwnProperty("school_class_id")) {
          if (payload.school_class_id === null) {
            student.school_class = null;
          } else {
            const sc = classesList.value.find((c) => c.id === payload.school_class_id);
            if (sc) student.school_class = sc;
          }
        }
      }
    });

    selectedStudents.value = [];
    closeBulkEditModal();
    triggerToast(
      "Berhasil",
      response.data?.message || "Data siswa terpilih berhasil diperbarui.",
      "success"
    );
  } catch (error) {
    console.error(error);
    triggerToast(
      "Gagal",
      error.response?.data?.message || "Terjadi kesalahan saat memperbarui data massal.",
      "error"
    );
  }
};

const confirmBulkDelete = () => {
  isBulkDeleteModalOpen.value = true;
};

const cancelBulkDelete = () => {
  isBulkDeleteModalOpen.value = false;
};

const executeBulkDelete = async () => {
  isBulkDeleteModalOpen.value = false;
  try {
    const response = await api.post("/api/students/bulk-delete", {
      ids: selectedStudents.value,
    });
    fetchData(currentPage.value);
    selectedStudents.value = [];
    triggerToast(
      "Berhasil",
      response.data?.message || "Data terpilih berhasil dihapus.",
      "success"
    );
  } catch (error) {
    console.error(error);
    triggerToast(
      "Gagal",
      error.response?.data?.message || "Terjadi kesalahan saat menghapus data massal.",
      "error"
    );
  }
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8">
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
      <div class="mt-4 flex flex-col sm:flex-row gap-2 sm:gap-3">
        <button
          @click="exportData"
          :disabled="isExporting"
          class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <PhSpinner v-if="isExporting" class="w-5 h-5 mr-2 animate-spin" />
          <PhDownloadSimple v-else class="w-5 h-5 mr-2" />
          Export Data
        </button>
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

    <!-- Banner Peringatan Kekurangan Data Relasi -->
    <div
      v-if="!isLoadingData && (classesList.length === 0 || majors.length === 0)"
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
            Perhatian: Data Relasi Belum Lengkap
          </h3>
          <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
            <p>
              Meskipun bersifat opsional, sangat disarankan untuk melengkapi data berikut
              agar penempatan siswa lebih terstruktur:
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
              <li v-if="classesList.length === 0">
                Data Kelas (Rombel) masih kosong.
                <router-link
                  to="/admin/data-kelas"
                  class="font-bold underline hover:text-yellow-600"
                  >Tambah Kelas</router-link
                >
              </li>
            </ul>
          </div>
        </div>
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
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6 overflow-y-auto"
        @click="closeImportModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg transform transition-all my-auto"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 rounded-t-xl"
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
          <div class="p-6" v-if="!showMapping">
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

            <div class="mt-4 flex justify-center">
              <button
                @click="downloadTemplate"
                class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium underline transition-colors"
              >
                <PhDownloadSimple class="w-4 h-4 mr-1.5" />
                Unduh Template Excel Kosong
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
          <!-- UI Mapping Kolom -->
          <div class="p-6" v-else>
            <div class="mb-4">
              <h4 class="text-lg font-bold text-gray-800 dark:text-white">
                Sesuaikan Kolom (Mapping)
              </h4>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Pilih kolom dari Excel Anda yang sesuai dengan data sistem.
              </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div v-for="field in mappingFields" :key="field.key" class="relative">
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >
                  {{ field.label }}
                  <span v-if="field.required" class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <button
                    type="button"
                    @click="mappingDropdowns[field.key] = !mappingDropdowns[field.key]"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-left focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex justify-between items-center transition-colors"
                    :class="
                      columnMapping[field.key]
                        ? 'text-gray-900 dark:text-white'
                        : 'text-gray-500 dark:text-gray-400'
                    "
                  >
                    <span class="truncate">{{
                      columnMapping[field.key] || "-- Pilih Kolom --"
                    }}</span>
                    <PhCaretDown
                      class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                      :class="{ 'rotate-180': mappingDropdowns[field.key] }"
                    />
                  </button>

                  <div
                    v-if="mappingDropdowns[field.key]"
                    @click="mappingDropdowns[field.key] = false"
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
                      v-if="mappingDropdowns[field.key]"
                      class="absolute top-full left-0 z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-xl max-h-48 overflow-y-auto custom-scrollbar"
                    >
                      <ul class="py-1 text-sm">
                        <li
                          class="px-4 py-2 hover:bg-blue-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 cursor-pointer"
                          @click="
                            columnMapping[field.key] = '';
                            mappingDropdowns[field.key] = false;
                          "
                        >
                          -- Pilih Kolom --
                        </li>
                        <li
                          v-for="header in excelHeaders"
                          :key="header"
                          class="px-4 py-2 hover:bg-blue-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 cursor-pointer"
                          @click="
                            columnMapping[field.key] = header;
                            mappingDropdowns[field.key] = false;
                          "
                        >
                          {{ header }}
                        </li>
                      </ul>
                    </div>
                  </Transition>
                </div>
              </div>
            </div>

            <div
              class="mt-8 flex justify-end gap-3 border-t border-gray-100 dark:border-slate-700 pt-4"
            >
              <button
                @click="showMapping = false"
                class="px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700"
              >
                Kembali
              </button>
              <button
                @click="processImport"
                :disabled="isImporting"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md flex items-center"
              >
                <PhSpinner v-if="isImporting" class="w-4 h-4 mr-2 animate-spin" />
                {{ isImporting ? "Memproses..." : "Proses Import" }}
              </button>
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
                    <option value="">-- Kosong --</option>
                    <option v-for="grade in grades" :key="grade" :value="grade">
                      {{ grade }}
                    </option>
                  </select>
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Rombel / Grup</label
                  >
                  <select
                    v-model="form.school_class_id"
                    :disabled="isLoadingData || classesList.length === 0"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option :value="null">Pilih Kelas (Opsional)</option>
                    <option v-if="isLoadingData" :value="null" disabled>
                      -- Memuat Data... --
                    </option>
                    <option v-else-if="classesList.length === 0" :value="null" disabled>
                      -- Data Kelas Kosong --
                    </option>
                    <option v-for="cls in classesList" :key="cls.id" :value="cls.id">
                      {{ cls.grade }} {{ cls.major }} - {{ cls.name }}
                    </option>
                  </select>
                  <p
                    v-if="!isLoadingData && classesList.length === 0"
                    class="mt-1 text-xs text-red-500"
                  >
                    * Anda belum memiliki
                    <router-link
                      to="/admin/data-kelas"
                      class="text-blue-600 dark:text-blue-400 hover:underline cursor-pointer"
                      @click="hideForm"
                      >Data Kelas</router-link
                    >.
                  </p>
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Jurusan / Peminatan</label
                  >
                  <select
                    v-model="form.major"
                    :disabled="isLoadingData || majors.length === 0"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="">-- Kosong --</option>
                    <option v-if="isLoadingData" value="" disabled>
                      -- Memuat Data... --
                    </option>
                    <option v-else-if="majors.length === 0" value="" disabled>
                      -- Data Jurusan Kosong --
                    </option>
                    <option v-for="major in majors" :key="major" :value="major">
                      {{ major }}
                    </option>
                  </select>
                  <p
                    v-if="!isLoadingData && majors.length === 0"
                    class="mt-1 text-xs text-red-500"
                  >
                    * Anda belum memiliki
                    <router-link
                      to="/admin/program-jurusan"
                      class="text-blue-600 dark:text-blue-400 hover:underline cursor-pointer"
                      @click="hideForm"
                      >Data Jurusan</router-link
                    >.
                  </p>
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

    <!-- Form Bulk Edit -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isBulkEditModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closeBulkEditModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
              Edit Massal ({{ selectedStudents.length }} Siswa)
            </h3>
            <button
              @click="closeBulkEditModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6">
            <p class="text-sm text-gray-500 mb-4">
              Pilih data yang ingin diubah. Kosongkan jika tidak ingin mengubah.
            </p>
            <div class="space-y-4">
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Tingkat Kelas</label
                >
                <select
                  v-model="bulkEditForm.grade"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">-- Tetap --</option>
                  <option value="kosong">Kosongkan Tingkat Kelas</option>
                  <option v-for="grade in grades" :key="grade" :value="grade">
                    {{ grade }}
                  </option>
                </select>
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Rombel / Grup</label
                >
                <select
                  v-model="bulkEditForm.school_class_id"
                  :disabled="isLoadingData || classesList.length === 0"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                >
                  <option :value="null">-- Tetap --</option>
                  <option value="">Hapus Rombel (Kosongkan)</option>
                  <option v-if="isLoadingData" value="" disabled>
                    -- Memuat Data... --
                  </option>
                  <option v-else-if="classesList.length === 0" value="" disabled>
                    -- Data Kelas Kosong --
                  </option>
                  <option v-for="cls in classesList" :key="cls.id" :value="cls.id">
                    {{ cls.grade }} {{ cls.major }} - {{ cls.name }}
                  </option>
                </select>
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Jurusan / Peminatan</label
                >
                <select
                  v-model="bulkEditForm.major"
                  :disabled="isLoadingData || majors.length === 0"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">-- Tetap --</option>
                  <option value="kosong">Kosongkan Jurusan</option>
                  <option v-if="isLoadingData" value="" disabled>
                    -- Memuat Data... --
                  </option>
                  <option v-else-if="majors.length === 0" value="" disabled>
                    -- Data Jurusan Kosong --
                  </option>
                  <option v-for="major in majors" :key="major" :value="major">
                    {{ major }}
                  </option>
                </select>
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Status Siswa</label
                >
                <select
                  v-model="bulkEditForm.status"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">-- Tetap --</option>
                  <option value="aktif">Aktif</option>
                  <option value="alumni">Alumni</option>
                </select>
              </div>
            </div>
          </div>
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              @click="closeBulkEditModal"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhXCircle class="w-5 h-5 mr-2" /> Batal
            </button>
            <button
              @click="executeBulkEdit"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhFloppyDisk class="w-5 h-5 mr-2" />
              Simpan Perubahan
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Data Table & Filters -->
    <div
      class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-visible"
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
            <option v-for="major in majors" :key="major" :value="major">
              {{ major }}
            </option>
          </select>
          <select
            v-model="filterStatus"
            class="block w-full md:w-auto md:min-w-[160px] px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Status</option>
            <option value="aktif">Aktif</option>
            <option value="alumni">Alumni</option>
          </select>
          <select
            v-model="itemsPerPage"
            @change="fetchData(1)"
            class="block w-full md:w-auto md:min-w-[120px] px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option :value="10">10 Baris</option>
            <option :value="50">50 Baris</option>
            <option :value="100">100 Baris</option>
            <option value="semua">Semua Baris</option>
          </select>
        </div>
      </div>

      <!-- Bulk Actions Bar -->
      <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-4"
      >
        <div
          v-if="selectedStudents.length > 0"
          class="bg-blue-50/90 dark:bg-blue-900/30 backdrop-blur-md border-b border-blue-200 dark:border-blue-800/50 p-3 sm:p-4 flex flex-wrap justify-between items-center gap-4 relative z-20 shadow-sm"
        >
          <div class="flex items-center gap-3">
            <span
              class="inline-flex items-center justify-center bg-blue-600 text-white w-7 h-7 rounded-full text-xs font-bold shadow-md"
            >
              {{ selectedStudents.length }}
            </span>
            <span class="text-sm font-semibold text-blue-900 dark:text-blue-200">
              Siswa Terpilih
            </span>
          </div>

          <div class="flex items-center gap-2 sm:gap-3">
            <button
              @click="openBulkEditModal"
              class="inline-flex items-center px-3 py-2 border border-blue-300 dark:border-blue-700 bg-white dark:bg-slate-800 text-sm font-semibold rounded-lg text-blue-700 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-700 focus:outline-none transition-colors shadow-sm"
            >
              <PhPencilSimple class="w-4 h-4 sm:mr-2" />
              <span class="hidden sm:inline">Edit Massal</span>
            </button>
            <button
              @click="confirmBulkDelete"
              class="inline-flex items-center px-3 py-2 border border-red-300 dark:border-red-800/50 bg-white dark:bg-slate-800 text-sm font-semibold rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-slate-700 focus:outline-none transition-colors shadow-sm"
            >
              <PhTrash class="w-4 h-4 sm:mr-2" />
              <span class="hidden sm:inline">Hapus</span>
            </button>
            <div
              class="h-6 border-l-2 border-blue-200 dark:border-blue-700/50 mx-1 sm:mx-2"
            ></div>
            <button
              @click="selectedStudents = []"
              class="inline-flex items-center px-2 py-2 text-sm font-medium rounded-lg text-gray-500 hover:text-gray-900 hover:bg-white dark:text-gray-400 dark:hover:text-white dark:hover:bg-slate-700 focus:outline-none transition-all"
              title="Batal Pilih"
            >
              <PhX class="w-5 h-5" />
            </button>
          </div>
        </div>
      </Transition>

      <div class="overflow-x-auto relative z-10">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr
              class="bg-gray-50 dark:bg-slate-700/50 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              <th class="px-6 py-4 w-12 text-center">
                <input
                  type="checkbox"
                  v-model="selectAll"
                  .indeterminate="isIndeterminate"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4"
                />
              </th>
              <th class="px-6 py-4">NISN</th>
              <th class="px-6 py-4">Nama</th>
              <th class="px-6 py-4">L/P</th>
              <th class="px-6 py-4">Kelas</th>
              <th class="px-6 py-4">Jurusan</th>
              <th class="px-6 py-4">Rombel</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            <template v-if="isLoadingData">
              <tr v-for="i in 5" :key="'skeleton-' + i" class="animate-pulse">
                <td class="px-6 py-4 text-center">
                  <div
                    class="h-4 w-4 bg-gray-200 dark:bg-slate-600 rounded mx-auto"
                  ></div>
                </td>
                <td class="px-6 py-4">
                  <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-24"></div>
                </td>
                <td class="px-6 py-4">
                  <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-32"></div>
                </td>
                <td class="px-6 py-4">
                  <div class="h-6 bg-gray-200 dark:bg-slate-600 rounded w-6"></div>
                </td>
                <td class="px-6 py-4">
                  <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-8"></div>
                </td>
                <td class="px-6 py-4">
                  <div class="h-6 bg-gray-200 dark:bg-slate-600 rounded-full w-16"></div>
                </td>
                <td class="px-6 py-4">
                  <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-20"></div>
                </td>
                <td class="px-6 py-4">
                  <div class="h-6 bg-gray-200 dark:bg-slate-600 rounded-full w-12"></div>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-1">
                    <div class="h-7 w-7 bg-gray-200 dark:bg-slate-600 rounded-md"></div>
                    <div class="h-7 w-7 bg-gray-200 dark:bg-slate-600 rounded-md"></div>
                  </div>
                </td>
              </tr>
            </template>
            <tr v-else-if="studentsList.length === 0">
              <td
                colspan="9"
                class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
              >
                <PhGraduationCap
                  class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-500 mb-3"
                />
                <p>Tidak ada data siswa yang ditemukan.</p>
              </td>
            </tr>
            <tr
              v-else
              v-for="student in studentsList"
              :key="student.id"
              class="hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors group"
            >
              <td class="px-6 py-4 text-center">
                <input
                  type="checkbox"
                  :value="student.id"
                  v-model="selectedStudents"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4"
                />
              </td>
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
                {{ student.grade || "-" }}
              </td>
              <td class="px-6 py-4">
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300"
                >
                  {{ student.major || "-" }}
                </span>
              </td>
              <td
                class="px-6 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300"
              >
                {{ student.school_class ? student.school_class.name : "-" }}
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

      <!-- Pagination Controls -->
      <div
        v-if="totalPages > 1"
        class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm rounded-b-2xl"
      >
        <span class="text-gray-500 dark:text-gray-400">
          Menampilkan
          <span class="font-medium text-gray-900 dark:text-white">{{
            paginationMeta.from || 0
          }}</span>
          -
          <span class="font-medium text-gray-900 dark:text-white">{{
            paginationMeta.to || 0
          }}</span>
          dari
          <span class="font-medium text-gray-900 dark:text-white">{{
            paginationMeta.total || 0
          }}</span>
          data
        </span>
        <div class="flex gap-2">
          <button
            @click="fetchData(currentPage - 1)"
            :disabled="currentPage === 1 || isLoadingData"
            class="px-3 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors"
          >
            Sebelumnya
          </button>
          <button
            @click="fetchData(currentPage + 1)"
            :disabled="currentPage === totalPages || isLoadingData"
            class="px-3 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors"
          >
            Selanjutnya
          </button>
        </div>
      </div>
    </div>

    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Data Siswa"
      message="Yakin ingin menghapus data siswa ini secara permanen dari sistem?"
      @confirm="confirmDelete"
      @cancel="cancelDelete"
    />

    <ConfirmModal
      :isOpen="isBulkDeleteModalOpen"
      title="Hapus Massal Siswa"
      :message="`Yakin ingin menghapus ${selectedStudents.length} data siswa ini secara permanen dari sistem?`"
      @confirm="executeBulkDelete"
      @cancel="cancelBulkDelete"
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
