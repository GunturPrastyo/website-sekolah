<script setup>
import { ref, computed, onMounted } from "vue";
import api from "@/api/index.js";
import {
  PhGear,
  PhImage,
  PhFloppyDisk,
  PhHouse,
  PhBuildings,
  PhGraduationCap,
  PhMegaphone,
} from "@phosphor-icons/vue";
import ImageUploader from "@/components/admin/ImageUploader.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const activeTab = ref("umum");

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

// State Umum
const generalSettings = ref({
  namaSekolah: "",
  deskripsi: "",
  alamat: "",
  embedMap: "",
  email: "",
  telepon: "",
  instagram: "",
  youtube: "",
  facebook: "",
  x: "",
  tiktok: "",
  logo: "",
  favicon: "",
});

// State Tampilan
const appearanceSettings = ref({
  headerBeranda: "",
  headerSejarah: "",
  headerVisiMisi: "",
  headerFasilitas: "",
  headerGuruStaf: "",
  headerEkskul: "",
  headerKurikulum: "",
  headerAlumni: "",
  headerProgramJurusan: "",
  headerPrestasi: "",
  headerPendaftaran: "",
  headerBerita: "",
  headerGaleri: "",
  headerArtikel: "",
  headerUnduhan: "",
});

const isMapUrlValid = computed(() => {
  const url = generalSettings.value.embedMap;
  if (!url) return true;
  return url.startsWith("https://www.google.com/maps/embed");
});

const fetchSettings = async () => {
  try {
    const response = await api.get("/api/settings");
    const data = response.data.data;

    if (data) {
      // Memperbarui State General Settings
      Object.keys(generalSettings.value).forEach((key) => {
        if (data[key] !== undefined) {
          generalSettings.value[key] = data[key];
        }
      });

      // Memperbarui State Appearance Settings
      Object.keys(appearanceSettings.value).forEach((key) => {
        if (data[key] !== undefined) {
          appearanceSettings.value[key] = data[key];
        }
      });
    }
  } catch (error) {
    console.error("Gagal mengambil pengaturan:", error);
    triggerToast("Gagal Memuat", "Gagal memuat pengaturan dari server", "error");
  }
};

onMounted(() => {
  fetchSettings();
});

const saveSettings = async () => {
  if (!isMapUrlValid.value) {
    triggerToast(
      "Gagal Menyimpan",
      "Link Embed Map tidak valid. Pastikan menggunakan URL dari iframe Google Maps.",
      "error"
    );
    return;
  }

  try {
    // Menggabungkan kedua state menjadi 1 object
    const payload = { ...generalSettings.value, ...appearanceSettings.value };
    await api.post("/api/settings", payload);
    triggerToast("Tersimpan", "Pengaturan berhasil disimpan", "success");
  } catch (error) {
    console.error("Gagal menyimpan pengaturan:", error);
    triggerToast(
      "Gagal Menyimpan",
      "Terjadi kesalahan saat menyimpan pengaturan",
      "error"
    );
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
          Pengaturan Sistem
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola konfigurasi umum dan tampilan website.
        </p>
      </div>
      <button
        @click="saveSettings"
        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors shrink-0"
      >
        <PhFloppyDisk class="w-5 h-5 mr-2" />
        Simpan Perubahan
      </button>
    </div>

    <!-- Tabs -->
    <div
      class="bg-white dark:bg-slate-800 rounded-t-xl border-b border-gray-200 dark:border-slate-700 flex overflow-x-auto custom-scrollbar"
    >
      <button
        @click="activeTab = 'umum'"
        class="px-6 py-4 text-sm font-medium transition-colors flex items-center gap-2 whitespace-nowrap border-b-2"
        :class="
          activeTab === 'umum'
            ? 'text-blue-600 border-blue-600 dark:text-blue-400 dark:border-blue-400 bg-blue-50/50 dark:bg-slate-700/50'
            : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-slate-700'
        "
      >
        <PhGear class="w-5 h-5" /> Pengaturan Umum
      </button>
      <button
        @click="activeTab = 'tampilan'"
        class="px-6 py-4 text-sm font-medium transition-colors flex items-center gap-2 whitespace-nowrap border-b-2"
        :class="
          activeTab === 'tampilan'
            ? 'text-blue-600 border-blue-600 dark:text-blue-400 dark:border-blue-400 bg-blue-50/50 dark:bg-slate-700/50'
            : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-slate-700'
        "
      >
        <PhImage class="w-5 h-5" /> Tampilan (Header)
      </button>
    </div>

    <div
      class="bg-white dark:bg-slate-800 rounded-b-xl border border-t-0 border-gray-200 dark:border-slate-700 shadow-sm p-6 mb-8"
    >
      <!-- Tab Umum -->
      <div v-show="activeTab === 'umum'" class="space-y-8 animate-fade-in">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div class="lg:col-span-2 space-y-4">
            <h3
              class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-100 dark:border-slate-700 pb-2"
            >
              Informasi Sekolah
            </h3>
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >Nama Sekolah</label
              >
              <input
                type="text"
                v-model="generalSettings.namaSekolah"
                class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
              />
            </div>
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >Alamat</label
              >
              <textarea
                v-model="generalSettings.alamat"
                rows="2"
                class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
              ></textarea>
            </div>
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >Link Embed Map (Lokasi)</label
              >
              <textarea
                v-model="generalSettings.embedMap"
                rows="3"
                class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all mb-1"
                :class="
                  !isMapUrlValid && generalSettings.embedMap
                    ? 'border-red-500 ring-2 ring-red-500 focus:ring-red-500 focus:border-red-500'
                    : ''
                "
                placeholder="Masukkan URL dari atribut src iframe Google Maps..."
              ></textarea>
              <p
                v-if="!isMapUrlValid && generalSettings.embedMap"
                class="text-xs text-red-500 mb-2 font-medium"
              >
                URL tidak valid. Harus diawali dengan https://www.google.com/maps/embed
              </p>
              <div
                class="h-56 w-full rounded-xl overflow-hidden border-4 border-gray-100 dark:border-slate-700/50 bg-gray-50 dark:bg-slate-800/50 mt-2 shadow-sm"
              >
                <iframe
                  v-if="generalSettings.embedMap"
                  :src="generalSettings.embedMap"
                  width="100%"
                  height="100%"
                  style="border: 0"
                  allowfullscreen=""
                  loading="lazy"
                ></iframe>
                <div
                  v-else
                  class="flex items-center justify-center w-full h-full text-gray-400 text-sm"
                >
                  Preview Peta
                </div>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Email</label
                >
                <input
                  type="email"
                  v-model="generalSettings.email"
                  class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                />
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Telepon</label
                >
                <input
                  type="text"
                  v-model="generalSettings.telepon"
                  class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                />
              </div>
            </div>

            <h3
              class="text-lg font-semibold text-gray-800 dark:text-white mb-4 mt-8 border-b border-gray-100 dark:border-slate-700 pb-2"
            >
              Sosial Media
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Instagram</label
                >
                <input
                  type="text"
                  v-model="generalSettings.instagram"
                  class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                  placeholder="@username"
                />
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Facebook</label
                >
                <input
                  type="text"
                  v-model="generalSettings.facebook"
                  class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                />
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >YouTube</label
                >
                <input
                  type="text"
                  v-model="generalSettings.youtube"
                  class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                />
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >X (Twitter)</label
                >
                <input
                  type="text"
                  v-model="generalSettings.x"
                  class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                />
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >TikTok</label
                >
                <input
                  type="text"
                  v-model="generalSettings.tiktok"
                  class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                />
              </div>
            </div>
          </div>
          <div class="lg:col-span-1 space-y-6">
            <div
              class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl border border-gray-100 dark:border-slate-600"
            >
              <ImageUploader
                v-model="generalSettings.logo"
                label="Logo Sekolah"
                :isCircular="true"
                containerClass="w-32 mx-auto"
              />
              <p class="text-xs text-center text-gray-500 mt-2">
                Format PNG disarankan, transparan. Max 2MB.
              </p>
            </div>
            <div
              class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl border border-gray-100 dark:border-slate-600"
            >
              <ImageUploader
                v-model="generalSettings.favicon"
                label="Favicon (Ikon Web)"
                :isCircular="true"
                containerClass="w-20 mx-auto"
              />
              <p class="text-xs text-center text-gray-500 mt-2">
                Ikon untuk tab browser. 1:1, Max 1MB.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Tampilan -->
      <div v-show="activeTab === 'tampilan'" class="space-y-8 animate-fade-in">
        <div>
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
            Pengaturan Header Halaman
          </h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Kelola gambar latar (banner) untuk setiap halaman pada website publik.
          </p>

          <!-- Group: Beranda -->
          <div class="mb-8">
            <div
              class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-200 dark:border-slate-700"
            >
              <PhHouse class="w-5 h-5 text-blue-600 dark:text-blue-400" />
              <h4 class="font-semibold text-gray-800 dark:text-white">Halaman Utama</h4>
            </div>
            <div
              class="bg-gray-50/50 dark:bg-slate-700/30 p-5 rounded-xl border border-gray-200 dark:border-slate-600"
            >
              <ImageUploader
                v-model="appearanceSettings.headerBeranda"
                label="Banner Beranda Utama"
                accept="image/*,video/mp4,video/webm"
                containerClass="w-full aspect-[21/9] md:aspect-[24/9]"
              />
              <p
                class="text-xs text-gray-500 dark:text-gray-400 mt-3 flex items-center gap-1"
              >
                <span class="font-semibold text-blue-600 dark:text-blue-400">Info:</span>
                Mendukung format gambar (JPG/PNG) dan video (MP4/WebM). Disarankan
                resolusi lanskap lebar.
              </p>
            </div>
          </div>

          <!-- Group: Profil Sekolah -->
          <div class="mb-8">
            <div
              class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-200 dark:border-slate-700"
            >
              <PhBuildings class="w-5 h-5 text-blue-600 dark:text-blue-400" />
              <h4 class="font-semibold text-gray-800 dark:text-white">Profil Sekolah</h4>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerSejarah"
                  label="Sejarah"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerVisiMisi"
                  label="Visi & Misi"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerFasilitas"
                  label="Fasilitas"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerGuruStaf"
                  label="Guru & Staf"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
            </div>
          </div>

          <!-- Group: Akademik & Kesiswaan -->
          <div class="mb-8">
            <div
              class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-200 dark:border-slate-700"
            >
              <PhGraduationCap class="w-5 h-5 text-blue-600 dark:text-blue-400" />
              <h4 class="font-semibold text-gray-800 dark:text-white">
                Akademik & Kesiswaan
              </h4>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerKurikulum"
                  label="Kurikulum"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerProgramJurusan"
                  label="Program Jurusan"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerEkskul"
                  label="Ekstrakurikuler"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerPrestasi"
                  label="Prestasi Siswa"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerAlumni"
                  label="Data Alumni"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
            </div>
          </div>

          <!-- Group: Publikasi & Media -->
          <div class="mb-4">
            <div
              class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-200 dark:border-slate-700"
            >
              <PhMegaphone class="w-5 h-5 text-blue-600 dark:text-blue-400" />
              <h4 class="font-semibold text-gray-800 dark:text-white">
                Publikasi & Media
              </h4>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerPendaftaran"
                  label="Info PPDB"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerBerita"
                  label="Berita"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerArtikel"
                  label="Artikel"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerGaleri"
                  label="Galeri Foto & Video"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.headerUnduhan"
                  label="Unduhan File"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
            </div>
          </div>
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

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.3s ease-out forwards;
}
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(5px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
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
