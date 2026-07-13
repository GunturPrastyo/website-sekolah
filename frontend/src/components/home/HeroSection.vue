<script setup>
import { computed, onMounted, onBeforeUnmount } from "vue";
import { PhQuotes } from "@phosphor-icons/vue";
import api from "@/api/index.js";
import Swiper from "swiper/bundle";
import "swiper/swiper-bundle.css";

const props = defineProps({
  appearanceSettings: { type: Object, required: true },
  displayedTitle: { type: String, required: true },
  showSubtitle: { type: Boolean, required: true },
  slogan: { type: String, required: true },
  statsArray: { type: Array, required: true },
});

let statsSwiperInstance = null;

const getImageUrl = (path) => {
  if (!path) return "";
  if (path.startsWith("http") || path.startsWith("data:")) return path;
  const baseUrl = api.defaults.baseURL;
  const cleanPath = path.startsWith("/") ? path.slice(1) : path;
  if (cleanPath.startsWith("storage/")) {
    return `${baseUrl}/${cleanPath}`;
  }
  return `${baseUrl}/storage/${cleanPath}`;
};

const heroMedia = computed(() => {
  const rawData = props.appearanceSettings?.headerBeranda;

  if (!rawData || rawData === "null") {
    return { type: "video", url: "/img/footage.webm" };
  }

  let path = rawData;
  if (
    typeof rawData === "string" &&
    (rawData.startsWith("[") || rawData.startsWith("{"))
  ) {
    try {
      const parsed = JSON.parse(rawData);
      path = Array.isArray(parsed) ? parsed[0] : parsed;
    } catch (e) {
      path = rawData;
    }
  } else if (Array.isArray(rawData)) {
    path = rawData[0];
  }

  const finalUrl = getImageUrl(path);
  const isVideo = /\.(mp4|webm|ogg)$/i.test(finalUrl);

  return {
    type: isVideo ? "video" : "url",
    url: finalUrl,
  };
});

const formatStatValue = (stat) => {
  if (!stat.isNumber) return stat.value + (stat.suffix || "");
  if (stat.value >= 1000) {
    return (stat.value / 1000).toFixed(1).replace(/\.0$/, "") + "k+";
  }
  return stat.value;
};

onMounted(() => {
  statsSwiperInstance = new Swiper(".stats-swiper", {
    loop: true,
    speed: 800,
    autoplay: { delay: 3000, disableOnInteraction: false },
    slidesPerView: 2,
    spaceBetween: 20,
    breakpoints: {
      480: { slidesPerView: 3, spaceBetween: 20 },
      768: { slidesPerView: 3, spaceBetween: 30 },
      1024: { slidesPerView: 4, spaceBetween: 40 },
    },
  });
});

onBeforeUnmount(() => {
  if (statsSwiperInstance) statsSwiperInstance.destroy(true, false);
});
</script>

<template>
  <header
    class="relative z-0 flex flex-col justify-end min-h-screen lg:h-screen text-white overflow-hidden bg-slate-950 pb-16 md:pb-24"
  >
    <div class="absolute inset-0 -z-10 overflow-hidden bg-slate-950">
      <Transition name="fade-bg" mode="in-out">
        <div
          v-if="heroMedia.type !== 'video'"
          :key="'img-' + heroMedia.url"
          class="absolute inset-0 w-full h-full"
        >
          <img
            :src="heroMedia.url"
            class="w-full h-full object-cover brightness-[0.85]"
            alt="Hero Background"
          />
        </div>

        <div v-else :key="'vid-' + heroMedia.url" class="absolute inset-0 w-full h-full">
          <video
            class="w-full h-full object-cover brightness-[0.85]"
            autoplay
            loop
            muted
            playsinline
          >
            <source :src="heroMedia.url" />
          </video>
        </div>
      </Transition>

      <div
        class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-slate-950 z-10 pointer-events-none"
      ></div>
    </div>

    <div class="container mx-auto px-6 md:px-12 z-20 w-full max-w-7xl">
      <div class="max-w-4xl mb-12 lg:mb-20">
        <h1
          class="text-4xl md:text-6xl lg:text-[5rem] font-bold tracking-tight uppercase leading-none drop-shadow-xl mb-6"
          style="font-family: 'Oswald', sans-serif"
        >
          {{ displayedTitle
          }}<span class="animate-pulse font-light text-blue-500/80 ml-2">|</span>
        </h1>

        <p
          class="text-lg md:text-2xl lg:text-3xl font-light text-slate-200 drop-shadow-md leading-relaxed md:leading-normal border-l-4 border-blue-500 pl-5 sm:pl-6 transition-all duration-1000 ease-out max-w-3xl"
          :class="
            showSubtitle ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-10'
          "
          style="font-family: 'Merriweather', serif"
        >
          {{ slogan }}
        </p>
      </div>

      <div
        class="w-full border-t border-white/10 pt-8 transition-all duration-1000 delay-500 ease-out"
        :class="showSubtitle ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
      >
        <div
          class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8 md:gap-12"
        >
          <div class="md:w-1/4 lg:w-1/5 shrink-0">
            <h4
              class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-1"
              style="font-family: 'Oswald', sans-serif"
            >
              Sekilas Pandang
            </h4>
            <p class="text-xs text-slate-500 font-medium">Data & Statistik Sekolah</p>
          </div>

          <div class="w-full md:w-3/4 lg:w-4/5 overflow-hidden">
            <div class="swiper stats-swiper overflow-visible!">
              <div class="swiper-wrapper">
                <div
                  v-for="(stat, index) in statsArray"
                  :key="index"
                  class="swiper-slide border-l border-white/10 pl-6 lg:pl-8 group"
                >
                  <h3
                    class="font-light text-white text-3xl sm:text-4xl lg:text-5xl mb-2 group-hover:text-blue-400 transition-colors duration-300"
                  >
                    {{ stat.prefix
                    }}<span class="font-bold">{{ formatStatValue(stat) }}</span>
                  </h3>
                  <p
                    class="text-xs sm:text-sm font-semibold uppercase text-slate-400 tracking-wider"
                  >
                    {{ stat.label }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;1,300&family=Oswald:wght@300;400;600;700&display=swap");

:deep(.swiper-slide) {
  height: auto;
}

.fade-bg-enter-active,
.fade-bg-leave-active {
  transition: opacity 1s ease;
}

.fade-bg-enter-from,
.fade-bg-leave-to {
  opacity: 0;
}
</style>
