<script setup>
import { ref, computed, onMounted } from "vue";
import api from "@/api/index.js";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhGraduationCap,
  PhBriefcase,
  PhBookOpen,
  PhX,
} from "@phosphor-icons/vue";
import ImageUploader from "@/components/admin/ImageUploader.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const programList = ref([]);

const form = ref({
  id: null,
  title: "",
  badge: "",
  description: "",
  subjects: "",
  careers: "",
  image: "",
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");

const fetchPrograms = async () => {
  try {
    const response = await api.get("/api/programs");
    programList.value = response.data.data;
  } catch (error) {
    triggerToast("Error", "Gagal memuat data program jurusan", "error");
  }
};

onMounted(() => {
  fetchPrograms();
});

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
    badge: "",
    description: "",
    subjects: "",
    careers: "",
    image: "",
  };
  isEditing.value = false;
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const addEntry = async () => {
  if (!form.value.title || !form.value.badge) {
    triggerToast("Gagal Menyimpan", "Nama Program dan Kategori wajib diisi!", "error");
    return;
  }

  try {
    const response = await api.post("/api/programs", form.value);
    programList.value.push(response.data.data);

    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast(
      "Berhasil Ditambahkan",
      "Data program jurusan baru telah ditambahkan ke sistem."
    );
    resetForm();
  } catch (error) {
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat menyimpan data", "error");
  }
};

const startEdit = (program) => {
  isEditing.value = true;
  form.value = { ...program };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = async () => {
  if (!form.value.title || !form.value.badge) {
    triggerToast("Gagal Menyimpan", "Nama Program dan Kategori wajib diisi!", "error");
    return;
  }

  try {
    const response = await api.put(`/api/programs/${form.value.id}`, form.value);
    const index = programList.value.findIndex((s) => s.id === form.value.id);
    if (index !== -1) {
      programList.value[index] = response.data.data;
    }

    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast("Perubahan Disimpan", "Data program jurusan berhasil diperbarui.");
    resetForm();
  } catch (error) {
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat memperbarui data", "error");
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
      await api.delete(`/api/programs/${itemToDelete.value}`);
      programList.value = programList.value.filter((s) => s.id !== itemToDelete.value);
      itemToDelete.value = null;
      triggerToast(
        "Data Dihapus",
        "Data program jurusan berhasil dihapus dari sistem.",
        "info"
      );
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

const filteredPrograms = computed(() => {
  if (!searchQuery.value) return programList.value;
  const query = searchQuery.value.toLowerCase();
  return programList.value.filter(
    (prog) =>
      prog.title.toLowerCase().includes(query) || prog.badge.toLowerCase().includes(query)
  );
});
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2
          class="text-3xl font-bold text-gray-800 dark:text-white"
          style="font-family: 'Oswald', sans-serif"
        >
          Manajemen Program Jurusan
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data jurusan, fokus pembelajaran, dan prospek karir siswa.
        </p>
      </div>
      <button
        v-if="!isFormVisible"
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Jurusan
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
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all"
          @click.stop
        >
          <!-- Modal Header -->
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
              {{ isEditing ? "Edit Data Program Jurusan" : "Tambah Data Jurusan Baru" }}
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
            <form id="programForm" @submit.prevent="isEditing ? saveEntry() : addEntry()">
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Kiri: Gambar -->
                <div class="lg:col-span-1">
                  <ImageUploader
                    v-model="form.image"
                    label="Gambar Utama Jurusan"
                    containerClass="w-full aspect-[4/3] sm:aspect-video lg:aspect-[4/3] mx-auto"
                    imageClass="object-cover rounded-xl"
                  />
                  <p
                    class="text-xs text-gray-500 dark:text-gray-400 mt-3 text-center lg:text-left max-w-sm mx-auto"
                  >
                    Rasio gambar terbaik adalah 16:9 atau 4:3.
                  </p>
                </div>

                <!-- Kolom Kanan: Form Data -->
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                      Nama Program / Jurusan
                    </label>
                    <input
                      type="text"
                      v-model="form.title"
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Contoh: Ilmu Pengetahuan Alam (IPA)"
                    />
                  </div>

                  <div class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                      Kategori / Badge
                    </label>
                    <input
                      type="text"
                      v-model="form.badge"
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Contoh: Sains & Teknologi"
                    />
                  </div>

                  <div class="md:col-span-2">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                      Deskripsi Jurusan
                    </label>
                    <textarea
                      v-model="form.description"
                      rows="3"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Jelaskan secara singkat mengenai jurusan ini..."
                    ></textarea>
                  </div>

                  <div class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                      Fokus Pembelajaran
                      <span class="text-xs text-gray-500">(Pisahkan dengan koma)</span>
                    </label>
                    <textarea
                      v-model="form.subjects"
                      rows="3"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Contoh: Fisika Lanjutan, Biologi Terpadu, Kimia Terapan"
                    ></textarea>
                  </div>

                  <div class="md:col-span-1">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                      Prospek Karir
                      <span class="text-xs text-gray-500">(Pisahkan dengan koma)</span>
                    </label>
                    <textarea
                      v-model="form.careers"
                      rows="3"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Contoh: Dokter, Insinyur, Ilmuwan, Apoteker"
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
              type="submit"
              form="programForm"
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

    <!-- List Section -->
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
          placeholder="Cari jurusan..."
          class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        />
      </div>

      <div
        v-if="filteredPrograms.length === 0"
        class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
      >
        <p>Tidak ada data jurusan yang ditemukan.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div
          v-for="prog in filteredPrograms"
          :key="prog.id"
          class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm flex flex-col overflow-hidden relative group"
        >
          <div
            class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 p-1 rounded-lg border border-gray-100 dark:border-slate-700 z-10"
          >
            <button
              @click="startEdit(prog)"
              class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md"
            >
              <PhPencilSimple class="w-4 h-4" />
            </button>
            <button
              @click="deleteEntry(prog.id)"
              class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md"
            >
              <PhTrash class="w-4 h-4" />
            </button>
          </div>

          <!-- Banner Gambar -->
          <div class="w-full h-40 sm:h-48 bg-gray-100 dark:bg-slate-700 relative">
            <img v-if="prog.image" :src="prog.image" class="w-full h-full object-cover" />
            <div
              v-else
              class="w-full h-full flex items-center justify-center text-gray-400"
            >
              <PhGraduationCap class="w-12 h-12 opacity-50" />
            </div>
            <div
              class="absolute bottom-3 left-3 bg-blue-600/90 backdrop-blur-sm px-2.5 py-1 text-white text-[10px] font-bold uppercase tracking-wider rounded"
            >
              {{ prog.badge }}
            </div>
          </div>

          <div class="p-5 flex flex-col flex-1">
            <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-2">
              {{ prog.title }}
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 mb-4 flex-1">
              {{ prog.description }}
            </p>
            <div
              class="grid grid-cols-2 gap-4 mt-auto border-t border-gray-100 dark:border-slate-700 pt-4"
            >
              <div>
                <p
                  class="text-xs font-semibold text-gray-700 dark:text-gray-300 flex items-center mb-1"
                >
                  <PhBookOpen class="w-3.5 h-3.5 mr-1" /> Topik:
                </p>
                <p class="text-xs text-gray-500 line-clamp-2">{{ prog.subjects }}</p>
              </div>
              <div>
                <p
                  class="text-xs font-semibold text-gray-700 dark:text-gray-300 flex items-center mb-1"
                >
                  <PhBriefcase class="w-3.5 h-3.5 mr-1" /> Karir:
                </p>
                <p class="text-xs text-gray-500 line-clamp-2">{{ prog.careers }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Data Jurusan"
      message="Yakin ingin menghapus data ini?"
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
