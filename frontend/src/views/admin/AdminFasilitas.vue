<script setup>
import { ref, onMounted } from "vue";
import api from "@/api/index.js";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhFloppyDisk,
  PhX,
  PhXCircle,
  PhBuildings,
} from "@phosphor-icons/vue";
import RichTextEditor from "@/components/RichTextEditor.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

const facilities = ref([]);
const isLoading = ref(false);

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

const fetchData = async () => {
  isLoading.value = true;
  try {
    const response = await api.get("/api/fasilitas");
    facilities.value = response.data.data;
  } catch (error) {
    console.error("Gagal memuat data", error);
    triggerToast(
      "Gagal Memuat",
      "Tidak dapat mengambil data fasilitas dari server.",
      "error"
    );
  } finally {
    isLoading.value = false;
  }
};

onMounted(fetchData);

// Form State
const isFormVisible = ref(false);
const isEditing = ref(false);
const form = ref({ id: null, category: "", content: "" });

const openAddForm = () => {
  form.value = { id: null, category: "", content: "" };
  isEditing.value = false;
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const openEditForm = (item) => {
  form.value = { id: item.id, category: item.category, content: item.content };
  isEditing.value = true;
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const hideForm = () => {
  isFormVisible.value = false;
  document.body.style.overflow = "";
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

const saveFacility = async () => {
  if (!form.value.category.trim()) {
    triggerToast("Gagal", "Kategori fasilitas wajib diisi!", "error");
    return;
  }

  if (!form.value.content || !form.value.content.trim()) {
    triggerToast("Gagal", "Isi fasilitas wajib diisi!", "error");
    return;
  }

  const extractedImages = extractImages(form.value.content);

  const payload = {
    category: form.value.category,
    content: form.value.content,
    images: extractedImages,
  };

  try {
    if (isEditing.value) {
      const response = await api.put(`/api/fasilitas/${form.value.id}`, payload);
      const idx = facilities.value.findIndex((f) => f.id === form.value.id);
      if (idx !== -1) {
        facilities.value[idx] = response.data.data;
      }
      triggerToast("Disimpan", "Data fasilitas berhasil diperbarui.");
    } else {
      const response = await api.post("/api/fasilitas", payload);
      facilities.value.unshift(response.data.data);
      triggerToast("Ditambahkan", "Fasilitas baru berhasil ditambahkan.");
    }
    isFormVisible.value = false;
    document.body.style.overflow = "";
  } catch (error) {
    console.error(error);
    triggerToast(
      "Gagal Menyimpan",
      error.response?.data?.message || "Terjadi kesalahan pada server.",
      "error"
    );
  }
};

// Hapus Data
const deleteFacility = (id) => {
  itemToDeleteId.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = async () => {
  try {
    await api.delete(`/api/fasilitas/${itemToDeleteId.value}`);
    facilities.value = facilities.value.filter((f) => f.id !== itemToDeleteId.value);
    triggerToast("Dihapus", "Fasilitas berhasil dihapus.", "info");
    isDeleteModalOpen.value = false;
  } catch (error) {
    console.error(error);
    triggerToast("Gagal Menghapus", "Terjadi kesalahan saat menghapus data.", "error");
  }
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2
          class="text-3xl font-bold text-gray-800 dark:text-white tracking-wide"
          style="font-family: 'Oswald', sans-serif"
        >
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

    <!-- Modal Form Tambah/Edit Fasilitas -->
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
              {{ isEditing ? "Edit Fasilitas" : "Tambah Fasilitas Baru" }}
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
            <form id="facilityForm" @submit.prevent="saveFacility">
              <div class="space-y-4 mb-6">
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Kategori Fasilitas</label
                  >
                  <input
                    type="text"
                    v-model="form.category"
                    required
                    class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow"
                    placeholder="Contoh: Ruang Kelas, Laboratorium, dll."
                  />
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >Isi Fasilitas (Teks & Gambar)</label
                  >
                  <RichTextEditor
                    v-model="form.content"
                    placeholder="Masukkan isi fasilitas..."
                  />
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
              form="facilityForm"
              type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
              <PhFloppyDisk class="w-5 h-5 mr-2" /> Simpan
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Loading Skeleton -->
    <div v-if="isLoading" class="space-y-6">
      <div
        v-for="i in 3"
        :key="i"
        class="bg-gray-200 dark:bg-slate-700 h-32 rounded-lg animate-pulse"
      ></div>
    </div>

    <!-- Daftar Fasilitas -->
    <div v-else class="space-y-6">
      <div
        v-for="facility in facilities"
        :key="facility.id"
        class="bg-white dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-700 p-6 shadow-sm flex flex-col gap-4 group"
      >
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1 overflow-hidden">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
              {{ facility.category }}
            </h3>
            <div class="text-gray-800 dark:text-gray-200 text-sm md:text-base ql-snow">
              <div
                v-html="facility.content"
                class="editor-content-preview ql-editor !p-0"
                style="font-family: inherit"
              ></div>
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
      </div>

      <div
        v-if="facilities.length === 0"
        class="py-16 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800"
      >
        <div
          class="mx-auto w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4"
        >
          <PhBuildings class="w-8 h-8 text-gray-400 dark:text-gray-500" />
        </div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
          Belum Ada Data Fasilitas
        </h3>
        <p class="text-sm">
          Silakan tambahkan data kategori dan fasilitas unggulan sekolah.
        </p>
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

<style scoped>
:deep(.editor-content-preview img) {
  max-width: 100%;
  height: auto;
  border-radius: 0.5rem;
}
</style>
