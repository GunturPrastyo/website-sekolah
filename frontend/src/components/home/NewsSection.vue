<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch, nextTick } from "vue";
import {
  PhCaretRight,
  PhNewspaper,
  PhCalendarBlank,
  PhEye,
  PhBell,
} from "@phosphor-icons/vue";

const props = defineProps({
  recentNews: { type: Array, required: true },
  announcements: { type: Array, required: true },
  isLoadingNews: { type: Boolean, required: true },
});

const announcementsWrapper = ref(null);
const announcementsContent = ref(null);
const shouldAutoScroll = ref(false);
let resizeObserver = null;

const mainNews = computed(() =>
  props.recentNews.length > 0 ? props.recentNews[0] : null
);
const subNews = computed(() => props.recentNews.slice(1, 4));

const checkScroll = () => {
  if (
    announcementsWrapper.value &&
    announcementsContent.value &&
    announcementsContent.value.children.length > 0
  ) {
    const singleContentHeight = announcementsContent.value.children[0].scrollHeight;
    shouldAutoScroll.value =
      singleContentHeight > announcementsWrapper.value.clientHeight;
  }
};

const getImageUrl = (path) => {
  if (!path) return "";
  if (path.startsWith("http") || path.startsWith("data:image")) return path;
  const baseUrl = import.meta.env.VITE_API_URL || "https://api-sekolah-sma.duckdns.org";
  const cleanPath = path.startsWith("/") ? path.slice(1) : path;
  if (cleanPath.startsWith("storage/")) {
    return `${baseUrl}/${cleanPath}`;
  }
  return `${baseUrl}/storage/${cleanPath}`;
};

const getNewsImage = (newsItem) => {
  if (!newsItem) return "";
  let imagePath = newsItem.image;
  if (newsItem.images && newsItem.images.length > 0) {
    imagePath = newsItem.images[0];
  }
  if (!imagePath) return "";
  return getImageUrl(imagePath);
};

const stripTags = (html) => {
  if (!html) return "";
  const tmp = document.createElement("DIV");
  tmp.innerHTML = html;
  return (tmp.textContent || tmp.innerText || "").substring(0, 150) + "...";
};

const formatDate = (dateString) => {
  if (!dateString) return "";
  return new Intl.DateTimeFormat("id-ID", {
    day: "numeric",
    month: "long",
    year: "numeric",
  }).format(new Date(dateString));
};

const formatDay = (dateString) => {
  if (!dateString) return "01";
  return new Date(dateString).getDate().toString().padStart(2, "0");
};

const formatMonth = (dateString) => {
  if (!dateString) return "Jan";
  const months = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "Mei",
    "Jun",
    "Jul",
    "Agt",
    "Sep",
    "Okt",
    "Nov",
    "Des",
  ];
  return months[new Date(dateString).getMonth()];
};

watch(
  () => props.announcements,
  () => {
    nextTick(() => {
      setTimeout(checkScroll, 500);
    });
  },
  { deep: true }
);

onMounted(() => {
  resizeObserver = new ResizeObserver(() => {
    checkScroll();
  });
  if (announcementsWrapper.value) {
    resizeObserver.observe(announcementsWrapper.value);
  }
  setTimeout(checkScroll, 600);
});

onBeforeUnmount(() => {
  if (resizeObserver) resizeObserver.disconnect();
});
</script>

<template>
  <section
    class="relative py-8 md:rounded-lg -mx-6 md:mx-0 overflow-hidden px-6 md:px-0 bg-white dark:bg-slate-800 shadow-lg"
  >
    <div class="w-full max-w-full container mx-auto px-0 md:px-4">
      <div
        class="mb-4 md:mb-10 border-b border-slate-200 dark:border-slate-700 pb-4 md:pb-0"
      >
        <div class="relative block">
          <h2
            class="text-2xl md:text-4xl font-bold text-slate-800 dark:text-white pl-4 border-l-4 border-blue-600 dark:border-blue-500 mb-4 sm:mb-2"
            style="font-family: 'Oswald', sans-serif"
          >
            Berita & Pengumuman
          </h2>
          <div
            class="flex flex-col md:flex-row md:justify-between md:items-end ml-0 sm:ml-4 gap-4"
          >
            <p
              class="text-slate-800/80 dark:text-slate-300 text-sm md:text-base max-w-2xl"
            >
              Ikuti terus perkembangan informasi terbaru seputar kegiatan, prestasi, dan
              agenda penting di lingkungan sekolah kami.
            </p>
            <router-link
              to="/berita"
              class="hidden md:inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-semibold transition-colors shrink-0 md:pb-4 md:-mb-px border-b-2 border-transparent hover:border-blue-600 dark:hover:border-blue-400"
            >
              Lihat berita lainnya
              <PhCaretRight class="w-4 h-4 ml-1" />
            </router-link>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-0 lg:gap-8 items-stretch">
        <div class="lg:col-span-2 flex flex-col gap-6">
          <template v-if="isLoadingNews">
            <div
              class="rounded-lg overflow-hidden shadow-lg h-[280px] sm:h-[350px] md:h-[400px] w-full bg-slate-200 dark:bg-slate-700 animate-pulse relative"
            >
              <div class="absolute bottom-0 left-0 p-5 md:p-8 w-full space-y-3">
                <div class="h-6 w-20 bg-slate-300 dark:bg-slate-600 rounded-full"></div>
                <div class="h-8 w-3/4 bg-slate-300 dark:bg-slate-600 rounded"></div>
              </div>
            </div>
          </template>

          <template v-else>
            <router-link
              v-if="mainNews"
              :to="`/artikel/${mainNews.slug}`"
              class="group relative rounded-lg overflow-hidden shadow-lg h-[280px] sm:h-[350px] md:h-[400px] w-full block transition-all duration-500"
            >
              <img
                v-if="getNewsImage(mainNews)"
                :src="getNewsImage(mainNews)"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                :alt="mainNews.title"
              />
              <div
                v-else
                class="absolute inset-0 w-full h-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center"
              >
                <PhNewspaper class="w-16 h-16 text-slate-400 opacity-50" />
              </div>
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"
              ></div>
              <div class="absolute bottom-0 left-0 p-5 md:p-8 w-full">
                <span
                  class="inline-block px-3 py-1 mb-3 text-sm font-bold text-white bg-blue-600 rounded-full"
                  style="font-family: 'Kalam', cursive"
                >
                  {{ mainNews.category || "Kegiatan" }}
                </span>
                <h3
                  class="text-lg sm:text-xl md:text-3xl font-bold text-white mb-1 sm:mb-2 group-hover:text-blue-300 transition-colors line-clamp-2"
                >
                  {{ mainNews.title }}
                </h3>
                <p class="text-gray-200 text-xs sm:text-sm md:text-base line-clamp-2">
                  {{ mainNews.excerpt || stripTags(mainNews.content) }}
                </p>
                <div
                  class="mt-4 flex items-center justify-between text-gray-300 text-xs md:text-sm"
                >
                  <div class="flex items-center">
                    <PhCalendarBlank class="w-4 h-4 mr-1.5" />
                    {{ formatDate(mainNews.created_at) }}
                  </div>
                  <span class="flex items-center font-medium">
                    <PhEye class="w-4 h-4 mr-1.5 text-blue-400" />
                    {{ mainNews.views || 0 }}
                  </span>
                </div>
              </div>
            </router-link>
            <div
              v-else
              class="h-[280px] sm:h-[350px] md:h-[400px] w-full bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center"
            >
              <span class="text-slate-400">Berita Belum Tersedia</span>
            </div>
          </template>

          <div class="relative">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
              <template v-if="isLoadingNews">
                <div
                  v-for="i in 3"
                  :key="'skel-news-' + i"
                  class="bg-white dark:bg-slate-800 rounded-lg overflow-hidden shadow-sm border border-gray-100 animate-pulse sm:h-64 flex sm:flex-col"
                ></div>
              </template>
              <template v-else>
                <router-link
                  v-for="(news, index) in subNews"
                  :key="news.id"
                  :to="`/artikel/${news.slug}`"
                  class="group bg-white dark:bg-slate-800 rounded-lg overflow-hidden shadow-sm hover:shadow-lg border border-gray-100 dark:border-slate-700 flex flex-row sm:flex-col h-full transition-all duration-500"
                  :class="{ 'sm:hidden xl:flex': index === 2 }"
                >
                  <div class="w-2/5 sm:w-full sm:h-40 overflow-hidden relative shrink-0">
                    <img
                      v-if="getNewsImage(news)"
                      :src="getNewsImage(news)"
                      class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                      :alt="news.title"
                    />
                    <div
                      v-else
                      class="absolute inset-0 w-full h-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center"
                    >
                      <PhNewspaper class="w-8 h-8 text-slate-400 opacity-50" />
                    </div>
                  </div>
                  <div
                    class="p-3 sm:p-4 flex flex-col flex-grow justify-center sm:justify-start"
                  >
                    <span
                      class="text-xs sm:text-sm font-bold tracking-wide text-blue-600 mb-1"
                      style="font-family: 'Kalam', cursive"
                    >
                      {{ news.category || "Berita" }}
                    </span>
                    <h4
                      class="font-bold text-blue-950 dark:text-slate-100 text-sm sm:text-base mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2"
                    >
                      {{ news.title }}
                    </h4>
                    <p
                      class="text-xs text-gray-500 dark:text-slate-400 line-clamp-2 mb-2"
                    >
                      {{ news.excerpt || stripTags(news.content) }}
                    </p>
                    <div
                      class="mt-auto flex items-center justify-between text-gray-500 dark:text-slate-400 text-[10px] sm:text-xs pt-1"
                    >
                      <div class="flex items-center">
                        <PhCalendarBlank class="w-3 h-3 mr-1" />
                        {{ formatDate(news.created_at) }}
                      </div>
                      <span class="flex items-center font-medium"
                        ><PhEye class="w-3 h-3 mr-1 text-blue-400" />
                        {{ news.views || 0 }}</span
                      >
                    </div>
                  </div>
                </router-link>
              </template>
            </div>
          </div>
        </div>

        <div class="lg:col-span-1 relative h-[550px] lg:h-auto mt-0 md:mt-8 lg:mt-0">
          <div
            class="absolute inset-0 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-100 dark:border-slate-700 flex flex-col overflow-hidden"
          >
            <div
              class="p-5 md:p-6 border-b border-blue-100 dark:border-slate-700/50 bg-gray-50 dark:bg-slate-700/50 z-20 flex items-center justify-between shadow-sm"
            >
              <h3
                class="text-md md:text-xl font-bold text-slate-800 dark:text-slate-200 flex items-center"
              >
                <PhBell class="w-5 h-5 mr-2 text-slate-800 dark:text-slate-200" />
                Pengumuman
              </h3>
            </div>

            <div
              ref="announcementsWrapper"
              class="relative flex-1 overflow-hidden group bg-white dark:bg-slate-800 z-10 min-h-0"
            >
              <div
                v-if="shouldAutoScroll && !isLoadingNews"
                class="absolute top-0 left-0 w-full h-10 bg-gradient-to-b from-white dark:from-slate-800 to-transparent z-20 pointer-events-none"
              ></div>
              <div
                v-if="shouldAutoScroll && !isLoadingNews"
                class="absolute bottom-0 left-0 w-full h-10 bg-gradient-to-t from-white dark:from-slate-800 to-transparent z-20 pointer-events-none"
              ></div>

              <template v-if="isLoadingNews">
                <div class="flex flex-col p-5 space-y-6">
                  <div
                    v-for="i in 3"
                    :key="'skel-ann-' + i"
                    class="h-16 bg-slate-100 dark:bg-slate-700/50 rounded-lg animate-pulse w-full"
                  ></div>
                </div>
              </template>
              <template v-else>
                <div
                  ref="announcementsContent"
                  class="flex flex-col"
                  :class="{
                    'animate-scroll-y group-hover:[animation-play-state:paused]': shouldAutoScroll,
                    'overflow-y-auto custom-scrollbar': !shouldAutoScroll,
                  }"
                >
                  <template v-if="announcements.length > 0">
                    <template v-for="i in shouldAutoScroll ? 2 : 1" :key="'loop-' + i">
                      <div class="flex flex-col">
                        <router-link
                          v-for="item in announcements"
                          :key="item.id + '-' + i"
                          :to="`/artikel/${item.slug}`"
                          class="p-5 border-b border-blue-50 dark:border-slate-700/50 hover:bg-blue-100/50 dark:hover:bg-slate-700 transition-colors flex items-start"
                        >
                          <div
                            class="bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 rounded-lg p-2 mr-4 text-center min-w-[60px] flex-shrink-0"
                            style="font-family: 'Kalam', cursive"
                          >
                            <span class="block text-xl font-bold leading-none">{{
                              formatDay(item.created_at)
                            }}</span>
                            <span class="block text-xs uppercase mt-1 font-semibold">{{
                              formatMonth(item.created_at)
                            }}</span>
                          </div>
                          <div>
                            <h4
                              class="font-semibold text-blue-950 dark:text-slate-100 text-sm mb-1 line-clamp-2"
                            >
                              {{ item.title }}
                            </h4>
                            <p
                              class="text-xs text-gray-500 dark:text-slate-400 line-clamp-3 leading-relaxed"
                            >
                              {{ item.excerpt || stripTags(item.content) }}
                            </p>
                          </div>
                        </router-link>
                      </div>
                    </template>
                  </template>
                  <div v-else class="p-6 text-center text-gray-500 m-auto">
                    Tidak ada pengumuman saat ini.
                  </div>
                </div>
              </template>
            </div>

            <div
              class="p-4 bg-white dark:bg-slate-800 z-20 border-t text-center shadow-md"
            >
              <router-link
                to="/berita"
                class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors"
                >Lihat Semua Pengumuman</router-link
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
