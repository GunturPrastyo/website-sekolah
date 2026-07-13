<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from "vue";
import { useRoute, useRouter } from "vue-router";
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
  PhX,
  PhTag,
} from "@phosphor-icons/vue";
import PageHeader from "@/components/PageHeader.vue";
import Breadcrumb from "@/components/Breadcrumb.vue";
import api from "@/api/index.js";

const route = useRoute();
const router = useRouter();

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
const appearanceSettings = ref({});

const searchQuery = ref("");
const activeAuthor = ref("");
const activeTag = ref("");

// --- Fitur Pagination ---
const itemsPerPage = 6;
const currentPage = ref(1);
const totalPages = ref(1);
const isLoading = ref(true);
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

  const baseUrl = api.defaults.baseURL;
  const cleanPath = path.startsWith("/") ? path.slice(1) : path;

  if (cleanPath.startsWith("storage/")) {
    return `${baseUrl}/${cleanPath}`;
  }
  return `${baseUrl}/storage/${cleanPath}`;
};

const mapNewsItem = (item) => {
  const tempDiv = document.createElement("div");
  tempDiv.innerHTML = item.content;
  const textContent = tempDiv.textContent || tempDiv.innerText || "";

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

// Fungsi mengambil data berita utama dari API backend
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
    if (activeAuthor.value) {
      url += `&author=${encodeURIComponent(activeAuthor.value)}`;
    }
    if (activeTag.value) {
      url += `&tag=${encodeURIComponent(activeTag.value)}`;
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

const fetchInitialData = async () => {
  try {
    const [settingsResponse] = await Promise.all([api.get("/api/settings")]);

    if (settingsResponse.data?.success) {
      appearanceSettings.value = settingsResponse.data.data;
    }
  } catch (error) {
    console.error("Gagal mengambil data setting halaman berita:", error);
  }
};

watch(
  () => route.query,
  (newQuery) => {
    if (
      newQuery.author !== activeAuthor.value &&
      (newQuery.author !== undefined || activeAuthor.value !== "")
    ) {
      activeAuthor.value = newQuery.author || "";
    }
    if (
      newQuery.q !== searchQuery.value &&
      (newQuery.q !== undefined || searchQuery.value !== "")
    ) {
      searchQuery.value = newQuery.q || "";
    }
    if (
      newQuery.tag !== activeTag.value &&
      (newQuery.tag !== undefined || activeTag.value !== "")
    ) {
      activeTag.value = newQuery.tag || "";
    }
  },
  { immediate: true }
);

watch([searchQuery, activeCategory, activeAuthor, activeTag], () => {
  currentPage.value = 1;

  const query = { ...route.query };
  let urlChanged = false;

  if (searchQuery.value) {
    if (query.q !== searchQuery.value) {
      query.q = searchQuery.value;
      urlChanged = true;
    }
  } else if (query.q) {
    delete query.q;
    urlChanged = true;
  }

  if (activeAuthor.value) {
    if (query.author !== activeAuthor.value) {
      query.author = activeAuthor.value;
      urlChanged = true;
    }
  } else if (query.author) {
    delete query.author;
    urlChanged = true;
  }

  if (activeTag.value) {
    if (query.tag !== activeTag.value) {
      query.tag = activeTag.value;
      urlChanged = true;
    }
  } else if (query.tag) {
    delete query.tag;
    urlChanged = true;
  }

  if (urlChanged) {
    router.replace({ query }).catch(() => {});
  }

  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchNews();
  }, 500);
});

let observer;

onMounted(() => {
  fetchNews();
  fetchPopularNews();
  fetchInitialData();

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
      v-if="appearanceSettings"
      badge="Informasi Publik"
      :title="appearanceSettings.headerBerita_title || 'Berita & Artikel Sekolah'"
      :description="
        appearanceSettings.headerBerita_description ||
        'Ikuti terus perkembangan, kegiatan, prestasi, dan pengumuman terbaru seputar lingkungan sekolah.'
      "
      :bgImage="getImageUrl(appearanceSettings.headerBerita_bgImage)"
      currentPage="Berita"
    />

    <!-- News Section -->
    <section class="py-8 md:py-12 px-6 bg-gray-50 dark:bg-slate-900 min-h-screen">
      <!-- Breadcrumb -->
      <div
        class="container mx-auto max-w-full px-0 lg:px-8 mb-6 md:mb-10 fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out"
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
                class="h-48 md:h-56 bg-gray-200 dark:bg-slate-700 w-full shrink-0 rounded-t-lg"
              ></div>
              <div class="p-5 md:p-6 flex flex-col flex-1">
                <div class="flex gap-3 md:gap-4 mb-4">
                  <div class="h-3 w-16 bg-gray-200 dark:bg-slate-700 rounded"></div>
                  <div class="h-3 w-20 bg-gray-200 dark:bg-slate-700 rounded"></div>
                </div>
                <div
                  class="h-4 md:h-5 w-full bg-gray-200 dark:bg-slate-700 rounded mb-2"
                ></div>
                <div
                  class="h-4 md:h-5 w-3/4 bg-gray-200 dark:bg-slate-700 rounded mb-4 md:mb-5"
                ></div>
                <div class="space-y-2 mb-5 md:mb-6">
                  <div
                    class="h-2.5 md:h-3 w-full bg-gray-200 dark:bg-slate-700 rounded"
                  ></div>
                  <div
                    class="h-2.5 md:h-3 w-full bg-gray-200 dark:bg-slate-700 rounded"
                  ></div>
                  <div
                    class="h-2.5 md:h-3 w-4/5 bg-gray-200 dark:bg-slate-700 rounded"
                  ></div>
                </div>
                <div class="mt-auto flex justify-between items-center">
                  <div
                    class="h-3 md:h-4 w-24 md:w-32 bg-gray-200 dark:bg-slate-700 rounded"
                  ></div>
                  <div
                    class="h-5 md:h-6 w-5 md:w-6 bg-gray-200 dark:bg-slate-700 rounded-full"
                  ></div>
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
                  <div class="relative h-48 md:h-56 overflow-hidden shrink-0">
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
                      class="absolute top-3 md:top-4 left-3 md:left-4 px-2.5 md:px-3 py-1 bg-white/90 backdrop-blur-sm text-blue-700 text-xs md:text-sm font-bold rounded-md capitalize tracking-wide shadow-sm"
                      style="font-family: 'Kalam', cursive"
                    >
                      {{ categories.find((c) => c.id === news.category)?.name }}
                    </div>
                  </div>

                  <!-- Content -->
                  <div
                    class="p-5 md:p-6 flex flex-col flex-1 relative bg-white dark:bg-slate-800"
                  >
                    <!-- Meta Info -->
                    <div
                      class="flex items-center text-[11px] md:text-xs text-gray-500 dark:text-gray-400 mb-2 md:mb-3 gap-3 md:gap-4 font-medium"
                    >
                      <span class="flex items-center">
                        <PhCalendarBlank
                          class="w-3 h-3 md:w-3.5 md:h-3.5 mr-1.5 text-blue-500"
                        />
                        {{ news.date }}
                      </span>
                      <span class="flex items-center">
                        <PhUser class="w-3 h-3 md:w-3.5 md:h-3.5 mr-1.5 text-blue-500" />
                        {{ news.author }}
                      </span>
                    </div>

                    <h3
                      class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-2 md:mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors leading-tight"
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
                      class="text-xs md:text-sm text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-3 mb-4 md:mb-6"
                    >
                      {{ news.excerpt }}
                    </p>

                    <div class="mt-auto flex items-center justify-between">
                      <router-link
                        :to="`/artikel/${news.slug}`"
                        class="flex items-center text-xs md:text-sm font-bold text-blue-600 dark:text-blue-400 group-hover:underline"
                      >
                        Baca Selengkapnya
                        <PhArrowUpRight
                          class="w-3.5 h-3.5 md:w-4 md:h-4 ml-1 transform group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"
                        />
                      </router-link>
                      <span
                        class="flex items-center text-[11px] md:text-xs font-medium text-gray-500 dark:text-gray-400"
                      >
                        <PhEye
                          class="w-3.5 h-3.5 md:w-4 md:h-4 mr-1 md:mr-1.5 text-blue-500"
                        />
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
                  activeAuthor = '';
                  activeTag = '';
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
          class="contents lg:flex lg:flex-col lg:w-1/3 lg:gap-8 relative z-0 lg:order-2"
        >
          <!-- Search & Category Widget -->
          <div
            class="bg-white dark:bg-slate-800 rounded-none lg:rounded-lg shadow-sm border-b lg:border border-gray-100 dark:border-slate-700 overflow-hidden order-1 lg:order-none fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-200 ease-out"
          >
            <div class="p-6">
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

              <!-- Indikator Filter Penulis -->
              <div
                v-if="activeAuthor"
                class="mb-6 flex items-center justify-between bg-blue-50 dark:bg-slate-700/50 px-4 py-3 rounded-lg border border-blue-100 dark:border-slate-600"
              >
                <div class="flex items-center text-sm text-blue-800 dark:text-blue-200">
                  <PhUser class="w-4 h-4 mr-2" />
                  <span
                    >Penulis: <strong class="font-bold">{{ activeAuthor }}</strong></span
                  >
                </div>
                <button
                  @click="activeAuthor = ''"
                  class="text-gray-400 hover:text-red-500 transition-colors"
                  title="Hapus Filter"
                >
                  <PhX class="w-4 h-4" />
                </button>
              </div>

              <!-- Indikator Filter Tag -->
              <div
                v-if="activeTag"
                class="mb-6 flex items-center justify-between bg-blue-50 dark:bg-slate-700/50 px-4 py-3 rounded-lg border border-blue-100 dark:border-slate-600"
              >
                <div class="flex items-center text-sm text-blue-800 dark:text-blue-200">
                  <PhTag class="w-4 h-4 mr-2" />
                  <span
                    >Tag: <strong class="font-bold">#{{ activeTag }}</strong></span
                  >
                </div>
                <button
                  @click="activeTag = ''"
                  class="text-gray-400 hover:text-red-500 transition-colors"
                  title="Hapus Filter"
                >
                  <PhX class="w-4 h-4" />
                </button>
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
          </div>

          <!-- Berita Populer Widget -->
          <div
            class="bg-white dark:bg-slate-800 rounded-none lg:rounded-lg shadow-sm border-y lg:border border-gray-100 dark:border-slate-700 overflow-hidden order-3 lg:order-none fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-300 ease-out"
          >
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
