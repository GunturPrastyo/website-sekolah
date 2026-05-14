<script setup>
import { ref } from "vue";
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
} from "@phosphor-icons/vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

// State Data
const syarat = ref([
  { id: 1, text: "Lulusan SMP/MTs sederajat tahun 2024, 2025, atau 2026." },
  { id: 2, text: "Memiliki Nomor Induk Siswa Nasional (NISN) yang valid." },
  { id: 3, text: "Menyiapkan berkas digital (Scan KK, Akta Kelahiran, dan Ijazah/SKL)." },
  { id: 4, text: "Pas foto terbaru ukuran 3x4 berwarna (pakaian seragam asal)." },
]);

const alur = ref([
  {
    id: 1,
    step: 1,
    title: "Isi Formulir Online",
    description: "Lengkapi data diri, asal sekolah, dan jurusan di bawah ini.",
  },
  {
    id: 2,
    step: 2,
    title: "Cetak Bukti Daftar",
    description: "Setelah submit, simpan dan cetak bukti pendaftaran otomatis.",
  },
  {
    id: 3,
    step: 3,
    title: "Verifikasi Berkas",
    description: "Bawa dokumen fisik ke sekolah sesuai jadwal yang tertera.",
  },
  {
    id: 4,
    step: 4,
    title: "Pengumuman",
    description: "Hasil kelulusan akan diinformasikan di portal resmi.",
  },
]);

const jalur = ref([
  {
    id: 1,
    name: "Jalur Zonasi",
    kuota: "50%",
    icon: "PhMapPin",
    description:
      "Diperuntukkan bagi calon peserta didik yang berdomisili di dalam wilayah zonasi yang telah ditetapkan berdasarkan jarak titik koordinat terdekat dari sekolah.",
  },
  {
    id: 2,
    name: "Jalur Prestasi",
    kuota: "30%",
    icon: "PhMedal",
    description:
      "Penerimaan berdasarkan akumulasi nilai rapor semester 1-5 atau sertifikat prestasi kejuaraan akademik maupun non-akademik.",
  },
  {
    id: 3,
    name: "Jalur Afirmasi",
    kuota: "15%",
    icon: "PhHandshake",
    description:
      "Khusus ditujukan bagi calon peserta didik dari keluarga ekonomi tidak mampu (dibuktikan dengan KIP/PKH) dan penyandang disabilitas.",
  },
  {
    id: 4,
    name: "Pindah Tugas",
    kuota: "5%",
    icon: "PhBriefcase",
    description:
      "Diperuntukkan bagi peserta didik yang mengikuti kepindahan tugas orang tua/wali dari instansi, atau anak kandung dari guru.",
  },
]);

const iconMap = {
  PhMapPin,
  PhMedal,
  PhHandshake,
  PhBriefcase,
  PhBuildings,
  PhUsers,
};

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
const saveSyarat = () => {
  syarat.value = tempSyarat.value.filter((s) => s.text.trim() !== "");
  closeSyaratModal();
  triggerToast("Berhasil Disimpan", "Data Syarat Pendaftaran berhasil diperbarui.");
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
const saveAlur = () => {
  alur.value = tempAlur.value.map((a, index) => ({ ...a, step: index + 1 }));
  closeAlurModal();
  triggerToast("Berhasil Disimpan", "Data Alur Pendaftaran berhasil diperbarui.");
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
const saveJalur = () => {
  jalur.value = tempJalur.value.filter((j) => j.name.trim() !== "");
  closeJalurModal();
  triggerToast("Berhasil Disimpan", "Data Jalur Pendaftaran berhasil diperbarui.");
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
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8">
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

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
      <!-- Syarat Pendaftaran -->
      <div
        class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex flex-col"
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
        class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex flex-col"
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
        class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 p-6 xl:col-span-2"
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
