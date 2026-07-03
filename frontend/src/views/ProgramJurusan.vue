<script setup>
import { ref, onMounted, nextTick } from "vue";
import {
  PhGraduationCap,
  PhBookOpen,
  PhCheckCircle,
  PhBriefcase,
} from "@phosphor-icons/vue";
import PageHeader from "@/components/PageHeader.vue";
import api from "@/api/index.js";

const programs = ref([]);
const appearanceSettings = ref({});
const isFetching = ref(true);

const getImageUrl = (path, defaultUrl = "") => {
  if (!path) return defaultUrl;
  if (path.startsWith("http") || path.startsWith("data:")) return path;

  const backendUrl = api.defaults.baseURL || ""; // Ensure backendUrl is a string
  return `${backendUrl.replace(/\/+$/, "")}/${path.replace(/^\/+/, "")}`; // Safely call replace
};

const handleImageError = (e) => {
  e.target.src = "";
};

const parseJSON = (data) => {
  if (!data) return [];
  if (typeof data === "string") {
    try {
      return JSON.parse(data);
    } catch (e) {
      return data.split(",").map((item) => item.trim());
    }
  }
  return data;
};

const fetchInitialData = async () => {
  isFetching.value = true;
  try {
    const [programsResponse, settingsResponse] = await Promise.all([
      api.get("/api/public-programs"),
      api.get("/api/settings"), // Mengambil konfigurasi gambar dinamis dari VPS
    ]);

    // Set Data Program Keahlian
    if (programsResponse.data && programsResponse.data.data) {
      programs.value = programsResponse.data.data.map((program) => ({
        ...program,
        subjects: parseJSON(program.subjects),
        careers: parseJSON(program.careers),
        bgClass: program.bgClass || "bg-blue-50 dark:bg-blue-900/20",
        sectionBgClass: program.sectionBgClass || "bg-blue-900 dark:bg-blue-950",
        textClass: program.textClass || "text-blue-600 dark:text-blue-400",
        borderClass: program.borderClass || "border-blue-200 dark:border-blue-800",
        decorationClass: program.decorationClass || "bg-blue-600 dark:bg-blue-500",
        image: getImageUrl(
          program.image,
          "https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800"
        ),
        background_img: getImageUrl(program.background_img, ""),
      }));
    }

    // Set Data Global Settings untuk Background Header
    if (settingsResponse.data?.success) {
      appearanceSettings.value = settingsResponse.data.data;
    }
  } catch (error) {
    console.error("Gagal memuat data awal halaman program jurusan:", error);
  } finally {
    isFetching.value = false;
    nextTick(() => {
      setupObserver();
    });
  }
};

const setupObserver = () => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("opacity-100", "translate-y-0", "translate-x-0");
          entry.target.classList.remove(
            "opacity-0",
            "translate-y-10",
            "-translate-x-10",
            "translate-x-10"
          );
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 }
  );

  document.querySelectorAll(".fade-on-scroll").forEach((el) => {
    observer.observe(el);
  });
};

onMounted(() => {
  fetchInitialData();
});
</script>

<template>
  <div>
    <PageHeader
      v-if="!isFetching && appearanceSettings"
      badge="Pilihan Jurusan"
      title="Program Keahlian Sekolah"
      description="Menyediakan beragam program keahlian yang membekali siswa dengan ilmu pengetahuan dan keterampilan praktis untuk meraih sukses di perguruan tinggi maupun karir."
      :bgImage="getImageUrl(appearanceSettings.headerProgramJurusan)"
    />

    <!-- Program Details Section -->
    <div class="min-h-screen flex flex-col relative transition-colors duration-700">
      <!-- Loading Skeleton -->
      <div v-if="isFetching" class="w-full flex flex-col z-10 relative">
        <section
          v-for="i in 3"
          :key="i"
          class="relative py-16 md:py-24 px-0 lg:px-8 overflow-hidden z-10"
        >
          <div class="container relative z-10 mx-auto max-w-6xl">
            <div
              class="flex flex-col lg:flex-row gap-10 lg:gap-16 items-center"
              :class="{ 'lg:flex-row-reverse': i % 2 !== 0 }"
            >
              <div class="w-full lg:w-1/2">
                <div
                  class="aspect-video md:aspect-[4/3] rounded-lg bg-slate-200 dark:bg-slate-800 animate-pulse"
                ></div>
              </div>
              <div class="w-full lg:w-1/2 space-y-6">
                <div
                  class="h-8 w-32 bg-slate-200 dark:bg-slate-800 rounded-full animate-pulse"
                ></div>
                <div
                  class="h-10 w-3/4 bg-slate-200 dark:bg-slate-800 rounded animate-pulse"
                ></div>
                <div class="space-y-3 pt-4">
                  <div
                    class="h-4 w-full bg-slate-200 dark:bg-slate-800 rounded animate-pulse"
                  ></div>
                  <div
                    class="h-4 w-5/6 bg-slate-200 dark:bg-slate-800 rounded animate-pulse"
                  ></div>
                  <div
                    class="h-4 w-4/6 bg-slate-200 dark:bg-slate-800 rounded animate-pulse"
                  ></div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-4">
                  <div class="space-y-3">
                    <div
                      class="h-5 w-1/2 bg-slate-200 dark:bg-slate-800 rounded animate-pulse mb-4"
                    ></div>
                    <div
                      class="h-4 w-full bg-slate-200 dark:bg-slate-800 rounded animate-pulse"
                    ></div>
                    <div
                      class="h-4 w-5/6 bg-slate-200 dark:bg-slate-800 rounded animate-pulse"
                    ></div>
                  </div>
                  <div class="space-y-3">
                    <div
                      class="h-5 w-1/2 bg-slate-200 dark:bg-slate-800 rounded animate-pulse mb-4"
                    ></div>
                    <div
                      class="h-4 w-full bg-slate-200 dark:bg-slate-800 rounded animate-pulse"
                    ></div>
                    <div
                      class="h-4 w-5/6 bg-slate-200 dark:bg-slate-800 rounded animate-pulse"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!-- Render Tiap Jurusan dengan Selang-seling (Alternate Layout & Background) -->
      <template v-else>
        <section
          v-for="(program, index) in programs"
          :key="program.id"
          class="max-w-full w-full relative py-16 md:py-24 transition-colors duration-700 ease-in-out overflow-hidden z-10 bg-fixed bg-cover bg-center"
          :style="
            program.background_img
              ? { backgroundImage: `url(${program.background_img})` }
              : {}
          "
        >
          <!-- Background Overlay -->
          <div
            class="absolute inset-0 z-0 transition-opacity duration-700 pointer-events-none"
            :class="
              index % 2 === 0
                ? 'bg-white/90 dark:bg-slate-900/95 backdrop-blur-[2px]'
                : program.sectionBgClass +
                  ' bg-opacity-95 dark:bg-opacity-95 backdrop-blur-md'
            "
          ></div>

          <div class="container relative z-10 mx-auto max-w-full px-0 lg:px-8">
            <div
              class="flex flex-col lg:flex-row gap-10 lg:gap-16 items-center"
              :class="{ 'lg:flex-row-reverse': index % 2 !== 0 }"
            >
              <!-- Kolom Gambar -->
              <div
                class="w-full lg:w-1/2 relative group fade-on-scroll opacity-0 transition-all duration-700 ease-out"
                :class="index % 2 === 0 ? '-translate-x-10' : 'translate-x-10'"
              >
                <div
                  class="absolute inset-0 translate-x-3 translate-y-3 md:translate-x-4 md:translate-y-4 rounded-lg shadow-lg transition-all duration-700 ease-in-out group-hover:translate-x-5 group-hover:translate-y-5 md:group-hover:translate-x-6 md:group-hover:translate-y-6"
                  :class="program.decorationClass"
                ></div>
                <div
                  class="relative rounded-lg overflow-hidden shadow-2xl aspect-video md:aspect-[4/3] border-4 border-white dark:border-slate-800 bg-white dark:bg-slate-800 z-10 transition-colors duration-700 ease-in-out"
                >
                  <img
                    :src="program.image"
                    @error="handleImageError"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                    :alt="'Jurusan ' + program.title"
                  />
                </div>
              </div>

              <!-- Kolom Deskripsi -->
              <div class="w-full lg:w-1/2 space-y-6">
                <div
                  class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold shadow-sm border backdrop-blur-sm"
                  style="font-family: 'Kalam', cursive"
                  :class="[
                    index % 2 === 0 ? program.bgClass : 'bg-white/10 dark:bg-black/20',
                    index % 2 === 0 ? program.textClass : 'text-white',
                    index % 2 === 0
                      ? program.borderClass
                      : 'border-white/20 dark:border-white/10',
                  ]"
                >
                  <PhGraduationCap class="w-3.5 h-3.5 mr-1.5" />
                  {{ program.badge }}
                </div>
                <h2
                  class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-100 ease-out text-3xl md:text-4xl font-bold"
                  :class="
                    index % 2 === 0 ? 'text-slate-900 dark:text-white' : 'text-white'
                  "
                >
                  {{ program.title }}
                </h2>
                <p
                  class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-200 ease-out text-base md:text-lg leading-relaxed text-justify"
                  :class="
                    index % 2 === 0
                      ? 'text-slate-600 dark:text-slate-400'
                      : 'text-white/80 dark:text-slate-300'
                  "
                >
                  {{ program.description }}
                </p>

                <div
                  class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-300 ease-out grid grid-cols-1 sm:grid-cols-2 gap-8 pt-4"
                >
                  <!-- Fokus Pembelajaran -->
                  <div>
                    <h4
                      class="font-bold mb-3 flex items-center"
                      :class="
                        index % 2 === 0 ? 'text-slate-900 dark:text-white' : 'text-white'
                      "
                    >
                      <PhBookOpen
                        class="w-5 h-5 mr-2"
                        :class="index % 2 === 0 ? program.textClass : 'text-white/90'"
                      />
                      Fokus Pembelajaran
                    </h4>
                    <ul class="space-y-2.5">
                      <li
                        v-for="(subject, idx) in program.subjects"
                        :key="idx"
                        class="flex items-start text-sm transition-colors duration-700 ease-in-out"
                        :class="[
                          index % 2 === 0
                            ? 'text-slate-600 dark:text-slate-400'
                            : 'text-white/80 dark:text-slate-300',
                        ]"
                      >
                        <PhCheckCircle
                          class="w-4 h-4 mr-2 mt-0.5 shrink-0"
                          :class="index % 2 === 0 ? program.textClass : 'text-white/90'"
                        />
                        <span>{{ subject }}</span>
                      </li>
                    </ul>
                  </div>
                  <!-- Prospek Karir -->
                  <div>
                    <h4
                      class="font-bold mb-3 flex items-center"
                      :class="
                        index % 2 === 0 ? 'text-slate-900 dark:text-white' : 'text-white'
                      "
                    >
                      <PhBriefcase
                        class="w-5 h-5 mr-2"
                        :class="index % 2 === 0 ? program.textClass : 'text-white/90'"
                      />
                      Prospek Karir
                    </h4>
                    <ul class="space-y-2.5">
                      <li
                        v-for="(career, idx) in program.careers"
                        :key="idx"
                        class="flex items-start text-sm transition-colors duration-700 ease-in-out"
                        :class="[
                          index % 2 === 0
                            ? 'text-slate-600 dark:text-slate-400'
                            : 'text-white/80 dark:text-slate-300',
                        ]"
                      >
                        <PhCheckCircle
                          class="w-4 h-4 mr-2 mt-0.5 shrink-0"
                          :class="index % 2 === 0 ? program.textClass : 'text-white/90'"
                        />
                        <span>{{ career }}</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </template>
    </div>
  </div>
</template>
