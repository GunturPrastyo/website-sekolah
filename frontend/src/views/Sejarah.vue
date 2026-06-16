<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import {
  PhBuildings,
  PhMedal,
  PhCheckCircle,
  PhHash,
  PhMapPin,
  PhClockCounterClockwise,
} from "@phosphor-icons/vue";
import { educationIcons } from "@/components/IconPicker.vue";
import PageHeader from "@/components/PageHeader.vue";
import api from "@/api/index.js";

const timelineRef = ref(null);
const lineHeight = ref("0%");
const currentProgress = ref(0);

const schoolProfile = ref({
  description: "Memuat deskripsi...",
  npsn: "-",
  accreditation: "-",
  location: "-",
  status: "-",
  image: "",
});

const timeline = ref([]);
const isLoading = ref(true);

const handleScroll = () => {
  if (!timelineRef.value) return;

  const rect = timelineRef.value.getBoundingClientRect();
  const windowHeight = window.innerHeight;

  // Animasi terpicu (garis menjalar) saat konten masuk 60% layar dari atas
  const startOffset = windowHeight * 0.6;
  const scrollPosition = startOffset - rect.top;

  let progress = scrollPosition / rect.height;
  progress = Math.max(0, Math.min(1, progress)); // Membatasi nilai antara 0 - 1
  lineHeight.value = `${progress * 100}%`;
  currentProgress.value = progress;
};

const getImageUrl = (path) => {
  if (!path) return "";
  if (path.startsWith("http") || path.startsWith("data:")) return path;
  const backendUrl = import.meta.env.VITE_API_URL || "http://localhost:8000";
  return `${backendUrl.replace(/\/+$/, "")}/${path.replace(/^\/+/, "")}`;
};

const fetchData = async () => {
  try {
    const profileRes = await api.get("/api/profil-sekolah");
    if (profileRes.data?.data) {
      const pd = profileRes.data.data;
      if (pd.description) schoolProfile.value.description = pd.description;
      if (pd.npsn) schoolProfile.value.npsn = pd.npsn;
      if (pd.accreditation) schoolProfile.value.accreditation = pd.accreditation;
      if (pd.location) schoolProfile.value.location = pd.location;
      if (pd.status) schoolProfile.value.status = pd.status;
      if (pd.image) schoolProfile.value.image = getImageUrl(pd.image);
    }
  } catch (error) {
    if (error.response && error.response.status === 404) {
      // Jangan tampilkan log console jika memang datanya belum ada di database
      schoolProfile.value.description = "Data profil sekolah belum tersedia.";
    } else {
      console.error("Gagal memuat profil sekolah:", error);
    }
  }

  try {
    const timelineRes = await api.get("/api/sejarah");
    if (timelineRes.data?.data) {
      timeline.value = timelineRes.data.data.map((item) => {
        if (item.image) {
          item.image = getImageUrl(item.image);
        }
        return item;
      });
    }
  } catch (error) {
    if (error.response && error.response.status !== 404) {
      console.error("Gagal memuat sejarah:", error);
    }
  } finally {
    isLoading.value = false;

    // Panggil ulang kalkulasi posisi scroll dan inisialisasi observer setelah rendering
    setTimeout(() => {
      handleScroll();
      setupObserver();
    }, 100);
  }
};

const getIconComponent = (iconName) => {
  if (!iconName) return PhBuildings;
  return educationIcons?.[iconName] || PhBuildings;
};

const setupObserver = () => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("opacity-100", "translate-y-0", "scale-100");
          entry.target.classList.remove(
            "opacity-0",
            "translate-y-10",
            "scale-95",
            "fade-on-scroll"
          );

          const sentences = entry.target.querySelectorAll(".fade-sentence");
          sentences.forEach((el, idx) => {
            setTimeout(() => {
              el.classList.add("opacity-100");
              el.classList.remove("opacity-0");
            }, 400 + idx * 500);
          });

          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 }
  );

  document.querySelectorAll(".fade-on-scroll").forEach((el) => {
    observer.observe(el);
  });
};

onMounted(() => {
  window.addEventListener("scroll", handleScroll);
  setupObserver(); // Panggil observer di awal untuk teks statis
  fetchData();
});

onBeforeUnmount(() => {
  window.removeEventListener("scroll", handleScroll);
});
</script>

<template>
  <div>
    <PageHeader
      badge="Profil Sekolah"
      title="Tentang Kami & Sejarah"
      description="Mengenal lebih dekat profil sekolah kami dan menyusuri jejak langkah perjuangan serta prestasi dari masa ke masa."
    />

    <!-- Profil Singkat Section -->
    <section class="pt-8 md:pt-12 pb-8 md:pb-10 bg-white dark:bg-slate-800">
      <div class="container mx-auto max-w-full px-8 sm:px-16">
        <div
          class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out flex flex-col lg:flex-row items-center gap-10 lg:gap-16"
        >
          <!-- Gambar Profil -->
          <div
            class="w-full lg:w-5/12 h-72 sm:h-80 lg:h-[450px] relative rounded-xl overflow-hidden shadow-xl border border-gray-100 dark:border-slate-700"
          >
            <div
              v-if="isLoading"
              class="absolute inset-0 bg-slate-200 dark:bg-slate-700 animate-pulse"
            ></div>
            <template v-else>
              <img
                v-if="schoolProfile.image"
                :src="schoolProfile.image"
                class="absolute inset-0 w-full h-full object-cover"
                alt="Gedung Sekolah"
              />
              <div
                v-else
                class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-800 text-gray-400 dark:text-gray-500"
              >
                <PhBuildings class="w-16 h-16 mb-2 opacity-50" />
                <span class="text-sm font-medium">Foto Belum Tersedia</span>
              </div>
              <div
                v-if="schoolProfile.image"
                class="absolute inset-0 bg-blue-900/20 mix-blend-multiply"
              ></div>
            </template>
          </div>

          <!-- Info & Fakta -->
          <div class="w-full lg:w-7/12 flex flex-col justify-center lg:py-6">
            <span
              class="text-lg md:text-xl font-bold text-blue-600 dark:text-blue-400 tracking-wider mb-2"
              style="font-family: 'Kalam', cursive"
              >Mengenal Lebih Dekat</span
            >
            <h2
              class="text-2xl min-[400px]:text-3xl md:text-4xl font-bold text-blue-950 dark:text-white mb-4 min-[400px]:mb-5"
            >
              Profil Singkat Sekolah
            </h2>

            <div v-if="isLoading" class="space-y-3 mb-8 animate-pulse">
              <div class="w-full h-4 bg-slate-200 dark:bg-slate-700 rounded"></div>
              <div class="w-full h-4 bg-slate-200 dark:bg-slate-700 rounded"></div>
              <div class="w-5/6 h-4 bg-slate-200 dark:bg-slate-700 rounded"></div>
              <div class="w-4/5 h-4 bg-slate-200 dark:bg-slate-700 rounded"></div>
            </div>
            <p
              v-else
              class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed text-justify whitespace-pre-line"
            >
              {{ schoolProfile.description }}
            </p>

            <!-- Grid Fakta -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div class="flex items-start group">
                <div
                  class="w-11 h-11 rounded-full bg-blue-600 dark:bg-blue-500 flex items-center justify-center text-white shrink-0 mr-4 group-hover:bg-blue-800 dark:group-hover:bg-blue-700 transition-colors shadow-sm"
                >
                  <PhHash class="w-5 h-5" />
                </div>
                <div>
                  <h4 class="text-sm font-bold text-gray-900 dark:text-white">NPSN</h4>
                  <div
                    v-if="isLoading"
                    class="w-16 h-4 bg-slate-200 dark:bg-slate-700 rounded mt-1 animate-pulse"
                  ></div>
                  <p v-else class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ schoolProfile.npsn }}
                  </p>
                </div>
              </div>
              <div class="flex items-start group">
                <div
                  class="w-11 h-11 rounded-full bg-blue-600 dark:bg-blue-500 flex items-center justify-center text-white shrink-0 mr-4 group-hover:bg-blue-800 dark:group-hover:bg-blue-700 transition-colors shadow-sm"
                >
                  <PhCheckCircle class="w-5 h-5" />
                </div>
                <div>
                  <h4 class="text-sm font-bold text-gray-900 dark:text-white">
                    Akreditasi
                  </h4>
                  <div
                    v-if="isLoading"
                    class="w-12 h-4 bg-slate-200 dark:bg-slate-700 rounded mt-1 animate-pulse"
                  ></div>
                  <p v-else class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ schoolProfile.accreditation }}
                  </p>
                </div>
              </div>
              <div class="flex items-start group">
                <div
                  class="w-11 h-11 rounded-full bg-blue-600 dark:bg-blue-500 flex items-center justify-center text-white shrink-0 mr-4 group-hover:bg-blue-800 dark:group-hover:bg-blue-700 transition-colors shadow-sm"
                >
                  <PhMapPin class="w-5 h-5" />
                </div>
                <div>
                  <h4 class="text-sm font-bold text-gray-900 dark:text-white">Lokasi</h4>
                  <div
                    v-if="isLoading"
                    class="w-24 h-4 bg-slate-200 dark:bg-slate-700 rounded mt-1 animate-pulse"
                  ></div>
                  <p v-else class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ schoolProfile.location }}
                  </p>
                </div>
              </div>
              <div class="flex items-start group">
                <div
                  class="w-11 h-11 rounded-full bg-blue-600 dark:bg-blue-500 flex items-center justify-center text-white shrink-0 mr-4 group-hover:bg-blue-800 dark:group-hover:bg-blue-700 transition-colors shadow-sm"
                >
                  <PhMedal class="w-5 h-5" />
                </div>
                <div>
                  <h4 class="text-sm font-bold text-gray-900 dark:text-white">Status</h4>
                  <div
                    v-if="isLoading"
                    class="w-20 h-4 bg-slate-200 dark:bg-slate-700 rounded mt-1 animate-pulse"
                  ></div>
                  <p v-else class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ schoolProfile.status }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Timeline Section -->
    <section
      class="pt-6 pb-12 md:pb-24 min-h-screen relative overflow-hidden bg-blue-950 dark:bg-gray-900 bg-fixed bg-center bg-cover"
    >
      <!-- Overlay Parallax untuk memastikan teks tetap bisa dibaca -->
      <div
        class="absolute inset-0 bg-blue-950/90 dark:bg-gray-900/90 backdrop-blur-[2px]"
      ></div>

      <div
        class="container relative z-10 mx-auto px-5 sm:px-8 md:px-14 lg:px-16 w-full max-w-full"
      >
        <!-- Bridging Title -->
        <div
          class="mb-10 md:mb-18 text-center fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out pt-6 md:pt-12"
        >
          <div class="inline-flex items-center justify-center space-x-2 mb-4">
            <span class="h-px w-8 sm:w-12 bg-blue-400 rounded-full"></span>
            <span
              class="text-blue-400 font-bold text-lg sm:text-xl tracking-wider"
              style="font-family: 'Kalam', cursive"
              >Lini Masa</span
            >
            <span class="h-px w-8 sm:w-12 bg-blue-400 rounded-full"></span>
          </div>
          <h2
            class="text-2xl min-[400px]:text-3xl sm:text-4xl md:text-5xl font-extrabold text-white mb-3 min-[400px]:mb-4 sm:mb-6 tracking-tight"
          >
            Jejak Langkah Kami
          </h2>
          <p
            class="text-blue-100 dark:text-gray-400 text-sm sm:text-base md:text-lg max-w-2xl mx-auto leading-relaxed"
          >
            Menelusuri kembali tonggak sejarah dan perjalanan panjang berdirinya institusi
            pendidikan kami hingga menjadi seperti sekarang.
          </p>
        </div>

        <div v-if="isLoading" class="relative wrap overflow-hidden h-full">
          <div
            class="hidden min-[400px]:block absolute z-0 w-1 bg-blue-900 dark:bg-slate-800 h-full left-6 sm:left-8 transform -translate-x-1/2 rounded-full"
          ></div>
          <div
            v-for="i in 3"
            :key="'skel-' + i"
            class="relative z-10 flex items-center w-full mb-8 min-[400px]:mb-12 last:mb-0 group animate-pulse"
          >
            <div
              class="hidden min-[400px]:flex absolute left-6 sm:left-8 transform -translate-x-1/2 items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full border-4 border-blue-950 dark:border-slate-950 shadow-lg z-20 bg-slate-800"
            >
              <div class="w-5 h-5 bg-slate-600 rounded-full"></div>
            </div>
            <div class="ml-0 min-[400px]:ml-16 sm:ml-20 lg:ml-24 flex-1 w-full">
              <div
                class="relative z-10 flex flex-col bg-white dark:bg-slate-800 rounded-xl shadow-xl overflow-hidden p-5 min-[400px]:p-6 md:p-8 lg:p-10"
              >
                <div class="mb-4 min-[400px]:mb-5">
                  <div
                    class="w-24 h-7 min-[400px]:h-8 sm:h-9 bg-slate-200 dark:bg-slate-700 rounded-xl min-[400px]:rounded-2xl"
                  ></div>
                </div>
                <div
                  class="w-2/3 md:w-1/2 h-6 md:h-8 bg-slate-200 dark:bg-slate-700 rounded mb-4 md:mb-6"
                ></div>
                <div class="space-y-3 w-full">
                  <div
                    class="w-full h-4 md:h-5 bg-slate-200 dark:bg-slate-700 rounded"
                  ></div>
                  <div
                    class="w-11/12 h-4 md:h-5 bg-slate-200 dark:bg-slate-700 rounded"
                  ></div>
                  <div
                    class="w-4/5 h-4 md:h-5 bg-slate-200 dark:bg-slate-700 rounded"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div
          v-else-if="timeline.length === 0"
          class="relative wrap overflow-hidden h-full"
        >
          <div
            class="hidden min-[400px]:block absolute z-0 w-1 bg-blue-900 dark:bg-slate-800 h-full left-6 sm:left-8 transform -translate-x-1/2 rounded-full"
          ></div>
          <div
            class="relative z-10 flex items-center w-full mb-8 min-[400px]:mb-12 group"
          >
            <div
              class="hidden min-[400px]:flex absolute left-6 sm:left-8 transform -translate-x-1/2 items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full border-4 border-blue-950 dark:border-slate-950 shadow-lg z-20 bg-blue-900 text-blue-400"
            >
              <PhClockCounterClockwise class="w-5 h-5 md:w-6 md:h-6" />
            </div>
            <div class="ml-0 min-[400px]:ml-16 sm:ml-20 lg:ml-24 flex-1 w-full">
              <div
                class="relative flex flex-col bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl shadow-xl p-5 min-[400px]:p-6 md:p-8 lg:p-10 text-center"
              >
                <div
                  class="mx-auto w-16 h-16 bg-blue-50/10 rounded-full flex items-center justify-center mb-4"
                >
                  <PhClockCounterClockwise class="w-8 h-8 text-blue-200" />
                </div>
                <h3 class="text-xl md:text-2xl font-bold text-white mb-2">
                  Riwayat Sejarah Belum Tersedia
                </h3>
                <p class="text-blue-100 text-sm md:text-base max-w-lg mx-auto">
                  Informasi mengenai lini masa dan sejarah institusi sedang dalam tahap
                  penyusunan. Silakan kembali lagi nanti.
                </p>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="relative wrap overflow-hidden h-full" ref="timelineRef">
          <!-- Garis Tengah Timeline (Latar Belakang) -->
          <div
            class="hidden min-[400px]:block absolute z-0 w-1 bg-blue-900 dark:bg-slate-800 h-full left-6 sm:left-8 transform -translate-x-1/2 rounded-full"
          >
            <!-- Garis Animasi Menjalar (Foreground) -->
            <div
              class="absolute top-0 left-0 w-full bg-blue-500 rounded-full transition-all duration-200 ease-out"
              :style="{ height: lineHeight }"
            ></div>
          </div>

          <!-- Looping Data Lini Masa -->
          <div
            v-for="(item, index) in timeline"
            :key="index"
            class="fade-on-scroll opacity-0 translate-y-10 scale-95 transition-all duration-1000 ease-out relative z-10 flex items-center w-full mb-8 min-[400px]:mb-12 last:mb-0 group"
          >
            <!-- Ikon Lingkaran Timeline -->
            <div
              class="hidden min-[400px]:flex absolute left-6 sm:left-8 transform -translate-x-1/2 items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full border-4 border-blue-950 dark:border-slate-950 shadow-lg z-20 group-hover:scale-110 transition-all duration-500"
              :class="
                currentProgress >= index / timeline.length
                  ? 'bg-blue-500 text-white shadow-[0_0_15px_rgba(59,130,246,0.5)]'
                  : 'bg-blue-900 dark:bg-slate-800 text-blue-400 dark:text-gray-400'
              "
            >
              <component
                :is="getIconComponent(item.icon)"
                class="w-5 h-5 md:w-6 md:h-6"
              />
            </div>

            <!-- Kartu Konten -->
            <div class="ml-0 min-[400px]:ml-16 sm:ml-20 lg:ml-24 flex-1 w-full">
              <div
                class="group/card relative hover:-translate-y-1.5 transition-transform duration-400"
              >
                <div
                  class="hidden md:block absolute top-1/2 -translate-y-1/2 w-6 h-6 bg-white dark:bg-slate-800 transform rotate-45 z-20 -left-3"
                ></div>

                <div
                  class="relative z-10 flex flex-col bg-white dark:bg-slate-800 rounded-xl shadow-xl group-hover/card:shadow-2xl overflow-hidden transition-shadow duration-400 p-5 min-[400px]:p-6 md:p-8 lg:p-10"
                >
                  <!-- Badge Tahun -->
                  <div class="mb-4 min-[400px]:mb-5">
                    <div
                      class="block w-full px-4 min-[400px]:px-5 py-1 bg-blue-600 dark:bg-blue-500 text-white font-bold text-sm min-[400px]:text-md sm:text-lg tracking-wider rounded-xl min-[400px]:rounded-2xl shadow-md text-left"
                      style="font-family: 'Kalam', cursive"
                    >
                      Tahun {{ item.year }}
                    </div>
                  </div>

                  <!-- Teks/Deskripsi -->
                  <h3
                    class="text-md md:text-2xl font-bold text-blue-950 dark:text-white mb-4 md:mb-6 leading-tight group-hover/card:text-blue-600 dark:group-hover/card:text-blue-400 transition-colors"
                  >
                    {{ item.title }}
                  </h3>

                  <div class="clearfix">
                    <!-- Gambar mengambang ke kiri -->
                    <div
                      v-if="item.image"
                      class="float-left w-[45%] sm:w-[40%] lg:w-[35%] max-w-[200px] sm:max-w-[240px] md:max-w-[280px] lg:max-w-[360px] mr-4 mb-2 md:mr-6 md:mb-4 lg:mr-8 lg:mb-5"
                    >
                      <div
                        class="rounded-lg overflow-hidden shadow-sm group-hover/card:shadow-md transition-shadow duration-300"
                      >
                        <img
                          :src="item.image"
                          :alt="item.title"
                          class="w-full aspect-[4/3] object-cover group-hover/card:scale-110 transition-transform duration-700"
                        />
                      </div>
                      <!-- Keterangan Gambar -->
                      <p
                        class="text-xs md:text-md font-light text-gray-500 dark:text-gray-400 mt-1.5 md:mt-2 text-center line-clamp-2 leading-tight px-1 italic"
                      >
                        Ilustrasi: {{ item.title }}
                      </p>
                    </div>

                    <div
                      class="text-gray-600 dark:text-gray-400 text-sm md:text-lg leading-relaxed text-justify editor-content-preview fade-sentence opacity-0 transition-opacity duration-1000 ease-in-out"
                      v-html="item.description"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");
</style>
