<script setup>
import { ref, computed } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhUser,
  PhEnvelopeSimple,
  PhLinkedinLogo,
} from "@phosphor-icons/vue";
import ImageUploader from "@/components/admin/ImageUploader.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

// Kategori Staf
const categories = [
  { id: "pimpinan", name: "Pimpinan Sekolah" },
  { id: "pendidik", name: "Tenaga Pendidik" },
  { id: "kependidikan", name: "Tenaga Kependidikan" },
];

// Saran Jabatan/Posisi Umum (Datalist Autocomplete)
const commonRoles = [
  "Kepala Sekolah",
  "Wakil Kepala Sekolah",
  "Guru Matematika",
  "Guru Bahasa Indonesia",
  "Guru Bahasa Inggris",
  "Guru Fisika",
  "Guru Kimia",
  "Guru Biologi",
  "Guru Pendidikan Agama",
  "Guru Penjasorkes",
  "Guru Seni Budaya",
  "Guru Informatika",
  "Guru Bimbingan Konseling (BK)",
  "Kepala Tata Usaha",
  "Staf Tata Usaha",
  "Pustakawan",
  "Laboran",
  "Petugas Keamanan",
  "Petugas Kebersihan",
];

// Dummy Data
const staffList = ref([
  {
    id: 1,
    name: "Dr. H. Budi Santoso, M.Pd",
    role: "Kepala Sekolah",
    category: "pimpinan",
    image:
      "https://ui-avatars.com/api/?name=Budi+Santoso&background=0D8ABC&color=fff&size=256",
    nip: "19750817 200003 1 004",
    email: "budi.santoso@sman1nogosari.sch.id",
    linkedin: "https://linkedin.com/in/budisantoso",
  },
  {
    id: 2,
    name: "Siti Aminah, M.Pd",
    role: "Wakil Kepala Sekolah",
    category: "pimpinan",
    image:
      "https://ui-avatars.com/api/?name=Siti+Aminah&background=0D8ABC&color=fff&size=256",
    nip: "19800512 200501 2 001",
    email: "siti.aminah@sman1nogosari.sch.id",
    linkedin: "",
  },
  {
    id: 3,
    name: "Ahmad Fauzi, S.Kom",
    role: "Guru Informatika",
    category: "pendidik",
    image:
      "https://ui-avatars.com/api/?name=Ahmad+Fauzi&background=0D8ABC&color=fff&size=256",
    nip: "19900215 201504 1 003",
    email: "",
    linkedin: "https://linkedin.com/in/ahmadfauzi",
  },
]);

const form = ref({
  id: null,
  name: "",
  role: "",
  category: "pimpinan",
  nip: "",
  email: "",
  linkedin: "",
  image: "",
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");

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
    role: "",
    category: "pimpinan",
    nip: "",
    email: "",
    linkedin: "",
    image: "",
  };
  isEditing.value = false;
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
};

const addEntry = () => {
  if (!form.value.name || !form.value.role || !form.value.category) {
    triggerToast("Gagal Menyimpan", "Nama, Jabatan, dan Kategori wajib diisi!", "error");
    return;
  }
  const newId =
    staffList.value.length > 0 ? Math.max(...staffList.value.map((s) => s.id)) + 1 : 1;
  staffList.value.push({ ...form.value, id: newId });

  isFormVisible.value = false;
  triggerToast("Berhasil Ditambahkan", "Data staf baru telah ditambahkan ke sistem.");
  resetForm();
};

const startEdit = (staff) => {
  isEditing.value = true;
  form.value = { ...staff };
  isFormVisible.value = true;
  window.scrollTo({ top: 0, behavior: "smooth" });
};

const saveEntry = () => {
  if (!form.value.name || !form.value.role || !form.value.category) {
    triggerToast("Gagal Menyimpan", "Nama, Jabatan, dan Kategori wajib diisi!", "error");
    return;
  }
  const index = staffList.value.findIndex((s) => s.id === form.value.id);
  if (index !== -1) {
    staffList.value[index] = { ...form.value };
  }

  isFormVisible.value = false;
  triggerToast("Perubahan Disimpan", "Data staf berhasil diperbarui.");
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
    staffList.value = staffList.value.filter((s) => s.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast("Data Dihapus", "Data staf berhasil dihapus dari sistem.", "info");
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredStaff = computed(() => {
  if (!searchQuery.value) return staffList.value;
  const query = searchQuery.value.toLowerCase();
  return staffList.value.filter(
    (staff) =>
      staff.name.toLowerCase().includes(query) ||
      staff.role.toLowerCase().includes(query) ||
      staff.nip.toLowerCase().includes(query)
  );
});

const getCategoryName = (id) => {
  const category = categories.find((c) => c.id === id);
  return category ? category.name : id;
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white" style="font-family: 'Oswald', sans-serif">
          Manajemen Guru & Staf
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data tenaga pendidik dan tenaga kependidikan sekolah.
        </p>
      </div>
      <button
        v-if="!isFormVisible"
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Data Staf
      </button>
    </div>

    <!-- Form Tambah/Edit Data -->
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
          {{ isEditing ? "Edit Data Staf" : "Tambah Data Staf Baru" }}
        </h3>
        <form @submit.prevent="isEditing ? saveEntry() : addEntry()">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kolom Kiri: Foto -->
            <div class="lg:col-span-1">
              <ImageUploader
                v-model="form.image"
                label="Foto Profil"
                :isCircular="true"
                containerClass="w-full max-w-[240px] mx-auto lg:mx-0"
                imageClass="object-cover object-top"
              />
              <p
                class="text-xs text-gray-500 dark:text-gray-400 mt-3 text-center lg:text-left max-w-[240px] mx-auto lg:mx-0"
              >
                Rasio foto terbaik adalah 1:1 (persegi).
              </p>
            </div>

            <!-- Kolom Kanan: Form Fields -->
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="md:col-span-2">
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Nama Lengkap & Gelar</label
                >
                <input
                  type="text"
                  v-model="form.name"
                  required
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Contoh: Budi Santoso, S.Pd"
                />
              </div>

              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Nomor Induk Pegawai (NIP)</label
                >
                <input
                  type="text"
                  v-model="form.nip"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Kosongkan jika tidak ada"
                />
              </div>

              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
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

              <div class="md:col-span-2">
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Jabatan / Posisi</label
                >
                <input
                  type="text"
                  v-model="form.role"
                  required
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Contoh: Guru Matematika Kelas X"
                  list="role-suggestions"
                />
                <datalist id="role-suggestions">
                  <option v-for="role in commonRoles" :key="role" :value="role"></option>
                </datalist>
              </div>

              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Email (Opsional)</label
                >
                <input
                  type="email"
                  v-model="form.email"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Contoh: guru@sekolah.sch.id"
                />
              </div>

              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Profil LinkedIn (Opsional)</label
                >
                <input
                  type="url"
                  v-model="form.linkedin"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Contoh: https://linkedin.com/in/username"
                />
              </div>
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
              <PhXCircle class="w-5 h-5 mr-2" />
              Batal
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

    <!-- Search & List Section -->
    <div
      class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm"
    >
      <!-- Kolom Pencarian -->
      <div class="mb-6 relative max-w-md">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
        </div>
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Cari berdasarkan nama, nip, atau jabatan..."
          class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors"
        />
      </div>

      <!-- Grid Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="staff in filteredStaff"
          :key="staff.id"
          class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm hover:shadow-md transition-shadow p-5 flex flex-col items-center text-center relative group"
        >
          <!-- Dropdown Aksi (Muncul saat hover) -->
          <div
            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 backdrop-blur p-1 rounded-lg border border-gray-100 dark:border-slate-700"
          >
            <button
              @click="startEdit(staff)"
              class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md transition-colors"
              title="Edit"
            >
              <PhPencilSimple class="w-4 h-4" />
            </button>
            <button
              @click="deleteEntry(staff.id)"
              class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md transition-colors"
              title="Hapus"
            >
              <PhTrash class="w-4 h-4" />
            </button>
          </div>

          <!-- Foto -->
          <div
            class="w-20 h-20 mb-4 shrink-0 rounded-full border-2 border-gray-100 dark:border-slate-600 overflow-hidden bg-gray-50 dark:bg-slate-700 flex items-center justify-center"
          >
            <img
              v-if="staff.image"
              :src="staff.image"
              :alt="staff.name"
              class="w-full h-full object-cover"
            />
            <PhUser v-else class="w-8 h-8 text-gray-400" />
          </div>

          <!-- Info -->
          <h4
            class="font-bold text-gray-900 dark:text-white line-clamp-1 mb-1"
            :title="staff.name"
          >
            {{ staff.name }}
          </h4>
          <p class="text-sm font-medium text-blue-600 dark:text-blue-400 mb-2">
            {{ staff.role }}
          </p>

          <div
            class="mt-auto pt-4 border-t border-gray-100 dark:border-slate-700 w-full flex flex-col gap-1.5"
          >
            <!-- Tautan Kontak -->
            <div
              v-if="staff.email || staff.linkedin"
              class="flex justify-center gap-2 mb-1"
            >
              <a
                v-if="staff.email"
                :href="'mailto:' + staff.email"
                class="p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors"
                title="Kirim Email"
              >
                <PhEnvelopeSimple class="w-5 h-5" />
              </a>
              <a
                v-if="staff.linkedin"
                :href="staff.linkedin"
                target="_blank"
                rel="noopener noreferrer"
                class="p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors"
                title="Profil LinkedIn"
              >
                <PhLinkedinLogo class="w-5 h-5" />
              </a>
            </div>

            <div
              class="text-xs text-gray-500 dark:text-gray-400 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 px-2.5 py-1.5 rounded"
            >
              <span>NIP:</span>
              <span class="font-semibold text-gray-700 dark:text-gray-300">{{
                staff.nip || "-"
              }}</span>
            </div>
            <div
              class="text-xs text-gray-500 dark:text-gray-400 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 px-2.5 py-1.5 rounded"
            >
              <span>Kategori:</span>
              <span class="font-semibold text-gray-700 dark:text-gray-300">{{
                getCategoryName(staff.category)
              }}</span>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div
          v-if="filteredStaff.length === 0"
          class="col-span-full py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
        >
          <p v-if="searchQuery">Tidak ada staf yang cocok dengan "{{ searchQuery }}".</p>
          <p v-else>Belum ada data guru/staf yang ditambahkan.</p>
        </div>
      </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Data Staf"
      message="Apakah Anda yakin ingin menghapus data staf ini? Tindakan ini tidak dapat dikembalikan."
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

<style scoped>
/* Transisi form */
.transition-all {
  overflow: hidden;
}
</style>
