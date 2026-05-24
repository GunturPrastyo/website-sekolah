<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import {
  PhMagnifyingGlass,
  PhBell,
  PhCaretDown,
  PhList,
  PhSun,
  PhMoon,
} from "@phosphor-icons/vue";

const emit = defineEmits(["toggle-sidebar"]);

const isDarkMode = ref(false);
const router = useRouter();

const searchQuery = ref("");
const searchResults = ref([]);
const isSearchOpen = ref(false);
const highlightedIndex = ref(-1);

const adminMenus = [
  // Dashboard
  {
    name: "Dashboard",
    link: "/admin/dashboard",
    desc: "Ringkasan data & aktivitas sistem",
    keywords: ["home", "beranda", "statistik", "grafik pengunjung"],
  },

  // Profil Sekolah
  {
    name: "Sejarah & Lini Masa",
    link: "/admin/sejarah",
    desc: "Manajemen profil, sejarah, & lini masa",
    keywords: ["sejarah", "lini masa", "timeline", "tahun berdiri"],
  },
  {
    name: "Profil Singkat Sekolah",
    link: "/admin/sejarah",
    desc: "NPSN, Akreditasi, Lokasi, Status, Foto Gedung",
    parent: "Sejarah",
    keywords: ["profil", "npsn", "akreditasi", "lokasi"],
  },
  {
    name: "Visi & Misi",
    link: "/admin/visi-misi",
    desc: "Manajemen visi & misi utama sekolah",
    keywords: ["visi", "misi", "tujuan"],
  },
  {
    name: "Sambutan Kepala Sekolah",
    link: "/admin/visi-misi",
    desc: "Teks sambutan kepala sekolah",
    parent: "Visi & Misi",
    keywords: ["sambutan", "kepala sekolah", "kepsek", "pidato"],
  },
  {
    name: "Fasilitas",
    link: "/admin/fasilitas",
    desc: "Manajemen fasilitas sekolah",
    keywords: ["fasilitas", "sarana", "prasarana", "gedung", "lab", "perpustakaan"],
  },

  // Master Data
  {
    name: "Data Guru & Staf",
    link: "/admin/guru-staf",
    desc: "Manajemen tenaga pendidik & kependidikan",
    keywords: ["guru", "staf", "pegawai", "pendidik", "kependidikan", "nip", "jabatan"],
  },
  {
    name: "Data Kelas",
    link: "/admin/data-kelas",
    desc: "Manajemen data kelas siswa",
    keywords: ["kelas", "rombel", "ruang"],
  },
  {
    name: "Data Siswa",
    link: "/admin/data-siswa",
    desc: "Manajemen data peserta didik",
    keywords: ["siswa", "murid", "peserta didik", "nisn"],
  },
  {
    name: "Tracking Alumni",
    link: "/admin/data-alumni",
    desc: "Data status karir/kuliah lulusan",
    keywords: ["alumni", "lulusan", "tracer study", "kuliah", "kerja"],
  },
  {
    name: "Peta Persebaran Alumni",
    link: "/admin/data-alumni",
    desc: "Titik lokasi kampus/instansi lulusan",
    parent: "Data Alumni",
    keywords: ["peta", "persebaran", "lokasi alumni", "kampus", "ptn", "universitas"],
  },

  // Akademik & Kesiswaan
  {
    name: "Mata Pelajaran (Kurikulum)",
    link: "/admin/kurikulum",
    desc: "Manajemen silabus & mata pelajaran",
    keywords: ["kurikulum", "mata pelajaran", "mapel", "silabus"],
  },
  {
    name: "Profil Pelajar Pancasila",
    link: "/admin/kurikulum",
    desc: "Manajemen pilar karakter pendidikan",
    parent: "Kurikulum",
    keywords: ["profil pelajar pancasila", "pancasila", "karakter", "pilar"],
  },
  {
    name: "Program Jurusan",
    link: "/admin/program-jurusan",
    desc: "Manajemen program jurusan",
    keywords: ["jurusan", "program", "peminatan", "ipa", "ips", "bahasa"],
  },
  {
    name: "Jadwal Pelajaran",
    link: "/admin/jadwal-pelajaran",
    desc: "Manajemen jadwal kelas",
    keywords: ["jadwal", "pelajaran", "roster"],
  },
  {
    name: "Ekstrakurikuler",
    link: "/admin/ekstrakurikuler",
    desc: "Manajemen ekstrakurikuler & klub",
    keywords: ["ekskul", "ekstrakurikuler", "klub", "kegiatan", "pramuka"],
  },
  {
    name: "Prestasi Siswa",
    link: "/admin/prestasi",
    desc: "Manajemen pencapaian & penghargaan",
    keywords: ["prestasi", "penghargaan", "juara", "lomba", "piala"],
  },

  // Informasi & Publikasi
  {
    name: "Agenda Sekolah",
    link: "/admin/agenda",
    desc: "Manajemen jadwal agenda & acara",
    keywords: ["agenda", "kalender", "kegiatan", "acara", "event"],
  },
  {
    name: "Informasi PPDB",
    link: "/admin/info-ppdb",
    desc: "Manajemen pendaftaran siswa baru",
    keywords: ["ppdb", "pendaftaran", "siswa baru", "penerimaan"],
  },
  {
    name: "Waktu Pembukaan PPDB",
    link: "/admin/info-ppdb",
    desc: "Countdown/Waktu PPDB",
    parent: "Info PPDB",
    keywords: ["waktu", "pembukaan", "countdown", "jadwal ppdb"],
  },
  {
    name: "Syarat & Alur PPDB",
    link: "/admin/info-ppdb",
    desc: "Syarat dan langkah daftar",
    parent: "Info PPDB",
    keywords: ["syarat", "alur", "langkah", "pendaftaran"],
  },
  {
    name: "Jalur Pendaftaran PPDB",
    link: "/admin/info-ppdb",
    desc: "Kuota & jalur daftar",
    parent: "Info PPDB",
    keywords: ["jalur", "kuota", "zonasi", "prestasi", "afirmasi"],
  },
  {
    name: "FAQ & Brosur PPDB",
    link: "/admin/info-ppdb",
    desc: "Pertanyaan umum & dokumen pendaftaran",
    parent: "Info PPDB",
    keywords: ["faq", "tanya jawab", "brosur", "dokumen"],
  },

  {
    name: "Berita & Artikel",
    link: "/admin/berita",
    desc: "Manajemen publikasi berita",
    keywords: ["berita", "artikel", "pengumuman", "publikasi", "blog", "post"],
  },
  {
    name: "Galeri Foto & Video",
    link: "/admin/galeri",
    desc: "Manajemen media publikasi",
    keywords: ["galeri", "foto", "video", "album", "dokumentasi"],
  },
  {
    name: "Unduhan File",
    link: "/admin/unduhan",
    desc: "Manajemen dokumen dan file publik",
    keywords: ["unduhan", "file", "download", "dokumen", "pdf"],
  },
  {
    name: "Validasi Konten",
    link: "/admin/validasi-konten",
    desc: "Validasi konten menunggu persetujuan",
    keywords: ["validasi", "konten", "persetujuan", "approval", "draft"],
  },

  // Sistem & Pengaturan
  {
    name: "Pengguna Sistem",
    link: "/admin/pengguna",
    desc: "Manajemen akses & pengguna sistem",
    keywords: ["pengguna", "user", "admin", "akun", "akses", "password", "role"],
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
  },
  {
    name: "Tampilan Banner / Header",
    link: "/admin/pengaturan",
    desc: "Gambar latar tiap halaman",
    parent: "Pengaturan Umum",
    keywords: ["tampilan", "banner", "header", "gambar latar", "background", "cover"],
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
</script>

<template>
  <header
    class="flex items-center justify-between h-20 px-6 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 shadow-sm"
  >
    <div class="flex items-center">
      <button
        @click="emit('toggle-sidebar')"
        class="text-gray-600 dark:text-gray-300 focus:outline-none mr-4"
      >
        <PhList :size="24" />
      </button>
      <div class="relative hidden md:block md:w-64 lg:w-96">
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
                  class="text-xs mt-0.5"
                  :class="
                    highlightedIndex === index
                      ? 'text-blue-500 dark:text-blue-300'
                      : 'text-gray-500 dark:text-gray-400'
                  "
                >
                  <span
                    v-if="result.parent"
                    class="font-semibold"
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
      <button
        class="p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
      >
        <PhBell :size="20" />
      </button>
      <div class="relative group">
        <button class="flex items-center gap-2">
          <img
            src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop"
            alt="Admin"
            class="w-10 h-10 rounded-full object-cover border-2 border-blue-500"
          />
          <div class="hidden md:block text-left">
            <div class="font-semibold text-sm text-gray-800 dark:text-white">
              Budi Santoso
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Admin Utama</div>
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
