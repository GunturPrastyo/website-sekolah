<script setup>
import { ref, computed } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhDotsSixVertical,
  PhMagnifyingGlass,
} from "@phosphor-icons/vue";
import RichTextEditor from "@/components/RichTextEditor.vue";
import IconPicker, { educationIcons } from "@/components/IconPicker.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

// Dummy data, in a real app this would come from an API
const timeline = ref([
  {
    id: 1,
    year: "1985",
    title: "Pendirian & Peresmian SMAN 1",
    icon: "PhBuildings",
    image: "https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?q=80&w=800",
    description:
      "Sekolah ini resmi didirikan pada tanggal 17 Agustus 1985 berdasarkan SK Menteri Pendidikan. Pada awalnya, sekolah hanya memiliki 3 ruang kelas dengan 120 siswa angkatan pertama dan menumpang di gedung SMP terdekat selama proses pembangunan gedung utama berlangsung.",
  },
  {
    id: 2,
    year: "1992",
    title: "Pembangunan Gedung Utama",
    icon: "PhHammer",
    image: "https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=800",
    description:
      "Pembangunan gedung sekolah mandiri akhirnya selesai dan diresmikan oleh Gubernur. Di tahun ini, SMAN 1 mulai menempati lokasi saat ini dengan fasilitas yang diperluas, meliputi 12 ruang kelas, ruang guru, dan lapangan olahraga serbaguna.",
  },
  {
    id: 3,
    year: "2005",
    title: "Akreditasi A & Prestasi Nasional",
    icon: "PhMedal",
    image: "https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=800",
    description:
      "Berkat dedikasi seluruh civitas akademika, SMAN 1 berhasil meraih akreditasi A (Sangat Baik). Pada tahun yang sama, tim cerdas cermat sekolah berhasil membawa pulang piala Juara 1 tingkat Nasional untuk pertama kalinya.",
  },
  {
    id: 4,
    year: "2015",
    title: "Era Transformasi Digital",
    icon: "PhMonitor",
    image: "https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=800",
    description:
      "Menjawab tantangan abad 21, sekolah mulai mengintegrasikan teknologi ke dalam pembelajaran. Pembangunan laboratorium komputer modern, perpustakaan digital, serta pengadaan proyektor dan Wi-Fi di seluruh area sekolah mulai direalisasikan.",
  },
  {
    id: 5,
    year: "2021",
    title: "Sekolah Adiwiyata & Peduli Lingkungan",
    icon: "PhLeaf",
    image: "https://images.unsplash.com/photo-1466692476868-aef1dfb1e736?q=80&w=800",
    description:
      "Berkomitmen pada lingkungan yang asri, sekolah memenangkan penghargaan Sekolah Adiwiyata tingkat Provinsi. Program bank sampah, taman hidroponik, dan ruang hijau terpadu menjadi identitas baru SMAN 1.",
  },
  {
    id: 6,
    year: "2026",
    title: "Pelopor Kurikulum Merdeka",
    icon: "PhRocket",
    image: "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800",
    description:
      "Hari ini, SMAN 1 Nogosari terus melesat menjadi sekolah percontohan dalam implementasi Kurikulum Merdeka. Dengan lebih dari 1100 siswa, kami terus melahirkan lulusan yang cerdas, berkarakter, dan berdaya saing global.",
  },
]);

const form = ref({
  id: null,
  year: "",
  title: "",
  icon: "PhBuildings", // Default icon
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

const resetForm = () => {
  form.value = {
    id: null,
    year: "",
    title: "",
    icon: "PhBuildings",
    description: "",
  };
  isEditing.value = false;
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
  setTimeout(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
    document.querySelectorAll(".overflow-y-auto").forEach((el) => {
      el.scrollTo({ top: 0, behavior: "smooth" });
    });
  }, 100);
};

const addEntry = () => {
  const plainDesc = form.value.description.replace(/<[^>]*>?/gm, "").trim();
  if (!form.value.year || !form.value.title || !plainDesc) {
    triggerToast(
      "Gagal Menyimpan",
      "Kolom Tahun, Judul, dan Deskripsi wajib diisi!",
      "error"
    );
    return;
  }
  const newId =
    timeline.value.length > 0 ? Math.max(...timeline.value.map((e) => e.id)) + 1 : 1;
  timeline.value.push({
    ...form.value,
    id: newId,
  });
  isFormVisible.value = false; // Hide form after adding
  setTimeout(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
    document.querySelectorAll(".overflow-y-auto").forEach((el) => {
      el.scrollTo({ top: 0, behavior: "smooth" });
    });
  }, 100);
  triggerToast(
    "Berhasil Ditambahkan",
    "Entri lini masa baru telah ditambahkan ke sistem."
  );
  resetForm();
};

const startEdit = (entry) => {
  isEditing.value = true;
  form.value = {
    ...entry,
  };
  isFormVisible.value = true; // Show form for editing
  setTimeout(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
    document.querySelectorAll(".overflow-y-auto").forEach((el) => {
      el.scrollTo({ top: 0, behavior: "smooth" });
    });
  }, 100);
};

const saveEntry = () => {
  const plainDesc = form.value.description.replace(/<[^>]*>?/gm, "").trim();
  if (!form.value.year || !form.value.title || !plainDesc) {
    triggerToast(
      "Gagal Menyimpan",
      "Kolom Tahun, Judul, dan Deskripsi wajib diisi!",
      "error"
    );
    return;
  }
  const index = timeline.value.findIndex((e) => e.id === form.value.id);
  if (index !== -1) {
    timeline.value[index] = {
      ...form.value,
    };
  }
  isFormVisible.value = false; // Hide form after saving
  setTimeout(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
    document.querySelectorAll(".overflow-y-auto").forEach((el) => {
      el.scrollTo({ top: 0, behavior: "smooth" });
    });
  }, 100);
  triggerToast("Perubahan Disimpan", "Entri lini masa berhasil diperbarui.");
  resetForm();
};

const hideForm = () => {
  resetForm();
  isFormVisible.value = false;
  setTimeout(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
    document.querySelectorAll(".overflow-y-auto").forEach((el) => {
      el.scrollTo({ top: 0, behavior: "smooth" });
    });
  }, 100);
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
  if (itemToDelete.value !== null) {
    timeline.value = timeline.value.filter((e) => e.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast("Data Dihapus", "Satu entri lini masa berhasil dihapus.", "info");
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

const handleDrop = (entry) => {
  if (draggedItemIndex.value === null) return;
  const targetIndex = timeline.value.findIndex((e) => e.id === entry.id);
  if (draggedItemIndex.value === targetIndex) return; // Mencegah drop pada dirinya sendiri

  const draggedItem = timeline.value.splice(draggedItemIndex.value, 1)[0];
  timeline.value.splice(targetIndex, 0, draggedItem);
  draggedItemIndex.value = null;
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
    <div class="mb-8">
      <h2
        class="text-3xl font-bold text-gray-800 dark:text-white"
        style="font-family: 'Oswald', sans-serif"
      >
        Manajemen Sejarah Sekolah
      </h2>
      <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
        Kelola data lini masa sejarah SMAN 1 Nogosari
      </p>
    </div>

    <!-- Form Tambah/Edit Data -->
    <Transition
      enter-active-class="transition-all duration-500 ease-out"
      leave-active-class="transition-all duration-300 ease-in"
      enter-from-class="opacity-0 -translate-y-4 max-h-0"
      enter-to-class="opacity-100 translate-y-0 max-h-[1000px]"
      leave-from-class="opacity-100 translate-y-0 max-h-[1000px]"
      leave-to-class="opacity-0 -translate-y-4 max-h-0"
    >
      <div
        v-if="isFormVisible"
        class="bg-white dark:bg-slate-800 p-6 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm mb-8"
      >
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
          {{ isEditing ? "Edit Entri Timeline" : "Tambah Entri Baru" }}
        </h3>
        <form @submit.prevent="isEditing ? saveEntry() : addEntry()">
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
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Ikon Representasi</label
            >
            <IconPicker v-model="form.icon" />
          </div>

          <div class="mb-6">
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

          <div class="flex gap-3">
            <button
              type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-slate-800"
            >
              <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
              <PhPlusCircle v-else class="w-5 h-5 mr-2" />
              {{ isEditing ? "Simpan Perubahan" : "Tambah Entri" }}
            </button>
            <button
              v-if="isEditing"
              type="button"
              @click="hideForm"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-slate-800"
            >
              <PhXCircle class="w-5 h-5 mr-2" />
              Batal
            </button>
          </div>
        </form>
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
              class="w-6 h-6 text-blue-500 shrink-0 mt-0.5"
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
          class="text-center py-8 text-gray-500 dark:text-gray-400"
        >
          <p v-if="searchQuery">
            Tidak ada entri yang cocok dengan pencarian "{{ searchQuery }}".
          </p>
          <p v-else>Belum ada entri timeline.</p>
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
