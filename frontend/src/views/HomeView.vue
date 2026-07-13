<script setup>
import { ref, reactive, nextTick, onMounted, onBeforeUnmount } from "vue";
import api from "@/api/index.js";

// IMPORT SEMUA KOMPONEN
import HeroSection from "@/components/home/HeroSection.vue";
import FeaturesSection from "@/components/home/FeaturesSection.vue";
import AlumniMapSection from "@/components/home/AlumniMapSection.vue";
import ProgramsSection from "@/components/home/ProgramsSection.vue";
import NewsSection from "@/components/home/NewsSection.vue";
import VideoGallerySection from "@/components/home/VideoGallerySection.vue";
import AgendaSection from "@/components/home/AgendaSection.vue";
import PpdbFaqSection from "@/components/home/PpdbFaqSection.vue";

// STATE UTAMA
const fullTitle = ref(localStorage.getItem("app_namaSekolah") || "");
const slogan = ref(localStorage.getItem("app_sloganSekolah") || "");
const displayedTitle = ref("");
const showSubtitle = ref(false);
const isTypewriterStarted = ref(false);

const appearanceSettings = ref({
  loginBackground: "",
  benefitFasilitasImage: "",
  benefitGuruImage: "",
  benefitPrestasiImage: "",
  programCoverImage: "",
  ppdbBackgroundImage: "",
  galleryBackgroundImage: "",
  headerBeranda: localStorage.getItem("app_headerBeranda") || "",
});

const statsArray = ref([
  { key: "akreditasi", value: "-", target: "-", label: "Akreditasi", isNumber: false },
  {
    key: "siswa",
    value: 0,
    target: 0,
    label: "Siswa Aktif",
    suffix: "+",
    isNumber: true,
  },
  { key: "guru", value: 0, target: 0, label: "Tenaga Pendidik", isNumber: true },
  {
    key: "ekskul",
    value: 0,
    target: 0,
    label: "Ekstrakurikuler",
    suffix: "+",
    isNumber: true,
  },
  {
    key: "prestasi",
    value: 0,
    target: 0,
    label: "Prestasi",
    suffix: "+",
    isNumber: true,
  },
]);

const programs = ref([]);
const news = ref([]);
const announcements = ref([]);
const galleriesByCategory = ref([]);
const fourthGalleryImage = ref(null);
const agendas = ref([]);
const alumniLocations = ref([]);

const ppdbInfo = ref({
  academic_year: "2026/2027",
  title: "Siap Menjadi Bagian dari Kami?",
  description:
    "Wujudkan potensi terbaikmu di sini. Pantau terus informasi terbaru, pendaftaran akan segera dibuka.",
  opening_date: null,
  brochure_file: null,
  registration_link: null,
});
const faqs = ref([]);

const schoolVideoUrl = ref("");
const schoolVideoTitle = ref("");
const schoolVideoDesc = ref("");
const isVideoPlaying = ref(false);

const alumniStatsTarget = ref({ alumni: 0, ptn: 0, instansi: 0 });

const isLoading = reactive({
  programs: true,
  news: true,
  video: true,
  agendas: true,
  ppdb: true,
  map: true,
});

let scrollObserver = null;
let typewriterInterval = null;
let statsAnimationId = null;

// FUNGSI FETCH API
const fetchSettings = async () => {
  try {
    const res = await api.get("/api/settings");
    if (res.data?.success) {
      appearanceSettings.value = { ...appearanceSettings.value, ...res.data.data };
      const newTitle = res.data.data.namaSekolah || "";
      slogan.value = res.data.data.deskripsi || "";

      localStorage.setItem("app_namaSekolah", newTitle);
      localStorage.setItem("app_sloganSekolah", slogan.value);

      if (res.data.data.headerBeranda)
        localStorage.setItem("app_headerBeranda", res.data.data.headerBeranda);

      if (fullTitle.value !== newTitle) {
        fullTitle.value = newTitle;
        if (isTypewriterStarted.value) {
          displayedTitle.value = newTitle;
        } else {
          startTypewriter();
        }
      } else {
        if (!isTypewriterStarted.value) startTypewriter();
      }
    }
  } catch (error) {
    if (!isTypewriterStarted.value) startTypewriter();
  }
};

const fetchSchoolStats = async () => {
  try {
    const cached = localStorage.getItem("app_schoolStats");
    if (cached) {
      const data = JSON.parse(cached);
      statsArray.value.forEach((stat) => {
        if (data[stat.key]) {
          stat.target = stat.isNumber ? Number(data[stat.key]) || 0 : data[stat.key];
          if (!stat.isNumber) stat.value = stat.target;
        }
      });
    }
    const res = await api.get("/api/public-stats");
    if (res.data?.data) {
      localStorage.setItem("app_schoolStats", JSON.stringify(res.data.data));
      let needsAnim = false;
      statsArray.value.forEach((stat) => {
        if (res.data.data[stat.key] !== undefined) {
          const newTarget = stat.isNumber
            ? Number(res.data.data[stat.key]) || 0
            : res.data.data[stat.key];
          if (stat.target !== newTarget) {
            stat.target = newTarget;
            needsAnim = true;
          }
          if (!stat.isNumber) stat.value = stat.target;
        }
      });
      if (showSubtitle.value && needsAnim) animateStats();
    }
  } catch (error) {}
};

const fetchPrograms = async () => {
  try {
    const res = await api.get("/api/public-programs");
    if (res.data?.data) programs.value = res.data.data;
  } finally {
    isLoading.programs = false;
    nextTick(observeElements);
  }
};

const fetchNewsAndAnnouncements = async () => {
  try {
    const [newsRes, annRes] = await Promise.all([
      api.get("/api/public-news?per_page=4&exclude_category=pengumuman"),
      api.get("/api/public-news?per_page=10&category=pengumuman"),
    ]);
    if (newsRes.data?.data) news.value = newsRes.data.data;
    if (annRes.data?.data) announcements.value = annRes.data.data;
  } finally {
    isLoading.news = false;
    nextTick(observeElements);
  }
};

const fetchGalleries = async () => {
  try {
    const res = await api.get("/api/public-galleries");
    if (res.data?.data) {
      const allGalleries = res.data.data;
      const grouped = {};
      allGalleries.forEach((gallery) => {
        if (!grouped[gallery.category]) {
          grouped[gallery.category] = gallery;
        }
      });
      const groupedArr = Object.values(grouped);
      galleriesByCategory.value = groupedArr.slice(0, 3);

      if (groupedArr.length > 3) fourthGalleryImage.value = groupedArr[3].image;
      else if (allGalleries.length > 3) fourthGalleryImage.value = allGalleries[3].image;
      else if (allGalleries.length > 0) fourthGalleryImage.value = allGalleries[0].image;
    }
  } catch (error) {
    console.error("Gagal mengambil galeri:", error);
  }
};

const fetchAgendas = async () => {
  try {
    const res = await api.get("/api/public-agendas");
    if (res.data?.data && res.data.data.length > 0) {
      agendas.value = res.data.data.map((agenda) => {
        let dateText = "01";
        let monthText = "Jan";
        let startDate = null;
        let endDate = null;

        const rawStartDate =
          agenda.start_date ||
          agenda.startDate ||
          agenda.tanggal_mulai ||
          agenda.date ||
          agenda.tanggal;
        const rawEndDate = agenda.end_date || agenda.endDate || agenda.tanggal_selesai;

        if (rawStartDate) {
          const d = new Date(rawStartDate);
          if (!isNaN(d.getTime())) {
            startDate = d;
            dateText = d.getDate().toString().padStart(2, "0");
            const months = [
              "Jan",
              "Feb",
              "Mar",
              "Apr",
              "Mei",
              "Jun",
              "Jul",
              "Agt",
              "Sep",
              "Okt",
              "Nov",
              "Des",
            ];
            monthText = months[d.getMonth()];

            if (rawEndDate && rawEndDate !== rawStartDate) {
              const endD = new Date(rawEndDate);
              if (!isNaN(endD.getTime())) {
                endDate = endD;
                dateText = `${d
                  .getDate()
                  .toString()
                  .padStart(2, "0")}-${endD.getDate().toString().padStart(2, "0")}`;
                if (d.getMonth() !== endD.getMonth())
                  monthText = `${months[d.getMonth()]}-${months[endD.getMonth()]}`;
              }
            }
          } else {
            dateText = rawStartDate;
            monthText = "Agenda";
          }
        }

        const validColors = ["yellow", "red", "green", "blue"];
        let color = "blue";

        if (agenda.color && validColors.includes(agenda.color.toLowerCase())) {
          color = agenda.color.toLowerCase();
        } else {
          const type = (
            agenda.category ||
            agenda.type ||
            agenda.kategori ||
            ""
          ).toLowerCase();
          if (type.includes("akademik")) color = "yellow";
          else if (
            type.includes("guru") ||
            type.includes("staf") ||
            type.includes("pendidik")
          )
            color = "red";
          else if (type.includes("kegiatan") || type.includes("lomba")) color = "green";
        }
        return {
          id: agenda.id,
          date: dateText,
          month: monthText,
          title: agenda.title || "Agenda Tanpa Judul",
          time: agenda.time,
          loc: agenda.location,
          color: color,
          file: agenda.attachment || null,
          attachment: agenda.attachment || null,
          startDate: startDate,
          endDate: endDate || startDate,
        };
      });
    }
  } finally {
    isLoading.agendas = false;
    nextTick(observeElements);
  }
};

const fetchSchoolVideo = async () => {
  try {
    const res = await api.get("/api/public-school-video");
    if (res.data?.data) {
      schoolVideoUrl.value = res.data.data.url;
      schoolVideoTitle.value = res.data.data.title;
      schoolVideoDesc.value = res.data.data.description;
    }
  } finally {
    isLoading.video = false;
    nextTick(observeElements);
  }
};

const fetchPpdbInfo = async () => {
  try {
    const res = await api.get("/api/ppdb-info");
    if (res.data?.data) {
      Object.assign(ppdbInfo.value, res.data.data);
      if (res.data.data.faqs) faqs.value = res.data.data.faqs;
    }
  } finally {
    isLoading.ppdb = false;
    nextTick(observeElements);
  }
};

const fetchAlumniLocations = async () => {
  try {
    const res = await api.get("/api/public-map-locations");
    if (res.data?.data) {
      alumniLocations.value = res.data.data;
      let ttlAlumni = 0,
        ttlPTN = 0,
        ttlInstansi = 0;
      res.data.data.forEach((loc) => {
        let locPTN = 0,
          locInstansi = 0,
          locAlumni = parseInt(loc.total_alumni || loc.totalAlumni) || 0;
        if (loc.institutions) {
          let calc = 0;
          loc.institutions.forEach((inst) => {
            const count = parseInt(inst.alumni) || 0;
            calc += count;
            if (inst.type === "ptn") locPTN += count;
            else if (["instansi", "kedinasan"].includes(inst.type)) locInstansi += count;
          });
          if (locAlumni < calc) locAlumni = calc;
        }
        ttlAlumni += locAlumni;
        ttlPTN += locPTN;
        ttlInstansi += locInstansi;
      });
      alumniStatsTarget.value = { alumni: ttlAlumni, ptn: ttlPTN, instansi: ttlInstansi };
    }
  } finally {
    isLoading.map = false;
    nextTick(observeElements);
  }
};

// ANIMASI & INIT
const animateStats = () => {
  if (statsAnimationId) cancelAnimationFrame(statsAnimationId);
  const duration = 3000;
  let startTimestamp = null;
  const startValues = statsArray.value.map((stat) => (stat.isNumber ? stat.value : 0));

  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    const easeProgress = 1 - Math.pow(1 - progress, 4);

    statsArray.value.forEach((stat, i) => {
      if (stat.isNumber) {
        stat.value = Math.floor(
          startValues[i] + easeProgress * (stat.target - startValues[i])
        );
      }
    });

    if (progress < 1) {
      statsAnimationId = window.requestAnimationFrame(step);
    } else {
      statsArray.value.forEach((stat) => {
        if (stat.isNumber) stat.value = stat.target;
      });
    }
  };
  statsAnimationId = window.requestAnimationFrame(step);
};

const startTypewriter = () => {
  if (isTypewriterStarted.value && displayedTitle.value.length > 0) return;
  isTypewriterStarted.value = true;

  if (typewriterInterval) clearInterval(typewriterInterval);

  let i = 0;
  displayedTitle.value = "";

  typewriterInterval = setInterval(() => {
    if (i < fullTitle.value.length) {
      displayedTitle.value += fullTitle.value.charAt(i);
      i++;
    } else {
      clearInterval(typewriterInterval);
      showSubtitle.value = true;
      setTimeout(animateStats, 500);
    }
  }, 120);
};

const observeElements = () => {
  if (!scrollObserver) return;
  // Membaca DOM di frame berikutnya agar tidak mengganggu render awal
  requestAnimationFrame(() => {
    document.querySelectorAll(".fade-on-scroll:not([data-observed])").forEach((el) => {
      scrollObserver.observe(el);
      el.dataset.observed = "true";
    });
  });
};

onMounted(() => {
  const cachedHeader = localStorage.getItem("app_headerBeranda");
  if (cachedHeader) appearanceSettings.value.headerBeranda = cachedHeader;

  // 1. Ambil data prioritas/krusial terlebih dahulu
  fetchSettings();
  fetchSchoolStats();
  fetchPpdbInfo();

  // 2. Tunda pengambilan data sekunder agar UI bisa dirender tanpa stuttering
  setTimeout(() => {
    fetchPrograms();
    fetchAgendas();
    fetchGalleries();
    fetchSchoolVideo();
    fetchNewsAndAnnouncements();
    fetchAlumniLocations();
  }, 100);

  if (fullTitle.value && !isTypewriterStarted.value) startTypewriter();

  // 3. Setup Observer dengan performa lebih baik
  scrollObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          scrollObserver.unobserve(entry.target);
        }
      });
    },
    {
      rootMargin: "0px 0px -50px 0px",
      threshold: 0.05,
    }
  );

  observeElements();
});

onBeforeUnmount(() => {
  if (scrollObserver) scrollObserver.disconnect();
  if (typewriterInterval) clearInterval(typewriterInterval);
  if (statsAnimationId) cancelAnimationFrame(statsAnimationId);
});
</script>

<template>
  <div class="w-full overflow-hidden box-border">
    <HeroSection
      :appearanceSettings="appearanceSettings"
      :statsArray="statsArray"
      :displayedTitle="displayedTitle"
      :showSubtitle="showSubtitle"
      :slogan="slogan"
      @start-typewriter="startTypewriter"
    />

    <main class="px-6 bg-gray-100 dark:bg-gray-900 overflow-hidden">
      <div class="fade-on-scroll">
        <FeaturesSection :appearanceSettings="appearanceSettings" />
      </div>

      <div class="fade-on-scroll">
        <AlumniMapSection
          :alumniLocations="alumniLocations"
          :alumniStatsTarget="alumniStatsTarget"
          :isLoading="isLoading.map"
        />
      </div>

      <div class="fade-on-scroll">
        <ProgramsSection
          :appearanceSettings="appearanceSettings"
          :programs="programs"
          :isLoadingPrograms="isLoading.programs"
        />
      </div>

      <div class="fade-on-scroll">
        <NewsSection
          :recentNews="news"
          :announcements="announcements"
          :isLoadingNews="isLoading.news"
        />
      </div>

      <div class="fade-on-scroll">
        <VideoGallerySection
          v-if="!isLoading.video"
          :key="schoolVideoUrl"
          :appearanceSettings="appearanceSettings"
          :schoolVideoUrl="schoolVideoUrl"
          :schoolVideoTitle="schoolVideoTitle"
          :schoolVideoDesc="schoolVideoDesc"
          :isLoadingSchoolVideo="isLoading.video"
          :isVideoPlaying="isVideoPlaying"
          :galleriesByCategory="galleriesByCategory"
          :fourthGalleryImage="fourthGalleryImage"
          @play-video="isVideoPlaying = true"
        />
      </div>

      <div class="fade-on-scroll">
        <AgendaSection :agendas="agendas" :isLoadingAgendas="isLoading.agendas" />
      </div>

      <div class="fade-on-scroll">
        <PpdbFaqSection
          :appearanceSettings="appearanceSettings"
          :ppdbInfo="ppdbInfo"
          :faqs="faqs"
          :isLoading="isLoading.ppdb"
        />
      </div>
    </main>
  </div>
</template>

<style scoped>
/* Kelas khusus untuk menangani animasi scroll lewat GPU */
.fade-on-scroll {
  opacity: 0;
  transform: translateY(20px);
  will-change: transform, opacity;
  transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.6s ease-out;
}

.fade-on-scroll.is-visible {
  opacity: 1;
  transform: translateY(0);
}
</style>
