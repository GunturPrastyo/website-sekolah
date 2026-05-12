<script setup>
import { ref, computed } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhImage,
  PhHeart,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const categories = [
  { id: "fasilitas", name: "Fasilitas" },
  { id: "kegiatan", name: "Kegiatan" },
  { id: "ekskul", name: "Ekstrakurikuler" },
  { id: "pentas-seni", name: "Pentas Seni" },
  { id: "hut-ri", name: "HUT RI" },
];

const galleryList = ref([
  {
    id: 1,
    title: "Gedung Utama Sekolah",
    category: "fasilitas",
    image:
      "https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800&h=1000&fit=crop",
    likes: 120,
  },
  {
    id: 2,
    title: "Upacara Bendera HUT RI",
    category: "hut-ri",
    image:
      "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1200&h=800&fit=crop",
    likes: 540,
  },
  {
    id: 3,
    title: "Praktikum Laboratorium Kimia",
    category: "fasilitas",
    image:
      "https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=800&h=1200&fit=crop",
    likes: 85,
  },
]);

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
const searchQuery = ref("");

const fileInput = ref(null);

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
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
};

const addEntry = () => {
  if (!form.value.title || form.value.images.length === 0) {
    triggerToast("Gagal Menyimpan", "Judul Foto dan Gambar wajib diisi!", "error");
    return;
  }

  let currentId =
    galleryList.value.length > 0 ? Math.max(...galleryList.value.map((s) => s.id)) : 0;

  const newEntries = form.value.images.map((img) => {
    currentId++;
    return {
      id: currentId,
      title: form.value.title,
      category: form.value.category,
      image: img,
      likes: 0,
    };
  });

  galleryList.value.unshift(...newEntries);

  isFormVisible.value = false;
  triggerToast(
    "Berhasil Ditambahkan",
    `${newEntries.length} foto baru telah ditambahkan ke galeri.`
  );
  resetForm();
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = {
    id: item.id,
    title: item.title,
    category: item.category,
    images: [item.image],
  };
  isFormVisible.value = true;
  window.scrollTo({ top: 0, behavior: "smooth" });
};

const saveEntry = () => {
  if (!form.value.title || form.value.images.length === 0) {
    triggerToast("Gagal Menyimpan", "Judul Foto dan Gambar wajib diisi!", "error");
    return;
  }
  const index = galleryList.value.findIndex((s) => s.id === form.value.id);
  if (index !== -1) {
    galleryList.value[index] = {
      ...galleryList.value[index],
      title: form.value.title,
      category: form.value.category,
      image: form.value.images[0],
    };

    if (form.value.images.length > 1) {
      let currentId = Math.max(...galleryList.value.map((s) => s.id));
      const newEntries = form.value.images.slice(1).map((img) => {
        currentId++;
        return {
          id: currentId,
          title: form.value.title,
          category: form.value.category,
          image: img,
          likes: 0,
        };
      });
      galleryList.value.unshift(...newEntries);
    }
  }

  isFormVisible.value = false;
  triggerToast("Perubahan Disimpan", "Data foto berhasil diperbarui.");
  resetForm();
};

const hideForm = () => {
  resetForm();
  isFormVisible.value = false;
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
  if (itemToDelete.value !== null) {
    galleryList.value = galleryList.value.filter((s) => s.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast("Data Dihapus", "Data foto berhasil dihapus dari galeri.", "info");
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredGallery = computed(() => {
  if (!searchQuery.value) return galleryList.value;
  const query = searchQuery.value.toLowerCase();
  return galleryList.value.filter((item) => item.title.toLowerCase().includes(query));
});

const getCategoryName = (id) => {
  const cat = categories.find((c) => c.id === id);
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
      <button
        v-if="!isFormVisible"
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Foto
      </button>
    </div>

    <!-- Form Tambah/Edit -->
    <Transition
      enter-active-class="transition-all duration-500 ease-out"
      leave-active-class="transition-all duration-300 ease-in"
      enter-from-class="opacity-0 -translate-y-4 max-h-0"
      enter-to-class="opacity-100 translate-y-0 max-h-[2000px]"
      leave-from-class="opacity-100 translate-y-0 max-h-[2000px]"
      leave-to-class="opacity-0 -translate-y-4 max-h-0"
    >
      <div
        v-if="isFormVisible"
        class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm mb-8"
      >
        <h3
          class="text-xl font-semibold text-gray-800 dark:text-white mb-6 border-b border-gray-100 dark:border-slate-700 pb-3"
        >
          {{ isEditing ? "Edit Data Foto" : "Tambah Foto Baru" }}
        </h3>
        <form @submit.prevent="isEditing ? saveEntry() : addEntry()">
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
                <select
                  v-model="form.category"
                  required
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                >
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                  </option>
                </select>
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

          <div
            class="flex gap-3 mt-6 justify-end border-t border-gray-100 dark:border-slate-700 pt-6"
          >
            <button
              type="button"
              @click="hideForm"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhXCircle class="w-5 h-5 mr-2" /> Batal
            </button>
            <button
              type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
              <PhPlusCircle v-else class="w-5 h-5 mr-2" />
              {{ isEditing ? "Simpan Perubahan" : "Simpan Data" }}
            </button>
          </div>
        </form>
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
          placeholder="Cari judul foto..."
          class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        />
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

      <div
        v-else
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6"
      >
        <div
          v-for="item in filteredGallery"
          :key="item.id"
          class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm flex flex-col overflow-hidden relative group"
        >
          <!-- Floating Actions -->
          <div
            class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 p-1 rounded-lg border border-gray-100 dark:border-slate-700 z-10"
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

          <!-- Image Thumbnail -->
          <div class="w-full aspect-square bg-gray-100 dark:bg-slate-700 relative">
            <img v-if="item.image" :src="item.image" class="w-full h-full object-cover" />
            <div
              v-else
              class="w-full h-full flex items-center justify-center text-gray-400"
            >
              <PhImage class="w-12 h-12 opacity-50" />
            </div>

            <div class="absolute bottom-3 left-3 flex gap-2">
              <span
                class="bg-blue-600/90 backdrop-blur-sm px-2.5 py-1 text-white text-[10px] font-bold uppercase tracking-wider rounded"
              >
                {{ getCategoryName(item.category) }}
              </span>
            </div>
          </div>

          <!-- Content Info -->
          <div class="p-4 flex flex-col flex-1">
            <h4
              class="font-bold text-gray-900 dark:text-white text-md mb-2 leading-tight line-clamp-2"
            >
              {{ item.title }}
            </h4>
            <div
              class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-auto pt-2 border-t border-gray-100 dark:border-slate-700"
            >
              <PhHeart class="w-4 h-4 mr-1 text-red-500" weight="fill" />
              {{ item.likes }} Suka
            </div>
          </div>
        </div>
      </div>
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
</style>
