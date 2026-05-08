<script setup>
import { ref } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhFloppyDisk,
  PhUploadSimple,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const facilities = ref([
  {
    id: 1,
    category: "Ruang Kelas",
    description:
      "Ruang kelas dirancang untuk menciptakan suasana belajar yang nyaman, interaktif, dan mendukung konsentrasi siswa dalam mengikuti pembelajaran.",
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
    description:
      "Laboratorium menjadi sarana penting dalam mendukung pembelajaran berbasis praktik dan eksperimen dengan fasilitas lengkap.",
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
const form = ref({ id: null, category: "", description: "", images: [] });
const fileInput = ref(null);

const openAddForm = () => {
  form.value = { id: null, category: "", description: "", images: [] };
  isEditing.value = false;
  isFormVisible.value = true;
};

const openEditForm = (item) => {
  form.value = { ...item, images: [...item.images] };
  isEditing.value = true;
  isFormVisible.value = true;
};

const hideForm = () => {
  isFormVisible.value = false;
};

const handleMultipleUpload = (event) => {
  const files = event.target.files;
  if (!files || files.length === 0) return;

  // In a real app, you would upload these files to a server and get URLs back
  // Here we use URL.createObjectURL for preview purposes
  for (let i = 0; i < files.length; i++) {
    form.value.images.push(URL.createObjectURL(files[i]));
  }

  // Reset file input
  if (fileInput.value) fileInput.value.value = "";
};

const removeFormImage = (index) => {
  form.value.images.splice(index, 1);
};

const saveFacility = () => {
  if (!form.value.category.trim()) {
    triggerToast("Gagal", "Kategori fasilitas wajib diisi!", "error");
    return;
  }
  if (!form.value.description.trim()) {
    triggerToast("Gagal", "Deskripsi fasilitas wajib diisi!", "error");
    return;
  }
  if (form.value.images.length === 0) {
    triggerToast("Gagal", "Minimal 1 foto fasilitas wajib diunggah!", "error");
    return;
  }

  if (isEditing.value) {
    const idx = facilities.value.findIndex((f) => f.id === form.value.id);
    if (idx !== -1) {
      facilities.value[idx] = { ...form.value };
    }
    triggerToast("Disimpan", "Data fasilitas berhasil diperbarui.");
  } else {
    facilities.value.push({
      id: Date.now(),
      category: form.value.category,
      description: form.value.description,
      images: [...form.value.images],
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
                class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-slate-600 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Contoh: Ruang Kelas, Laboratorium, dll."
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Deskripsi (Rich Text)</label>
              <div class="border rounded-lg dark:border-slate-600 overflow-hidden">
                <div
                  class="bg-gray-50 dark:bg-slate-700 p-2 flex gap-2 border-b dark:border-slate-600"
                >
                  <button
                    type="button"
                    class="px-2 py-1 text-sm font-bold hover:bg-gray-200 dark:hover:bg-slate-600 rounded"
                  >
                    B
                  </button>
                  <button
                    type="button"
                    class="px-2 py-1 text-sm italic hover:bg-gray-200 dark:hover:bg-slate-600 rounded"
                  >
                    I
                  </button>
                  <button
                    type="button"
                    class="px-2 py-1 text-sm underline hover:bg-gray-200 dark:hover:bg-slate-600 rounded"
                  >
                    U
                  </button>
                  <div class="w-px h-6 bg-gray-300 dark:bg-slate-500 mx-1"></div>
                  <button
                    type="button"
                    class="px-2 py-1 text-sm hover:bg-gray-200 dark:hover:bg-slate-600 rounded"
                  >
                    List
                  </button>
                </div>
                <textarea
                  v-model="form.description"
                  rows="5"
                  class="w-full px-4 py-2 outline-none dark:bg-slate-800 focus:ring-0"
                  placeholder="Tuliskan deskripsi fasilitas..."
                ></textarea>
              </div>
            </div>

            <!-- Multiple Image Upload -->
            <div>
              <label class="block text-sm font-medium mb-2"
                >Galeri Foto (Bisa pilih banyak gambar sekaligus)</label
              >

              <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-4">
                <div
                  v-for="(img, idx) in form.images"
                  :key="idx"
                  class="relative group aspect-video rounded-xl overflow-hidden border dark:border-slate-600"
                >
                  <img :src="img" class="w-full h-full object-cover" />
                  <button
                    @click.prevent="removeFormImage(idx)"
                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                    title="Hapus Foto"
                  >
                    <PhTrash class="w-4 h-4" />
                  </button>
                </div>

                <div
                  class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-xl flex flex-col items-center justify-center aspect-video cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors"
                  @click="$refs.fileInput.click()"
                >
                  <PhUploadSimple class="w-8 h-8 text-gray-400 mb-1" />
                  <span
                    class="text-xs font-medium text-gray-500 dark:text-gray-400 text-center px-2"
                    >Upload<br />Gambar</span
                  >
                </div>
              </div>

              <input
                type="file"
                multiple
                accept="image/*"
                @change="handleMultipleUpload"
                class="hidden"
                ref="fileInput"
              />
            </div>
          </div>
          <div class="flex justify-end gap-3 pt-4 border-t dark:border-slate-700">
            <button
              type="button"
              @click="hideForm"
              class="px-4 py-2 text-sm font-medium border rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors"
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
            <div class="text-gray-600 dark:text-gray-400 whitespace-pre-line text-sm">
              {{ facility.description }}
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
