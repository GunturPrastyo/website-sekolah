<script setup>
import { ref, computed, nextTick, onMounted } from "vue";
import api from "@/api/index.js";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhNewspaper,
  PhEye,
  PhTag,
  PhX,
  PhCaretDown,
  PhCheck,
} from "@phosphor-icons/vue";
import RichTextEditor from "@/components/RichTextEditor.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const categories = ref([
  { id: "akademik", name: "Akademik" },
  { id: "kegiatan", name: "Kegiatan" },
  { id: "prestasi", name: "Prestasi" },
  { id: "pengumuman", name: "Pengumuman" },
]);

const newsList = ref([]);

const form = ref({
  id: null,
  title: "",
  category: "akademik",
  images: [],
  content: "",
  tags: "",
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const currentUserId = ref(null);
const currentUserRole = ref(localStorage.getItem("user_role") || "admin");
const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");

const fetchNews = async () => {
  try {
    const response = await api.get("/api/news");
    newsList.value = response.data.data || [];
  } catch (error) {
    triggerToast("Error", "Gagal mengambil data berita", "error");
  }
};

onMounted(async () => {
  fetchNews();
  try {
    const { data } = await api.get("/api/user");
    currentUserId.value = data.id;
  } catch (error) {
    console.error("Gagal mengambil data profil user", error);
  }
});

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
  const isInUse = newsList.value.some((s) => s.category === categoryToDelete.id);

  if (isInUse) {
    triggerToast(
      "Gagal",
      `Kategori "${categoryToDelete.name}" sedang digunakan oleh data berita!`,
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

const fileInput = ref(null);
const draggedImageIndex = ref(null);

const triggerFileInput = () => {
  if (fileInput.value) fileInput.value.click();
};

const handleFileUpload = (event) => {
  const files = Array.from(event.target.files);
  files.forEach((file) => {
    const reader = new FileReader();
    reader.onload = (e) => {
      form.value.images.push(e.target.result);
    };
    reader.readAsDataURL(file);
  });
  event.target.value = "";
};

const removeImage = (index) => {
  form.value.images.splice(index, 1);
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
    category: "akademik",
    images: [],
    content: "",
    tags: "",
  };
  isEditing.value = false;
  showNewCategoryInput.value = false;
  newCategoryName.value = "";
  isCategoryDropdownOpen.value = false;
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
  nextTick(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
};

const addEntry = async () => {
  if (!form.value.title) {
    triggerToast("Gagal Menyimpan", "Judul Berita wajib diisi!", "error");
    return;
  }

  try {
    const response = await api.post("/api/news", form.value);

    if (response.data.data.status === "approved") {
      newsList.value.unshift(response.data.data);
    }

    isFormVisible.value = false;
    triggerToast(
      "Berhasil Ditambahkan",
      response.data.data.status === "pending"
        ? "Berita berhasil ditambahkan dan menunggu persetujuan (masuk ke Draft)."
        : "Data berita baru telah ditambahkan ke sistem."
    );
    resetForm();
  } catch (error) {
    triggerToast("Gagal", "Terjadi kesalahan saat menyimpan data", "error");
  }
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = { ...item, images: item.images ? [...item.images] : [] };
  isFormVisible.value = true;
  nextTick(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
};

const saveEntry = async () => {
  if (!form.value.title) {
    triggerToast("Gagal Menyimpan", "Judul Berita wajib diisi!", "error");
    return;
  }

  try {
    const response = await api.put(`/api/news/${form.value.id}`, form.value);

    if (response.data.data.status !== "approved") {
      newsList.value = newsList.value.filter((s) => s.id !== form.value.id);
      triggerToast(
        "Perubahan Disimpan",
        "Berita kembali ke status Draft/Pending karena telah diedit."
      );
    } else {
      const index = newsList.value.findIndex((s) => s.id === form.value.id);
      if (index !== -1) {
        newsList.value[index] = response.data.data;
      }
      triggerToast("Perubahan Disimpan", "Data berita berhasil diperbarui.");
    }

    isFormVisible.value = false;
    resetForm();
  } catch (error) {
    triggerToast(
      "Gagal",
      error.response?.data?.message || "Terjadi kesalahan saat memperbarui data",
      "error"
    );
  }
};

const hideForm = () => {
  resetForm();
  isFormVisible.value = false;
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = async () => {
  if (itemToDelete.value !== null) {
    try {
      await api.delete(`/api/news/${itemToDelete.value}`);
      newsList.value = newsList.value.filter((s) => s.id !== itemToDelete.value);
      itemToDelete.value = null;
      triggerToast("Data Dihapus", "Data berita berhasil dihapus dari sistem.", "info");
    } catch (error) {
      triggerToast(
        "Gagal",
        error.response?.data?.message || "Terjadi kesalahan saat menghapus data.",
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

const handleImageDragStart = (index, event) => {
  draggedImageIndex.value = index;
  event.dataTransfer.effectAllowed = "move";
};

const handleImageDrop = (index) => {
  if (draggedImageIndex.value === null || draggedImageIndex.value === index) return;
  const draggedItem = form.value.images.splice(draggedImageIndex.value, 1)[0];
  form.value.images.splice(index, 0, draggedItem);
  draggedImageIndex.value = null;
};

const filteredNews = computed(() => {
  const list = newsList.value || [];
  if (!searchQuery.value) return list;
  const query = searchQuery.value.toLowerCase();
  return list.filter((item) => item.title.toLowerCase().includes(query));
});

const getCategoryName = (id) => {
  const cat = categories.value.find((c) => c.id === id);
  return cat ? cat.name : id;
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
          Manajemen Berita
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data artikel, berita, dan pengumuman sekolah.
        </p>
      </div>
      <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
        <button
          v-if="!isFormVisible"
          @click="showAddForm"
          class="inline-flex w-full sm:w-auto items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shrink-0"
        >
          <PhPlusCircle class="w-5 h-5 mr-2" />
          Tambah Berita
        </button>
      </div>
    </div>

    <!-- Form Tambah/Edit Data -->
    <Transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-4"
    >
      <div
        v-if="isFormVisible"
        class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm mb-8 overflow-hidden"
      >
        <!-- Modal Header -->
        <div
          class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
        >
          <h3 class="text-xl font-bold text-gray-800 dark:text-white">
            {{ isEditing ? "Edit Data Berita" : "Tambah Data Berita Baru" }}
          </h3>
          <button
            @click="hideForm"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
          >
            <PhX class="w-6 h-6" />
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
          <form id="beritaForm" @submit.prevent="isEditing ? saveEntry() : addEntry()">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <!-- Image Uploader -->
              <div
                class="lg:col-span-1 border border-gray-200 dark:border-slate-600 rounded-xl p-4 bg-gray-50 dark:bg-slate-700/50 h-max"
              >
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3"
                >
                  Gambar Berita
                </label>

                <div v-if="form.images?.length > 0" class="mb-4">
                  <!-- Gambar Utama (Index 0) -->
                  <div
                    class="relative w-full aspect-[4/3] rounded-lg overflow-hidden mb-2 group cursor-move shadow-sm"
                    draggable="true"
                    @dragstart="handleImageDragStart(0, $event)"
                    @dragover.prevent
                    @dragenter.prevent
                    @drop="handleImageDrop(0)"
                  >
                    <img :src="form.images[0]" class="w-full h-full object-cover" />
                    <div
                      class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm"
                    >
                      Utama
                    </div>
                    <button
                      type="button"
                      @click="removeImage(0)"
                      class="absolute top-2 right-2 p-1.5 bg-red-500/90 hover:bg-red-600 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity shadow-sm"
                    >
                      <PhTrash class="w-4 h-4" />
                    </button>
                  </div>

                  <!-- Grid Gambar Lainnya -->
                  <div class="grid grid-cols-3 gap-2">
                    <div
                      v-for="(img, index) in (form.images || []).slice(1)"
                      :key="index + 1"
                      class="relative aspect-square rounded-md overflow-hidden group cursor-move shadow-sm"
                      draggable="true"
                      @dragstart="handleImageDragStart(index + 1, $event)"
                      @dragover.prevent
                      @dragenter.prevent
                      @drop="handleImageDrop(index + 1)"
                    >
                      <img :src="img" class="w-full h-full object-cover" />
                      <button
                        type="button"
                        @click="removeImage(index + 1)"
                        class="absolute top-1 right-1 p-1 bg-red-500/90 hover:bg-red-600 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity shadow-sm"
                      >
                        <PhTrash class="w-3 h-3" />
                      </button>
                    </div>
                  </div>
                </div>

                <input
                  type="file"
                  ref="fileInput"
                  multiple
                  accept="image/*"
                  class="hidden"
                  @change="handleFileUpload"
                />
                <button
                  type="button"
                  @click="triggerFileInput"
                  class="w-full py-6 border-2 border-dashed border-gray-300 dark:border-slate-500 rounded-lg flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors"
                >
                  <PhPlusCircle class="w-6 h-6 mb-2 text-gray-400" />
                  <span class="text-sm font-medium">Klik tambah gambar</span>
                </button>
                <p
                  class="text-[10px] text-gray-500 dark:text-gray-400 mt-3 text-center leading-relaxed"
                >
                  Tahan dan geser (drag & drop) gambar untuk mengubah urutan. Gambar
                  paling atas akan menjadi thumbnail utama.
                </p>
              </div>

              <!-- Form Fields -->
              <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Judul Berita</label
                  >
                  <input
                    type="text"
                    v-model="form.title"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: Peringatan Hari Guru Nasional Berlangsung Meriah"
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

                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Isi Berita</label
                  >
                  <RichTextEditor
                    v-model="form.content"
                    placeholder="Tuliskan isi lengkap berita di sini..."
                  />
                </div>

                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Tags</label
                  >
                  <div class="relative">
                    <div
                      class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                      <PhTag class="w-5 h-5 text-gray-400" />
                    </div>
                    <input
                      type="text"
                      v-model="form.tags"
                      class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Pisahkan dengan koma. Contoh: Pendidikan, Prestasi, Sekolah"
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
            type="submit"
            form="beritaForm"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
          >
            <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
            <PhPlusCircle v-else class="w-5 h-5 mr-2" />
            {{ isEditing ? "Simpan Perubahan" : "Simpan Berita" }}
          </button>
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
          placeholder="Cari judul berita..."
          class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        />
      </div>

      <div
        v-if="!filteredNews || filteredNews.length === 0"
        class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
      >
        <div
          class="mx-auto w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4"
        >
          <PhNewspaper class="w-8 h-8 text-gray-400 dark:text-gray-500" />
        </div>
        <p>Tidak ada data berita yang ditemukan.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <div
          v-for="news in filteredNews"
          :key="news.id"
          class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm flex flex-col overflow-hidden relative group"
        >
          <!-- Floating Actions -->
          <div
            class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 p-1 rounded-lg border border-gray-100 dark:border-slate-700 z-10"
          >
            <button
              v-if="
                news.user_id === currentUserId ||
                (news.author && news.author.id === currentUserId)
              "
              @click="startEdit(news)"
              class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md"
              title="Edit"
            >
              <PhPencilSimple class="w-4 h-4" />
            </button>
            <button
              v-if="
                news.user_id === currentUserId ||
                (news.author && news.author.id === currentUserId) ||
                currentUserRole === 'super_admin'
              "
              @click="deleteEntry(news.id)"
              class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md"
              title="Hapus"
            >
              <PhTrash class="w-4 h-4" />
            </button>
          </div>

          <!-- Image Thumbnail -->
          <div class="w-full aspect-[16/9] bg-gray-100 dark:bg-slate-700 relative">
            <img
              v-if="news.images?.length > 0"
              :src="news.images[0]"
              class="w-full h-full object-cover"
            />
            <div
              v-else
              class="w-full h-full flex items-center justify-center text-gray-400"
            >
              <PhNewspaper class="w-12 h-12 opacity-50" />
            </div>

            <!-- Badges -->
            <div class="absolute bottom-3 left-3 flex flex-wrap gap-2">
              <span
                class="bg-blue-600/90 backdrop-blur-sm px-2.5 py-1 text-white text-[10px] font-bold uppercase tracking-wider rounded"
              >
                {{ getCategoryName(news.category) }}
              </span>
              <span
                v-if="news.images?.length > 1"
                class="bg-gray-900/80 backdrop-blur-sm px-2 py-1 text-white text-[10px] font-bold rounded flex items-center shadow-sm"
              >
                +{{ news.images?.length - 1 }} Foto
              </span>
            </div>
          </div>

          <!-- Content Info -->
          <div class="p-5 flex flex-col flex-1">
            <div
              v-if="news.status === 'rejected' && news.rejection_note"
              class="mb-3 p-3 bg-red-50 border border-red-100 rounded-lg text-xs text-red-600 dark:bg-red-900/20 dark:border-red-800/30 dark:text-red-400"
            >
              <span class="font-bold">Alasan Ditolak:</span> {{ news.rejection_note }}
            </div>
            <h4
              class="font-bold text-gray-900 dark:text-white text-lg mb-2 leading-tight"
            >
              {{ news.title }}
            </h4>

            <div
              class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 mb-4 flex-1"
              v-html="news.content"
            ></div>

            <div
              class="flex items-center text-xs font-medium text-gray-400 dark:text-gray-500 mt-auto border-t border-gray-100 dark:border-slate-700 pt-3"
            >
              <PhEye class="w-4 h-4 mr-1" />
              {{ news.views }} Tayangan
            </div>
          </div>
        </div>
      </div>
    </div>

    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Data Berita"
      message="Yakin ingin menghapus berita ini secara permanen?"
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
