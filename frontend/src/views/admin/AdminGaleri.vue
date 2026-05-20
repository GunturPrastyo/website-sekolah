<script setup>
import { ref, computed, nextTick, watch, onMounted } from "vue";
import api from "@/api/index.js";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhImage,
  PhHeart,
  PhX,
  PhVideoCamera,
  PhCaretDown,
  PhCheck,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const categories = ref([
  { id: "fasilitas", name: "Fasilitas" },
  { id: "kegiatan", name: "Kegiatan" },
  { id: "ekskul", name: "Ekstrakurikuler" },
  { id: "pentas-seni", name: "Pentas Seni" },
  { id: "hut-ri", name: "HUT RI" },
]);

const galleryList = ref([]);

const form = ref({
  id: null,
  title: "",
  category: "fasilitas",
  images: [],
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const activeCategory = ref("semua");

const selectedItems = ref([]);
const isBulkDeleteModalOpen = ref(false);

const showNewCategoryInput = ref(false);
const newCategoryName = ref("");
const isCategoryDropdownOpen = ref(false);
const editingCategoryIndex = ref(null);
const editingCategoryName = ref("");

const fetchGallery = async () => {
  try {
    const response = await api.get("/api/galleries");
    galleryList.value = response.data.data;
  } catch (error) {
    triggerToast("Error", "Gagal memuat data galeri", "error");
  }
};

onMounted(() => {
  fetchGallery();
});

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
  const isInUse = galleryList.value.some((item) => item.category === categoryToDelete.id);

  if (isInUse) {
    triggerToast(
      "Gagal",
      `Kategori "${categoryToDelete.name}" sedang digunakan oleh data galeri!`,
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

// Video Profil State
const videoUrl = ref("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
const tempVideoUrl = ref("");
const isEditingVideo = ref(false);

const embedVideoUrl = computed(() => {
  if (!videoUrl.value) return "";
  let url = videoUrl.value;
  const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
  const match = url.match(regExp);
  if (match && match[2].length === 11) {
    return `https://www.youtube.com/embed/${match[2]}`;
  }
  return url;
});

const startEditVideo = () => {
  tempVideoUrl.value = videoUrl.value;
  isEditingVideo.value = true;
};

const cancelEditVideo = () => {
  isEditingVideo.value = false;
  tempVideoUrl.value = "";
};

const saveVideo = () => {
  if (!tempVideoUrl.value) {
    triggerToast("Gagal Menyimpan", "Tautan video tidak boleh kosong!", "error");
    return;
  }
  videoUrl.value = tempVideoUrl.value;
  isEditingVideo.value = false;
  triggerToast("Perubahan Disimpan", "Tautan video profil berhasil diperbarui.");
};

const deleteVideo = () => {
  videoUrl.value = "";
  triggerToast("Data Dihapus", "Tautan video profil berhasil dihapus.", "info");
};

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
    category: "fasilitas",
    images: [],
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
  if (!form.value.title || form.value.images.length === 0) {
    triggerToast("Gagal Menyimpan", "Judul Foto dan Gambar wajib diisi!", "error");
    return;
  }

  try {
    const response = await api.post("/api/galleries", form.value);
    galleryList.value.unshift(...response.data.data);

    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast(
      "Berhasil Ditambahkan",
      `${response.data.data.length} foto baru telah ditambahkan ke galeri.`
    );
    resetForm();
  } catch (error) {
    triggerToast("Gagal", "Terjadi kesalahan saat menyimpan data", "error");
  }
};

const startEdit = async (item) => {
  isEditing.value = true;
  form.value = {
    id: item.id,
    title: item.title,
    category: item.category,
    images: [item.image],
  };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = async () => {
  if (!form.value.title || form.value.images.length === 0) {
    triggerToast("Gagal Menyimpan", "Judul Foto dan Gambar wajib diisi!", "error");
    return;
  }

  try {
    const response = await api.put(`/api/galleries/${form.value.id}`, form.value);
    const newItems = response.data.data;

    const index = galleryList.value.findIndex((s) => s.id === form.value.id);
    if (index !== -1) {
      galleryList.value[index] = newItems[0];
    }

    if (newItems.length > 1) {
      galleryList.value.unshift(...newItems.slice(1));
    }

    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast("Perubahan Disimpan", "Data foto berhasil diperbarui.");
    resetForm();
  } catch (error) {
    triggerToast("Gagal", "Terjadi kesalahan saat memperbarui data", "error");
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
      await api.delete(`/api/galleries/${itemToDelete.value}`);
      galleryList.value = galleryList.value.filter((s) => s.id !== itemToDelete.value);
      itemToDelete.value = null;
      triggerToast("Data Dihapus", "Data foto berhasil dihapus dari galeri.", "info");
    } catch (error) {
      triggerToast("Gagal", "Terjadi kesalahan saat menghapus data", "error");
    }
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredGallery = computed(() => {
  if (activeCategory.value === "semua") return galleryList.value;
  return galleryList.value.filter((item) => item.category === activeCategory.value);
});

const selectAll = () => {
  const filteredIds = filteredGallery.value.map((item) => item.id);
  const allSelected = filteredIds.every((id) => selectedItems.value.includes(id));

  if (allSelected && filteredIds.length > 0) {
    selectedItems.value = selectedItems.value.filter((id) => !filteredIds.includes(id));
  } else {
    const newSelection = new Set([...selectedItems.value, ...filteredIds]);
    selectedItems.value = Array.from(newSelection);
  }
};

const confirmBulkDelete = async () => {
  if (selectedItems.value.length === 0) return;
  try {
    await api.post("/api/galleries/bulk-delete", { ids: selectedItems.value });
    galleryList.value = galleryList.value.filter(
      (item) => !selectedItems.value.includes(item.id)
    );
    triggerToast(
      "Data Dihapus",
      `${selectedItems.value.length} foto berhasil dihapus dari galeri.`,
      "info"
    );
    selectedItems.value = [];
    isBulkDeleteModalOpen.value = false;
  } catch (error) {
    triggerToast("Gagal", "Terjadi kesalahan saat menghapus data", "error");
  }
};

watch(activeCategory, () => {
  selectedItems.value = [];
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
          Manajemen Galeri
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data foto dokumentasi kegiatan sekolah.
        </p>
      </div>
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
              {{ isEditing ? "Edit Data Foto" : "Tambah Foto Baru" }}
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
            <form id="galleryForm" @submit.prevent="isEditing ? saveEntry() : addEntry()">
              <div class="flex flex-col gap-6">
                <!-- Form Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                      >Judul Foto</label
                    >
                    <input
                      type="text"
                      v-model="form.title"
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Contoh: Gedung Utama Sekolah"
                    />
                  </div>

                  <div>
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
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
                </div>

                <!-- Image Uploader -->
                <div
                  class="border border-gray-200 dark:border-slate-600 rounded-xl p-4 md:p-6 bg-gray-50 dark:bg-slate-700/50"
                >
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3"
                  >
                    Unggah Foto
                  </label>

                  <div v-if="form.images.length > 0" class="mb-4">
                    <div
                      class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3"
                    >
                      <!-- Gambar Utama (Index 0) -->
                      <div
                        class="relative col-span-2 row-span-2 rounded-lg overflow-hidden group shadow-sm aspect-[4/3]"
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
                      <div
                        v-for="(img, index) in form.images.slice(1)"
                        :key="index + 1"
                        class="relative rounded-lg overflow-hidden group shadow-sm aspect-square"
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
                    <span class="text-sm font-medium">Klik tambah foto</span>
                  </button>
                  <p
                    class="text-[10px] text-gray-500 dark:text-gray-400 mt-3 text-center leading-relaxed"
                  >
                    Anda dapat memilih lebih dari satu foto sekaligus untuk diunggah.
                  </p>
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
              form="galleryForm"
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

    <!-- Pengaturan Video Profil -->
    <div
      class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm mb-8"
    >
      <div
        class="flex items-center justify-between mb-4 border-b border-gray-100 dark:border-slate-700 pb-4"
      >
        <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
          <PhVideoCamera class="w-6 h-6 mr-2 text-blue-600" />
          Video Profil Sekolah
        </h3>
        <div class="flex items-center gap-3">
          <button
            v-if="videoUrl && !isEditingVideo"
            @click="deleteVideo"
            class="text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors flex items-center"
          >
            <PhTrash class="w-4 h-4 mr-1" />
            Hapus
          </button>
          <button
            @click="isEditingVideo ? cancelEditVideo() : startEditVideo()"
            class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors flex items-center"
          >
            <PhPencilSimple v-if="!isEditingVideo && videoUrl" class="w-4 h-4 mr-1" />
            <PhPlusCircle v-if="!isEditingVideo && !videoUrl" class="w-4 h-4 mr-1" />
            <PhXCircle v-if="isEditingVideo" class="w-4 h-4 mr-1" />
            {{ isEditingVideo ? "Batal" : videoUrl ? "Edit Tautan" : "Tambah Tautan" }}
          </button>
        </div>
      </div>

      <div class="flex flex-col gap-6">
        <!-- Input Form -->
        <div v-if="isEditingVideo" class="flex flex-col gap-4">
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
            >
              Tautan Video YouTube
            </label>
            <textarea
              v-model="tempVideoUrl"
              rows="2"
              class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
              placeholder="Contoh: https://www.youtube.com/watch?v=..."
            ></textarea>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
              Masukkan tautan video YouTube untuk ditampilkan di halaman profil. Sistem
              akan otomatis mengonversi ke format embed.
            </p>
          </div>
          <button
            @click="saveVideo"
            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors self-start"
          >
            <PhFloppyDisk class="w-5 h-5 mr-2" />
            Simpan Tautan
          </button>
        </div>

        <div v-else class="flex flex-col justify-center">
          <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Tautan saat ini:
          </p>
          <div
            class="p-3 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-200 dark:border-slate-600 break-all text-sm font-mono text-gray-800 dark:text-gray-200"
          >
            {{ videoUrl || "Belum ada tautan video yang diatur." }}
          </div>
        </div>

        <!-- Preview -->
        <div
          class="relative w-full aspect-video lg:aspect-[21/9] rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-800 flex items-center justify-center"
        >
          <iframe
            v-if="embedVideoUrl"
            :src="embedVideoUrl"
            title="Video Profil Sekolah"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            class="w-full h-full"
          ></iframe>
          <div v-else class="text-gray-400 dark:text-gray-500 flex flex-col items-center">
            <PhVideoCamera class="w-12 h-12 mb-2 opacity-50" />
            <span class="text-sm font-medium">Preview Video</span>
          </div>
        </div>
      </div>
    </div>

    <!-- List/Grid -->
    <div
      class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm"
    >
      <div
        class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center mb-6"
      >
        <div class="flex flex-wrap gap-2.5">
          <button
            @click="activeCategory = 'semua'"
            class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all duration-300 focus:outline-none border"
            :class="
              activeCategory === 'semua'
                ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/30'
                : 'bg-gray-50 dark:bg-slate-800 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-slate-600 hover:border-blue-300 hover:text-blue-600 dark:hover:text-blue-400'
            "
          >
            Semua
          </button>
          <button
            v-for="cat in categories"
            :key="cat.id"
            @click="activeCategory = cat.id"
            class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all duration-300 focus:outline-none border"
            :class="
              activeCategory === cat.id
                ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/30'
                : 'bg-gray-50 dark:bg-slate-800 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-slate-600 hover:border-blue-300 hover:text-blue-600 dark:hover:text-blue-400'
            "
          >
            {{ cat.name }}
          </button>
        </div>
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto justify-end">
          <button
            v-if="selectedItems.length > 0"
            @click="isBulkDeleteModalOpen = true"
            class="flex-1 sm:flex-none justify-center px-3 py-1.5 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition-colors flex items-center shadow-sm"
          >
            <PhTrash class="w-4 h-4 mr-1.5" /> Hapus ({{ selectedItems.length }})
          </button>
          <button
            v-if="filteredGallery.length > 0 || selectedItems.length > 0"
            @click="selectAll"
            class="flex-1 sm:flex-none justify-center px-3 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors text-gray-700 dark:text-gray-300 shadow-sm"
          >
            {{
              selectedItems.length === filteredGallery.length &&
              filteredGallery.length > 0
                ? "Batal Pilih Semua"
                : "Pilih Semua"
            }}
          </button>
          <button
            v-if="!isFormVisible"
            @click="showAddForm"
            class="flex-1 sm:flex-none justify-center px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors flex items-center shadow-sm"
          >
            <PhPlusCircle class="w-4 h-4 mr-1.5" /> Tambah Foto
          </button>
        </div>
      </div>

      <div
        v-if="filteredGallery.length === 0"
        class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
      >
        <div
          class="mx-auto w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4"
        >
          <PhImage class="w-8 h-8 text-gray-400 dark:text-gray-500" />
        </div>
        <p>Tidak ada foto yang ditemukan.</p>
      </div>

      <TransitionGroup
        name="gallery"
        tag="div"
        v-else
        class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-4 md:gap-6 w-full transform-gpu"
      >
        <div
          v-for="item in filteredGallery"
          :key="item.id"
          class="group relative overflow-hidden rounded-xl shadow-sm hover:shadow-xl transition-all duration-500 bg-gray-200 dark:bg-slate-800 block break-inside-avoid mb-4 md:mb-6 transform-gpu"
          :class="{ 'ring-2 ring-blue-500 shadow-md': selectedItems.includes(item.id) }"
        >
          <!-- Checkbox Multiple Select -->
          <label
            class="absolute top-3 left-3 z-40 cursor-pointer flex items-center justify-center w-8 h-8 rounded-full bg-white/90 dark:bg-slate-800/90 shadow-sm border border-gray-200 dark:border-slate-600 opacity-0 group-hover:opacity-100 transition-opacity"
            :class="{ 'opacity-100 !border-blue-500': selectedItems.includes(item.id) }"
          >
            <input
              type="checkbox"
              :value="item.id"
              v-model="selectedItems"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer"
            />
          </label>

          <!-- Floating Actions -->
          <div
            class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 p-1 rounded-lg border border-gray-100 dark:border-slate-700 z-40"
          >
            <button
              @click="startEdit(item)"
              class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md"
              title="Edit"
            >
              <PhPencilSimple class="w-4 h-4" />
            </button>
            <button
              @click="deleteEntry(item.id)"
              class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md"
              title="Hapus"
            >
              <PhTrash class="w-4 h-4" />
            </button>
          </div>

          <!-- Image -->
          <img
            v-if="item.image"
            :src="item.image"
            class="w-full h-auto block transition-all duration-700 group-hover:scale-105"
          />
          <div
            v-else
            class="w-full aspect-square flex items-center justify-center text-gray-400 bg-gray-100 dark:bg-slate-700"
          >
            <PhImage class="w-12 h-12 opacity-50" />
          </div>

          <!-- Dark Overlay on Hover -->
          <div
            class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
          ></div>

          <!-- Bottom Left Text -->
          <div
            class="absolute bottom-0 left-0 p-3 md:p-5 w-full z-20 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0"
          >
            <h4
              class="text-white font-bold text-sm md:text-base leading-snug drop-shadow-md mb-1 md:mb-2 line-clamp-2"
            >
              {{ item.title }}
            </h4>
            <div class="flex items-center gap-1.5 md:gap-2">
              <span
                class="bg-blue-600/90 backdrop-blur-sm px-2 py-0.5 text-white text-[10px] font-bold uppercase tracking-wider rounded"
              >
                {{ getCategoryName(item.category) }}
              </span>
              <span
                class="text-gray-300 text-[10px] md:text-xs font-medium tracking-wide flex items-center"
              >
                <PhHeart class="w-3 h-3 mr-1 text-red-500" weight="fill" />
                {{ item.likes }}
              </span>
            </div>
          </div>
        </div>
      </TransitionGroup>
    </div>

    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Foto"
      message="Yakin ingin menghapus foto ini secara permanen?"
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

.gallery-move,
.gallery-enter-active,
.gallery-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.gallery-enter-from,
.gallery-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(-30px);
}
.gallery-leave-active {
  position: absolute;
}
</style>
