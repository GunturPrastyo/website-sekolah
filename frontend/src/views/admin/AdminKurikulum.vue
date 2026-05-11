<script setup>
import { ref, computed } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhBookOpen,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const grades = [
  { id: "10", name: "Kelas X (Fase E)" },
  { id: "11", name: "Kelas XI (Fase F)" },
  { id: "12", name: "Kelas XII (Fase F)" },
];

const majors = [
  { id: "umum", name: "Umum (Fase E)" },
  { id: "ipa", name: "MIPA" },
  { id: "ips", name: "IPS" },
  { id: "bahasa", name: "Bahasa" },
];

const categories = [
  "Muatan Nasional (Wajib)",
  "Muatan Pilihan (Fase E)",
  "Kelompok Mata Pelajaran Pilihan (Sains & Teknologi)",
  "Kelompok Mata Pelajaran Pilihan (Sosiologi & Humaniora)",
  "Kelompok Mata Pelajaran Pilihan (Bahasa & Budaya)",
  "Kelompok Persiapan UTBK (Sains & Teknologi)",
  "Kelompok Persiapan UTBK (Soshum)",
  "Kelompok Persiapan Ujian Lanjutan (Sastra)",
];

const subjectList = ref([
  {
    id: 1,
    grade: "10",
    major: "umum",
    category: "Muatan Nasional (Wajib)",
    name: "Pendidikan Agama dan Budi Pekerti",
    desc:
      "Mempelajari nilai-nilai spiritual, toleransi, dan pembentukan karakter akhlak mulia.",
    topics:
      "Hakikat Penciptaan Manusia, Toleransi Beragama di Indonesia, Sejarah Perkembangan Agama, Etika dan Budi Pekerti Abad 21",
  },
  {
    id: 2,
    grade: "11",
    major: "ipa",
    category: "Kelompok Mata Pelajaran Pilihan (Sains & Teknologi)",
    name: "Fisika Lanjutan",
    desc: "Pendalaman mekanika, termodinamika, dan gelombang.",
    topics:
      "Dinamika Rotasi dan Kesetimbangan, Elastisitas Bahan, Fluida Statis dan Dinamis, Suhu dan Kalor",
  },
]);

const form = ref({
  id: null,
  grade: "10",
  major: "umum",
  category: "Muatan Nasional (Wajib)",
  name: "",
  desc: "",
  topics: "",
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
    grade: "10",
    major: "umum",
    category: "Muatan Nasional (Wajib)",
    name: "",
    desc: "",
    topics: "",
  };
  isEditing.value = false;
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
};

const addEntry = () => {
  if (!form.value.name || !form.value.category) {
    triggerToast("Gagal Menyimpan", "Nama dan Kategori wajib diisi!", "error");
    return;
  }
  const newId =
    subjectList.value.length > 0
      ? Math.max(...subjectList.value.map((s) => s.id)) + 1
      : 1;
  subjectList.value.push({ ...form.value, id: newId });

  isFormVisible.value = false;
  triggerToast(
    "Berhasil Ditambahkan",
    "Data mata pelajaran baru telah ditambahkan ke sistem."
  );
  resetForm();
};

const startEdit = (subject) => {
  isEditing.value = true;
  form.value = { ...subject };
  isFormVisible.value = true;
  window.scrollTo({ top: 0, behavior: "smooth" });
};

const saveEntry = () => {
  if (!form.value.name || !form.value.category) {
    triggerToast("Gagal Menyimpan", "Nama dan Kategori wajib diisi!", "error");
    return;
  }
  const index = subjectList.value.findIndex((s) => s.id === form.value.id);
  if (index !== -1) {
    subjectList.value[index] = { ...form.value };
  }

  isFormVisible.value = false;
  triggerToast("Perubahan Disimpan", "Data mata pelajaran berhasil diperbarui.");
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
    subjectList.value = subjectList.value.filter((s) => s.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast(
      "Data Dihapus",
      "Data mata pelajaran berhasil dihapus dari sistem.",
      "info"
    );
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredSubjects = computed(() => {
  if (!searchQuery.value) return subjectList.value;
  const query = searchQuery.value.toLowerCase();
  return subjectList.value.filter(
    (subject) =>
      subject.name.toLowerCase().includes(query) ||
      subject.category.toLowerCase().includes(query)
  );
});

const getGradeName = (id) => {
  const grade = grades.find((g) => g.id === id);
  return grade ? grade.name : id;
};

const getMajorName = (id) => {
  const major = majors.find((m) => m.id === id);
  return major ? major.name : id;
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
          Manajemen Kurikulum
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data silabus dan mata pelajaran per tingkat kelas dan peminatan.
        </p>
      </div>
      <button
        v-if="!isFormVisible"
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Mata Pelajaran
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
          {{ isEditing ? "Edit Data Mata Pelajaran" : "Tambah Data Mata Pelajaran Baru" }}
        </h3>
        <form @submit.prevent="isEditing ? saveEntry() : addEntry()">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Tingkat Kelas
              </label>
              <select
                v-model="form.grade"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              >
                <option v-for="grade in grades" :key="grade.id" :value="grade.id">
                  {{ grade.name }}
                </option>
              </select>
            </div>

            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Peminatan / Jurusan
              </label>
              <select
                v-model="form.major"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              >
                <option v-for="major in majors" :key="major.id" :value="major.id">
                  {{ major.name }}
                </option>
              </select>
            </div>

            <div class="md:col-span-2">
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Kategori Kurikulum
              </label>
              <input
                type="text"
                v-model="form.category"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                placeholder="Contoh: Muatan Nasional (Wajib)"
                list="category-suggestions"
              />
              <datalist id="category-suggestions">
                <option v-for="cat in categories" :key="cat" :value="cat"></option>
              </datalist>
            </div>

            <div class="md:col-span-2">
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Nama Mata Pelajaran
              </label>
              <input
                type="text"
                v-model="form.name"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                placeholder="Contoh: Matematika Peminatan"
              />
            </div>

            <div class="md:col-span-2">
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Deskripsi Singkat
              </label>
              <textarea
                v-model="form.desc"
                rows="2"
                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                placeholder="Deskripsi tentang mata pelajaran"
              ></textarea>
            </div>

            <div class="md:col-span-2">
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Topik Pembelajaran
                <span class="text-xs text-gray-500">(Pisahkan dengan koma)</span>
              </label>
              <textarea
                v-model="form.topics"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                placeholder="Contoh: Eksponen dan Logaritma, Barisan dan Deret, Sistem Persamaan Linear"
              ></textarea>
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
          placeholder="Cari mata pelajaran atau kategori..."
          class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors"
        />
      </div>

      <!-- Empty State -->
      <div
        v-if="filteredSubjects.length === 0"
        class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
      >
        <p v-if="searchQuery">
          Tidak ada mata pelajaran yang cocok dengan "{{ searchQuery }}".
        </p>
        <p v-else>Belum ada data mata pelajaran yang ditambahkan.</p>
      </div>

      <!-- List Cards -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        <div
          v-for="subject in filteredSubjects"
          :key="subject.id"
          class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm hover:shadow-md transition-shadow p-5 flex flex-col relative group"
        >
          <!-- Dropdown Aksi -->
          <div
            class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 backdrop-blur p-1 rounded-lg border border-gray-100 dark:border-slate-700 z-10"
          >
            <button
              @click="startEdit(subject)"
              class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md transition-colors"
              title="Edit"
            >
              <PhPencilSimple class="w-4 h-4" />
            </button>
            <button
              @click="deleteEntry(subject.id)"
              class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md transition-colors"
              title="Hapus"
            >
              <PhTrash class="w-4 h-4" />
            </button>
          </div>

          <!-- Info -->
          <div class="flex items-center gap-3 mb-3">
            <div
              class="w-10 h-10 shrink-0 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center"
            >
              <PhBookOpen class="w-5 h-5" />
            </div>
            <div>
              <h4
                class="font-bold text-gray-900 dark:text-white line-clamp-1"
                :title="subject.name"
              >
                {{ subject.name }}
              </h4>
              <p class="text-xs font-medium text-blue-600 dark:text-blue-400">
                {{ getGradeName(subject.grade) }} &bull; {{ getMajorName(subject.major) }}
              </p>
            </div>
          </div>

          <p
            class="text-xs text-gray-500 dark:text-gray-400 mb-3 bg-gray-50 dark:bg-slate-700/50 p-2 rounded"
          >
            <strong>Kategori:</strong> {{ subject.category }}
          </p>

          <p
            class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 mb-3 flex-1"
            :title="subject.desc"
          >
            {{ subject.desc }}
          </p>

          <div class="mt-auto border-t border-gray-100 dark:border-slate-700 pt-3">
            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
              Topik Pembelajaran:
            </p>
            <div class="flex flex-wrap gap-1">
              <span
                v-for="(topic, idx) in subject.topics.split(',').slice(0, 3)"
                :key="idx"
                class="inline-block px-2 py-1 text-[10px] bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 rounded-sm truncate max-w-[120px]"
                :title="topic.trim()"
              >
                {{ topic.trim() }}
              </span>
              <span
                v-if="subject.topics.split(',').length > 3"
                class="inline-block px-2 py-1 text-[10px] bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 rounded-sm"
              >
                +{{ subject.topics.split(",").length - 3 }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Data Mata Pelajaran"
      message="Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dikembalikan."
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
