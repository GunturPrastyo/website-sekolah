<script setup>
import { ref, computed } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhTrophy,
  PhUser,
  PhCalendar,
  PhMedal,
  PhCaretDown,
  PhCheck,
  PhX,
} from "@phosphor-icons/vue";
import ImageUploader from "@/components/admin/ImageUploader.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const categories = ref([
  { id: "akademik", name: "Akademik / Sains" },
  { id: "olahraga", name: "Olahraga" },
  { id: "seni", name: "Seni & Budaya" },
  { id: "lainnya", name: "Lainnya" },
]);

const levels = [
  { id: "sekolah", name: "Tingkat Sekolah" },
  { id: "kabupaten", name: "Tingkat Kabupaten/Kota" },
  { id: "provinsi", name: "Tingkat Provinsi" },
  { id: "nasional", name: "Tingkat Nasional" },
  { id: "internasional", name: "Tingkat Internasional" },
];

const prestasiList = ref([
  {
    id: 1,
    title: "Juara 1 Olimpiade Sains Nasional (OSN) Fisika",
    studentName: "Ahmad Faisal & Tim",
    category: "akademik",
    level: "nasional",
    year: "2025",
    description:
      "Meraih medali emas pada ajang OSN bidang Fisika yang diselenggarakan di Jakarta, bersaing dengan ratusan siswa dari seluruh Indonesia.",
    image: "https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=800",
  },
  {
    id: 2,
    title: "Juara 2 Turnamen Bola Basket Pelajar",
    studentName: "Tim Basket SMAN 1",
    category: "olahraga",
    level: "provinsi",
    year: "2024",
    description:
      "Berhasil menjadi runner-up dalam kejuaraan bola basket antar pelajar SMA se-Provinsi Jawa Tengah.",
    image: "https://images.unsplash.com/photo-1546519638-68e109498ffc?q=80&w=800",
  },
  {
    id: 3,
    title: "Juara Harapan 1 Lomba Tari Kreasi",
    studentName: "Sanggar Tari Nusantara",
    category: "seni",
    level: "nasional",
    year: "2024",
    description:
      "Mendapatkan penghargaan sebagai penampil dengan kostum dan koreografi terbaik pada FLS2N di Bali.",
    image: "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800",
  },
]);

const form = ref({
  id: null,
  title: "",
  studentName: "",
  category: "akademik",
  level: "kabupaten",
  year: new Date().getFullYear().toString(),
  description: "",
  image: "",
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");

const showNewCategoryInput = ref(false);
const newCategoryName = ref("");
const isCategoryDropdownOpen = ref(false);
const editingCategoryIndex = ref(null);
const editingCategoryName = ref("");

const selectCategory = (id) => {
  if (id === "ADD_NEW") {
    showNewCategoryInput.value = true;
    form.value.category = "";
  } else {
    form.value.category = id;
  }
  isCategoryDropdownOpen.value = false;
};

const addNewCategory = () => {
  const name = newCategoryName.value.trim();
  if (name) {
    const newId = name.toLowerCase().replace(/\s+/g, "-");
    if (!categories.value.some((c) => c.id === newId)) {
      categories.value.push({ id: newId, name: name });
    }
    form.value.category = newId;
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

const startEditCategory = (index, name) => {
  editingCategoryIndex.value = index;
  editingCategoryName.value = name;
};

const saveEditCategory = (index) => {
  const newName = editingCategoryName.value.trim();
  if (newName) {
    categories.value[index].name = newName;
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
  const isInUse = prestasiList.value.some((s) => s.category === categoryToDelete.id);

  if (isInUse) {
    triggerToast(
      "Gagal",
      `Kategori "${categoryToDelete.name}" sedang digunakan oleh data prestasi!`,
      "error"
    );
    return;
  }

  if (
    window.confirm(
      `Apakah Anda yakin ingin menghapus kategori '${categoryToDelete.name}'?`
    )
  ) {
    categories.value.splice(index, 1);
    if (form.value.category === categoryToDelete.id) {
      form.value.category = "";
    }
    triggerToast(
      "Kategori Dihapus",
      `Kategori "${categoryToDelete.name}" berhasil dihapus.`,
      "info"
    );
  }

  isCategoryDropdownOpen.value = false;
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
    title: "",
    studentName: "",
    category: "akademik",
    level: "kabupaten",
    year: new Date().getFullYear().toString(),
    description: "",
    image: "",
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

const addEntry = () => {
  if (!form.value.title || !form.value.studentName) {
    triggerToast(
      "Gagal Menyimpan",
      "Judul Prestasi dan Nama Peraih wajib diisi!",
      "error"
    );
    return;
  }
  const newId =
    prestasiList.value.length > 0
      ? Math.max(...prestasiList.value.map((s) => s.id)) + 1
      : 1;
  prestasiList.value.unshift({ ...form.value, id: newId }); // Add to beginning of array

  isFormVisible.value = false;
  document.body.style.overflow = "";
  triggerToast("Berhasil Ditambahkan", "Data prestasi baru telah ditambahkan ke sistem.");
  resetForm();
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = { ...item };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = () => {
  if (!form.value.title || !form.value.studentName) {
    triggerToast(
      "Gagal Menyimpan",
      "Judul Prestasi dan Nama Peraih wajib diisi!",
      "error"
    );
    return;
  }
  const index = prestasiList.value.findIndex((s) => s.id === form.value.id);
  if (index !== -1) {
    prestasiList.value[index] = { ...form.value };
  }

  isFormVisible.value = false;
  document.body.style.overflow = "";
  triggerToast("Perubahan Disimpan", "Data prestasi berhasil diperbarui.");
  resetForm();
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

const confirmDelete = () => {
  if (itemToDelete.value !== null) {
    prestasiList.value = prestasiList.value.filter((s) => s.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast("Data Dihapus", "Data prestasi berhasil dihapus dari sistem.", "info");
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredPrestasi = computed(() => {
  if (!searchQuery.value) return prestasiList.value;
  const query = searchQuery.value.toLowerCase();
  return prestasiList.value.filter(
    (item) =>
      item.title.toLowerCase().includes(query) ||
      item.studentName.toLowerCase().includes(query) ||
      item.year.toString().includes(query)
  );
});

const getCategoryName = (id) => {
  const cat = categories.value.find((c) => c.id === id);
  return cat ? cat.name : id;
};

const getLevelName = (id) => {
  const level = levels.find((l) => l.id === id);
  return level ? level.name : id;
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
          Manajemen Prestasi
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data pencapaian dan penghargaan yang diraih oleh siswa maupun sekolah.
        </p>
      </div>
      <button
        v-if="!isFormVisible"
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Prestasi
      </button>
    </div>

    <!-- Modal Form Tambah/Edit -->
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
              {{ isEditing ? "Edit Data Prestasi" : "Tambah Data Prestasi Baru" }}
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
              id="prestasiForm"
              @submit.prevent="isEditing ? saveEntry() : addEntry()"
            >
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Image Uploader -->
                <div class="lg:col-span-1">
                  <ImageUploader
                    v-model="form.image"
                    label="Foto / Dokumentasi (Opsional)"
                    containerClass="w-full aspect-[4/3] sm:aspect-video lg:aspect-[4/3] mx-auto"
                    imageClass="object-cover rounded-xl"
                  />
                  <p
                    class="text-xs text-gray-500 dark:text-gray-400 mt-3 text-center lg:text-left max-w-sm mx-auto"
                  >
                    Dapat berupa foto penyerahan piala, sertifikat, atau aksi saat
                    perlombaan.
                  </p>
                </div>

                <!-- Form Fields -->
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="md:col-span-2">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Judul / Nama Prestasi</label
                    >
                    <input
                      type="text"
                      v-model="form.title"
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Contoh: Juara 1 Olimpiade Sains Nasional"
                    />
                  </div>

                  <div class="md:col-span-2">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Nama Peraih (Siswa / Tim)</label
                    >
                    <input
                      type="text"
                      v-model="form.studentName"
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Contoh: Ahmad Faisal atau Tim Basket Putra"
                    />
                  </div>

                  <div class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Kategori</label
                    >
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
                          getCategoryName(form.category) || "Pilih kategori..."
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
                              :key="cat.id"
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
                                @click="selectCategory(cat.id)"
                              >
                                <span class="truncate pr-2">{{ cat.name }}</span>
                                <div
                                  class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 shrink-0"
                                  @click.stop
                                >
                                  <button
                                    type="button"
                                    @click="startEditCategory(index, cat.name)"
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

                  <div class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Tingkat</label
                    >
                    <select
                      v-model="form.level"
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    >
                      <option v-for="lvl in levels" :key="lvl.id" :value="lvl.id">
                        {{ lvl.name }}
                      </option>
                    </select>
                  </div>

                  <div class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Tahun Perolehan</label
                    >
                    <input
                      type="number"
                      v-model="form.year"
                      required
                      min="1990"
                      :max="new Date().getFullYear() + 1"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Contoh: 2024"
                    />
                  </div>

                  <div class="md:col-span-2">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Deskripsi Singkat</label
                    >
                    <textarea
                      v-model="form.description"
                      rows="3"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Jelaskan secara singkat mengenai event dan pencapaiannya..."
                    ></textarea>
                  </div>
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
              <PhXCircle class="w-5 h-5 mr-2" /> Batal
            </button>
            <button
              form="prestasiForm"
              type="submit"
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

    <!-- List/Grid -->
    <div
      class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm"
    >
      <div class="mb-6 relative max-w-md">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
        </div>
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Cari judul, nama peraih, atau tahun..."
          class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        />
      </div>

      <div
        v-if="filteredPrestasi.length === 0"
        class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
      >
        <div
          class="mx-auto w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4"
        >
          <PhTrophy class="w-8 h-8 text-gray-400 dark:text-gray-500" />
        </div>
        <p>Tidak ada data prestasi yang ditemukan.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <div
          v-for="prestasi in filteredPrestasi"
          :key="prestasi.id"
          class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm flex flex-col overflow-hidden relative group"
        >
          <!-- Floating Actions -->
          <div
            class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 p-1 rounded-lg border border-gray-100 dark:border-slate-700 z-10"
          >
            <button
              @click="startEdit(prestasi)"
              class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md"
              title="Edit"
            >
              <PhPencilSimple class="w-4 h-4" />
            </button>
            <button
              @click="deleteEntry(prestasi.id)"
              class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md"
              title="Hapus"
            >
              <PhTrash class="w-4 h-4" />
            </button>
          </div>

          <!-- Image Thumbnail -->
          <div class="w-full aspect-[16/9] bg-gray-100 dark:bg-slate-700 relative">
            <img
              v-if="prestasi.image"
              :src="prestasi.image"
              class="w-full h-full object-cover"
            />
            <div
              v-else
              class="w-full h-full flex items-center justify-center text-gray-400"
            >
              <PhTrophy class="w-12 h-12 opacity-50" />
            </div>

            <!-- Badges -->
            <div class="absolute bottom-3 left-3 flex flex-wrap gap-2">
              <span
                class="bg-blue-600/90 backdrop-blur-sm px-2.5 py-1 text-white text-[10px] font-bold uppercase tracking-wider rounded"
              >
                {{ getCategoryName(prestasi.category) }}
              </span>
              <span
                class="bg-yellow-500/90 backdrop-blur-sm px-2.5 py-1 text-yellow-900 text-[10px] font-bold uppercase tracking-wider rounded flex items-center"
              >
                <PhMedal class="w-3 h-3 mr-1" />
                {{ getLevelName(prestasi.level) }}
              </span>
            </div>
          </div>

          <!-- Content Info -->
          <div class="p-5 flex flex-col flex-1">
            <div
              class="flex items-center text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 gap-3"
            >
              <span class="flex items-center text-blue-600 dark:text-blue-400">
                <PhCalendar class="w-4 h-4 mr-1" /> Tahun {{ prestasi.year }}
              </span>
              <span class="flex items-center">
                <PhUser class="w-4 h-4 mr-1" /> {{ prestasi.studentName }}
              </span>
            </div>

            <h4
              class="font-bold text-gray-900 dark:text-white text-lg mb-2 leading-tight"
            >
              {{ prestasi.title }}
            </h4>

            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 mb-2 flex-1">
              {{ prestasi.description }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Data Prestasi"
      message="Yakin ingin menghapus data prestasi ini secara permanen?"
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

<style scoped>
.transition-all {
  overflow: hidden;
}
</style>
