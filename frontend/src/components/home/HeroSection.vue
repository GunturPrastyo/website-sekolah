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
  const baseUrl = import.meta.env.VITE_API_URL;
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
        speed: 2000,
        autoplay: totalImages > 1 ? { delay: 6000, disableOnInteraction: false } : false,
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
  if (bgSwiperInstance) bgSwiperInstance.destroy(true, true);
  if (statsSwiperInstance) statsSwiperInstance.destroy(true, true);
});
</script>

<template>
  <header
    class="relative z-0 flex flex-col justify-end min-h-screen lg:h-screen text-white overflow-hidden bg-slate-950 pb-16 md:pb-24"
  >
    <!-- Latar Belakang Gambar / Video -->
    <div class="absolute inset-0 -z-10 overflow-hidden bg-slate-950">
      <!-- Tampilkan Slider Jika Ada Gambar -->
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
              class="w-full h-full object-cover opacity-60 mix-blend-screen dark:mix-blend-overlay"
              alt="Home Background Slider"
            />
          </div>
        </div>
      </div>

      <!-- HAPUS VIDEO DEFAULT: Kini hanya menampilkan gradien kosong jika belum ada gambar -->

      <!-- Gradien Overlay untuk menajamkan teks -->
      <div
        class="absolute inset-0 bg-gradient-to-b from-slate-900/30 via-slate-950/60 to-slate-950 z-10 pointer-events-none"
      ></div>
    </div>

    <!-- Konten Utama Hero Section -->
    <div class="container mx-auto px-6 md:px-12 z-20 w-full max-w-7xl">
      <!-- Area Judul dan Motto (Rata Kiri) -->
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

      <!-- Area Statistik Profesional (Gaya Dashboard Modern) -->
      <div
        class="w-full border-t border-white/10 pt-8 transition-all duration-1000 delay-500 ease-out"
        :class="showSubtitle ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
      >
        <div
          class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8 md:gap-12"
        >
          <!-- Teks Pengantar Statistik di Sebelah Kiri -->
          <div class="md:w-1/4 lg:w-1/5 shrink-0">
            <h4
              class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-1"
              style="font-family: 'Oswald', sans-serif"
            >
              Sekilas Pandang
            </h4>
            <p class="text-xs text-slate-500 font-medium">Data & Statistik Sekolah</p>
          </div>

          <!-- Swiper Angka Statistik di Sebelah Kanan -->
          <div class="w-full md:w-3/4 lg:w-4/5 overflow-hidden">
            <div class="swiper stats-swiper !overflow-visible">
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

/* Opsional: Membuat navigasi titik swiper jika nantinya dibutuhkan */
:deep(.swiper-slide) {
  height: auto;
}
</style>
