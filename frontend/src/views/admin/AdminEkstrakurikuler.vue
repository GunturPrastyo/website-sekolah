<script setup>
import { ref, computed, onMounted } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhUsers,
  PhClock,
  PhX,
  PhCaretDown,
  PhCheck,
} from "@phosphor-icons/vue";
import ImageUploader from "@/components/admin/ImageUploader.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";
import RichTextEditor from "@/components/RichTextEditor.vue";
import api from "@/api/index.js";

const ekskulList = ref([]);

const categories = ref([
  { id: "olahraga", name: "Olahraga" },
  { id: "seni-budaya", name: "Seni & Budaya" },
  { id: "akademik", name: "Akademik & Sains" },
  { id: "kepemimpinan", name: "Kepemimpinan" },
]);

const form = ref({
  id: null,
  name: "",
  category: "",
  image: "",
  schedule: "",
  story: "",
  pembina: "",
  members: 0,
  socials: {
    ig: "",
    yt: "",
    email: "",
  },
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

const fetchData = async () => {
  try {
    const response = await api.get("/api/extracurriculars");
    ekskulList.value = response.data.data;
  } catch (error) {
    console.error("Gagal mengambil data ekstrakurikuler:", error);
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

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

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
  const isInUse = ekskulList.value.some((item) => item.category === categoryToDelete.id);

  if (isInUse) {
    triggerToast(
      "Gagal",
      `Kategori "${categoryToDelete.name}" sedang digunakan oleh data ekstrakurikuler!`,
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

const resetForm = () => {
  form.value = {
    id: null,
    name: "",
    category: "",
    image: "",
    schedule: "",
    story: "",
    pembina: "",
    members: 0,
    socials: { ig: "", yt: "", email: "" },
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
    await api.post("/api/extracurriculars", form.value);
    await fetchData();
    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast(
      "Berhasil Ditambahkan",
      "Data ekstrakurikuler baru telah ditambahkan ke sistem."
    );
    resetForm();
  } catch (error) {
    console.error("Gagal menambahkan ekstrakurikuler:", error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat menyimpan data.", "error");
  }
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = {
    ...item,
    socials: { ...(item.socials || { ig: "", yt: "", email: "" }) },
  };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = async () => {
  if (!form.value.name || !form.value.category) {
    triggerToast("Gagal Menyimpan", "Nama dan Kategori wajib diisi!", "error");
    return;
  }

  try {
    await api.put(`/api/extracurriculars/${form.value.id}`, form.value);
    await fetchData();
    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast("Perubahan Disimpan", "Data ekstrakurikuler berhasil diperbarui.");
    resetForm();
  } catch (error) {
    console.error("Gagal mengupdate ekstrakurikuler:", error);
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
      await api.delete(`/api/extracurriculars/${itemToDelete.value}`);
      await fetchData();
      itemToDelete.value = null;
      triggerToast(
        "Data Dihapus",
        "Data ekstrakurikuler berhasil dihapus dari sistem.",
        "info"
      );
    } catch (error) {
      console.error("Gagal menghapus ekstrakurikuler:", error);
      triggerToast("Gagal Menghapus", "Terjadi kesalahan saat menghapus data.", "error");
    }
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredEkskul = computed(() => {
  if (!searchQuery.value) return ekskulList.value;
  const query = searchQuery.value.toLowerCase();
  return ekskulList.value.filter(
    (item) =>
      item.name.toLowerCase().includes(query) ||
      item.category.toLowerCase().includes(query)
  );
});

const getCategoryName = (id) => {
  const cat = categories.value.find((c) => c.id === id);
  return cat ? cat.name : id;
};

const stripTags = (html) => {
  if (!html) return "";
  const doc = new DOMParser().parseFromString(html, "text/html");
  return doc.body.textContent || "";
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
          Manajemen Ekstrakurikuler
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data ekstrakurikuler, pembina, dan jadwal kegiatan siswa.
        </p>
      </div>
      <button
        v-if="!isFormVisible"
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Ekstrakurikuler
      </button>
    </div>

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
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full sm:w-fit sm:min-w-[600px] md:min-w-[800px] lg:min-w-[1000px] max-w-[95vw] max-h-[90vh] flex flex-col overflow-hidden transform transition-all"
          @click.stop
        >
          <!-- Modal Header -->
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
              {{
                isEditing
                  ? "Edit Data Ekstrakurikuler"
                  : "Tambah Data Ekstrakurikuler Baru"
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
            <form id="ekskulForm" @submit.prevent="isEditing ? saveEntry() : addEntry()">
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                  <ImageUploader
                    v-model="form.image"
                    label="Gambar Utama"
                    containerClass="w-full aspect-[3/4] sm:aspect-[3/4] mx-auto"
                    imageClass="object-cover rounded-xl"
                  />
                </div>

                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Nama Ekstrakurikuler</label
                    >
                    <input
                      type="text"
                      v-model="form.name"
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Contoh: Pramuka"
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
                      >Jadwal</label
                    >
                    <input
                      type="text"
                      v-model="form.schedule"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Contoh: Jumat, 15.00 - 17.00"
                    />
                  </div>

                  <div class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Pembina</label
                    >
                    <input
                      type="text"
                      v-model="form.pembina"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Nama Guru Pembina"
                    />
                  </div>

                  <div class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Jumlah Anggota</label
                    >
                    <input
                      type="number"
                      v-model.number="form.members"
                      min="0"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="0"
                    />
                  </div>

                  <div class="md:col-span-2">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Detail Ekstrakurikuler</label
                    >
                    <RichTextEditor
                      v-model="form.story"
                      placeholder="Cerita lengkap atau detail ekstrakurikuler..."
                    />
                  </div>

                  <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Instagram</label
                      >
                      <input
                        type="text"
                        v-model="form.socials.ig"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                        placeholder="@username"
                      />
                    </div>
                    <div>
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >YouTube</label
                      >
                      <input
                        type="text"
                        v-model="form.socials.yt"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Nama Channel"
                      />
                    </div>
                    <div>
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Email</label
                      >
                      <input
                        type="email"
                        v-model="form.socials.email"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                        placeholder="email@sekolah.com"
                      />
                    </div>
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
              form="ekskulForm"
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

    <!-- List -->
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
          placeholder="Cari nama ekstrakurikuler atau kategori..."
          class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        />
      </div>

      <div
        v-if="filteredEkskul.length === 0"
        class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
      >
        <p>Tidak ada ekstrakurikuler yang ditemukan.</p>
      </div>

      <div
        v-else
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
      >
        <div
          v-for="ekskul in filteredEkskul"
          :key="ekskul.id"
          class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm flex flex-col overflow-hidden relative group"
        >
          <div
            class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 p-1 rounded-lg border border-gray-100 dark:border-slate-700 z-10"
          >
            <button
              @click="startEdit(ekskul)"
              class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md"
            >
              <PhPencilSimple class="w-4 h-4" />
            </button>
            <button
              @click="deleteEntry(ekskul.id)"
              class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md"
            >
              <PhTrash class="w-4 h-4" />
            </button>
          </div>

          <div class="w-full aspect-[4/3] bg-gray-100 dark:bg-slate-700 relative">
            <img
              v-if="ekskul.image"
              :src="ekskul.image"
              class="w-full h-full object-cover"
            />
            <div
              class="absolute bottom-3 left-3 bg-blue-600/90 backdrop-blur-sm px-2.5 py-1 text-white text-[10px] font-bold uppercase tracking-wider rounded"
            >
              {{ getCategoryName(ekskul.category) }}
            </div>
          </div>

          <div class="p-4 flex flex-col flex-1">
            <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-1">
              {{ ekskul.name }}
            </h4>
            <p class="text-sm text-blue-600 dark:text-blue-400 mb-3 flex items-center">
              <PhClock class="w-4 h-4 mr-1" /> {{ ekskul.schedule || "-" }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 flex-1 mb-4">
              {{ stripTags(ekskul.story) }}
            </p>

            <div
              class="flex items-center justify-between border-t border-gray-100 dark:border-slate-700 pt-3"
            >
              <span
                class="text-xs text-gray-500 dark:text-gray-400 font-medium truncate pr-2"
                :title="ekskul.pembina"
              >
                {{ ekskul.pembina || "Belum ada pembina" }}
              </span>
              <span
                class="flex items-center text-xs font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded"
              >
                <PhUsers class="w-3.5 h-3.5 mr-1" /> {{ ekskul.members }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Data Ekstrakurikuler"
      message="Yakin ingin menghapus data ekstrakurikuler ini?"
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
