<script setup>
import { ref, computed, onMounted } from "vue";
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
  PhUploadSimple,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";
import api from "@/api/index.js";

const agendaList = ref([]);

const form = ref({
  id: null,
  title: "",
  startDate: "",
  endDate: "",
  time: "",
  location: "",
  color: "blue",
  attachment: "",
});
const fileInputRef = ref(null);

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
    calBg:
      "bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/40",
    calHeader:
      "bg-yellow-200/70 dark:bg-yellow-700/40 text-yellow-800 dark:text-yellow-200",
    calText: "text-yellow-700 dark:text-yellow-300",
    legendBg: "bg-yellow-400",
  },
  {
    id: "red",
    name: "Guru & Staf",
    bg: "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400",
    calBg: "bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40",
    calHeader: "bg-red-200/70 dark:bg-red-700/40 text-red-800 dark:text-red-200",
    calText: "text-red-700 dark:text-red-300",
    legendBg: "bg-red-500",
  },
  {
    id: "green",
    name: "Kegiatan",
    bg: "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400",
    calBg:
      "bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40",
    calHeader: "bg-green-200/70 dark:bg-green-700/40 text-green-800 dark:text-green-200",
    calText: "text-green-700 dark:text-green-300",
    legendBg: "bg-green-500",
  },
  {
    id: "blue",
    name: "Seminar & Acara",
    bg: "bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400",
    calBg: "bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40",
    calHeader: "bg-blue-200/70 dark:bg-blue-700/40 text-blue-800 dark:text-blue-200",
    calText: "text-blue-700 dark:text-blue-300",
    legendBg: "bg-blue-500",
  },
];

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

const triggerFileInput = () => {
  if (fileInputRef.value) fileInputRef.value.click();
};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      form.value.attachment = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    form.value.attachment = "";
  }
};

const removeAttachment = () => {
  form.value.attachment = "";
  if (fileInputRef.value) {
    fileInputRef.value.value = "";
  }
};

const fetchData = async () => {
  try {
    const response = await api.get("/api/agendas");
    agendaList.value = response.data.data;
  } catch (error) {
    console.error("Gagal mengambil data agenda:", error);
  }
};

onMounted(() => {
  fetchData();
});

const resetForm = () => {
  form.value = {
    id: null,
    title: "",
    startDate: "",
    endDate: "",
    time: "",
    location: "",
    color: "blue",
    attachment: "",
  };
  isEditing.value = false;
  if (fileInputRef.value) {
    fileInputRef.value.value = "";
  }
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

const addEntry = async () => {
  if (!form.value.title || !form.value.startDate) {
    triggerToast("Gagal Menyimpan", "Judul dan Tanggal Mulai wajib diisi!", "error");
    return;
  }

  try {
    await api.post("/api/agendas", form.value);
    await fetchData();
    hideForm();
    triggerToast("Berhasil Ditambahkan", "Data agenda baru berhasil ditambahkan.");
  } catch (error) {
    console.error("Gagal menambahkan agenda:", error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat menyimpan data.", "error");
  }
};

const startEdit = (item) => {
  isEditing.value = true;

  const formattedItem = { ...item };
  if (formattedItem.startDate) {
    formattedItem.startDate = formattedItem.startDate.substring(0, 10);
  }
  if (formattedItem.endDate) {
    formattedItem.endDate = formattedItem.endDate.substring(0, 10);
  }
  form.value = formattedItem;
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = async () => {
  if (!form.value.title || !form.value.startDate) {
    triggerToast("Gagal Menyimpan", "Judul dan Tanggal Mulai wajib diisi!", "error");
    return;
  }

  try {
    await api.put(`/api/agendas/${form.value.id}`, form.value);

    await fetchData();

    hideForm();
    triggerToast("Perubahan Disimpan", "Data agenda berhasil diperbarui.");
  } catch (error) {
    console.error("Gagal memperbarui agenda:", error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat memperbarui data.", "error");
  }
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = async () => {
  if (itemToDelete.value !== null) {
    try {
      await api.delete(`/api/agendas/${itemToDelete.value}`);

      await fetchData();

      itemToDelete.value = null;
      triggerToast("Data Dihapus", "Data agenda berhasil dihapus dari sistem.", "info");
    } catch (error) {
      console.error("Gagal menghapus agenda:", error);
      triggerToast("Gagal", "Terjadi kesalahan saat menghapus data", "error");
    }
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
        (item.title && item.title.toLowerCase().includes(query)) ||
        (item.location && item.location.toLowerCase().includes(query))
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
const currentDate = ref(new Date());

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

const getColSpanClass = (span) => {
  const map = {
    1: "col-span-1",
    2: "col-span-2",
    3: "col-span-3",
    4: "col-span-4",
    5: "col-span-5",
    6: "col-span-6",
    7: "col-span-7",
  };
  return map[span] || "col-span-1";
};

const calendarBlocks = computed(() => {
  const year = currentDate.value.getFullYear();
  const month = currentDate.value.getMonth();
  const firstDay = new Date(year, month, 1).getDay(); // 0 = Minggu
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  const today = new Date();
  const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(
    2,
    "0"
  )}-${String(today.getDate()).padStart(2, "0")}`;

  const blocks = [];
  for (let i = 0; i < firstDay; i++) {
    blocks.push({ type: "empty", span: 1 });
  }

  let currentDay = 1;
  let currentWeekday = firstDay;

  while (currentDay <= daysInMonth) {
    const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(
      currentDay
    ).padStart(2, "0")}`;

    // Cek apakah ada agenda yang jatuh pada tanggal ini
    const dayAgendas = agendaList.value.filter((a) => {
      if (a.endDate) {
        return dateStr >= a.startDate && dateStr <= a.endDate;
      }
      return dateStr === a.startDate;
    });

    if (dayAgendas.length > 0) {
      // Pilih event pertama untuk ditampilkan sebagai span (mirip Gantt chart HomeView)
      const event = dayAgendas[0];

      let span = 1;
      let d = currentDay + 1;
      let w = currentWeekday + 1;

      while (w < 7 && d <= daysInMonth) {
        const nextDateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(
          d
        ).padStart(2, "0")}`;
        const isSameEvent =
          (event.endDate &&
            nextDateStr >= event.startDate &&
            nextDateStr <= event.endDate) ||
          (!event.endDate && nextDateStr === event.startDate);

        if (isSameEvent) {
          span++;
          d++;
          w++;
        } else {
          break;
        }
      }

      const dates = [];
      for (let i = 0; i < span; i++) {
        dates.push({
          date: currentDay + i,
          isSunday: (currentWeekday + i) % 7 === 0,
          isToday:
            `${year}-${String(month + 1).padStart(2, "0")}-${String(
              currentDay + i
            ).padStart(2, "0")}` === todayStr,
        });
      }

      blocks.push({
        type: "event",
        span,
        event,
        dates,
      });

      currentDay += span;
      currentWeekday = (currentWeekday + span) % 7;
    } else {
      blocks.push({
        type: "day",
        span: 1,
        date: currentDay,
        isSunday: currentWeekday === 0,
        isToday: dateStr === todayStr,
      });
      currentDay++;
      currentWeekday = (currentWeekday + 1) % 7;
    }
  }
  return blocks;
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
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400/60 dark:placeholder-slate-500/60"
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
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400/60 dark:placeholder-slate-500/60"
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
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400/60 dark:placeholder-slate-500/60"
                    placeholder="Contoh: Aula Utama, Lapangan, dll."
                  />
                </div>

                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Lampiran (Gambar / PDF)
                  </label>
                  <input
                    type="file"
                    ref="fileInputRef"
                    accept="image/*,application/pdf"
                    @change="handleFileUpload"
                    class="hidden"
                  />
                  <button
                    v-if="!form.attachment"
                    type="button"
                    @click="triggerFileInput"
                    class="w-full py-6 border-2 border-dashed border-gray-300 dark:border-slate-500 rounded-lg flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors"
                  >
                    <PhUploadSimple class="w-8 h-8 mb-2 text-gray-400" />
                    <span class="text-sm font-medium"
                      >Klik untuk mengunggah lampiran</span
                    >
                    <span class="text-xs text-gray-400 dark:text-gray-500 mt-1"
                      >Mendukung format JPG, PNG, atau PDF</span
                    >
                  </button>
                  <div
                    v-if="
                      form.attachment &&
                      (form.attachment.startsWith('data:image') ||
                        form.attachment.match(/\.(jpeg|jpg|png|gif|webp)$/i))
                    "
                    class="mt-3 relative inline-block"
                  >
                    <img
                      :src="form.attachment"
                      class="h-24 w-auto rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm object-cover"
                      alt="Preview Lampiran"
                    />
                    <button
                      type="button"
                      @click="removeAttachment"
                      class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow hover:bg-red-600 transition-colors"
                      title="Hapus"
                    >
                      <PhX class="w-3 h-3" />
                    </button>
                  </div>
                  <div
                    v-else-if="form.attachment"
                    class="mt-3 flex items-center justify-between p-3 bg-blue-50 dark:bg-slate-700/50 rounded-lg border border-blue-100 dark:border-slate-600"
                  >
                    <span
                      class="text-sm font-medium text-blue-600 dark:text-blue-400 flex items-center"
                    >
                      📄 File telah dilampirkan
                    </span>
                    <button
                      type="button"
                      @click="removeAttachment"
                      class="text-red-500 hover:text-red-600 transition-colors p-1"
                      title="Hapus"
                    >
                      <PhX class="w-4 h-4" />
                    </button>
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
          <PhCalendarBlank class="w-6 h-6 mr-2 text-gray-800" /> Kalender
        </h3>
        <div
          class="flex items-center gap-1 bg-slate-50 dark:bg-slate-800/80 p-1.5 rounded-full border border-slate-100 dark:border-slate-700 w-full sm:w-auto justify-between sm:justify-center shadow-sm"
        >
          <button
            @click="prevMonth"
            class="p-2 hover:bg-white dark:hover:bg-slate-700 rounded-full transition-all hover:shadow-sm text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400"
            title="Bulan Sebelumnya"
          >
            <PhCaretLeft class="w-4 h-4 md:w-5 md:h-5" weight="bold" />
          </button>
          <span
            class="font-semibold text-sm md:text-base text-slate-700 dark:text-slate-200 w-32 text-center tracking-wide"
            >{{ currentMonthName }}</span
          >
          <button
            @click="nextMonth"
            class="p-2 hover:bg-white dark:hover:bg-slate-700 rounded-full transition-all hover:shadow-sm text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400"
            title="Bulan Selanjutnya"
          >
            <PhCaretRight class="w-4 h-4 md:w-5 md:h-5" weight="bold" />
          </button>
        </div>
      </div>

      <div class="grid grid-cols-7 gap-1 md:gap-2 text-center mb-2">
        <div class="text-xs font-semibold text-red-400/80 dark:text-red-400/70 py-2">
          Min
        </div>
        <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 py-2">
          Sen
        </div>
        <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 py-2">
          Sel
        </div>
        <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 py-2">
          Rab
        </div>
        <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 py-2">
          Kam
        </div>
        <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 py-2">
          Jum
        </div>
        <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 py-2">
          Sab
        </div>
      </div>

      <div class="grid grid-cols-7 gap-y-2 gap-x-1 md:gap-x-2 text-sm text-center">
        <template v-for="(block, index) in calendarBlocks" :key="index">
          <div v-if="block.type === 'empty'" class="py-2"></div>

          <div
            v-else-if="block.type === 'day'"
            class="py-1.5 mx-0.5 rounded-xl cursor-pointer transition-all duration-200 flex items-center justify-center group"
            :class="
              block.isSunday
                ? 'hover:bg-red-50/50 dark:hover:bg-red-900/20'
                : 'hover:bg-slate-50 dark:hover:bg-slate-700/30'
            "
          >
            <span
              class="w-8 h-8 flex items-center justify-center rounded-full transition-all duration-300"
              :class="
                block.isToday
                  ? 'bg-blue-500 text-white shadow-sm font-semibold scale-110'
                  : block.isSunday
                  ? 'text-red-400 dark:text-red-400/80 font-medium group-hover:text-red-500'
                  : 'text-slate-600 dark:text-slate-400 font-medium group-hover:text-blue-600 dark:group-hover:text-blue-400'
              "
            >
              {{ block.date }}
            </span>
          </div>

          <div
            v-else-if="block.type === 'event'"
            class="mx-0.5 relative rounded-xl overflow-hidden cursor-pointer transition-all duration-300 hover:shadow-md flex flex-col justify-between group"
            :class="[
              getColSpanClass(block.span),
              getColorConfig(block.event.color).calBg,
            ]"
            :title="block.event.title"
            @click="startEdit(block.event)"
          >
            <div
              class="grid w-full py-1.5"
              :style="`grid-template-columns: repeat(${block.span}, minmax(0, 1fr))`"
            >
              <div
                v-for="d in block.dates"
                :key="d.date"
                class="flex items-center justify-center"
              >
                <span
                  class="w-7 h-7 flex items-center justify-center rounded-full font-medium transition-transform group-hover:scale-105"
                  :class="[
                    d.isToday
                      ? 'bg-blue-500 text-white shadow-sm font-semibold'
                      : d.isSunday
                      ? 'text-red-400 dark:text-red-400/80'
                      : getColorConfig(block.event.color).calText,
                  ]"
                >
                  {{ d.date }}
                </span>
              </div>
            </div>
            <div
              class="text-[10px] font-semibold px-2.5 py-1 text-center truncate transition-colors"
              :class="getColorConfig(block.event.color).calHeader"
            >
              {{ block.event.title }}
            </div>
          </div>
        </template>
      </div>

      <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-700">
        <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Keterangan Label</h4>
        <div class="flex flex-wrap gap-3">
          <div
            v-for="color in colors"
            :key="color.id"
            class="flex items-center text-xs text-gray-600 dark:text-gray-400"
          >
            <span class="w-3 h-3 rounded-sm mr-2" :class="color.legendBg"></span>
            {{ color.name }}
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
              class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
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
