<script setup>
import { ref, onMounted, nextTick, computed } from "vue";
import api from "@/api/index.js";
import PageHeader from "@/components/PageHeader.vue";
import {
  PhListChecks,
  PhCheckCircle,
  PhGitMerge,
  PhMapPin,
  PhMedal,
  PhHandshake,
  PhBriefcase,
  PhDownloadSimple,
  PhCaretRight,
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

// Fungsi gabungan untuk mengambil data PPDB dan Global Settings secara paralel
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

// Inisialisasi Slider Swiper (Diperbaiki agar menampilkan card tengah dan 2 setengah card di sisi)
const initSwiper = () => {
  new Swiper(".jalur-swiper", {
    direction: "horizontal",
    loop: true,
    speed: 800,
    autoplay: {
      delay: 3500,
      disableOnInteraction: false,
    },
    // Konfigurasi agar card di tengah fokus, dan sampingnya terpotong
    centeredSlides: true,
    slidesPerView: 1.15,
    spaceBetween: 16,
    grabCursor: true,
    pagination: {
      el: ".jalur-pagination",
      clickable: true,
    },
    breakpoints: {
      640: {
        slidesPerView: 1.5,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 2.5, // 1 card penuh di tengah, setengah di kiri, setengah di kanan
        spaceBetween: 30,
      },
      1280: {
        slidesPerView: 3,
        spaceBetween: 30,
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

      <!-- SEKSI 1: SYARAT DAN ALUR PENDAFTARAN (DIPISAH PROFESIONAL) -->
      <section
        class="py-12 md:py-16 px-6 bg-slate-50 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 overflow-hidden"
      >
        <!-- Mempertahankan max-w asli -->
        <div class="container mx-auto max-w-full px-0 lg:px-16">
          <!-- BLOK 1: SYARAT PENDAFTARAN -->
          <div class="mb-16 md:mb-24">
            <div class="text-center mb-10">
              <span
                class="text-blue-600 font-bold uppercase tracking-widest text-xs sm:text-sm mb-2 block"
                >Kualifikasi Administrasi</span
              >
              <h2
                class="text-3xl md:text-4xl font-bold text-blue-950 dark:text-white"
                style="font-family: 'Oswald', sans-serif"
              >
                Syarat Pendaftaran
              </h2>
              <p class="text-gray-600 dark:text-gray-400 mt-2 max-w-2xl mx-auto">
                Pastikan Anda telah menyiapkan dan memenuhi seluruh kelengkapan dokumen
                berikut sebelum memulai proses pendaftaran.
              </p>
            </div>

            <div
              class="bg-white dark:bg-slate-800 p-6 md:p-10 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-lg relative overflow-hidden"
            >
              <!-- Ornamen dekorasi di pojok card -->
              <div
                class="absolute top-0 right-0 w-32 h-32 bg-blue-50 dark:bg-slate-700/50 rounded-bl-full -z-0"
              ></div>

              <div class="relative z-10">
                <ul
                  v-if="isLoading"
                  class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-pulse"
                >
                  <li v-for="i in 6" :key="'skel-syarat-' + i" class="flex items-center">
                    <div
                      class="w-5 h-5 bg-gray-300 dark:bg-slate-600 rounded-full mr-3 shrink-0"
                    ></div>
                    <div
                      class="h-4 bg-gray-300 dark:bg-slate-600 rounded w-full max-w-[200px]"
                    ></div>
                  </li>
                </ul>
                <ul
                  v-else-if="syaratList.length > 0"
                  class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5"
                >
                  <li
                    v-for="(syarat, index) in syaratList"
                    :key="index"
                    class="flex items-start bg-blue-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-blue-100/50 dark:border-slate-700 hover:shadow-sm transition-shadow"
                  >
                    <PhCheckCircle
                      class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 shrink-0 mt-0.5"
                      weight="fill"
                    />
                    <span
                      class="text-sm md:text-base text-gray-700 dark:text-gray-300 font-medium"
                    >
                      {{
                        typeof syarat === "string" ? syarat : syarat.text || syarat.desc
                      }}
                    </span>
                  </li>
                </ul>
                <div v-else class="text-center py-10">
                  <PhListChecks class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                  <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                    Belum ada data syarat pendaftaran.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- BLOK 2: ALUR PENDAFTARAN (TIMELINE HORIZONTAL/VERTICAL) -->
          <div>
            <div class="text-center mb-12 md:mb-16">
              <span
                class="text-blue-600 font-bold uppercase tracking-widest text-xs sm:text-sm mb-2 block"
                >Tahapan Seleksi</span
              >
              <h2
                class="text-3xl md:text-4xl font-bold text-blue-950 dark:text-white"
                style="font-family: 'Oswald', sans-serif"
              >
                Alur Pendaftaran
              </h2>
              <p class="text-gray-600 dark:text-gray-400 mt-2 max-w-2xl mx-auto">
                Ikuti panduan langkah demi langkah berikut ini untuk memastikan proses
                pendaftaran Anda berjalan lancar.
              </p>
            </div>

            <div v-if="isLoading" class="flex justify-center py-10 animate-pulse">
              <div
                class="w-full max-w-4xl h-32 bg-gray-200 dark:bg-slate-700 rounded-xl"
              ></div>
            </div>

            <div v-else-if="alurList.length > 0" class="relative">
              <!-- Garis penghubung background (hanya Desktop) -->
              <div
                class="hidden md:block absolute top-[28px] left-[50px] right-[50px] h-1 bg-blue-100 dark:bg-slate-700 z-0"
              ></div>

              <div
                class="flex flex-col md:flex-row justify-between items-start md:items-stretch gap-8 md:gap-4 relative z-10"
              >
                <div
                  v-for="(alur, index) in alurList"
                  :key="index"
                  class="flex md:flex-col items-center md:items-center text-left md:text-center relative w-full group"
                >
                  <!-- Garis penghubung background (hanya Mobile) -->
                  <div
                    v-if="index !== alurList.length - 1"
                    class="md:hidden absolute top-[45px] left-[23px] w-1 h-[calc(100%+20px)] bg-blue-100 dark:bg-slate-700 -z-10"
                  ></div>

                  <!-- Nomor Lingkaran -->
                  <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-full flex items-center justify-center font-bold text-lg md:text-xl shrink-0 z-10 border-4 border-white dark:border-slate-800 shadow-md transition-colors duration-300"
                    :class="
                      index === alurList.length - 1
                        ? 'bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-gray-300'
                        : 'bg-blue-600 text-white group-hover:bg-blue-700'
                    "
                  >
                    {{ index + 1 }}
                  </div>

                  <!-- Konten Teks -->
                  <div
                    class="ml-5 md:ml-0 md:mt-5 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 w-full flex-1"
                  >
                    <h4
                      class="font-bold text-sm md:text-base mb-1"
                      :class="
                        index === alurList.length - 1
                          ? 'text-gray-500 dark:text-gray-400'
                          : 'text-blue-950 dark:text-white'
                      "
                    >
                      {{ alur.title || alur.nama }}
                    </h4>
                    <p
                      class="text-xs md:text-sm"
                      :class="
                        index === alurList.length - 1
                          ? 'text-gray-400 dark:text-gray-500'
                          : 'text-gray-600 dark:text-gray-400'
                      "
                    >
                      {{ alur.desc || alur.description }}
                    </p>
                  </div>

                  <!-- Panah Indikator (Desktop) -->
                  <div
                    v-if="index !== alurList.length - 1"
                    class="hidden md:flex absolute top-5 -right-4 w-8 justify-center z-10 text-blue-300"
                  >
                    <PhCaretRight class="w-6 h-6" weight="bold" />
                  </div>
                </div>
              </div>
            </div>

            <p
              v-else
              class="text-sm text-center text-gray-500 dark:text-gray-400 italic py-10"
            >
              Belum ada data alur pendaftaran.
            </p>
          </div>
        </div>
      </section>

      <!-- SEKSI 2: JALUR PENDAFTARAN (DIKEMBALIKAN KE WARNA DEFAULT) -->
      <section
        class="py-12 md:py-16 px-0 bg-blue-950 dark:bg-slate-900 relative overflow-hidden border-b border-blue-900 dark:border-slate-800"
      >
        <!-- Mempertahankan max-w asli -->
        <div class="container mx-auto max-w-full px-0 lg:px-16 relative z-10">
          <!-- Teks Header di Atas Slider -->
          <div class="text-center mb-12 px-6">
            <h2
              class="text-3xl md:text-4xl font-bold text-white mb-4 leading-tight"
              style="font-family: 'Oswald', sans-serif"
            >
              Jalur Pendaftaran PPDB
            </h2>
            <p
              class="text-blue-100 dark:text-gray-300 text-base md:text-lg leading-relaxed max-w-3xl mx-auto"
            >
              Sistem Penerimaan Peserta Didik Baru terbagi menjadi beberapa jalur utama.
              Pastikan Anda memilih jalur yang paling sesuai dengan kondisi dan
              kualifikasi.
            </p>
          </div>

          <!-- Area Slider Horizontal Penuh -->
          <div class="w-full relative">
            <div
              v-if="isLoading"
              class="w-full h-[400px] flex items-center justify-center animate-pulse px-6"
            >
              <div
                class="bg-blue-800/40 dark:bg-slate-800/50 rounded-xl w-full max-w-3xl h-full border border-blue-700/30 dark:border-slate-700/50"
              ></div>
            </div>

            <div
              v-else-if="jalurList.length === 0"
              class="text-center w-full text-blue-200 italic py-20"
            >
              Belum ada data jalur pendaftaran.
            </div>

            <div v-else class="w-full relative pb-12">
              <div class="swiper jalur-swiper w-full !pb-8 !px-4">
                <div class="swiper-wrapper items-stretch">
                  <div
                    v-for="(jalur, index) in jalurList"
                    :key="index"
                    class="swiper-slide h-auto"
                  >
                    <!-- Tampilan Card Default (Original) -->
                    <div
                      class="bg-white dark:bg-slate-800 rounded-xl shadow-lg hover:shadow-2xl relative group transform transition-all duration-500 h-full flex flex-col overflow-hidden border border-gray-100 dark:border-slate-700/60 border-b-4 border-b-yellow-400 dark:border-b-yellow-500"
                    >
                      <div
                        class="h-28 sm:h-32 overflow-hidden relative shrink-0 bg-blue-600 dark:bg-blue-800 transition-colors duration-500 group-hover:bg-blue-700 dark:group-hover:bg-blue-600"
                      >
                        <div
                          class="absolute top-4 left-4 px-3 py-1 bg-yellow-400 text-blue-950 text-[10px] sm:text-xs font-bold rounded-full shadow-sm border border-yellow-300"
                        >
                          Kuota {{ jalur.kuota }}
                        </div>
                      </div>

                      <div
                        class="relative z-10 px-5 sm:px-6 md:px-8 pb-8 flex-1 flex flex-col -mt-8"
                      >
                        <div
                          class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-400 dark:bg-yellow-500 text-blue-950 rounded-xl flex items-center justify-center mb-4 shadow-md border border-yellow-300 dark:border-yellow-400 group-hover:scale-110 transition-transform"
                        >
                          <component :is="getIcon(index)" class="w-6 h-6 sm:w-7 sm:h-7" />
                        </div>

                        <h3
                          class="text-xl sm:text-2xl font-bold text-blue-950 dark:text-white mb-3"
                        >
                          {{ jalur.title }}
                        </h3>

                        <p
                          class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed"
                        >
                          {{ jalur.desc }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Pagination di bagian bawah slider -->
                <div
                  class="jalur-pagination absolute bottom-0 left-0 w-full flex justify-center mt-4"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<style scoped>
/* Custom styles for Jalur Swiper pagination */
:deep(.jalur-pagination .swiper-pagination-bullet) {
  background-color: rgba(255, 255, 255, 0.4);
  transition: all 0.3s ease;
  margin: 0 6px !important;
  width: 8px;
  height: 8px;
}

:deep(.jalur-pagination .swiper-pagination-bullet-active) {
  background-color: #fbbf24; /* yellow-400 */
  width: 24px;
  border-radius: 6px;
}

/* Modifikasi Opacity untuk kartu yang tidak berada di tengah (Efek Carousel) */
:deep(.swiper-slide) {
  transition: opacity 0.5s ease, transform 0.5s ease;
  opacity: 0.5;
  transform: scale(0.9);
}

:deep(.swiper-slide-active) {
  opacity: 1;
  transform: scale(1);
  z-index: 10;
}
</style>
