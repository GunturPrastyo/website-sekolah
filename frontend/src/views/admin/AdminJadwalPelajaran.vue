<script setup>
import { ref, computed, onMounted, watch } from "vue";
import api from "@/api/index.js";
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
const classesList = ref([]);
const subjectsList = ref([]);
const teachersList = ref([]);
const scheduleList = ref([]);

const fetchData = async () => {
  try {
    const [classesRes, subjectsRes, teachersRes, schedulesRes] = await Promise.all([
      api.get("/api/school-classes"),
      api.get("/api/curriculum-subjects"),
      api.get("/api/staff"),
      api.get("/api/lesson-schedules"),
    ]);

    classesList.value = classesRes.data.data;
    subjectsList.value = subjectsRes.data.data;
    teachersList.value = teachersRes.data.data;
    scheduleList.value = schedulesRes.data.data;

    // Set default active class jika null dan data tersedia
    if (!activeClassId.value && classesList.value.length > 0) {
      activeClassId.value = classesList.value[0].id;
    }
  } catch (error) {
    console.error("Gagal mengambil data:", error);
    triggerToast("Error", "Gagal memuat data jadwal pelajaran", "error");
  }
};

onMounted(() => {
  fetchData();
});

const form = ref({
  id: null,
  school_class_id: null,
  day: "Senin",
  start_time: "07:00",
  end_time: "08:30",
  curriculum_subject_id: null,
  staff_id: null,
});

const viewMode = ref("timeline"); // 'timeline' or 'table'
const activeClassId = ref(null);

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const isStartDropdownOpen = ref(false);
const isEndDropdownOpen = ref(false);

const isSubjectDropdownOpen = ref(false);
const isTeacherDropdownOpen = ref(false);

const subjectSearch = ref("");
const teacherSearch = ref("");
const subjectPage = ref(1);
const teacherPage = ref(1);
const itemsPerPage = 10;

watch(subjectSearch, () => {
  subjectPage.value = 1;
});
watch(teacherSearch, () => {
  teacherPage.value = 1;
});

const filteredSubjectsList = computed(() => {
  if (!subjectSearch.value) return subjectsList.value;
  const q = subjectSearch.value.toLowerCase();
  return subjectsList.value.filter((s) => s.name.toLowerCase().includes(q));
});
const paginatedSubjects = computed(() => {
  const start = (subjectPage.value - 1) * itemsPerPage;
  return filteredSubjectsList.value.slice(start, start + itemsPerPage);
});
const totalSubjectPages = computed(
  () => Math.ceil(filteredSubjectsList.value.length / itemsPerPage) || 1
);

const filteredTeachersList = computed(() => {
  if (!teacherSearch.value) return teachersList.value;
  const q = teacherSearch.value.toLowerCase();
  return teachersList.value.filter((t) => t.name.toLowerCase().includes(q));
});
const paginatedTeachers = computed(() => {
  const start = (teacherPage.value - 1) * itemsPerPage;
  return filteredTeachersList.value.slice(start, start + itemsPerPage);
});
const totalTeacherPages = computed(
  () => Math.ceil(filteredTeachersList.value.length / itemsPerPage) || 1
);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");
const filterClass = ref("semua");
const filterDay = ref("semua");
const filterTeacher = ref("semua");

const hours = Array.from({ length: 24 }, (_, i) => String(i).padStart(2, "0"));
const minutes = Array.from({ length: 60 }, (_, i) => String(i).padStart(2, "0"));

const formStartHour = computed({
  get: () => (form.value.start_time ? form.value.start_time.split(":")[0] : "07"),
  set: (val) => {
    const currentM = form.value.start_time ? form.value.start_time.split(":")[1] : "00";
    form.value.start_time = `${val}:${currentM}`;
  },
});

const formStartMinute = computed({
  get: () => (form.value.start_time ? form.value.start_time.split(":")[1] : "00"),
  set: (val) => {
    const currentH = form.value.start_time ? form.value.start_time.split(":")[0] : "07";
    form.value.start_time = `${currentH}:${val}`;
  },
});

const formEndHour = computed({
  get: () => (form.value.end_time ? form.value.end_time.split(":")[0] : "08"),
  set: (val) => {
    const currentM = form.value.end_time ? form.value.end_time.split(":")[1] : "30";
    form.value.end_time = `${val}:${currentM}`;
  },
});

const formEndMinute = computed({
  get: () => (form.value.end_time ? form.value.end_time.split(":")[1] : "30"),
  set: (val) => {
    const currentH = form.value.end_time ? form.value.end_time.split(":")[0] : "08";
    form.value.end_time = `${currentH}:${val}`;
  },
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
    school_class_id: classesList.value.length > 0 ? classesList.value[0].id : null,
    day: "Senin",
    start_time: "07:00",
    end_time: "08:30",
    curriculum_subject_id: null,
    staff_id: null,
  };
  isEditing.value = false;
  isStartDropdownOpen.value = false;
  isEndDropdownOpen.value = false;
  isSubjectDropdownOpen.value = false;
  isTeacherDropdownOpen.value = false;
  subjectSearch.value = "";
  teacherSearch.value = "";
  subjectPage.value = 1;
  teacherPage.value = 1;
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
    if (s.class_id !== schedule.school_class_id || s.day !== schedule.day) return false;

    const start1 = timeToMinutes(s.startTime);
    const end1 = timeToMinutes(s.endTime);
    const start2 = timeToMinutes(schedule.start_time);
    const end2 = timeToMinutes(schedule.end_time);

    return start1 < end2 && end1 > start2;
  });
};

const hasConflict = (schedule) => {
  return scheduleList.value.some((s) => {
    if (s.id === schedule.id) return false;
    if (s.class_id !== schedule.class_id || s.day !== schedule.day) return false;

    const start1 = timeToMinutes(s.startTime);
    const end1 = timeToMinutes(s.endTime);
    const start2 = timeToMinutes(schedule.startTime);
    const end2 = timeToMinutes(schedule.endTime);

    return start1 < end2 && end1 > start2;
  });
};

const addEntry = async () => {
  if (
    !form.value.curriculum_subject_id ||
    !form.value.staff_id ||
    !form.value.start_time ||
    !form.value.end_time
  ) {
    triggerToast("Gagal Menyimpan", "Mohon lengkapi semua kolom form!", "error");
    return;
  }

  form.value.start_time = normalizeTime(form.value.start_time);
  form.value.end_time = normalizeTime(form.value.end_time);

  if (checkConflict(form.value)) {
    triggerToast(
      "Gagal Menyimpan",
      "Terdapat jadwal yang bentrok di kelas dan hari tersebut!",
      "error"
    );
    return;
  }

  try {
    await api.post("/api/lesson-schedules", form.value);
    await fetchData();
    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast("Berhasil Ditambahkan", "Data jadwal pelajaran baru telah ditambahkan.");
    resetForm();
  } catch (error) {
    console.error(error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan pada server.", "error");
  }
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = {
    id: item.id,
    school_class_id: item.class_id,
    day: item.day,
    start_time: item.startTime,
    end_time: item.endTime,
    curriculum_subject_id: item.subject_id,
    staff_id: item.teacher_id,
  };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = async () => {
  if (
    !form.value.curriculum_subject_id ||
    !form.value.staff_id ||
    !form.value.start_time ||
    !form.value.end_time
  ) {
    triggerToast("Gagal Menyimpan", "Mohon lengkapi semua kolom form!", "error");
    return;
  }

  form.value.start_time = normalizeTime(form.value.start_time);
  form.value.end_time = normalizeTime(form.value.end_time);

  if (checkConflict(form.value)) {
    triggerToast(
      "Gagal Menyimpan",
      "Terdapat jadwal yang bentrok di kelas dan hari tersebut!",
      "error"
    );
    return;
  }

  try {
    await api.put(`/api/lesson-schedules/${form.value.id}`, form.value);
    await fetchData();
    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast("Perubahan Disimpan", "Data jadwal pelajaran berhasil diperbarui.");
    resetForm();
  } catch (error) {
    console.error(error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan pada server.", "error");
  }
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = async () => {
  if (itemToDelete.value !== null) {
    try {
      await api.delete(`/api/lesson-schedules/${itemToDelete.value}`);
      await fetchData();
      itemToDelete.value = null;
      triggerToast(
        "Data Dihapus",
        "Jadwal pelajaran berhasil dihapus dari sistem.",
        "info"
      );
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

const filteredSchedule = computed(() => {
  let result = scheduleList.value;

  if (filterClass.value !== "semua") {
    result = result.filter((item) => item.class_id === filterClass.value);
  }

  if (filterDay.value !== "semua") {
    result = result.filter((item) => item.day === filterDay.value);
  }

  if (filterTeacher.value !== "semua") {
    result = result.filter((item) => item.teacher_id === filterTeacher.value);
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(
      (item) =>
        (item.subject && item.subject.toLowerCase().includes(query)) ||
        (item.teacher && item.teacher.toLowerCase().includes(query))
    );
  }

  // Sortir berdasarkan Kelas -> Hari -> Waktu Mulai
  const dayIndex = (day) => days.indexOf(day);
  result.sort((a, b) => {
    if (a.className !== b.className) {
      return (a.className || "").localeCompare(b.className || "");
    }
    if (a.day !== b.day) return dayIndex(a.day) - dayIndex(b.day);
    return (a.startTime || "").localeCompare(b.startTime || "");
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
  const filtered = scheduleList.value.filter((s) => s.class_id === activeClassId.value);

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
        class="fixed inset-0 z-[100] overflow-y-auto bg-black/50 backdrop-blur-sm"
        @click="hideForm"
      >
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
          <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-2xl overflow-visible transform transition-all"
            @click.stop
          >
            <div
              class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 rounded-t-xl"
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
            <div class="p-6 overflow-visible flex-1">
              <form
                id="jadwalForm"
                @submit.prevent="isEditing ? saveEntry() : addEntry()"
              >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                      Kelas / Rombel
                    </label>
                    <select
                      v-model="form.school_class_id"
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    >
                      <option v-for="c in classesList" :key="c.id" :value="c.id">
                        {{ c.name }}
                      </option>
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

                  <div class="relative" :class="{ 'z-50': isStartDropdownOpen }">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                      Waktu Mulai
                    </label>
                    <div
                      class="flex items-center justify-between w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white hover:border-blue-400 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-colors cursor-pointer"
                      @click="
                        isStartDropdownOpen = !isStartDropdownOpen;
                        isEndDropdownOpen = false;
                        isSubjectDropdownOpen = false;
                        isTeacherDropdownOpen = false;
                      "
                    >
                      <div class="flex items-center">
                        <span class="font-medium w-6 text-center">{{
                          formStartHour
                        }}</span>
                        <span class="mx-1 font-bold">:</span>
                        <span class="font-medium w-6 text-center">{{
                          formStartMinute
                        }}</span>
                      </div>
                      <PhClock class="w-5 h-5 text-gray-400 pointer-events-none" />
                    </div>

                    <Transition
                      enter-active-class="transition ease-out duration-200"
                      enter-from-class="opacity-0 translate-y-1 scale-95"
                      enter-to-class="opacity-100 translate-y-0 scale-100"
                      leave-active-class="transition ease-in duration-150"
                      leave-from-class="opacity-100 translate-y-0 scale-100"
                      leave-to-class="opacity-0 translate-y-1 scale-95"
                    >
                      <div
                        v-if="isStartDropdownOpen"
                        class="absolute top-full left-0 mt-2 w-full bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl z-50 flex flex-col overflow-hidden origin-top"
                      >
                        <div
                          class="fixed inset-0 z-[-1] cursor-default"
                          @click.stop="isStartDropdownOpen = false"
                        ></div>

                        <div
                          class="flex border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50"
                        >
                          <div
                            class="flex-1 text-center py-2 text-xs font-bold text-gray-500 dark:text-gray-400"
                          >
                            JAM
                          </div>
                          <div
                            class="flex-1 text-center py-2 text-xs font-bold text-gray-500 dark:text-gray-400 border-l border-gray-100 dark:border-slate-700"
                          >
                            MENIT
                          </div>
                        </div>

                        <div class="flex h-48 bg-white dark:bg-slate-800 relative z-10">
                          <div
                            class="flex-1 overflow-y-auto custom-scrollbar border-r border-gray-100 dark:border-slate-700 scroll-smooth"
                          >
                            <button
                              v-for="h in hours"
                              :key="h"
                              type="button"
                              @click.stop="formStartHour = h"
                              class="w-full text-center py-2 text-sm hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors"
                              :class="
                                formStartHour === h
                                  ? 'bg-blue-100 text-blue-600 font-bold dark:bg-slate-700 dark:text-blue-400'
                                  : 'text-gray-700 dark:text-gray-300'
                              "
                            >
                              {{ h }}
                            </button>
                          </div>
                          <div
                            class="flex-1 overflow-y-auto custom-scrollbar scroll-smooth"
                          >
                            <button
                              v-for="m in minutes"
                              :key="m"
                              type="button"
                              @click.stop="formStartMinute = m"
                              class="w-full text-center py-2 text-sm hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors"
                              :class="
                                formStartMinute === m
                                  ? 'bg-blue-100 text-blue-600 font-bold dark:bg-slate-700 dark:text-blue-400'
                                  : 'text-gray-700 dark:text-gray-300'
                              "
                            >
                              {{ m }}
                            </button>
                          </div>
                        </div>

                        <div
                          class="p-2 border-t border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 relative z-10"
                        >
                          <button
                            type="button"
                            @click.stop="isStartDropdownOpen = false"
                            class="w-full py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm"
                          >
                            Pilih Waktu
                          </button>
                        </div>
                      </div>
                    </Transition>
                  </div>

                  <div class="relative" :class="{ 'z-50': isEndDropdownOpen }">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                      Waktu Selesai
                    </label>
                    <div
                      class="flex items-center justify-between w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white hover:border-blue-400 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-colors cursor-pointer"
                      @click="
                        isEndDropdownOpen = !isEndDropdownOpen;
                        isStartDropdownOpen = false;
                        isSubjectDropdownOpen = false;
                        isTeacherDropdownOpen = false;
                      "
                    >
                      <div class="flex items-center">
                        <span class="font-medium w-6 text-center">{{ formEndHour }}</span>
                        <span class="mx-1 font-bold">:</span>
                        <span class="font-medium w-6 text-center">{{
                          formEndMinute
                        }}</span>
                      </div>
                      <PhClock class="w-5 h-5 text-gray-400 pointer-events-none" />
                    </div>

                    <Transition
                      enter-active-class="transition ease-out duration-200"
                      enter-from-class="opacity-0 translate-y-1 scale-95"
                      enter-to-class="opacity-100 translate-y-0 scale-100"
                      leave-active-class="transition ease-in duration-150"
                      leave-from-class="opacity-100 translate-y-0 scale-100"
                      leave-to-class="opacity-0 translate-y-1 scale-95"
                    >
                      <div
                        v-if="isEndDropdownOpen"
                        class="absolute top-full left-0 mt-2 w-full bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl z-50 flex flex-col overflow-hidden origin-top"
                      >
                        <div
                          class="fixed inset-0 z-[-1] cursor-default"
                          @click.stop="isEndDropdownOpen = false"
                        ></div>

                        <div
                          class="flex border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50"
                        >
                          <div
                            class="flex-1 text-center py-2 text-xs font-bold text-gray-500 dark:text-gray-400"
                          >
                            JAM
                          </div>
                          <div
                            class="flex-1 text-center py-2 text-xs font-bold text-gray-500 dark:text-gray-400 border-l border-gray-100 dark:border-slate-700"
                          >
                            MENIT
                          </div>
                        </div>

                        <div class="flex h-48 bg-white dark:bg-slate-800 relative z-10">
                          <div
                            class="flex-1 overflow-y-auto custom-scrollbar border-r border-gray-100 dark:border-slate-700 scroll-smooth"
                          >
                            <button
                              v-for="h in hours"
                              :key="h"
                              type="button"
                              @click.stop="formEndHour = h"
                              class="w-full text-center py-2 text-sm hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors"
                              :class="
                                formEndHour === h
                                  ? 'bg-blue-100 text-blue-600 font-bold dark:bg-slate-700 dark:text-blue-400'
                                  : 'text-gray-700 dark:text-gray-300'
                              "
                            >
                              {{ h }}
                            </button>
                          </div>
                          <div
                            class="flex-1 overflow-y-auto custom-scrollbar scroll-smooth"
                          >
                            <button
                              v-for="m in minutes"
                              :key="m"
                              type="button"
                              @click.stop="formEndMinute = m"
                              class="w-full text-center py-2 text-sm hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors"
                              :class="
                                formEndMinute === m
                                  ? 'bg-blue-100 text-blue-600 font-bold dark:bg-slate-700 dark:text-blue-400'
                                  : 'text-gray-700 dark:text-gray-300'
                              "
                            >
                              {{ m }}
                            </button>
                          </div>
                        </div>

                        <div
                          class="p-2 border-t border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 relative z-10"
                        >
                          <button
                            type="button"
                            @click.stop="isEndDropdownOpen = false"
                            class="w-full py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm"
                          >
                            Pilih Waktu
                          </button>
                        </div>
                      </div>
                    </Transition>
                  </div>

                  <div
                    class="md:col-span-2 relative"
                    :class="{ 'z-50': isSubjectDropdownOpen }"
                  >
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                      Mata Pelajaran
                    </label>
                    <div
                      class="flex items-center justify-between w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white hover:border-blue-400 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-colors cursor-pointer"
                      @click="
                        isSubjectDropdownOpen = !isSubjectDropdownOpen;
                        isTeacherDropdownOpen = false;
                        isStartDropdownOpen = false;
                        isEndDropdownOpen = false;
                      "
                    >
                      <span class="truncate">{{
                        form.curriculum_subject_id
                          ? subjectsList.find((s) => s.id === form.curriculum_subject_id)
                              ?.name || "Pilih Mata Pelajaran"
                          : "Pilih Mata Pelajaran"
                      }}</span>
                      <PhBookOpen class="w-5 h-5 text-gray-400 pointer-events-none" />
                    </div>

                    <Transition
                      enter-active-class="transition ease-out duration-200"
                      enter-from-class="opacity-0 translate-y-1 scale-95"
                      enter-to-class="opacity-100 translate-y-0 scale-100"
                      leave-active-class="transition ease-in duration-150"
                      leave-from-class="opacity-100 translate-y-0 scale-100"
                      leave-to-class="opacity-0 translate-y-1 scale-95"
                    >
                      <div
                        v-if="isSubjectDropdownOpen"
                        class="absolute top-full left-0 mt-2 w-full bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl z-50 flex flex-col overflow-hidden origin-top"
                      >
                        <div
                          class="fixed inset-0 z-[-1] cursor-default"
                          @click.stop="isSubjectDropdownOpen = false"
                        ></div>

                        <div class="p-2 border-b border-gray-100 dark:border-slate-700">
                          <input
                            v-model="subjectSearch"
                            type="text"
                            placeholder="Cari mata pelajaran..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-gray-50 dark:bg-slate-700 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 dark:text-white"
                            @click.stop
                          />
                        </div>

                        <div class="max-h-48 overflow-y-auto custom-scrollbar">
                          <div
                            v-if="paginatedSubjects.length === 0"
                            class="px-4 py-3 text-sm text-gray-500 text-center"
                          >
                            Tidak ada data.
                          </div>
                          <button
                            v-for="s in paginatedSubjects"
                            :key="s.id"
                            type="button"
                            @click.stop="
                              form.curriculum_subject_id = s.id;
                              isSubjectDropdownOpen = false;
                            "
                            class="w-full text-left px-4 py-2 text-sm hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors"
                            :class="
                              form.curriculum_subject_id === s.id
                                ? 'bg-blue-100 text-blue-600 font-bold dark:bg-slate-700 dark:text-blue-400'
                                : 'text-gray-700 dark:text-gray-300'
                            "
                          >
                            {{ s.name }}
                          </button>
                        </div>

                        <div
                          class="p-2 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-between items-center text-sm"
                          @click.stop
                        >
                          <button
                            type="button"
                            :disabled="subjectPage === 1"
                            @click.stop="subjectPage--"
                            class="px-2 py-1 rounded text-gray-600 hover:bg-gray-200 disabled:opacity-50 dark:text-gray-300 dark:hover:bg-slate-600"
                          >
                            Sebelumnya
                          </button>
                          <span class="text-gray-500 dark:text-gray-400 text-xs"
                            >Halaman {{ subjectPage }} dari {{ totalSubjectPages }}</span
                          >
                          <button
                            type="button"
                            :disabled="subjectPage >= totalSubjectPages"
                            @click.stop="subjectPage++"
                            class="px-2 py-1 rounded text-gray-600 hover:bg-gray-200 disabled:opacity-50 dark:text-gray-300 dark:hover:bg-slate-600"
                          >
                            Selanjutnya
                          </button>
                        </div>
                      </div>
                    </Transition>
                  </div>

                  <div
                    class="md:col-span-2 relative"
                    :class="{ 'z-50': isTeacherDropdownOpen }"
                  >
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                      Guru Pengampu
                    </label>
                    <div
                      class="flex items-center justify-between w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white hover:border-blue-400 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-colors cursor-pointer"
                      @click="
                        isTeacherDropdownOpen = !isTeacherDropdownOpen;
                        isSubjectDropdownOpen = false;
                        isStartDropdownOpen = false;
                        isEndDropdownOpen = false;
                      "
                    >
                      <span class="truncate">{{
                        form.staff_id
                          ? teachersList.find((t) => t.id === form.staff_id)?.name ||
                            "Pilih Guru Pengampu"
                          : "Pilih Guru Pengampu"
                      }}</span>
                      <PhChalkboardTeacher
                        class="w-5 h-5 text-gray-400 pointer-events-none"
                      />
                    </div>

                    <Transition
                      enter-active-class="transition ease-out duration-200"
                      enter-from-class="opacity-0 translate-y-1 scale-95"
                      enter-to-class="opacity-100 translate-y-0 scale-100"
                      leave-active-class="transition ease-in duration-150"
                      leave-from-class="opacity-100 translate-y-0 scale-100"
                      leave-to-class="opacity-0 translate-y-1 scale-95"
                    >
                      <div
                        v-if="isTeacherDropdownOpen"
                        class="absolute top-full left-0 mt-2 w-full bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl z-50 flex flex-col overflow-hidden origin-top"
                      >
                        <div
                          class="fixed inset-0 z-[-1] cursor-default"
                          @click.stop="isTeacherDropdownOpen = false"
                        ></div>

                        <div class="p-2 border-b border-gray-100 dark:border-slate-700">
                          <input
                            v-model="teacherSearch"
                            type="text"
                            placeholder="Cari guru pengampu..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-gray-50 dark:bg-slate-700 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 dark:text-white"
                            @click.stop
                          />
                        </div>

                        <div class="max-h-48 overflow-y-auto custom-scrollbar">
                          <div
                            v-if="paginatedTeachers.length === 0"
                            class="px-4 py-3 text-sm text-gray-500 text-center"
                          >
                            Tidak ada data.
                          </div>
                          <button
                            v-for="t in paginatedTeachers"
                            :key="t.id"
                            type="button"
                            @click.stop="
                              form.staff_id = t.id;
                              isTeacherDropdownOpen = false;
                            "
                            class="w-full text-left px-4 py-2 text-sm hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors"
                            :class="
                              form.staff_id === t.id
                                ? 'bg-blue-100 text-blue-600 font-bold dark:bg-slate-700 dark:text-blue-400'
                                : 'text-gray-700 dark:text-gray-300'
                            "
                          >
                            {{ t.name }}
                          </button>
                        </div>

                        <div
                          class="p-2 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-between items-center text-sm"
                          @click.stop
                        >
                          <button
                            type="button"
                            :disabled="teacherPage === 1"
                            @click.stop="teacherPage--"
                            class="px-2 py-1 rounded text-gray-600 hover:bg-gray-200 disabled:opacity-50 dark:text-gray-300 dark:hover:bg-slate-600"
                          >
                            Sebelumnya
                          </button>
                          <span class="text-gray-500 dark:text-gray-400 text-xs"
                            >Halaman {{ teacherPage }} dari {{ totalTeacherPages }}</span
                          >
                          <button
                            type="button"
                            :disabled="teacherPage >= totalTeacherPages"
                            @click.stop="teacherPage++"
                            class="px-2 py-1 rounded text-gray-600 hover:bg-gray-200 disabled:opacity-50 dark:text-gray-300 dark:hover:bg-slate-600"
                          >
                            Selanjutnya
                          </button>
                        </div>
                      </div>
                    </Transition>
                  </div>
                </div>
              </form>
            </div>
            <div
              class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3 rounded-b-xl"
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
      </div>
    </Transition>

    <!-- Gantt Chart Timeline View -->
    <div
      v-if="viewMode === 'timeline'"
      class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden p-4 sm:p-6 mb-8"
    >
      <div class="flex gap-2 mb-8 overflow-x-auto pb-2 custom-scrollbar">
        <button
          v-for="c in classesList"
          :key="c.id"
          @click="activeClassId = c.id"
          class="px-5 py-2.5 rounded-lg font-semibold whitespace-nowrap transition-colors border text-sm"
          :class="
            activeClassId === c.id
              ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/30'
              : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100 hover:text-blue-600 dark:bg-slate-700 dark:border-slate-600 dark:text-gray-300 dark:hover:bg-slate-600 dark:hover:text-blue-400'
          "
        >
          {{ c.name }}
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
            <option v-for="c in classesList" :key="c.id" :value="c.id">
              {{ c.name }}
            </option>
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
            <option v-for="teacher in teachersList" :key="teacher.id" :value="teacher.id">
              {{ teacher.name }}
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
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #475569;
}
</style>
