<script setup>
import { ref, onMounted, watch, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "@/api/index.js";
import {
  PhClock,
  PhShareNetwork,
  PhLink,
  PhTrendUp,
  PhCalendarBlank,
  PhEye,
  PhUsers,
} from "@phosphor-icons/vue";
import Breadcrumb from "@/components/Breadcrumb.vue";
import ShareModal from "@/components/ShareModal.vue";

const route = useRoute();
const router = useRouter();

const isShareModalOpen = ref(false);
const article = ref(null);
const isLoading = ref(true);
const popularNews = ref([]);
const relatedArticles = ref([]);
const otherAuthors = ref([]);

const getImageUrl = (path) => {
  if (!path)
    return "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1200";
  if (path.startsWith("http") || path.startsWith("data:image")) return path;

  const baseUrl = import.meta.env.VITE_API_URL;
  const cleanPath = path.startsWith("/") ? path.slice(1) : path;
  if (cleanPath.startsWith("storage/")) {
    return `${baseUrl}/${cleanPath}`;
  }
  return `${baseUrl}/storage/${cleanPath}`;
};

const openShareModal = () => {
  if (article.value) {
    isShareModalOpen.value = true;
  }
};

const closeShareModal = () => {
  isShareModalOpen.value = false;
};

const breadcrumbTitle = computed(() => {
  if (isLoading.value || !article.value) return "Memuat Artikel...";
  const title = article.value.title;
  return title.length > 40 ? title.substring(0, 40) + "..." : title;
});

const fetchArticleData = async () => {
  isLoading.value = true;
  try {
    const slug = route.params.slug;
    // Fetch article
    const response = await api.get(`/api/public-news/${slug}`);
    const data = response.data.data;

    let imageUrl = getImageUrl(data.image);
    if (data.images && data.images.length > 0) {
      imageUrl = getImageUrl(data.images[0]);
    }

    article.value = {
      ...data,
      image: imageUrl,
      date: new Date(data.created_at).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
      }),
      authorName: data.author ? data.author.name : "Admin",
      authorInitials: data.author ? data.author.name.substring(0, 2).toUpperCase() : "AD",
    };

    // Calculate reading time roughly (words / 200)
    const textContent = article.value.content.replace(/<[^>]+>/g, "");
    const wordCount = textContent.split(/\s+/).length;
    article.value.readTime = Math.max(1, Math.ceil(wordCount / 200));

    fetchSideData(article.value.category);
  } catch (error) {
    console.error("Gagal memuat artikel", error);
    router.push("/berita");
  } finally {
    isLoading.value = false;
  }
};

const fetchSideData = async (category) => {
  try {
    const response = await api.get("/api/public-news");
    const allNews = response.data.data;

    // Hitung rekap penulis lainnya
    const authorMap = {};
    allNews.forEach((item) => {
      const authorName = item.author ? item.author.name : "Admin";
      const authorId = item.author ? item.author.id : "admin";
      if (!authorMap[authorId]) {
        authorMap[authorId] = {
          id: authorId,
          name: authorName,
          initials: authorName.substring(0, 2).toUpperCase(),
          count: 0,
        };
      }
      authorMap[authorId].count++;
    });

    const currentAuthorName = article.value?.authorName;
    otherAuthors.value = Object.values(authorMap)
      .filter((a) => a.name !== currentAuthorName)
      .sort((a, b) => b.count - a.count)
      .slice(0, 4); // Ambil 4 penulis lainnya teratas

    const processedNews = allNews.map((item) => {
      const tempDiv = document.createElement("div");
      tempDiv.innerHTML = item.content;
      const textContent = tempDiv.textContent || tempDiv.innerText || "";

      let imageUrl = getImageUrl(item.image);
      if (item.images && item.images.length > 0) {
        imageUrl = getImageUrl(item.images[0]);
      }

      return {
        id: item.id,
        slug: item.slug,
        title: item.title,
        category: item.category,
        date: new Date(item.created_at).toLocaleDateString("id-ID", {
          day: "2-digit",
          month: "short",
          year: "numeric",
        }),
        image: imageUrl,
        views: item.views || 0,
        description: textContent.substring(0, 100) + "...",
      };
    });

    const otherNews = processedNews.filter(
      (n) => String(n.slug) !== String(route.params.slug)
    );

    popularNews.value = [...otherNews].sort((a, b) => b.views - a.views).slice(0, 3);
    relatedArticles.value = otherNews.filter((n) => n.category === category).slice(0, 3);
    if (relatedArticles.value.length === 0) {
      relatedArticles.value = otherNews.slice(0, 3);
    }
  } catch (error) {
    console.error("Gagal memuat sidebar data", error);
  }
};

onMounted(() => {
  fetchArticleData();
  window.scrollTo({ top: 0, behavior: "smooth" });
});

watch(
  () => route.params.slug,
  (newSlug) => {
    if (newSlug) {
      fetchArticleData();
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  }
);
</script>

<template>
  <div class="pt-28 pb-0 lg:pb-16 bg-gray-50 dark:bg-slate-900 min-h-screen">
    <div class="container mx-auto max-w-full px-0 lg:px-8">
      <!-- Breadcrumb -->
      <div class="mb-6 md:mb-8 px-4 lg:px-0">
        <Breadcrumb
          :items="[
            { name: 'Beranda', link: '/', icon: 'home' },
            { name: 'Berita', link: '/berita' },
            { name: breadcrumbTitle },
          ]"
        />
      </div>

      <!-- Skeleton Loader -->
      <div
        v-if="isLoading"
        class="flex flex-col lg:flex-row gap-0 lg:gap-8 animate-pulse"
      >
        <!-- KIRI: Konten Utama Skeleton -->
        <main
          class="w-full lg:w-2/3 bg-white dark:bg-slate-800 rounded-none lg:rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden relative z-10"
        >
          <div class="p-6 md:p-10 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-6 w-20 bg-gray-200 dark:bg-slate-700 rounded-md"></div>
              <div class="h-4 w-24 bg-gray-200 dark:bg-slate-700 rounded"></div>
            </div>

            <div
              class="h-10 w-full sm:w-3/4 bg-gray-200 dark:bg-slate-700 rounded mb-3"
            ></div>
            <div
              class="h-10 w-2/3 sm:w-1/2 bg-gray-200 dark:bg-slate-700 rounded mb-6"
            ></div>

            <div class="flex items-center gap-3">
              <div
                class="w-12 h-12 rounded-full bg-gray-200 dark:bg-slate-700 shrink-0"
              ></div>
              <div class="flex flex-col gap-2">
                <div class="h-4 w-32 bg-gray-200 dark:bg-slate-700 rounded"></div>
                <div class="h-3 w-48 bg-gray-200 dark:bg-slate-700 rounded"></div>
              </div>
            </div>
          </div>

          <div class="w-full h-64 md:h-96 bg-gray-200 dark:bg-slate-700"></div>

          <div class="p-6 md:p-10 space-y-4">
            <div class="h-4 w-full bg-gray-200 dark:bg-slate-700 rounded"></div>
            <div class="h-4 w-full bg-gray-200 dark:bg-slate-700 rounded"></div>
            <div class="h-4 w-11/12 bg-gray-200 dark:bg-slate-700 rounded"></div>
            <div class="h-4 w-full bg-gray-200 dark:bg-slate-700 rounded mt-6"></div>
            <div class="h-4 w-full bg-gray-200 dark:bg-slate-700 rounded"></div>
            <div class="h-4 w-4/5 bg-gray-200 dark:bg-slate-700 rounded"></div>
            <div class="h-4 w-5/6 bg-gray-200 dark:bg-slate-700 rounded mt-6"></div>
            <div class="h-4 w-1/2 bg-gray-200 dark:bg-slate-700 rounded"></div>
          </div>
        </main>

        <!-- KANAN: Sidebar Skeleton -->
        <aside class="w-full lg:w-1/3 flex flex-col -mt-px lg:mt-0 relative z-0">
          <div
            class="bg-white dark:bg-slate-800 rounded-none lg:rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden"
          >
            <div
              class="p-6 md:p-8 text-center border-b border-gray-100 dark:border-slate-700 flex flex-col items-center"
            >
              <div
                class="w-20 h-20 rounded-full bg-gray-200 dark:bg-slate-700 mb-4 border-4 border-white shadow-sm"
              ></div>
              <div class="h-5 w-32 bg-gray-200 dark:bg-slate-700 rounded mb-2"></div>
              <div class="h-3 w-48 bg-gray-200 dark:bg-slate-700 rounded mb-4"></div>
              <div class="h-10 w-full bg-gray-200 dark:bg-slate-700 rounded-lg"></div>
            </div>

            <div class="p-6 md:p-8 border-b border-gray-100 dark:border-slate-700">
              <div class="h-6 w-40 bg-gray-200 dark:bg-slate-700 rounded mb-5"></div>
              <div class="space-y-5">
                <div v-for="i in 3" :key="i" class="flex gap-4">
                  <div
                    class="w-20 h-20 rounded-lg bg-gray-200 dark:bg-slate-700 shrink-0"
                  ></div>
                  <div class="flex-1 py-1 space-y-2">
                    <div class="h-4 w-full bg-gray-200 dark:bg-slate-700 rounded"></div>
                    <div class="h-4 w-3/4 bg-gray-200 dark:bg-slate-700 rounded"></div>
                    <div
                      class="h-3 w-1/2 bg-gray-200 dark:bg-slate-700 rounded mt-3"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </aside>
      </div>

      <div v-else class="flex flex-col lg:flex-row gap-0 lg:gap-8">
        <!-- KIRI: Konten Utama Artikel -->
        <main
          class="w-full lg:w-2/3 bg-white dark:bg-slate-800 rounded-none lg:rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden relative z-10"
        >
          <!-- Header Artikel -->
          <div class="p-6 md:p-10 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center justify-between gap-2 mb-4">
              <div class="flex flex-wrap items-center gap-3">
                <span
                  class="px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-md uppercase tracking-wider shadow-sm"
                >
                  {{ article?.category }}
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                  <PhClock class="w-3.5 h-3.5 mr-1.5" /> {{ article?.readTime }} menit
                  baca
                </span>
              </div>

              <!-- Tombol Aksi (Mobile) -->
              <button
                @click="openShareModal"
                class="sm:hidden shrink-0 p-2 rounded-full bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-gray-300 hover:bg-blue-50 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
              >
                <PhShareNetwork class="w-5 h-5" />
              </button>
            </div>

            <h1
              class="text-2xl md:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight mb-6"
            >
              {{ article?.title }}
            </h1>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <!-- Profil Penulis -->
              <div class="flex items-center gap-3">
                <div
                  class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xl shrink-0 uppercase"
                >
                  {{ article?.authorInitials }}
                </div>
                <div>
                  <div class="font-bold text-gray-900 dark:text-white text-sm">
                    {{ article?.authorName }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ article?.date }} • {{ article?.views }} Tayangan
                  </div>
                </div>
              </div>

              <!-- Tombol Aksi (Desktop) -->
              <div class="hidden sm:flex items-center gap-2">
                <button
                  @click="openShareModal"
                  class="p-2 rounded-full bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-gray-300 hover:bg-blue-50 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                >
                  <PhShareNetwork class="w-5 h-5" />
                </button>
              </div>
            </div>
          </div>

          <!-- Featured Image -->
          <div class="w-full h-64 md:h-96 relative bg-gray-200 dark:bg-slate-700">
            <img
              :src="article?.image"
              :alt="article?.title"
              class="w-full h-full object-cover"
            />
          </div>

          <!-- Isi Artikel -->
          <article
            class="p-6 md:p-10 text-gray-700 dark:text-gray-300 text-base md:text-lg text-justify article-content"
            v-html="article?.content"
          ></article>

          <!-- Tags -->
          <div
            v-if="article?.tags"
            class="px-6 md:px-10 pb-10 flex flex-wrap gap-2 border-b border-gray-100 dark:border-slate-700 mt-6"
          >
            <router-link
              :to="{ path: '/berita', query: { tag: tag.trim() } }"
              v-for="(tag, index) in article.tags.split(',')"
              :key="index"
              class="px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded hover:bg-blue-100 hover:text-blue-600 transition-colors"
              >#{{ tag.trim() }}</router-link
            >
          </div>
        </main>

        <!-- KANAN: Sidebar -->
        <aside class="w-full lg:w-1/3 flex flex-col -mt-px lg:mt-0 relative z-0">
          <div
            class="bg-white dark:bg-slate-800 rounded-none lg:rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden"
          >
            <!-- Author Profile Widget -->
            <div
              class="py-8 px-6 text-center border-b border-gray-100 dark:border-slate-700"
            >
              <div
                class="w-20 h-20 mx-auto rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-2xl mb-4 border-4 border-white shadow-sm uppercase"
              >
                {{ article?.authorInitials }}
              </div>
              <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                {{ article?.authorName }}
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Penulis Artikel di SMA Negeri 1 Nogosari.
              </p>
              <router-link
                :to="{ path: '/berita', query: { author: article?.authorName } }"
                class="block w-full py-2 bg-blue-50 text-blue-600 dark:bg-slate-700 dark:text-blue-400 rounded-lg text-sm font-semibold hover:bg-blue-100 transition-colors"
              >
                Lihat Artikel Lainnya
              </router-link>
            </div>

            <!-- Penulis Lainnya Widget -->
            <div
              class="py-8 px-6 border-b border-gray-100 dark:border-slate-700"
              v-if="otherAuthors.length > 0"
            >
              <h3
                class="text-lg font-bold text-gray-900 dark:text-white mb-5 border-b border-gray-100 dark:border-slate-700 pb-3 flex items-center"
              >
                <PhUsers class="w-5 h-5 mr-2 text-blue-500" />
                Penulis Lainnya
              </h3>
              <div class="flex flex-wrap gap-2">
                <router-link
                  :to="{ path: '/berita', query: { author: author.name } }"
                  v-for="author in otherAuthors"
                  :key="author.id"
                  class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 hover:bg-blue-50 dark:bg-slate-700/50 dark:hover:bg-blue-900/30 border border-gray-200 hover:border-blue-200 dark:border-slate-600 dark:hover:border-blue-800/50 rounded-2xl transition-all group shadow-sm hover:shadow-md"
                >
                  <span
                    class="text-xs font-semibold text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400"
                  >
                    {{ author.name }}
                  </span>
                  <span
                    class="flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold rounded-lg bg-white dark:bg-slate-800 text-gray-500 dark:text-gray-400 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-600 dark:group-hover:text-blue-300 border border-gray-100 dark:border-slate-700"
                  >
                    {{ author.count }}
                  </span>
                </router-link>
              </div>
            </div>

            <!-- Artikel Terkait Widget -->
            <div class="py-8 px-6 border-b border-gray-100 dark:border-slate-700">
              <h3
                class="text-lg font-bold text-gray-900 dark:text-white mb-5 border-b border-gray-100 dark:border-slate-700 pb-3 flex items-center"
              >
                <PhLink class="w-5 h-5 mr-2 text-blue-500" />
                Artikel Terkait
              </h3>
              <div class="space-y-5">
                <router-link
                  :to="`/artikel/${item.slug}`"
                  v-for="item in relatedArticles"
                  :key="item.id"
                  class="flex items-start gap-4 group"
                >
                  <div
                    class="w-20 h-20 shrink-0 rounded-lg overflow-hidden shadow-sm relative"
                  >
                    <img
                      :src="item.image"
                      class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    />
                    <div
                      class="absolute top-0 left-0 px-2 py-0.5 flex items-center justify-center text-[10px] font-bold rounded-br-lg shadow-sm z-10 bg-blue-600 text-white"
                    >
                      {{ item.category }}
                    </div>
                  </div>
                  <div class="flex flex-col justify-start flex-1 py-0.5">
                    <h4
                      class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-snug line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mb-1"
                    >
                      {{ item.title }}
                    </h4>
                    <p
                      class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-2 leading-relaxed"
                    >
                      {{ item.description }}
                    </p>
                    <div
                      class="flex items-center justify-between text-[11px] font-medium text-gray-400 dark:text-gray-500 mt-auto"
                    >
                      <span class="flex items-center">
                        <PhCalendarBlank class="w-3 h-3 mr-1" />
                        {{ item.date }}
                      </span>
                      <span class="flex items-center">
                        <PhEye class="w-3 h-3 mr-1" />
                        {{ item.views }}
                      </span>
                    </div>
                  </div>
                </router-link>
              </div>
            </div>

            <!-- Berita Populer Widget -->
            <div class="py-8 px-6">
              <h3
                class="text-lg font-bold text-gray-900 dark:text-white mb-0 md:mb-5 border-b border-gray-100 dark:border-slate-700 pb-3 flex items-center"
              >
                <PhTrendUp class="w-5 h-5 mr-2 text-blue-500" />
                Terpopuler
              </h3>
              <div class="space-y-5 mt-5 md:mt-0">
                <router-link
                  :to="`/artikel/${news.slug}`"
                  v-for="(news, index) in popularNews"
                  :key="news.id"
                  class="flex items-start gap-4 group"
                >
                  <div
                    class="w-20 h-20 shrink-0 rounded-lg overflow-hidden shadow-sm relative"
                  >
                    <img
                      :src="news.image"
                      class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    />
                    <div
                      class="absolute top-0 left-0 px-2 py-0.5 flex items-center justify-center text-[10px] font-bold rounded-br-lg shadow-sm z-10 bg-blue-600 text-white"
                    >
                      {{ news.category }}
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
                      {{ news.description }}
                    </p>
                    <div
                      class="flex items-center justify-between text-[11px] font-medium text-gray-400 dark:text-gray-500 mt-auto"
                    >
                      <span class="flex items-center">
                        <PhCalendarBlank class="w-3 h-3 mr-1" />
                        {{ news.date }}
                      </span>
                      <span class="flex items-center">
                        <PhEye class="w-3 h-3 mr-1" />
                        {{ news.views }}
                      </span>
                    </div>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </div>

    <!-- Share Modal Component -->
    <ShareModal
      :is-open="isShareModalOpen"
      :title="article?.title || ''"
      @close="closeShareModal"
    />
  </div>
</template>

<style scoped>
:deep(.article-content p) {
  margin-bottom: 0;
}
:deep(.article-content h1),
:deep(.article-content h2),
:deep(.article-content h3),
:deep(.article-content h4) {
  font-weight: 700;
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
  color: inherit;
}
:deep(.article-content ul) {
  list-style-type: disc;
  padding-left: 1.5rem;
  margin-bottom: 0;
}
:deep(.article-content ol) {
  list-style-type: decimal;
  padding-left: 1.5rem;
  margin-bottom: 0;
}
:deep(.article-content img) {
  border-radius: 0.5rem;
  max-width: 100%;
  height: auto;
  margin: 1.5rem auto;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}
:deep(.article-content a) {
  color: #3b82f6;
  text-decoration: underline;
}
:deep(.article-content blockquote) {
  border-left: 4px solid #e5e7eb;
  padding-left: 1rem;
  font-style: italic;
  color: #6b7280;
  margin: 1.5rem 0;
}
</style>
