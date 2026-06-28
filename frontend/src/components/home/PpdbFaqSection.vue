<script setup>
import { ref, onMounted, onBeforeUnmount, watch, computed } from "vue";
import { PhDownloadSimple } from "@phosphor-icons/vue";
import api from "@/api/index.js";

const props = defineProps({
  appearanceSettings: { type: Object, required: true },
  ppdbInfo: { type: Object, required: true },
  faqs: { type: Array, required: true },
  isLoading: { type: Boolean, required: true },
});

const activeFaq = ref(null);
const toggleFaq = (index) => {
  activeFaq.value = activeFaq.value === index ? null : index;
};

const brochureUrl = computed(() => {
  const path = props.ppdbInfo.brosur_path;
  if (!path) return null;
  if (path.startsWith("http")) return path;

  // Menggunakan base URL dari API untuk membentuk URL absolut
  const baseUrl = api.defaults.baseURL.endsWith("/")
    ? api.defaults.baseURL
    : `${api.defaults.baseURL}/`;
  return `${baseUrl}storage/${path.replace(/^storage\//, "")}`;
});

// Logika Countdown dipindah ke komponen ini agar tidak membebani parent
const ppdbCountdown = ref({ days: "00", hours: "00", minutes: "00", seconds: "00" });
let countdownInterval;

const updateCountdown = () => {
  if (!props.ppdbInfo.opening_date) {
    ppdbCountdown.value = { days: "00", hours: "00", minutes: "00", seconds: "00" };
    return;
  }

  const targetDate = new Date(props.ppdbInfo.opening_date).getTime();
  const now = new Date().getTime();
  const distance = targetDate - now;

  if (distance < 0) {
    clearInterval(countdownInterval);
    ppdbCountdown.value = { days: "00", hours: "00", minutes: "00", seconds: "00" };
    return;
  }

  const days = Math.floor(distance / (1000 * 60 * 60 * 24));
  const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((distance % (1000 * 60)) / 1000);

  ppdbCountdown.value = {
    days: String(days).padStart(2, "0"),
    hours: String(hours).padStart(2, "0"),
    minutes: String(minutes).padStart(2, "0"),
    seconds: String(seconds).padStart(2, "0"),
  };
};

onMounted(() => {
  updateCountdown();
  countdownInterval = setInterval(updateCountdown, 1000);
});

onBeforeUnmount(() => {
  if (countdownInterval) clearInterval(countdownInterval);
});

watch(
  () => props.ppdbInfo.opening_date,
  () => {
    updateCountdown();
  }
);
</script>

<template>
  <section class="relative -mx-6 md:mx-0 overflow-hidden">
    <div
      class="w-full max-w-full relative mx-auto mb-0 md:mb-12 p-8 px-4 md:px-6 lg:px-4 md:rounded-xl bg-center bg-cover md:bg-fixed overflow-hidden shadow-xl bg-slate-900"
      :style="
        appearanceSettings.ppdbBackgroundImage
          ? { backgroundImage: `url(${appearanceSettings.ppdbBackgroundImage})` }
          : {}
      "
    >
      <!-- Overlay Parallax -->
      <div
        class="absolute inset-0 bg-gradient-to-b from-blue-900/80 via-slate-900/90 to-black/95 backdrop-blur-[2px]"
      ></div>

      <div
        class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-12 items-stretch"
      >
        <!-- FAQ Kolom Kiri -->
        <div class="flex flex-col justify-center p-0 sm:p-4">
          <div class="mb-8 md:mb-10 text-center lg:text-left">
            <h2
              class="text-2xl md:text-4xl font-bold text-white mb-3"
              style="font-family: 'Oswald', sans-serif"
            >
              Pertanyaan Seputar PPDB
            </h2>
            <p
              class="text-white font-medium text-sm md:text-base max-w-2xl mx-auto lg:mx-0"
            >
              Jawaban atas beberapa pertanyaan yang paling sering ditanyakan oleh calon
              siswa dan orang tua.
            </p>
          </div>

          <div v-if="isLoading" class="w-full flex flex-col gap-4">
            <div
              v-for="i in 4"
              :key="'skel-faq-' + i"
              class="bg-white dark:bg-slate-800/80 border border-gray-100 dark:border-slate-700 rounded-xl shadow-md p-5 md:p-6 animate-pulse backdrop-blur-sm"
            >
              <div class="h-5 bg-gray-200 dark:bg-slate-700 rounded w-3/4 mb-2"></div>
            </div>
          </div>

          <div v-else-if="faqs.length > 0" class="w-full flex flex-col gap-4">
            <div
              v-for="(faq, index) in faqs"
              :key="index"
              class="bg-white dark:bg-slate-800/80 border border-gray-100 dark:border-slate-700 rounded-xl shadow-md backdrop-blur-sm"
            >
              <button
                @click="toggleFaq(index)"
                class="w-full text-left px-5 md:px-6 py-4 font-semibold text-gray-900 dark:text-white flex justify-between items-center hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors rounded-xl focus:outline-none"
              >
                <span class="pr-0 sm:pr-2 text-sm md:text-base">{{
                  faq.question || faq.q
                }}</span>
                <svg
                  class="w-5 h-5 text-blue-600 dark:text-blue-400 transform transition-transform duration-300 flex-shrink-0"
                  :class="{ 'rotate-180': activeFaq === index }"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  ></path>
                </svg>
              </button>
              <div
                :class="{
                  'max-h-96 opacity-100 pb-4': activeFaq === index,
                  'max-h-0 opacity-0 overflow-hidden': activeFaq !== index,
                }"
                class="transition-all duration-300 px-5 md:px-6 text-gray-700 dark:text-gray-300 text-sm md:text-base"
              >
                <div class="border-t border-gray-100 dark:border-slate-700 pt-3">
                  {{ faq.answer || faq.a }}
                </div>
              </div>
            </div>
          </div>
          <div
            v-else
            class="text-white/80 p-4 border border-white/20 rounded-lg text-center backdrop-blur-sm mt-4"
          >
            Belum ada informasi FAQ PPDB yang tersedia.
          </div>
        </div>

        <!-- CTA Kolom Kanan -->
        <div
          class="relative py-12 md:py-16 bg-gradient-to-br from-blue-600 to-blue-800 border border-blue-500/30 px-6 md:px-10 text-center rounded-lg shadow-xl overflow-hidden flex flex-col justify-center items-center"
        >
          <div
            class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"
          ></div>
          <div
            class="absolute bottom-0 left-0 w-64 h-64 bg-blue-400 opacity-20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"
          ></div>

          <div class="relative z-10 w-full max-w-xl mx-auto">
            <template v-if="isLoading">
              <div class="animate-pulse flex flex-col items-center w-full">
                <div class="h-6 bg-white/20 rounded-full w-32 mb-6"></div>
                <div class="h-10 bg-white/20 rounded-lg w-3/4 mb-4"></div>
                <div class="h-10 bg-white/20 rounded-lg w-2/4 mb-6"></div>
                <div class="h-4 bg-white/20 rounded w-full max-w-md mx-auto mb-2"></div>
                <div class="h-4 bg-white/20 rounded w-4/5 max-w-md mx-auto mb-8"></div>
                <div class="flex justify-center gap-3 sm:gap-4 mb-8">
                  <div
                    v-for="i in 4"
                    :key="'skel-cd-' + i"
                    class="bg-white/10 rounded-lg w-[60px] h-[60px] sm:w-[70px] sm:h-[70px] border border-white/20"
                  ></div>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 justify-center w-full">
                  <div class="h-12 bg-white/20 rounded-xl w-full sm:w-40"></div>
                  <div class="h-12 bg-white/20 rounded-xl w-full sm:w-40"></div>
                </div>
              </div>
            </template>
            <template v-else>
              <span
                class="inline-block px-4 py-1.5 mb-6 text-xs md:text-sm font-semibold text-blue-950 bg-yellow-400 rounded-full shadow-sm"
              >
                TAHUN AJARAN {{ ppdbInfo.academic_year }}
              </span>
              <h2
                class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-6 leading-tight"
                style="font-family: 'Oswald', sans-serif"
              >
                {{ ppdbInfo.title }}
              </h2>
              <p class="text-blue-100 text-sm md:text-base mb-6 max-w-md mx-auto">
                {{ ppdbInfo.description }}
              </p>

              <!-- Countdown Timer -->
              <div class="flex justify-center gap-3 sm:gap-4 mb-8">
                <div
                  class="group flex flex-col items-center bg-white/10 backdrop-blur-sm rounded-lg p-2 sm:p-3 min-w-[60px] sm:min-w-[70px] border border-white/20 hover:-translate-y-1 transition-all cursor-default"
                >
                  <span
                    class="text-xl sm:text-2xl font-bold text-white mb-1 group-hover:scale-110 transition-all"
                    style="font-family: 'Oswald', sans-serif"
                    >{{ ppdbCountdown.days }}</span
                  >
                  <span
                    class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wider"
                    >Hari</span
                  >
                </div>
                <div
                  class="group flex flex-col items-center bg-white/10 backdrop-blur-sm rounded-lg p-2 sm:p-3 min-w-[60px] sm:min-w-[70px] border border-white/20 hover:-translate-y-1 transition-all cursor-default"
                >
                  <span
                    class="text-xl sm:text-2xl font-bold text-white mb-1 group-hover:scale-110 transition-all"
                    style="font-family: 'Oswald', sans-serif"
                    >{{ ppdbCountdown.hours }}</span
                  >
                  <span
                    class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wider"
                    >Jam</span
                  >
                </div>
                <div
                  class="group flex flex-col items-center bg-white/10 backdrop-blur-sm rounded-lg p-2 sm:p-3 min-w-[60px] sm:min-w-[70px] border border-white/20 hover:-translate-y-1 transition-all cursor-default"
                >
                  <span
                    class="text-xl sm:text-2xl font-bold text-white mb-1 group-hover:scale-110 transition-all"
                    style="font-family: 'Oswald', sans-serif"
                    >{{ ppdbCountdown.minutes }}</span
                  >
                  <span
                    class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wider"
                    >Menit</span
                  >
                </div>
                <div
                  class="group flex flex-col items-center bg-white/10 backdrop-blur-sm rounded-lg p-2 sm:p-3 min-w-[60px] sm:min-w-[70px] border border-white/20 hover:-translate-y-1 transition-all cursor-default"
                >
                  <span
                    class="text-xl sm:text-2xl font-bold text-white mb-1 group-hover:scale-110 transition-all"
                    style="font-family: 'Oswald', sans-serif"
                    >{{ ppdbCountdown.seconds }}</span
                  >
                  <span
                    class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wider"
                    >Detik</span
                  >
                </div>
              </div>

              <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a
                  v-if="ppdbInfo.registration_link"
                  :href="ppdbInfo.registration_link"
                  target="_blank"
                  class="px-6 py-3.5 bg-yellow-400 text-blue-950 font-bold rounded-xl shadow-lg hover:bg-yellow-300 hover:scale-105 transition-all text-sm md:text-base animate-float flex items-center justify-center"
                >
                  Daftar Sekarang
                </a>
                <router-link
                  v-else
                  to="/pendaftaran"
                  class="px-6 py-3.5 bg-yellow-400 text-blue-950 font-bold rounded-xl shadow-lg hover:bg-yellow-300 hover:scale-105 transition-all text-sm md:text-base animate-float flex items-center justify-center"
                >
                  Info Pendaftaran
                </router-link>
                <a
                  v-if="brochureUrl"
                  :href="brochureUrl"
                  target="_blank"
                  download
                  class="px-6 py-3.5 bg-gray-50/10 text-white font-semibold rounded-xl border border-white/50 hover:bg-gray-50/20 transition-all flex items-center justify-center text-sm md:text-base"
                >
                  <PhDownloadSimple class="w-5 h-5 mr-2" /> Unduh Brosur
                </a>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
@keyframes float {
  0%,
  100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-8px);
  }
}
.animate-float {
  animation: float 3s ease-in-out infinite;
}
.animate-float:hover {
  animation-play-state: paused;
}
</style>
