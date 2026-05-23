<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import api from "@/api/index.js";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhDotsSixVertical,
  PhX,
  PhListChecks,
  PhGitMerge,
  PhCheckCircle,
  PhMapPin,
  PhMedal,
  PhHandshake,
  PhBriefcase,
  PhSignpost,
  PhBuildings,
  PhUsers,
  PhQuestion,
  PhFilePdf,
  PhUploadSimple,
  PhClock,
  PhCalendarBlank,
} from "@phosphor-icons/vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

// State Data
const syarat = ref([]);
const alur = ref([]);
const jalur = ref([]);
const faqs = ref([]);

const openingDate = ref("");
const countdown = ref({ days: 0, hours: 0, minutes: 0, seconds: 0 });
const isOpeningDateModalOpen = ref(false);
const tempOpeningDate = ref("");
let timerInterval = null;

const iconMap = {
  PhMapPin,
  PhMedal,
  PhHandshake,
  PhBriefcase,
  PhBuildings,
  PhUsers,
};

const fileBrosur = ref(null);
const brosurInput = ref(null);

// Toast
const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

const fetchPpdbInfo = async () => {
  try {
    const response = await api.get('/api/ppdb-info');
    const data = response.data.data;
    if (data) {
      if (data.syarat) syarat.value = data.syarat;
      if (data.alur) alur.value = data.alur;
      if (data.jalur) jalur.value = data.jalur;
      if (data.faqs) faqs.value = data.faqs;
      if (data.opening_date) {
        openingDate.value = data.opening_date;
        startCountdown();
      }
      if (data.brosur_url) {
        fileBrosur.value = { name: "Brosur PPDB.pdf", url: data.brosur_url, isUploaded: true };
      }
    }
  } catch (error) {
    console.error("Gagal mengambil info PPDB", error);
  }
};

onMounted(() => {
  fetchPpdbInfo();
});

onUnmounted(() => {
  if (timerInterval) clearInterval(timerInterval);
});

const startCountdown = () => {
  if (timerInterval) clearInterval(timerInterval);
  
  if (!openingDate.value) return;

  const targetDate = new Date(openingDate.value).getTime();
  
  timerInterval = setInterval(() => {
    const now = new Date().getTime();
    const distance = targetDate - now;
    
    if (distance < 0) {
      clearInterval(timerInterval);
      countdown.value = { days: 0, hours: 0, minutes: 0, seconds: 0 };
      return;
    }
    
    countdown.value = {
      days: Math.floor(distance / (1000 * 60 * 60 * 24)),
      hours: Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
      minutes: Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
      seconds: Math.floor((distance % (1000 * 60)) / 1000)
    };
  }, 1000);
};

const openOpeningDateModal = () => {
  tempOpeningDate.value = openingDate.value ? new Date(openingDate.value).toISOString().slice(0, 16) : "";
  isOpeningDateModalOpen.value = true;
  document.body.style.overflow = "hidden";
};

const closeOpeningDateModal = () => {
  isOpeningDateModalOpen.value = false;
  document.body.style.overflow = "";
};

const saveOpeningDate = async () => {
  try {
    await api.put('/api/ppdb-info', { opening_date: tempOpeningDate.value });
    openingDate.value = tempOpeningDate.value;
    startCountdown();
    closeOpeningDateModal();
    triggerToast("Berhasil Disimpan", "Waktu pembukaan PPDB berhasil diperbarui.");
  } catch (error) {
    triggerToast("Gagal", "Terjadi kesalahan saat menyimpan waktu pembukaan.", "error");
  }
};

// Drag and Drop Generic
const draggedIndex = ref(null);
const handleDragStart = (index, event) => {
  draggedIndex.value = index;
  event.dataTransfer.effectAllowed = "move";
};
const handleDrop = (index, list) => {
  if (draggedIndex.value === null) return;
  if (draggedIndex.value === index) return;
  const draggedItem = list.splice(draggedIndex.value, 1)[0];
  list.splice(index, 0, draggedItem);
  draggedIndex.value = null;
};

// Syarat Modal
const isSyaratModalOpen = ref(false);
const tempSyarat = ref([]);

const openSyaratModal = () => {
  tempSyarat.value = syarat.value.map((s) => ({ ...s }));
  isSyaratModalOpen.value = true;
  document.body.style.overflow = "hidden";
};
const closeSyaratModal = () => {
  isSyaratModalOpen.value = false;
  document.body.style.overflow = "";
};
const saveSyarat = async () => {
  try {
    const newData = tempSyarat.value.filter((s) => s.text.trim() !== "");
    await api.put('/api/ppdb-info', { syarat: newData });
    syarat.value = newData;
    closeSyaratModal();
    triggerToast("Berhasil Disimpan", "Data Syarat Pendaftaran berhasil diperbarui.");
  } catch(error) {
    triggerToast("Gagal", "Terjadi kesalahan saat menyimpan.", "error");
  }
};
const addSyarat = () => tempSyarat.value.push({ id: Date.now(), text: "" });
const removeSyarat = (index) => tempSyarat.value.splice(index, 1);

// Alur Modal
const isAlurModalOpen = ref(false);
const tempAlur = ref([]);

const openAlurModal = () => {
  tempAlur.value = alur.value.map((a) => ({ ...a }));
  isAlurModalOpen.value = true;
  document.body.style.overflow = "hidden";
};
const closeAlurModal = () => {
  isAlurModalOpen.value = false;
  document.body.style.overflow = "";
};
const saveAlur = async () => {
  try {
    const newData = tempAlur.value.map((a, index) => ({ ...a, step: index + 1 }));
    await api.put('/api/ppdb-info', { alur: newData });
    alur.value = newData;
    closeAlurModal();
    triggerToast("Berhasil Disimpan", "Data Alur Pendaftaran berhasil diperbarui.");
  } catch(error) {
    triggerToast("Gagal", "Terjadi kesalahan saat menyimpan.", "error");
  }
};
const addAlur = () =>
  tempAlur.value.push({ id: Date.now(), step: 0, title: "", description: "" });
const removeAlur = (index) => tempAlur.value.splice(index, 1);

// Jalur Modal
const isJalurModalOpen = ref(false);
const tempJalur = ref([]);

const openJalurModal = () => {
  tempJalur.value = jalur.value.map((j) => ({ ...j }));
  isJalurModalOpen.value = true;
  document.body.style.overflow = "hidden";
};
const closeJalurModal = () => {
  isJalurModalOpen.value = false;
  document.body.style.overflow = "";
};
const saveJalur = async () => {
  try {
    const newData = tempJalur.value.filter((j) => j.name.trim() !== "");
    await api.put('/api/ppdb-info', { jalur: newData });
    jalur.value = newData;
    closeJalurModal();
    triggerToast("Berhasil Disimpan", "Data Jalur Pendaftaran berhasil diperbarui.");
  } catch(error) {
    triggerToast("Gagal", "Terjadi kesalahan saat menyimpan.", "error");
  }
};
const addJalur = () =>
  tempJalur.value.push({
    id: Date.now(),
    name: "",
    kuota: "",
    icon: "PhMapPin",
    description: "",
  });
const removeJalur = (index) => tempJalur.value.splice(index, 1);

// FAQ Modal
const isFaqModalOpen = ref(false);
const tempFaq = ref([]);

const openFaqModal = () => {
  tempFaq.value = faqs.value.map((f) => ({ ...f }));
  isFaqModalOpen.value = true;
  document.body.style.overflow = "hidden";
};
const closeFaqModal = () => {
  isFaqModalOpen.value = false;
  document.body.style.overflow = "";
};
const saveFaq = async () => {
  try {
    const newData = tempFaq.value.filter((f) => f.q.trim() !== "" && f.a.trim() !== "");
    await api.put('/api/ppdb-info', { faqs: newData });
    faqs.value = newData;
    closeFaqModal();
    triggerToast("Berhasil Disimpan", "Data FAQ berhasil diperbarui.");
  } catch(error) {
    triggerToast("Gagal", "Terjadi kesalahan saat menyimpan.", "error");
  }
};
const addFaq = () => tempFaq.value.push({ id: Date.now(), q: "", a: "" });
const removeFaq = (index) => tempFaq.value.splice(index, 1);

// Upload Brosur
const handleFileUpload = async (event) => {
  const file = event.target.files[0];
  if (file) {
    if (file.type === "application/pdf") {
      try {
        const formData = new FormData();
        formData.append('brosur', file);
        const response = await api.post('/api/ppdb-info/brosur', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        
        fileBrosur.value = { name: file.name, url: response.data.url, isUploaded: true, size: file.size };
        triggerToast("Berhasil Diunggah", `File ${file.name} berhasil diunggah.`);
      } catch (error) {
        triggerToast("Gagal Diunggah", "Terjadi kesalahan saat mengunggah file.", "error");
      }
    } else {
      triggerToast("Gagal Diunggah", "Harap unggah file dengan format PDF.", "error");
    }
  }
};
const removeBrosur = async () => {
  try {
    await api.delete('/api/ppdb-info/brosur');
    fileBrosur.value = null;
    triggerToast("Berhasil Dihapus", "Brosur berhasil dihapus.", "info");
  } catch (error) {
    triggerToast("Gagal Dihapus", "Terjadi kesalahan saat menghapus brosur.", "error");
  }
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8 flex flex-col lg:flex-row justify-between lg:items-center gap-6">
      <div>
        <h2
          class="text-3xl font-bold text-gray-800 dark:text-white"
          style="font-family: 'Oswald', sans-serif"
        >
          Manajemen Info PPDB
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola informasi Syarat, Alur, dan Jalur Pendaftaran
        </p>
      </div>

      <!-- Waktu Pembukaan & Countdown -->
      <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 rounded-xl shadow-lg text-white shrink-0 min-w-[300px]">
        <div class="flex justify-between items-start mb-3">
          <h3 class="font-bold flex items-center text-sm uppercase tracking-wider text-blue-100">
            <PhCalendarBlank class="w-4 h-4 mr-2" /> Waktu Pembukaan PPDB
          </h3>
          <button @click="openOpeningDateModal" class="p-1 bg-white/20 hover:bg-white/30 rounded transition-colors text-white" title="Edit Waktu">
            <PhPencilSimple class="w-4 h-4" />
          </button>
        </div>
        
        <div v-if="openingDate" class="text-center">
          <p class="text-sm font-medium mb-2">{{ new Date(openingDate).toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' }) }}</p>
          <div class="flex justify-center gap-2 text-center mt-2">
            <div class="bg-black/20 rounded-lg p-2 w-16">
              <span class="block text-xl font-bold">{{ countdown.days }}</span>
              <span class="text-[10px] uppercase text-blue-200">Hari</span>
            </div>
            <div class="bg-black/20 rounded-lg p-2 w-16">
              <span class="block text-xl font-bold">{{ countdown.hours }}</span>
              <span class="text-[10px] uppercase text-blue-200">Jam</span>
            </div>
            <div class="bg-black/20 rounded-lg p-2 w-16">
              <span class="block text-xl font-bold">{{ countdown.minutes }}</span>
              <span class="text-[10px] uppercase text-blue-200">Menit</span>
            </div>
            <div class="bg-black/20 rounded-lg p-2 w-16">
              <span class="block text-xl font-bold">{{ countdown.seconds }}</span>
              <span class="text-[10px] uppercase text-blue-200">Detik</span>
            </div>
          </div>
        </div>
        <div v-else class="text-center py-2 text-blue-200 text-sm">
          Waktu pembukaan belum diatur.
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
      <!-- Syarat Pendaftaran -->
      <div
        class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex flex-col xl:col-span-6"
      >
        <div
          class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-slate-700 pb-3"
        >
          <h3
            class="text-xl font-semibold text-gray-800 dark:text-white flex items-center"
          >
            <PhListChecks class="w-6 h-6 mr-2 text-blue-600" />
            Syarat Pendaftaran
          </h3>
          <button
            @click="openSyaratModal"
            class="text-sm px-3 py-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors flex items-center font-medium"
          >
            <PhPencilSimple class="w-4 h-4 mr-1.5" /> Edit
          </button>
        </div>
        <ul class="space-y-4 flex-1">
          <li v-for="item in syarat" :key="item.id" class="flex items-start">
            <PhCheckCircle class="w-5 h-5 text-green-500 mr-3 shrink-0 mt-0.5" />
            <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{
              item.text
            }}</span>
          </li>
          <li v-if="syarat.length === 0" class="text-gray-500 text-sm italic">
            Belum ada syarat pendaftaran.
          </li>
        </ul>
      </div>

      <!-- Alur Pendaftaran -->
      <div
        class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex flex-col xl:col-span-6"
      >
        <div
          class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-slate-700 pb-3"
        >
          <h3
            class="text-xl font-semibold text-gray-800 dark:text-white flex items-center"
          >
            <PhGitMerge class="w-6 h-6 mr-2 text-blue-600" />
            Alur Pendaftaran
          </h3>
          <button
            @click="openAlurModal"
            class="text-sm px-3 py-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors flex items-center font-medium"
          >
            <PhPencilSimple class="w-4 h-4 mr-1.5" /> Edit
          </button>
        </div>
        <div
          class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-blue-200 before:via-blue-200 dark:before:via-slate-600 before:to-transparent"
        >
          <div
            v-for="item in alur"
            :key="item.id"
            class="relative flex items-center gap-4"
          >
            <div
              class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border-2 border-blue-500 flex items-center justify-center text-blue-600 font-bold shrink-0 z-10"
            >
              {{ item.step }}
            </div>
            <div>
              <h4 class="font-bold text-gray-800 dark:text-white text-sm">
                {{ item.title }}
              </h4>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                {{ item.description }}
              </p>
            </div>
          </div>
          <div v-if="alur.length === 0" class="text-gray-500 text-sm italic ml-12">
            Belum ada alur pendaftaran.
          </div>
        </div>
      </div>

      <!-- Jalur Pendaftaran -->
      <div
        class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 p-6 xl:col-span-12"
      >
        <div
          class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-slate-700 pb-3"
        >
          <h3
            class="text-xl font-semibold text-gray-800 dark:text-white flex items-center"
          >
            <PhSignpost class="w-6 h-6 mr-2 text-blue-600" />
            Jalur Pendaftaran
          </h3>
          <button
            @click="openJalurModal"
            class="text-sm px-3 py-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors flex items-center font-medium"
          >
            <PhPencilSimple class="w-4 h-4 mr-1.5" /> Edit
          </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="item in jalur"
            :key="item.id"
            class="bg-gray-50 dark:bg-slate-700/50 p-5 rounded-xl border border-gray-100 dark:border-slate-600 border-b-4 border-b-yellow-400"
          >
            <div class="flex justify-between items-start mb-4">
              <div
                class="w-10 h-10 bg-yellow-400 text-blue-950 rounded-xl flex items-center justify-center shadow-sm"
              >
                <component :is="iconMap[item.icon] || PhMapPin" class="w-5 h-5" />
              </div>
              <span
                class="px-2.5 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 text-xs font-bold rounded-md"
              >
                Kuota {{ item.kuota }}
              </span>
            </div>
            <h4 class="font-bold text-gray-800 dark:text-white mb-2">{{ item.name }}</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
              {{ item.description }}
            </p>
          </div>
        </div>
        <div
          v-if="jalur.length === 0"
          class="text-gray-500 text-sm italic text-center py-4"
        >
          Belum ada jalur pendaftaran.
        </div>
      </div>

      <!-- Brosur PPDB -->
      <div
        class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex flex-col xl:col-span-4"
      >
        <div
          class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-slate-700 pb-3"
        >
          <h3
            class="text-xl font-semibold text-gray-800 dark:text-white flex items-center"
          >
            <PhFilePdf class="w-6 h-6 mr-2 text-red-600" />
            Brosur PPDB
          </h3>
        </div>
        <div class="flex-1 flex flex-col justify-center">
          <div
            v-if="fileBrosur"
            class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-100 dark:border-blue-800/50 mb-4"
          >
            <div class="flex items-center overflow-hidden mr-3">
              <PhFilePdf class="w-8 h-8 text-red-500 mr-3 shrink-0" />
              <div class="overflow-hidden">
                <p
                  class="text-sm font-bold text-gray-800 dark:text-white truncate"
                  :title="fileBrosur.name"
                >
                  {{ fileBrosur.name }}
                </p>
                <p v-if="fileBrosur.size" class="text-xs text-gray-500 dark:text-gray-400">
                  {{ (fileBrosur.size / 1024 / 1024).toFixed(2) }} MB
                </p>
                <a v-if="fileBrosur.url" :href="fileBrosur.url" target="_blank" class="text-xs text-blue-600 hover:underline">
                  Lihat File
                </a>
              </div>
            </div>
            <button
              @click="removeBrosur"
              class="p-2 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-md transition-colors shrink-0"
              title="Hapus Brosur"
            >
              <PhTrash class="w-5 h-5" />
            </button>
          </div>
          <div
            v-else
            class="text-center p-6 border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700/30 mb-4"
          >
            <PhUploadSimple class="w-10 h-10 mx-auto text-gray-400 mb-2" />
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Belum ada brosur yang diunggah.
            </p>
          </div>
          <input
            type="file"
            ref="brosurInput"
            accept="application/pdf"
            class="hidden"
            @change="handleFileUpload"
          />
          <button
            @click="$refs.brosurInput.click()"
            class="w-full mt-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition-colors"
          >
            {{ fileBrosur ? "Ganti Brosur (PDF)" : "Unggah Brosur (PDF)" }}
          </button>
        </div>
      </div>

      <!-- Pertanyaan Seputar PPDB (FAQ) -->
      <div
        class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex flex-col xl:col-span-8"
      >
        <div
          class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-slate-700 pb-3"
        >
          <h3
            class="text-xl font-semibold text-gray-800 dark:text-white flex items-center"
          >
            <PhQuestion class="w-6 h-6 mr-2 text-blue-600" />
            Pertanyaan Seputar PPDB (FAQ)
          </h3>
          <button
            @click="openFaqModal"
            class="text-sm px-3 py-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors flex items-center font-medium"
          >
            <PhPencilSimple class="w-4 h-4 mr-1.5" /> Edit
          </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div
            v-for="item in faqs"
            :key="item.id"
            class="p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-100 dark:border-slate-600"
          >
            <h4 class="font-bold text-gray-800 dark:text-white mb-2">{{ item.q }}</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
              {{ item.a }}
            </p>
          </div>
          <div
            v-if="faqs.length === 0"
            class="md:col-span-2 text-gray-500 text-sm italic text-center py-4"
          >
            Belum ada data FAQ.
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Syarat Pendaftaran -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isSyaratModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closeSyaratModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
              Edit Syarat Pendaftaran
            </h3>
            <button
              @click="closeSyaratModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <div class="flex justify-between items-center mb-4">
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Atur syarat-syarat pendaftaran siswa baru.
              </p>
              <button
                @click="addSyarat"
                type="button"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
              >
                <PhPlusCircle class="w-4 h-4 mr-1.5" /> Tambah Syarat
              </button>
            </div>
            <div class="space-y-3">
              <div
                v-for="(item, index) in tempSyarat"
                :key="item.id"
                class="flex gap-2 items-start bg-gray-50 dark:bg-slate-700/30 p-2 rounded-lg border border-gray-100 dark:border-slate-600"
                draggable="true"
                @dragstart="handleDragStart(index, $event)"
                @dragover.prevent
                @drop="handleDrop(index, tempSyarat)"
              >
                <div
                  class="mt-2 text-gray-400 cursor-grab active:cursor-grabbing hover:text-gray-600 dark:hover:text-gray-300 shrink-0"
                  title="Geser untuk memindahkan"
                >
                  <PhDotsSixVertical class="w-5 h-5" />
                </div>
                <textarea
                  v-model="item.text"
                  rows="2"
                  class="flex-1 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-sm focus:ring-blue-500 focus:border-blue-500 dark:text-white resize-none"
                  placeholder="Masukkan syarat pendaftaran..."
                ></textarea>
                <button
                  @click="removeSyarat(index)"
                  class="mt-1 p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors shrink-0"
                  title="Hapus"
                >
                  <PhTrash class="w-4 h-4" />
                </button>
              </div>
              <div
                v-if="tempSyarat.length === 0"
                class="text-center py-6 text-sm text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-lg"
              >
                Belum ada data. Silakan klik "Tambah Syarat".
              </div>
            </div>
          </div>
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              @click="closeSyaratModal"
              class="px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600"
            >
              Batal
            </button>
            <button
              @click="saveSyarat"
              class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
            >
              <PhFloppyDisk class="w-5 h-5 mr-2" /> Simpan Perubahan
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Modal Alur Pendaftaran -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isAlurModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closeAlurModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
              Edit Alur Pendaftaran
            </h3>
            <button
              @click="closeAlurModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <div class="flex justify-between items-center mb-4">
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Atur tahapan proses pendaftaran. Urutan akan menjadi nomor langkah (step).
              </p>
              <button
                @click="addAlur"
                type="button"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
              >
                <PhPlusCircle class="w-4 h-4 mr-1.5" /> Tambah Alur
              </button>
            </div>
            <div class="space-y-3">
              <div
                v-for="(item, index) in tempAlur"
                :key="item.id"
                class="flex gap-3 items-start bg-gray-50 dark:bg-slate-700/30 p-3 rounded-lg border border-gray-100 dark:border-slate-600"
                draggable="true"
                @dragstart="handleDragStart(index, $event)"
                @dragover.prevent
                @drop="handleDrop(index, tempAlur)"
              >
                <div
                  class="mt-3 text-gray-400 cursor-grab active:cursor-grabbing hover:text-gray-600 dark:hover:text-gray-300 shrink-0"
                  title="Geser untuk mengubah urutan"
                >
                  <PhDotsSixVertical class="w-5 h-5" />
                </div>
                <div
                  class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm shrink-0 mt-2"
                >
                  {{ index + 1 }}
                </div>
                <div class="flex-1 space-y-2">
                  <input
                    v-model="item.title"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-sm focus:ring-blue-500 focus:border-blue-500 dark:text-white font-medium"
                    placeholder="Judul Langkah (contoh: Isi Formulir Online)"
                  />
                  <textarea
                    v-model="item.description"
                    rows="2"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-sm focus:ring-blue-500 focus:border-blue-500 dark:text-white resize-none"
                    placeholder="Deskripsi penjelasan langkah..."
                  ></textarea>
                </div>
                <button
                  @click="removeAlur(index)"
                  class="mt-2 p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors shrink-0"
                  title="Hapus"
                >
                  <PhTrash class="w-5 h-5" />
                </button>
              </div>
              <div
                v-if="tempAlur.length === 0"
                class="text-center py-6 text-sm text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-lg"
              >
                Belum ada data alur.
              </div>
            </div>
          </div>
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              @click="closeAlurModal"
              class="px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600"
            >
              Batal
            </button>
            <button
              @click="saveAlur"
              class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
            >
              <PhFloppyDisk class="w-5 h-5 mr-2" /> Simpan Perubahan
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Modal Jalur Pendaftaran -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isJalurModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closeJalurModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
              Edit Jalur Pendaftaran
            </h3>
            <button
              @click="closeJalurModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <div class="flex justify-between items-center mb-4">
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Atur jalur penerimaan siswa baru, termasuk nama jalur, kuota, ikon, dan
                deskripsinya.
              </p>
              <button
                @click="addJalur"
                type="button"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400 rounded-md hover:bg-blue-100 dark:bg-blue-900/50 transition-colors"
              >
                <PhPlusCircle class="w-4 h-4 mr-1.5" /> Tambah Jalur
              </button>
            </div>
            <div class="space-y-4">
              <div
                v-for="(item, index) in tempJalur"
                :key="item.id"
                class="flex gap-3 items-start bg-gray-50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-100 dark:border-slate-600"
                draggable="true"
                @dragstart="handleDragStart(index, $event)"
                @dragover.prevent
                @drop="handleDrop(index, tempJalur)"
              >
                <div
                  class="mt-4 text-gray-400 cursor-grab active:cursor-grabbing hover:text-gray-600 dark:hover:text-gray-300 shrink-0"
                  title="Geser untuk memindahkan"
                >
                  <PhDotsSixVertical class="w-6 h-6" />
                </div>
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div class="sm:col-span-2">
                    <label
                      class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Nama Jalur</label
                    >
                    <input
                      v-model="item.name"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-sm focus:ring-blue-500 focus:border-blue-500 dark:text-white font-semibold"
                      placeholder="Contoh: Jalur Zonasi"
                    />
                  </div>
                  <div>
                    <label
                      class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Kuota (%)</label
                    >
                    <input
                      v-model="item.kuota"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-sm focus:ring-blue-500 focus:border-blue-500 dark:text-white"
                      placeholder="Contoh: 50%"
                    />
                  </div>
                  <div>
                    <label
                      class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Ikon Representasi</label
                    >
                    <select
                      v-model="item.icon"
                      class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-sm focus:ring-blue-500 focus:border-blue-500 dark:text-white"
                    >
                      <option value="PhMapPin">Lokasi/Zonasi (Map Pin)</option>
                      <option value="PhMedal">Prestasi (Medal)</option>
                      <option value="PhHandshake">Kerjasama/Afirmasi (Handshake)</option>
                      <option value="PhBriefcase">Tugas/Kerja (Briefcase)</option>
                      <option value="PhBuildings">Gedung/Sekolah (Buildings)</option>
                      <option value="PhUsers">Komunitas/Orang (Users)</option>
                    </select>
                  </div>
                  <div class="sm:col-span-2">
                    <label
                      class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"
                      >Deskripsi Jalur</label
                    >
                    <textarea
                      v-model="item.description"
                      rows="2"
                      class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-sm focus:ring-blue-500 focus:border-blue-500 dark:text-white resize-none"
                      placeholder="Masukkan penjelasan mengenai jalur pendaftaran ini..."
                    ></textarea>
                  </div>
                </div>
                <button
                  @click="removeJalur(index)"
                  class="mt-4 p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors shrink-0"
                  title="Hapus Jalur"
                >
                  <PhTrash class="w-5 h-5" />
                </button>
              </div>
              <div
                v-if="tempJalur.length === 0"
                class="text-center py-6 text-sm text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-lg"
              >
                Belum ada data. Silakan klik "Tambah Jalur".
              </div>
            </div>
          </div>
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              @click="closeJalurModal"
              class="px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600"
            >
              Batal
            </button>
            <button
              @click="saveJalur"
              class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
            >
              <PhFloppyDisk class="w-5 h-5 mr-2" /> Simpan Perubahan
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Modal FAQ Pendaftaran -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isFaqModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closeFaqModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
              Edit Pertanyaan Seputar PPDB
            </h3>
            <button
              @click="closeFaqModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <div class="flex justify-between items-center mb-4">
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Atur pertanyaan dan jawaban yang sering ditanyakan seputar PPDB.
              </p>
              <button
                @click="addFaq"
                type="button"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400 rounded-md hover:bg-blue-100 dark:bg-blue-900/50 transition-colors"
              >
                <PhPlusCircle class="w-4 h-4 mr-1.5" /> Tambah FAQ
              </button>
            </div>
            <div class="space-y-4">
              <div
                v-for="(item, index) in tempFaq"
                :key="item.id"
                class="flex gap-3 items-start bg-gray-50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-100 dark:border-slate-600"
                draggable="true"
                @dragstart="handleDragStart(index, $event)"
                @dragover.prevent
                @drop="handleDrop(index, tempFaq)"
              >
                <div
                  class="mt-2 text-gray-400 cursor-grab active:cursor-grabbing hover:text-gray-600 dark:hover:text-gray-300 shrink-0"
                  title="Geser untuk memindahkan"
                >
                  <PhDotsSixVertical class="w-6 h-6" />
                </div>
                <div class="flex-1 space-y-3">
                  <input
                    v-model="item.q"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-sm focus:ring-blue-500 focus:border-blue-500 dark:text-white font-semibold"
                    placeholder="Masukkan pertanyaan..."
                  />
                  <textarea
                    v-model="item.a"
                    rows="2"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-sm focus:ring-blue-500 focus:border-blue-500 dark:text-white resize-none"
                    placeholder="Masukkan jawaban..."
                  ></textarea>
                </div>
                <button
                  @click="removeFaq(index)"
                  class="mt-1 p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors shrink-0"
                  title="Hapus FAQ"
                >
                  <PhTrash class="w-5 h-5" />
                </button>
              </div>
              <div
                v-if="tempFaq.length === 0"
                class="text-center py-6 text-sm text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-lg"
              >
                Belum ada data FAQ. Silakan klik "Tambah FAQ".
              </div>
            </div>
          </div>
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              @click="closeFaqModal"
              class="px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600"
            >
              Batal
            </button>
            <button
              @click="saveFaq"
              class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
            >
              <PhFloppyDisk class="w-5 h-5 mr-2" /> Simpan Perubahan
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Modal Waktu Pembukaan -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isOpeningDateModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closeOpeningDateModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
              Edit Waktu Pembukaan PPDB
            </h3>
            <button
              @click="closeOpeningDateModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal & Waktu</label>
            <input 
              type="datetime-local" 
              v-model="tempOpeningDate"
              class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
            />
            <p class="text-xs text-gray-500 mt-2">Countdown akan dihitung mundur menuju waktu ini.</p>
          </div>
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              @click="closeOpeningDateModal"
              class="px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600"
            >
              Batal
            </button>
            <button
              @click="saveOpeningDate"
              class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
            >
              <PhFloppyDisk class="w-5 h-5 mr-2" /> Simpan
            </button>
          </div>
        </div>
      </div>
    </Transition>

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
