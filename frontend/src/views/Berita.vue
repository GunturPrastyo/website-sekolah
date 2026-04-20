<script setup>
import { ref, computed, onMounted, nextTick, onUpdated } from "vue";
import { createIcons, icons } from "lucide";

const activeCategory = ref("semua");

const categories = [
  { id: "semua", name: "Semua Berita" },
  { id: "akademik", name: "Akademik" },
  { id: "kegiatan", name: "Kegiatan" },
  { id: "prestasi", name: "Prestasi" },
  { id: "pengumuman", name: "Pengumuman" },
];

const newsList = ref([
  {
    id: 1,
    title: "Peringatan Hari Guru Nasional Berlangsung Meriah",
    category: "kegiatan",
    date: "24 Nov 2025",
    author: "Tim Redaksi",
    image: "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800",
    excerpt:
      "Seluruh siswa dan staf pengajar berpartisipasi dalam rangkaian acara yang dimeriahkan dengan berbagai penampilan pentas seni dan penghargaan bagi guru berprestasi.",
  },
  {
    id: 2,
    title: "Siswa SMAN 1 Meraih Juara 1 Olimpiade Sains Tingkat Nasional",
    category: "prestasi",
    date: "10 Jan 2026",
    author: "Humas",
    image: "https://images.unsplash.com/photo-1567057419565-4349c49d8a04?q=80&w=800",
    excerpt:
      "Prestasi membanggakan kembali ditorehkan oleh siswa-siswi kita di kancah nasional dalam bidang sains terapan, mengalahkan ratusan peserta dari sekolah lain.",
  },
  {
    id: 3,
    title: "Jadwal Pelaksanaan Ujian Akhir Semester Ganjil 2025/2026",
    category: "akademik",
    date: "01 Des 2025",
    author: "Kurikulum",
    image: "https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=800",
    excerpt:
      "Diberitahukan kepada seluruh siswa bahwa Ujian Akhir Semester (UAS) Ganjil akan dilaksanakan secara serentak mulai tanggal 10 hingga 18 Desember 2025.",
  },
  {
    id: 4,
    title: "Peresmian Laboratorium Komputer Baru untuk Menunjang Digitalisasi",
    category: "pengumuman",
    date: "05 Feb 2026",
    author: "Sarpras",
    image: "https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800",
    excerpt:
      "Fasilitas lab baru telah dilengkapi dengan 40 unit komputer berspesifikasi tinggi untuk mendukung pembelajaran informatika dan simulasi ujian digital.",
  },
  {
    id: 5,
    title: "Kunjungan Edukasi Sejarah ke Museum Nasional Jakarta",
    category: "kegiatan",
    date: "20 Mar 2026",
    author: "Kesiswaan",
    image: "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800",
    excerpt:
      "Kegiatan rutin tahunan ini diikuti oleh seluruh siswa kelas X guna mengenal lebih dekat peninggalan sejarah bangsa dan memupuk rasa nasionalisme.",
  },
  {
    id: 6,
    title: "Pendaftaran Peserta Didik Baru (PPDB) Jalur Prestasi Dibuka",
    category: "pengumuman",
    date: "15 Apr 2026",
    author: "Panitia PPDB",
    image: "https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=800",
    excerpt:
      "SMAN 1 Nogosari resmi membuka pendaftaran PPDB tahun ajaran baru khusus untuk jalur prestasi akademik maupun non-akademik. Simak persyaratannya di sini.",
  },
]);

const filteredNews = computed(() => {
  if (activeCategory.value === "semua") return newsList.value;
  return newsList.value.filter((news) => news.category === activeCategory.value);
});

onMounted(() => {
  createIcons({ icons });
});

onUpdated(() => {
  nextTick(() => {
    createIcons({ icons });
  });
});
</script>

<template>
  <div>
    <!-- Hero Banner -->
    <div
      class="relative pt-32 pb-24 lg:pt-44 lg:pb-32 bg-gradient-to-br from-indigo-600 via-blue-700 to-slate-900 overflow-hidden"
    >
      <!-- Background glow elements -->
      <div
        class="absolute -top-32 -left-32 w-96 h-96 bg-white/10 blur-3xl rounded-full"
      ></div>
      <div
        class="absolute bottom-0 right-0 w-80 h-80 bg-blue-400/20 blur-3xl rounded-full"
      ></div>

      <div class="container relative z-10 mx-auto px-6 text-center">
        <span
          class="inline-block px-4 py-1.5 mb-5 text-xs md:text-sm font-semibold text-indigo-900 bg-white/90 rounded-full shadow-sm backdrop-blur"
        >
          Informasi Publik
        </span>
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
          Berita & Artikel Sekolah
        </h1>
        <p class="text-indigo-100 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
          Ikuti terus perkembangan, kegiatan, prestasi, dan pengumuman terbaru seputar
          lingkungan SMA Negeri 1 Nogosari.
        </p>
      </div>
    </div>

    <!-- News Section -->
    <section class="py-16 md:py-24 px-6 bg-gray-50 dark:bg-slate-900 min-h-screen">
      <div class="container mx-auto max-w-6xl">
        
        <!-- Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
          <button
            v-for="cat in categories"
            :key="cat.id"
            @click="activeCategory = cat.id"
            class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 focus:outline-none"
            :class="
              activeCategory === cat.id
                ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30'
                : 'bg-white text-gray-600 dark:bg-slate-800 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:border-indigo-300 hover:text-indigo-600 dark:hover:text-indigo-400'
            "
          >
            {{ cat.name }}
          </button>
        </div>

        <!-- Grid Berita dengan Animasi -->
        <TransitionGroup
          name="news-list"
          tag="div"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative"
        >
          <article
            v-for="news in filteredNews"
            :key="news.id"
            class="group relative bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden border border-gray-100 dark:border-slate-700 flex flex-col h-full transform hover:-translate-y-1"
          >
            <!-- Image Container -->
            <div class="relative h-56 overflow-hidden shrink-0">
              <img
                :src="news.image"
                :alt="news.title"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-80"
              ></div>
              
              <!-- Category Badge -->
              <div
                class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm text-indigo-700 text-xs font-bold rounded-md uppercase tracking-wider shadow-sm"
              >
                {{ categories.find((c) => c.id === news.category)?.name }}
              </div>
            </div>

            <!-- Content -->
            <div class="p-6 flex flex-col flex-1 relative bg-white dark:bg-slate-800">
              <!-- Meta Info -->
              <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-3 gap-4 font-medium">
                <span class="flex items-center">
                  <i data-lucide="calendar" class="w-3.5 h-3.5 mr-1.5 text-indigo-500"></i>
                  {{ news.date }}
                </span>
                <span class="flex items-center">
                  <i data-lucide="user" class="w-3.5 h-3.5 mr-1.5 text-indigo-500"></i>
                  {{ news.author }}
                </span>
              </div>

              <h3
                class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-tight"
              >
                <a href="#" class="focus:outline-none">
                  <span class="absolute inset-0"></span>
                  {{ news.title }}
                </a>
              </h3>

              <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-3 mb-6">
                {{ news.excerpt }}
              </p>
              
              <div class="mt-auto flex items-center text-sm font-bold text-indigo-600 dark:text-indigo-400 group-hover:underline">
                Baca Selengkapnya <i data-lucide="arrow-right" class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform"></i>
              </div>
            </div>
          </article>
        </TransitionGroup>

        <!-- Empty State -->
        <div
          v-if="filteredNews.length === 0"
          class="py-20 text-center bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm mt-4"
        >
          <div
            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-700 mb-4 text-gray-400"
          >
            <i data-lucide="newspaper" class="w-8 h-8"></i>
          </div>
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">
            Belum Ada Berita
          </h3>
          <p class="text-gray-500 dark:text-gray-400 mt-1">
            Belum ada artikel atau pengumuman untuk kategori ini.
          </p>
        </div>

      </div>
    </section>
  </div>
</template>

<style scoped>
.news-list-move,
.news-list-enter-active,
.news-list-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.news-list-enter-from,
.news-list-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(20px);
}
.news-list-leave-active {
  position: absolute;
}
</style>