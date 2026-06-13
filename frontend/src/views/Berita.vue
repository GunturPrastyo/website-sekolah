<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from "vue";
import { useRoute } from "vue-router";
import {
  PhCalendarBlank,
  PhUser,
  PhCaretLeft,
  PhCaretRight,
  PhEye,
  PhFileX,
  PhFadersHorizontal,
  PhMagnifyingGlass,
  PhTrendUp,
  PhArrowUpRight,
} from "@phosphor-icons/vue";
import PageHeader from "@/components/PageHeader.vue";
import Breadcrumb from "@/components/Breadcrumb.vue";
import api from "@/api/index.js";

const route = useRoute();

const activeCategory = ref("semua");

const categories = ref([
  { id: "semua", name: "Semua Berita" },
  { id: "akademik", name: "Akademik" },
  { id: "kegiatan", name: "Kegiatan" },
  { id: "prestasi", name: "Prestasi" },
  { id: "pengumuman", name: "Pengumuman" },
]);

const paginatedNews = ref([]);
const popularNews = ref([]);

const searchQuery = ref("");

// --- Fitur Pagination ---
const itemsPerPage = 6;
const currentPage = ref(1);
const totalPages = ref(1);
const isLoading = ref(true); // Set true untuk skeleton loading saat mount
let searchTimeout = null;

const changePage = (page) => {
  if (page < 1 || page > totalPages.value) return;
  currentPage.value = page;
  fetchNews();
  const container = document.getElementById("news-list-container");
  if (container) {
    window.scrollTo({ top: container.offsetTop - 120, behavior: "smooth" });
  }
};

const skeletonCount = computed(() => {
  if (!isLoading.value) return 0;
  return itemsPerPage;
});

const getImageUrl = (path) => {
  if (!path)
    return "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800";
  if (path.startsWith("http") || path.startsWith("data:image")) return path;

  const baseUrl = import.meta.env.VITE_API_URL || "http://localhost:8000";
  const cleanPath = path.startsWith("/") ? path.slice(1) : path;
  if (cleanPath.startsWith("storage/")) {
    return `${baseUrl}/${cleanPath}`;
  }
  return `${baseUrl}/storage/${cleanPath}`;
};

const mapNewsItem = (item) => {
  // Buat text excerpt dengan cara menghilangkan tag HTML dari konten
  const tempDiv = document.createElement("div");
  tempDiv.innerHTML = item.content;
  const textContent = tempDiv.textContent || tempDiv.innerText || "";

  // Ambil gambar pertama dari array images, atau gunakan fallback image
  let rawImage = item.image;
  if (item.images && item.images.length > 0) {
    rawImage = item.images[0];
  }
  let imageUrl = getImageUrl(rawImage);

  const catLower = item.category ? item.category.toLowerCase() : "pengumuman";

  return {
    id: item.id,
    slug: item.slug,
    title: item.title,
    category: catLower,
    date: new Date(item.created_at).toLocaleDateString("id-ID", {
      day: "2-digit",
      month: "short",
      year: "numeric",
    }),
    author: item.author ? item.author.name : "Admin",
    image: imageUrl,
    views: item.views || 0,
    excerpt: textContent.substring(0, 150) + "...",
  };
};

// Fungsi mengambil data dari API backend
const fetchNews = async () => {
  isLoading.value = true;
  try {
    let url = `/api/public-news?page=${currentPage.value}&per_page=${itemsPerPage}`;
    
    if (activeCategory.value !== "semua") {
      url += `&category=${activeCategory.value}`;
    }
    if (searchQuery.value.trim()) {
      url += `&search=${encodeURIComponent(searchQuery.value.trim())}`;
    }

    const response = await api.get(url);
    const result = response.data;

    paginatedNews.value = result.data.map(mapNewsItem);
    
    if (result.pagination) {
      totalPages.value = result.pagination.last_page;
      currentPage.value = result.pagination.current_page;
    }
  } catch (error) {
    console.error("Gagal mengambil data berita:", error);
  } finally {
    isLoading.value = false;
  }
};

const fetchPopularNews = async () => {
  try {
    const response = await api.get("/api/public-news?per_page=5&sort=views");
    popularNews.value = response.data.data.map(mapNewsItem);
  } catch (error) {
    console.error("Gagal mengambil data berita populer:", error);
  }
};

watch([searchQuery, activeCategory], () => {
  currentPage.value = 1;
  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchNews();
  }, 500);
});

let observer;

onMounted(() => {
  if (route.query.q) {
    searchQuery.value = route.query.q;
  }
  
  fetchNews();
  fetchPopularNews();

  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("opacity-100", "translate-y-0");
          entry.target.classList.remove("opacity-0", "translate-y-10");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 }
  );

  document.querySelectorAll(".fade-on-scroll").forEach((el) => {
    observer.observe(el);
  });
});

onBeforeUnmount(() => {
  if (observer) observer.disconnect();
});
</script>

<template>
  <div>
    <PageHeader
      badge="Informasi Publik"
      title="Berita & Artikel Sekolah"
      description="Ikuti terus perkembangan, kegiatan, prestasi, dan pengumuman terbaru seputar lingkungan sekolah."
      bgImage="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1600&auto=format&fit=crop"
    />

    <!-- News Section -->
    <section class="py-16 md:py-12 px-6 bg-gray-50 dark:bg-slate-900 min-h-screen">
      <!-- Breadcrumb -->
      <div
        class="container mx-auto max-w-full px-0 lg:px-8 mb-8 md:mb-10 fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out"
      >
        <Breadcrumb
          :items="[{ name: 'Beranda', link: '/', icon: 'home' }, { name: 'Berita' }]"
        />
      </div>

      <div
        class="container mx-auto max-w-full px-0 lg:px-8 flex flex-col lg:flex-row gap-8 lg:gap-10"
      >
        <!-- KIRI: Daftar Berita -->
        <div
          id="news-list-container"
          class="w-full lg:w-2/3 order-2 lg:order-1 fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-100 ease-out"
        >
          <!-- Skeleton Loading -->
          <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div
              v-for="i in skeletonCount"
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
            <div v-if="paginatedNews.length > 0">
              <!-- Grid Berita dengan Animasi -->
              <TransitionGroup
                name="news-list"
                tag="div"
                class="grid grid-cols-1 md:grid-cols-2 gap-8 relative"
              >
                <article
                  v-for="news in paginatedNews"
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
                      class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm text-blue-700 text-sm font-bold rounded-md capitalize tracking-wide shadow-sm"
                      style="font-family: 'Kalam', cursive"
                    >
                      {{ categories.find((c) => c.id === news.category)?.name }}
                    </div>
                  </div>

                  <!-- Content -->
                  <div
                    class="p-6 flex flex-col flex-1 relative bg-white dark:bg-slate-800"
                  >
                    <!-- Meta Info -->
                    <div
                      class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-3 gap-4 font-medium"
                    >
                      <span class="flex items-center">
                        <PhCalendarBlank class="w-3.5 h-3.5 mr-1.5 text-blue-500" />
                        {{ news.date }}
                      </span>
                      <span class="flex items-center">
                        <PhUser class="w-3.5 h-3.5 mr-1.5 text-blue-500" />
                        {{ news.author }}
                      </span>
                    </div>

                    <h3
                      class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors leading-tight"
                    >
                      <router-link
                        :to="`/artikel/${news.slug}`"
                        class="focus:outline-none"
                      >
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
                        :to="`/artikel/${news.slug}`"
                        class="flex items-center text-sm font-bold text-blue-600 dark:text-blue-400 group-hover:underline"
                      >
                        Baca Selengkapnya
                        <PhArrowUpRight
                          class="w-4 h-4 ml-1 transform group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"
                        />
                      </router-link>
                      <span
                        class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400"
                      >
                        <PhEye class="w-4 h-4 mr-1.5 text-blue-500" />
                        {{ news.views }}
                      </span>
                    </div>
                  </div>
                </article>
              </TransitionGroup>

              <!-- Pagination Berita -->
              <div
                v-if="totalPages > 1"
                class="flex justify-between items-center gap-2 mt-10 relative z-10 w-full"
              >
                <button
                  @click="changePage(currentPage - 1)"
                  :disabled="currentPage === 1 || isLoading"
                  class="flex items-center px-4 py-2 rounded-lg text-base tracking-wide font-bold transition-colors border"
                  style="font-family: 'Kalam', cursive"
                  :class="
                    currentPage === 1
                      ? 'bg-gray-50 text-gray-400 border-gray-200 cursor-not-allowed dark:bg-slate-800 dark:border-slate-700 dark:text-gray-500'
                      : 'bg-white text-blue-600 border-gray-200 hover:bg-blue-50 hover:border-blue-300 dark:bg-slate-800 dark:text-blue-400 dark:border-slate-700 dark:hover:border-blue-500'
                  "
                >
                  <PhCaretLeft class="w-4 h-4 mr-1" />
                  Sebelumnya
                </button>

                <div class="flex items-center gap-1 hidden sm:flex">
                  <button
                    v-for="page in totalPages"
                    :key="page"
                    @click="changePage(page)"
                    :disabled="isLoading"
                    class="w-10 h-10 rounded-lg text-base font-bold transition-colors flex items-center justify-center border"
                    style="font-family: 'Kalam', cursive"
                    :class="
                      currentPage === page
                        ? 'bg-blue-600 text-white border-blue-600 shadow-md'
                        : 'bg-white text-gray-600 border-gray-200 hover:bg-blue-50 hover:text-blue-600 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-700 dark:hover:border-blue-500 dark:hover:text-blue-400'
                    "
                  >
                    {{ page }}
                  </button>
                </div>

                <button
                  @click="changePage(currentPage + 1)"
                  :disabled="currentPage === totalPages || isLoading"
                  class="flex items-center px-4 py-2 rounded-lg text-base tracking-wide font-bold transition-colors border"
                  style="font-family: 'Kalam', cursive"
                  :class="
                    currentPage === totalPages
                      ? 'bg-gray-50 text-gray-400 border-gray-200 cursor-not-allowed dark:bg-slate-800 dark:border-slate-700 dark:text-gray-500'
                      : 'bg-white text-blue-600 border-gray-200 hover:bg-blue-50 hover:border-blue-300 dark:bg-slate-800 dark:text-blue-400 dark:border-slate-700 dark:hover:border-blue-500'
                  "
                >
                  Selanjutnya
                  <PhCaretRight class="w-4 h-4 ml-1" />
                </button>
              </div>
            </div>

            <!-- Empty State -->
            <div
              v-else
              class="py-20 text-center bg-white dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm"
            >
              <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-700 mb-4 text-gray-400"
              >
                <PhFileX class="w-8 h-8" />
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
        <aside
          class="w-full lg:w-1/3 flex flex-col -mt-px lg:mt-0 relative z-0 order-1 lg:order-2 fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-200 ease-out"
        >
          <div
            class="bg-white dark:bg-slate-800 rounded-none lg:rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden"
          >
            <!-- Search & Category Widget -->
            <div class="p-6 border-b border-gray-100 dark:border-slate-700">
              <h3
                class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center"
              >
                <PhFadersHorizontal class="w-5 h-5 mr-2 text-blue-500" />
                Cari & Filter
              </h3>

              <!-- Search Bar -->
              <div class="relative mb-6">
                <PhMagnifyingGlass
                  class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                />
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
                </button>
              </div>
            </div>

            <!-- Berita Populer Widget -->
            <div class="p-6">
              <h3
                class="text-lg font-bold text-gray-900 dark:text-white mb-5 border-b border-gray-100 dark:border-slate-700 pb-3 flex items-center"
              >
                <PhTrendUp class="w-5 h-5 mr-2 text-blue-500" />
                Terpopuler
              </h3>
              <div class="space-y-5">
                <router-link
                  :to="`/artikel/${news.slug}`"
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
                      <PhEye class="w-3.5 h-3.5 mr-1" />
                      {{ news.views }} kali dibaca
                    </span>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </section>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");

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
