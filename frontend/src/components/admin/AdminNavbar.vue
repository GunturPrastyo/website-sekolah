<script setup>
import { ref, onMounted, onBeforeUnmount, markRaw } from "vue";
import { useRouter } from "vue-router";
import api from "@/api/index.js";
import {
  PhMagnifyingGlass,
  PhBell,
  PhCaretDown,
  PhList,
  PhSun,
  PhMoon,
  PhHouse,
  PhClockCounterClockwise,
  PhBuildings,
  PhTarget,
  PhMegaphone,
  PhChalkboardTeacher,
  PhChalkboard,
  PhGraduationCap,
  PhBriefcase,
  PhMapPin,
  PhBooks,
  PhStar,
  PhBookBookmark,
  PhCalendar,
  PhActivity,
  PhTrophy,
  PhCalendarCheck,
  PhClipboardText,
  PhHourglass,
  PhListChecks,
  PhSignpost,
  PhQuestion,
  PhNewspaper,
  PhImages,
  PhDownloadSimple,
  PhCheckCircle,
  PhUsers,
  PhGear,
  PhShareNetwork,
  PhImageSquare,
  PhWarningCircle,
  PhX,
} from "@phosphor-icons/vue";

const emit = defineEmits(["toggle-sidebar"]);

const isDarkMode = ref(false);
const router = useRouter();

const searchQuery = ref("");
const searchResults = ref([]);
const isSearchOpen = ref(false);
const highlightedIndex = ref(-1);

const currentUser = ref({
  name: "Memuat...",
  role: "admin",
  avatar: null,
});

const isNotificationOpen = ref(false);
const notifications = ref([]);
const notifDropdown = ref(null);

const fetchNotifications = async () => {
  try {
    const response = await api.get("/api/notifications");
    notifications.value = response.data.data || [];
  } catch (error) {
    console.error("Gagal mengambil notifikasi:", error);
  }
};

const toggleNotification = () => {
  isNotificationOpen.value = !isNotificationOpen.value;
  if (isNotificationOpen.value) {
    fetchNotifications();
  }
};

const markAllRead = async () => {
  try {
    await api.post("/api/notifications/mark-all-read");
    notifications.value = [];
    isNotificationOpen.value = false;
  } catch (error) {
    console.error("Gagal menandai semua notifikasi telah dibaca:", error);
  }
};

const dismissNotification = async (id) => {
  try {
    await api.post(`/api/notifications/${id}/mark-read`);
    notifications.value = notifications.value.filter((n) => n.id !== id);
  } catch (error) {
    console.error("Gagal menghapus notifikasi:", error);
  }
};

const closeNotificationOutside = (e) => {
  if (notifDropdown.value && !notifDropdown.value.contains(e.target)) {
    isNotificationOpen.value = false;
  }
};

const adminMenus = [
  // Dashboard
  {
    name: "Dashboard",
    link: "/admin/dashboard",
    desc: "Ringkasan data & aktivitas sistem",
    keywords: ["home", "beranda", "statistik", "grafik pengunjung"],
    icon: markRaw(PhHouse),
  },

  // Profil Sekolah
  {
    name: "Sejarah & Lini Masa",
    link: "/admin/sejarah",
    desc: "Manajemen profil, sejarah, & lini masa",
    keywords: ["sejarah", "lini masa", "timeline", "tahun berdiri"],
    icon: markRaw(PhClockCounterClockwise),
  },
  {
    name: "Profil Singkat Sekolah",
    link: "/admin/sejarah",
    desc: "NPSN, Akreditasi, Lokasi, Status, Foto Gedung",
    parent: "Sejarah",
    keywords: ["profil", "npsn", "akreditasi", "lokasi"],
    icon: markRaw(PhBuildings),
  },
  {
    name: "Visi & Misi",
    link: "/admin/visi-misi",
    desc: "Manajemen visi & misi utama sekolah",
    keywords: ["visi", "misi", "tujuan"],
    icon: markRaw(PhTarget),
  },
  {
    name: "Sambutan Kepala Sekolah",
    link: "/admin/visi-misi",
    desc: "Teks sambutan kepala sekolah",
    parent: "Visi & Misi",
    keywords: ["sambutan", "kepala sekolah", "kepsek", "pidato"],
    icon: markRaw(PhMegaphone),
  },
  {
    name: "Fasilitas",
    link: "/admin/fasilitas",
    desc: "Manajemen fasilitas sekolah",
    keywords: ["fasilitas", "sarana", "prasarana", "gedung", "lab", "perpustakaan"],
    icon: markRaw(PhBuildings),
  },

  // Master Data
  {
    name: "Data Guru & Staf",
    link: "/admin/guru-staf",
    desc: "Manajemen tenaga pendidik & kependidikan",
    keywords: ["guru", "staf", "pegawai", "pendidik", "kependidikan", "nip", "jabatan"],
    icon: markRaw(PhChalkboardTeacher),
  },
  {
    name: "Data Kelas",
    link: "/admin/data-kelas",
    desc: "Manajemen data kelas siswa",
    keywords: ["kelas", "rombel", "ruang"],
    icon: markRaw(PhChalkboard),
  },
  {
    name: "Data Siswa",
    link: "/admin/data-siswa",
    desc: "Manajemen data peserta didik",
    keywords: ["siswa", "murid", "peserta didik", "nisn"],
    icon: markRaw(PhGraduationCap),
  },
  {
    name: "Tracking Alumni",
    link: "/admin/data-alumni",
    desc: "Data status karir/kuliah lulusan",
    keywords: ["alumni", "lulusan", "tracer study", "kuliah", "kerja"],
    icon: markRaw(PhBriefcase),
  },
  {
    name: "Peta Persebaran Alumni",
    link: "/admin/data-alumni",
    desc: "Titik lokasi kampus/instansi lulusan",
    parent: "Data Alumni",
    keywords: ["peta", "persebaran", "lokasi alumni", "kampus", "ptn", "universitas"],
    icon: markRaw(PhMapPin),
  },

  // Akademik & Kesiswaan
  {
    name: "Mata Pelajaran (Kurikulum)",
    link: "/admin/kurikulum",
    desc: "Manajemen silabus & mata pelajaran",
    keywords: ["kurikulum", "mata pelajaran", "mapel", "silabus"],
    icon: markRaw(PhBooks),
  },
  {
    name: "Profil Pelajar Pancasila",
    link: "/admin/kurikulum",
    desc: "Manajemen pilar karakter pendidikan",
    parent: "Kurikulum",
    keywords: ["profil pelajar pancasila", "pancasila", "karakter", "pilar"],
    icon: markRaw(PhStar),
  },
  {
    name: "Program Jurusan",
    link: "/admin/program-jurusan",
    desc: "Manajemen program jurusan",
    keywords: ["jurusan", "program", "peminatan", "ipa", "ips", "bahasa"],
    icon: markRaw(PhBookBookmark),
  },
  {
    name: "Jadwal Pelajaran",
    link: "/admin/jadwal-pelajaran",
    desc: "Manajemen jadwal kelas",
    keywords: ["jadwal", "pelajaran", "roster"],
    icon: markRaw(PhCalendar),
  },
  {
    name: "Ekstrakurikuler",
    link: "/admin/ekstrakurikuler",
    desc: "Manajemen ekstrakurikuler & klub",
    keywords: ["ekskul", "ekstrakurikuler", "klub", "kegiatan", "pramuka"],
    icon: markRaw(PhActivity),
  },
  {
    name: "Prestasi Siswa",
    link: "/admin/prestasi",
    desc: "Manajemen pencapaian & penghargaan",
    keywords: ["prestasi", "penghargaan", "juara", "lomba", "piala"],
    icon: markRaw(PhTrophy),
  },

  // Informasi & Publikasi
  {
    name: "Agenda Sekolah",
    link: "/admin/agenda",
    desc: "Manajemen jadwal agenda & acara",
    keywords: ["agenda", "kalender", "kegiatan", "acara", "event"],
    icon: markRaw(PhCalendarCheck),
  },
  {
    name: "Informasi PPDB",
    link: "/admin/info-ppdb",
    desc: "Manajemen pendaftaran siswa baru",
    keywords: ["ppdb", "pendaftaran", "siswa baru", "penerimaan"],
    icon: markRaw(PhClipboardText),
  },
  {
    name: "Waktu Pembukaan PPDB",
    link: "/admin/info-ppdb",
    desc: "Countdown/Waktu PPDB",
    parent: "Info PPDB",
    keywords: ["waktu", "pembukaan", "countdown", "jadwal ppdb"],
    icon: markRaw(PhHourglass),
  },
  {
    name: "Syarat & Alur PPDB",
    link: "/admin/info-ppdb",
    desc: "Syarat dan langkah daftar",
    parent: "Info PPDB",
    keywords: ["syarat", "alur", "langkah", "pendaftaran"],
    icon: markRaw(PhListChecks),
  },
  {
    name: "Jalur Pendaftaran PPDB",
    link: "/admin/info-ppdb",
    desc: "Kuota & jalur daftar",
    parent: "Info PPDB",
    keywords: ["jalur", "kuota", "zonasi", "prestasi", "afirmasi"],
    icon: markRaw(PhSignpost),
  },
  {
    name: "FAQ & Brosur PPDB",
    link: "/admin/info-ppdb",
    desc: "Pertanyaan umum & dokumen pendaftaran",
    parent: "Info PPDB",
    keywords: ["faq", "tanya jawab", "brosur", "dokumen"],
    icon: markRaw(PhQuestion),
  },

  {
    name: "Berita & Artikel",
    link: "/admin/berita",
    desc: "Manajemen publikasi berita",
    keywords: ["berita", "artikel", "pengumuman", "publikasi", "blog", "post"],
    icon: markRaw(PhNewspaper),
  },
  {
    name: "Galeri Foto & Video",
    link: "/admin/galeri",
    desc: "Manajemen media publikasi",
    keywords: ["galeri", "foto", "video", "album", "dokumentasi"],
    icon: markRaw(PhImages),
  },
  {
    name: "Unduhan File",
    link: "/admin/unduhan",
    desc: "Manajemen dokumen dan file publik",
    keywords: ["unduhan", "file", "download", "dokumen", "pdf"],
    icon: markRaw(PhDownloadSimple),
  },
  {
    name: "Validasi Konten",
    link: "/admin/validasi-konten",
    desc: "Validasi konten menunggu persetujuan",
    keywords: ["validasi", "konten", "persetujuan", "approval", "draft"],
    icon: markRaw(PhCheckCircle),
  },

  // Sistem & Pengaturan
  {
    name: "Pengguna Sistem",
    link: "/admin/pengguna",
    desc: "Manajemen akses & pengguna sistem",
    keywords: ["pengguna", "user", "admin", "akun", "akses", "password", "role"],
    icon: markRaw(PhUsers),
  },
  {
    name: "Pengaturan Umum",
    link: "/admin/pengaturan",
    desc: "Nama sekolah, alamat, kontak, logo",
    keywords: [
      "pengaturan",
      "umum",
      "setting",
      "nama",
      "alamat",
      "kontak",
      "logo",
      "favicon",
      "maps",
      "telepon",
      "email",
    ],
    icon: markRaw(PhGear),
  },
  {
    name: "Sosial Media",
    link: "/admin/pengaturan",
    desc: "Link sosmed sekolah",
    parent: "Pengaturan Umum",
    keywords: [
      "sosial media",
      "sosmed",
      "instagram",
      "facebook",
      "youtube",
      "tiktok",
      "twitter",
      "x",
    ],
    icon: markRaw(PhShareNetwork),
  },
  {
    name: "Tampilan Banner / Header",
    link: "/admin/pengaturan",
    desc: "Gambar latar tiap halaman",
    parent: "Pengaturan Umum",
    keywords: ["tampilan", "banner", "header", "gambar latar", "background", "cover"],
    icon: markRaw(PhImageSquare),
  },
];

const handleSearch = () => {
  if (!searchQuery.value.trim()) {
    searchResults.value = [];
    isSearchOpen.value = false;
    highlightedIndex.value = -1;
    return;
  }

  // Memecah query per kata untuk pencarian multi-kata yang pintar
  const queryWords = searchQuery.value
    .toLowerCase()
    .split(/\s+/)
    .filter((w) => w);

  searchResults.value = adminMenus.filter((menu) => {
    const searchableText = [
      menu.name,
      menu.desc,
      menu.parent || "",
      ...(menu.keywords || []),
    ]
      .join(" ")
      .toLowerCase();

    // Mengembalikan true HANYA JIKA semua kata kunci pencarian ada di dalam menu
    return queryWords.every((word) => searchableText.includes(word));
  });

  isSearchOpen.value = true;
  highlightedIndex.value = -1;
};

const navigateTo = (link) => {
  router.push(link);
  searchQuery.value = "";
  isSearchOpen.value = false;
  highlightedIndex.value = -1;
};

const closeSearch = () => {
  isSearchOpen.value = false;
  highlightedIndex.value = -1;
};

const handleKeyDown = (e) => {
  if (!isSearchOpen.value || searchResults.value.length === 0) return;

  if (e.key === "ArrowDown") {
    e.preventDefault();
    highlightedIndex.value =
      highlightedIndex.value < searchResults.value.length - 1
        ? highlightedIndex.value + 1
        : 0;
  } else if (e.key === "ArrowUp") {
    e.preventDefault();
    highlightedIndex.value =
      highlightedIndex.value > 0
        ? highlightedIndex.value - 1
        : searchResults.value.length - 1;
  } else if (e.key === "Enter") {
    e.preventDefault();
    if (
      highlightedIndex.value >= 0 &&
      highlightedIndex.value < searchResults.value.length
    ) {
      navigateTo(searchResults.value[highlightedIndex.value].link);
    } else if (searchResults.value.length > 0) {
      navigateTo(searchResults.value[0].link);
    }
  } else if (e.key === "Escape") {
    closeSearch();
  }
};

const fetchProfile = async () => {
  try {
    const response = await api.get("/api/user");
    currentUser.value = response.data;
    fetchNotifications();
  } catch (error) {
    console.error("Gagal mengambil profil:", error);
  }
};

onMounted(() => {
  if (
    localStorage.getItem("color-theme") === "dark" ||
    (!("color-theme" in localStorage) &&
      window.matchMedia("(prefers-color-scheme: dark)").matches)
  ) {
    isDarkMode.value = true;
  } else {
    isDarkMode.value = false;
  }

  document.addEventListener("click", closeNotificationOutside);
  fetchProfile();
});

onBeforeUnmount(() => {
  document.removeEventListener("click", closeNotificationOutside);
});

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;
  if (isDarkMode.value) {
    document.documentElement.classList.add("dark");
    localStorage.setItem("color-theme", "dark");
  } else {
    document.documentElement.classList.remove("dark");
    localStorage.setItem("color-theme", "light");
  }
};

const handleLogout = async () => {
  try {
    await api.post("/logout");
  } catch (error) {
    console.error("Gagal logout:", error);
  } finally {
    localStorage.removeItem("isLoggedIn");
    localStorage.removeItem("user_role");
    router.push("/login");
  }
};
</script>

<template>
  <header
    class="flex items-center justify-between h-20 px-6 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 shadow-sm"
  >
    <div class="flex items-center">
      <button
        @click="emit('toggle-sidebar')"
        class="text-gray-600 dark:text-gray-300 focus:outline-none mr-4 lg:hidden"
      >
        <PhList :size="24" />
      </button>
      <div
        v-if="currentUser.role === 'super_admin'"
        class="relative hidden md:block md:w-64 lg:w-96"
      >
        <PhMagnifyingGlass
          class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500"
        />
        <input
          type="text"
          v-model="searchQuery"
          @input="handleSearch"
          @focus="handleSearch"
          @blur="closeSearch"
          @keydown="handleKeyDown"
          placeholder="Cari menu atau fitur..."
          class="w-full pl-11 pr-4 py-2 rounded-lg border-transparent bg-gray-100 dark:bg-slate-700/50 text-sm text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:border-transparent focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all"
        />

        <!-- Search Results Dropdown -->
        <div
          v-if="isSearchOpen && searchQuery.trim()"
          class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-gray-100 dark:border-slate-700 z-50 max-h-80 overflow-y-auto custom-scrollbar"
        >
          <ul v-if="searchResults.length > 0" class="py-2">
            <li v-for="(result, index) in searchResults" :key="index">
              <button
                @mousedown.prevent="navigateTo(result.link)"
                class="w-full text-left px-4 py-2 transition-colors"
                :class="
                  highlightedIndex === index
                    ? 'bg-blue-50 dark:bg-slate-700'
                    : 'hover:bg-gray-50 dark:hover:bg-slate-700/50'
                "
              >
                <div class="flex items-center gap-3">
                  <div
                    class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg transition-colors"
                    :class="
                      highlightedIndex === index
                        ? 'bg-blue-100 dark:bg-slate-600 text-blue-600 dark:text-blue-400'
                        : 'bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400'
                    "
                  >
                    <component v-if="result.icon" :is="result.icon" :size="18" />
                    <PhMagnifyingGlass v-else :size="18" />
                  </div>
                  <div>
                    <div
                      class="text-sm font-semibold"
                      :class="
                        highlightedIndex === index
                          ? 'text-blue-600 dark:text-blue-400'
                          : 'text-gray-800 dark:text-gray-200'
                      "
                    >
                      {{ result.name }}
                    </div>
                    <div
                      class="text-xs mt-0.5 line-clamp-1"
                      :class="
                        highlightedIndex === index
                          ? 'text-blue-500 dark:text-blue-300'
                          : 'text-gray-500 dark:text-gray-400'
                      "
                    >
                      <span
                        v-if="result.parent"
                        class="font-semibold mr-1"
                        :class="
                          highlightedIndex === index
                            ? 'text-blue-600 dark:text-blue-400'
                            : 'text-blue-500 dark:text-blue-400'
                        "
                      >
                        [{{ result.parent }}]
                      </span>
                      {{ result.desc }}
                    </div>
                  </div>
                </div>
              </button>
            </li>
          </ul>
          <div
            v-else
            class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center"
          >
            Menu atau fitur tidak ditemukan.
          </div>
        </div>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <button
        @click="toggleDarkMode"
        class="p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
        title="Ganti Tema"
      >
        <PhMoon v-if="!isDarkMode" :size="20" />
        <PhSun v-else :size="20" />
      </button>
      <div class="relative" ref="notifDropdown">
        <button
          @click="toggleNotification"
          class="relative p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors focus:outline-none"
        >
          <PhBell :size="20" />
          <span
            v-if="notifications.length > 0"
            class="absolute top-1 right-1 flex items-center justify-center w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full border-2 border-white dark:border-slate-800"
          >
            {{ notifications.length }}
          </span>
        </button>

        <!-- Dropdown Notifications -->
        <div
          v-if="isNotificationOpen"
          class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-100 dark:border-slate-700 z-50 overflow-hidden flex flex-col"
        >
          <div
            class="p-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="font-bold text-gray-800 dark:text-white">Notifikasi</h3>
            <button
              v-if="notifications.length > 0"
              @click="markAllRead"
              class="text-xs text-blue-600 dark:text-blue-400 hover:underline focus:outline-none"
            >
              Tandai sudah dibaca
            </button>
          </div>

          <div class="overflow-y-auto max-h-80 custom-scrollbar">
            <template v-if="notifications.length > 0">
              <div
                v-for="notif in notifications"
                :key="notif.id"
                @click="
                  isNotificationOpen = false;
                  $router.push(notif.link);
                "
                class="relative flex items-start p-4 border-b border-gray-50 dark:border-slate-700/50 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors cursor-pointer group"
              >
                <div
                  class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center mt-1 mr-3"
                  :class="
                    notif.type === 'error'
                      ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400'
                      : 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400'
                  "
                >
                  <PhCheckCircle v-if="notif.type === 'warning'" class="w-4 h-4" />
                  <PhWarningCircle v-else class="w-4 h-4" />
                </div>
                <div class="pr-6">
                  <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                    {{ notif.title }}
                  </h4>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                    {{ notif.message }}
                  </p>
                  <span class="text-[10px] text-gray-400 mt-2 block">{{
                    notif.time
                  }}</span>
                </div>
                <button
                  @click.stop="dismissNotification(notif.id)"
                  class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100"
                  title="Hapus Notifikasi"
                >
                  <PhX class="w-4 h-4" />
                </button>
              </div>
            </template>
            <div v-else class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
              Tidak ada notifikasi baru.
            </div>
          </div>
        </div>
      </div>
      <div class="relative group">
        <button class="flex items-center gap-2">
          <img
            :src="
              currentUser.avatar ||
              'https://ui-avatars.com/api/?name=' +
                encodeURIComponent(
                  currentUser.name === 'Memuat...' ? 'Admin' : currentUser.name
                ) +
                '&background=0D8ABC&color=fff'
            "
            alt="Admin"
            class="w-10 h-10 rounded-full object-cover border-2 border-blue-500"
          />
          <div class="hidden md:block text-left">
            <div class="font-semibold text-sm text-gray-800 dark:text-white">
              {{ currentUser.name }}
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
              {{ currentUser.role === "super_admin" ? "Super Admin" : "Admin Biasa" }}
            </div>
          </div>
          <PhCaretDown :size="16" class="text-gray-500 hidden md:block" />
        </button>
        <div
          class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-md shadow-lg py-1 hidden group-hover:block z-20 border border-gray-100 dark:border-slate-700"
        >
          <a
            href="#"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700"
            >Profil Saya</a
          >
          <a
            href="#"
            @click.prevent="handleLogout"
            class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/50"
            >Keluar</a
          >
        </div>
      </div>
    </div>
  </header>
</template>

<style scoped>
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
