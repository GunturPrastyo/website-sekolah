<script setup>
import { ref, computed } from "vue";
import {
  PhMagnifyingGlass,
  PhEye,
  PhTrash,
  PhCheckCircle,
  PhXCircle,
  PhClipboardText,
  PhDownloadSimple,
  PhX,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

// Dummy Data Pendaftar
const registrants = ref([
  {
    id: "PPDB-84729",
    nisn: "0051234567",
    namaLengkap: "Budi Santoso",
    tempatLahir: "Boyolali",
    tanggalLahir: "2010-05-14",
    jenisKelamin: "Laki-laki",
    agama: "Islam",
    namaAyah: "Parno",
    pekerjaanAyah: "Wiraswasta",
    namaIbu: "Siti Aminah",
    pekerjaanIbu: "Ibu Rumah Tangga",
    noTelp: "081234567890",
    alamat: "Jl. Merdeka No. 45, Nogosari, Boyolali",
    asalSekolah: "SMPN 1 Nogosari",
    tahunLulus: "2026",
    jalur: "Zonasi",
    jurusan: "IPA",
    status: "Menunggu",
    tanggal: "12 Mei 2026",
  },
  {
    id: "PPDB-12934",
    nisn: "0069876543",
    namaLengkap: "Siti Rahmawati",
    tempatLahir: "Surakarta",
    tanggalLahir: "2011-02-20",
    jenisKelamin: "Perempuan",
    agama: "Islam",
    namaAyah: "Joko Supriyanto",
    pekerjaanAyah: "PNS",
    namaIbu: "Ratna Sari",
    pekerjaanIbu: "Guru",
    noTelp: "085678901234",
    alamat: "Perumahan Indah Asri Blok C2, Boyolali",
    asalSekolah: "SMPN 2 Boyolali",
    tahunLulus: "2026",
    jalur: "Prestasi",
    jurusan: "IPA",
    status: "Diterima",
    tanggal: "13 Mei 2026",
  },
  {
    id: "PPDB-55612",
    nisn: "0053334445",
    namaLengkap: "Kevin Pratama",
    tempatLahir: "Semarang",
    tanggalLahir: "2010-11-05",
    jenisKelamin: "Laki-laki",
    agama: "Kristen",
    namaAyah: "Hendrik",
    pekerjaanAyah: "Pegawai Swasta",
    namaIbu: "Maria",
    pekerjaanIbu: "Pegawai Swasta",
    noTelp: "081345678912",
    alamat: "Jl. Pemuda No. 12, Boyolali",
    asalSekolah: "SMP Pelita Harapan",
    tahunLulus: "2026",
    jalur: "Zonasi",
    jurusan: "IPS",
    status: "Ditolak",
    tanggal: "14 Mei 2026",
  },
]);

const searchQuery = ref("");
const filterStatus = ref("semua");
const filterJalur = ref("semua");

const isDetailModalOpen = ref(false);
const selectedRegistrant = ref(null);

const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

// Melihat detail
const viewDetails = (registrant) => {
  selectedRegistrant.value = registrant;
  isDetailModalOpen.value = true;
};

const closeDetailModal = () => {
  isDetailModalOpen.value = false;
  setTimeout(() => {
    selectedRegistrant.value = null;
  }, 300);
};

// Update Status Pendaftaran
const updateStatus = (id, newStatus) => {
  const index = registrants.value.findIndex((r) => r.id === id);
  if (index !== -1) {
    registrants.value[index].status = newStatus;
    triggerToast(
      "Status Diperbarui",
      `Status pendaftar berhasil diubah menjadi ${newStatus}.`,
      newStatus === "Diterima" ? "success" : newStatus === "Ditolak" ? "error" : "info"
    );
    if (isDetailModalOpen.value) {
      selectedRegistrant.value.status = newStatus; // Update state di modal aktif
    }
  }
};

// Hapus Pendaftar
const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
  if (itemToDelete.value !== null) {
    registrants.value = registrants.value.filter((r) => r.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast("Data Dihapus", "Data pendaftar berhasil dihapus dari sistem.", "info");
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

// Computed Properties untuk Tabel
const filteredRegistrants = computed(() => {
  return registrants.value.filter((r) => {
    const matchSearch =
      r.namaLengkap.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      r.nisn.includes(searchQuery.value) ||
      r.id.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchStatus = filterStatus.value === "semua" || r.status === filterStatus.value;
    const matchJalur = filterJalur.value === "semua" || r.jalur === filterJalur.value;

    return matchSearch && matchStatus && matchJalur;
  });
});

const getStatusClass = (status) => {
  switch (status) {
    case "Diterima":
      return "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400";
    case "Ditolak":
      return "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400";
    case "Menunggu":
    default:
      return "bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400";
  }
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
          Manajemen PPDB
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data pendaftaran peserta didik baru, validasi, dan penentuan status.
        </p>
      </div>
      <button
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
      >
        <PhDownloadSimple class="w-5 h-5 mr-2" />
        Export Data (Excel)
      </button>
    </div>

    <!-- Data Table & Filters -->
    <div
      class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden"
    >
      <div
        class="p-6 border-b border-gray-100 dark:border-slate-700 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4"
      >
        <div class="relative w-full lg:w-96 shrink-0">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
          </div>
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Cari Nama, NISN, atau No Registrasi..."
            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
          <select
            v-model="filterJalur"
            class="block w-full sm:w-48 px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Jalur</option>
            <option value="Zonasi">Zonasi</option>
            <option value="Prestasi">Prestasi</option>
            <option value="Afirmasi">Afirmasi</option>
            <option value="Pindah Tugas">Pindah Tugas</option>
          </select>

          <select
            v-model="filterStatus"
            class="block w-full sm:w-48 px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Status</option>
            <option value="Menunggu">Menunggu Verifikasi</option>
            <option value="Diterima">Diterima</option>
            <option value="Ditolak">Ditolak</option>
          </select>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr
              class="bg-gray-50 dark:bg-slate-700/50 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              <th class="px-6 py-4 whitespace-nowrap">No Registrasi</th>
              <th class="px-6 py-4">Data Calon Siswa</th>
              <th class="px-6 py-4 hidden md:table-cell">Asal Sekolah</th>
              <th class="px-6 py-4 hidden lg:table-cell">Jalur & Jurusan</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            <tr v-if="filteredRegistrants.length === 0">
              <td
                colspan="6"
                class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
              >
                <PhClipboardText class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-500 mb-3" />
                <p>Tidak ada data pendaftar yang cocok.</p>
              </td>
            </tr>
            <tr
              v-for="reg in filteredRegistrants"
              :key="reg.id"
              class="hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors group"
            >
              <td class="px-6 py-4 font-mono text-sm font-semibold text-blue-600 dark:text-blue-400">
                {{ reg.id }}
              </td>
              <td class="px-6 py-4">
                <span class="block text-sm font-bold text-gray-900 dark:text-gray-100">
                  {{ reg.namaLengkap }}
                </span>
                <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                  NISN: {{ reg.nisn }}
                </span>
              </td>
              <td class="px-6 py-4 hidden md:table-cell text-sm text-gray-600 dark:text-gray-300">
                {{ reg.asalSekolah }}
              </td>
              <td class="px-6 py-4 hidden lg:table-cell">
                <span class="block text-sm text-gray-800 dark:text-gray-200 font-medium">
                  {{ reg.jalur }}
                </span>
                <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5 uppercase">
                  {{ reg.jurusan }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                  :class="getStatusClass(reg.status)"
                >
                  {{ reg.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-1">
                  <button
                    @click="viewDetails(reg)"
                    class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors"
                    title="Lihat Detail"
                  >
                    <PhEye class="w-5 h-5" />
                  </button>
                  <button
                    @click="deleteEntry(reg.id)"
                    class="p-1.5 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-md transition-colors"
                    title="Hapus Data"
                  >
                    <PhTrash class="w-5 h-5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Detail Pendaftar -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isDetailModalOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
        @click="closeDetailModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all"
          @click.stop
        >
          <!-- Modal Header -->
          <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-800/80">
            <div>
              <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
                Detail Pendaftar
                <span
                  class="ml-3 px-2.5 py-0.5 rounded-full text-xs font-semibold"
                  :class="getStatusClass(selectedRegistrant?.status)"
                >
                  {{ selectedRegistrant?.status }}
                </span>
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-mono">
                No. Reg: {{ selectedRegistrant?.id }} | Terdaftar: {{ selectedRegistrant?.tanggal }}
              </p>
            </div>
            <button
              @click="closeDetailModal"
              class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors bg-white dark:bg-slate-700 rounded-full p-2 shadow-sm border border-gray-200 dark:border-slate-600"
            >
              <PhX class="w-5 h-5" />
            </button>
          </div>

          <!-- Modal Content -->
          <div class="p-6 overflow-y-auto flex-1 space-y-8">
            <!-- Seksi Data Diri -->
            <div>
              <h4 class="text-sm font-bold text-blue-600 dark:text-blue-400 mb-4 uppercase tracking-wider border-b border-gray-100 dark:border-slate-700 pb-2">
                Data Diri Siswa
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <span class="block text-xs text-gray-500 dark:text-gray-400">Nama Lengkap</span>
                  <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ selectedRegistrant?.namaLengkap }}</span>
                </div>
                <div>
                  <span class="block text-xs text-gray-500 dark:text-gray-400">NISN</span>
                  <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ selectedRegistrant?.nisn }}</span>
                </div>
                <div>
                  <span class="block text-xs text-gray-500 dark:text-gray-400">Tempat, Tanggal Lahir</span>
                  <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ selectedRegistrant?.tempatLahir }}, {{ selectedRegistrant?.tanggalLahir }}</span>
                </div>
                <div>
                  <span class="block text-xs text-gray-500 dark:text-gray-400">Jenis Kelamin / Agama</span>
                  <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ selectedRegistrant?.jenisKelamin }} / {{ selectedRegistrant?.agama }}</span>
                </div>
              </div>
            </div>

            <!-- Seksi Data Orang Tua -->
            <div>
              <h4 class="text-sm font-bold text-blue-600 dark:text-blue-400 mb-4 uppercase tracking-wider border-b border-gray-100 dark:border-slate-700 pb-2">
                Data Orang Tua & Kontak
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <span class="block text-xs text-gray-500 dark:text-gray-400">Nama Ayah (Pekerjaan)</span>
                  <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ selectedRegistrant?.namaAyah }} ({{ selectedRegistrant?.pekerjaanAyah }})</span>
                </div>
                <div>
                  <span class="block text-xs text-gray-500 dark:text-gray-400">Nama Ibu (Pekerjaan)</span>
                  <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ selectedRegistrant?.namaIbu }} ({{ selectedRegistrant?.pekerjaanIbu }})</span>
                </div>
                <div>
                  <span class="block text-xs text-gray-500 dark:text-gray-400">No. Telp / WA</span>
                  <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ selectedRegistrant?.noTelp }}</span>
                </div>
                <div class="md:col-span-2">
                  <span class="block text-xs text-gray-500 dark:text-gray-400">Alamat Lengkap</span>
                  <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ selectedRegistrant?.alamat }}</span>
                </div>
              </div>
            </div>

            <!-- Seksi Akademik -->
            <div>
              <h4 class="text-sm font-bold text-blue-600 dark:text-blue-400 mb-4 uppercase tracking-wider border-b border-gray-100 dark:border-slate-700 pb-2">
                Asal Sekolah & Pilihan
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <span class="block text-xs text-gray-500 dark:text-gray-400">Asal Sekolah (SMP/MTs)</span>
                  <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ selectedRegistrant?.asalSekolah }} (Lulus: {{ selectedRegistrant?.tahunLulus }})</span>
                </div>
                <div>
                  <span class="block text-xs text-gray-500 dark:text-gray-400">Jalur Pendaftaran</span>
                  <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ selectedRegistrant?.jalur }}</span>
                </div>
                <div>
                  <span class="block text-xs text-gray-500 dark:text-gray-400">Pilihan Peminatan/Jurusan</span>
                  <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ selectedRegistrant?.jurusan }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Footer (Aksi) -->
          <div class="p-5 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/80 flex flex-wrap gap-3 justify-end items-center">
            <div v-if="selectedRegistrant?.status === 'Menunggu'" class="flex gap-3 w-full sm:w-auto">
              <button
                @click="updateStatus(selectedRegistrant.id, 'Ditolak')"
                class="flex-1 sm:flex-none justify-center px-4 py-2 bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 font-semibold rounded-lg transition-colors flex items-center"
              >
                <PhXCircle class="w-5 h-5 mr-2" /> Tolak
              </button>
              <button
                @click="updateStatus(selectedRegistrant.id, 'Diterima')"
                class="flex-1 sm:flex-none justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors flex items-center"
              >
                <PhCheckCircle class="w-5 h-5 mr-2" /> Terima Pendaftar
              </button>
            </div>
            <div v-else class="flex gap-3 w-full sm:w-auto">
               <button
                @click="updateStatus(selectedRegistrant.id, 'Menunggu')"
                class="flex-1 sm:flex-none justify-center px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-slate-700 dark:text-gray-300 dark:hover:bg-slate-600 font-semibold rounded-lg transition-colors flex items-center"
              >
                Kembalikan ke Menunggu
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Data Pendaftar"
      message="Yakin ingin menghapus data pendaftar ini secara permanen? Aksi ini tidak dapat dibatalkan."
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