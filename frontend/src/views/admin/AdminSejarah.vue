<script setup>
import { ref, computed, onMounted } from "vue";
import api from "@/api/index.js";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhDotsSixVertical,
  PhMagnifyingGlass,
  PhX,
  PhClockCounterClockwise,
  PhBuildings,
} from "@phosphor-icons/vue";
import RichTextEditor from "@/components/RichTextEditor.vue";
import IconPicker, { educationIcons } from "@/components/IconPicker.vue";
import ImageUploader from "@/components/admin/ImageUploader.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const timeline = ref([]);

const schoolProfile = ref({
  description: "",
  npsn: "",
  accreditation: "",
  location: "",
  status: "",
  image: "",
});

const isProfileModalVisible = ref(false);
const tempProfile = ref({});

const form = ref({
  id: null,
  year: "",
  title: "",
  icon: "PhBuildings", // Default icon
  color: "text-blue-500",
  description: "",
});

const isFormVisible = ref(false); // Controls form visibility
const isEditing = ref(false); // Indicates if an entry is being edited
const isDeleteModalOpen = ref(false); // Indicates if delete modal is open
const itemToDelete = ref(null); // Stores the id of the item to be deleted

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

const fetchData = async () => {
  try {
    // Dipisah menggunakan try-catch individu agar jika salah satu API 404, yang lain tetap jalan
    try {
      const profileRes = await api.get("/api/profil-sekolah");
      if (profileRes.data?.data) schoolProfile.value = profileRes.data.data;
    } catch (err) {
      console.warn("API profil-sekolah gagal dimuat atau belum tersedia (404).", err);
    }
    try {
      const timelineRes = await api.get("/api/sejarah");
      if (timelineRes.data?.data) timeline.value = timelineRes.data.data;
    } catch (err) {
      console.warn("API sejarah gagal dimuat atau belum tersedia (404).", err);
    }
  } catch (error) {
    console.error("Gagal mengambil data dari API:", error);
    triggerToast("Gagal Memuat", "Tidak dapat memuat data dari server.", "error");
  }
};

onMounted(() => {
  fetchData();
});

const resetForm = () => {
  form.value = {
    id: null,
    year: "",
    title: "",
    icon: "PhBuildings",
    color: "text-blue-500",
    description: "",
  };
  isEditing.value = false;
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const addEntry = async () => {
  const plainDesc = form.value.description.replace(/<[^>]*>?/gm, "").trim();
  if (!form.value.year || !form.value.title || !plainDesc) {
    triggerToast(
      "Gagal Menyimpan",
      "Kolom Tahun, Judul, dan Deskripsi wajib diisi!",
      "error"
    );
    return;
  }

  try {
    const response = await api.post("/api/sejarah", form.value);
    timeline.value.push(response.data.data); // Asumsikan API me-return resource yang baru disimpan
    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast(
      "Berhasil Ditambahkan",
      "Entri lini masa baru telah ditambahkan ke sistem."
    );
    resetForm();
  } catch (error) {
    console.error(error);
    triggerToast(
      "Gagal Menyimpan",
      "Terjadi kesalahan pada server saat menambahkan data.",
      "error"
    );
  }
};

const openProfileModal = () => {
  tempProfile.value = { ...schoolProfile.value };
  isProfileModalVisible.value = true;
  document.body.style.overflow = "hidden";
};

const closeProfileModal = () => {
  isProfileModalVisible.value = false;
  document.body.style.overflow = "";
};

const saveProfile = async () => {
  try {
    // Menggunakan langsung method PUT via axios dengan payload JSON
    const response = await api.put("/api/profil-sekolah", tempProfile.value);

    schoolProfile.value = response.data.data;
    isProfileModalVisible.value = false;
    document.body.style.overflow = "";
    triggerToast("Profil Disimpan", "Data profil singkat sekolah berhasil diperbarui.");
  } catch (error) {
    console.error(error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat menyimpan profil.", "error");
  }
};

const startEdit = (entry) => {
  isEditing.value = true;
  form.value = {
    ...entry,
    color: entry.color || "text-blue-500",
  };
  isFormVisible.value = true; // Show form for editing
  document.body.style.overflow = "hidden";
};

const saveEntry = async () => {
  const plainDesc = form.value.description.replace(/<[^>]*>?/gm, "").trim();
  if (!form.value.year || !form.value.title || !plainDesc) {
    triggerToast(
      "Gagal Menyimpan",
      "Kolom Tahun, Judul, dan Deskripsi wajib diisi!",
      "error"
    );
    return;
  }

  try {
    const response = await api.put(`/api/sejarah/${form.value.id}`, form.value);
    const index = timeline.value.findIndex((e) => e.id === form.value.id);
    if (index !== -1) {
      timeline.value[index] = response.data.data;
    }
    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast("Perubahan Disimpan", "Entri lini masa berhasil diperbarui.");
    resetForm();
  } catch (error) {
    console.error(error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat memperbarui entri.", "error");
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
      await api.delete(`/api/sejarah/${itemToDelete.value}`);
      timeline.value = timeline.value.filter((e) => e.id !== itemToDelete.value);
      itemToDelete.value = null;
      triggerToast("Data Dihapus", "Satu entri lini masa berhasil dihapus.", "info");
    } catch (error) {
      console.error(error);
      triggerToast("Gagal Menghapus", "Terjadi kesalahan saat menghapus data.", "error");
    }
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

// State dan fungsi untuk Drag & Drop
const draggedItemIndex = ref(null);

const handleDragStart = (entry, event) => {
  draggedItemIndex.value = timeline.value.findIndex((e) => e.id === entry.id);
  event.dataTransfer.effectAllowed = "move";
};

const handleDrop = async (entry) => {
  if (draggedItemIndex.value === null) return;
  const targetIndex = timeline.value.findIndex((e) => e.id === entry.id);
  if (draggedItemIndex.value === targetIndex) return; // Mencegah drop pada dirinya sendiri

  const draggedItem = timeline.value.splice(draggedItemIndex.value, 1)[0];
  timeline.value.splice(targetIndex, 0, draggedItem);
  draggedItemIndex.value = null;

  try {
    // Menyimpan urutan baru ke database
    const orders = timeline.value.map((item, index) => ({ id: item.id, order: index }));
    await api.post("/api/sejarah/reorder", { orders });
  } catch (error) {
    console.error("Gagal menyimpan urutan baru:", error);
    triggerToast("Gagal", "Terjadi kesalahan saat memperbarui urutan timeline.", "error");
  }
};

const searchQuery = ref("");

const filteredTimeline = computed(() => {
  if (!searchQuery.value) return timeline.value;
  const query = searchQuery.value.toLowerCase();
  return timeline.value.filter(
    (entry) =>
      entry.year.toLowerCase().includes(query) ||
      entry.title.toLowerCase().includes(query) ||
      entry.description.toLowerCase().includes(query)
  );
});
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div
      class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
    >
      <div>
        <h2
          class="text-3xl font-bold text-gray-800 dark:text-white"
          style="font-family: 'Oswald', sans-serif"
        >
          Manajemen Profil & Sejarah
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola profil singkat dan data lini masa sejarah SMAN 1 Nogosari
        </p>
      </div>
    </div>

    <!-- Profil Singkat Sekolah (View Mode) -->
    <div
      class="bg-white dark:bg-slate-800 p-6 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm mb-12 relative group"
    >
      <div
        class="flex justify-between items-center mb-4 border-b border-gray-100 dark:border-slate-700 pb-3"
      >
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
          Profil Singkat Sekolah
        </h3>
        <button
          @click="openProfileModal"
          class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
        >
          <PhPencilSimple class="w-4 h-4 mr-2" />
          Edit Profil
        </button>
      </div>
      <div
        v-if="schoolProfile.description || schoolProfile.npsn"
        class="grid grid-cols-1 lg:grid-cols-3 gap-8"
      >
        <div class="lg:col-span-2 space-y-4">
          <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
              Deskripsi Profil
            </p>
            <p class="text-gray-900 dark:text-white leading-relaxed">
              {{ schoolProfile.description }}
            </p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                NPSN
              </p>
              <p class="text-gray-900 dark:text-white font-medium">
                {{ schoolProfile.npsn }}
              </p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                Akreditasi
              </p>
              <p class="text-gray-900 dark:text-white font-medium">
                {{ schoolProfile.accreditation }}
              </p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                Lokasi
              </p>
              <p class="text-gray-900 dark:text-white font-medium">
                {{ schoolProfile.location }}
              </p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                Status
              </p>
              <p class="text-gray-900 dark:text-white font-medium">
                {{ schoolProfile.status }}
              </p>
            </div>
          </div>
        </div>
        <div class="lg:col-span-1">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
            Foto Gedung Utama
          </p>
          <div
            class="w-full aspect-[4/3] rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-slate-700"
          >
            <img
              v-if="schoolProfile.image"
              :src="schoolProfile.image"
              class="w-full h-full object-cover"
            />
          </div>
        </div>
      </div>
      <div
        v-else
        class="py-12 mt-4 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
      >
        <div
          class="mx-auto w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4"
        >
          <PhBuildings class="w-8 h-8 text-gray-400 dark:text-gray-500" />
        </div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
          Belum Ada Data Profil Sekolah
        </h3>
        <p class="text-sm">
          Silakan klik tombol "Edit Profil" untuk mulai melengkapi informasi singkat
          sekolah.
        </p>
      </div>
    </div>

    <!-- Modal Form Edit Profil -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isProfileModalVisible"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closeProfileModal"
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
              Edit Profil Sekolah
            </h3>
            <button
              @click="closeProfileModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>

          <!-- Modal Body -->
          <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form id="profileForm" @submit.prevent="saveProfile">
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4">
                  <div>
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                      Deskripsi Profil
                    </label>
                    <textarea
                      v-model="tempProfile.description"
                      rows="6"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition-colors"
                      placeholder="Masukkan deskripsi profil singkat..."
                      required
                    ></textarea>
                  </div>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >NPSN</label
                      >
                      <input
                        type="text"
                        v-model="tempProfile.npsn"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                        required
                      />
                    </div>
                    <div>
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Akreditasi</label
                      >
                      <input
                        type="text"
                        v-model="tempProfile.accreditation"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                        required
                      />
                    </div>
                    <div>
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Lokasi</label
                      >
                      <input
                        type="text"
                        v-model="tempProfile.location"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                        required
                      />
                    </div>
                    <div>
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Status</label
                      >
                      <input
                        type="text"
                        v-model="tempProfile.status"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                        required
                      />
                    </div>
                  </div>
                </div>
                <div class="lg:col-span-1">
                  <ImageUploader
                    v-model="tempProfile.image"
                    label="Foto Gedung Utama"
                    containerClass="w-full aspect-[4/3] mx-auto"
                    imageClass="object-cover rounded-xl"
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
              @click="closeProfileModal"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
              <PhXCircle class="w-5 h-5 mr-2" />
              Batal
            </button>
            <button
              form="profileForm"
              type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-slate-800 transition-colors"
            >
              <PhFloppyDisk class="w-5 h-5 mr-2" />
              Simpan Profil
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <div class="flex justify-between items-center mb-6">
      <h3
        class="text-2xl font-bold text-gray-800 dark:text-white"
        style="font-family: 'Oswald', sans-serif"
      >
        Lini Masa Sejarah
      </h3>
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
              {{ isEditing ? "Edit Entri Timeline" : "Tambah Entri Baru" }}
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
              id="timelineForm"
              @submit.prevent="isEditing ? saveEntry() : addEntry()"
            >
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <label
                    for="year"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Tahun</label
                  >
                  <input
                    type="text"
                    id="year"
                    v-model="form.year"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: 1985"
                    required
                  />
                </div>
                <div>
                  <label
                    for="title"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Judul</label
                  >
                  <input
                    type="text"
                    id="title"
                    v-model="form.title"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: Pendirian SMAN 1"
                    required
                  />
                </div>
              </div>

              <div class="mb-4">
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Ikon Representasi</label
                >
                <IconPicker v-model="form.icon" v-model:color-value="form.color" />
              </div>

              <div class="mb-2">
                <label
                  for="description"
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Deskripsi</label
                >
                <RichTextEditor
                  v-model="form.description"
                  placeholder="Masukkan deskripsi lengkap entri timeline..."
                />
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
              form="timelineForm"
              type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-slate-800 transition-colors"
            >
              <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
              <PhPlusCircle v-else class="w-5 h-5 mr-2" />
              {{ isEditing ? "Simpan Perubahan" : "Tambah Entri" }}
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Daftar Entri Timeline -->
    <div
      class="bg-white dark:bg-slate-800 p-6 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm"
    >
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
          Daftar Entri Timeline
        </h3>
        <button
          v-if="!isFormVisible"
          @click="showAddForm"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-slate-800"
        >
          <PhPlusCircle class="w-5 h-5 mr-2" />
          Tambah Entri Baru
        </button>
      </div>

      <!-- Kolom Pencarian -->
      <div class="mb-6 relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
        </div>
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Cari berdasarkan tahun, judul, atau deskripsi..."
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors"
        />
      </div>

      <div class="space-y-4">
        <div
          v-for="entry in filteredTimeline"
          :key="entry.id"
          :draggable="!searchQuery"
          @dragstart="handleDragStart(entry, $event)"
          @dragover.prevent
          @dragenter.prevent
          @drop="handleDrop(entry)"
          class="flex items-start justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg border border-gray-100 dark:border-slate-600 hover:shadow-md transition-shadow group gap-4"
          :class="!searchQuery ? 'cursor-move' : ''"
          :title="!searchQuery ? 'Tahan dan geser untuk mengubah urutan' : ''"
        >
          <div class="flex items-start gap-3 flex-1 overflow-hidden">
            <PhDotsSixVertical
              class="w-6 h-6 shrink-0 transition-colors mt-0.5"
              :class="
                !searchQuery
                  ? 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 cursor-grab active:cursor-grabbing'
                  : 'text-gray-300 dark:text-gray-600 opacity-50 cursor-not-allowed'
              "
            />
            <component
              :is="educationIcons[entry.icon]"
              class="w-6 h-6 shrink-0 mt-0.5"
              :class="entry.color || 'text-blue-500'"
            />
            <div class="flex-1 overflow-hidden">
              <p class="font-semibold text-gray-800 dark:text-white">
                {{ entry.year }} - {{ entry.title }}
              </p>
              <div
                class="text-sm text-gray-600 dark:text-gray-400 mt-1 editor-content-preview"
                v-html="entry.description"
              ></div>
            </div>
          </div>
          <div class="flex gap-2 shrink-0">
            <button
              @click="startEdit(entry)"
              class="p-2 rounded-full text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
              title="Edit"
            >
              <PhPencilSimple class="w-5 h-5" />
            </button>
            <button
              @click="deleteEntry(entry.id)"
              class="p-2 rounded-full text-red-600 hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors"
              title="Hapus"
            >
              <PhTrash class="w-5 h-5" />
            </button>
          </div>
        </div>
        <div
          v-if="filteredTimeline.length === 0"
          class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
        >
          <div
            class="mx-auto w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4"
          >
            <PhClockCounterClockwise class="w-8 h-8 text-gray-400 dark:text-gray-500" />
          </div>
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
            Belum Ada Entri Sejarah
          </h3>
          <p v-if="searchQuery">
            Tidak ada entri yang cocok dengan pencarian "{{ searchQuery }}".
          </p>
          <p v-else class="text-sm">
            Silakan tambahkan entri timeline baru untuk mulai melengkapi sejarah sekolah.
          </p>
        </div>
      </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Entri Timeline"
      message="Apakah Anda yakin ingin menghapus entri sejarah ini? Data yang telah dihapus tidak dapat dikembalikan."
      @confirm="confirmDelete"
      @cancel="cancelDelete"
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
