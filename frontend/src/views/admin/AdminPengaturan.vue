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
  benefitFasilitasImage: "",
  benefitGuruImage: "",
  benefitPrestasiImage: "",
  programCoverImage: "",
  loginBackground: "",
  ppdbBackgroundImage: "",
  galleryBackgroundImage: "",
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
      <div v-show="activeTab === 'umum'" class="space-y-6 animate-fade-in">
        <!-- Identitas Sekolah & Logo -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
          <div
            class="xl:col-span-2 space-y-5 bg-gray-50/50 dark:bg-slate-700/30 p-5 md:p-6 rounded-2xl border border-gray-200 dark:border-slate-600"
          >
            <div
              class="flex items-center gap-2 mb-2 pb-2 border-b border-gray-200 dark:border-slate-700"
            >
              <PhBuildings class="w-5 h-5 text-gray-600 dark:text-white" />
              <h3 class="font-semibold text-gray-800 dark:text-white">Identitas Utama</h3>
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
          </div>

          <div class="xl:col-span-1 flex flex-col gap-6">
            <div
              class="bg-gray-50/50 dark:bg-slate-700/30 p-5 rounded-2xl border border-gray-200 dark:border-slate-600 flex flex-col items-center justify-center h-full"
            >
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
            <div
              class="bg-gray-50/50 dark:bg-slate-700/30 p-5 rounded-2xl border border-gray-200 dark:border-slate-600 flex flex-col items-center justify-center h-full"
            >
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
          class="bg-gray-50/50 dark:bg-slate-700/30 p-5 md:p-6 rounded-2xl border border-gray-200 dark:border-slate-600 space-y-5"
        >
          <div
            class="flex items-center gap-2 mb-2 pb-2 border-b border-gray-200 dark:border-slate-700"
          >
            <PhMapPin class="w-5 h-5 text-gray-600 dark:text-white" />
            <h3 class="font-semibold text-gray-800 dark:text-white">Kontak & Lokasi</h3>
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
                  dari iframe Google Maps, atau cukup paste seluruh tag iframe di sini.
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
          class="bg-gray-50/50 dark:bg-slate-700/30 p-5 md:p-6 rounded-2xl border border-gray-200 dark:border-slate-600 space-y-5"
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
              class="bg-gray-50/50 dark:bg-slate-700/30 p-5 rounded-xl border border-gray-200 dark:border-slate-600 mb-5"
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

            <h5 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 text-sm">
              Gambar Komponen Beranda
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.benefitFasilitasImage"
                  label="Kenapa Pilih Kami 1 (Fasilitas)"
                  containerClass="w-full aspect-[4/3]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.benefitGuruImage"
                  label="Kenapa Pilih Kami 2 (Guru)"
                  containerClass="w-full aspect-[4/3]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.benefitPrestasiImage"
                  label="Kenapa Pilih Kami 3 (Prestasi)"
                  containerClass="w-full aspect-[4/3]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.programCoverImage"
                  label="Cover Jurusan Pilihan"
                  containerClass="w-full aspect-[4/3]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.ppdbBackgroundImage"
                  label="Gambar Latar FAQ PPDB"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.galleryBackgroundImage"
                  label="Gambar Latar Video & Galeri"
                  containerClass="w-full aspect-[21/9]"
                />
              </div>
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

          <!-- Group: Sistem & Autentikasi -->
          <div class="mb-4">
            <div
              class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-200 dark:border-slate-700"
            >
              <PhLockKey class="w-5 h-5 text-blue-600 dark:text-blue-400" />
              <h4 class="font-semibold text-gray-800 dark:text-white">
                Sistem & Autentikasi
              </h4>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
              <div
                class="bg-gray-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-200 dark:border-slate-600"
              >
                <ImageUploader
                  v-model="appearanceSettings.loginBackground"
                  label="Background Halaman Login"
                  containerClass="w-full aspect-[21/9]"
                />
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
