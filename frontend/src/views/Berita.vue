<script setup>
import { ref, computed, onMounted, nextTick, onUpdated, reactive, watch } from "vue";
import { createIcons, icons } from "lucide";
import PageHeader from "@/components/PageHeader.vue";
import Breadcrumb from "@/components/Breadcrumb.vue";

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
    views: 1250,
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
    views: 3420,
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
    views: 890,
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
    views: 2105,
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
    views: 1560,
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
    views: 4120,
    excerpt:
      "SMAN 1 Nogosari resmi membuka pendaftaran PPDB tahun ajaran baru khusus untuk jalur prestasi akademik maupun non-akademik. Simak persyaratannya di sini.",
  },
]);

const searchQuery = ref("");

const filteredNews = computed(() => {
  let filtered = newsList.value;

  if (activeCategory.value !== "semua") {
    filtered = filtered.filter((news) => news.category === activeCategory.value);
  }

  if (searchQuery.value.trim()) {
    const query = searchQuery.value.trim().toLowerCase();
    filtered = filtered.filter(
      (news) =>
        news.title.toLowerCase().includes(query) ||
        news.excerpt.toLowerCase().includes(query)
    );
  }

  return filtered;
});

const popularNews = computed(() => {
  return [...newsList.value].sort((a, b) => b.views - a.views).slice(0, 5);
});

const getCategoryCount = (categoryId) => {
  if (categoryId === "semua") return newsList.value.length;
  return newsList.value.filter((news) => news.category === categoryId).length;
};

const isLoading = ref(false);
let searchTimeout = null;

watch([searchQuery, activeCategory], () => {
  isLoading.value = true;
  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    isLoading.value = false;
  }, 800); // Tampilkan loading selama 800ms
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
    <PageHeader
      badge="Informasi Publik"
      title="Berita & Artikel Sekolah"
      description="Ikuti terus perkembangan, kegiatan, prestasi, dan pengumuman terbaru seputar lingkungan SMA Negeri 1 Nogosari."
    />

    <!-- News Section -->
    <section class="py-16 md:py-12 px-6 bg-gray-50 dark:bg-slate-900 min-h-screen">
      <!-- Breadcrumb -->
      <div class="container mx-auto max-w-full px-0 lg:px-8 mb-8 md:mb-10">
        <Breadcrumb
          :items="[{ name: 'Beranda', link: '/', icon: 'home' }, { name: 'Berita' }]"
        />
      </div>

      <div
        class="container mx-auto max-w-full px-0 lg:px-8 flex flex-col lg:flex-row gap-8 lg:gap-10"
      >
        <!-- KIRI: Daftar Berita -->
        <div class="w-full lg:w-2/3 order-2 lg:order-1">
          <!-- Skeleton Loading -->
          <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div
              v-for="i in 4"
              :key="'skeleton-' + i"
              class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col h-full animate-pulse"
            >
              <div
                class="h-56 bg-gray-200 dark:bg-slate-700 w-full shrink-0 rounded-t-lg"
              ></div>
              <div class="p-6 flex flex-col flex-1">
                <div class="flex gap-4 mb-4">
                  <div class="h-3 w-16 bg-gray-200 dark:bg-slate-700 rounded"></div>
                  <div class="h-3 w-20 bg-gray-200 dark:bg-slate-700 rounded"></div>
                </div>
                <div class="h-5 w-full bg-gray-200 dark:bg-slate-700 rounded mb-2"></div>
                <div class="h-5 w-3/4 bg-gray-200 dark:bg-slate-700 rounded mb-5"></div>
                <div class="space-y-2 mb-6">
                  <div class="h-3 w-full bg-gray-200 dark:bg-slate-700 rounded"></div>
                  <div class="h-3 w-full bg-gray-200 dark:bg-slate-700 rounded"></div>
                  <div class="h-3 w-4/5 bg-gray-200 dark:bg-slate-700 rounded"></div>
                </div>
                <div class="mt-auto flex justify-between items-center">
                  <div class="h-4 w-32 bg-gray-200 dark:bg-slate-700 rounded"></div>
                  <div class="h-6 w-6 bg-gray-200 dark:bg-slate-700 rounded-full"></div>
                </div>
              </div>
            </div>
          </div>

          <template v-else>
            <!-- Grid Berita dengan Animasi -->
            <TransitionGroup
              v-if="filteredNews.length > 0"
              name="news-list"
              tag="div"
              class="grid grid-cols-1 md:grid-cols-2 gap-8 relative"
            >
              <article
                v-for="news in filteredNews"
                :key="news.id"
                class="group relative bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden border border-gray-100 dark:border-slate-700 flex flex-col h-full transform hover:-translate-y-1"
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
                    class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm text-blue-700 text-xs font-bold rounded-md uppercase tracking-wider shadow-sm"
                  >
                    {{ categories.find((c) => c.id === news.category)?.name }}
                  </div>
                </div>

                <!-- Content -->
                <div class="p-6 flex flex-col flex-1 relative bg-white dark:bg-slate-800">
                  <!-- Meta Info -->
                  <div
                    class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-3 gap-4 font-medium"
                  >
                    <span class="flex items-center">
                      <i
                        data-lucide="calendar"
                        class="w-3.5 h-3.5 mr-1.5 text-blue-500"
                      ></i>
                      {{ news.date }}
                    </span>
                    <span class="flex items-center">
                      <i data-lucide="user" class="w-3.5 h-3.5 mr-1.5 text-blue-500"></i>
                      {{ news.author }}
                    </span>
                  </div>

                  <h3
                    class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors leading-tight"
                  >
                    <router-link to="/artikel" class="focus:outline-none">
                      <span class="absolute inset-0"></span>
                      {{ news.title }}
                    </router-link>
                  </h3>

                  <p
                    class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-3 mb-6"
                  >
                    {{ news.excerpt }}
                  </p>

                  <div class="mt-auto flex items-center justify-between">
                    <router-link
                      to="/artikel"
                      class="flex items-center text-sm font-bold text-blue-600 dark:text-blue-400 group-hover:underline"
                    >
                      Baca Selengkapnya
                      <i
                        data-lucide="chevron-right"
                        class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform"
                      ></i>
                    </router-link>
                    <span
                      class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400"
                    >
                      <i data-lucide="eye" class="w-4 h-4 mr-1.5 text-blue-500"></i>
                      {{ news.views }}
                    </span>
                  </div>
                </div>
              </article>
            </TransitionGroup>

            <!-- Empty State -->
            <div
              v-else
              class="py-20 text-center bg-white dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm"
            >
              <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-700 mb-4 text-gray-400"
              >
                <i data-lucide="search-x" class="w-8 h-8"></i>
              </div>
              <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                Berita Tidak Ditemukan
              </h3>
              <p class="text-gray-500 dark:text-gray-400 mt-1">
                Tidak ada artikel yang cocok dengan pencarian atau kategori ini.
              </p>
              <button
                @click="
                  searchQuery = '';
                  activeCategory = 'semua';
                "
                class="mt-4 px-5 py-2 bg-blue-50 text-blue-600 dark:bg-slate-700 dark:text-blue-400 rounded-lg text-sm font-semibold hover:bg-blue-100 transition-colors"
              >
                Reset Pencarian
              </button>
            </div>
          </template>
        </div>

        <!-- KANAN: Sidebar -->
        <aside class="contents lg:block w-full lg:w-1/3 order-1 lg:order-2">
          <!-- Search & Category Widget -->
          <div
            class="w-full order-1 lg:order-none mb-0 lg:mb-8 bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700"
          >
            <h3
              class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center"
            >
              <i data-lucide="sliders-horizontal" class="w-5 h-5 mr-2 text-blue-500"></i>
              Cari & Filter
            </h3>

            <!-- Search Bar -->
            <div class="relative mb-6">
              <i
                data-lucide="search"
                class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
              ></i>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari judul atau isi berita..."
                class="w-full pl-11 pr-4 py-3 rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm"
              />
            </div>

            <!-- Kategori -->
            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3">
              Kategori Berita
            </h4>
            <div class="flex flex-wrap gap-2.5">
              <button
                v-for="cat in categories"
                :key="cat.id"
                @click="activeCategory = cat.id"
                class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all duration-300 focus:outline-none border"
                :class="
                  activeCategory === cat.id
                    ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/30'
                    : 'bg-gray-50 dark:bg-slate-800 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-slate-600 hover:border-blue-300 hover:text-blue-600 dark:hover:text-blue-400'
                "
              >
                {{ cat.name }}
                <span class="ml-1 text-[11px] font-bold opacity-70">
                  ({{ getCategoryCount(cat.id) }})
                </span>
              </button>
            </div>
          </div>

          <!-- Berita Populer Widget -->
          <div
            class="w-full order-3 lg:order-none bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700"
          >
            <h3
              class="text-lg font-bold text-gray-900 dark:text-white mb-5 border-b border-gray-100 dark:border-slate-700 pb-3 flex items-center"
            >
              <i data-lucide="trending-up" class="w-5 h-5 mr-2 text-blue-500"></i>
              Terpopuler
            </h3>
            <div class="space-y-5">
              <router-link
                to="/artikel"
                v-for="(news, index) in popularNews"
                :key="'popular-' + news.id"
                class="flex items-start gap-4 group"
              >
                <div
                  class="w-24 h-24 shrink-0 rounded-lg overflow-hidden shadow-sm relative"
                >
                  <img
                    :src="news.image"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                  />
                  <!-- Badge Peringkat -->
                  <div
                    class="absolute top-0 left-0 bg-blue-600 text-white w-6 h-6 flex items-center justify-center text-xs font-bold rounded-br-lg shadow-sm z-10"
                  >
                    {{ index + 1 }}
                  </div>
                </div>
                <div class="flex flex-col justify-start flex-1 py-0.5">
                  <h4
                    class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-snug line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mb-1"
                  >
                    {{ news.title }}
                  </h4>
                  <p
                    class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-2 leading-relaxed"
                  >
                    {{ news.excerpt }}
                  </p>
                  <span
                    class="text-[11px] font-medium text-gray-400 dark:text-gray-500 flex items-center mt-auto"
                  >
                    <i data-lucide="eye" class="w-3.5 h-3.5 mr-1"></i>
                    {{ news.views }} kali dibaca
                  </span>
                </div>
              </router-link>
            </div>
          </div>
        </aside>
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
