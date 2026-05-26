<script setup>
import { ref, computed, nextTick, onMounted } from "vue";
import api from "@/api/index.js";
import {
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhNewspaper,
  PhTag,
  PhX,
  PhPlusCircle,
  PhImage,
  PhImages,
} from "@phosphor-icons/vue";
import RichTextEditor from "@/components/RichTextEditor.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const activeTab = ref("berita");

const categories = ref([
  { id: "akademik", name: "Akademik" },
  { id: "kegiatan", name: "Kegiatan" },
  { id: "prestasi", name: "Prestasi" },
  { id: "pengumuman", name: "Pengumuman" },
]);

const galleryCategories = ref([
  { id: "fasilitas", name: "Fasilitas" },
  { id: "kegiatan", name: "Kegiatan" },
  { id: "ekskul", name: "Ekstrakurikuler" },
  { id: "pentas-seni", name: "Pentas Seni" },
  { id: "hut-ri", name: "HUT RI" },
]);

const newsList = ref([]);
const galleryList = ref([]);

const form = ref({
  id: null,
  title: "",
  category: "akademik",
  images: [],
  content: "",
  tags: "",
});

const isFormVisible = ref(false);
const galleryForm = ref({
  id: null,
  title: "",
  category: "fasilitas",
  images: [],
});

const isNewsFormVisible = ref(false);
const isGalleryFormVisible = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);
const deleteType = ref("news");

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");

const fetchNews = async () => {
  try {
    const response = await api.get("/api/my-pending-news");
    newsList.value = response.data.data || [];
  } catch (error) {
    triggerToast("Error", "Gagal mengambil data draft berita", "error");
  }
};

const fetchGalleries = async () => {
  try {
    const response = await api.get("/api/my-pending-galleries");
    galleryList.value = response.data.data || [];
  } catch (error) {
    triggerToast("Error", "Gagal mengambil data draft galeri", "error");
  }
};

onMounted(() => {
  fetchNews();
  fetchGalleries();
});

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

const galleryFileInput = ref(null);
const triggerGalleryFileInput = () => {
  if (galleryFileInput.value) galleryFileInput.value.click();
};
const handleGalleryFileUpload = (event) => {
  const files = Array.from(event.target.files);
  files.forEach((file) => {
    const reader = new FileReader();
    reader.onload = (e) => {
      galleryForm.value.images.push(e.target.result);
    };
    reader.readAsDataURL(file);
  });
  event.target.value = "";
};
const removeGalleryImage = (index) => {
  galleryForm.value.images.splice(index, 1);
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
};

const startEditNews = (item) => {
  form.value = { ...item, images: item.images ? [...item.images] : [] };
  isFormVisible.value = true;
  isNewsFormVisible.value = true;
  isGalleryFormVisible.value = false;
  nextTick(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
};

const startEditGallery = (item) => {
  galleryForm.value = {
    id: item.id,
    title: item.title,
    category: item.category,
    images: [item.image],
  };
  isGalleryFormVisible.value = true;
  isNewsFormVisible.value = false;
  nextTick(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
};

const saveNewsEntry = async () => {
  if (!form.value.title) {
    triggerToast("Gagal Menyimpan", "Judul Berita wajib diisi!", "error");
    return;
  }

  try {
    const response = await api.put(`/api/news/${form.value.id}`, form.value);
    const index = newsList.value.findIndex((s) => s.id === form.value.id);
    if (index !== -1) {
      newsList.value[index] = response.data.data;
    }

    isFormVisible.value = false;
    isNewsFormVisible.value = false;
    triggerToast(
      "Perubahan Disimpan",
      "Revisi berita berhasil disimpan dan kembali menunggu persetujuan."
    );
    resetForm();
  } catch (error) {
    triggerToast("Gagal", "Terjadi kesalahan saat memperbarui data", "error");
  }
};

const saveGalleryEntry = async () => {
  if (!galleryForm.value.title || galleryForm.value.images.length === 0) {
    triggerToast("Gagal Menyimpan", "Judul Foto dan Gambar wajib diisi!", "error");
    return;
  }

  try {
    const response = await api.put(
      `/api/galleries/${galleryForm.value.id}`,
      galleryForm.value
    );
    const newItems = response.data.data;
    const index = galleryList.value.findIndex((s) => s.id === galleryForm.value.id);
    if (index !== -1) {
      galleryList.value[index] = newItems[0];
    }
    if (newItems.length > 1) {
      galleryList.value.unshift(...newItems.slice(1));
    }

    isGalleryFormVisible.value = false;
    triggerToast(
      "Perubahan Disimpan",
      "Revisi galeri berhasil disimpan dan kembali menunggu persetujuan."
    );
  } catch (error) {
    triggerToast("Gagal", "Terjadi kesalahan saat memperbarui data galeri", "error");
  }
};

const hideNewsForm = () => {
  resetForm();
  isFormVisible.value = false;
  isNewsFormVisible.value = false;
};

const hideGalleryForm = () => {
  isGalleryFormVisible.value = false;
};

const deleteEntry = (id, type) => {
  itemToDelete.value = id;
  deleteType.value = type;
  isDeleteModalOpen.value = true;
};

const confirmDelete = async () => {
  if (itemToDelete.value !== null) {
    try {
      await api.delete(`/api/news/${itemToDelete.value}`);
      newsList.value = newsList.value.filter((s) => s.id !== itemToDelete.value);
      if (deleteType.value === "news") {
        await api.delete(`/api/news/${itemToDelete.value}`);
        newsList.value = newsList.value.filter((s) => s.id !== itemToDelete.value);
      } else {
        await api.delete(`/api/galleries/${itemToDelete.value}`);
        galleryList.value = galleryList.value.filter((s) => s.id !== itemToDelete.value);
      }
      itemToDelete.value = null;
      triggerToast("Data Dihapus", "Draft berhasil dihapus dari sistem.", "info");
    } catch (error) {
      triggerToast("Gagal", "Terjadi kesalahan saat menghapus data.", "error");
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

const handleGalleryImageDragStart = (index, event) => {
  draggedImageIndex.value = index;
  event.dataTransfer.effectAllowed = "move";
};

const handleGalleryImageDrop = (index) => {
  if (draggedImageIndex.value === null || draggedImageIndex.value === index) return;
  const draggedItem = galleryForm.value.images.splice(draggedImageIndex.value, 1)[0];
  galleryForm.value.images.splice(index, 0, draggedItem);
  draggedImageIndex.value = null;
};

const filteredNews = computed(() => {
  const list = newsList.value || [];
  if (!searchQuery.value) return list;
  const query = searchQuery.value.toLowerCase();
  return list.filter((item) => item.title.toLowerCase().includes(query));
});

const filteredGalleries = computed(() => {
  const list = galleryList.value || [];
  if (!searchQuery.value) return list;
  const query = searchQuery.value.toLowerCase();
  return list.filter((item) => item.title.toLowerCase().includes(query));
});

const getGalleryCategoryName = (id) => {
  const cat = galleryCategories.value.find((c) => c.id === id);
  return cat ? cat.name : id;
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8">
      <h2
        class="text-3xl font-bold text-gray-800 dark:text-white"
        style="font-family: 'Oswald', sans-serif"
      >
        Draft & Revisi Konten
      </h2>
      <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
        Pantau status berita/galeri Anda yang sedang menunggu persetujuan, atau perbaiki
        konten yang dikembalikan/ditolak oleh Super Admin.
      </p>
    </div>

    <!-- Sistem Tabs -->
    <div class="flex gap-4 border-b border-gray-200 dark:border-slate-700 mb-6">
      <button
        @click="activeTab = 'berita'"
        class="pb-3 px-2 text-sm font-semibold transition-colors relative"
        :class="
          activeTab === 'berita'
            ? 'text-blue-600 dark:text-blue-400'
            : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
        "
      >
        <div class="flex items-center gap-2">
          <PhNewspaper class="w-5 h-5" />
          Draft Berita
          <span
            v-if="newsList.length > 0"
            class="bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded-full"
            >{{ newsList.length }}</span
          >
        </div>
        <div
          v-if="activeTab === 'berita'"
          class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 dark:bg-blue-400 rounded-t-full"
        ></div>
      </button>

      <button
        @click="activeTab = 'galeri'"
        class="pb-3 px-2 text-sm font-semibold transition-colors relative"
        :class="
          activeTab === 'galeri'
            ? 'text-blue-600 dark:text-blue-400'
            : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
        "
      >
        <div class="flex items-center gap-2">
          <PhImage class="w-5 h-5" />
          Draft Galeri
          <span
            v-if="galleryList.length > 0"
            class="bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded-full"
            >{{ galleryList.length }}</span
          >
        </div>
        <div
          v-if="activeTab === 'galeri'"
          class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 dark:bg-blue-400 rounded-t-full"
        ></div>
      </button>
    </div>

    <!-- Form Edit Data -->
    <Transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-4"
    >
      <div
        v-if="isNewsFormVisible && activeTab === 'berita'"
        class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm mb-8 overflow-hidden"
      >
        <div
          class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
        >
          <h3 class="text-xl font-bold text-gray-800 dark:text-white">
            Perbaiki Draft Berita
          </h3>
          <button
            @click="hideNewsForm"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
          >
            <PhX class="w-6 h-6" />
          </button>
        </div>

        <div class="p-6">
          <form id="draftForm" @submit.prevent="saveNewsEntry()">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <!-- Image Uploader -->
              <div
                class="lg:col-span-1 border border-gray-200 dark:border-slate-600 rounded-xl p-4 bg-gray-50 dark:bg-slate-700/50 h-max"
              >
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3"
                  >Gambar Berita</label
                >
                <div v-if="form.images?.length > 0" class="mb-4">
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
                  />
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Kategori</label
                  >
                  <select
                    v-model="form.category"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                      {{ cat.name }}
                    </option>
                  </select>
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
            @click="hideNewsForm"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors"
          >
            <PhXCircle class="w-5 h-5 mr-2" /> Batal
          </button>
          <button
            type="submit"
            form="draftForm"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors"
          >
            <PhFloppyDisk class="w-5 h-5 mr-2" /> Simpan & Ajukan Ulang
          </button>
        </div>
      </div>
    </Transition>

    <!-- Form Edit Data Galeri -->
    <Transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-4"
    >
      <div
        v-if="isGalleryFormVisible && activeTab === 'galeri'"
        class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm mb-8 overflow-hidden"
      >
        <div
          class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
        >
          <h3 class="text-xl font-bold text-gray-800 dark:text-white">
            Perbaiki Draft Galeri
          </h3>
          <button
            @click="hideGalleryForm"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
          >
            <PhX class="w-6 h-6" />
          </button>
        </div>
        <div class="p-6">
          <form id="draftGalleryForm" @submit.prevent="saveGalleryEntry()">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                  >Judul Foto</label
                >
                <input
                  type="text"
                  v-model="galleryForm.title"
                  required
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                />
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                  >Kategori</label
                >
                <select
                  v-model="galleryForm.category"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                >
                  <option v-for="cat in galleryCategories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                  </option>
                </select>
              </div>
              <div
                class="md:col-span-2 border border-gray-200 dark:border-slate-600 rounded-xl p-4 bg-gray-50 dark:bg-slate-700/50"
              >
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3"
                  >Gambar Galeri</label
                >
                <div v-if="galleryForm.images?.length > 0" class="mb-4">
                  <div
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3"
                  >
                    <div
                      class="relative col-span-2 row-span-2 rounded-lg overflow-hidden group shadow-sm aspect-[4/3] cursor-move"
                      draggable="true"
                      @dragstart="handleGalleryImageDragStart(0, $event)"
                      @dragover.prevent
                      @dragenter.prevent
                      @drop="handleGalleryImageDrop(0)"
                    >
                      <img
                        :src="galleryForm.images[0]"
                        class="w-full h-full object-cover"
                      />
                      <div
                        class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm"
                      >
                        Utama
                      </div>
                      <button
                        type="button"
                        @click="removeGalleryImage(0)"
                        class="absolute top-2 right-2 p-1.5 bg-red-500/90 hover:bg-red-600 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity shadow-sm"
                      >
                        <PhTrash class="w-4 h-4" />
                      </button>
                    </div>
                    <div
                      v-for="(img, index) in galleryForm.images.slice(1)"
                      :key="index + 1"
                      class="relative rounded-lg overflow-hidden group shadow-sm aspect-square cursor-move"
                      draggable="true"
                      @dragstart="handleGalleryImageDragStart(index + 1, $event)"
                      @dragover.prevent
                      @dragenter.prevent
                      @drop="handleGalleryImageDrop(index + 1)"
                    >
                      <img :src="img" class="w-full h-full object-cover" />
                      <button
                        type="button"
                        @click="removeGalleryImage(index + 1)"
                        class="absolute top-1 right-1 p-1 bg-red-500/90 hover:bg-red-600 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity shadow-sm"
                      >
                        <PhTrash class="w-3 h-3" />
                      </button>
                    </div>
                  </div>
                </div>
                <input
                  type="file"
                  ref="galleryFileInput"
                  multiple
                  accept="image/*"
                  class="hidden"
                  @change="handleGalleryFileUpload"
                />
                <button
                  type="button"
                  @click="triggerGalleryFileInput"
                  class="w-full py-6 border-2 border-dashed border-gray-300 dark:border-slate-500 rounded-lg flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors"
                >
                  <PhPlusCircle class="w-6 h-6 mb-2 text-gray-400" />
                  <span class="text-sm font-medium">Klik tambah gambar</span>
                </button>
              </div>
            </div>
          </form>
        </div>
        <div
          class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
        >
          <button
            type="button"
            @click="hideGalleryForm"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors"
          >
            <PhXCircle class="w-5 h-5 mr-2" /> Batal
          </button>
          <button
            type="submit"
            form="draftGalleryForm"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors"
          >
            <PhFloppyDisk class="w-5 h-5 mr-2" /> Simpan & Ajukan Ulang
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
          :placeholder="
            activeTab === 'berita'
              ? 'Cari judul draft berita...'
              : 'Cari judul draft galeri...'
          "
          class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        />
      </div>

      <div v-if="activeTab === 'berita'">
        <div
          v-if="!filteredNews || filteredNews.length === 0"
          class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
        >
          <div
            class="mx-auto w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4"
          >
            <PhNewspaper class="w-8 h-8 text-gray-400 dark:text-gray-500" />
          </div>
          <p>Tidak ada draft berita atau berita yang ditolak.</p>
        </div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
          <div
            v-for="news in filteredNews"
            :key="news.id"
            class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm flex flex-col overflow-hidden relative group"
          >
            <div
              class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 p-1 rounded-lg border border-gray-100 dark:border-slate-700 z-10"
            >
              <button
                @click="startEditNews(news)"
                class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md"
                title="Edit/Revisi"
              >
                <PhPencilSimple class="w-4 h-4" />
              </button>
              <button
                @click="deleteEntry(news.id, 'news')"
                class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md"
                title="Hapus Permanen"
              >
                <PhTrash class="w-4 h-4" />
              </button>
            </div>
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
              <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                <span
                  v-if="news.status === 'pending'"
                  class="bg-yellow-500/90 backdrop-blur-sm px-2.5 py-1 text-white text-[10px] font-bold uppercase tracking-wider rounded shadow-sm"
                  >Menunggu Persetujuan</span
                >
                <span
                  v-else-if="news.status === 'rejected'"
                  class="bg-red-500/90 backdrop-blur-sm px-2.5 py-1 text-white text-[10px] font-bold uppercase tracking-wider rounded shadow-sm"
                  >Ditolak</span
                >
              </div>
            </div>
            <div class="p-5 flex flex-col flex-1">
              <div
                v-if="news.status === 'rejected' && news.rejection_note"
                class="mb-3 p-3 bg-red-50 border border-red-100 rounded-lg text-xs text-red-600 dark:bg-red-900/20 dark:border-red-800/30 dark:text-red-400"
              >
                <span class="font-bold block mb-1">Catatan Penolakan:</span>
                {{ news.rejection_note }}
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
            </div>
          </div>
        </div>
      </div>

      <div v-else-if="activeTab === 'galeri'">
        <div
          v-if="!filteredGalleries || filteredGalleries.length === 0"
          class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
        >
          <div
            class="mx-auto w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4"
          >
            <PhImages class="w-8 h-8 text-gray-400 dark:text-gray-500" />
          </div>
          <p>Tidak ada draft galeri atau galeri yang ditolak.</p>
        </div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
          <div
            v-for="item in filteredGalleries"
            :key="item.id"
            class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm flex flex-col overflow-hidden relative group"
          >
            <div
              class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 p-1 rounded-lg border border-gray-100 dark:border-slate-700 z-10"
            >
              <button
                @click="startEditGallery(item)"
                class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md"
                title="Edit/Revisi"
              >
                <PhPencilSimple class="w-4 h-4" />
              </button>
              <button
                @click="deleteEntry(item.id, 'gallery')"
                class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md"
                title="Hapus Permanen"
              >
                <PhTrash class="w-4 h-4" />
              </button>
            </div>

            <div class="w-full aspect-[4/3] bg-gray-100 dark:bg-slate-700 relative">
              <img
                v-if="item.image"
                :src="item.image"
                class="w-full h-full object-cover"
              />
              <div
                v-else
                class="w-full h-full flex items-center justify-center text-gray-400"
              >
                <PhImage class="w-12 h-12 opacity-50" />
              </div>
              <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                <span
                  v-if="item.status === 'pending'"
                  class="bg-yellow-500/90 backdrop-blur-sm px-2.5 py-1 text-white text-[10px] font-bold uppercase tracking-wider rounded shadow-sm"
                  >Menunggu Persetujuan</span
                >
                <span
                  v-else-if="item.status === 'rejected'"
                  class="bg-red-500/90 backdrop-blur-sm px-2.5 py-1 text-white text-[10px] font-bold uppercase tracking-wider rounded shadow-sm"
                  >Ditolak</span
                >
              </div>
            </div>

            <div class="p-4 flex flex-col flex-1">
              <div
                v-if="item.status === 'rejected' && item.rejection_note"
                class="mb-3 p-3 bg-red-50 border border-red-100 rounded-lg text-xs text-red-600 dark:bg-red-900/20 dark:border-red-800/30 dark:text-red-400"
              >
                <span class="font-bold block mb-1">Catatan Penolakan:</span>
                {{ item.rejection_note }}
              </div>
              <h4
                class="font-bold text-gray-900 dark:text-white text-base mb-1 line-clamp-2"
              >
                {{ item.title }}
              </h4>
              <div
                class="text-xs text-blue-600 dark:text-blue-400 uppercase font-bold tracking-wider mt-auto"
              >
                {{ getGalleryCategoryName(item.category) }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Draft Konten"
      message="Yakin ingin menghapus draft konten ini secara permanen?"
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
