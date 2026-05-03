<script setup>
import { ref, computed, onMounted } from "vue";
import "leaflet/dist/leaflet.css";
import { LMap, LTileLayer, LMarker } from "@vue-leaflet/vue-leaflet";
import L from "leaflet";
import PageHeader from "@/components/PageHeader.vue";
import {
  PhMapPin,
  PhBriefcase,
  PhGraduationCap,
  PhCaretDown,
  PhMagnifyingGlass,
  PhUsers,
  PhX,
} from "@phosphor-icons/vue";

// --- STATE MANAGEMENT ---
const activeYear = ref("semua");
const activeField = ref("semua");
const searchQuery = ref("");
const isModalOpen = ref(false);
const selectedAlumnus = ref(null);
const mapZoom = ref(2.5);
const mapCenter = ref([20, 10]); // Center the map view initially

// --- DUMMY DATA ---
const alumniData = ref([
  {
    id: 1,
    name: "Dr. Rina Wulandari",
    year: 2010,
    field: "Kesehatan",
    role: "Dokter Spesialis Jantung",
    location: "Jakarta, Indonesia",
    latlng: [-6.2088, 106.8456],
    image: "https://images.unsplash.com/photo-1559839734-2b71ea197ec2?q=80&w=400",
    testimonial:
      "Pendidikan karakter dan dasar sains yang kuat di SMAN 1 menjadi fondasi utama saya dalam menempuh pendidikan kedokteran. Jangan pernah takut bermimpi besar!",
  },
  {
    id: 2,
    name: "Budi Prasetyo, S.T.",
    year: 2012,
    field: "Teknologi",
    role: "Software Engineer di Gojek",
    location: "Jakarta, Indonesia",
    latlng: [-6.23, 106.86], // Slightly offset for visibility
    image: "https://images.unsplash.com/photo-1557862921-37829c790f19?q=80&w=400",
    testimonial:
      "Klub Robotika di sekolah adalah awal mula kecintaan saya pada teknologi. Manfaatkan semua fasilitas yang ada untuk mengasah skill kalian.",
  },
  {
    id: 3,
    name: "Ahmad Fauzi, M.Eng.",
    year: 2011,
    field: "Teknik",
    role: "Civil Engineer di Waskita Karya",
    location: "Surabaya, Indonesia",
    latlng: [-7.2575, 112.7521],
    image: "https://images.unsplash.com/photo-1611267294114-c31820269b1e?q=80&w=400",
    testimonial:
      "Kemampuan problem-solving yang diajarkan para guru sangat berguna di dunia kerja. Teruslah belajar dan jangan mudah menyerah.",
  },
  {
    id: 4,
    name: "Siti Nurhaliza, Ph.D",
    year: 2009,
    field: "Akademisi",
    role: "Dosen & Peneliti di UGM",
    location: "Yogyakarta, Indonesia",
    latlng: [-7.7956, 110.3695],
    image: "https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=400",
    testimonial:
      "Kegiatan Karya Ilmiah Remaja (KIR) membuka wawasan saya tentang dunia penelitian. Saya bangga menjadi alumni SMAN 1 Nogosari.",
  },
  {
    id: 5,
    name: "David Lee",
    year: 2015,
    field: "Startup",
    role: "Founder & CEO di EduTech Pte. Ltd.",
    location: "Singapura",
    latlng: [1.3521, 103.8198],
    image: "https://images.unsplash.com/photo-1568602471122-7832951cc4c5?q=80&w=400",
    testimonial:
      "Jiwa kepemimpinan saya terbentuk melalui OSIS. Pengalaman organisasi di sekolah adalah modal yang sangat berharga.",
  },
  {
    id: 6,
    name: "Maria Garcia",
    year: 2013,
    field: "Seni",
    role: "Animator di Pixar Animation Studios",
    location: "California, USA",
    latlng: [37.7749, -122.4194],
    image: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400",
    testimonial:
      "Ekstrakurikuler seni rupa menjadi tempat saya berekspresi. Terima kasih kepada guru-guru yang selalu mendukung passion saya.",
  },
]);

// --- COMPUTED PROPERTIES ---
const years = computed(() =>
  ["semua", ...new Set(alumniData.value.map((a) => a.year))].sort((a, b) =>
    b === "semua" ? -1 : a === "semua" ? 1 : b - a
  )
);
const fields = computed(() => [
  "semua",
  ...new Set(alumniData.value.map((a) => a.field)),
]);

const filteredAlumni = computed(() => {
  return alumniData.value.filter((alumnus) => {
    const yearMatch = activeYear.value === "semua" || alumnus.year == activeYear.value;
    const fieldMatch =
      activeField.value === "semua" || alumnus.field === activeField.value;
    const searchMatch =
      !searchQuery.value ||
      alumnus.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      alumnus.role.toLowerCase().includes(searchQuery.value.toLowerCase());
    return yearMatch && fieldMatch && searchMatch;
  });
});

// --- METHODS ---
const getPinColor = (field) => {
  switch (field) {
    case "Kesehatan":
      return "#ef4444"; // red-500
    case "Teknologi":
      return "#3b82f6"; // blue-500
    case "Teknik":
      return "#f97316"; // orange-500
    case "Akademisi":
      return "#8b5cf6"; // violet-500
    case "Startup":
      return "#14b8a6"; // teal-500
    case "Seni":
      return "#ec4899"; // pink-500
    default:
      return "#eab308"; // yellow-500
  }
};

const createPulseIcon = (field) => {
  const color = getPinColor(field);
  return L.divIcon({
    html: `
      <div class="pulse-ring" style="border-color: ${color};"></div>
      <div class="pulse-dot" style="background-color: ${color};"></div>
    `,
    className: "css-pulse-icon",
    iconSize: [20, 20],
    iconAnchor: [10, 10],
  });
};

// --- METHODS ---
const openModal = (alumnus) => {
  selectedAlumnus.value = alumnus;
  isModalOpen.value = true;
  document.body.style.overflow = "hidden";
};

const closeModal = () => {
  isModalOpen.value = false;
  document.body.style.overflow = "";
};

// --- LIFECYCLE HOOKS ---
onMounted(() => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("opacity-100", "translate-y-0");
          entry.target.classList.remove("opacity-0", "translate-y-10");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 }
  );

  document.querySelectorAll(".fade-on-scroll").forEach((el) => {
    observer.observe(el);
  });
});
</script>

<template>
  <div>
    <PageHeader
      badge="Jejak Lulusan"
      title="Peta Persebaran Alumni"
      description="Lihat bagaimana lulusan SMAN 1 Nogosari berkiprah dan memberikan kontribusi di berbagai belahan dunia, baik di institusi pendidikan ternama maupun dunia profesional."
      bgImage="https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1600&auto=format&fit=crop"
    />

    <section class="py-12 md:py-16 bg-gray-50 dark:bg-slate-900">
      <div class="container mx-auto max-w-7xl px-4">
        <!-- Filter Section -->
        <div
          class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out p-5 md:p-6 mb-10 bg-white dark:bg-slate-800 rounded-xl shadow-md border border-gray-100 dark:border-slate-700 flex flex-col md:flex-row gap-4"
        >
          <div class="relative flex-1">
            <PhMagnifyingGlass
              class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
            />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari nama atau profesi alumni..."
              class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-slate-700 rounded-lg text-sm shadow-inner focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all dark:text-white placeholder-gray-400 focus:outline-none"
            />
          </div>
          <div class="flex gap-4">
            <div class="relative flex-1 md:flex-none">
              <select
                v-model="activeYear"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm text-sm appearance-none cursor-pointer"
              >
                <option v-for="year in years" :key="year" :value="year">
                  {{ year === "semua" ? "Semua Angkatan" : `Angkatan ${year}` }}
                </option>
              </select>
              <PhCaretDown
                class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
              />
            </div>
            <div class="relative flex-1 md:flex-none">
              <select
                v-model="activeField"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm text-sm appearance-none cursor-pointer"
              >
                <option v-for="field in fields" :key="field" :value="field">
                  {{ field === "semua" ? "Semua Bidang" : field }}
                </option>
              </select>
              <PhCaretDown
                class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
              />
            </div>
          </div>
        </div>

        <!-- Map View (Desktop) -->
        <div
          class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-200 ease-out hidden lg:block relative w-full aspect-[2/1] bg-blue-950 dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden border-4 border-white dark:border-slate-800"
        >
          <l-map
            v-model:zoom="mapZoom"
            :center="mapCenter"
            :use-global-leaflet="false"
            :options="{ zoomControl: false }"
            class="w-full h-full"
          >
            <l-tile-layer
              url="https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png"
              layer-type="base"
              name="OpenStreetMap"
            ></l-tile-layer>
            <l-marker
              v-for="alumnus in filteredAlumni"
              :key="alumnus.id"
              :lat-lng="alumnus.latlng"
              :icon="createPulseIcon(alumnus.field)"
              @click="openModal(alumnus)"
            />
          </l-map>
        </div>

        <!-- Card List View (Mobile) -->
        <div class="block lg:hidden space-y-6">
          <div
            v-for="alumnus in filteredAlumni"
            :key="alumnus.id"
            @click="openModal(alumnus)"
            class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out bg-white dark:bg-slate-800 p-4 rounded-xl shadow-md border border-gray-100 dark:border-slate-700 flex items-center gap-4"
          >
            <img
              :src="alumnus.image"
              class="w-16 h-16 rounded-full object-cover border-2 border-white dark:border-slate-700 shadow-sm"
            />
            <div class="flex-1">
              <h4 class="font-bold text-gray-900 dark:text-white">
                {{ alumnus.name }}
              </h4>
              <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">
                {{ alumnus.role }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Angkatan {{ alumnus.year }} • {{ alumnus.location }}
              </p>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div
          v-if="filteredAlumni.length === 0"
          class="text-center py-20 fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out"
        >
          <PhUsers class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" />
          <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
            Alumni Tidak Ditemukan
          </h3>
          <p class="text-gray-500 dark:text-gray-400 mt-1">
            Coba ubah filter atau kata kunci pencarian Anda.
          </p>
        </div>
      </div>
    </section>

    <!-- Modal Detail Alumni -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isModalOpen"
        @click="closeModal"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
      >
        <div
          @click.stop
          class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto flex flex-col"
        >
          <div
            class="p-5 flex justify-between items-center border-b border-gray-100 dark:border-slate-700 sticky top-0 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm z-10"
          >
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Profil Alumni</h3>
            <button
              @click="closeModal"
              class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
            >
              <PhX class="w-5 h-5 text-gray-600 dark:text-gray-300" />
            </button>
          </div>
          <div v-if="selectedAlumnus" class="flex flex-col">
            <div class="p-6 text-center">
              <img
                :src="selectedAlumnus.image"
                class="w-28 h-28 rounded-full object-cover mx-auto mb-4 border-4 border-white dark:border-slate-700 shadow-lg"
              />
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ selectedAlumnus.name }}
              </h2>
              <p class="text-blue-600 dark:text-blue-400 font-semibold">
                {{ selectedAlumnus.role }}
              </p>
              <div
                class="mt-2 flex justify-center items-center gap-4 text-sm text-gray-500 dark:text-gray-400"
              >
                <span class="flex items-center gap-1"
                  ><PhGraduationCap /> Angkatan {{ selectedAlumnus.year }}</span
                >
                <span class="flex items-center gap-1"
                  ><PhMapPin /> {{ selectedAlumnus.location }}</span
                >
              </div>
            </div>
            <div
              class="bg-gray-50 dark:bg-slate-900/50 p-6 border-t border-gray-100 dark:border-slate-700"
            >
              <h4 class="font-bold text-gray-900 dark:text-white mb-2">Testimoni:</h4>
              <blockquote
                class="italic text-gray-600 dark:text-gray-300 border-l-2 border-blue-500 pl-4"
              >
                "{{ selectedAlumnus.testimonial }}"
              </blockquote>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");

.css-pulse-icon {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.pulse-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  position: relative;
  z-index: 2;
  border: 2px solid white;
}

.pulse-ring {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 3px solid; /* Color will be set by style */
  position: absolute;
  z-index: 1;
  animation: pulse 2s ease-out infinite;
  opacity: 0;
}

@keyframes pulse {
  0% {
    transform: scale(0.1);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: scale(1.2);
    opacity: 0;
  }
}
</style>
