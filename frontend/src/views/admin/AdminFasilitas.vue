<script setup>
import { ref } from "vue";
import { PhPlusCircle, PhPencilSimple, PhTrash, PhFloppyDisk } from "@phosphor-icons/vue";
import RichTextEditor from "@/components/RichTextEditor.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const facilities = ref([
  {
    id: 1,
    category: "Ruang Kelas",
    content: `Ruang kelas dirancang untuk menciptakan suasana belajar yang nyaman, interaktif, dan mendukung konsentrasi siswa dalam mengikuti pembelajaran.<br><br><img src="https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800" style="max-width: 100%; border-radius: 8px; margin-bottom: 8px;"><img src="https://images.unsplash.com/photo-1594434533439-04c3a735d359?q=80&w=800" style="max-width: 100%; border-radius: 8px; margin-bottom: 8px;"><img src="https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800" style="max-width: 100%; border-radius: 8px; margin-bottom: 8px;"><img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=800" style="max-width: 100%; border-radius: 8px; margin-bottom: 8px;"><img src="https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800" style="max-width: 100%; border-radius: 8px; margin-bottom: 8px;">`,
    images: [
      "https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800",
      "https://images.unsplash.com/photo-1594434533439-04c3a735d359?q=80&w=800",
      "https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800",
      "https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=800",
      "https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800",
    ],
  },
  {
    id: 2,
    category: "Laboratorium",
    content: `Laboratorium menjadi sarana penting dalam mendukung pembelajaran berbasis praktik dan eksperimen dengan fasilitas lengkap.<br><br><img src="https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800" style="max-width: 100%; border-radius: 8px; margin-bottom: 8px;">`,
    images: ["https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800"],
  },
]);

// State Toast & Modal Delete
const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const isDeleteModalOpen = ref(false);
const itemToDeleteId = ref(null);

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => (showToast.value = false), 4000);
};

// Form State
const isFormVisible = ref(false);
const isEditing = ref(false);
const form = ref({ id: null, category: "", content: "" });

const openAddForm = () => {
  form.value = { id: null, category: "", content: "" };
  isEditing.value = false;
  isFormVisible.value = true;
};

const openEditForm = (item) => {
  form.value = { id: item.id, category: item.category, content: item.content };
  isEditing.value = true;
  isFormVisible.value = true;
};

const hideForm = () => {
  isFormVisible.value = false;
};

const extractImages = (html) => {
  if (!html) return [];
  const regex = /<img[^>]+src="([^">]+)"/g;
  const images = [];
  let match;
  while ((match = regex.exec(html))) {
    images.push(match[1]);
  }
  return images;
};

const stripTags = (html) => {
  if (!html) return "";
  return html
    .replace(/<[^>]*>?/gm, " ")
    .replace(/\s+/g, " ")
    .trim();
};

const saveFacility = () => {
  if (!form.value.category.trim()) {
    triggerToast("Gagal", "Kategori fasilitas wajib diisi!", "error");
    return;
  }

  if (!form.value.content || !form.value.content.trim()) {
    triggerToast("Gagal", "Isi fasilitas wajib diisi!", "error");
    return;
  }

  const extractedImages = extractImages(form.value.content);

  if (isEditing.value) {
    const idx = facilities.value.findIndex((f) => f.id === form.value.id);
    if (idx !== -1) {
      facilities.value[idx] = {
        ...form.value,
        images: extractedImages,
      };
    }
    triggerToast("Disimpan", "Data fasilitas berhasil diperbarui.");
  } else {
    facilities.value.push({
      id: Date.now(),
      category: form.value.category,
      content: form.value.content,
      images: extractedImages,
    });
    triggerToast("Ditambahkan", "Fasilitas baru berhasil ditambahkan.");
  }
  isFormVisible.value = false;
};

// Hapus Data
const deleteFacility = (id) => {
  itemToDeleteId.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
  facilities.value = facilities.value.filter((f) => f.id !== itemToDeleteId.value);
  triggerToast("Dihapus", "Fasilitas berhasil dihapus.", "info");
  isDeleteModalOpen.value = false;
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
          Manajemen Fasilitas
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data kategori dan daftar fasilitas unggulan sekolah.
        </p>
      </div>
      <button
        v-if="!isFormVisible"
        @click="openAddForm"
        class="shrink-0 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2"
      >
        <PhPlusCircle class="w-5 h-5" /> Tambah Fasilitas
      </button>
    </div>

    <!-- Form Tambah/Edit Fasilitas -->
    <Transition
      enter-active-class="transition-all duration-300"
      enter-from-class="opacity-0 -translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
    >
      <div
        v-if="isFormVisible"
        class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 p-6 mb-8 shadow-sm"
      >
        <h4 class="text-lg font-bold mb-4 border-b pb-2 dark:border-slate-700">
          {{ isEditing ? "Edit Fasilitas" : "Tambah Fasilitas Baru" }}
        </h4>
        <form @submit.prevent="saveFacility">
          <div class="space-y-4 mb-6">
            <div>
              <label class="block text-sm font-medium mb-1">Kategori Fasilitas</label>
              <input
                type="text"
                v-model="form.category"
                required
                class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700/50 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-shadow"
                placeholder="Contoh: Ruang Kelas, Laboratorium, dll."
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2"
                >Isi Fasilitas (Teks & Gambar)</label
              >
              <RichTextEditor
                v-model="form.content"
                placeholder="Masukkan isi fasilitas..."
              />
            </div>
          </div>
          <div class="flex justify-end gap-3 pt-4 border-t dark:border-slate-700">
            <button
              type="button"
              @click="hideForm"
              class="px-4 py-2 text-sm font-medium bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors"
            >
              Batal
            </button>
            <button
              type="submit"
              class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2"
            >
              <PhFloppyDisk class="w-5 h-5" /> Simpan
            </button>
          </div>
        </form>
      </div>
    </Transition>

    <!-- Daftar Fasilitas -->
    <div class="space-y-6">
      <div
        v-for="facility in facilities"
        :key="facility.id"
        class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 p-6 shadow-sm flex flex-col gap-4 group"
      >
        <div class="flex items-start justify-between">
          <div>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
              {{ facility.category }}
            </h3>
            <div class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3">
              {{ stripTags(facility.content) }}
            </div>
          </div>
          <div class="flex gap-2 shrink-0">
            <button
              @click="openEditForm(facility)"
              class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-slate-700 dark:text-blue-400 dark:hover:bg-slate-600 rounded-lg transition-colors"
              title="Edit"
            >
              <PhPencilSimple class="w-5 h-5" />
            </button>
            <button
              @click="deleteFacility(facility.id)"
              class="p-2 text-red-600 bg-red-50 hover:bg-red-100 dark:bg-slate-700 dark:text-red-400 dark:hover:bg-slate-600 rounded-lg transition-colors"
              title="Hapus"
            >
              <PhTrash class="w-5 h-5" />
            </button>
          </div>
        </div>

        <!-- Image Grid Preview -->
        <div v-if="facility.images && facility.images.length > 0" class="mt-2">
          <div
            class="grid gap-2"
            :class="{
              'grid-cols-1 md:grid-cols-2 lg:grid-cols-3': facility.images.length === 1,
              'grid-cols-2': facility.images.length === 2,
              'grid-cols-3': facility.images.length === 3,
              'grid-cols-2 md:grid-cols-4': facility.images.length >= 4,
            }"
          >
            <div
              v-for="(img, idx) in facility.images.slice(0, 4)"
              :key="idx"
              class="relative aspect-video rounded-xl overflow-hidden"
            >
              <img :src="img" class="w-full h-full object-cover" />
              <div
                v-if="idx === 3 && facility.images.length > 4"
                class="absolute inset-0 bg-black/60 flex items-center justify-center text-white font-bold text-2xl backdrop-blur-sm"
              >
                +{{ facility.images.length - 4 }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="facilities.length === 0"
        class="py-20 text-center border-2 border-dashed rounded-2xl dark:border-slate-700 bg-white dark:bg-slate-800"
      >
        <p class="text-gray-500 dark:text-gray-400">Belum ada data fasilitas.</p>
      </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Fasilitas"
      message="Apakah Anda yakin ingin menghapus data fasilitas ini?"
      @confirm="confirmDelete"
      @cancel="isDeleteModalOpen = false"
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
