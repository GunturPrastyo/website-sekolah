<script setup>
import { onMounted, onBeforeUnmount } from "vue";
import Swiper from "swiper/bundle";
import "swiper/swiper-bundle.css";

defineProps({
  appearanceSettings: { type: Object, required: true },
});

let alasanSwiperInstance = null;

onMounted(() => {
  alasanSwiperInstance = new Swiper(".alasan-swiper", {
    loop: true,
    speed: 800,
    slidesPerView: 1,
    spaceBetween: 0,
    autoplay: { delay: 3500, disableOnInteraction: false },
    pagination: { el: ".alasan-pagination", clickable: true },
    breakpoints: {
      768: {
        slidesPerView: 4,
        spaceBetween: 24,
        allowTouchMove: false,
        loop: false,
        autoplay: false,
      },
    },
  });
});

onBeforeUnmount(() => {
  if (alasanSwiperInstance) alasanSwiperInstance.destroy(true, true);
});
</script>

<template>
  <section
    class="relative pt-4 -mx-6 md:-mx-6 lg:-mx-0 overflow-hidden px-0 md:px-6 mb-8"
  >
    <div class="w-full max-w-full container z-10 mx-auto">
      <!-- HEADER -->
      <div class="mb-8 px-2 md:px-0 mt-4 sm:mt-6">
        <div class="relative block">
          <h2
            class="text-2xl md:text-4xl font-bold text-slate-800 dark:text-white mb-2"
            style="font-family: 'Oswald', sans-serif"
          >
            Kenapa Pilih Sekolah Ini?
          </h2>
          <p class="text-slate-800/80 dark:text-slate-300 text-sm md:text-base max-w-2xl">
            Kenali lebih jauh berbagai keunggulan dan fasilitas yang kami sediakan
          </p>
        </div>
      </div>

      <!-- SWIPER / GRID -->
      <div class="swiper alasan-swiper overflow-visible px-4 md:px-0">
        <div
          class="swiper-wrapper md:grid md:grid-cols-4 md:auto-rows-[280px] lg:auto-rows-[320px] md:gap-6"
        >
          <!-- FASILITAS -->
          <div
            class="swiper-slide md:!w-auto relative bg-center bg-cover md:bg-fixed sm:rounded-lg overflow-hidden shadow-lg hover:shadow-2xl group flex flex-col min-h-[290px] md:min-h-0 md:col-span-2 md:row-span-2 transition-shadow duration-500 before:absolute before:inset-0 before:bg-inherit before:blur-[3px] md:before:blur-none"
            :style="{
              backgroundImage: `url(${
                appearanceSettings.benefitFasilitasImage || '/img/fasilitas.jpg'
              })`,
            }"
          >
            <div
              class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-900/80 to-slate-800/40 group-hover:via-slate-800/70 group-hover:to-slate-700/40 transition-all duration-500"
            ></div>
            <div
              class="relative z-10 p-6 pt-10 sm:p-8 flex flex-col justify-start items-center text-left md:justify-end md:items-start h-full w-full min-h-[290px] md:min-h-0"
            >
              <div class="flex flex-col items-start w-full max-w-sm mx-auto md:mx-0">
                <h3
                  class="text-xl md:text-2xl font-bold text-white mb-2 group-hover:text-blue-300 transition-colors duration-300"
                  style="font-family: 'Oswald', sans-serif"
                >
                  Fasilitas Lengkap
                </h3>
                <p
                  class="text-slate-300 text-sm md:text-base leading-relaxed mb-5 line-clamp-3 group-hover:text-white transition-colors duration-300"
                >
                  Didukung dengan laboratorium modern, ruang kelas yang nyaman, serta
                  sarana olahraga lengkap untuk menunjang kegiatan belajar.
                </p>
                <router-link
                  to="/fasilitas"
                  class="inline-flex items-center text-sm font-bold text-blue-400 group-hover:text-blue-300 transition-colors w-fit"
                >
                  Jelajahi Fasilitas
                  <svg
                    class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1.5 transition-transform duration-300"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7"
                    ></path>
                  </svg>
                </router-link>
              </div>
            </div>
          </div>

          <!-- GURU -->
          <div
            class="swiper-slide md:!w-auto relative bg-center bg-cover md:bg-fixed sm:rounded-lg overflow-hidden shadow-lg hover:shadow-2xl group flex flex-col min-h-[290px] md:min-h-0 md:col-span-2 md:row-span-1 transition-shadow duration-500 before:absolute before:inset-0 before:bg-inherit before:blur-[3px] md:before:blur-none"
            :style="{
              backgroundImage: `url(${
                appearanceSettings.benefitGuruImage || '/img/pengajar.jpg'
              })`,
            }"
          >
            <div
              class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-900/80 to-slate-800/40 group-hover:via-slate-800/70 group-hover:to-slate-700/40 transition-all duration-500"
            ></div>
            <div
              class="relative z-10 p-6 pt-10 sm:p-8 flex flex-col justify-start items-center text-left md:justify-end md:items-start h-full w-full min-h-[290px] md:min-h-0"
            >
              <div class="flex flex-col items-start w-full max-w-sm mx-auto md:mx-0">
                <h3
                  class="text-xl md:text-2xl font-bold text-white mb-2 group-hover:text-blue-300 transition-colors duration-300"
                  style="font-family: 'Oswald', sans-serif"
                >
                  Guru Berpengalaman
                </h3>
                <p
                  class="text-slate-300 text-sm leading-relaxed mb-4 line-clamp-3 md:line-clamp-2 group-hover:text-white transition-colors duration-300"
                >
                  Tenaga pendidik profesional yang siap membimbing serta mendampingi siswa
                  mencapai potensi terbaiknya.
                </p>
                <router-link
                  to="/guru-staf"
                  class="inline-flex items-center text-sm font-bold text-blue-400 group-hover:text-blue-300 transition-colors w-fit"
                >
                  Profil Pendidik
                  <svg
                    class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1.5 transition-transform duration-300"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7"
                    ></path>
                  </svg>
                </router-link>
              </div>
            </div>
          </div>

          <!-- PRESTASI -->
          <div
            class="swiper-slide md:!w-auto relative bg-center bg-cover md:bg-fixed sm:rounded-lg overflow-hidden shadow-lg hover:shadow-2xl group flex flex-col min-h-[290px] md:min-h-0 md:col-span-2 md:row-span-1 transition-shadow duration-500 before:absolute before:inset-0 before:bg-inherit before:blur-[3px] md:before:blur-none"
            :style="{
              backgroundImage: `url(${
                appearanceSettings.benefitPrestasiImage || '/img/prestasi.jpg'
              })`,
            }"
          >
            <div
              class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-900/80 to-slate-800/40 group-hover:via-slate-800/70 group-hover:to-slate-700/40 transition-all duration-500"
            ></div>
            <div
              class="relative z-10 p-6 pt-10 sm:p-8 flex flex-col justify-start items-center text-left md:justify-end md:items-start h-full w-full min-h-[290px] md:min-h-0"
            >
              <div class="flex flex-col items-start w-full max-w-sm mx-auto md:mx-0">
                <h3
                  class="text-xl md:text-2xl font-bold text-white mb-2 group-hover:text-blue-300 transition-colors duration-300"
                  style="font-family: 'Oswald', sans-serif"
                >
                  Prestasi Siswa
                </h3>
                <p
                  class="text-slate-300 text-sm leading-relaxed mb-4 line-clamp-3 md:line-clamp-2 group-hover:text-white transition-colors duration-300"
                >
                  Berbagai pencapaian membanggakan di tingkat regional hingga nasional
                  yang diraih oleh siswa-siswi berbakat.
                </p>
                <router-link
                  to="/prestasi"
                  class="inline-flex items-center text-sm font-bold text-blue-400 group-hover:text-blue-300 transition-colors w-fit"
                >
                  Daftar Prestasi
                  <svg
                    class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1.5 transition-transform duration-300"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7"
                    ></path>
                  </svg>
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <div
          class="alasan-pagination absolute bottom-4 z-20 w-full flex justify-center md:hidden mb-6"
        ></div>
      </div>
    </div>
  </section>
</template>
