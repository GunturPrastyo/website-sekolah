<script setup>
import { ref, computed } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhFile,
  PhFileText,
  PhArchive,
  PhImage,
  PhDownloadSimple,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const categories = [
  { id: "akademik", name: "Akademik & Kurikulum" },
  { id: "kesiswaan", name: "Kesiswaan & OSIS" },
  { id: "ppdb", name: "Brosur & PPDB" },
  { id: "umum", name: "Dokumen Umum" },
];

const filesList = ref([
  {
    id: 1,
    name: "Kalender_Akademik_2025_2026.pdf",
    type: "pdf",
    size: "2.4 MB",
    date: "15 Jan 2026",
    category: "akademik",
  },
  {
    id: 2,
    name: "Brosur_PPDB_SMAN1_Nogosari.pdf",
    type: "pdf",
    size: "5.1 MB",
    date: "10 Feb 2026",
    category: "ppdb",
  },
  {
    id: 3,
    name: "Formulir_Pendaftaran_Ekskul.docx",
    type: "docx",
    size: "1.2 MB",
    date: "05 Feb 2026",
    category: "kesiswaan",
  },
  {
    id: 4,
    name: "SOP_Tata_Tertib_Siswa_Rev.pdf",
    type: "pdf",
    size: "3.5 MB",
    date: "20 Jan 2026",
    category: "umum",
  },
  {
    id: 5,
    name: "Modul_Latihan_UTBK_Saintek.zip",
    type: "zip",
    size: "15.8 MB",
    date: "12 Jan 2026",
    category: "akademik",
  },
  {
    id: 6,
    name: "Buku_Panduan_OSIS_2026.pdf",
    type: "pdf",
    size: "4.2 MB",
    date: "02 Feb 2026",
    category: "kesiswaan",
  },
  {
    id: 7,
    name: "Logo_Resmi_SMAN1_Nogosari.png",
    type: "image",
    size: "1.8 MB",
    date: "25 Jan 2026",
    category: "umum",
  },
  {
    id: 8,
    name: "Juknis_Pelaksanaan_PPDB.docx",
    type: "docx",
    size: "850 KB",
    date: "11 Feb 2026",
    category: "ppdb",
  },
]);

const form = ref({
  id: null,
  name: "",
  category: "umum",
  file: null,
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");
const fileInput = ref(null);

const getFileIcon = (type) => {
  switch (type) {
    case "pdf":
      return PhFileText;
    case "docx":
      return PhFileText;
    case "zip":
      return PhArchive;
    case "image":
      return PhImage;
    default:
      return PhFile;
  }
};

const getFileIconColor = (type) => {
  switch (type) {
    case "pdf":
      return "text-red-500 dark:text-red-400";
    case "docx":
      return "text-blue-500 dark:text-blue-400";
    case "zip":
      return "text-yellow-500 dark:text-yellow-400";
    case "image":
      return "text-emerald-500 dark:text-emerald-400";
    default:
      return "text-gray-500 dark:text-gray-400";
  }
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
    name: "",
    category: "umum",
    file: null,
  };
  if (fileInput.value) {
    fileInput.value.value = "";
  }
  isEditing.value = false;
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.value.file = file;
    if (!form.value.name) {
      form.value.name = file.name;
    }
  }
};

const extractFileType = (fileName) => {
  const ext = fileName.split(".").pop().toLowerCase();
  if (["pdf"].includes(ext)) return "pdf";
  if (["doc", "docx"].includes(ext)) return "docx";
  if (["zip", "rar"].includes(ext)) return "zip";
  if (["png", "jpg", "jpeg", "webp"].includes(ext)) return "image";
  return ext;
};

const addEntry = () => {
  if (!form.value.name) {
    triggerToast("Gagal Menyimpan", "Nama File wajib diisi!", "error");
    return;
  }

  let type = "unknown";
  let size = "Unknown Size";
  let name = form.value.name;

  if (form.value.file) {
    type = extractFileType(form.value.file.name);
    const sizeInMB = (form.value.file.size / (1024 * 1024)).toFixed(1);
    const sizeInKB = (form.value.file.size / 1024).toFixed(0);
    size = form.value.file.size > 1024 * 1024 ? `${sizeInMB} MB` : `${sizeInKB} KB`;
  } else {
    type = extractFileType(name);
    size = "0 KB";
  }

  const newId =
    filesList.value.length > 0 ? Math.max(...filesList.value.map((s) => s.id)) + 1 : 1;

  const today = new Date();
  const dateStr = `${today.getDate()} ${today.toLocaleString("id-ID", {
    month: "short",
  })} ${today.getFullYear()}`;

  filesList.value.unshift({
    id: newId,
    name: name,
    category: form.value.category,
    type: type,
    size: size,
    date: dateStr,
  });

  isFormVisible.value = false;
  triggerToast("Berhasil Ditambahkan", "Data file baru telah ditambahkan ke sistem.");
  resetForm();
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = {
    id: item.id,
    name: item.name,
    category: item.category,
    file: null,
  };
  isFormVisible.value = true;
  window.scrollTo({ top: 0, behavior: "smooth" });
};

const saveEntry = () => {
  if (!form.value.name) {
    triggerToast("Gagal Menyimpan", "Nama File wajib diisi!", "error");
    return;
  }
  const index = filesList.value.findIndex((s) => s.id === form.value.id);
  if (index !== -1) {
    const item = filesList.value[index];
    let newType = item.type;
    let newSize = item.size;
    let newName = form.value.name;

    if (form.value.file) {
      newType = extractFileType(form.value.file.name);
      const sizeInMB = (form.value.file.size / (1024 * 1024)).toFixed(1);
      const sizeInKB = (form.value.file.size / 1024).toFixed(0);
      newSize = form.value.file.size > 1024 * 1024 ? `${sizeInMB} MB` : `${sizeInKB} KB`;
    } else {
      newType = extractFileType(newName);
    }

    filesList.value[index] = {
      ...item,
      name: newName,
      category: form.value.category,
      type: newType,
      size: newSize,
    };
  }

  isFormVisible.value = false;
  triggerToast("Perubahan Disimpan", "Data file berhasil diperbarui.");
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
    filesList.value = filesList.value.filter((s) => s.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast("Data Dihapus", "File berhasil dihapus dari sistem.", "info");
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredFiles = computed(() => {
  if (!searchQuery.value) return filesList.value;
  const query = searchQuery.value.toLowerCase();
  return filesList.value.filter((item) => item.name.toLowerCase().includes(query));
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
          Manajemen Unduhan
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola dokumen dan berkas informasi yang dapat diunduh oleh publik.
        </p>
      </div>
      <button
        v-if="!isFormVisible"
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Unggah File
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
          {{ isEditing ? "Edit Data File" : "Unggah File Baru" }}
        </h3>
        <form @submit.prevent="isEditing ? saveEntry() : addEntry()">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Pilih File
                {{ isEditing ? "(Opsional - Kosongkan jika tidak mengubah file)" : "" }}
              </label>
              <input
                type="file"
                ref="fileInput"
                @change="handleFileUpload"
                :required="!isEditing"
                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-blue-400 dark:hover:file:bg-slate-600 transition-colors cursor-pointer border border-gray-300 dark:border-slate-600 rounded-lg p-1 bg-gray-50 dark:bg-slate-700"
              />
            </div>

            <div class="md:col-span-1">
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >Nama File</label
              >
              <input
                type="text"
                v-model="form.name"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                placeholder="Contoh: Kalender_Akademik_2025.pdf"
              />
            </div>

            <div class="md:col-span-1">
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >Kategori Direktori</label
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
              {{ isEditing ? "Simpan Perubahan" : "Unggah File" }}
            </button>
          </div>
        </form>
      </div>
    </Transition>

    <!-- Data Table -->
    <div
      class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden"
    >
      <div
        class="p-6 border-b border-gray-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
      >
        <div class="relative w-full max-w-md">
          <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
          >
            <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
          </div>
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Cari nama file..."
            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr
              class="bg-gray-50 dark:bg-slate-700/50 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              <th class="px-6 py-4">Nama File</th>
              <th class="px-6 py-4 hidden md:table-cell">Kategori</th>
              <th class="px-6 py-4 hidden sm:table-cell">Ukuran</th>
              <th class="px-6 py-4 hidden lg:table-cell">Tanggal Diunggah</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            <tr v-if="filteredFiles.length === 0">
              <td
                colspan="5"
                class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
              >
                <PhFile class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-500 mb-3" />
                <p>Tidak ada data file yang ditemukan.</p>
              </td>
            </tr>
            <tr
              v-for="file in filteredFiles"
              :key="file.id"
              class="hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors group"
            >
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <component
                    :is="getFileIcon(file.type)"
                    class="w-8 h-8 shrink-0"
                    :class="getFileIconColor(file.type)"
                    weight="fill"
                  />
                  <div>
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-gray-200"
                    >
                      {{ file.name }}
                    </span>
                    <span
                      class="block md:hidden text-xs text-gray-400 dark:text-gray-500 mt-0.5"
                    >
                      {{ getCategoryName(file.category) }} • {{ file.size }}
                    </span>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 hidden md:table-cell">
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-slate-700 dark:text-gray-300"
                >
                  {{ getCategoryName(file.category) }}
                </span>
              </td>
              <td
                class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell font-medium"
              >
                {{ file.size }}
              </td>
              <td
                class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell"
              >
                {{ file.date }}
              </td>
              <td class="px-6 py-4 text-right">
                <div
                  class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <button
                    @click="startEdit(file)"
                    class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors"
                    title="Edit Data"
                  >
                    <PhPencilSimple class="w-4 h-4" />
                  </button>
                  <button
                    @click="deleteEntry(file.id)"
                    class="p-1.5 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-md transition-colors"
                    title="Hapus File"
                  >
                    <PhTrash class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus File Unduhan"
      message="Yakin ingin menghapus file ini? File yang dihapus tidak akan tersedia lagi untuk diunduh."
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