<script setup>
import { ref } from "vue";
import { PhFloppyDisk } from "@phosphor-icons/vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const form = ref({
  gelombang: "Gelombang 1",
  tanggalBuka: "2026-05-01",
  tanggalTutup: "2026-06-30",
  pengumuman: "2026-07-05",
  status: "aktif",
  deskripsi:
    "Penerimaan Peserta Didik Baru tahun ajaran 2026/2027 telah dibuka untuk Gelombang 1. Silakan lengkapi persyaratan yang dibutuhkan.",
  persyaratan:
    "1. Fotokopi Ijazah/SKL\n2. Fotokopi Kartu Keluarga\n3. Akta Kelahiran\n4. Pas Foto 3x4 (4 lembar)",
});

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

const saveEntry = () => {
  triggerToast("Perubahan Disimpan", "Data Informasi PPDB berhasil diperbarui.");
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
          Manajemen Info PPDB
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola informasi Penerimaan Peserta Didik Baru yang ditampilkan ke publik.
        </p>
      </div>
      <button
        @click="saveEntry"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhFloppyDisk class="w-5 h-5 mr-2" />
        Simpan Perubahan
      </button>
    </div>

    <div
      class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-6"
    >
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Gelombang Pendaftaran
          </label>
          <input
            type="text"
            v-model="form.gelombang"
            class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Status Pendaftaran
          </label>
          <select
            v-model="form.status"
            class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="aktif">Buka / Aktif</option>
            <option value="tutup">Tutup</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Tanggal Buka
          </label>
          <input
            type="date"
            v-model="form.tanggalBuka"
            class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Tanggal Tutup
          </label>
          <input
            type="date"
            v-model="form.tanggalTutup"
            class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Tanggal Pengumuman Hasil
          </label>
          <input
            type="date"
            v-model="form.pengumuman"
            class="w-full md:w-1/2 px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Deskripsi Pengumuman Singkat
          </label>
          <textarea
            v-model="form.deskripsi"
            rows="3"
            class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
          ></textarea>
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Persyaratan Pendaftaran
          </label>
          <textarea
            v-model="form.persyaratan"
            rows="6"
            class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
          ></textarea>
        </div>
      </div>
    </div>

    <ToastNotification
      :isOpen="showToast"
      :title="toastData.title"
      :message="toastData.message"
      :type="toastData.type"
      @close="showToast = false"
    />
  </main>
</template>
