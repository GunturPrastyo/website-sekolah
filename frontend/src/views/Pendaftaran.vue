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

// Reactive State
const ppdbInfo = ref(null);
const appearanceSettings = ref({});
const isLoading = ref(true);

const iconMap = [PhMapPin, PhMedal, PhHandshake, PhBriefcase];

const getIcon = (index) => {
  return iconMap[index % iconMap.length];
};

// Helper parser JSON/Teks baris baru dari Database
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

// Fungsi gabungan untuk mengambil data PPDB dan Global Settings secara paralel (bersamaan)
const fetchInitialData = async () => {
  try {
    const [ppdbResponse, settingsResponse] = await Promise.all([
      api.get("/api/ppdb-info"),
      api.get("/api/settings"), // 👈 Mengambil data setting dinamis dari VPS
    ]);

    // Set data PPDB
    ppdbInfo.value = ppdbResponse.data?.data || ppdbResponse.data;

    // Set data global settings jika request sukses
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
    direction: "horizontal", // default slider horizontal untuk versi Mobile
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
      forceToAxis: true, // Menghindari halaman tertahan saat scroll beda sumbu
    },
    grabCursor: true,
    pagination: {
      el: ".jalur-pagination",
      clickable: true,
    },
    breakpoints: {
      1024: {
        direction: "vertical", // Beralih ke slider vertikal untuk versi Desktop
        slidesPerView: 1.5,
        spaceBetween: 24,
      },
    },
  });
};

// Panggil fungsi pengambilan data saat komponen dimuat
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
              class="inline-flex items-center px-6 py-3 bg-white text-blue-800 hover:bg-blue-50 text-sm font-bold rounded-full transition-all shadow-xl hover:-translate-y-1"
            >
              <PhDownloadSimple class="w-5 h-5 mr-2" weight="bold" />
              Unduh Brosur PPDB
            </a>
          </div>
        </template>
      </PageHeader>

      <!-- BAGIAN INFORMASI & PERSYARATAN (DI-UPGRADE) -->
      <section
        class="py-16 md:py-20 px-6 bg-slate-50 dark:bg-slate-900 border-b border-slate-200/60 dark:border-slate-800 relative overflow-hidden"
      >
        <!-- Ornamen Latar Belakang -->
        <div
          class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-500/5 dark:bg-blue-600/5 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/4"
        ></div>
        <div
          class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-500/5 dark:bg-indigo-600/5 rounded-full blur-3xl pointer-events-none translate-y-1/2 -translate-x-1/4"
        ></div>

        <div class="container mx-auto max-w-7xl relative z-10">
          <div class="text-center mb-14 max-w-3xl mx-auto">
            <h4
              class="text-blue-600 dark:text-blue-400 font-bold tracking-widest text-xs sm:text-sm uppercase mb-3"
              style="font-family: 'Plus Jakarta Sans', sans-serif"
            >
              Panduan Utama
            </h4>
            <h2
              class="text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-4 leading-tight tracking-tight"
              style="font-family: 'Plus Jakarta Sans', sans-serif"
            >
              Informasi & Persyaratan
            </h2>
            <p class="text-slate-600 dark:text-slate-400 text-base md:text-lg">
              Pelajari dengan saksama seluruh ketentuan dan kualifikasi administrasi yang
              dibutuhkan sebelum memulai proses pengisian formulir.
            </p>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
            <!-- KOLOM SYARAT PENDAFTARAN -->
            <div
              class="bg-white dark:bg-slate-800 rounded-3xl p-8 md:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/60 dark:border-slate-700/60 hover:shadow-xl transition-shadow duration-500"
            >
              <div
                class="flex items-center mb-8 pb-6 border-b border-slate-100 dark:border-slate-700/50"
              >
                <div
                  class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-5 shadow-inner"
                >
                  <PhListChecks
                    class="w-7 h-7 text-blue-600 dark:text-blue-400"
                    weight="fill"
                  />
                </div>
                <div>
                  <h3
                    class="text-2xl font-bold text-slate-900 dark:text-white"
                    style="font-family: 'Plus Jakarta Sans', sans-serif"
                  >
                    Syarat Pendaftaran
                  </h3>
                  <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mt-1">
                    Dokumen yang wajib dilengkapi
                  </p>
                </div>
              </div>

              <ul v-if="isLoading" class="space-y-6 animate-pulse">
                <li v-for="i in 4" :key="'skel-syarat-' + i" class="flex items-start">
                  <div
                    class="w-6 h-6 bg-slate-200 dark:bg-slate-700 rounded-full mr-4 shrink-0"
                  ></div>
                  <div class="flex-1 space-y-2 pt-1">
                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-full"></div>
                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-3/4"></div>
                  </div>
                </li>
              </ul>

              <ul v-else-if="syaratList.length > 0" class="space-y-5">
                <li
                  v-for="(syarat, index) in syaratList"
                  :key="index"
                  class="flex items-start group"
                >
                  <div
                    class="mt-0.5 mr-4 w-6 h-6 rounded-full bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-800/50 group-hover:bg-emerald-100 transition-colors"
                  >
                    <PhCheckCircle
                      class="w-4 h-4 text-emerald-600 dark:text-emerald-400"
                      weight="fill"
                    />
                  </div>
                  <span
                    class="text-[15px] md:text-base text-slate-700 dark:text-slate-300 leading-relaxed font-medium"
                    style="font-family: 'Plus Jakarta Sans', sans-serif"
                    >{{
                      typeof syarat === "string" ? syarat : syarat.text || syarat.desc
                    }}</span
                  >
                </li>
              </ul>

              <div
                v-else
                class="text-center py-10 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700"
              >
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                  Belum ada data persyaratan yang diunggah.
                </p>
              </div>
            </div>

            <!-- KOLOM ALUR PENDAFTARAN -->
            <div
              class="bg-white dark:bg-slate-800 rounded-3xl p-8 md:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/60 dark:border-slate-700/60 hover:shadow-xl transition-shadow duration-500"
            >
              <div
                class="flex items-center mb-8 pb-6 border-b border-slate-100 dark:border-slate-700/50"
              >
                <div
                  class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center mr-5 shadow-inner"
                >
                  <PhGitMerge
                    class="w-7 h-7 text-indigo-600 dark:text-indigo-400"
                    weight="fill"
                  />
                </div>
                <div>
                  <h3
                    class="text-2xl font-bold text-slate-900 dark:text-white"
                    style="font-family: 'Plus Jakarta Sans', sans-serif"
                  >
                    Alur Pendaftaran
                  </h3>
                  <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mt-1">
                    Langkah-langkah proses PPDB
                  </p>
                </div>
              </div>

              <!-- Skeleton Alur -->
              <div
                v-if="isLoading"
                class="space-y-8 animate-pulse relative before:absolute before:inset-0 before:ml-[1.1rem] before:-translate-x-px before:h-full before:w-[2px] before:bg-slate-200 dark:before:bg-slate-700"
              >
                <div v-for="i in 3" :key="'skel-alur-' + i" class="relative flex gap-6">
                  <div
                    class="w-9 h-9 rounded-full bg-slate-300 dark:bg-slate-600 shrink-0 z-10 border-4 border-white dark:border-slate-800"
                  ></div>
                  <div class="flex-1 pt-1.5 space-y-2">
                    <div class="h-5 bg-slate-200 dark:bg-slate-700 rounded w-1/2"></div>
                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-full"></div>
                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-4/5"></div>
                  </div>
                </div>
              </div>

              <!-- Konten Alur -->
              <div
                v-else-if="alurList.length > 0"
                class="relative before:absolute before:inset-0 before:ml-[1.1rem] before:-translate-x-px before:h-[calc(100%-2rem)] before:w-[2px] before:bg-gradient-to-b before:from-indigo-200 before:via-blue-200 dark:before:from-indigo-900/50 dark:before:via-blue-900/50 before:to-transparent space-y-8"
              >
                <div
                  v-for="(alur, index) in alurList"
                  :key="index"
                  class="relative flex items-start gap-6 group"
                >
                  <div
                    class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm shrink-0 z-10 border-[3px] border-white dark:border-slate-800 shadow-sm transition-colors duration-300"
                    :class="
                      index === alurList.length - 1
                        ? 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400'
                        : 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 group-hover:bg-indigo-600 group-hover:text-white'
                    "
                    style="font-family: 'Plus Jakarta Sans', sans-serif"
                  >
                    {{ index + 1 }}
                  </div>
                  <div class="pt-1.5">
                    <h4
                      class="text-base font-bold mb-1.5"
                      style="font-family: 'Plus Jakarta Sans', sans-serif"
                      :class="
                        index === alurList.length - 1
                          ? 'text-slate-500 dark:text-slate-400'
                          : 'text-slate-900 dark:text-white'
                      "
                    >
                      {{ alur.title || alur.nama }}
                    </h4>
                    <p
                      class="text-[13px] md:text-sm leading-relaxed"
                      :class="
                        index === alurList.length - 1
                          ? 'text-slate-400 dark:text-slate-500'
                          : 'text-slate-600 dark:text-slate-400'
                      "
                    >
                      {{ alur.desc || alur.description }}
                    </p>
                  </div>
                </div>
              </div>

              <div
                v-else
                class="text-center py-10 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700"
              >
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                  Belum ada data alur pendaftaran.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section
        class="py-12 md:py-8 px-6 bg-slate-950 dark:bg-slate-950 relative overflow-hidden border-b border-slate-900"
      >
        <div class="container mx-auto max-w-full px-0 lg:px-16 relative z-10">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
            <div class="lg:col-span-5 text-center lg:text-left">
              <h4
                class="text-amber-400 font-bold tracking-widest text-xs sm:text-sm uppercase mb-3"
                style="font-family: 'Plus Jakarta Sans', sans-serif"
              >
                Pilihan Seleksi
              </h4>
              <h2
                class="text-3xl md:text-5xl font-extrabold text-white mb-6 leading-tight"
                style="font-family: 'Plus Jakarta Sans', sans-serif"
              >
                Jalur Pendaftaran
              </h2>
              <p class="text-slate-300 text-base md:text-lg leading-relaxed">
                Sistem Penerimaan Peserta Didik Baru terbagi menjadi 4 jalur utama.
                Pastikan Anda memilih jalur yang paling sesuai dengan kondisi dan
                kualifikasi untuk memperbesar peluang diterima.
              </p>

              <div
                class="hidden lg:flex items-center gap-3 text-amber-400 font-semibold mt-10"
              >
                <div
                  class="w-10 h-10 rounded-full border border-amber-400/50 flex items-center justify-center animate-bounce shadow-[0_0_15px_rgba(251,191,36,0.3)] bg-amber-400/10"
                >
                  <PhArrowDown class="w-4 h-4" weight="bold" />
                </div>
                <span class="text-sm tracking-wide">Scroll untuk melihat jalur</span>
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
                  class="bg-slate-800/50 rounded-2xl w-full max-w-sm h-full border border-slate-700/50"
                ></div>
              </div>

              <div
                v-else-if="jalurList.length === 0"
                class="text-center w-full text-slate-400 font-medium"
              >
                Belum ada data jalur pendaftaran yang tersedia saat ini.
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
                        class="bg-slate-900/90 rounded-3xl shadow-xl hover:shadow-2xl relative group transform transition-all duration-500 h-full flex flex-col overflow-hidden border border-slate-800 hover:border-slate-700"
                      >
                        <!-- Top Accent Bar -->
                        <div
                          class="h-2 w-full bg-gradient-to-r from-amber-400 to-orange-500"
                        ></div>

                        <div class="p-6 sm:p-8 flex-1 flex flex-col relative z-10">
                          <div class="flex justify-between items-start mb-6">
                            <div
                              class="w-12 h-12 sm:w-14 sm:h-14 bg-amber-400/10 text-amber-400 rounded-2xl flex items-center justify-center border border-amber-400/20 group-hover:scale-110 group-hover:bg-amber-400 group-hover:text-slate-900 transition-all duration-300"
                            >
                              <component
                                :is="getIcon(index)"
                                class="w-6 h-6 sm:w-7 sm:h-7"
                                weight="duotone"
                              />
                            </div>
                            <div
                              class="px-3.5 py-1.5 bg-slate-800 border border-slate-700 text-slate-300 text-[11px] sm:text-xs font-bold rounded-full shadow-inner tracking-widest uppercase"
                            >
                              Kuota <span class="text-amber-400">{{ jalur.kuota }}</span>
                            </div>
                          </div>

                          <h3
                            class="text-xl sm:text-2xl font-bold text-white mb-3 tracking-tight"
                            style="font-family: 'Plus Jakarta Sans', sans-serif"
                          >
                            {{ jalur.title }}
                          </h3>

                          <p
                            class="text-slate-400 text-sm leading-relaxed line-clamp-4 lg:line-clamp-none font-medium"
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
@import url("https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap");

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
      black 5%,
      black 95%,
      transparent 100%
    );
    mask-image: linear-gradient(
      to bottom,
      transparent 0%,
      black 5%,
      black 95%,
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
  background-color: rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
  margin: 6px !important;
}

:deep(.jalur-pagination .swiper-pagination-bullet-active) {
  background-color: #fbbf24; /* amber-400 */
  width: 24px;
  border-radius: 6px;
}

@media (min-width: 1024px) {
  :deep(.jalur-pagination .swiper-pagination-bullet-active) {
    height: 24px;
    width: 8px;
  }
}
</style>
