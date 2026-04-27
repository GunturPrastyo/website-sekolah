<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import { createIcons, icons } from "lucide";
import PageHeader from "@/components/PageHeader.vue";

const activeGrade = ref("10");
const activeMajor = ref("ipa");
const expandedSubject = ref(null);

const changeGrade = (id) => {
  activeGrade.value = id;
  expandedSubject.value = null; // Reset accordion saat ganti kelas
};

const majors = ref([
  { id: "ipa", name: "MIPA", desc: "Matematika & Ilmu Pengetahuan Alam" },
  { id: "ips", name: "IPS", desc: "Ilmu Pengetahuan Sosial" },
  { id: "bahasa", name: "Bahasa", desc: "Ilmu Bahasa & Budaya" },
]);

const changeMajor = (id) => {
  activeMajor.value = id;
  expandedSubject.value = null; // Reset accordion saat ganti jurusan
};

const toggleSubject = (id) => {
  expandedSubject.value = expandedSubject.value === id ? null : id;
};

const grades = ref([
  { id: "10", name: "Kelas X (Fase E)", desc: "Peminatan Dasar & Umum" },
  { id: "11", name: "Kelas XI (Fase F)", desc: "Peminatan Lanjutan" },
  { id: "12", name: "Kelas XII (Fase F)", desc: "Pendalaman & Persiapan UTBK" },
]);

// Base Kurikulum Kelas 10 (Fase E - Seragam untuk semua jurusan)
const grade10Base = [
  {
    category: "Muatan Nasional (Wajib)",
    subjects: [
      {
        id: "10-w-1",
        name: "Pendidikan Agama dan Budi Pekerti",
        icon: "book",
        color: "text-green-600 dark:text-green-400",
        bg: "bg-green-100 dark:bg-green-900/30",
        desc:
          "Mempelajari nilai-nilai spiritual, toleransi, dan pembentukan karakter akhlak mulia.",
        topics: [
          "Hakikat Penciptaan Manusia",
          "Toleransi Beragama di Indonesia",
          "Sejarah Perkembangan Agama",
          "Etika dan Budi Pekerti Abad 21",
        ],
      },
      {
        id: "10-w-2",
        name: "Pendidikan Pancasila",
        icon: "award",
        color: "text-red-600 dark:text-red-400",
        bg: "bg-red-100 dark:bg-red-900/30",
        desc: "Pendalaman ideologi negara dan pembentukan Profil Pelajar Pancasila.",
        topics: [
          "Sejarah Perumusan Pancasila",
          "Konstitusi dan UUD 1945",
          "Bhinneka Tunggal Ika dalam Praktik",
          "Sistem Demokrasi Indonesia",
        ],
      },
      {
        id: "10-w-3",
        name: "Bahasa Indonesia",
        icon: "book-open",
        color: "text-blue-600 dark:text-blue-400",
        bg: "bg-blue-100 dark:bg-blue-900/30",
        desc:
          "Peningkatan literasi, keterampilan menulis, dan analisis literatur sastra.",
        topics: [
          "Menulis Teks Laporan Hasil Observasi",
          "Menganalisis Teks Anekdot",
          "Menulis Teks Hikayat",
          "Negosiasi dan Debat",
        ],
      },
      {
        id: "10-w-4",
        name: "Matematika Dasar",
        icon: "calculator",
        color: "text-purple-600 dark:text-purple-400",
        bg: "bg-purple-100 dark:bg-purple-900/30",
        desc: "Konsep dasar matematika, aljabar, dan logika komputasional dasar.",
        topics: [
          "Eksponen dan Logaritma",
          "Barisan dan Deret",
          "Sistem Persamaan Linear",
          "Fungsi Kuadrat dan Grafiknya",
        ],
      },
    ],
  },
  {
    category: "Muatan Pilihan (Fase E)",
    subjects: [
      {
        id: "10-p-1",
        name: "Informatika",
        icon: "monitor",
        color: "text-cyan-600 dark:text-cyan-400",
        bg: "bg-cyan-100 dark:bg-cyan-900/30",
        desc: "Pengenalan teknologi informasi, logika pemrograman, dan literasi digital.",
        topics: [
          "Berpikir Komputasional",
          "Teknologi Informasi & Komunikasi",
          "Sistem Komputer Dasar",
          "Jaringan Komputer dan Internet",
        ],
      },
      {
        id: "10-p-2",
        name: "Ilmu Pengetahuan Alam & Sosial (IPAS)",
        icon: "flask-conical",
        color: "text-emerald-600 dark:text-emerald-400",
        bg: "bg-emerald-100 dark:bg-emerald-900/30",
        desc: "Integrasi dasar Fisika, Kimia, Biologi, Geografi, dan Sosiologi.",
        topics: [
          "Pengukuran dalam Kerja Ilmiah",
          "Gejala Alam dan Sosial",
          "Kimia Hijau",
          "Interaksi Sosial",
        ],
      },
    ],
  },
];

// Data Dummy Silabus Digital per Kelas & Jurusan
const curriculumData = ref({
  10: {
    ipa: grade10Base,
    ips: grade10Base,
    bahasa: grade10Base,
  },
  11: {
    ipa: [
      {
        category: "Kelompok Mata Pelajaran Pilihan (Sains & Teknologi)",
        subjects: [
          {
            id: "11-ipa-1",
            name: "Fisika Lanjutan",
            icon: "zap",
            color: "text-amber-600 dark:text-amber-400",
            bg: "bg-amber-100 dark:bg-amber-900/30",
            desc: "Pendalaman mekanika, termodinamika, dan gelombang.",
            topics: [
              "Dinamika Rotasi dan Kesetimbangan",
              "Elastisitas Bahan",
              "Fluida Statis dan Dinamis",
              "Suhu dan Kalor",
            ],
          },
          {
            id: "11-ipa-2",
            name: "Biologi Terapan",
            icon: "leaf",
            color: "text-green-600 dark:text-green-400",
            bg: "bg-green-100 dark:bg-green-900/30",
            desc: "Studi anatomi, fisiologi, dan ekosistem makhluk hidup tingkat lanjut.",
            topics: [
              "Struktur dan Fungsi Sel",
              "Sistem Jaringan Tumbuhan",
              "Sistem Gerak Manusia",
              "Sistem Peredaran Darah",
            ],
          },
        ],
      },
    ],
    ips: [
      {
        category: "Kelompok Mata Pelajaran Pilihan (Sosiologi & Humaniora)",
        subjects: [
          {
            id: "11-ips-1",
            name: "Ekonomi Pembangunan",
            icon: "pie-chart",
            color: "text-emerald-600 dark:text-emerald-400",
            bg: "bg-emerald-100 dark:bg-emerald-900/30",
            desc: "Memahami makroekonomi, kebijakan fiskal, dan pembangunan wilayah.",
            topics: [
              "Pendapatan Nasional",
              "Pertumbuhan & Pembangunan Ekonomi",
              "Ketenagakerjaan",
              "Indeks Harga & Inflasi",
            ],
          },
          {
            id: "11-ips-2",
            name: "Sosiologi Masyarakat",
            icon: "users",
            color: "text-orange-600 dark:text-orange-400",
            bg: "bg-orange-100 dark:bg-orange-900/30",
            desc: "Kajian mendalam tentang struktur masyarakat dan dinamika sosial.",
            topics: [
              "Kelompok Sosial",
              "Permasalahan Sosial",
              "Kesetaraan Sosial",
              "Konflik dan Resolusi",
            ],
          },
        ],
      },
    ],
    bahasa: [
      {
        category: "Kelompok Mata Pelajaran Pilihan (Bahasa & Budaya)",
        subjects: [
          {
            id: "11-bhs-1",
            name: "Sastra Indonesia Lanjutan",
            icon: "book-open",
            color: "text-rose-600 dark:text-rose-400",
            bg: "bg-rose-100 dark:bg-rose-900/30",
            desc: "Analisis karya sastra klasik hingga modern, puisi, dan prosa.",
            topics: [
              "Kritik Sastra",
              "Menulis Novel Pendek",
              "Apresiasi Puisi",
              "Pementasan Drama",
            ],
          },
          {
            id: "11-bhs-2",
            name: "Bahasa Asing (Jepang/Mandarin)",
            icon: "languages",
            color: "text-cyan-600 dark:text-cyan-400",
            bg: "bg-cyan-100 dark:bg-cyan-900/30",
            desc: "Penguasaan dasar percakapan dan tata bahasa asing pilihan.",
            topics: [
              "Huruf Dasar (Hiragana/Katakana)",
              "Perkenalan Diri",
              "Keluarga & Kegiatan Sehari-hari",
              "Budaya & Etika Berkomunikasi",
            ],
          },
        ],
      },
    ],
  },
  12: {
    ipa: [
      {
        category: "Kelompok Persiapan UTBK (Sains & Teknologi)",
        subjects: [
          {
            id: "12-ipa-1",
            name: "Matematika Peminatan",
            icon: "sigma",
            color: "text-indigo-600 dark:text-indigo-400",
            bg: "bg-indigo-100 dark:bg-indigo-900/30",
            desc: "Kalkulus lanjutan, trigonometri kompleks, dan geometri ruang.",
            topics: [
              "Limit Fungsi Trigonometri",
              "Turunan Fungsi Trigonometri",
              "Distribusi Binomial",
              "Geometri Ruang 3D",
            ],
          },
          {
            id: "12-ipa-2",
            name: "Kimia Analisis",
            icon: "flask-conical",
            color: "text-pink-600 dark:text-pink-400",
            bg: "bg-pink-100 dark:bg-pink-900/30",
            desc: "Reaksi kimia organik, termokimia, dan elektrokimia.",
            topics: [
              "Sifat Koligatif Larutan",
              "Reaksi Redoks",
              "Sel Elektrokimia",
              "Senyawa Karbon Organik",
            ],
          },
        ],
      },
    ],
    ips: [
      {
        category: "Kelompok Persiapan UTBK (Soshum)",
        subjects: [
          {
            id: "12-ips-1",
            name: "Geografi Regional",
            icon: "map",
            color: "text-teal-600 dark:text-teal-400",
            bg: "bg-teal-100 dark:bg-teal-900/30",
            desc: "Pemetaan spasial, sistem informasi geografis, dan tata ruang.",
            topics: [
              "Konsep Wilayah dan Tata Ruang",
              "Interaksi Desa-Kota",
              "Pemanfaatan Peta & SIG",
              "Kerja Sama Negara Maju & Berkembang",
            ],
          },
          {
            id: "12-ips-2",
            name: "Sejarah Dunia Modern",
            icon: "hourglass",
            color: "text-amber-700 dark:text-amber-500",
            bg: "bg-amber-100 dark:bg-amber-900/30",
            desc: "Analisis sejarah kontemporer dan dampaknya pada geopolitik.",
            topics: [
              "Perang Dingin",
              "Organisasi Global & Regional",
              "Sejarah Kontemporer Dunia",
              "Perkembangan IPTEK Era Globalisasi",
            ],
          },
        ],
      },
    ],
    bahasa: [
      {
        category: "Kelompok Persiapan Ujian Lanjutan (Sastra)",
        subjects: [
          {
            id: "12-bhs-1",
            name: "Bahasa Inggris Lanjut (TOEFL Prep)",
            icon: "globe",
            color: "text-indigo-600 dark:text-indigo-400",
            bg: "bg-indigo-100 dark:bg-indigo-900/30",
            desc:
              "Penguasaan tata bahasa kompleks, reading comprehension, dan listening.",
            topics: [
              "Analytical Exposition Text",
              "Explanation Text",
              "Discussion Text",
              "Job Application Letters",
            ],
          },
          {
            id: "12-bhs-2",
            name: "Antropologi Budaya",
            icon: "users-2",
            color: "text-rose-700 dark:text-rose-500",
            bg: "bg-rose-100 dark:bg-rose-900/30",
            desc: "Kajian etnografi dan pewarisan budaya masyarakat lokal dan global.",
            topics: [
              "Keberagaman Budaya Lokal",
              "Sistem Religi dan Kepercayaan",
              "Pewarisan Nilai Budaya",
              "Dampak Globalisasi terhadap Budaya",
            ],
          },
        ],
      },
    ],
  },
});

const currentSyllabus = computed(() => {
  if (!curriculumData.value[activeGrade.value]) return [];
  return curriculumData.value[activeGrade.value][activeMajor.value] || [];
});

onMounted(() => {
  nextTick(() => {
    createIcons();
    createIcons({ icons });
  });
});
</script>

<template>
  <div>
    <PageHeader
      badge="Akademik"
      title="Silabus & Kurikulum Digital"
      description="Jelajahi struktur kurikulum interaktif kami yang dirancang khusus untuk membekali siswa dengan kompetensi abad 21 (Kurikulum Merdeka)."
    />

    <!-- Main Content Section -->
    <section class="pt-8 md:pt-12 pb-12 px-6 bg-gray-50 dark:bg-slate-900">
      <div class="container mx-auto max-w-6xl">
        <!-- Profil Pelajar Pancasila -->
        <div>
          <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-4">
              Profil Pelajar Pancasila
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
              Kurikulum kami berfokus pada pembentukan karakter siswa yang berlandaskan 6
              dimensi Profil Pelajar Pancasila.
            </p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Dimensi 1 -->
            <div
              class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 flex items-start gap-4 transition-transform hover:-translate-y-1"
            >
              <div
                class="w-12 h-12 shrink-0 rounded-full bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center"
              >
                <i data-lucide="heart" class="w-6 h-6"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-1">
                  Beriman & Berakhlak
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                  Membentuk siswa yang religius dan memiliki etika baik dalam kehidupan
                  sehari-hari.
                </p>
              </div>
            </div>

            <!-- Dimensi 2 -->
            <div
              class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 flex items-start gap-4 transition-transform hover:-translate-y-1"
            >
              <div
                class="w-12 h-12 shrink-0 rounded-full bg-yellow-100 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400 flex items-center justify-center"
              >
                <i data-lucide="globe-2" class="w-6 h-6"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-1">
                  Berkebinekaan Global
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                  Menghargai keberagaman budaya, toleran, dan berwawasan luas di kancah
                  internasional.
                </p>
              </div>
            </div>

            <!-- Dimensi 3 -->
            <div
              class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 flex items-start gap-4 transition-transform hover:-translate-y-1"
            >
              <div
                class="w-12 h-12 shrink-0 rounded-full bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 flex items-center justify-center"
              >
                <i data-lucide="users" class="w-6 h-6"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-1">
                  Bergotong Royong
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                  Mampu berkolaborasi, peduli terhadap sesama, dan berbagi dalam
                  menyelesaikan masalah.
                </p>
              </div>
            </div>

            <!-- Dimensi 4 -->
            <div
              class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 flex items-start gap-4 transition-transform hover:-translate-y-1"
            >
              <div
                class="w-12 h-12 shrink-0 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center"
              >
                <i data-lucide="user-check" class="w-6 h-6"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-1">Mandiri</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                  Bertanggung jawab atas proses dan hasil belajarnya sendiri dengan
                  kesadaran tinggi.
                </p>
              </div>
            </div>

            <!-- Dimensi 5 -->
            <div
              class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 flex items-start gap-4 transition-transform hover:-translate-y-1"
            >
              <div
                class="w-12 h-12 shrink-0 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center"
              >
                <i data-lucide="lightbulb" class="w-6 h-6"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-1">
                  Bernalar Kritis
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                  Mampu memproses informasi secara objektif, mengevaluasi, dan
                  menyimpulkan dengan baik.
                </p>
              </div>
            </div>

            <!-- Dimensi 6 -->
            <div
              class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 flex items-start gap-4 transition-transform hover:-translate-y-1"
            >
              <div
                class="w-12 h-12 shrink-0 rounded-full bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-400 flex items-center justify-center"
              >
                <i data-lucide="palette" class="w-6 h-6"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-1">Kreatif</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                  Mampu memodifikasi dan menghasilkan gagasan, karya, atau tindakan yang
                  orisinal.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Interactive Syllabus Area (Full Width to Footer) -->
    <section
      class="relative w-full py-16 md:py-24 px-6 border-t border-blue-800/30 dark:border-slate-800 bg-center bg-cover overflow-hidden"
    >
      <!-- Background Overlay -->
      <div
        class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-950 to-slate-900/90 dark:from-slate-900/95 dark:to-slate-950/95 backdrop-blur-[2px]"
      ></div>

      <div
        class="container relative z-10 mx-auto max-w-6xl flex flex-col lg:flex-row gap-8 lg:gap-12 items-start"
      >
        <!-- Sidebar Tabs (Tingkat Kelas) -->
        <div class="w-full lg:w-1/3">
          <div class="flex items-center gap-4 mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-white">Jenjang Kelas</h2>
            <div class="h-px bg-white/20 dark:bg-slate-700 flex-1"></div>
          </div>
          <div class="flex flex-col gap-3 mb-10">
            <button
              v-for="grade in grades"
              :key="grade.id"
              @click="changeGrade(grade.id)"
              class="w-full text-left p-5 rounded-lg transition-all duration-300 border-2"
              :class="
                activeGrade === grade.id
                  ? 'bg-white border-white text-blue-600 shadow-xl shadow-black/10 dark:bg-slate-800 dark:border-slate-700 dark:text-white'
                  : 'bg-white/10 backdrop-blur-sm border-white/10 hover:bg-white/20 hover:border-white/30 text-blue-50 dark:bg-slate-800/50 dark:hover:bg-slate-800 dark:border-slate-700/50 dark:text-gray-300 shadow-sm'
              "
            >
              <div class="flex justify-between items-center">
                <div>
                  <h4 class="text-lg font-bold mb-1">{{ grade.name }}</h4>
                  <p class="text-sm opacity-80">{{ grade.desc }}</p>
                </div>
                <i
                  data-lucide="chevron-right"
                  class="w-5 h-5 transition-transform"
                  :class="activeGrade === grade.id ? 'translate-x-1' : ''"
                ></i>
              </div>
            </button>
          </div>

          <!-- Jurusan / Peminatan -->
          <div class="flex items-center gap-4 mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-white">Peminatan / Jurusan</h2>
            <div class="h-px bg-white/20 dark:bg-slate-700 flex-1"></div>
          </div>
          <div class="flex flex-col gap-3">
            <button
              v-for="major in majors"
              :key="major.id"
              @click="changeMajor(major.id)"
              class="w-full text-left p-5 rounded-lg transition-all duration-300 border-2"
              :class="
                activeMajor === major.id
                  ? 'bg-white border-white text-blue-600 shadow-xl shadow-black/10 dark:bg-slate-800 dark:border-slate-700 dark:text-white'
                  : 'bg-white/10 backdrop-blur-sm border-white/10 hover:bg-white/20 hover:border-white/30 text-blue-50 dark:bg-slate-800/50 dark:hover:bg-slate-800 dark:border-slate-700/50 dark:text-gray-300 shadow-sm'
              "
            >
              <div class="flex justify-between items-center">
                <div>
                  <h4 class="text-lg font-bold mb-1">{{ major.name }}</h4>
                  <p class="text-sm opacity-80">{{ major.desc }}</p>
                </div>
                <i
                  data-lucide="chevron-right"
                  class="w-5 h-5 transition-transform"
                  :class="activeMajor === major.id ? 'translate-x-1' : ''"
                ></i>
              </div>
            </button>
          </div>

          <!-- Info Box Mini -->
          <div
            class="mt-10 bg-white/10 backdrop-blur-md p-5 rounded-lg border border-white/20 dark:bg-slate-800 dark:border-slate-700 shadow-sm"
          >
            <h4 class="font-bold text-white mb-2 flex items-center">
              <i data-lucide="info" class="w-4 h-4 mr-2"></i> Info Silabus
            </h4>
            <p class="text-sm text-blue-100 dark:text-gray-400 leading-relaxed">
              Klik pada setiap mata pelajaran di samping untuk melihat rincian topik
              pembelajaran (Capaian Pembelajaran) yang akan dibahas selama dua semester.
            </p>
          </div>
        </div>

        <!-- Main Content (Syllabus Accordion) -->
        <div class="w-full lg:w-2/3">
          <Transition
            mode="out-in"
            enter-active-class="transition-all duration-400 ease-out"
            enter-from-class="opacity-0 translate-x-4"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 -translate-x-4"
          >
            <div :key="activeGrade + '-' + activeMajor" class="space-y-8">
              <div
                v-for="(category, idx) in currentSyllabus"
                :key="idx"
                class="mb-8 last:mb-0"
              >
                <!-- Category Header -->
                <div class="flex items-center gap-4 mb-6">
                  <h2 class="text-xl md:text-2xl font-bold text-white">
                    {{ category.category }}
                  </h2>
                  <div class="h-px bg-white/20 dark:bg-slate-700 flex-1"></div>
                </div>

                <!-- Subject Accordions -->
                <div class="flex flex-col gap-4">
                  <div
                    v-for="subject in category.subjects"
                    :key="subject.id"
                    class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-transparent dark:border-slate-700 overflow-hidden transition-all"
                    :class="
                      expandedSubject === subject.id
                        ? 'ring-4 ring-white/30 dark:ring-blue-500/40 shadow-xl'
                        : 'hover:border-white/50 hover:shadow-xl'
                    "
                  >
                    <!-- Accordion Header (Clickable) -->
                    <button
                      @click="toggleSubject(subject.id)"
                      class="w-full p-5 md:p-6 flex items-start sm:items-center justify-between gap-4 focus:outline-none"
                    >
                      <div class="flex items-start sm:items-center gap-4 text-left">
                        <div
                          class="w-12 h-12 shrink-0 rounded-lg flex items-center justify-center"
                          :class="subject.bg + ' ' + subject.color"
                        >
                          <i :data-lucide="subject.icon" class="w-6 h-6"></i>
                        </div>
                        <div>
                          <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-1"
                          >
                            {{ subject.name }}
                          </h3>
                          <p
                            class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1 sm:line-clamp-none pr-4"
                          >
                            {{ subject.desc }}
                          </p>
                        </div>
                      </div>
                      <div
                        class="shrink-0 w-8 h-8 rounded-full bg-gray-50 dark:bg-slate-700 flex items-center justify-center text-gray-400 transition-transform duration-300"
                        :class="
                          expandedSubject === subject.id
                            ? 'rotate-180 bg-blue-50 text-blue-600 dark:bg-slate-600 dark:text-blue-400'
                            : ''
                        "
                      >
                        <i data-lucide="chevron-down" class="w-5 h-5"></i>
                      </div>
                    </button>

                    <!-- Accordion Body -->
                    <div
                      class="transition-all duration-500 ease-in-out overflow-hidden"
                      :class="
                        expandedSubject === subject.id
                          ? 'max-h-[800px] opacity-100'
                          : 'max-h-0 opacity-0'
                      "
                    >
                      <div
                        class="p-6 pt-0 border-t border-gray-50 dark:border-slate-700/50 bg-gray-50/50 dark:bg-slate-800/50 mt-2"
                      >
                        <h4
                          class="text-sm font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider flex items-center mt-4"
                        >
                          <i
                            data-lucide="bookmark"
                            class="w-4 h-4 mr-2 text-blue-500"
                          ></i>
                          Topik Pembelajaran
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                          <div
                            v-for="(topic, tIdx) in subject.topics"
                            :key="tIdx"
                            class="flex items-start gap-3 bg-white dark:bg-slate-700 p-3.5 rounded-lg border border-gray-100 dark:border-slate-600/50 shadow-sm"
                          >
                            <i
                              data-lucide="check-circle"
                              class="w-5 h-5 text-green-500 shrink-0 mt-0.5"
                            ></i>
                            <span
                              class="text-sm font-medium text-gray-700 dark:text-gray-200 leading-snug"
                              >{{ topic }}</span
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Empty State jika tidak ada data -->
              <div
                v-if="currentSyllabus.length === 0"
                class="py-16 text-center bg-white/10 dark:bg-slate-800/80 backdrop-blur-sm rounded-lg border border-dashed border-white/30 dark:border-slate-700"
              >
                <div
                  class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/20 dark:bg-slate-700 mb-4 text-white"
                >
                  <i data-lucide="book-x" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Silabus Belum Tersedia</h3>
                <p class="text-blue-100 dark:text-gray-400 mt-1">
                  Modul kurikulum untuk kelas ini sedang dalam proses penyusunan.
                </p>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </section>
  </div>
</template>
