<script setup>
import { ref, computed } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhCalendar,
  PhClock,
  PhChalkboardTeacher,
  PhBookOpen,
  PhX,
  PhList,
  PhCalendarBlank,
  PhWarningCircle,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
const classes = [
  "X MIPA 1",
  "X MIPA 2",
  "X IPS 1",
  "X IPS 2",
  "XI MIPA 1",
  "XI MIPA 2",
  "XI IPS 1",
  "XI IPS 2",
  "XII MIPA 1",
  "XII MIPA 2",
  "XII IPS 1",
  "XII IPS 2",
];

const scheduleList = ref([
  {
    id: 1,
    className: "X MIPA 1",
    day: "Senin",
    startTime: "07:00",
    endTime: "08:30",
    subject: "Matematika Peminatan",
    teacher: "Budi Santoso, S.Pd",
  },
  {
    id: 2,
    className: "X MIPA 1",
    day: "Senin",
    startTime: "08:30",
    endTime: "10:00",
    subject: "Fisika Dasar",
    teacher: "Siti Aminah, M.Pd",
  },
  {
    id: 3,
    className: "XI IPS 1",
    day: "Selasa",
    startTime: "10:15",
    endTime: "11:45",
    subject: "Sosiologi Masyarakat",
    teacher: "Ahmad Fauzi, S.Sos",
  },
]);

const form = ref({
  id: null,
  className: "X MIPA 1",
  day: "Senin",
  startTime: "",
  endTime: "",
  subject: "",
  teacher: "",
});

const viewMode = ref("timeline"); // 'timeline' or 'table'
const activeClass = ref("X MIPA 1");

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");
const filterClass = ref("semua");
const filterDay = ref("semua");
const filterTeacher = ref("semua");

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
    className: "X MIPA 1",
    day: "Senin",
    startTime: "",
    endTime: "",
    subject: "",
    teacher: "",
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

const normalizeTime = (timeStr) => {
  if (!timeStr) return "";
  let normalized = timeStr.replace(".", ":");
  // Tambahkan nol di depan jika formatnya misal "7:00" -> "07:00"
  const parts = normalized.split(":");
  if (parts.length === 2) {
    const h = parts[0].padStart(2, "0");
    const m = parts[1].padStart(2, "0");
    return `${h}:${m}`;
  }
  return normalized;
};

const checkConflict = (schedule) => {
  return scheduleList.value.some((s) => {
    if (schedule.id && s.id === schedule.id) return false;
    if (s.className !== schedule.className || s.day !== schedule.day) return false;

    const start1 = timeToMinutes(s.startTime);
    const end1 = timeToMinutes(s.endTime);
    const start2 = timeToMinutes(schedule.startTime);
    const end2 = timeToMinutes(schedule.endTime);

    return start1 < end2 && end1 > start2;
  });
};

const hasConflict = (schedule) => {
  return scheduleList.value.some((s) => {
    if (s.id === schedule.id) return false;
    if (s.className !== schedule.className || s.day !== schedule.day) return false;

    const start1 = timeToMinutes(s.startTime);
    const end1 = timeToMinutes(s.endTime);
    const start2 = timeToMinutes(schedule.startTime);
    const end2 = timeToMinutes(schedule.endTime);

    return start1 < end2 && end1 > start2;
  });
};

const addEntry = () => {
  if (
    !form.value.subject ||
    !form.value.teacher ||
    !form.value.startTime ||
    !form.value.endTime
  ) {
    triggerToast("Gagal Menyimpan", "Mohon lengkapi semua kolom form!", "error");
    return;
  }

  form.value.startTime = normalizeTime(form.value.startTime);
  form.value.endTime = normalizeTime(form.value.endTime);

  if (checkConflict(form.value)) {
    triggerToast(
      "Gagal Menyimpan",
      "Terdapat jadwal yang bentrok di kelas dan hari tersebut!",
      "error"
    );
    return;
  }
  const newId =
    scheduleList.value.length > 0
      ? Math.max(...scheduleList.value.map((s) => s.id)) + 1
      : 1;
  scheduleList.value.push({ ...form.value, id: newId });

  isFormVisible.value = false;
  document.body.style.overflow = "";
  triggerToast("Berhasil Ditambahkan", "Data jadwal pelajaran baru telah ditambahkan.");
  resetForm();
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = {
    ...item,
    startTime: item.startTime,
    endTime: item.endTime,
  };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = () => {
  if (
    !form.value.subject ||
    !form.value.teacher ||
    !form.value.startTime ||
    !form.value.endTime
  ) {
    triggerToast("Gagal Menyimpan", "Mohon lengkapi semua kolom form!", "error");
    return;
  }

  form.value.startTime = normalizeTime(form.value.startTime);
  form.value.endTime = normalizeTime(form.value.endTime);

  if (checkConflict(form.value)) {
    triggerToast(
      "Gagal Menyimpan",
      "Terdapat jadwal yang bentrok di kelas dan hari tersebut!",
      "error"
    );
    return;
  }
  const index = scheduleList.value.findIndex((s) => s.id === form.value.id);
  if (index !== -1) {
    scheduleList.value[index] = { ...form.value };
  }

  isFormVisible.value = false;
  document.body.style.overflow = "";
  triggerToast("Perubahan Disimpan", "Data jadwal pelajaran berhasil diperbarui.");
  resetForm();
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
  if (itemToDelete.value !== null) {
    scheduleList.value = scheduleList.value.filter((s) => s.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast(
      "Data Dihapus",
      "Jadwal pelajaran berhasil dihapus dari sistem.",
      "info"
    );
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const uniqueTeachers = computed(() => {
  const teachers = new Set(scheduleList.value.map((s) => s.teacher));
  return Array.from(teachers).sort();
});

const filteredSchedule = computed(() => {
  let result = scheduleList.value;

  if (filterClass.value !== "semua") {
    result = result.filter((item) => item.className === filterClass.value);
  }

  if (filterDay.value !== "semua") {
    result = result.filter((item) => item.day === filterDay.value);
  }

  if (filterTeacher.value !== "semua") {
    result = result.filter((item) => item.teacher === filterTeacher.value);
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(
      (item) =>
        item.subject.toLowerCase().includes(query) ||
        item.teacher.toLowerCase().includes(query)
    );
  }

  // Sortir berdasarkan Kelas -> Hari -> Waktu Mulai
  const dayIndex = (day) => days.indexOf(day);
  result.sort((a, b) => {
    if (a.className !== b.className) return a.className.localeCompare(b.className);
    if (a.day !== b.day) return dayIndex(a.day) - dayIndex(b.day);
    return a.startTime.localeCompare(b.startTime);
  });

  return result;
});

const timeMin = 7 * 60; // 07:00
const timeMax = 16 * 60; // 16:00
const totalRange = timeMax - timeMin; // 9 jam = 540 menit

const timeToMinutes = (timeStr) => {
  if (!timeStr) return 0;
  const [h, m] = timeStr.split(":").map(Number);
  return h * 60 + m;
};

const getBlockStyle = (startTime, endTime) => {
  const start = timeToMinutes(startTime);
  const end = timeToMinutes(endTime);
  const left = ((start - timeMin) / totalRange) * 100;
  const width = ((end - start) / totalRange) * 100;
  return {
    left: `${Math.max(0, left)}%`,
    width: `${Math.min(100 - left, width)}%`,
  };
};

const groupedSchedule = computed(() => {
  const filtered = scheduleList.value.filter((s) => s.className === activeClass.value);

  // Palet analogous (berdekatan) untuk estetika teori warna yang harmonis dan modern
  const colors = [
    "bg-teal-500/95 backdrop-blur-sm text-white hover:bg-teal-600 dark:bg-teal-600/95 dark:hover:bg-teal-700",
    "bg-cyan-500/95 backdrop-blur-sm text-white hover:bg-cyan-600 dark:bg-cyan-600/95 dark:hover:bg-cyan-700",
    "bg-sky-500/95 backdrop-blur-sm text-white hover:bg-sky-600 dark:bg-sky-600/95 dark:hover:bg-sky-700",
    "bg-blue-500/95 backdrop-blur-sm text-white hover:bg-blue-600 dark:bg-blue-600/95 dark:hover:bg-blue-700",
    "bg-indigo-500/95 backdrop-blur-sm text-white hover:bg-indigo-600 dark:bg-indigo-600/95 dark:hover:bg-indigo-700",
    "bg-violet-500/95 backdrop-blur-sm text-white hover:bg-violet-600 dark:bg-violet-600/95 dark:hover:bg-violet-700",
  ];

  return days.map((d) => {
    const daySchedules = filtered
      .filter((s) => s.day === d)
      .sort((a, b) => a.startTime.localeCompare(b.startTime)); // Urutkan berdasarkan waktu

    let prevColorIndex = -1;

    const schedulesWithColor = daySchedules.map((schedule) => {
      let hash = 0;
      const subject = schedule.subject || "";
      for (let i = 0; i < subject.length; i++) {
        hash = subject.charCodeAt(i) + ((hash << 5) - hash);
      }

      let colorIndex = Math.abs(hash) % colors.length;

      // Apabila indeks warna sama dengan warna pada blok sebelumnya, ubah warnanya
      if (colorIndex === prevColorIndex) {
        colorIndex = (colorIndex + 1) % colors.length;
      }

      prevColorIndex = colorIndex;

      return {
        ...schedule,
        colorClass: colors[colorIndex],
      };
    });

    return {
      day: d,
      schedules: schedulesWithColor,
    };
  });
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
          Manajemen Jadwal Pelajaran
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola dan susun jadwal mata pelajaran untuk setiap rombongan belajar (kelas).
        </p>
      </div>
      <div class="flex items-center gap-3">
        <div class="bg-gray-100 dark:bg-slate-700 p-1 rounded-lg flex items-center">
          <button
            @click="viewMode = 'timeline'"
            class="px-3 py-1.5 rounded-md text-sm font-medium transition-all flex items-center"
            :class="
              viewMode === 'timeline'
                ? 'bg-white dark:bg-slate-600 text-blue-600 dark:text-blue-400 shadow-sm'
                : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'
            "
          >
            <PhCalendarBlank class="w-4 h-4 sm:mr-1.5" />
            <span class="hidden sm:inline">Timeline</span>
          </button>
          <button
            @click="viewMode = 'table'"
            class="px-3 py-1.5 rounded-md text-sm font-medium transition-all flex items-center"
            :class="
              viewMode === 'table'
                ? 'bg-white dark:bg-slate-600 text-blue-600 dark:text-blue-400 shadow-sm'
                : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'
            "
          >
            <PhList class="w-4 h-4 sm:mr-1.5" />
            <span class="hidden sm:inline">Tabel</span>
          </button>
        </div>
        <button
          v-if="!isFormVisible"
          @click="showAddForm"
          class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shrink-0"
        >
          <PhPlusCircle class="w-5 h-5 mr-2" />
          <span class="hidden sm:inline">Tambah Jadwal</span>
          <span class="sm:hidden">Tambah</span>
        </button>
      </div>
    </div>

    <!-- Form Tambah/Edit Jadwal -->
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
              {{ isEditing ? "Edit Jadwal Pelajaran" : "Tambah Jadwal Baru" }}
            </h3>
            <button
              @click="hideForm"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form id="jadwalForm" @submit.prevent="isEditing ? saveEntry() : addEntry()">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Kelas / Rombel
                  </label>
                  <select
                    v-model="form.className"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option v-for="c in classes" :key="c" :value="c">{{ c }}</option>
                  </select>
                </div>

                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Hari
                  </label>
                  <select
                    v-model="form.day"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option v-for="d in days" :key="d" :value="d">{{ d }}</option>
                  </select>
                </div>

                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Waktu Mulai
                  </label>
                  <input
                    type="time"
                    v-model="form.startTime"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>

                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Waktu Selesai
                  </label>
                  <input
                    type="time"
                    v-model="form.endTime"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>

                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Mata Pelajaran
                  </label>
                  <input
                    type="text"
                    v-model="form.subject"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: Matematika Peminatan"
                  />
                </div>

                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Guru Pengampu
                  </label>
                  <input
                    type="text"
                    v-model="form.teacher"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: Budi Santoso, S.Pd"
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
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
              <PhXCircle class="w-5 h-5 mr-2" />
              Batal
            </button>
            <button
              form="jadwalForm"
              type="submit"
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

    <!-- Gantt Chart Timeline View -->
    <div
      v-if="viewMode === 'timeline'"
      class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden p-4 sm:p-6 mb-8"
    >
      <div class="flex gap-2 mb-8 overflow-x-auto pb-2 custom-scrollbar">
        <button
          v-for="className in classes"
          :key="className"
          @click="activeClass = className"
          class="px-5 py-2.5 rounded-lg font-semibold whitespace-nowrap transition-colors border text-sm"
          :class="
            activeClass === className
              ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/30'
              : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100 hover:text-blue-600 dark:bg-slate-700 dark:border-slate-600 dark:text-gray-300 dark:hover:bg-slate-600 dark:hover:text-blue-400'
          "
        >
          {{ className }}
        </button>
      </div>

      <div class="overflow-x-auto pb-6 custom-scrollbar">
        <div
          class="min-w-[800px] lg:min-w-[1000px] relative mt-6 border-t border-l border-gray-200 dark:border-slate-700 rounded-tl-lg"
        >
          <!-- Background Grid Lines -->
          <div
            class="absolute top-0 bottom-0 left-[100px] right-0 pointer-events-none flex"
          >
            <div
              v-for="hour in 9"
              :key="'grid-' + hour"
              class="flex-1 border-r border-gray-200/70 border-dashed dark:border-slate-700/70 relative"
            >
              <span
                class="absolute -top-7 -translate-x-1/2 text-[10px] font-bold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-slate-700 px-2 py-0.5 rounded border border-gray-200 dark:border-slate-600 shadow-sm"
              >
                {{ String(hour + 6).padStart(2, "0") }}.00
              </span>
            </div>
            <!-- Last grid line text -->
            <div class="relative w-0">
              <span
                class="absolute -top-7 -translate-x-1/2 text-[10px] font-bold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-slate-700 px-2 py-0.5 rounded border border-gray-200 dark:border-slate-600 shadow-sm"
              >
                16.00
              </span>
            </div>
          </div>

          <!-- Class Rows -->
          <div
            class="relative z-10 divide-y divide-gray-100 dark:divide-slate-700/50 border-b border-gray-200 dark:border-slate-700"
          >
            <div
              v-for="group in groupedSchedule"
              :key="group.day"
              class="flex items-stretch relative group/row hover:bg-blue-50/50 dark:hover:bg-slate-700/50 transition-colors even:bg-gray-50/40 dark:even:bg-slate-800/40"
            >
              <!-- Day Name -->
              <div
                class="w-[100px] shrink-0 font-bold text-gray-700 dark:text-gray-300 text-sm flex items-center pl-4 py-6 border-r border-gray-200 dark:border-slate-700"
              >
                {{ group.day }}
              </div>

              <!-- Schedule Blocks Area -->
              <div class="flex-1 h-24 relative">
                <!-- Blocks -->
                <div
                  v-for="schedule in group.schedules"
                  :key="schedule.id"
                  class="absolute top-2 bottom-2 p-2.5 rounded-lg border border-white/20 dark:border-white/10 flex flex-col justify-center overflow-hidden cursor-pointer shadow-sm transition-all hover:z-20 hover:shadow-md hover:-translate-y-0.5 group/block"
                  :class="[
                    schedule.colorClass,
                    hasConflict(schedule)
                      ? 'ring-2 ring-red-500 ring-offset-1 dark:ring-offset-slate-800'
                      : '',
                  ]"
                  :style="getBlockStyle(schedule.startTime, schedule.endTime)"
                  @click="startEdit(schedule)"
                >
                  <div
                    class="font-bold text-[11px] sm:text-xs truncate leading-tight flex items-center gap-1 mb-1"
                  >
                    <PhWarningCircle
                      v-if="hasConflict(schedule)"
                      class="w-3.5 h-3.5 text-yellow-300 shrink-0"
                      title="Jadwal Bentrok"
                    />
                    {{ schedule.subject }}
                  </div>
                  <div
                    class="text-[10px] font-medium opacity-90 truncate flex items-center gap-1 mb-0.5"
                  >
                    <PhClock class="w-3 h-3 shrink-0" />
                    {{ schedule.startTime.replace(":", ".") }} -
                    {{ schedule.endTime.replace(":", ".") }}
                  </div>
                  <div class="text-[10px] opacity-80 truncate flex items-center gap-1">
                    <PhChalkboardTeacher class="w-3 h-3 shrink-0" />
                    {{ schedule.teacher }}
                  </div>

                  <!-- Floating Delete Button -->
                  <button
                    @click.stop="deleteEntry(schedule.id)"
                    class="absolute top-1 right-1 p-1 rounded-md bg-black/20 hover:bg-red-500 text-white opacity-0 group-hover/block:opacity-100 transition-all"
                  >
                    <PhTrash class="w-3.5 h-3.5" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table & Filters -->
    <div
      v-if="viewMode === 'table'"
      class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden"
    >
      <div
        class="p-6 border-b border-gray-100 dark:border-slate-700 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4"
      >
        <div class="flex flex-col sm:flex-row gap-4 w-full">
          <div class="relative flex-1 md:max-w-md">
            <div
              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
            >
              <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
            </div>
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Cari mata pelajaran atau guru..."
              class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>
          <select
            v-model="filterClass"
            class="block w-full sm:w-40 px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Kelas</option>
            <option v-for="c in classes" :key="c" :value="c">{{ c }}</option>
          </select>
          <select
            v-model="filterDay"
            class="block w-full sm:w-40 px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Hari</option>
            <option v-for="d in days" :key="d" :value="d">{{ d }}</option>
          </select>
          <select
            v-model="filterTeacher"
            class="block w-full sm:w-48 px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Guru</option>
            <option v-for="teacher in uniqueTeachers" :key="teacher" :value="teacher">
              {{ teacher }}
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
              <th class="px-6 py-4">Kelas & Hari</th>
              <th class="px-6 py-4">Waktu</th>
              <th class="px-6 py-4">Mata Pelajaran</th>
              <th class="px-6 py-4 hidden sm:table-cell">Guru Pengampu</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            <tr v-if="filteredSchedule.length === 0">
              <td
                colspan="5"
                class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
              >
                <PhCalendar
                  class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-500 mb-3"
                />
                <p>Tidak ada jadwal pelajaran yang ditemukan.</p>
              </td>
            </tr>
            <tr
              v-for="schedule in filteredSchedule"
              :key="schedule.id"
              class="hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors group"
            >
              <td class="px-6 py-4">
                <span
                  class="block text-sm font-semibold text-gray-800 dark:text-gray-200"
                >
                  {{ schedule.className }}
                </span>
                <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                  {{ schedule.day }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div
                  class="flex items-center text-sm text-blue-600 dark:text-blue-400 font-medium"
                >
                  <PhClock class="w-4 h-4 mr-1.5" />
                  {{ schedule.startTime.replace(":", ".") }} -
                  {{ schedule.endTime.replace(":", ".") }}
                </div>
              </td>
              <td class="px-6 py-4">
                <div
                  class="flex items-center text-sm font-semibold text-gray-800 dark:text-gray-200"
                >
                  <PhBookOpen class="w-4 h-4 mr-1.5 text-gray-400" />
                  {{ schedule.subject }}
                </div>
              </td>
              <td class="px-6 py-4 hidden sm:table-cell">
                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                  <PhChalkboardTeacher class="w-4 h-4 mr-1.5 text-gray-400" />
                  {{ schedule.teacher }}
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <div
                  class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <button
                    @click="startEdit(schedule)"
                    class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors"
                    title="Edit Jadwal"
                  >
                    <PhPencilSimple class="w-4 h-4" />
                  </button>
                  <button
                    @click="deleteEntry(schedule.id)"
                    class="p-1.5 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-md transition-colors"
                    title="Hapus Jadwal"
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
      title="Hapus Jadwal Pelajaran"
      message="Yakin ingin menghapus jadwal pelajaran ini?"
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
