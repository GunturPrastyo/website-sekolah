<script setup>
import { ref, onMounted, nextTick, computed } from "vue";
import api from "@/api/index.js";
import PageHeader from "@/components/PageHeader.vue";
import {
  PhListChecks,
  PhCheckCircle,
  PhGitMerge,
  PhArrowDown,
  PhMapPin,
  PhMedal,
  PhHandshake,
  PhBriefcase,
  PhDownloadSimple,
} from "@phosphor-icons/vue";
import Swiper from "swiper/bundle";
import "swiper/swiper-bundle.css";

const ppdbInfo = ref(null);
const appearanceSettings = ref({});
const isLoading = ref(true);

const iconMap = [PhMapPin, PhMedal, PhHandshake, PhBriefcase];

const getIcon = (index) => {
  return iconMap[index % iconMap.length];
};

const parseJson = (data) => {
  if (!data) return [];
  if (typeof data === "string") {
    try {
      return JSON.parse(data);
    } catch (e) {
      if (data.includes("\n")) {
        return data
          .split("\n")
          .map((s) => s.trim())
          .filter((s) => s);
      }
      return [];
    }
  }
  return Array.isArray(data) ? data : [];
};

// Computed Properties untuk mapping data array
const syaratList = computed(() => {
  if (!ppdbInfo.value) return [];
  return parseJson(ppdbInfo.value.syarat || ppdbInfo.value.requirements);
});

const alurList = computed(() => {
  if (!ppdbInfo.value) return [];
  return parseJson(ppdbInfo.value.alur || ppdbInfo.value.flow);
});

const jalurList = computed(() => {
  if (!ppdbInfo.value) return [];
  const rawJalur = parseJson(ppdbInfo.value.jalur || ppdbInfo.value.paths);
  return rawJalur.map((j) => ({
    title: j.title || j.nama || "",
    kuota: j.kuota || "",
    desc: j.desc || j.description || "",
  }));
});

const fetchInitialData = async () => {
  try {
    const [ppdbResponse, settingsResponse] = await Promise.all([
      api.get("/api/ppdb-info"),
      api.get("/api/settings"),
    ]);

    ppdbInfo.value = ppdbResponse.data?.data || ppdbResponse.data;

    if (settingsResponse.data?.success) {
      appearanceSettings.value = settingsResponse.data.data;
    }
  } catch (error) {
    console.error("Gagal memuat data halaman PPDB:", error);
  } finally {
    isLoading.value = false;
    if (jalurList.value.length > 0) {
      nextTick(() => {
        initSwiper();
      });
    }
  }
};

// Inisialisasi Slider Swiper
const initSwiper = () => {
  new Swiper(".jalur-swiper", {
    direction: "horizontal",
    loop: true,
    loopAdditionalSlides: 4,
    speed: 800,
    autoplay: {
      delay: 3500,
      disableOnInteraction: false,
    },
    slidesPerView: 1.2,
    centeredSlides: true,
    spaceBetween: 20,
    mousewheel: {
      forceToAxis: true,
    },
    grabCursor: true,
    pagination: {
      el: ".jalur-pagination",
      clickable: true,
    },
    breakpoints: {
      1024: {
        direction: "vertical",
        slidesPerView: 1.5,
        spaceBetween: 24,
      },
    },
  });
};

onMounted(() => {
  fetchInitialData();
});
</script>

<template>
  <div>
    <div>
      <PageHeader
        v-if="!isLoading"
        :badge="`Tahun Ajaran ${new Date().getFullYear()}/${
          new Date().getFullYear() + 1
        }`"
        title="Informasi PPDB"
        description="Pusat Informasi Penerimaan Peserta Didik Baru (PPDB). Temukan panduan lengkap mengenai tata cara pendaftaran, persyaratan, alur, dan berbagai jalur penerimaan yang tersedia untuk calon peserta didik baru."
        :bgImage="appearanceSettings.ppdbBackgroundImage"
      >
        <template #bg-ornament>
          <div
            v-if="ppdbInfo && ppdbInfo.brosur"
            class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20"
          >
            <a
              :href="ppdbInfo.brosur"
              target="_blank"
              class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-colors shadow-lg"
            >
              <PhDownloadSimple class="w-4 h-4 mr-2" />
              Unduh Brosur PPDB
            </a>
          </div>
        </template>
      </PageHeader>

      <section
        class="py-12 md:py-10 px-6 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700"
      >
        <div class="container mx-auto max-w-full px-0 lg:px-16">
          <div class="text-center mb-10">
            <h2
              class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white"
              style="font-family: 'Oswald', sans-serif"
            >
              Informasi & Persyaratan
            </h2>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
              Pastikan Anda membaca ketentuan berikut sebelum mengisi formulir.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
            <div
              class="bg-blue-50/20 dark:bg-slate-700/30 p-6 md:p-8 rounded-xl border border-gray-300 dark:border-slate-700 shadow-lg"
            >
              <h3
                class="text-2xl font-bold text-gray-900 dark:text-white mb-5 flex items-center"
                style="font-family: 'Oswald', sans-serif"
              >
                <PhListChecks class="w-6 h-6 mr-2 text-gray-900 dark:text-white" />
                Syarat Pendaftaran
              </h3>

              <ul v-if="isLoading" class="space-y-4 animate-pulse">
                <li v-for="i in 4" :key="'skel-syarat-' + i" class="flex items-start">
                  <div
                    class="w-5 h-5 bg-gray-300 dark:bg-slate-600 rounded-full mr-3 shrink-0 mt-0.5"
                  ></div>
                  <div
                    class="h-4 bg-gray-300 dark:bg-slate-600 rounded w-full mt-1.5"
                  ></div>
                </li>
              </ul>
              <ul v-else-if="syaratList.length > 0" class="space-y-4">
                <li
                  v-for="(syarat, index) in syaratList"
                  :key="index"
                  class="flex items-start"
                >
                  <PhCheckCircle class="w-5 h-5 text-green-500 mr-3 shrink-0 mt-0.5" />
                  <span
                    class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"
                    >{{
                      typeof syarat === "string" ? syarat : syarat.text || syarat.desc
                    }}</span
                  >
                </li>
              </ul>
              <p v-else class="text-sm text-gray-500 dark:text-gray-400 italic">
                Belum ada data syarat pendaftaran.
              </p>
            </div>

            <div
              class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-xl border border-gray-300 dark:border-slate-700 shadow-lg"
            >
              <h3
                class="text-2xl font-bold text-gray-900 dark:text-white mb-5 flex items-center"
                style="font-family: 'Oswald', sans-serif"
              >
                <PhGitMerge class="w-6 h-6 mr-2 text-gray-900 dark:text-white" />
                Alur Pendaftaran
              </h3>

              <div
                v-if="isLoading"
                class="space-y-6 animate-pulse relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-gray-300 dark:before:from-slate-600 before:to-transparent"
              >
                <div
                  v-for="i in 3"
                  :key="'skel-alur-' + i"
                  class="relative flex items-center gap-4"
                >
                  <div
                    class="w-10 h-10 rounded-full bg-gray-200 dark:bg-slate-700 shrink-0 z-10"
                  ></div>
                  <div class="flex-1">
                    <div
                      class="h-4 bg-gray-300 dark:bg-slate-600 rounded w-1/2 mb-2"
                    ></div>
                    <div class="h-3 bg-gray-300 dark:bg-slate-600 rounded w-full"></div>
                  </div>
                </div>
              </div>
              <div
                v-else-if="alurList.length > 0"
                class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-blue-200 before:via-blue-200 dark:before:via-slate-600 before:to-transparent"
              >
                <div
                  v-for="(alur, index) in alurList"
                  :key="index"
                  class="relative flex items-center gap-4"
                >
                  <div
                    class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border-2 flex items-center justify-center font-bold shrink-0 z-10"
                    :class="
                      index === alurList.length - 1
                        ? 'border-gray-300 dark:border-slate-600 text-gray-400'
                        : 'border-blue-500 dark:border-blue-400 text-blue-600 dark:text-blue-400'
                    "
                  >
                    {{ index + 1 }}
                  </div>
                  <div>
                    <h4
                      class="font-bold text-sm"
                      :class="
                        index === alurList.length - 1
                          ? 'text-gray-400 dark:text-gray-500'
                          : 'text-blue-950 dark:text-white'
                      "
                    >
                      {{ alur.title || alur.nama }}
                    </h4>
                    <p
                      class="text-xs mt-1"
                      :class="
                        index === alurList.length - 1
                          ? 'text-gray-400 dark:text-gray-500'
                          : 'text-gray-500 dark:text-gray-400'
                      "
                    >
                      {{ alur.desc || alur.description }}
                    </p>
                  </div>
                </div>
              </div>
              <p v-else class="text-sm text-gray-500 dark:text-gray-400 italic">
                Belum ada data alur pendaftaran.
              </p>
            </div>
          </div>
        </div>
      </section>

      <section
        class="py-12 md:py-8 px-6 bg-blue-950 dark:bg-slate-900 relative overflow-hidden border-b border-blue-900 dark:border-slate-800"
      >
        <div class="container mx-auto max-w-full px-0 lg:px-16 relative z-10">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
            <div class="lg:col-span-5 text-center lg:text-left">
              <h2
                class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight"
                style="font-family: 'Oswald', sans-serif"
              >
                Jalur Pendaftaran PPDB
              </h2>
              <p
                class="text-blue-100 dark:text-gray-300 text-base md:text-lg leading-relaxed"
              >
                Sistem Penerimaan Peserta Didik Baru terbagi menjadi 4 jalur utama.
                Pastikan Anda memilih jalur yang paling sesuai dengan kondisi dan
                kualifikasi untuk memperbesar peluang diterima.
              </p>

              <div
                class="hidden lg:flex items-center gap-3 text-yellow-400 font-semibold mt-8"
              >
                <div
                  class="w-10 h-10 rounded-full border-2 border-yellow-400 flex items-center justify-center animate-bounce"
                >
                  <PhArrowDown class="w-5 h-5" />
                </div>
                Scroll untuk melihat jalur
              </div>
            </div>

            <div
              class="lg:col-span-7 relative h-[380px] lg:h-[520px] w-full flex items-center justify-center"
            >
              <div
                v-if="isLoading"
                class="w-full h-full flex items-center justify-center animate-pulse px-4 lg:px-10 py-8"
              >
                <div
                  class="bg-blue-800/40 dark:bg-slate-800/50 rounded-xl w-full max-w-sm h-full border border-blue-700/30 dark:border-slate-700/50"
                ></div>
              </div>

              <div
                v-else-if="jalurList.length === 0"
                class="text-center w-full text-blue-200 italic"
              >
                Belum ada data jalur pendaftaran.
              </div>

              <div v-else class="fade-mask-slider w-full h-full absolute inset-0">
                <div class="swiper jalur-swiper h-full w-full lg:!py-8 lg:!px-10">
                  <div class="swiper-wrapper items-stretch">
                    <div
                      v-for="(jalur, index) in jalurList"
                      :key="index"
                      class="swiper-slide"
                    >
                      <div
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-lg hover:shadow-2xl relative group transform transition-all duration-500 h-full flex flex-col overflow-hidden border border-gray-100 dark:border-slate-700/60 border-b-4 border-b-yellow-400 dark:border-b-yellow-500"
                      >
                        <div
                          class="h-28 sm:h-36 overflow-hidden relative shrink-0 bg-blue-600 dark:bg-blue-800 transition-colors duration-500 group-hover:bg-blue-700 dark:group-hover:bg-blue-600"
                        >
                          <div
                            class="absolute top-4 left-4 px-3 py-1 bg-yellow-400 text-blue-950 text-[10px] sm:text-xs font-bold rounded-full shadow-sm border border-yellow-300"
                          >
                            Kuota {{ jalur.kuota }}
                          </div>
                        </div>

                        <div
                          class="relative z-10 px-5 sm:px-6 md:px-8 pb-16 lg:pb-8 flex-1 flex flex-col -mt-8"
                        >
                          <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-400 dark:bg-yellow-500 text-blue-950 rounded-xl sm:rounded-2xl flex items-center justify-center mb-3 shadow-md border border-yellow-300 dark:border-yellow-400 group-hover:scale-110 transition-transform"
                          >
                            <component
                              :is="getIcon(index)"
                              class="w-5 h-5 sm:w-6 sm:h-6"
                            />
                          </div>

                          <h3
                            class="text-lg sm:text-xl font-bold text-blue-950 dark:text-white mb-2"
                          >
                            {{ jalur.title }}
                          </h3>

                          <p
                            class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm leading-relaxed line-clamp-4 lg:line-clamp-none"
                          >
                            {{ jalur.desc }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="jalur-pagination absolute z-30 pointer-events-auto"></div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Custom styles for Swiper Mask & Fade */
.fade-mask-slider {
  -webkit-mask-image: linear-gradient(
    to right,
    transparent 0%,
    black 5%,
    black 95%,
    transparent 100%
  );
  mask-image: linear-gradient(
    to right,
    transparent 0%,
    black 5%,
    black 95%,
    transparent 100%
  );
}

@media (min-width: 1024px) {
  .fade-mask-slider {
    -webkit-mask-image: linear-gradient(
      to bottom,
      transparent 0%,
      black 10%,
      black 90%,
      transparent 100%
    );
    mask-image: linear-gradient(
      to bottom,
      transparent 0%,
      black 10%,
      black 90%,
      transparent 100%
    );
  }
}

/* Custom styles for Jalur Swiper pagination */
:deep(.jalur-pagination) {
  display: flex;
  align-items: center;
  justify-content: center;
  bottom: 0px !important;
  left: 0;
  width: 100%;
  flex-direction: row;
}

@media (min-width: 1024px) {
  :deep(.jalur-pagination) {
    right: 0px !important;
    left: auto;
    top: 50% !important;
    transform: translateY(-50%);
    flex-direction: column;
    width: auto;
    bottom: auto !important;
  }
}

:deep(.jalur-pagination .swiper-pagination-bullet) {
  background-color: rgba(255, 255, 255, 0.4);
  transition: all 0.3s ease;
  margin: 6px !important;
}

:deep(.jalur-pagination .swiper-pagination-bullet-active) {
  background-color: #ffffff;
  width: 24px;
  border-radius: 6px;
}

@media (min-width: 1024px) {
  :deep(.jalur-pagination .swiper-pagination-bullet-active) {
    height: 24px;
    width: 8px;
    /* default bullet width */
  }
}
</style>
