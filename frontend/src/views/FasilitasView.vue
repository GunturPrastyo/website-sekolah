<template>
  <div>
    <section class="relative z-20 px-0 dark:bg-slate-900 min-h-screen">
      <main class="w-full">
        <!-- HEADER TRANSITION -->
        <Transition
          mode="out-in"
          enter-active-class="transition-all duration-700 ease-out"
          enter-from-class="opacity-0 translate-y-8"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition-all duration-300 ease-in"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 -translate-y-8"
        >
          <div
            :key="currentCategory.id + '-header'"
            class="relative h-[350px] md:h-[400px] lg:h-[450px] overflow-hidden shadow-lg lg:rounded-b-2xl border-b border-gray-200 dark:border-slate-800"
          >
            <img
              :src="currentCategory.items[0].src"
              class="absolute inset-0 w-full h-full object-cover scale-105 animate-slow-zoom"
              alt="Fasilitas Header"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-slate-900/20"
            ></div>

            <div class="absolute bottom-0 left-0 w-full p-2 md:p-14 py-8 text-white">
              <div class="container mx-auto max-w-7xl">
                <span
                  class="inline-block px-4 py-1.5 mb-4 text-md font-extrabold text-blue-900 bg-white backdrop-blur-sm dark:bg-blue-900/40 dark:text-blue-300 rounded-full shadow-sm"
                  style="font-family: 'Kalam', cursive"
                >
                  Fasilitas Unggulan
                </span>

                <h1
                  class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-3 md:mb-4 drop-shadow-md"
                >
                  {{ currentCategory.name }}
                </h1>

                <p
                  class="text-blue-50 text-sm md:text-base max-w-2xl leading-relaxed drop-shadow"
                >
                  Jelajahi berbagai fasilitas modern yang mendukung pengalaman belajar
                  siswa secara optimal di lingkungan SMAN 1 Nogosari.
                </p>
              </div>
            </div>
          </div>
        </Transition>

        <!-- CONTENT AREA -->
        <div
          class="container mx-auto max-w-full px-2 sm:px-8 py-6 lg:py-8 grid lg:grid-cols-12 gap-10 items-start"
        >
          <!-- MAIN ARTICLE (Transitions on change) -->
          <div class="lg:col-span-8">
            <Transition
              mode="out-in"
              enter-active-class="transition-all duration-500 ease-out delay-100"
              enter-from-class="opacity-0 translate-y-8"
              enter-to-class="opacity-100 translate-y-0"
              leave-active-class="transition-all duration-200 ease-in"
              leave-from-class="opacity-100 translate-y-0"
              leave-to-class="opacity-0 -translate-y-4"
            >
              <div
                :key="currentCategory.id + '-content'"
                class="bg-white dark:bg-slate-800 p-6 md:p-8 lg:p-10 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm space-y-8"
              >
                <!-- Konten Pengantar -->
                <div class="space-y-6">
                  <p
                    class="text-base md:text-lg text-gray-700 dark:text-gray-300 leading-relaxed text-justify"
                  >
                    {{ currentCategory.description }}
                  </p>

                  <blockquote
                    class="relative border-l-4 border-blue-400 bg-blue-50/50 dark:bg-slate-800/50 p-6 sm:p-8 rounded-r-xl italic text-gray-600 dark:text-gray-400 shadow-sm overflow-hidden"
                  >
                    <div
                      class="absolute -right-4 -top-4 text-blue-100 dark:text-slate-700/50 opacity-50 transform rotate-180 pointer-events-none"
                    >
                      <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path
                          d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"
                        />
                      </svg>
                    </div>
                    <p
                      class="relative z-10 text-base md:text-lg font-medium leading-relaxed"
                    >
                      "Fasilitas yang baik bukan hanya pelengkap, tetapi fondasi utama
                      dalam menciptakan pengalaman belajar yang maksimal bagi setiap
                      siswa."
                    </p>
                  </blockquote>
                </div>

                <!-- List Fasilitas -->
                <div class="space-y-12">
                  <div
                    v-for="(item, index) in currentCategory.items"
                    :key="item.title + index"
                    class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out space-y-5"
                  >
                    <h3
                      class="text-lg md:text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3"
                    >
                      <span
                        class="text-gray-900 dark:text-white text-lg md:text-xl font-bold"
                      >
                        {{ index + 1 }}.
                      </span>
                      {{ item.title }}
                    </h3>

                    <!-- Galeri Grid (Multi-image) -->
                    <div
                      v-if="item.images && item.images.length > 1"
                      class="grid grid-cols-2 gap-3 md:gap-4 my-6"
                    >
                      <template
                        v-for="(img, imgIdx) in item.images.slice(0, 4)"
                        :key="imgIdx"
                      >
                        <div
                          class="relative overflow-hidden rounded-xl shadow-sm h-48 md:h-64 cursor-pointer group bg-gray-100 dark:bg-slate-700"
                          :class="{
                            'col-span-2 h-56 md:h-80':
                              item.images.length === 3 && imgIdx === 0,
                          }"
                          @click="openGallery(item.images, imgIdx)"
                        >
                          <img
                            :src="img"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                          />

                          <!-- Hover Overlay for Zoom (Kecuali gambar ke-4 jika lebih dr 4) -->
                          <div
                            class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-500 flex items-center justify-center"
                          >
                            <div
                              v-if="!(imgIdx === 3 && item.images.length > 4)"
                              class="w-12 h-12 rounded-full bg-white/30 backdrop-blur-md flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transform scale-50 group-hover:scale-100 transition-all duration-300 shadow-lg"
                            >
                              <PhArrowsOut class="w-5 h-5" />
                            </div>
                          </div>

                          <!-- Overlay Spesial Gambar Ke-4 -->
                          <div
                            v-if="imgIdx === 3 && item.images.length > 4"
                            class="absolute inset-0 bg-blue-900/80 flex flex-col items-center justify-center text-white backdrop-blur-[2px] transition-colors group-hover:bg-blue-800"
                          >
                            <PhImage class="w-8 h-8 mb-2 opacity-80" />
                            <span class="text-3xl md:text-4xl font-bold"
                              >+{{ item.images.length - 4 }}</span
                            >
                            <span class="text-sm md:text-base font-medium mt-1"
                              >Lihat Galeri</span
                            >
                          </div>
                        </div>
                      </template>
                    </div>

                    <!-- Single Image -->
                    <div
                      v-else
                      class="relative overflow-hidden rounded-2xl shadow-sm my-6 cursor-pointer group bg-gray-100 dark:bg-slate-700"
                      @click="openGallery(item.images || [item.src], 0)"
                    >
                      <img
                        :src="item.images ? item.images[0] : item.src"
                        class="w-full h-[250px] sm:h-[350px] md:h-[450px] object-cover group-hover:scale-105 transition duration-700"
                      />
                      <div
                        class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-500 flex items-center justify-center"
                      >
                        <div
                          class="w-16 h-16 rounded-full bg-white/30 backdrop-blur-md flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transform scale-50 group-hover:scale-100 transition-all duration-500 shadow-xl"
                        >
                          <PhArrowsOut class="w-6 h-6" />
                        </div>
                      </div>
                    </div>

                    <p
                      class="text-gray-600 dark:text-gray-300 leading-relaxed text-justify text-base md:text-lg"
                    >
                      {{ item.text }} Kami memastikan bahwa setiap detail dari sarana ini
                      dirawat dengan baik agar siswa dapat memanfaatkannya secara
                      maksimal.
                    </p>
                  </div>
                </div>
              </div>
            </Transition>
          </div>

          <!-- SIDEBAR NAVIGASI (STATIS) -->
          <div class="lg:col-span-4 space-y-8 lg:sticky lg:top-28 z-10">
            <div
              class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col"
            >
              <div
                class="border-blue-600 dark:border-slate-600 shrink-0 border-l-4 pl-4 rounded-sm mb-6"
              >
                <h4 class="text-lg font-bold text-gray-900 dark:text-white">
                  Kategori Fasilitas
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  Pilih kategori untuk melihat fasilitas.
                </p>
              </div>

              <div
                class="space-y-3 mt-2 max-h-[600px] overflow-y-auto pr-2 pb-4 custom-scrollbar"
              >
                <div
                  v-for="other in facilityCategories"
                  :key="other.id"
                  @click="changeCategory(other.id)"
                  class="group cursor-pointer flex items-start gap-4 p-3 rounded-2xl transition-all border"
                  :class="
                    other.id === activeCategory
                      ? 'bg-blue-50 border-blue-200 dark:bg-blue-900/30 dark:border-blue-800'
                      : 'bg-transparent border-transparent hover:bg-gray-50 dark:hover:bg-slate-700/50 hover:border-gray-100 dark:hover:border-slate-600'
                  "
                >
                  <div
                    class="relative overflow-hidden rounded-xl w-20 h-20 shrink-0 shadow-sm"
                  >
                    <img
                      :src="other.items[0].src"
                      class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                    />
                    <div
                      class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[10px] text-center py-0.5 backdrop-blur-sm font-medium"
                    >
                      {{ other.items.length }} Bagian
                    </div>
                  </div>
                  <div class="flex-1 py-1">
                    <h5
                      class="text-sm font-bold transition-colors line-clamp-1"
                      :class="
                        other.id === activeCategory
                          ? 'text-blue-700 dark:text-blue-400'
                          : 'text-gray-800 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400'
                      "
                    >
                      {{ other.name }}
                    </h5>
                    <p
                      class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2 leading-relaxed"
                    >
                      {{ other.description }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </section>

    <!-- Lightbox Gallery Modal -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isGalleryOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-sm"
        @click="closeGallery"
      >
        <!-- Close Button -->
        <button
          @click.stop="closeGallery"
          class="absolute top-4 right-4 md:top-6 md:right-6 text-white/70 hover:text-white transition-colors z-50 p-2"
        >
          <PhX class="w-8 h-8" />
        </button>

        <!-- Navigation Buttons -->
        <button
          v-if="currentGalleryImages.length > 1"
          @click.stop="prevImage"
          class="absolute left-2 md:left-10 text-white/70 hover:text-white transition-colors z-50 p-2 md:p-4 hover:bg-white/10 rounded-full"
        >
          <svg
            class="w-8 h-8 md:w-10 md:h-10"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 19l-7-7 7-7"
            ></path>
          </svg>
        </button>
        <button
          v-if="currentGalleryImages.length > 1"
          @click.stop="nextImage"
          class="absolute right-2 md:right-10 text-white/70 hover:text-white transition-colors z-50 p-2 md:p-4 hover:bg-white/10 rounded-full"
        >
          <svg
            class="w-8 h-8 md:w-10 md:h-10"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5l7 7-7 7"
            ></path>
          </svg>
        </button>

        <!-- Image Container -->
        <div
          class="relative w-full max-w-5xl max-h-[90vh] px-12 md:px-24 flex flex-col items-center justify-center"
          @click.stop
        >
          <img
            :src="currentGalleryImages[currentImageIndex]"
            class="max-w-full max-h-[85vh] object-contain rounded-md shadow-2xl transition-transform duration-300"
          />
          <div
            class="absolute bottom-[-30px] md:bottom-[-40px] text-white/80 text-sm md:text-base font-medium bg-black/50 px-3 py-1 rounded-full"
          >
            {{ currentImageIndex + 1 }} / {{ currentGalleryImages.length }}
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch, nextTick } from "vue";
import {
  PhArrowsOut,
  PhImage,
  PhCheckCircle,
  PhArrowRight,
  PhX,
} from "@phosphor-icons/vue";

const activeCategory = ref("kelas");

/* State Modal Gallery Lightbox */
const isGalleryOpen = ref(false);
const currentGalleryImages = ref([]);
const currentImageIndex = ref(0);

const openGallery = (images, index = 0) => {
  currentGalleryImages.value = images;
  currentImageIndex.value = index;
  isGalleryOpen.value = true;
  document.body.style.overflow = "hidden"; // Cegah background bisa discroll
};

const closeGallery = () => {
  isGalleryOpen.value = false;
  document.body.style.overflow = "";
};

const nextImage = () => {
  currentImageIndex.value =
    currentImageIndex.value < currentGalleryImages.value.length - 1
      ? currentImageIndex.value + 1
      : 0;
};

const prevImage = () => {
  currentImageIndex.value =
    currentImageIndex.value > 0
      ? currentImageIndex.value - 1
      : currentGalleryImages.value.length - 1;
};
/* End State Gallery */

const changeCategory = (id) => {
  activeCategory.value = id;
  // Otomatis scroll ke atas jika di mobile / tablet (lebar layar < 1024px)
  if (window.innerWidth < 1024) {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
};

let observer = null;
const initObserver = () => {
  if (observer) observer.disconnect();

  observer = new IntersectionObserver(
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
};

onMounted(() => {
  initObserver();
});

onBeforeUnmount(() => {
});

watch(activeCategory, async () => {
  await nextTick();
  // Jeda untuk menunggu transisi out-in selesai
  setTimeout(() => {
    initObserver();
  }, 600);
});

const facilityCategories = ref([
  {
    id: "kelas",
    name: "Ruang Kelas",
    description:
      "Ruang kelas dirancang untuk menciptakan suasana belajar yang nyaman, interaktif, dan mendukung konsentrasi siswa dalam mengikuti pembelajaran.",
    items: [
      {
        src: "https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800",
        images: [
          "https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800",
          "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800",
          "https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=800",
          "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800",
          "https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=800",
          "https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800",
        ],
        title: "Ruang Kelas Modern",
        text:
          "Setiap ruang kelas dilengkapi dengan fasilitas modern seperti proyektor, papan interaktif, dan ventilasi yang baik. Ruangan kami didesain khusus agar pencahayaan dari luar masuk dengan sempurna.",
      },
      {
        src: "https://images.unsplash.com/photo-1594434533439-04c3a735d359?q=80&w=800",
        title: "Suasana Interaktif",
        text: "Lingkungan belajar mendorong siswa aktif berdiskusi dan berkolaborasi.",
      },
    ],
  },
  {
    id: "lab",
    name: "Laboratorium",
    description:
      "Laboratorium menjadi sarana penting dalam mendukung pembelajaran berbasis praktik dan eksperimen.",
    items: [
      {
        src: "https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800",
        images: [
          "https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800",
          "https://images.unsplash.com/photo-1628243422323-b5039a8a4010?q=80&w=800",
          "https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=800",
        ],
        title: "Lab Sains",
        text:
          "Fasilitas lengkap untuk eksperimen fisika, kimia, dan biologi dengan standar keamanan tertinggi.",
      },
      {
        src: "https://images.unsplash.com/photo-1628243422323-b5039a8a4010?q=80&w=800",
        title: "Lab Komputer",
        text: "Komputer modern dengan koneksi internet cepat.",
      },
    ],
  },
  {
    id: "perpus",
    name: "Perpustakaan",
    description:
      "Perpustakaan menjadi pusat literasi dan sumber belajar bagi seluruh siswa.",
    items: [
      {
        src: "https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=800",
        title: "Ruang Baca Nyaman",
        text: "Lingkungan tenang dengan koleksi buku lengkap.",
      },
    ],
  },
  {
    id: "olahraga",
    name: "Sarana Olahraga",
    description: "Fasilitas olahraga mendukung pengembangan fisik dan bakat siswa.",
    items: [
      {
        src: "https://images.unsplash.com/photo-1575361204480-aadea2503aa4?q=80&w=800",
        title: "Lapangan Olahraga",
        text: "Lapangan luas dan terawat untuk berbagai kegiatan olahraga.",
      },
    ],
  },
  {
    id: "pendukung",
    name: "Fasilitas Pendukung",
    description: "Fasilitas tambahan untuk menunjang kenyamanan siswa selama di sekolah.",
    items: [
      {
        src: "https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=800",
        title: "Kantin Sehat",
        text: "Menyediakan makanan sehat dan higienis.",
      },
    ],
  },
]);

const currentCategory = computed(() => {
  return (
    facilityCategories.value.find((c) => c.id === activeCategory.value) ||
    facilityCategories.value[0]
  );
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");

/* Custom Scrollbar for Sidebar Nav */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #e5e7eb;
  border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #475569;
}

@keyframes slow-zoom {
  0% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1.15);
  }
}
.animate-slow-zoom {
  animation: slow-zoom 20s alternate infinite ease-in-out;
}
</style>
