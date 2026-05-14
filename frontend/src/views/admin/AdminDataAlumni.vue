<script setup>
import { ref, computed } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhGraduationCap,
  PhX,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const statusAlumniList = ["Kuliah", "Bekerja", "Wirausaha", "Lainnya"];

// Simulasi Data Master Siswa (Hanya yang berstatus Alumni di Data Siswa)
const masterSiswaAlumni = ref([
  { nisn: "0059876543", name: "Budi Santoso", year: "2023" },
  { nisn: "0061234567", name: "Siti Rahma", year: "2022" },
  { nisn: "0071112223", name: "Ahmad Dahlan", year: "2023" },
  { nisn: "0089998887", name: "Putri Larasati", year: "2023" },
]);

// Dummy data simulasi alumni
const alumniList = ref([
  {
    id: 1,
    nisn: "0059876543",
    name: "Budi Santoso",
    year: "2023",
    status: "Kuliah",
    instansi: "Universitas Gadjah Mada",
  },
  {
    id: 2,
    nisn: "0061234567",
    name: "Siti Rahma",
    year: "2022",
    status: "Bekerja",
    instansi: "PT Pertamina",
  },
]);

const form = ref({
  id: null,
  nisn: "",
  name: "",
  year: new Date().getFullYear().toString(),
  status: "Kuliah",
  instansi: "",
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");
const filterStatus = ref("semua");
const searchStudent = ref("");
const isDropdownOpen = ref(false);

// Memfilter agar siswa yang sudah dilacak tidak muncul lagi di dropdown
const unassignedAlumni = computed(() => {
  const trackedNisn = alumniList.value.map((a) => a.nisn);
  return masterSiswaAlumni.value.filter((s) => !trackedNisn.includes(s.nisn));
});

const filteredUnassignedAlumni = computed(() => {
  if (!searchStudent.value) return unassignedAlumni.value;
  const q = searchStudent.value.toLowerCase();
  return unassignedAlumni.value.filter(
    (s) => s.name.toLowerCase().includes(q) || s.nisn.toLowerCase().includes(q)
  );
});

const selectStudent = (siswa) => {
  form.value.nisn = siswa.nisn;
  form.value.name = siswa.name;
  form.value.year = siswa.year;
  searchStudent.value = `${siswa.nisn} - ${siswa.name}`;
  isDropdownOpen.value = false;
};

const closeDropdown = () => {
  setTimeout(() => {
    isDropdownOpen.value = false;
  }, 200);
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
    nisn: "",
    name: "",
    year: new Date().getFullYear().toString(),
    status: "Kuliah",
    instansi: "",
  };
  searchStudent.value = "";
  isDropdownOpen.value = false;
  isEditing.value = false;
};

const hideForm = () => {
  resetForm();
  isFormVisible.value = false;
  document.body.style.overflow = "";
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const addEntry = () => {
  if (!form.value.name || !form.value.nisn) {
    triggerToast("Gagal Menyimpan", "Nama dan NISN wajib diisi!", "error");
    return;
  }

  const newId =
    alumniList.value.length > 0 ? Math.max(...alumniList.value.map((s) => s.id)) + 1 : 1;

  alumniList.value.unshift({
    id: newId,
    nisn: form.value.nisn,
    name: form.value.name,
    year: form.value.year,
    status: form.value.status,
    instansi: form.value.instansi,
  });

  hideForm();
  triggerToast("Berhasil Ditambahkan", "Data alumni baru berhasil ditambahkan.");
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = { ...item };
  searchStudent.value = `${item.nisn} - ${item.name}`;
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = () => {
  if (!form.value.name || !form.value.nisn) {
    triggerToast("Gagal Menyimpan", "Nama dan NISN wajib diisi!", "error");
    return;
  }

  const index = alumniList.value.findIndex((s) => s.id === form.value.id);
  if (index !== -1) {
    alumniList.value[index] = { ...form.value };
  }

  hideForm();
  triggerToast("Perubahan Disimpan", "Data alumni berhasil diperbarui.");
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
  if (itemToDelete.value !== null) {
    alumniList.value = alumniList.value.filter((s) => s.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast("Data Dihapus", "Data alumni berhasil dihapus dari sistem.", "info");
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredAlumni = computed(() => {
  let result = alumniList.value;

  if (filterStatus.value !== "semua") {
    result = result.filter((item) => item.status === filterStatus.value);
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(
      (item) =>
        item.name.toLowerCase().includes(query) || item.nisn.toLowerCase().includes(query)
    );
  }
  return result;
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
          Data Tracking Alumni
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data pelacakan alumni, status karir, maupun kelanjutan studi (Tracer
          Study).
        </p>
      </div>
      <button
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Data Alumni
      </button>
    </div>

    <!-- Form Tambah/Edit Manual -->
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
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
              {{ isEditing ? "Edit Data Alumni" : "Tambah Alumni Baru" }}
            </h3>
            <button
              @click="hideForm"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6 overflow-y-auto custom-scrollbar max-h-[70vh]">
            <form id="alumniForm" @submit.prevent="isEditing ? saveEntry() : addEntry()">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-if="!isEditing" class="md:col-span-2 relative">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Cari Siswa (Dari Data Siswa Berstatus Alumni)
                  </label>
                  <input
                    type="text"
                    v-model="searchStudent"
                    @focus="isDropdownOpen = true"
                    @blur="closeDropdown"
                    placeholder="Ketik NISN atau Nama Siswa..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  />
                  <div
                    v-if="isDropdownOpen"
                    class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg max-h-48 overflow-y-auto"
                  >
                    <div
                      v-if="filteredUnassignedAlumni.length === 0"
                      class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400"
                    >
                      Siswa tidak ditemukan
                    </div>
                    <div
                      v-for="siswa in filteredUnassignedAlumni"
                      :key="siswa.nisn"
                      @click="selectStudent(siswa)"
                      class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-50 dark:hover:bg-slate-700 text-gray-800 dark:text-gray-200"
                    >
                      {{ siswa.nisn }} - {{ siswa.name }}
                    </div>
                  </div>
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >NISN</label
                  >
                  <input
                    type="text"
                    v-model="form.nisn"
                    disabled
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                    placeholder="005XXXXXXX"
                  />
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Nama Lengkap</label
                  >
                  <input
                    type="text"
                    v-model="form.name"
                    disabled
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                    placeholder="Nama alumni"
                  />
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Tahun Lulus</label
                  >
                  <input
                    type="text"
                    v-model="form.year"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="2023"
                  />
                </div>
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Status</label
                  >
                  <select
                    v-model="form.status"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option
                      v-for="status in statusAlumniList"
                      :key="status"
                      :value="status"
                    >
                      {{ status }}
                    </option>
                  </select>
                </div>
                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Instansi / Tempat (Kampus / Perusahaan)</label
                  >
                  <input
                    type="text"
                    v-model="form.instansi"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: Universitas Gadjah Mada"
                  />
                </div>
              </div>
            </form>
          </div>
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              type="button"
              @click="hideForm"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhXCircle class="w-5 h-5 mr-2" /> Batal
            </button>
            <button
              form="alumniForm"
              type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
              <PhPlusCircle v-else class="w-5 h-5 mr-2" />
              {{ isEditing ? "Simpan Perubahan" : "Simpan Data" }}
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Data Table & Filters -->
    <div
      class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden"
    >
      <div
        class="p-6 border-b border-gray-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
      >
        <div class="flex flex-col md:flex-row gap-4 w-full">
          <div class="relative flex-1 md:max-w-xs">
            <div
              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
            >
              <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
            </div>
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Cari NISN atau Nama..."
              class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>
          <select
            v-model="filterStatus"
            class="block w-full md:w-40 px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Status</option>
            <option v-for="status in statusAlumniList" :key="status" :value="status">
              {{ status }}
            </option>
          </select>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr
              class="bg-gray-50 dark:bg-slate-700/50 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              <th class="px-6 py-4">NISN</th>
              <th class="px-6 py-4">Nama</th>
              <th class="px-6 py-4">Tahun Lulus</th>
              <th class="px-6 py-4">Status Karir</th>
              <th class="px-6 py-4">Instansi / Kampus</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            <tr v-if="filteredAlumni.length === 0">
              <td
                colspan="6"
                class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
              >
                <PhGraduationCap
                  class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-500 mb-3"
                />
                <p>Tidak ada data alumni yang ditemukan.</p>
              </td>
            </tr>
            <tr
              v-for="alumni in filteredAlumni"
              :key="alumni.id"
              class="hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors group"
            >
              <td class="px-6 py-4">
                <span class="block text-sm text-gray-600 dark:text-gray-400 font-mono">{{
                  alumni.nisn
                }}</span>
              </td>
              <td class="px-6 py-4">
                <span
                  class="block text-sm font-semibold text-gray-800 dark:text-gray-200"
                  >{{ alumni.name }}</span
                >
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                {{ alumni.year }}
              </td>
              <td class="px-6 py-4">
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                  :class="{
                    'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400':
                      alumni.status === 'Kuliah',
                    'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400':
                      alumni.status === 'Bekerja',
                    'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-400':
                      alumni.status === 'Wirausaha',
                    'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300':
                      alumni.status === 'Lainnya',
                  }"
                >
                  {{ alumni.status }}
                </span>
              </td>
              <td
                class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 truncate max-w-[200px]"
                :title="alumni.instansi"
              >
                {{ alumni.instansi || "-" }}
              </td>
              <td class="px-6 py-4 text-right">
                <div
                  class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <button
                    @click="startEdit(alumni)"
                    class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors"
                    title="Edit Data"
                  >
                    <PhPencilSimple class="w-4 h-4" />
                  </button>
                  <button
                    @click="deleteEntry(alumni.id)"
                    class="p-1.5 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-md transition-colors"
                    title="Hapus Data"
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
      title="Hapus Data Alumni"
      message="Yakin ingin menghapus data tracking alumni ini secara permanen dari sistem?"
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
