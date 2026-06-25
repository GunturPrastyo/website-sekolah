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

const initSwiper = () => {
  new Swiper(".jalur-swiper", {
    direction: "horizontal",
    loop: true,
    speed: 800,
    autoplay: { delay: 3500, disableOnInteraction: false },
    centeredSlides: true,
    slidesPerView: 1.2,
    spaceBetween: 20,
    grabCursor: true,
    pagination: { el: ".jalur-pagination", clickable: true },
    breakpoints: {
      1024: {
        slidesPerView: 2.5,
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
    <PageHeader
      v-if="!isLoading"
      :badge="`Tahun Ajaran ${new Date().getFullYear()}/${new Date().getFullYear() + 1}`"
      title="Informasi PPDB"
      description="Pusat Informasi Penerimaan Peserta Didik Baru (PPDB). Temukan panduan lengkap mengenai tata cara pendaftaran, persyaratan, alur, dan berbagai jalur penerimaan."
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
            <PhDownloadSimple class="w-4 h-4 mr-2" /> Unduh Brosur PPDB
          </a>
        </div>
      </template>
    </PageHeader>

    <section
      class="py-16 px-6 bg-slate-50 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800"
    >
      <div class="container mx-auto max-w-full px-0 lg:px-16">
        <!-- Syarat & Alur dipisah section agar lebih rapi -->
        <div class="mb-20">
          <div class="text-center mb-12">
            <h2
              class="text-3xl md:text-4xl font-bold text-blue-950 dark:text-white"
              style="font-family: 'Oswald', sans-serif"
            >
              Syarat & Alur Pendaftaran
            </h2>
          </div>
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Syarat -->
            <div
              class="bg-white dark:bg-slate-800 p-8 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm"
            >
              <h3
                class="text-xl font-bold mb-6 flex items-center text-blue-950 dark:text-white"
              >
                <PhListChecks class="w-6 h-6 mr-2" /> Syarat Pendaftaran
              </h3>
              <ul v-if="syaratList.length > 0" class="space-y-4">
                <li
                  v-for="(syarat, index) in syaratList"
                  :key="index"
                  class="flex items-start"
                >
                  <PhCheckCircle class="w-5 h-5 text-green-500 mr-3 shrink-0" />
                  <span class="text-sm text-gray-700 dark:text-gray-300">{{
                    typeof syarat === "string" ? syarat : syarat.text
                  }}</span>
                </li>
              </ul>
            </div>
            <!-- Alur -->
            <div
              class="bg-white dark:bg-slate-800 p-8 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm"
            >
              <h3
                class="text-xl font-bold mb-6 flex items-center text-blue-950 dark:text-white"
              >
                <PhGitMerge class="w-6 h-6 mr-2" /> Alur Pendaftaran
              </h3>
              <div v-if="alurList.length > 0" class="space-y-6">
                <div
                  v-for="(alur, index) in alurList"
                  :key="index"
                  class="flex items-start gap-4"
                >
                  <div
                    class="w-8 h-8 rounded-full border-2 border-blue-500 flex items-center justify-center font-bold text-blue-600 shrink-0"
                  >
                    {{ index + 1 }}
                  </div>
                  <div>
                    <h4 class="font-bold text-sm text-blue-950 dark:text-white">
                      {{ alur.title }}
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                      {{ alur.desc }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Jalur Pendaftaran Section -->
        <div
          class="bg-blue-950 dark:bg-slate-900 rounded-xl p-8 md:p-12 text-center text-white"
        >
          <h2 class="text-3xl font-bold mb-8" style="font-family: 'Oswald', sans-serif">
            Jalur Pendaftaran
          </h2>
          <div class="swiper jalur-swiper">
            <div class="swiper-wrapper">
              <div v-for="(jalur, index) in jalurList" :key="index" class="swiper-slide">
                <div
                  class="bg-white dark:bg-slate-800 p-6 rounded-xl text-left border-b-4 border-yellow-400"
                >
                  <div class="text-sm font-bold text-blue-600 mb-2">
                    Kuota: {{ jalur.kuota }}
                  </div>
                  <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                    {{ jalur.title }}
                  </h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ jalur.desc }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
