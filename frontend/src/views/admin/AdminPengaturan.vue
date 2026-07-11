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
  PhUser,
  PhLockKey,
  PhTextT,
  PhTextAa,
  PhMapPin,
  PhShareNetwork,
  PhInstagramLogo,
  PhFacebookLogo,
  PhYoutubeLogo,
  PhTwitterLogo,
  PhTiktokLogo,
} from "@phosphor-icons/vue";
import ImageUploader from "@/components/admin/ImageUploader.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const userRole = ref(localStorage.getItem("user_role") || "admin");
const activeTab = ref(userRole.value === "super_admin" ? "umum" : "akun");

// State untuk navigasi kategori di tab "Pengaturan Umum"
const generalSettingsCategories = ref([
  { key: "identitas", label: "Identitas Utama", icon: PhBuildings },
  { key: "kontak", label: "Kontak & Lokasi", icon: PhMapPin },
  { key: "sosmed", label: "Sosial Media", icon: PhShareNetwork },
]);
const activeGeneralCategoryKey = ref("identitas");

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
  headerSejarah_title: "",
  headerSejarah_description: "",
  headerSejarah_bgImage: "",
  headerVisiMisi_title: "",
  headerVisiMisi_description: "",
  headerVisiMisi_bgImage: "",
  headerFasilitas_title: "",
  headerFasilitas_description: "",
  headerFasilitas_bgImage: "",
  headerGuruStaf_title: "",
  headerGuruStaf_description: "",
  headerGuruStaf_bgImage: "",
  headerEkskul_title: "",
  headerEkskul_description: "",
  headerEkskul_bgImage: "",
  headerKurikulum_title: "",
  headerKurikulum_description: "",
  headerKurikulum_bgImage: "",
  headerAlumni_title: "",
  headerAlumni_description: "",
  headerAlumni_bgImage: "",
  headerProgramJurusan_title: "",
  headerProgramJurusan_description: "",
  headerProgramJurusan_bgImage: "",
  headerPrestasi_title: "",
  headerPrestasi_description: "",
  headerPrestasi_bgImage: "",
  headerPendaftaran_title: "",
  headerPendaftaran_description: "",
  headerPendaftaran_bgImage: "",
  headerBerita_title: "",
  headerBerita_description: "",
  headerBerita_bgImage: "",
  headerGaleri_title: "",
  headerGaleri_description: "",
  headerGaleri_bgImage: "",
  headerArtikel_title: "",
  headerArtikel_description: "",
  headerArtikel_bgImage: "",
  headerUnduhan_title: "",
  headerUnduhan_description: "",
  headerUnduhan_bgImage: "",
  benefitFasilitasImage: "",
  benefitGuruImage: "",
  benefitPrestasiImage: "",
  programCoverImage: "",
  loginBackground: "",
  ppdbBackgroundImage: "",
  galleryBackgroundImage: "",
});

const headerPagesConfig = ref([
  {
    group: "Halaman Utama",
    icon: PhHouse,
    pages: [
      {
        key: "headerBeranda",
        label: "Banner Utama",
        type: "image_only",
        aspect: "24/9",
        note: "Mendukung format gambar (JPG/PNG) dan video (MP4/WebM).",
        accept: "image/*,video/mp4,video/webm",
      },
      {
        key: "benefitFasilitasImage",
        label: "Benefit: Fasilitas",
        type: "image_only",
        aspect: "4/3",
      },
      {
        key: "benefitGuruImage",
        label: "Benefit: Guru",
        type: "image_only",
        aspect: "4/3",
      },
      {
        key: "benefitPrestasiImage",
        label: "Benefit: Prestasi",
        type: "image_only",
        aspect: "4/3",
      },
      {
        key: "programCoverImage",
        label: "Cover Jurusan",
        type: "image_only",
        aspect: "4/3",
      },
      {
        key: "ppdbBackgroundImage",
        label: "Latar Belakang FAQ PPDB",
        type: "image_only",
        aspect: "21/9",
      },
      {
        key: "galleryBackgroundImage",
        label: "Latar Belakang Video & Galeri",
        type: "image_only",
        aspect: "21/9",
      },
    ],
  },
  {
    group: "Profil Sekolah",
    icon: PhBuildings,
    pages: [
      { key: "headerSejarah", label: "Sejarah" },
      { key: "headerVisiMisi", label: "Visi & Misi" },
      { key: "headerFasilitas", label: "Fasilitas" },
      { key: "headerGuruStaf", label: "Guru & Staf" },
    ],
  },
  {
    group: "Akademik & Kesiswaan",
    icon: PhGraduationCap,
    pages: [
      { key: "headerKurikulum", label: "Kurikulum" },
      { key: "headerProgramJurusan", label: "Program Jurusan" },
      { key: "headerEkskul", label: "Ekstrakurikuler" },
      { key: "headerPrestasi", label: "Prestasi Siswa" },
      { key: "headerAlumni", label: "Data Alumni" },
    ],
  },
  {
    group: "Publikasi & Media",
    icon: PhMegaphone,
    pages: [
      { key: "headerPendaftaran", label: "Informasi PPDB" },
      { key: "headerBerita", label: "Berita" },
      { key: "headerArtikel", label: "Artikel" },
      { key: "headerGaleri", label: "Galeri Foto & Video" },
      { key: "headerUnduhan", label: "Unduhan File" },
    ],
  },
  {
    group: "Sistem & Autentikasi",
    icon: PhLockKey,
    pages: [
      {
        key: "loginBackground",
        label: "Background Halaman Login",
        type: "image_only",
        aspect: "21/9",
      },
    ],
  },
]);

const activeHeaderPageKey = ref("headerBeranda");

const activeHeaderPage = computed(() => {
  for (const group of headerPagesConfig.value) {
    const found = group.pages.find((p) => p.key === activeHeaderPageKey.value);
    if (found) return found;
  }
  return null;
});

// State Akun
const currentUser = ref({
  name: "",
  provider: "",
  avatar: "",
});

const profileForm = ref({
  name: "",
  password: "",
  avatar: "",
});

const isMapUrlValid = computed(() => {
  const url = generalSettings.value.embedMap;
  if (!url) return true;
  return url.includes("google.com/maps/embed");
});

const extractLocationFromMapUrl = () => {
  let url = generalSettings.value.embedMap;
  if (!url) return;

  // Jika user memasukkan tag iframe, ekstrak src-nya saja
  const iframeRegex = /src="([^"]+)"/;
  const matchIframe = url.match(iframeRegex);
  if (matchIframe) {
    url = matchIframe[1];
    generalSettings.value.embedMap = url; // auto-correct to just the URL
  }

  // Coba ambil nama tempat dari parameter pb (biasanya di tag !2s)
  const pbMatch = url.match(/!2s([^!]+)/);
  if (pbMatch && pbMatch[1]) {
    try {
      const locationName = decodeURIComponent(pbMatch[1].replace(/\+/g, " "));
      // Isi otomatis alamat jika masih kosong
      if (!generalSettings.value.alamat) {
        generalSettings.value.alamat = locationName;
      }
    } catch (e) {
      console.error("Gagal mendecode nama lokasi dari URL peta", e);
    }
  }
};

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

const fetchProfile = async () => {
  try {
    const response = await api.get("/api/user");
    currentUser.value = response.data;
    profileForm.value.name = response.data.name;
    profileForm.value.avatar = response.data.avatar || "";
  } catch (error) {
    console.error("Gagal mengambil profil:", error);
  }
};

onMounted(() => {
  if (userRole.value === "super_admin") {
    fetchSettings();
  }
  fetchProfile();
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

    // Mengirim sinyal agar semua komponen lain (Sidebar, Header, Publik) me-refresh datanya secara reaktif
    window.dispatchEvent(new CustomEvent("settings-updated"));
    // Mengirim sinyal ke tab browser lain (cross-tab) menggunakan event 'storage'
    localStorage.setItem("settings_updated_at", Date.now());
  } catch (error) {
    console.error("Gagal menyimpan pengaturan:", error);
    triggerToast(
      "Gagal Menyimpan",
      "Terjadi kesalahan saat menyimpan pengaturan",
      "error"
    );
  }
};

const updateProfile = async () => {
  try {
    const payload = { name: profileForm.value.name };

    // Jika ada perubahan pada foto profil
    if (profileForm.value.avatar !== currentUser.value.avatar) {
      payload.avatar = profileForm.value.avatar;
    }

    if (currentUser.value.provider === "local" && profileForm.value.password) {
      payload.password = profileForm.value.password;
    }

    await api.post("/api/profile", payload);
    triggerToast("Profil Tersimpan", "Profil akun berhasil diperbarui", "success");
    profileForm.value.password = ""; // Reset form password setelah tersimpan

    // Sinkronisasikan foto pada state (sehingga tidak diupload berulang)
    currentUser.value.avatar = payload.avatar;
  } catch (error) {
    console.error("Gagal menyimpan profil:", error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat memperbarui profil", "error");
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
          {{ userRole === "super_admin" ? "Pengaturan Sistem" : "Pengaturan Akun" }}
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          {{
            userRole === "super_admin"
              ? "Kelola konfigurasi umum, tampilan website, dan profil akun."
              : "Kelola profil dan keamanan akun Anda."
          }}
        </p>
      </div>
      <button
        v-if="activeTab !== 'akun'"
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
        v-if="userRole === 'super_admin'"
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
        v-if="userRole === 'super_admin'"
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
        @click="activeTab = 'akun'"
        class="px-6 py-4 text-sm font-medium transition-colors flex items-center gap-2 whitespace-nowrap border-b-2"
        :class="
          activeTab === 'akun'
            ? 'text-blue-600 border-blue-600 dark:text-blue-400 dark:border-blue-400 bg-blue-50/50 dark:bg-slate-700/50'
            : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-slate-700'
        "
      >
        <PhUser class="w-5 h-5" /> Pengaturan Akun
      </button>
    </div>

    <div
      class="bg-white dark:bg-slate-800 rounded-b-xl border border-t-0 border-gray-200 dark:border-slate-700 shadow-sm p-6 mb-8"
    >
      <!-- Tab Umum -->
      <div v-show="activeTab === 'umum'" class="animate-fade-in">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
          <!-- Kolom Kiri: Navigasi Kategori -->
          <div
            class="lg:col-span-1 lg:sticky lg:top-6 bg-white dark:bg-slate-800/50 p-4 rounded-xl border border-gray-200 dark:border-slate-700"
          >
            <ul class="space-y-1">
              <li v-for="cat in generalSettingsCategories" :key="cat.key">
                <button
                  @click="activeGeneralCategoryKey = cat.key"
                  class="w-full text-left text-sm px-3 py-2 rounded-md transition-colors flex items-center gap-2"
                  :class="
                    activeGeneralCategoryKey === cat.key
                      ? 'bg-blue-100 dark:bg-blue-900/50 font-semibold text-blue-700 dark:text-blue-300'
                      : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700'
                  "
                >
                  <component :is="cat.icon" class="w-5 h-5" />
                  {{ cat.label }}
                </button>
              </li>
            </ul>
          </div>

          <!-- Kolom Kanan: Form Editor -->
          <div
            class="lg:col-span-3 bg-gray-50/50 dark:bg-slate-700/30 p-5 md:p-6 rounded-2xl border border-gray-200 dark:border-slate-600"
          >
            <!-- Identitas Sekolah & Logo -->
            <div
              v-if="activeGeneralCategoryKey === 'identitas'"
              class="animate-fade-in space-y-5"
            >
              <div
                class="flex items-center gap-2 mb-2 pb-2 border-b border-gray-200 dark:border-slate-700"
              >
                <PhBuildings class="w-5 h-5 text-gray-600 dark:text-white" />
                <h3 class="font-semibold text-gray-800 dark:text-white">
                  Identitas Utama
                </h3>
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 mt-6"
                  >Nama Sekolah</label
                >
                <input
                  type="text"
                  v-model="generalSettings.namaSekolah"
                  class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                />
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                  >Slogan / Moto Sekolah</label
                >
                <input
                  type="text"
                  v-model="generalSettings.deskripsi"
                  placeholder="Contoh: Mencetak Generasi Unggul..."
                  class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">
                  Akan ditampilkan sebagai teks hero di banner halaman beranda.
                </p>
              </div>
              <div
                class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-gray-200 dark:border-slate-700"
              >
                <div class="flex flex-col items-center justify-center h-full">
                  <ImageUploader
                    v-model="generalSettings.logo"
                    label="Logo Sekolah"
                    :isCircular="true"
                    containerClass="w-32 mx-auto"
                  />
                  <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-3">
                    Format PNG disarankan, transparan. Max 2MB.
                  </p>
                </div>
                <div class="flex flex-col items-center justify-center h-full">
                  <ImageUploader
                    v-model="generalSettings.favicon"
                    label="Favicon (Ikon Web)"
                    :isCircular="true"
                    containerClass="w-20 mx-auto"
                  />
                  <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-3">
                    Ikon untuk tab browser. Rasio 1:1, Max 1MB.
                  </p>
                </div>
              </div>
            </div>

            <!-- Kontak & Lokasi -->
            <div
              v-if="activeGeneralCategoryKey === 'kontak'"
              class="animate-fade-in space-y-5"
            >
              <div
                class="flex items-center gap-2 mb-2 pb-2 border-b border-gray-200 dark:border-slate-700"
              >
                <PhMapPin class="w-5 h-5 text-gray-600 dark:text-white" />
                <h3 class="font-semibold text-gray-800 dark:text-white">
                  Kontak & Lokasi
                </h3>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-6">
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                    >Email Resmi</label
                  >
                  <input
                    type="email"
                    v-model="generalSettings.email"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                    placeholder="info@sekolah.sch.id"
                  />
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                    >Nomor Telepon</label
                  >
                  <input
                    type="text"
                    v-model="generalSettings.telepon"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                    placeholder="(021) 1234567"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-2">
                <div class="space-y-5">
                  <div>
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                      >Link Embed Peta (Google Maps)</label
                    >
                    <textarea
                      v-model="generalSettings.embedMap"
                      @input="extractLocationFromMapUrl"
                      rows="4"
                      class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                      :class="
                        !isMapUrlValid && generalSettings.embedMap
                          ? 'border-red-500 ring-2 ring-red-500 focus:ring-red-500 focus:border-red-500'
                          : ''
                      "
                      placeholder="Bisa paste URL langsung atau tag <iframe> dari Google Maps..."
                    ></textarea>
                    <p
                      v-if="!isMapUrlValid && generalSettings.embedMap"
                      class="text-xs text-red-500 mt-1.5 font-medium"
                    >
                      URL tidak valid. Harus mengandung https://www.google.com/maps/embed
                    </p>
                    <p v-else class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">
                      Salin URL atribut
                      <code
                        class="bg-gray-100 dark:bg-slate-700 px-1 rounded text-blue-600 dark:text-blue-400"
                        >src</code
                      >
                      dari iframe Google Maps, atau cukup paste seluruh tag iframe di
                      sini.
                    </p>
                  </div>
                  <div>
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                      >Alamat Lengkap</label
                    >
                    <textarea
                      v-model="generalSettings.alamat"
                      rows="3"
                      class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                      placeholder="Jl. Raya Sekolah No. 123..."
                    ></textarea>
                  </div>
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                    >Preview Peta Lokasi</label
                  >
                  <div
                    class="h-[250px] w-full rounded-xl overflow-hidden border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 shadow-sm relative"
                  >
                    <iframe
                      v-if="isMapUrlValid && generalSettings.embedMap"
                      :src="generalSettings.embedMap"
                      width="100%"
                      height="100%"
                      style="border: 0"
                      allowfullscreen=""
                      loading="lazy"
                    ></iframe>
                    <div
                      v-else
                      class="flex flex-col items-center justify-center w-full h-full text-gray-400 dark:text-gray-500 p-6 text-center"
                    >
                      <PhMapPin class="w-10 h-10 mb-2 opacity-50" />
                      <span class="text-sm"
                        >Preview peta akan muncul di sini setelah link embed valid
                        dimasukkan.</span
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sosial Media -->
            <div
              v-if="activeGeneralCategoryKey === 'sosmed'"
              class="animate-fade-in space-y-5"
            >
              <div
                class="flex items-center gap-2 mb-2 pb-2 border-b border-gray-200 dark:border-slate-700"
              >
                <PhShareNetwork class="w-5 h-5 text-gray-600 dark:text-white" />
                <h3 class="font-semibold text-gray-800 dark:text-white">
                  Tautan Sosial Media
                </h3>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mt-6">
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 flex items-center gap-1.5"
                    ><PhInstagramLogo class="w-4 h-4 text-pink-600" /> Instagram</label
                  >
                  <input
                    type="text"
                    v-model="generalSettings.instagram"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                    placeholder="https://instagram.com/..."
                  />
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 flex items-center gap-1.5"
                    ><PhFacebookLogo class="w-4 h-4 text-blue-600" /> Facebook</label
                  >
                  <input
                    type="text"
                    v-model="generalSettings.facebook"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                    placeholder="https://facebook.com/..."
                  />
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 flex items-center gap-1.5"
                    ><PhYoutubeLogo class="w-4 h-4 text-red-600" /> YouTube</label
                  >
                  <input
                    type="text"
                    v-model="generalSettings.youtube"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                    placeholder="https://youtube.com/..."
                  />
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 flex items-center gap-1.5"
                    ><PhTwitterLogo class="w-4 h-4 text-gray-800 dark:text-gray-200" /> X
                    (Twitter)</label
                  >
                  <input
                    type="text"
                    v-model="generalSettings.x"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                    placeholder="https://x.com/..."
                  />
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 flex items-center gap-1.5"
                    ><PhTiktokLogo class="w-4 h-4 text-black dark:text-gray-200" />
                    TikTok</label
                  >
                  <input
                    type="text"
                    v-model="generalSettings.tiktok"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                    placeholder="https://tiktok.com/@..."
                  />
                </div>
              </div>
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
            Kelola judul, deskripsi, dan gambar latar (banner) untuk setiap halaman pada
            website publik.
          </p>

          <!-- Group: Header Halaman Statis -->
          <div class="mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
              <!-- Kolom Kiri: Navigasi Halaman -->
              <div
                class="lg:col-span-1 lg:sticky lg:top-6 bg-white dark:bg-slate-800/50 p-4 rounded-xl border border-gray-200 dark:border-slate-700"
              >
                <div v-for="group in headerPagesConfig" :key="group.group" class="mb-4">
                  <h5
                    class="flex items-center gap-2 text-sm font-bold text-gray-800 dark:text-gray-400 px-3 mb-2"
                  >
                    <component :is="group.icon" class="w-4 h-4" />
                    {{ group.group }}
                  </h5>
                  <ul class="space-y-1">
                    <li v-for="page in group.pages" :key="page.key">
                      <button
                        @click="activeHeaderPageKey = page.key"
                        class="w-full text-left text-sm px-3 py-2 rounded-md transition-colors flex items-center gap-2"
                        :class="
                          activeHeaderPageKey === page.key
                            ? 'bg-blue-100 dark:bg-blue-900/50 font-semibold text-blue-700 dark:text-blue-300'
                            : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700'
                        "
                      >
                        {{ page.label }}
                      </button>
                    </li>
                  </ul>
                </div>
              </div>

              <!-- Kolom Kanan: Form Editor -->
              <div
                class="lg:col-span-3 bg-gray-50/50 dark:bg-slate-700/30 p-5 md:p-6 rounded-2xl border border-gray-200 dark:border-slate-600"
              >
                <div v-if="activeHeaderPage" class="animate-fade-in">
                  <h4
                    class="text-lg font-bold text-gray-800 dark:text-white mb-1 flex items-center gap-2"
                  >
                    <component
                      :is="activeHeaderPage.icon || PhImage"
                      class="w-5 h-5 text-gray-800 dark:text-gray-400"
                    />

                    <span class="text-gray-800 dark:text-gray-400">{{
                      activeHeaderPage.label
                    }}</span>
                  </h4>
                  <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Atur konten yang akan tampil pada komponen
                    {{ activeHeaderPage.label }}.
                  </p>

                  <div v-if="!activeHeaderPage.type" class="space-y-5">
                    <div>
                      <label
                        class="flex items-center gap-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                        ><PhTextT class="w-4 h-4" /> Judul Halaman</label
                      >
                      <input
                        type="text"
                        v-model="appearanceSettings[activeHeaderPageKey + '_title']"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                        :placeholder="'Judul untuk halaman ' + activeHeaderPage.label"
                      />
                    </div>
                    <div>
                      <label
                        class="flex items-center gap-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                        ><PhTextAa class="w-4 h-4" /> Deskripsi Singkat</label
                      >
                      <textarea
                        v-model="appearanceSettings[activeHeaderPageKey + '_description']"
                        rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400/60 dark:placeholder-slate-500/60"
                        :placeholder="
                          'Deskripsi singkat untuk halaman ' + activeHeaderPage.label
                        "
                      ></textarea>
                    </div>
                    <div>
                      <label
                        class="flex items-center gap-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                        ><PhImage class="w-4 h-4" /> Gambar Latar</label
                      >
                      <ImageUploader
                        v-model="appearanceSettings[activeHeaderPageKey + '_bgImage']"
                        :label="`Gambar Latar Halaman ${activeHeaderPage.label}`"
                        containerClass="w-full"
                        :style="{ aspectRatio: '21/9' }"
                      />
                    </div>
                  </div>

                  <div v-if="activeHeaderPage.type === 'image_only'" class="space-y-5">
                    <div>
                      <ImageUploader
                        v-model="appearanceSettings[activeHeaderPageKey]"
                        :label="`Gambar untuk ${activeHeaderPage.label}`"
                        :accept="activeHeaderPage.accept || 'image/*'"
                        containerClass="w-full"
                        :style="{ aspectRatio: activeHeaderPage.aspect || '21/9' }"
                      />
                      <p
                        v-if="activeHeaderPage.note"
                        class="text-xs text-gray-500 dark:text-gray-400 mt-3"
                      >
                        <span class="font-semibold text-blue-600 dark:text-blue-400"
                          >Info:</span
                        >
                        {{ activeHeaderPage.note }}
                        />
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Akun -->
      <div v-show="activeTab === 'akun'" class="space-y-8 animate-fade-in">
        <form @submit.prevent="updateProfile" class="max-w-4xl">
          <div class="flex flex-col md:flex-row gap-8">
            <!-- Kolom Kiri: Avatar -->
            <div class="w-full md:w-1/3">
              <div
                class="bg-gray-50 dark:bg-slate-700/50 p-6 rounded-xl border border-gray-100 dark:border-slate-600 flex flex-col items-center sticky top-6"
              >
                <ImageUploader
                  v-model="profileForm.avatar"
                  label="Foto Profil"
                  :isCircular="true"
                  containerClass="w-32 mx-auto"
                />
                <p class="text-xs text-center text-gray-500 mt-4 leading-relaxed">
                  Format PNG/JPG disarankan, rasio 1:1. Maksimal 2MB.
                </p>
              </div>
            </div>

            <!-- Kolom Kanan: Data Form -->
            <div class="w-full md:w-2/3 space-y-6">
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >
                  Nama Lengkap
                </label>
                <input
                  type="text"
                  v-model="profileForm.name"
                  required
                  class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400/60 dark:placeholder-slate-500/60"
                />
              </div>

              <div v-if="currentUser.provider === 'local'">
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >
                  Kata Sandi Baru (Opsional)
                </label>
                <input
                  type="password"
                  v-model="profileForm.password"
                  minlength="8"
                  placeholder="Kosongkan jika tidak ingin mengubah kata sandi"
                  class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400/60 dark:placeholder-slate-500/60"
                />
                <p class="text-xs text-gray-500 mt-2">Minimal 8 karakter.</p>
              </div>

              <div class="pt-2">
                <button
                  type="submit"
                  class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors shrink-0"
                >
                  <PhFloppyDisk class="w-5 h-5 mr-2" />
                  Simpan Profil
                </button>
              </div>
            </div>
          </div>
        </form>
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
