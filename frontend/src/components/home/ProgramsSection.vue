<script setup>
import { onBeforeUnmount, watch, nextTick } from "vue";
import {
  PhCaretRight,
  PhFlask,
  PhGlobe,
  PhTranslate,
  PhMonitor,
  PhBookOpen,
  PhGraduationCap,
  PhCalculator,
  PhBuildings,
  PhBriefcase,
  PhPalette,
  PhStethoscope,
} from "@phosphor-icons/vue";
import Swiper from "swiper/bundle";
import "swiper/swiper-bundle.css";

const props = defineProps({
  appearanceSettings: { type: Object, required: true },
  programs: { type: Array, required: true },
  isLoadingPrograms: { type: Boolean, required: true },
});

let jurusanSwiperInstance = null;

const programGradients = [
  "from-blue-900 to-slate-900 group-hover:from-blue-800",
  "from-cyan-900 to-slate-900 group-hover:from-cyan-800",
  "from-sky-900 to-slate-900 group-hover:from-sky-800",
  "from-indigo-900 to-slate-900 group-hover:from-indigo-800",
  "from-purple-900 to-slate-900 group-hover:from-purple-800",
];

const programIconMap = {
  PhFlask,
  PhGlobe,
  PhTranslate,
  PhMonitor,
  PhBookOpen,
  PhGraduationCap,
  monitor: PhMonitor,
  microscope: PhFlask,
  "book-open": PhBookOpen,
  languages: PhTranslate,
  calculator: PhCalculator,
  building: PhBuildings,
  briefcase: PhBriefcase,
  palette: PhPalette,
  stethoscope: PhStethoscope,
  globe: PhGlobe,
};

const iconList = [PhFlask, PhGlobe, PhTranslate, PhMonitor, PhBookOpen, PhGraduationCap];

const getProgramGradient = (index) => {
  return programGradients[index % programGradients.length];
};

const getProgramIcon = (iconOrIndex) => {
  if (typeof iconOrIndex === "string" && programIconMap[iconOrIndex]) {
    return programIconMap[iconOrIndex];
  }
  if (typeof iconOrIndex === "number") {
    return iconList[iconOrIndex % iconList.length];
  }
  return PhGraduationCap;
};

const stripTags = (html) => {
  if (!html) return "";
  const tmp = document.createElement("DIV");
  tmp.innerHTML = html;
  return (tmp.textContent || tmp.innerText || "").substring(0, 150) + "...";
};

const initJurusanSwiper = () => {
  if (jurusanSwiperInstance) {
    jurusanSwiperInstance.destroy(true, true);
  }

  const totalPrograms = props.programs.length;
  const desktopSlides = totalPrograms > 0 ? Math.min(3, totalPrograms) : 3;
  const tabletSlides = totalPrograms > 0 ? Math.min(2, totalPrograms) : 2;
  const enableLoop = totalPrograms > 3;

  jurusanSwiperInstance = new Swiper(".jurusan-swiper", {
    loop: enableLoop,
    speed: 800,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    slidesPerView: 1,
    spaceBetween: 0,
    pagination: { el: ".jurusan-pagination", clickable: true },
    breakpoints: {
      768: {
        slidesPerView: tabletSlides,
        spaceBetween: 0,
      },
      1024: {
        slidesPerView: desktopSlides,
        spaceBetween: 0,
      },
    },
  });
};

watch(
  () => props.programs,
  () => {
    nextTick(() => {
      initJurusanSwiper();
    });
  },
  { deep: true }
);

onBeforeUnmount(() => {
  if (jurusanSwiperInstance) jurusanSwiperInstance.destroy(true, true);
});
</script>

<template>
  <section
    class="relative -my-12 -mx-6 md:-mx-6 lg:-mx-0 overflow-hidden px-0 md:px-6 mb-10 md:mb-16"
  >
    <div class="w-full max-w-full container relative z-10 mx-auto">
      <div
        class="relative overflow-hidden shadow-2xl min-h-[340px] md:min-h-[450px] flex items-center md:rounded-lg bg-center bg-cover lg:bg-fixed"
        :style="{
          backgroundImage: `url(${
            appearanceSettings.programCoverImage || '/img/jurusan.jpg'
          })`,
        }"
      >
        <div
          class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/80 to-slate-800/50 backdrop-blur-[2px]"
        ></div>
        <div class="relative z-10 -mt-12 pl-4 pr-4 md:px-14 max-w-2xl text-white">
          <h2
            class="text-3xl md:text-5xl font-bold mb-2 md:mb-4"
            style="font-family: 'Oswald', sans-serif"
          >
            Pilihan Jurusan Unggulan
          </h2>
          <p class="text-sm md:text-xl text-gray-200">
            Temukan minat dan bakatmu dengan pilihan jurusan terbaik untuk masa depanmu.
          </p>
        </div>
      </div>

      <div class="relative z-20 mt-[-80px] md:mt-[-100px]">
        <div class="max-w-[90%] mx-auto md:max-w-[95%]">
          <div
            class="swiper jurusan-swiper rounded-lg md:rounded-2xl overflow-hidden shadow-xl"
          >
            <div class="swiper-wrapper items-stretch">
              <template v-if="isLoadingPrograms">
                <div
                  v-for="i in 3"
                  :key="'skeleton-prog-' + i"
                  class="swiper-slide !h-auto relative min-h-[320px] overflow-hidden border-b border-white/10 md:border-b-0 md:border-r md:border-white/10 bg-slate-800 animate-pulse"
                >
                  <div
                    class="relative z-10 p-6 pb-14 sm:p-8 flex flex-col justify-start h-full"
                  >
                    <div class="w-14 h-14 rounded-xl bg-slate-700 mb-4"></div>
                    <div class="h-8 bg-slate-700 rounded w-1/2 mb-4"></div>
                    <div class="space-y-2 mb-4">
                      <div class="h-4 bg-slate-700 rounded w-full"></div>
                      <div class="h-4 bg-slate-700 rounded w-5/6"></div>
                      <div class="h-4 bg-slate-700 rounded w-4/6"></div>
                    </div>
                    <div class="w-32 h-4 bg-slate-700 rounded mt-auto"></div>
                  </div>
                </div>
              </template>

              <template v-else-if="programs.length > 0">
                <div
                  v-for="(program, index) in programs"
                  :key="program.id"
                  class="swiper-slide !h-auto relative min-h-[320px] overflow-hidden border-b border-white/10 md:border-b-0 md:border-r md:border-white/10 group cursor-pointer"
                >
                  <div
                    class="absolute inset-0 bg-gradient-to-br transition-colors duration-500"
                    :class="getProgramGradient(index)"
                  ></div>

                  <div
                    class="relative z-10 p-6 pb-14 sm:p-8 flex flex-col justify-start h-full text-white"
                  >
                    <div
                      class="w-14 h-14 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-white mb-4 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-inner"
                    >
                      <component
                        :is="getProgramIcon(program.icon || index)"
                        class="w-7 h-7"
                      />
                    </div>

                    <h3 class="text-xl md:text-2xl font-bold mb-2 text-white">
                      {{ program.title }}
                    </h3>

                    <p
                      class="text-xs sm:text-sm md:text-base text-blue-100 mb-4 leading-relaxed line-clamp-3"
                    >
                      {{ program.description || stripTags(program.content) }}
                    </p>

                    <router-link
                      to="/program-jurusan"
                      class="inline-flex items-center text-xs sm:text-sm font-bold text-white group-hover:text-blue-200 transition-colors w-fit mt-auto"
                    >
                      Lihat Selengkapnya
                      <PhCaretRight
                        class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1.5 transition-transform duration-300"
                      />
                    </router-link>
                  </div>
                </div>
              </template>

              <template v-else>
                <div class="p-8 text-center text-white w-full bg-slate-800">
                  <p>Data program jurusan belum tersedia.</p>
                </div>
              </template>
            </div>

            <div
              class="jurusan-pagination absolute bottom-4 left-0 z-30 flex w-full justify-center mb-0"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
