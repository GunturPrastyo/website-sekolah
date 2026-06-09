<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from "vue";
import PageHeader from "@/components/PageHeader.vue";
import api from "@/api/index.js";
import {
  PhBook,
  PhMedal,
  PhBookOpen,
  PhCalculator,
  PhMonitor,
  PhFlask,
  PhLightning,
  PhLeaf,
  PhChartPieSlice,
  PhUsers,
  PhTranslate,
  PhSigma,
  PhMapTrifold,
  PhHourglass,
  PhGlobe,
  PhUsersThree,
  PhHeart,
  PhGlobeHemisphereWest,
  PhUserCheck,
  PhLightbulb,
  PhPalette,
  PhMagnifyingGlass,
  PhWarningCircle,
  PhFileX,
  PhCaretRight,
  PhCaretLeft,
  PhInfo,
  PhCaretDown,
  PhBookmark,
  PhCheckCircle,
  PhSpinner,
  PhStar,
  PhHandshake,
  PhHandsClapping,
  PhPlant,
  PhRecycle,
  PhScales,
  PhShieldCheck,
  PhBrain,
  PhTree,
  PhHandHeart,
  PhPerson,
  PhUsersFour,
  PhHandsPraying,
  PhSparkle,
} from "@phosphor-icons/vue";
import { educationIcons } from "@/components/IconPicker.vue";

const activeGrade = ref("10");
const activeMajor = ref("");
const expandedSubject = ref(null);
const searchQuery = ref("");
const showMajorInfo = ref(false);

const changeGrade = (id) => {
  activeGrade.value = id;
  expandedSubject.value = null; // Reset accordion saat ganti kelas
};

const majors = ref([]);

const iconMap = {
  ...educationIcons,
  PhBook,
  PhMedal,
  PhBookOpen,
  PhCalculator,
  PhMonitor,
  PhFlask,
  PhLightning,
  PhLeaf,
  PhChartPieSlice,
  PhUsers,
  PhTranslate,
  PhSigma,
  PhMapTrifold,
  PhHourglass,
  PhGlobe,
  PhUsersThree,
  PhHeart,
  PhGlobeHemisphereWest,
  PhUserCheck,
  PhLightbulb,
  PhPalette,
  PhMagnifyingGlass,
  PhWarningCircle,
  PhFileX,
  PhCaretRight,
  PhInfo,
  PhCaretDown,
  PhBookmark,
  PhCheckCircle,
  PhStar,
  PhHandshake,
  PhHandsClapping,
  PhPlant,
  PhRecycle,
  PhScales,
  PhShieldCheck,
  PhBrain,
  PhTree,
  PhHandHeart,
  PhPerson,
  PhUsersFour,
  PhHandsPraying,
  PhSparkle,
};

const getDarkColorClass = (colorClass) => {
  if (colorClass && colorClass.startsWith("#")) return "text-white";
  const colorMap = {
    "text-slate-500": "bg-slate-600 dark:bg-slate-500 text-white",
    "text-blue-500": "bg-blue-600 dark:bg-blue-500 text-white",
    "text-red-500": "bg-red-600 dark:bg-red-500 text-white",
    "text-green-500": "bg-green-600 dark:bg-green-500 text-white",
    "text-yellow-500": "bg-yellow-500 dark:bg-yellow-600 text-white",
    "text-purple-500": "bg-purple-600 dark:bg-purple-500 text-white",
    "text-orange-500": "bg-orange-500 dark:bg-orange-600 text-white",
    "text-pink-500": "bg-pink-600 dark:bg-pink-500 text-white",
    "text-teal-500": "bg-teal-600 dark:bg-teal-500 text-white",
    "text-gray-500": "bg-gray-600 dark:bg-gray-500 text-white",
    "text-emerald-500": "bg-emerald-600 dark:bg-emerald-500 text-white",
    "text-cyan-500": "bg-cyan-600 dark:bg-cyan-500 text-white",
    "text-indigo-500": "bg-indigo-600 dark:bg-indigo-500 text-white",
    "text-rose-500": "bg-rose-600 dark:bg-rose-500 text-white",
  };
  return colorMap[colorClass] || "bg-blue-600 dark:bg-blue-500 text-white";
};

const changeMajor = (id) => {
  activeMajor.value = id;
  expandedSubject.value = null; // Reset accordion saat ganti jurusan
};

const toggleSubject = (id) => {
  expandedSubject.value = expandedSubject.value === id ? null : id;
};

const grades = ref([]);

const curriculumData = ref({});
const isFetching = ref(true);

const pancasilaProfile = ref({
  title: "Profil Pelajar Pancasila",
  description: "Memuat deskripsi...",
  dimensions: [],
});

const fetchCurriculum = async () => {
  isFetching.value = true;
  try {
    const [currRes, progRes, pancaRes] = await Promise.all([
      api.get("/api/public-curriculum-subjects"),
      api.get("/api/public-programs").catch(() => null),
      api.get("/api/public-pancasila-profile").catch(() => null),
    ]);

    if (pancaRes && pancaRes.data && pancaRes.data.data) {
      const pd = pancaRes.data.data;
      pancasilaProfile.value.title = pd.title || pancasilaProfile.value.title;
      pancasilaProfile.value.description =
        pd.description || pancasilaProfile.value.description;
      if (pd.dimensions && pd.dimensions.length > 0) {
        let dims = pd.dimensions;
        if (typeof dims === "string") {
          try {
            dims = JSON.parse(dims);
          } catch (e) {}
        }
        if (Array.isArray(dims) && dims.length > 0) {
          pancasilaProfile.value.dimensions = dims;
        }
      }
    }

    if (progRes && progRes.data && progRes.data.data) {
      majors.value = progRes.data.data.map((p) => ({
        id: String(p.id),
        name: p.title,
        desc: p.badge || p.description?.substring(0, 50) || "",
      }));
    } else {
      majors.value = [
        { id: "1", name: "MIPA", desc: "Matematika & Ilmu Pengetahuan Alam" },
        { id: "2", name: "IPS", desc: "Ilmu Pengetahuan Sosial" },
        { id: "3", name: "Bahasa", desc: "Ilmu Bahasa & Budaya" },
      ];
    }

    if (currRes && currRes.data && currRes.data.data) {
      const data = currRes.data.data;
      const groupedData = {};
      const uniqueGrades = new Set();

      data.forEach((subject) => {
        const grade = String(subject.grade);
        uniqueGrades.add(grade);
        const category = subject.category;

        const programId = subject.program_id;

        if (!groupedData[grade]) groupedData[grade] = {};

        // Jika program_id null/kosong (Umum), masukkan mapel ini ke semua peminatan
        const majorsToAdd =
          programId === null ||
          programId === undefined ||
          programId === "semua" ||
          programId === ""
            ? majors.value.map((m) => m.id)
            : [String(programId)];

        majorsToAdd.forEach((m) => {
          if (!groupedData[grade][m]) groupedData[grade][m] = [];

          let catObj = groupedData[grade][m].find((c) => c.category === category);
          if (!catObj) {
            catObj = { category: category, subjects: [] };
            groupedData[grade][m].push(catObj);
          }

          let topics = [];
          try {
            topics =
              typeof subject.topics === "string"
                ? JSON.parse(subject.topics)
                : subject.topics;
          } catch (e) {
            topics = typeof subject.topics === "string" ? subject.topics.split(",") : [];
          }

          const isHex = subject.color && subject.color.startsWith("#");
          catObj.subjects.push({
            id: subject.id || `${grade}-${m}-${category}-${subject.name}`,
            name: subject.name,
            desc: subject.description || subject.desc,
            icon: iconMap[subject.icon] || iconMap.PhBook,
            color: "",
            bg: isHex
              ? "shadow-md text-white"
              : getDarkColorClass(subject.color) + " shadow-md",
            hexStyle: isHex ? { backgroundColor: subject.color } : {},
            topics: topics || [],
          });
        });
      });

      curriculumData.value = groupedData;

      const gradesArray = Array.from(uniqueGrades).sort(
        (a, b) => parseInt(a) - parseInt(b)
      );
      if (gradesArray.length > 0) {
        grades.value = gradesArray.map((g) => ({
          id: g,
          name: `Kelas ${g === "10" ? "X" : g === "11" ? "XI" : "XII"}`,
          desc: g === "10" ? "Fase E" : "Fase F",
        }));
        if (!gradesArray.includes(activeGrade.value)) {
          activeGrade.value = gradesArray[0];
        }
      } else {
        grades.value = [
          { id: "10", name: "Kelas X (Fase E)", desc: "Peminatan Dasar & Umum" },
          { id: "11", name: "Kelas XI (Fase F)", desc: "Peminatan Lanjutan" },
          { id: "12", name: "Kelas XII (Fase F)", desc: "Pendalaman & Persiapan UTBK" },
        ];
      }
    }

    if (
      majors.value.length > 0 &&
      !majors.value.find((m) => m.id === activeMajor.value)
    ) {
      activeMajor.value = majors.value[0].id;
    }
  } catch (error) {
    console.error("Gagal mengambil data kurikulum:", error);
  } finally {
    isFetching.value = false;
  }
};

const currentSyllabus = computed(() => {
  if (!curriculumData.value[activeGrade.value]) return [];
  let syllabus = curriculumData.value[activeGrade.value][activeMajor.value] || [];

  if (searchQuery.value.trim() !== "") {
    const query = searchQuery.value.toLowerCase();

    const filteredSyllabus = syllabus
      .map((category) => {
        const filteredSubjects = category.subjects.filter(
          (subject) =>
            subject.name.toLowerCase().includes(query) ||
            subject.desc.toLowerCase().includes(query)
        );
        return { ...category, subjects: filteredSubjects };
      })
      .filter((category) => category.subjects.length > 0);

    return filteredSyllabus;
  }

  return syllabus;
});

const itemsPerPage = ref(window.innerWidth >= 768 ? 8 : 6);
const currentPage = ref(1);
const isChangingPage = ref(false);

const handleResize = () => {
  itemsPerPage.value = window.innerWidth >= 768 ? 8 : 6;
};

watch([activeGrade, activeMajor, searchQuery], () => {
  currentPage.value = 1;
});

const flattenedSyllabus = computed(() => {
  const flattened = [];
  currentSyllabus.value.forEach((cat) => {
    cat.subjects.forEach((sub) => {
      flattened.push({ ...sub, category: cat.category });
    });
  });
  return flattened;
});

const totalPages = computed(() => {
  return Math.ceil(flattenedSyllabus.value.length / itemsPerPage.value) || 1;
});

watch(currentPage, (newVal, oldVal) => {
  if (newVal !== oldVal) {
    isChangingPage.value = true;

    // Otomatis scroll perlahan ke atas daftar silabus saat ganti halaman
    const el = document.getElementById("syllabus-top");
    if (el) {
      const y = el.getBoundingClientRect().top + window.scrollY - 100;
      window.scrollTo({ top: y, behavior: "smooth" });
    }

    setTimeout(() => {
      isChangingPage.value = false;
    }, 500); // Simulasi delay lazy loading data (500ms)
  }
});

watch(totalPages, (newVal) => {
  if (currentPage.value > newVal) {
    currentPage.value = newVal;
  }
});

const paginatedSyllabus = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  const sliced = flattenedSyllabus.value.slice(start, end);

  const groups = {};
  sliced.forEach((sub) => {
    if (!groups[sub.category]) {
      groups[sub.category] = { category: sub.category, subjects: [] };
    }
    groups[sub.category].subjects.push(sub);
  });
  return Object.values(groups);
});

onMounted(() => {
  fetchCurriculum();
  window.addEventListener("resize", handleResize);
});

onBeforeUnmount(() => {
  window.removeEventListener("resize", handleResize);
});
</script>

<template>
  <div>
    <PageHeader
      badge="Akademik"
      title="Silabus & Kurikulum Digital"
      description="Jelajahi struktur kurikulum interaktif kami yang dirancang khusus untuk membekali siswa dengan kompetensi abad 21 (Kurikulum Merdeka)."
      bgImage="https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1600&auto=format&fit=crop"
    />

    <!-- Main Content Section -->
    <section class="pt-8 md:pt-12 pb-12 px-6 bg-gray-50 dark:bg-slate-900">
      <div class="container mx-auto max-w-full px-0 lg:px-16">
        <!-- Profil Pelajar Pancasila -->
        <div>
          <div v-if="isFetching" class="w-full">
            <div class="text-center mb-10">
              <div class="inline-flex items-center justify-center space-x-2 mb-3">
                <span
                  class="h-px w-8 sm:w-12 bg-blue-200 dark:bg-blue-400/50 rounded-full animate-pulse"
                ></span>
                <div
                  class="h-6 w-32 bg-blue-200 dark:bg-blue-900/50 rounded animate-pulse"
                ></div>
                <span
                  class="h-px w-8 sm:w-12 bg-blue-200 dark:bg-blue-400/50 rounded-full animate-pulse"
                ></span>
              </div>
              <div
                class="h-10 w-3/4 max-w-md mx-auto bg-gray-200 dark:bg-slate-700 rounded-lg animate-pulse mb-4"
              ></div>
              <div class="space-y-2 max-w-2xl mx-auto">
                <div
                  class="h-4 w-full bg-gray-200 dark:bg-slate-700 rounded animate-pulse"
                ></div>
                <div
                  class="h-4 w-5/6 mx-auto bg-gray-200 dark:bg-slate-700 rounded animate-pulse"
                ></div>
              </div>
            </div>
            <div
              class="grid gap-6 mx-auto grid-cols-1 md:grid-cols-2 lg:grid-cols-3 w-full"
            >
              <div
                v-for="i in 6"
                :key="i"
                class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 flex items-start gap-4 animate-pulse"
              >
                <div
                  class="w-12 h-12 shrink-0 rounded-full bg-gray-200 dark:bg-slate-700"
                ></div>
                <div class="flex-1 space-y-3 py-1">
                  <div class="h-5 w-3/4 bg-gray-200 dark:bg-slate-700 rounded"></div>
                  <div class="space-y-2">
                    <div
                      class="h-3 w-full bg-gray-100 dark:bg-slate-700/50 rounded"
                    ></div>
                    <div class="h-3 w-5/6 bg-gray-100 dark:bg-slate-700/50 rounded"></div>
                    <div class="h-3 w-4/6 bg-gray-100 dark:bg-slate-700/50 rounded"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-else>
            <div class="text-center mb-10">
              <div class="inline-flex items-center justify-center space-x-2 mb-3">
                <span
                  class="h-px w-8 sm:w-12 bg-blue-600 dark:bg-blue-400 rounded-full"
                ></span>
                <span
                  class="text-blue-600 dark:text-blue-400 font-bold text-base sm:text-xl"
                  style="font-family: 'Kalam', cursive"
                  >Pilar Karakter</span
                >
                <span
                  class="h-px w-8 sm:w-12 bg-blue-600 dark:bg-blue-400 rounded-full"
                ></span>
              </div>
              <h2
                class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-4 tracking-tight flex items-center justify-center gap-3"
              >
                {{ pancasilaProfile.title }}
              </h2>
              <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                {{ pancasilaProfile.description }}
              </p>
            </div>
            <div
              class="grid gap-6 mx-auto w-full"
              :class="{
                'grid-cols-1': pancasilaProfile.dimensions.length === 1,
                'grid-cols-1 md:grid-cols-2': pancasilaProfile.dimensions.length === 2,
                'grid-cols-1 md:grid-cols-2 lg:grid-cols-3':
                  pancasilaProfile.dimensions.length >= 3 ||
                  pancasilaProfile.dimensions.length === 0,
              }"
            >
              <div
                v-for="(dim, idx) in pancasilaProfile.dimensions"
                :key="idx"
                class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 flex items-start gap-4 transition-transform hover:-translate-y-1"
              >
                <div
                  class="w-12 h-12 shrink-0 rounded-full flex items-center justify-center shadow-md"
                  :class="getDarkColorClass(dim.color)"
                  :style="
                    dim.color && dim.color.startsWith('#')
                      ? { backgroundColor: dim.color }
                      : {}
                  "
                >
                  <component :is="iconMap[dim.icon] || iconMap.PhHeart" class="w-6 h-6" />
                </div>
                <div>
                  <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                    {{ dim.name }}
                  </h4>
                  <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    {{ dim.desc }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Interactive Syllabus Area (Full Width to Footer) -->
    <section
      class="relative py-12 px-6 border-t border-blue-800/30 dark:border-slate-800 bg-center bg-cover overflow-hidden"
    >
      <!-- Background Overlay -->
      <div
        class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-950 to-slate-900/90 dark:from-slate-900/95 dark:to-slate-950/95 backdrop-blur-[2px]"
      ></div>

      <!-- Header Silabus -->
      <div
        class="relative z-10 container mx-auto max-w-full px-0 md:px-8 lg:px-4 text-center mb-12 md:mb-16"
      >
        <div class="inline-flex items-center justify-center space-x-2 mb-3">
          <span class="h-px w-8 sm:w-12 bg-blue-400 rounded-full"></span>
          <span
            class="text-blue-400 font-bold text-base sm:text-lg"
            style="font-family: 'Kalam', cursive"
            >Struktur Kurikulum</span
          >
          <span class="h-px w-8 sm:w-12 bg-blue-400 rounded-full"></span>
        </div>
        <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4 tracking-tight">
          Silabus Pembelajaran
        </h2>
        <p
          class="text-blue-100/80 dark:text-gray-400 max-w-2xl mx-auto text-sm sm:text-base"
        >
          Eksplorasi daftar mata pelajaran beserta rincian materi yang akan dipelajari
          sesuai dengan jenjang kelas dan peminatan yang dipilih.
        </p>
      </div>

      <div
        class="container relative z-10 mx-auto max-w-full px-0 lg:px-16 flex flex-col lg:flex-row gap-8 lg:gap-12 items-start"
      >
        <!-- Sidebar Tabs (Tingkat Kelas) -->
        <div class="w-full lg:w-1/3">
          <!-- Search Bar -->
          <div class="mb-10">
            <div class="relative">
              <PhMagnifyingGlass
                class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-blue-300 pointer-events-none z-10"
              />
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari mata pelajaran..."
                class="w-full pl-12 pr-5 py-4 rounded-xl border-2 border-white/10 bg-white/10 backdrop-blur-sm text-white placeholder:text-blue-200/70 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all shadow-sm text-base"
              />
            </div>
          </div>

          <!-- Jenjang Kelas Skeleton -->
          <div v-if="isFetching" class="mb-10">
            <div class="flex items-center gap-4 mb-6">
              <div
                class="h-8 w-40 bg-white/10 dark:bg-slate-700/50 rounded animate-pulse"
              ></div>
              <div class="h-px bg-white/20 dark:bg-slate-700 flex-1"></div>
            </div>
            <div class="flex flex-col gap-3">
              <div
                v-for="i in 3"
                :key="i"
                class="w-full p-5 rounded-lg border-2 border-white/5 bg-white/5 dark:bg-slate-800/30 animate-pulse flex justify-between items-center"
              >
                <div class="space-y-2 flex-1">
                  <div class="h-5 w-1/2 bg-white/10 dark:bg-slate-700/50 rounded"></div>
                  <div class="h-3 w-1/3 bg-white/10 dark:bg-slate-700/50 rounded"></div>
                </div>
                <div class="w-5 h-5 rounded bg-white/10 dark:bg-slate-700/50"></div>
              </div>
            </div>
          </div>
          <!-- Jenjang Kelas Data -->
          <div v-else class="mb-10">
            <div class="flex items-center gap-4 mb-6">
              <h2 class="text-xl md:text-2xl font-bold text-white">Jenjang Kelas</h2>
              <div class="h-px bg-white/20 dark:bg-slate-700 flex-1"></div>
            </div>
            <div class="flex flex-col gap-3">
              <button
                v-for="grade in grades"
                :key="grade.id"
                @click="changeGrade(grade.id)"
                class="w-full text-left p-5 rounded-lg transition-all duration-300 border-2"
                :class="
                  activeGrade === grade.id
                    ? 'bg-white border-white text-blue-600 shadow-xl shadow-black/10 dark:bg-slate-800 dark:border-slate-700 dark:text-white'
                    : 'bg-white/10 backdrop-blur-sm border-white/10 hover:bg-white/20 hover:border-white/30 text-blue-50 dark:bg-slate-800/50 dark:hover:bg-slate-800 dark:border-slate-700/50 dark:text-gray-300 shadow-sm'
                "
              >
                <div class="flex justify-between items-center">
                  <div>
                    <h4 class="text-lg font-bold mb-1">{{ grade.name }}</h4>
                    <p class="text-sm opacity-80">{{ grade.desc }}</p>
                  </div>
                  <PhCaretRight
                    class="w-5 h-5 transition-transform"
                    :class="activeGrade === grade.id ? 'translate-x-1' : ''"
                  />
                </div>
              </button>
            </div>
          </div>

          <!-- Peminatan / Jurusan Skeleton -->
          <div v-if="isFetching" class="mb-6">
            <div class="flex items-center gap-4 mb-6">
              <div
                class="h-8 w-48 bg-white/10 dark:bg-slate-700/50 rounded animate-pulse"
              ></div>
              <div class="h-px bg-white/20 dark:bg-slate-700 flex-1"></div>
            </div>
            <div class="flex flex-col gap-3">
              <div
                v-for="i in 3"
                :key="i"
                class="w-full p-5 rounded-lg border-2 border-white/5 bg-white/5 dark:bg-slate-800/30 animate-pulse flex justify-between items-center"
              >
                <div class="space-y-2 flex-1">
                  <div class="h-5 w-1/2 bg-white/10 dark:bg-slate-700/50 rounded"></div>
                  <div class="h-3 w-1/3 bg-white/10 dark:bg-slate-700/50 rounded"></div>
                </div>
                <div class="w-5 h-5 rounded bg-white/10 dark:bg-slate-700/50"></div>
              </div>
            </div>
          </div>
          <!-- Peminatan / Jurusan Data -->
          <div v-else class="mb-6">
            <div class="flex items-center gap-4 mb-3">
              <h2
                class="text-xl md:text-2xl font-bold text-white flex items-center gap-2"
              >
                Peminatan / Jurusan
                <button
                  @click="showMajorInfo = !showMajorInfo"
                  class="text-blue-300 hover:text-white transition-colors focus:outline-none"
                  title="Informasi Peminatan"
                >
                  <PhInfo class="w-5 h-5 md:w-6 md:h-6" />
                </button>
              </h2>
              <div class="h-px bg-white/20 dark:bg-slate-700 flex-1"></div>
            </div>

            <Transition
              enter-active-class="transition-all duration-300 ease-out overflow-hidden"
              enter-from-class="opacity-0 -translate-y-2 max-h-0"
              enter-to-class="opacity-100 translate-y-0 max-h-40"
              leave-active-class="transition-all duration-200 ease-in overflow-hidden"
              leave-from-class="opacity-100 translate-y-0 max-h-40"
              leave-to-class="opacity-0 -translate-y-2 max-h-0"
            >
              <div
                v-if="showMajorInfo"
                class="mb-4 p-3 bg-blue-800/40 border border-blue-400/30 rounded-lg text-xs sm:text-sm text-blue-100 leading-relaxed shadow-sm"
              >
                Mata pelajaran kategori <strong>Umum (Fase E)</strong> secara otomatis
                akan tersedia dan ditampilkan di semua pilihan peminatan.
              </div>
            </Transition>

            <div class="flex flex-col gap-3">
              <button
                v-for="major in majors"
                :key="major.id"
                @click="changeMajor(major.id)"
                class="w-full text-left p-5 rounded-lg transition-all duration-300 border-2"
                :class="
                  activeMajor === major.id
                    ? 'bg-white border-white text-blue-600 shadow-xl shadow-black/10 dark:bg-slate-800 dark:border-slate-700 dark:text-white'
                    : 'bg-white/10 backdrop-blur-sm border-white/10 hover:bg-white/20 hover:border-white/30 text-blue-50 dark:bg-slate-800/50 dark:hover:bg-slate-800 dark:border-slate-700/50 dark:text-gray-300 shadow-sm'
                "
              >
                <div class="flex justify-between items-center">
                  <div>
                    <h4 class="text-lg font-bold mb-1">{{ major.name }}</h4>
                    <p class="text-sm opacity-80">{{ major.desc }}</p>
                  </div>
                  <PhCaretRight
                    class="w-5 h-5 transition-transform"
                    :class="activeMajor === major.id ? 'translate-x-1' : ''"
                  />
                </div>
              </button>
            </div>
          </div>

          <!-- Info Box Mini -->
          <div
            class="mt-10 bg-white/10 backdrop-blur-md p-5 rounded-lg border border-white/20 dark:bg-slate-800 dark:border-slate-700 shadow-sm"
          >
            <h4 class="font-bold text-white mb-2 flex items-center">
              <PhInfo class="w-4 h-4 mr-2" /> Info Silabus
            </h4>
            <p class="text-sm text-blue-100 dark:text-gray-400 leading-relaxed">
              Klik pada setiap mata pelajaran di samping untuk melihat rincian topik
              pembelajaran (Capaian Pembelajaran) yang akan dibahas selama dua semester.
            </p>
          </div>
        </div>

        <!-- Main Content (Syllabus Accordion) -->
        <div class="w-full lg:w-2/3" id="syllabus-top">
          <Transition
            mode="out-in"
            enter-active-class="transition-all duration-400 ease-out"
            enter-from-class="opacity-0 translate-x-4"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 -translate-x-4"
          >
            <div v-if="isFetching || isChangingPage" class="w-full space-y-8 py-4">
              <div v-for="i in 2" :key="i" class="mb-8">
                <!-- Category Header Skeleton -->
                <div class="flex items-center gap-4 mb-6">
                  <div
                    class="h-8 w-48 bg-white/10 dark:bg-slate-700/50 rounded animate-pulse"
                  ></div>
                  <div class="h-px bg-white/10 dark:bg-slate-700/50 flex-1"></div>
                </div>

                <!-- Subject Accordions Skeleton -->
                <div class="flex flex-col gap-4">
                  <div
                    v-for="j in 3"
                    :key="j"
                    class="bg-white/5 dark:bg-slate-800/50 rounded-lg p-5 md:p-6 flex items-center justify-between gap-4 animate-pulse border border-white/5 dark:border-slate-700/50"
                  >
                    <div class="flex items-center gap-4 w-full text-left">
                      <div
                        class="w-12 h-12 shrink-0 rounded-lg bg-white/10 dark:bg-slate-700/50"
                      ></div>
                      <div class="space-y-3 flex-1 py-1">
                        <div
                          class="h-5 w-1/3 bg-white/10 dark:bg-slate-700/50 rounded"
                        ></div>
                        <div
                          class="h-4 w-2/3 bg-white/10 dark:bg-slate-700/50 rounded"
                        ></div>
                      </div>
                    </div>
                    <div
                      class="shrink-0 w-8 h-8 rounded-full bg-white/10 dark:bg-slate-700/50"
                    ></div>
                  </div>
                </div>
              </div>
            </div>

            <div v-else :key="activeGrade + '-' + activeMajor" class="space-y-8">
              <div
                v-for="(category, idx) in paginatedSyllabus"
                :key="idx"
                class="mb-8 last:mb-0"
              >
                <!-- Category Header -->
                <div class="flex items-center gap-4 mb-6">
                  <h2 class="text-xl md:text-2xl font-bold text-white">
                    {{ category.category }}
                  </h2>
                  <div class="h-px bg-white/20 dark:bg-slate-700 flex-1"></div>
                </div>

                <!-- Subject Accordions -->
                <div class="flex flex-col gap-4">
                  <div
                    v-for="subject in category.subjects"
                    :key="subject.id"
                    class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-transparent dark:border-slate-700 overflow-hidden transition-all"
                    :class="
                      expandedSubject === subject.id
                        ? 'ring-4 ring-white/30 dark:ring-blue-500/40 shadow-xl'
                        : 'hover:border-white/50 hover:shadow-xl'
                    "
                  >
                    <!-- Accordion Header (Clickable) -->
                    <button
                      @click="toggleSubject(subject.id)"
                      class="w-full p-5 md:p-6 flex items-start sm:items-center justify-between gap-4 focus:outline-none"
                    >
                      <div class="flex items-start sm:items-center gap-4 text-left">
                        <div
                          class="w-12 h-12 shrink-0 rounded-lg flex items-center justify-center"
                          :class="subject.bg + ' ' + subject.color"
                          :style="subject.hexStyle"
                        >
                          <component :is="subject.icon" class="w-6 h-6" />
                        </div>
                        <div>
                          <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-1"
                          >
                            {{ subject.name }}
                          </h3>
                          <p
                            class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1 sm:line-clamp-none pr-4"
                          >
                            {{ subject.desc }}
                          </p>
                        </div>
                      </div>
                      <div
                        class="shrink-0 w-8 h-8 rounded-full bg-gray-50 dark:bg-slate-700 flex items-center justify-center text-gray-400 transition-transform duration-300"
                        :class="
                          expandedSubject === subject.id
                            ? 'rotate-180 bg-blue-50 text-blue-600 dark:bg-slate-600 dark:text-blue-400'
                            : ''
                        "
                      >
                        <PhCaretDown class="w-5 h-5" />
                      </div>
                    </button>

                    <!-- Accordion Body -->
                    <div
                      class="transition-all duration-500 ease-in-out overflow-hidden"
                      :class="
                        expandedSubject === subject.id
                          ? 'max-h-[800px] opacity-100'
                          : 'max-h-0 opacity-0'
                      "
                    >
                      <div
                        class="p-6 pt-0 border-t border-gray-50 dark:border-slate-700/50 bg-gray-50/50 dark:bg-slate-800/50 mt-2"
                      >
                        <h4
                          class="text-sm font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider flex items-center mt-4"
                        >
                          <PhBookmark class="w-4 h-4 mr-2 text-blue-500" />
                          Topik Pembelajaran
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                          <div
                            v-for="(topic, tIdx) in subject.topics"
                            :key="tIdx"
                            class="flex items-start gap-3 bg-white dark:bg-slate-700 p-3.5 rounded-lg border border-gray-100 dark:border-slate-600/50 shadow-sm"
                          >
                            <PhCheckCircle
                              class="w-5 h-5 text-green-500 shrink-0 mt-0.5"
                            />
                            <span
                              class="text-sm font-medium text-gray-700 dark:text-gray-200 leading-snug"
                              >{{ topic }}</span
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Empty State jika tidak ada data -->
              <div
                v-if="currentSyllabus.length === 0"
                class="py-16 text-center bg-white/10 dark:bg-slate-800/80 backdrop-blur-sm rounded-lg border border-dashed border-white/30 dark:border-slate-700"
              >
                <div
                  class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/20 dark:bg-slate-700 mb-4 text-white"
                >
                  <component
                    :is="searchQuery ? PhWarningCircle : PhFileX"
                    class="w-8 h-8"
                  />
                </div>
                <h3 class="text-lg font-bold text-white">
                  {{
                    searchQuery ? "Pelajaran Tidak Ditemukan" : "Silabus Belum Tersedia"
                  }}
                </h3>
                <p class="text-blue-100 dark:text-gray-400 mt-1">
                  {{
                    searchQuery
                      ? `Tidak ada mata pelajaran yang cocok dengan kata kunci "${searchQuery}".`
                      : "Modul kurikulum untuk kelas ini sedang dalam proses penyusunan."
                  }}
                </p>
              </div>

              <!-- Pagination Controls -->
              <div
                v-if="totalPages > 1 && flattenedSyllabus.length > 0"
                class="flex justify-between items-center mt-10 w-full border-t border-white/10 pt-6"
              >
                <!-- Tombol Sebelumnya -->
                <button
                  @click="currentPage > 1 ? currentPage-- : null"
                  :disabled="currentPage === 1"
                  class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 text-white hover:bg-white/20 disabled:opacity-50 disabled:cursor-not-allowed transition-all font-medium text-sm md:text-base border border-white/5 hover:border-white/20"
                >
                  <PhCaretLeft class="w-5 h-5" />
                  <span class="hidden sm:inline">Sebelumnya</span>
                </button>

                <!-- Angka Pagination -->
                <div class="flex gap-2 flex-wrap justify-center mx-2">
                  <button
                    v-for="page in totalPages"
                    :key="page"
                    @click="currentPage = page"
                    class="w-10 h-10 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center border border-transparent"
                    :class="
                      currentPage === page
                        ? 'bg-blue-600 text-white shadow-lg border-blue-400 scale-110'
                        : 'bg-white/10 text-white hover:bg-white/20 hover:border-white/30'
                    "
                  >
                    {{ page }}
                  </button>
                </div>
                <!-- Tombol Selanjutnya -->
                <button
                  @click="currentPage < totalPages ? currentPage++ : null"
                  :disabled="currentPage === totalPages"
                  class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 text-white hover:bg-white/20 disabled:opacity-50 disabled:cursor-not-allowed transition-all font-medium text-sm md:text-base border border-white/5 hover:border-white/20"
                >
                  <span class="hidden sm:inline">Selanjutnya</span>
                  <PhCaretRight class="w-5 h-5" />
                </button>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");
</style>
