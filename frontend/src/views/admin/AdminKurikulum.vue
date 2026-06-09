<script setup>
import { ref, computed, onMounted, nextTick, onBeforeUpdate } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhBookOpen,
  PhX,
  PhHeart,
  PhGlobeHemisphereWest,
  PhGlobe,
  PhUsers,
  PhUserCheck,
  PhLightbulb,
  PhPalette,
  PhBook,
  PhLightning,
  PhCaretDown,
  PhCheck,
  PhStar,
  PhHandshake,
  PhHandsClapping,
  PhPlant,
  PhRecycle,
  PhScales,
  PhShieldCheck,
  PhBrain,
  PhTree,
  PhHandHeart,
  PhPerson,
  PhUsersFour,
  PhHandsPraying,
  PhSparkle,
} from "@phosphor-icons/vue";
import IconPicker, { educationIcons } from "@/components/IconPicker.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";
import api from "@/api/index.js";

const grades = [
  { id: "10", name: "Kelas X (Fase E)" },
  { id: "11", name: "Kelas XI (Fase F)" },
  { id: "12", name: "Kelas XII (Fase F)" },
];

const majors = ref([{ id: null, name: "Umum (Fase E)" }]);

const categories = ref(["Muatan Nasional (Wajib)", "Muatan Pilihan (Fase E)"]);

const showNewCategoryInput = ref(false);
const newCategoryName = ref("");
const isCategoryDropdownOpen = ref(false);
const editingCategoryIndex = ref(null);
const editingCategoryName = ref("");

const selectCategory = (category) => {
  if (category === "ADD_NEW") {
    showNewCategoryInput.value = true;
    form.value.category = "";
  } else {
    form.value.category = category;
  }
  isCategoryDropdownOpen.value = false;
};

const addNewCategory = () => {
  const cat = newCategoryName.value.trim();
  if (cat) {
    if (!categories.value.includes(cat)) {
      categories.value.push(cat);
    }
    form.value.category = cat;
    showNewCategoryInput.value = false;
    newCategoryName.value = "";
  } else {
    triggerToast("Gagal", "Nama kategori baru tidak boleh kosong!", "error");
  }
};

const cancelNewCategory = () => {
  showNewCategoryInput.value = false;
  newCategoryName.value = "";
  form.value.category = "";
};

const startEditCategory = (index, category) => {
  editingCategoryIndex.value = index;
  editingCategoryName.value = category;
};

const saveEditCategory = (index) => {
  const newName = editingCategoryName.value.trim();
  if (newName) {
    const oldName = categories.value[index];
    subjectList.value.forEach((subject) => {
      if (subject.category === oldName) {
        subject.category = newName;
      }
    });
    if (form.value.category === oldName) {
      form.value.category = newName;
    }
    categories.value[index] = newName;
    editingCategoryIndex.value = null;
    editingCategoryName.value = "";
    triggerToast("Berhasil", "Nama kategori berhasil diperbarui.", "success");
  } else {
    triggerToast("Gagal", "Nama kategori tidak boleh kosong!", "error");
  }
};

const cancelEditCategory = () => {
  editingCategoryIndex.value = null;
  editingCategoryName.value = "";
};

const handleDeleteCategory = (index) => {
  const categoryToDelete = categories.value[index];
  const isInUse = subjectList.value.some((s) => s.category === categoryToDelete);

  if (isInUse) {
    triggerToast(
      "Gagal",
      `Kategori "${categoryToDelete}" sedang digunakan oleh mata pelajaran!`,
      "error"
    );
    return;
  }

  if (
    window.confirm(`Apakah Anda yakin ingin menghapus kategori '${categoryToDelete}'?`)
  ) {
    categories.value.splice(index, 1);
    if (form.value.category === categoryToDelete) {
      form.value.category = "";
    }
    triggerToast(
      "Kategori Dihapus",
      `Kategori "${categoryToDelete}" berhasil dihapus.`,
      "info"
    );
  }

  isCategoryDropdownOpen.value = false;
};

// Konfigurasi Ikon Cadangan (jika tidak ada di IconPicker)
const fallbackIcons = {
  PhHeart,
  PhGlobeHemisphereWest,
  PhGlobe,
  PhUsers,
  PhUserCheck,
  PhLightbulb,
  PhPalette,
  PhBookOpen,
  PhBook,
  PhLightning,
  PhStar,
  PhHandshake,
  PhHandsClapping,
  PhPlant,
  PhRecycle,
  PhScales,
  PhShieldCheck,
  PhBrain,
  PhTree,
  PhHandHeart,
  PhPerson,
  PhUsersFour,
  PhHandsPraying,
  PhSparkle,
};

const getIconComponent = (iconName) => {
  if (educationIcons && educationIcons[iconName]) return educationIcons[iconName];
  return fallbackIcons[iconName] || PhBookOpen;
};

const getDarkColorClass = (colorClass) => {
  if (colorClass && colorClass.startsWith("#")) return "text-white";
  const colorMap = {
    "text-slate-500": "bg-slate-600 dark:bg-slate-500 text-white",
    "text-blue-500": "bg-blue-600 dark:bg-blue-500 text-white",
    "text-red-500": "bg-red-600 dark:bg-red-500 text-white",
    "text-green-500": "bg-green-600 dark:bg-green-500 text-white",
    "text-yellow-500": "bg-yellow-500 dark:bg-yellow-600 text-white",
    "text-purple-500": "bg-purple-600 dark:bg-purple-500 text-white",
    "text-orange-500": "bg-orange-500 dark:bg-orange-600 text-white",
    "text-pink-500": "bg-pink-600 dark:bg-pink-500 text-white",
    "text-teal-500": "bg-teal-600 dark:bg-teal-500 text-white",
    "text-gray-500": "bg-gray-600 dark:bg-gray-500 text-white",
    "text-emerald-500": "bg-emerald-600 dark:bg-emerald-500 text-white",
    "text-cyan-500": "bg-cyan-600 dark:bg-cyan-500 text-white",
    "text-indigo-500": "bg-indigo-600 dark:bg-indigo-500 text-white",
    "text-rose-500": "bg-rose-600 dark:bg-rose-500 text-white",
  };
  return colorMap[colorClass] || "bg-blue-600 dark:bg-blue-500 text-white";
};

// State Profil Pelajar Pancasila
const pppData = ref({
  title: "Profil Pelajar Pancasila",
  description:
    "Kurikulum kami berfokus pada pembentukan karakter siswa yang berlandaskan 6 dimensi Profil Pelajar Pancasila.",
  dimensions: [],
});

const isPPPModalVisible = ref(false);
const tempPPPData = ref({});

const dimensionRefs = ref([]);

onBeforeUpdate(() => {
  dimensionRefs.value = [];
});

const subjectList = ref([]);

const form = ref({
  id: null,
  grade: "10",
  program_id: null,
  category: "Muatan Nasional (Wajib)",
  name: "",
  icon: "PhBook",
  color: "text-blue-500",
  desc: "",
  topics: "",
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

const openPPPModal = () => {
  tempPPPData.value = JSON.parse(JSON.stringify(pppData.value));
  isPPPModalVisible.value = true;
  document.body.style.overflow = "hidden";
};

const closePPPModal = () => {
  isPPPModalVisible.value = false;
  document.body.style.overflow = "";
};

const removeDimension = (index) => {
  tempPPPData.value.dimensions.splice(index, 1);
};

const addDimension = async () => {
  tempPPPData.value.dimensions.push({
    id: Date.now(),
    name: "",
    desc: "",
    icon: "PhHeart",
    color: "text-blue-500",
  });

  await nextTick();

  const lastDimensionEl = dimensionRefs.value[dimensionRefs.value.length - 1];
  if (lastDimensionEl) {
    lastDimensionEl.scrollIntoView({ behavior: "smooth", block: "center" });
  }
};

const fetchData = async () => {
  try {
    const [subjectsRes, pppRes, programsRes] = await Promise.all([
      api.get("/api/curriculum-subjects"),
      api.get("/api/pancasila-profile"),
      api.get("/api/programs"),
    ]);
    subjectList.value = subjectsRes.data.data;
    pppData.value = pppRes.data.data;

    const programsData = programsRes.data.data.map((p) => ({
      id: p.id,
      name: p.title,
    }));
    majors.value = [{ id: null, name: "Umum (Fase E)" }, ...programsData];
  } catch (error) {
    console.error("Gagal mengambil data kurikulum:", error);
    triggerToast(
      "Gagal Memuat Data",
      "Terjadi kesalahan saat memuat data dari server.",
      "error"
    );
  }
};

onMounted(() => {
  fetchData();
});

const savePPPData = async () => {
  if (!tempPPPData.value.title.trim() || !tempPPPData.value.description.trim()) {
    triggerToast("Gagal Menyimpan", "Judul dan deskripsi utama wajib diisi!", "error");
    return;
  }
  try {
    await api.post("/api/pancasila-profile", tempPPPData.value);
    await fetchData();
    closePPPModal();
    triggerToast("Profil Disimpan", "Data Profil Pelajar Pancasila berhasil diperbarui.");
  } catch (error) {
    console.error("Gagal menyimpan Profil Pelajar Pancasila:", error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat menyimpan data.", "error");
  }
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
    grade: "10",
    program_id: null,
    category: "Muatan Nasional (Wajib)",
    name: "",
    icon: "PhBook",
    color: "text-blue-500",
    desc: "",
    topics: "",
  };
  isEditing.value = false;
  showNewCategoryInput.value = false;
  newCategoryName.value = "";
  isCategoryDropdownOpen.value = false;
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const addEntry = async () => {
  if (!form.value.name || !form.value.category) {
    triggerToast("Gagal Menyimpan", "Nama dan Kategori wajib diisi!", "error");
    return;
  }

  try {
    await api.post("/api/curriculum-subjects", form.value);
    await fetchData();
    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast(
      "Berhasil Ditambahkan",
      "Data mata pelajaran baru telah ditambahkan ke sistem."
    );
    resetForm();
  } catch (error) {
    console.error("Gagal menambahkan mata pelajaran:", error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat menyimpan data.", "error");
  }
};

const startEdit = (subject) => {
  isEditing.value = true;
  form.value = { ...subject };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = async () => {
  if (!form.value.name || !form.value.category) {
    triggerToast("Gagal Menyimpan", "Nama dan Kategori wajib diisi!", "error");
    return;
  }

  try {
    await api.put(`/api/curriculum-subjects/${form.value.id}`, form.value);
    await fetchData();
    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast("Perubahan Disimpan", "Data mata pelajaran berhasil diperbarui.");
    resetForm();
  } catch (error) {
    console.error("Gagal mengupdate mata pelajaran:", error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat memperbarui data.", "error");
  }
};

const hideForm = () => {
  resetForm();
  isFormVisible.value = false;
  document.body.style.overflow = "";
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = async () => {
  if (itemToDelete.value !== null) {
    try {
      await api.delete(`/api/curriculum-subjects/${itemToDelete.value}`);
      await fetchData();
      itemToDelete.value = null;
      triggerToast(
        "Data Dihapus",
        "Data mata pelajaran berhasil dihapus dari sistem.",
        "info"
      );
    } catch (error) {
      console.error("Gagal menghapus mata pelajaran:", error);
      triggerToast("Gagal Menghapus", "Terjadi kesalahan saat menghapus data.", "error");
    }
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredAndGroupedSubjects = computed(() => {
  let filtered = subjectList.value;

  if (filterGrade.value !== "semua") {
    filtered = filtered.filter((s) => s.grade === filterGrade.value);
  }
  if (filterMajor.value !== "semua") {
    filtered = filtered.filter((s) => s.program_id === filterMajor.value);
  }
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(
      (s) =>
        s.name.toLowerCase().includes(query) || s.category.toLowerCase().includes(query)
    );
  }

  const grouped = {};
  filtered.forEach((s) => {
    if (!grouped[s.category]) grouped[s.category] = [];
    grouped[s.category].push(s);
  });

  return grouped;
});

const getGradeName = (id) => {
  const grade = grades.find((g) => g.id === id);
  return grade ? grade.name : id;
};

const getMajorName = (id) => {
  const major = majors.value.find((m) => m.id === id);
  return major ? major.name : id;
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2
          class="text-3xl font-bold text-gray-800 dark:text-white"
          style="font-family: 'Oswald', sans-serif"
        >
          Manajemen Kurikulum
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data silabus dan mata pelajaran per tingkat kelas dan peminatan.
        </p>
      </div>
    </div>

    <!-- Profil Pelajar Pancasila Section -->
    <div
      class="bg-white dark:bg-slate-800 p-6 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm mb-8 relative group"
    >
      <div class="flex justify-between items-start mb-6">
        <div>
          <h3
            class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2"
          >
            {{ pppData.title }}
          </h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-2xl">
            {{ pppData.description }}
          </p>
        </div>
        <button
          @click="openPPPModal"
          class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shrink-0"
        >
          <PhPencilSimple class="w-4 h-4 mr-2" />
          Edit Profil
        </button>
      </div>

      <div
        class="grid gap-4 w-full"
        :class="{
          'grid-cols-1': pppData.dimensions.length === 1,
          'grid-cols-1 md:grid-cols-2': pppData.dimensions.length === 2,
          'grid-cols-1 md:grid-cols-2 lg:grid-cols-3':
            pppData.dimensions.length >= 3 || pppData.dimensions.length === 0,
        }"
      >
        <div
          v-for="dim in pppData.dimensions"
          :key="dim.id"
          class="p-4 rounded-xl border border-gray-100 dark:border-slate-700 flex items-start gap-4 hover:shadow-md transition-shadow bg-gray-50 dark:bg-slate-700/30"
        >
          <div
            class="w-10 h-10 shrink-0 rounded-full flex items-center justify-center shadow-sm"
            :class="getDarkColorClass(dim.color)"
            :style="
              dim.color && dim.color.startsWith('#') ? { backgroundColor: dim.color } : {}
            "
          >
            <component :is="getIconComponent(dim.icon)" class="w-5 h-5" />
          </div>
          <div>
            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
              {{ dim.name }}
            </h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
              {{ dim.desc }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Edit Profil Pelajar Pancasila -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isPPPModalVisible"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closePPPModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3
              class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2"
            >
              Edit Profil Pelajar Pancasila
            </h3>
            <button
              @click="closePPPModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>

          <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form id="pppForm" @submit.prevent="savePPPData">
              <div
                class="mb-6 space-y-4 border-b border-gray-100 dark:border-slate-700 pb-6"
              >
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Judul Seksi</label
                  >
                  <input
                    type="text"
                    v-model="tempPPPData.title"
                    required
                    placeholder="Contoh: Profil Pelajar Pancasila"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 placeholder:font-light"
                  />
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Deskripsi Singkat</label
                  >
                  <textarea
                    v-model="tempPPPData.description"
                    required
                    rows="2"
                    placeholder="Contoh: Kurikulum kami berfokus pada pembentukan karakter siswa..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 placeholder:font-light"
                  ></textarea>
                </div>
              </div>

              <div class="mb-4">
                <h4 class="font-bold text-gray-800 dark:text-white">Dimensi Karakter</h4>
              </div>

              <div class="space-y-4">
                <div
                  v-for="(dim, index) in tempPPPData.dimensions"
                  :key="dim.id"
                  :ref="(el) => (dimensionRefs[index] = el)"
                  class="p-4 bg-gray-50 dark:bg-slate-700/30 rounded-lg border border-gray-100 dark:border-slate-600"
                >
                  <div class="flex justify-between items-center mb-3">
                    <h5
                      class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                    >
                      Dimensi {{ index + 1 }}
                    </h5>
                    <button
                      type="button"
                      @click="removeDimension(index)"
                      class="text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors"
                      title="Hapus Dimensi"
                    >
                      <PhTrash class="w-4 h-4" />
                    </button>
                  </div>

                  <div class="flex flex-col gap-4">
                    <div>
                      <label
                        class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Ikon</label
                      >
                      <IconPicker v-model="dim.icon" v-model:color-value="dim.color" />
                    </div>
                    <div>
                      <label
                        class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Nama Dimensi</label
                      >
                      <input
                        type="text"
                        v-model="dim.name"
                        required
                        placeholder="Contoh: Beriman, bertakwa kepada Tuhan YME..."
                        class="w-full px-3 py-1.5 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400 placeholder:font-light"
                      />
                    </div>
                    <div>
                      <label
                        class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Deskripsi</label
                      >
                      <textarea
                        v-model="dim.desc"
                        required
                        rows="2"
                        placeholder="Masukkan deskripsi dari dimensi ini..."
                        class="w-full px-3 py-1.5 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400 placeholder:font-light"
                      ></textarea>
                    </div>
                  </div>
                </div>

                <!-- Tombol Tambah Dimensi -->
                <button
                  type="button"
                  @click="addDimension"
                  class="w-full flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200 group"
                >
                  <PhPlusCircle
                    class="w-6 h-6 mb-1 text-gray-400 group-hover:text-blue-500 transition-colors"
                  />
                  <span class="text-sm font-medium">Tambah Dimensi Baru</span>
                </button>
              </div>
            </form>
          </div>

          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              type="button"
              @click="closePPPModal"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors"
            >
              <PhXCircle class="w-5 h-5 mr-2" /> Batal
            </button>
            <button
              type="submit"
              form="pppForm"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors"
            >
              <PhFloppyDisk class="w-5 h-5 mr-2" /> Simpan Perubahan
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Modal Form Tambah/Edit Data -->
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
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all"
          @click.stop
        >
          <!-- Modal Header -->
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
              {{
                isEditing ? "Edit Data Mata Pelajaran" : "Tambah Data Mata Pelajaran Baru"
              }}
            </h3>
            <button
              @click="hideForm"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>

          <!-- Modal Body -->
          <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form
              id="kurikulumForm"
              @submit.prevent="isEditing ? saveEntry() : addEntry()"
            >
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Tingkat Kelas
                  </label>
                  <select
                    v-model="form.grade"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option v-for="grade in grades" :key="grade.id" :value="grade.id">
                      {{ grade.name }}
                    </option>
                  </select>
                </div>

                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Peminatan / Jurusan
                  </label>
                  <select
                    v-model="form.program_id"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option v-for="major in majors" :key="major.id" :value="major.id">
                      {{ major.name }}
                    </option>
                  </select>
                </div>

                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Ikon Representasi
                  </label>
                  <IconPicker v-model="form.icon" v-model:color-value="form.color" />
                </div>

                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Kategori Kurikulum
                  </label>
                  <div v-if="!showNewCategoryInput" class="relative">
                    <button
                      type="button"
                      @click="isCategoryDropdownOpen = !isCategoryDropdownOpen"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-left focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex justify-between items-center transition-colors"
                      :class="
                        form.category
                          ? 'text-gray-900 dark:text-white'
                          : 'text-gray-500 dark:text-gray-400'
                      "
                    >
                      <span class="truncate">{{
                        form.category || "Pilih kategori..."
                      }}</span>
                      <PhCaretDown
                        class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                        :class="{ 'rotate-180': isCategoryDropdownOpen }"
                      />
                    </button>

                    <div
                      v-if="isCategoryDropdownOpen"
                      @click="isCategoryDropdownOpen = false"
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
                        v-if="isCategoryDropdownOpen"
                        class="absolute top-full z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-xl max-h-60 overflow-y-auto custom-scrollbar"
                      >
                        <ul class="py-1 text-sm">
                          <li
                            v-for="(cat, index) in categories"
                            :key="index"
                            class="hover:bg-blue-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 transition-colors group"
                          >
                            <div
                              v-if="editingCategoryIndex === index"
                              class="flex items-center gap-2 w-full px-4 py-2"
                              @click.stop
                            >
                              <input
                                type="text"
                                v-model="editingCategoryName"
                                class="flex-1 px-2 py-1 text-sm border border-gray-300 dark:border-slate-600 rounded bg-white dark:bg-slate-700 focus:ring-blue-500 focus:border-blue-500"
                                @keydown.enter.prevent="saveEditCategory(index)"
                                @keydown.esc.prevent="cancelEditCategory()"
                              />
                              <button
                                type="button"
                                @click="saveEditCategory(index)"
                                class="p-1 text-green-600 hover:bg-green-100 rounded"
                                title="Simpan"
                              >
                                <PhCheck class="w-4 h-4" />
                              </button>
                              <button
                                type="button"
                                @click="cancelEditCategory()"
                                class="p-1 text-gray-500 hover:bg-gray-200 rounded"
                                title="Batal"
                              >
                                <PhX class="w-4 h-4" />
                              </button>
                            </div>
                            <div
                              v-else
                              class="flex items-center justify-between w-full px-4 py-2.5 cursor-pointer"
                              @click="selectCategory(cat)"
                            >
                              <span class="truncate pr-2">{{ cat }}</span>
                              <div
                                class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 shrink-0"
                                @click.stop
                              >
                                <button
                                  type="button"
                                  @click="startEditCategory(index, cat)"
                                  class="p-1 text-blue-600 hover:bg-blue-100 rounded transition-colors"
                                  title="Edit Kategori"
                                >
                                  <PhPencilSimple class="w-4 h-4" />
                                </button>
                                <button
                                  type="button"
                                  @click="handleDeleteCategory(index)"
                                  class="p-1 text-red-600 hover:bg-red-100 rounded transition-colors"
                                  title="Hapus Kategori"
                                >
                                  <PhTrash class="w-4 h-4" />
                                </button>
                              </div>
                            </div>
                          </li>
                          <li
                            @click="selectCategory('ADD_NEW')"
                            class="px-4 py-2.5 hover:bg-blue-50 dark:hover:bg-slate-700 cursor-pointer font-semibold text-blue-600 dark:text-blue-400 border-t border-gray-100 dark:border-slate-700 transition-colors sticky bottom-0 bg-white dark:bg-slate-800"
                          >
                            + Tambah Kategori Baru...
                          </li>
                        </ul>
                      </div>
                    </Transition>
                  </div>
                  <div v-else class="flex gap-2">
                    <input
                      type="text"
                      v-model="newCategoryName"
                      class="flex-1 px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Ketik nama kategori..."
                      @keydown.enter.prevent="addNewCategory"
                    />
                    <button
                      type="button"
                      @click="addNewCategory"
                      class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                    >
                      Simpan
                    </button>
                    <button
                      type="button"
                      @click="cancelNewCategory"
                      class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 dark:bg-slate-600 dark:text-gray-300 dark:hover:bg-slate-500 transition-colors text-sm font-medium"
                    >
                      Batal
                    </button>
                  </div>
                </div>

                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Nama Mata Pelajaran
                  </label>
                  <input
                    type="text"
                    v-model="form.name"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: Matematika Peminatan"
                  />
                </div>

                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Deskripsi Singkat
                  </label>
                  <textarea
                    v-model="form.desc"
                    rows="2"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Deskripsi tentang mata pelajaran"
                  ></textarea>
                </div>

                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Topik Pembelajaran
                    <span class="text-xs text-gray-500">(Pisahkan dengan koma)</span>
                  </label>
                  <textarea
                    v-model="form.topics"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: Eksponen dan Logaritma, Barisan dan Deret, Sistem Persamaan Linear"
                  ></textarea>
                </div>
              </div>
            </form>
          </div>

          <!-- Modal Footer -->
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              type="button"
              @click="hideForm"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
              <PhXCircle class="w-5 h-5 mr-2" />
              Batal
            </button>
            <button
              type="submit"
              form="kurikulumForm"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
              <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
              <PhPlusCircle v-else class="w-5 h-5 mr-2" />
              {{ isEditing ? "Simpan Perubahan" : "Simpan Data" }}
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Search & List Section -->
    <div
      class="bg-white dark:bg-slate-800 p-6 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm"
    >
      <!-- Kolom Pencarian, Filter & Tombol Tambah -->
      <div
        class="mb-8 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4"
      >
        <div class="flex flex-col md:flex-row gap-3 w-full xl:w-auto">
          <div class="relative w-full md:w-64 shrink-0">
            <div
              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
            >
              <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
            </div>
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Cari mata pelajaran..."
              class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors"
            />
          </div>
          <select
            v-model="filterGrade"
            class="w-full md:w-auto px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors cursor-pointer"
          >
            <option value="semua">Semua Kelas</option>
            <option v-for="grade in grades" :key="grade.id" :value="grade.id">
              {{ grade.name }}
            </option>
          </select>
          <select
            v-model="filterMajor"
            class="w-full md:w-auto px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors cursor-pointer"
          >
            <option value="semua">Semua Peminatan</option>
            <option v-for="major in majors" :key="major.id" :value="major.id">
              {{ major.name }}
            </option>
          </select>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto shrink-0">
          <button
            v-if="!isFormVisible"
            @click="showAddForm"
            class="inline-flex w-full sm:w-auto items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shrink-0"
          >
            <PhPlusCircle class="w-5 h-5 mr-2" />
            Tambah Mata Pelajaran
          </button>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-if="Object.keys(filteredAndGroupedSubjects).length === 0"
        class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
      >
        <p v-if="searchQuery || filterGrade !== 'semua' || filterMajor !== 'semua'">
          Tidak ada mata pelajaran yang cocok dengan pencarian atau filter yang dipilih.
        </p>
        <p v-else>Belum ada data mata pelajaran yang ditambahkan.</p>
      </div>

      <!-- List Cards -->
      <div v-else class="space-y-8">
        <div v-for="(subjects, category) in filteredAndGroupedSubjects" :key="category">
          <div class="flex items-center gap-3 mb-4">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
              {{ category }}
            </h3>
            <div class="h-px bg-gray-200 dark:bg-slate-700 flex-1"></div>
            <span
              class="text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-slate-700 px-2.5 py-1 rounded-full"
              >{{ subjects.length }} Pelajaran</span
            >
          </div>
          <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <div
              v-for="subject in subjects"
              :key="subject.id"
              class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm hover:shadow-md transition-shadow p-5 flex flex-col relative group"
            >
              <!-- Dropdown Aksi -->
              <div
                class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 backdrop-blur p-1 rounded-lg border border-gray-100 dark:border-slate-700 z-10"
              >
                <button
                  @click="startEdit(subject)"
                  class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md transition-colors"
                  title="Edit"
                >
                  <PhPencilSimple class="w-4 h-4" />
                </button>
                <button
                  @click="deleteEntry(subject.id)"
                  class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md transition-colors"
                  title="Hapus"
                >
                  <PhTrash class="w-4 h-4" />
                </button>
              </div>

              <!-- Info -->
              <div class="flex items-center gap-3 mb-3">
                <div
                  class="w-10 h-10 shrink-0 rounded-full flex items-center justify-center shadow-sm"
                  :class="getDarkColorClass(subject.color)"
                  :style="
                    subject.color && subject.color.startsWith('#')
                      ? { backgroundColor: subject.color }
                      : {}
                  "
                >
                  <component :is="getIconComponent(subject.icon)" class="w-5 h-5" />
                </div>
                <div>
                  <h4
                    class="font-bold text-gray-900 dark:text-white line-clamp-1"
                    :title="subject.name"
                  >
                    {{ subject.name }}
                  </h4>
                  <p class="text-xs font-medium text-blue-600 dark:text-blue-400">
                    {{ getGradeName(subject.grade) }} &bull;
                    {{ getMajorName(subject.program_id) }}
                  </p>
                </div>
              </div>

              <p
                class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 mb-3 flex-1 mt-1"
                :title="subject.desc"
              >
                {{ subject.desc }}
              </p>

              <div class="mt-auto border-t border-gray-100 dark:border-slate-700 pt-3">
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                  Topik Pembelajaran:
                </p>
                <div class="flex flex-wrap gap-1">
                  <span
                    v-for="(topic, idx) in subject.topics.split(',').slice(0, 3)"
                    :key="idx"
                    class="inline-block px-2 py-1 text-[10px] bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 rounded-sm truncate max-w-[120px]"
                    :title="topic.trim()"
                  >
                    {{ topic.trim() }}
                  </span>
                  <span
                    v-if="subject.topics.split(',').length > 3"
                    class="inline-block px-2 py-1 text-[10px] bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 rounded-sm"
                  >
                    +{{ subject.topics.split(",").length - 3 }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Data Mata Pelajaran"
      message="Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dikembalikan."
      @confirm="confirmDelete"
      @cancel="cancelDelete"
    />

    <!-- Notifikasi Toast -->
    <ToastNotification
      :isOpen="showToast"
      :title="toastData.title"
      :message="toastData.message"
      :type="toastData.type"
      @close="showToast = false"
    />
  </main>
</template>

<style scoped>
/* Transisi form */
.transition-all {
  overflow: hidden;
}
</style>
