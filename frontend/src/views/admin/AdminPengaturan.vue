<script setup>
import { ref, computed } from "vue";
import {
  PhGear,
  PhImage,
  PhTag,
  PhFloppyDisk,
  PhPlus,
  PhTrash,
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
  namaSekolah: "SMAN 1 Nogosari",
  deskripsi: "Pendidikan berkualitas berfokus pada pembentukan karakter",
  alamat: "Nogosari, Kab. Boyolali, Jawa Tengah",
  embedMap:
    "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.5103639967265!2d110.79379!3d-7.4682055!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMjgnMDUuNSJTIDExMMKwNDcnMzcuNiJF!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid",
  email: "info@sman1nogosari.sch.id",
  telepon: "(0276) 123456",
  instagram: "@sman1nogosari",
  youtube: "SMAN 1 Nogosari Official",
  facebook: "SMAN 1 Nogosari",
  x: "@sman1nogosari",
  tiktok: "@sman1nogosari",
  logo: "",
  favicon: "",
});

// State Tampilan
const appearanceSettings = ref({
  headerBeranda: "/img/footage.webm",
  headerSejarah:
    "https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?q=80&w=1600",
  headerVisiMisi:
    "https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1600",
  headerFasilitas:
    "https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=1600",
  headerGuruStaf:
    "https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=1600",
  headerEkskul:
    "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1600",
});

// State Kategori
const categorySettings = ref({
  berita: ["Akademik", "Prestasi", "Pengumuman", "Kegiatan"],
  galeri: ["Fasilitas", "Ekstrakurikuler", "Acara Sekolah", "Lomba"],
  jabatan: [
    "Kepala Sekolah",
    "Wakil Kepala Sekolah",
    "Guru Mata Pelajaran",
    "Staf Tata Usaha",
    "Pustakawan",
  ],
});

const newCategory = ref({
  berita: "",
  galeri: "",
  jabatan: "",
});

const addCategory = (type) => {
  if (newCategory.value[type].trim()) {
    categorySettings.value[type].push(newCategory.value[type].trim());
    newCategory.value[type] = "";
    triggerToast("Berhasil", "Kategori baru ditambahkan", "success");
  }
};

const removeCategory = (type, index) => {
  categorySettings.value[type].splice(index, 1);
  triggerToast("Dihapus", "Kategori dihapus", "info");
};

const isMapUrlValid = computed(() => {
  const url = generalSettings.value.embedMap;
  if (!url) return true;
  return url.startsWith("https://www.google.com/maps/embed");
});

const saveSettings = () => {
  if (!isMapUrlValid.value) {
    triggerToast(
      "Gagal Menyimpan",
      "Link Embed Map tidak valid. Pastikan menggunakan URL dari iframe Google Maps.",
      "error"
    );
    return;
  }
  triggerToast("Tersimpan", "Pengaturan berhasil disimpan", "success");
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8 bg-gray-100 dark:bg-slate-900">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2
          class="text-3xl font-bold text-gray-800 dark:text-white"
          style="font-family: 'Oswald', sans-serif"
        >
          Pengaturan Sistem
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola konfigurasi umum, tampilan, dan data kategori website.
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
      <button
        @click="activeTab = 'kategori'"
        class="px-6 py-4 text-sm font-medium transition-colors flex items-center gap-2 whitespace-nowrap border-b-2"
        :class="
          activeTab === 'kategori'
            ? 'text-blue-600 border-blue-600 dark:text-blue-400 dark:border-blue-400 bg-blue-50/50 dark:bg-slate-700/50'
            : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-slate-700'
        "
      >
        <PhTag class="w-5 h-5" /> Data Kategori
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
            Page Header Halaman Publik
          </h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Atur gambar banner/header untuk masing-masing halaman website utama.
          </p>

          <div
            class="border border-gray-200 dark:border-slate-600 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-700/30 mb-6"
          >
            <ImageUploader
              v-model="appearanceSettings.headerBeranda"
              label="Banner Beranda Utama"
              accept="image/*,video/mp4,video/webm"
              containerClass="w-full aspect-[16/9] md:aspect-[21/9]"
            />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
              Mendukung format gambar (JPG/PNG) dan video (MP4/WebM).
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div
              class="border border-gray-200 dark:border-slate-600 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-700/30"
            >
              <ImageUploader
                v-model="appearanceSettings.headerSejarah"
                label="Halaman Sejarah"
                containerClass="w-full aspect-[21/9]"
              />
            </div>
            <div
              class="border border-gray-200 dark:border-slate-600 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-700/30"
            >
              <ImageUploader
                v-model="appearanceSettings.headerVisiMisi"
                label="Halaman Visi & Misi"
                containerClass="w-full aspect-[21/9]"
              />
            </div>
            <div
              class="border border-gray-200 dark:border-slate-600 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-700/30"
            >
              <ImageUploader
                v-model="appearanceSettings.headerFasilitas"
                label="Halaman Fasilitas"
                containerClass="w-full aspect-[21/9]"
              />
            </div>
            <div
              class="border border-gray-200 dark:border-slate-600 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-700/30"
            >
              <ImageUploader
                v-model="appearanceSettings.headerGuruStaf"
                label="Halaman Guru & Staf"
                containerClass="w-full aspect-[21/9]"
              />
            </div>
            <div
              class="border border-gray-200 dark:border-slate-600 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-700/30"
            >
              <ImageUploader
                v-model="appearanceSettings.headerEkskul"
                label="Halaman Ekstrakurikuler"
                containerClass="w-full aspect-[21/9]"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Kategori -->
      <div v-show="activeTab === 'kategori'" class="space-y-8 animate-fade-in">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Kategori Berita -->
          <div
            class="border border-gray-200 dark:border-slate-600 p-6 rounded-xl bg-gray-50/50 dark:bg-slate-700/30"
          >
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
              Kategori Berita
            </h3>
            <div class="flex gap-2 mb-4">
              <input
                type="text"
                v-model="newCategory.berita"
                @keyup.enter="addCategory('berita')"
                placeholder="Tambah kategori berita..."
                class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm"
              />
              <button
                @click="addCategory('berita')"
                class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
              >
                <PhPlus class="w-5 h-5" />
              </button>
            </div>
            <ul class="space-y-2 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
              <li
                v-for="(item, index) in categorySettings.berita"
                :key="index"
                class="flex justify-between items-center bg-white dark:bg-slate-800 p-3 rounded-lg border border-gray-100 dark:border-slate-600 shadow-sm text-sm"
              >
                <span class="dark:text-gray-200 font-medium">{{ item }}</span>
                <button
                  @click="removeCategory('berita', index)"
                  class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 p-1.5 rounded"
                >
                  <PhTrash class="w-4 h-4" />
                </button>
              </li>
              <li
                v-if="categorySettings.berita.length === 0"
                class="text-sm text-gray-500 text-center py-4"
              >
                Belum ada kategori
              </li>
            </ul>
          </div>

          <!-- Kategori Galeri -->
          <div
            class="border border-gray-200 dark:border-slate-600 p-6 rounded-xl bg-gray-50/50 dark:bg-slate-700/30"
          >
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
              Kategori Galeri
            </h3>
            <div class="flex gap-2 mb-4">
              <input
                type="text"
                v-model="newCategory.galeri"
                @keyup.enter="addCategory('galeri')"
                placeholder="Tambah kategori galeri..."
                class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm"
              />
              <button
                @click="addCategory('galeri')"
                class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
              >
                <PhPlus class="w-5 h-5" />
              </button>
            </div>
            <ul class="space-y-2 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
              <li
                v-for="(item, index) in categorySettings.galeri"
                :key="index"
                class="flex justify-between items-center bg-white dark:bg-slate-800 p-3 rounded-lg border border-gray-100 dark:border-slate-600 shadow-sm text-sm"
              >
                <span class="dark:text-gray-200 font-medium">{{ item }}</span>
                <button
                  @click="removeCategory('galeri', index)"
                  class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 p-1.5 rounded"
                >
                  <PhTrash class="w-4 h-4" />
                </button>
              </li>
              <li
                v-if="categorySettings.galeri.length === 0"
                class="text-sm text-gray-500 text-center py-4"
              >
                Belum ada kategori
              </li>
            </ul>
          </div>

          <!-- Jabatan Guru & Staf -->
          <div
            class="border border-gray-200 dark:border-slate-600 p-6 rounded-xl bg-gray-50/50 dark:bg-slate-700/30 lg:col-span-2"
          >
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
              Jabatan Guru & Staf
            </h3>
            <div class="flex gap-2 mb-4 max-w-md">
              <input
                type="text"
                v-model="newCategory.jabatan"
                @keyup.enter="addCategory('jabatan')"
                placeholder="Tambah posisi/jabatan..."
                class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm"
              />
              <button
                @click="addCategory('jabatan')"
                class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
              >
                <PhPlus class="w-5 h-5" />
              </button>
            </div>
            <div class="flex flex-wrap gap-3">
              <div
                v-for="(item, index) in categorySettings.jabatan"
                :key="index"
                class="flex items-center gap-2 bg-white dark:bg-slate-800 px-3 py-2 rounded-lg border border-gray-100 dark:border-slate-600 shadow-sm text-sm"
              >
                <span class="dark:text-gray-200 font-medium">{{ item }}</span>
                <button
                  @click="removeCategory('jabatan', index)"
                  class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 p-1 rounded-full transition-colors"
                >
                  <PhTrash class="w-3.5 h-3.5" />
                </button>
              </div>
              <div
                v-if="categorySettings.jabatan.length === 0"
                class="text-sm text-gray-500 text-center py-2 w-full"
              >
                Belum ada posisi
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
