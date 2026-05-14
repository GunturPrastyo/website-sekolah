<script setup>
import { ref, computed } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhCalendarBlank,
  PhClock,
  PhMapPin,
  PhX,
  PhCaretLeft,
  PhCaretRight,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const agendaList = ref([
  {
    id: 1,
    title: "Ujian Tengah Semester (UTS)",
    startDate: "2026-04-02",
    endDate: "2026-04-08",
    time: "07:30 - Selesai",
    location: "Ruang Kelas",
    color: "yellow",
    description: "Pelaksanaan Ujian Tengah Semester Genap Tahun Ajaran 2025/2026.",
  },
  {
    id: 2,
    title: "Rapat Evaluasi Guru & Staf",
    startDate: "2026-04-09",
    endDate: "",
    time: "13:00 - 15:00",
    location: "Ruang Guru",
    color: "red",
    description: "Rapat evaluasi bulanan untuk seluruh tenaga pendidik dan kependidikan.",
  },
  {
    id: 3,
    title: "Peringatan Hari Bumi (Kerja Bakti)",
    startDate: "2026-04-15",
    endDate: "",
    time: "08:00 - 11:00",
    location: "Lingkungan Sekolah",
    color: "green",
    description:
      "Kerja bakti massal membersihkan lingkungan sekolah dalam rangka Hari Bumi.",
  },
  {
    id: 4,
    title: "Seminar Persiapan Kuliah Kelas XII",
    startDate: "2026-04-20",
    endDate: "",
    time: "09:00 - 12:00",
    location: "Aula Utama",
    color: "blue",
    description: "Seminar motivasi dan persiapan SNBT untuk siswa-siswi kelas XII.",
  },
]);

const form = ref({
  id: null,
  title: "",
  startDate: "",
  endDate: "",
  time: "",
  location: "",
  color: "blue",
  description: "",
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");
const filterColor = ref("semua");

const colors = [
  {
    id: "yellow",
    name: "Akademik",
    bg: "bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400",
  },
  {
    id: "red",
    name: "Guru & Staf",
    bg: "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400",
  },
  {
    id: "green",
    name: "Kegiatan",
    bg: "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400",
  },
  {
    id: "blue",
    name: "Seminar & Acara",
    bg: "bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400",
  },
];

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
    startDate: "",
    endDate: "",
    time: "",
    location: "",
    color: "blue",
    description: "",
  };
  isEditing.value = false;
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const hideForm = () => {
  resetForm();
  isFormVisible.value = false;
  document.body.style.overflow = "";
};

const addEntry = () => {
  if (!form.value.title || !form.value.startDate) {
    triggerToast("Gagal Menyimpan", "Judul dan Tanggal Mulai wajib diisi!", "error");
    return;
  }

  const newId =
    agendaList.value.length > 0 ? Math.max(...agendaList.value.map((a) => a.id)) + 1 : 1;

  agendaList.value.push({ ...form.value, id: newId });
  agendaList.value.sort((a, b) => new Date(a.startDate) - new Date(b.startDate));

  hideForm();
  triggerToast("Berhasil Ditambahkan", "Data agenda baru berhasil ditambahkan.");
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = { ...item };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = () => {
  if (!form.value.title || !form.value.startDate) {
    triggerToast("Gagal Menyimpan", "Judul dan Tanggal Mulai wajib diisi!", "error");
    return;
  }

  const index = agendaList.value.findIndex((a) => a.id === form.value.id);
  if (index !== -1) {
    agendaList.value[index] = { ...form.value };
    agendaList.value.sort((a, b) => new Date(a.startDate) - new Date(b.startDate));
  }

  hideForm();
  triggerToast("Perubahan Disimpan", "Data agenda berhasil diperbarui.");
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
  if (itemToDelete.value !== null) {
    agendaList.value = agendaList.value.filter((a) => a.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast("Data Dihapus", "Data agenda berhasil dihapus dari sistem.", "info");
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredAgendas = computed(() => {
  let result = agendaList.value;

  if (filterColor.value !== "semua") {
    result = result.filter((item) => item.color === filterColor.value);
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(
      (item) =>
        item.title.toLowerCase().includes(query) ||
        item.location.toLowerCase().includes(query) ||
        item.description.toLowerCase().includes(query)
    );
  }
  return result;
});

const formatDate = (dateString) => {
  if (!dateString) return "";
  const options = { year: "numeric", month: "long", day: "numeric" };
  return new Date(dateString).toLocaleDateString("id-ID", options);
};

const getColorConfig = (colorId) => {
  return colors.find((c) => c.id === colorId) || colors[0];
};

// Logika Kalender Dinamis
const currentDate = ref(new Date(2026, 3, 1)); // Diatur default ke April 2026 sesuai data dummy

const currentMonthName = computed(() => {
  const options = { month: "long", year: "numeric" };
  return currentDate.value.toLocaleDateString("id-ID", options);
});

const prevMonth = () => {
  currentDate.value = new Date(
    currentDate.value.getFullYear(),
    currentDate.value.getMonth() - 1,
    1
  );
};

const nextMonth = () => {
  currentDate.value = new Date(
    currentDate.value.getFullYear(),
    currentDate.value.getMonth() + 1,
    1
  );
};

const calendarDays = computed(() => {
  const year = currentDate.value.getFullYear();
  const month = currentDate.value.getMonth();
  const firstDay = new Date(year, month, 1).getDay(); // 0 = Minggu
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  const today = new Date();
  const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(
    2,
    "0"
  )}-${String(today.getDate()).padStart(2, "0")}`;

  const days = [];
  for (let i = 0; i < firstDay; i++) {
    days.push({ empty: true });
  }

  for (let i = 1; i <= daysInMonth; i++) {
    const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(i).padStart(
      2,
      "0"
    )}`;

    // Cek apakah ada agenda yang jatuh pada tanggal ini
    const dayAgendas = agendaList.value.filter((a) => {
      if (a.endDate) {
        return dateStr >= a.startDate && dateStr <= a.endDate;
      }
      return dateStr === a.startDate;
    });

    days.push({
      empty: false,
      date: i,
      dateStr,
      isToday: dateStr === todayStr,
      events: dayAgendas,
    });
  }
  return days;
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
          Manajemen Agenda Sekolah
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola jadwal kegiatan akademik dan non-akademik sekolah.
        </p>
      </div>
      <button
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Agenda
      </button>
    </div>

    <!-- Form Tambah/Edit -->
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
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all"
          @click.stop
        >
          <!-- Modal Header -->
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
              {{ isEditing ? "Edit Agenda Sekolah" : "Tambah Agenda Baru" }}
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
            <form id="agendaForm" @submit.prevent="isEditing ? saveEntry() : addEntry()">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Judul Kegiatan
                  </label>
                  <input
                    type="text"
                    v-model="form.title"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Masukkan judul agenda..."
                  />
                </div>

                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Tanggal Mulai
                  </label>
                  <input
                    type="date"
                    v-model="form.startDate"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>

                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Tanggal Selesai (Opsional)
                  </label>
                  <input
                    type="date"
                    v-model="form.endDate"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>

                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Waktu / Jam
                  </label>
                  <input
                    type="text"
                    v-model="form.time"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: 08:00 - Selesai"
                  />
                </div>

                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Kategori Label (Warna)
                  </label>
                  <select
                    v-model="form.color"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option v-for="color in colors" :key="color.id" :value="color.id">
                      {{ color.name }}
                    </option>
                  </select>
                </div>

                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Lokasi
                  </label>
                  <input
                    type="text"
                    v-model="form.location"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: Aula Utama, Lapangan, dll."
                  />
                </div>

                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Deskripsi Singkat
                  </label>
                  <textarea
                    v-model="form.description"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 resize-none"
                    placeholder="Masukkan detail keterangan agenda..."
                  ></textarea>
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
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhXCircle class="w-5 h-5 mr-2" /> Batal
            </button>
            <button
              form="agendaForm"
              type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
              <PhPlusCircle v-else class="w-5 h-5 mr-2" />
              {{ isEditing ? "Simpan Perubahan" : "Simpan Agenda" }}
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Visualisasi Kalender Dinamis -->
    <div
      class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 mb-8 overflow-hidden"
    >
      <div
        class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4"
      >
        <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
          <PhCalendarBlank class="w-6 h-6 mr-2 text-blue-600" /> Visualisasi Kalender
        </h3>
        <div
          class="flex items-center gap-2 bg-gray-50 dark:bg-slate-700/50 p-1.5 rounded-lg border border-gray-200 dark:border-slate-600 w-full sm:w-auto justify-between sm:justify-center"
        >
          <button
            @click="prevMonth"
            class="p-1.5 hover:bg-white dark:hover:bg-slate-600 rounded-md transition-colors shadow-sm"
            title="Bulan Sebelumnya"
          >
            <PhCaretLeft class="w-5 h-5 text-gray-600 dark:text-gray-300" />
          </button>
          <span
            class="font-bold text-sm md:text-base text-gray-800 dark:text-white w-32 text-center"
            >{{ currentMonthName }}</span
          >
          <button
            @click="nextMonth"
            class="p-1.5 hover:bg-white dark:hover:bg-slate-600 rounded-md transition-colors shadow-sm"
            title="Bulan Selanjutnya"
          >
            <PhCaretRight class="w-5 h-5 text-gray-600 dark:text-gray-300" />
          </button>
        </div>
      </div>

      <div class="grid grid-cols-7 gap-1 md:gap-2 text-center mb-2">
        <div class="text-xs md:text-sm font-bold text-red-500 py-1 md:py-2">Min</div>
        <div
          class="text-xs md:text-sm font-bold text-gray-500 dark:text-gray-400 py-1 md:py-2"
        >
          Sen
        </div>
        <div
          class="text-xs md:text-sm font-bold text-gray-500 dark:text-gray-400 py-1 md:py-2"
        >
          Sel
        </div>
        <div
          class="text-xs md:text-sm font-bold text-gray-500 dark:text-gray-400 py-1 md:py-2"
        >
          Rab
        </div>
        <div
          class="text-xs md:text-sm font-bold text-gray-500 dark:text-gray-400 py-1 md:py-2"
        >
          Kam
        </div>
        <div
          class="text-xs md:text-sm font-bold text-gray-500 dark:text-gray-400 py-1 md:py-2"
        >
          Jum
        </div>
        <div
          class="text-xs md:text-sm font-bold text-gray-500 dark:text-gray-400 py-1 md:py-2"
        >
          Sab
        </div>
      </div>

      <div class="grid grid-cols-7 gap-1 md:gap-2">
        <div
          v-for="(day, index) in calendarDays"
          :key="index"
          class="min-h-[70px] md:min-h-[100px] p-1 md:p-2 rounded-lg border transition-colors flex flex-col"
          :class="
            day.empty
              ? 'border-transparent bg-transparent'
              : 'border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/30 hover:bg-blue-50 dark:hover:bg-slate-700/50'
          "
        >
          <div v-if="!day.empty" class="h-full flex flex-col">
            <div class="flex justify-end mb-1 md:mb-2">
              <span
                class="text-xs md:text-sm font-semibold w-6 h-6 flex items-center justify-center rounded-full"
                :class="
                  day.isToday
                    ? 'bg-blue-600 text-white shadow-md'
                    : index % 7 === 0
                    ? 'text-red-500'
                    : 'text-gray-700 dark:text-gray-300'
                "
              >
                {{ day.date }}
              </span>
            </div>
            <div class="flex flex-col gap-1 overflow-y-auto flex-1">
              <div
                v-for="event in day.events"
                :key="event.id"
                class="text-[9px] md:text-[11px] px-1 md:px-1.5 py-0.5 md:py-1 rounded font-medium truncate cursor-help border border-transparent shadow-sm"
                :class="getColorConfig(event.color).bg"
                :title="event.title"
              >
                {{ event.title }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table & Filters -->
    <div
      class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden"
    >
      <div
        class="p-6 border-b border-gray-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
      >
        <div class="flex flex-col md:flex-row gap-4 w-full">
          <div class="relative flex-1 md:max-w-md">
            <div
              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
            >
              <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
            </div>
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Cari judul atau lokasi agenda..."
              class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>
          <select
            v-model="filterColor"
            class="block w-full md:w-56 px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Kategori</option>
            <option v-for="color in colors" :key="color.id" :value="color.id">
              {{ color.name }}
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
              <th class="px-6 py-4">Judul Agenda</th>
              <th class="px-6 py-4">Tanggal Pelaksanaan</th>
              <th class="px-6 py-4 hidden sm:table-cell">Waktu & Lokasi</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            <tr v-if="filteredAgendas.length === 0">
              <td
                colspan="4"
                class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
              >
                <PhCalendarBlank
                  class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-500 mb-3"
                />
                <p>Tidak ada agenda yang ditemukan.</p>
              </td>
            </tr>
            <tr
              v-for="agenda in filteredAgendas"
              :key="agenda.id"
              class="hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors group"
            >
              <td class="px-6 py-4">
                <span
                  class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1"
                  >{{ agenda.title }}</span
                >
                <span
                  class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"
                  :class="getColorConfig(agenda.color).bg"
                >
                  {{ getColorConfig(agenda.color).name }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span class="block text-sm text-gray-700 dark:text-gray-300">
                  {{ formatDate(agenda.startDate) }}
                  <span v-if="agenda.endDate" class="text-gray-400">
                    s.d. <br />{{ formatDate(agenda.endDate) }}</span
                  >
                </span>
              </td>
              <td class="px-6 py-4 hidden sm:table-cell">
                <div class="flex flex-col gap-1 text-sm text-gray-600 dark:text-gray-400">
                  <div class="flex items-center">
                    <PhClock class="w-4 h-4 mr-1.5 opacity-70" />
                    {{ agenda.time || "-" }}
                  </div>
                  <div class="flex items-center">
                    <PhMapPin class="w-4 h-4 mr-1.5 opacity-70" />
                    {{ agenda.location || "-" }}
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <div
                  class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <button
                    @click="startEdit(agenda)"
                    class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors"
                    title="Edit Data"
                  >
                    <PhPencilSimple class="w-4 h-4" />
                  </button>
                  <button
                    @click="deleteEntry(agenda.id)"
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
      title="Hapus Agenda"
      message="Yakin ingin menghapus agenda sekolah ini dari sistem?"
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
