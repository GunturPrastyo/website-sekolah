<script setup>
import { computed, nextTick, watch, onMounted, onBeforeUnmount } from "vue";
import { PhQuotes } from "@phosphor-icons/vue";
import Swiper from "swiper/bundle";
import "swiper/swiper-bundle.css";

const props = defineProps({
  appearanceSettings: { type: Object, required: true },
  displayedTitle: { type: String, required: true },
  showSubtitle: { type: Boolean, required: true },
  slogan: { type: String, required: true },
  statsArray: { type: Array, required: true },
});

let bgSwiperInstance = null;
let statsSwiperInstance = null;

const getImageUrl = (path) => {
  if (!path) return "";
  if (path.startsWith("http") || path.startsWith("data:")) return path;
  const baseUrl = import.meta.env.VITE_API_URL || "https://api-sekolah-sma.duckdns.org";
  const cleanPath = path.startsWith("/") ? path.slice(1) : path;
  if (cleanPath.startsWith("storage/")) {
    return `${baseUrl}/${cleanPath}`;
  }
  return `${baseUrl}/storage/${cleanPath}`;
};

const homeSliderImages = computed(() => {
  const rawData = props.appearanceSettings?.loginBackground;
  if (!rawData) return [];
  if (
    typeof rawData === "string" &&
    (rawData.startsWith("[") || rawData.startsWith("{"))
  ) {
    try {
      return JSON.parse(rawData);
    } catch (e) {
      return [rawData];
    }
  }
  if (Array.isArray(rawData)) return rawData;
  return [rawData];
});

const initBgSwiper = () => {
  if (bgSwiperInstance) {
    bgSwiperInstance.destroy(true, true);
    bgSwiperInstance = null;
  }

  const totalImages = homeSliderImages.value.length;
  if (totalImages > 0) {
    nextTick(() => {
      bgSwiperInstance = new Swiper(".home-bg-swiper", {
        effect: "fade",
        fadeEffect: { crossFade: true },
        loop: totalImages > 1,
        speed: 1500,
        autoplay: totalImages > 1 ? { delay: 5000, disableOnInteraction: false } : false,
        allowTouchMove: false,
      });
    });
  }
};

const formatStatValue = (stat) => {
  if (!stat.isNumber) return stat.value + (stat.suffix || "");
  if (stat.value >= 1000) {
    return (stat.value / 1000).toFixed(1).replace(/\.0$/, "") + "k+";
  }
  return stat.value;
};

watch(
  () => props.appearanceSettings.loginBackground,
  () => {
    initBgSwiper();
  }
);

onMounted(() => {
  initBgSwiper();
  statsSwiperInstance = new Swiper(".stats-swiper", {
    loop: true,
    speed: 800,
    autoplay: { delay: 2500, disableOnInteraction: false },
    slidesPerView: 3,
    spaceBetween: 5,
    breakpoints: {
      400: { slidesPerView: 3, spaceBetween: 10 },
      640: { slidesPerView: 4, spaceBetween: 20 },
    },
  });
});

onBeforeUnmount(() => {
  if (bgSwiperInstance) bgSwiperInstance.destroy(true, true);
  if (statsSwiperInstance) statsSwiperInstance.destroy(true, true);
});
</script>

<template>
  <header
    class="relative z-0 flex flex-col items-center justify-center h-screen md:h-[90vh] lg:h-screen text-center text-white overflow-hidden bg-slate-950"
  >
    <div class="absolute inset-0 -z-10 overflow-hidden bg-slate-950">
      <div
        v-if="homeSliderImages.length > 0"
        class="swiper home-bg-swiper absolute inset-0 w-full h-full"
      >
        <div class="swiper-wrapper">
          <div
            v-for="(imgUrl, idx) in homeSliderImages"
            :key="'bg-slide-' + idx"
            class="swiper-slide w-full h-full"
          >
            <img
              :src="getImageUrl(imgUrl)"
              class="w-full h-full object-cover opacity-25 mix-blend-screen dark:mix-blend-overlay"
              alt="Home Background Slider"
            />
          </div>
        </div>
      </div>

      <video
        v-else
        autoplay
        loop
        muted
        playsinline
        class="absolute inset-0 w-full h-full object-cover opacity-30"
      >
        <source src="/img/footage.webm" type="video/webm" />
      </video>

      <div
        class="absolute inset-0 bg-gradient-to-b from-blue-950/50 via-slate-950/70 to-slate-950 dark:to-black z-10 pointer-events-none"
      ></div>
    </div>

    <div class="z-20 p-5">
      <h1
        class="text-3xl md:text-5xl lg:text-5xl font-bold tracking-wide uppercase drop-shadow-lg"
        style="font-family: 'Oswald', sans-serif"
      >
        {{ displayedTitle }}<span class="animate-pulse font-light text-white/70">|</span>
      </h1>
      <p
        class="mt-6 text-lg md:text-xl lg:text-2xl text-center transition-all duration-1000 ease-out text-gray-100 drop-shadow-lg"
        :class="showSubtitle ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5'"
        style="font-family: 'Merriweather', serif"
      >
        <span class="inline-flex items-center justify-center">
          <PhQuotes
            class="w-6 h-6 sm:w-8 sm:h-8 mr-2 hidden sm:block opacity-80"
            weight="fill"
          />
          {{ slogan }}
        </span>
      </p>
      <div
        class="w-11/12 sm:w-full max-w-96 sm:max-w-2xl mx-auto mt-12 overflow-hidden bg-white/15 backdrop-blur-sm rounded-lg shadow-lg border border-white/20 transition-all duration-1000 delay-500 ease-out"
        :class="showSubtitle ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
      >
        <div class="p-3 sm:p-4 stats-swiper">
          <div class="swiper-wrapper">
            <div
              v-for="(stat, index) in statsArray"
              :key="index"
              class="text-center swiper-slide"
            >
              <h3 class="font-bold text-white text-base min-[400px]:text-lg sm:text-2xl">
                {{ stat.prefix }}{{ formatStatValue(stat) }}
              </h3>
              <p
                class="mt-0.5 sm:mt-1 text-[9px] min-[400px]:text-[10px] sm:text-xs font-semibold uppercase text-white/80 tracking-tighter sm:tracking-normal line-clamp-1"
              >
                {{ stat.label }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>
