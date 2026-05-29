<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from "vue";
import {
  PhPlay,
  PhImage,
  PhHeart,
  PhDownloadSimple,
  PhImageBroken,
  PhX,
} from "@phosphor-icons/vue";
import PageHeader from "@/components/PageHeader.vue";

const activeCategory = ref("semua");
const activeTab = ref("terbaru"); // Status tab: 'terbaru' atau 'terpopuler'

// Kategori dinamis
const categories = ref([
  {
    id: "semua",
    name: "Semua Foto",
    count: 0,
    image:
      "https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800&auto=format&fit=crop",
  },
]);

const galleryList = ref([]);
const isLoadingGalleries = ref(true);

const schoolVideoUrl = ref("");

const fetchSchoolVideo = async () => {
  try {
    const apiUrl = import.meta.env.VITE_API_URL || "http://localhost:8000/api";
    const response = await fetch(`${apiUrl}/public-school-video`);
    const result = await response.json();
    if (result.data && result.data.url) {
      schoolVideoUrl.value = result.data.url;
    }
  } catch (error) {
    console.error("Gagal mengambil data video profil:", error);
  }
};

const fetchGalleries = async () => {
  isLoadingGalleries.value = true;
  try {
    const apiUrl = import.meta.env.VITE_API_URL || "http://localhost:8000/api";
    const response = await fetch(`${apiUrl}/public-galleries`);
    const result = await response.json();

    const categoryMap = {};

    galleryList.value = result.data.map((item) => {
      const catLower = item.category
        ? item.category.toLowerCase().replace(/\s+/g, "-")
        : "lainnya";
      const catName = item.category || "Lainnya";

      if (!categoryMap[catLower]) {
        categoryMap[catLower] = {
          id: catLower,
          name: catName,
          count: 0,
          image: item.image, // Menggunakan gambar pertama dari kategori ini sebagai cover
        };
      }
      categoryMap[catLower].count++;

      return {
        id: item.id,
        title: item.title,
        category: catLower,
        image: item.image,
        likes: item.likes || 0,
        liked: false,
      };
    });

    categories.value[0].count = galleryList.value.length;

    // Convert map to array and add to categories
    for (const key in categoryMap) {
      categories.value.push(categoryMap[key]);
    }
  } catch (error) {
    console.error("Gagal mengambil data galeri:", error);
  } finally {
    isLoadingGalleries.value = false;
  }
};

const toggleLike = (item) => {
  if (item.liked) {
    item.likes--;
    item.liked = false;
  } else {
    item.likes++;
    item.liked = true;
  }
};

const filteredGallery = computed(() => {
  let result = galleryList.value;

  // 1. Filter Kategori
  if (activeCategory.value !== "semua") {
    result = result.filter((item) => item.category === activeCategory.value);
  }

  // 2. Sort berdasarkan Tab (Terpopuler vs Terbaru)
  if (activeTab.value === "terpopuler") {
    result = [...result].sort((a, b) => b.likes - a.likes);
  } else {
    result = [...result].sort((a, b) => b.id - a.id); // Asumsi ID lebih besar = lebih baru
  }

  return result;
});

// Ambil thumbnail dinamis jika URL adalah YouTube
const videoThumbnail = computed(() => {
  if (!schoolVideoUrl.value)
    return "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1600&auto=format&fit=crop";
  const url = schoolVideoUrl.value;
  const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
  const match = url.match(regExp);
  if (match && match[2].length === 11) {
    return `https://img.youtube.com/vi/${match[2]}/maxresdefault.jpg`;
  }
  return "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1600&auto=format&fit=crop";
});

// --- INFINITE SCROLL STATE ---
const itemsPerPage = 8;
const visibleCount = ref(itemsPerPage);
const isLoadingMore = ref(false);
const loadMoreSentinel = ref(null);

const displayedGallery = computed(() => {
  return filteredGallery.value.slice(0, visibleCount.value);
});

const hasMore = computed(() => {
  return visibleCount.value < filteredGallery.value.length;
});

const loadMore = () => {
  if (isLoadingMore.value || !hasMore.value) return;
  isLoadingMore.value = true;
  setTimeout(() => {
    visibleCount.value += itemsPerPage;
    isLoadingMore.value = false;
  }, 500); // Jeda simulasi loading halus
};

watch([activeCategory, activeTab], () => {
  visibleCount.value = itemsPerPage;
});

let observer = null;
onMounted(() => {
  fetchSchoolVideo();
  fetchGalleries().then(() => {
    observer = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting && hasMore.value) {
          loadMore();
        }
      },
      { rootMargin: "0px 0px 150px 0px" }
    ); // Pemicu aktif 150px sebelum mentok ke bawah

    if (loadMoreSentinel.value) observer.observe(loadMoreSentinel.value);
  });
});

onBeforeUnmount(() => {
  if (observer) observer.disconnect();
});

// Lightbox Modal State
const isModalOpen = ref(false);
const currentImage = ref(null);
const currentIndex = ref(0);

const openModal = (index) => {
  currentIndex.value = index;
  currentImage.value = filteredGallery.value[index];
  isModalOpen.value = true;
  document.body.style.overflow = "hidden"; // Prevent scrolling
};

const closeModal = () => {
  isModalOpen.value = false;
  setTimeout(() => {
    currentImage.value = null;
  }, 300); // Tunggu animasi selesai
  document.body.style.overflow = ""; // Restore scrolling
};

const nextImage = () => {
  currentIndex.value = (currentIndex.value + 1) % filteredGallery.value.length;
  currentImage.value = filteredGallery.value[currentIndex.value];
};

const prevImage = () => {
  currentIndex.value =
    (currentIndex.value - 1 + filteredGallery.value.length) %
    filteredGallery.value.length;
  currentImage.value = filteredGallery.value[currentIndex.value];
};

// Tracking loading state untuk skeleton
const imageLoaded = ref({});
const onImageLoad = (id) => {
  imageLoaded.value[id] = true;
};
</script>

<template>
  <div>
    <PageHeader
      badge="Koleksi Visual"
      title="Galeri Kegiatan"
      description="Merekam jejak langkah, fasilitas, dan berbagai momen tak terlupakan di lingkungan SMAN 1 Nogosari."
      bgImage="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1600&auto=format&fit=crop"
    />

    <!-- Gallery Section -->
    <section class="py-4 md:py-12 px-0 md:px-6 bg-gray-50 dark:bg-slate-900 min-h-screen">
      <div class="container mx-auto max-w-full">
        <!-- Video Profil Section -->
        <a
          v-if="schoolVideoUrl"
          :href="schoolVideoUrl"
          target="_blank"
          class="mb-4 md:mb-6 relative rounded-none sm:rounded-xl overflow-hidden shadow-xl aspect-[4/3] sm:aspect-video md:aspect-[21/9] group cursor-pointer w-full block"
        >
          <img
            :src="videoThumbnail"
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
            alt="Video Profil Sekolah"
          />
          <div
            class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors"
          ></div>

          <!-- Play Button Center -->
          <div class="absolute inset-0 flex items-center justify-center z-20">
            <div
              class="w-16 h-16 md:w-20 md:h-20 bg-blue-600/90 rounded-full flex items-center justify-center text-white backdrop-blur-sm group-hover:bg-blue-500 group-hover:scale-110 transition-all shadow-[0_0_30px_rgba(37,99,235,0.6)]"
            >
              <PhPlay class="w-8 h-8 md:w-10 md:h-10 ml-1" weight="fill" />
            </div>
          </div>

          <div
            class="absolute bottom-0 left-0 p-5 md:p-8 w-full bg-gradient-to-t from-blue-950/90 via-blue-950/40 to-transparent z-10"
          >
            <span
              class="inline-block px-3 py-1 mb-3 text-sm font-semibold text-blue-900 bg-blue-100 rounded-full"
              style="font-family: 'Kalam', cursive"
              >Video Profil</span
            >
            <h3
              class="text-xl md:text-3xl font-bold text-white mb-2 group-hover:text-blue-200 transition-colors"
            >
              Company Profile SMAN 1 Nogosari 2026
            </h3>
          </div>
        </a>

        <!-- Skeleton Video Profil -->
        <div
          v-else
          class="mb-4 md:mb-6 relative rounded-none sm:rounded-xl overflow-hidden shadow-xl aspect-[4/3] sm:aspect-video md:aspect-[21/9] w-full block bg-gray-200 dark:bg-slate-800 animate-pulse flex items-center justify-center"
        >
          <div class="flex flex-col items-center">
            <PhPlay
              class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-2"
              weight="fill"
            />
            <span class="text-gray-500 dark:text-gray-400 font-medium"
              >Memuat Video...</span
            >
          </div>
        </div>

        <!-- Category Cards (Flexible Wrap untuk Ganjil/Genap) -->
        <div class="flex flex-wrap gap-2 md:gap-4 mb-4 md:mb-6 px-0 md:px-0">
          <div
            v-for="cat in categories"
            :key="cat.id"
            @click="activeCategory = cat.id"
            class="flex-grow basis-[120px] sm:basis-[180px] lg:basis-[200px] max-w-full relative rounded-lg md:rounded-xl overflow-hidden h-24 md:h-36 cursor-pointer group shadow-sm hover:shadow-md transition-all duration-300 border-2"
            :class="[
              activeCategory === cat.id
                ? 'border-blue-500 ring-4 ring-blue-500/20 dark:ring-blue-500/30'
                : 'border-transparent',
            ]"
          >
            <img
              :src="cat.image"
              class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
              loading="lazy"
            />
            <div
              class="absolute inset-0 bg-black/50 group-hover:bg-black/40 transition-colors duration-300"
            ></div>
            <div
              class="absolute inset-0 flex flex-col items-center justify-center text-white p-4 text-center"
            >
              <span class="font-bold text-sm md:text-lg drop-shadow-md">{{
                cat.name
              }}</span>
              <span
                class="text-[10px] md:text-xs font-medium opacity-90 mt-1 bg-white/20 px-2 py-0.5 rounded-full backdrop-blur-sm"
                >{{ cat.count }} Foto</span
              >
            </div>
          </div>
        </div>

        <!-- Tabs & Heading Gallery -->
        <div
          class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 md:mb-6 p-4 md:rounded-xl shadow-md bg-gradient-to-br from-blue-800 to-blue-900 dark:from-blue-900 dark:to-blue-950"
        >
          <h3
            class="text-md sm:text-lg font-bold text-white flex items-center tracking-wide"
            style="font-family: 'Oswald', sans-serif"
          >
            <PhImage class="w-6 h-6 mr-2 text-white" />
            Koleksi Galeri
          </h3>
          <div
            class="flex bg-white/20 dark:bg-black/20 p-1 rounded-xl shadow-inner border border-white/20"
          >
            <button
              @click="activeTab = 'terbaru'"
              class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-300"
              :class="
                activeTab === 'terbaru'
                  ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-md'
                  : 'text-blue-100 hover:text-white'
              "
            >
              Terbaru
            </button>
            <button
              @click="activeTab = 'terpopuler'"
              class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-300"
              :class="
                activeTab === 'terpopuler'
                  ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-md'
                  : 'text-blue-100 hover:text-white'
              "
            >
              Terpopuler
            </button>
          </div>
        </div>

        <!-- Photo Grid (True Masonry using CSS Columns) -->
        <TransitionGroup
          name="gallery"
          tag="div"
          class="columns-2 md:columns-3 lg:columns-4 gap-1 sm:gap-4 md:gap-6 w-full transform-gpu"
        >
          <div
            v-for="(item, index) in displayedGallery"
            :key="item.id"
            @click="openModal(index)"
            class="group relative overflow-hidden rounded-none sm:rounded-xl cursor-pointer shadow-sm hover:shadow-xl transition-all duration-500 bg-gray-200 dark:bg-slate-800 block break-inside-avoid mb-1 sm:mb-4 md:mb-6 transform-gpu"
            :class="{ 'min-h-[200px] animate-pulse': !imageLoaded[item.id] }"
          >
            <!-- Skeleton placeholder -->
            <div
              v-if="!imageLoaded[item.id]"
              class="absolute inset-0 flex items-center justify-center bg-gray-300 dark:bg-slate-700 z-10"
            >
              <PhImage class="w-8 h-8 text-gray-400 dark:text-gray-500" />
            </div>

            <img
              :src="item.image"
              :alt="item.title"
              @load="onImageLoad(item.id)"
              class="w-full h-auto block transition-all duration-700 group-hover:scale-105"
              :class="imageLoaded[item.id] ? 'opacity-100' : 'opacity-0'"
              loading="lazy"
            />

            <!-- Dark Overlay on Hover -->
            <div
              v-show="imageLoaded[item.id]"
              class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
            ></div>

            <!-- Top Right Actions (Stock Photo Style) -->
            <div
              v-show="imageLoaded[item.id]"
              class="absolute top-4 right-4 flex flex-col gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-0 md:translate-x-2 group-hover:translate-x-0 z-20"
            >
              <button
                @click.stop="toggleLike(item)"
                class="h-9 px-3 rounded-lg bg-white/90 hover:bg-white flex items-center justify-center shadow-sm transition-colors font-semibold text-xs gap-1.5"
                :class="item.liked ? 'text-red-500' : 'text-gray-700 hover:text-red-500'"
                title="Suka"
              >
                <PhHeart
                  class="w-4 h-4 transition-transform"
                  :class="{ 'animate-bounce-heart': item.liked }"
                  :weight="item.liked ? 'fill' : 'regular'"
                />
                {{ item.likes }}
              </button>
              <a
                @click.stop
                :href="item.image"
                download
                target="_blank"
                class="w-9 h-9 rounded-lg bg-white/90 hover:bg-white text-gray-700 hover:text-blue-600 hidden md:flex items-center justify-center shadow-sm transition-colors"
                title="Unduh"
              >
                <PhDownloadSimple class="w-4 h-4" />
              </a>
            </div>

            <!-- Bottom Left Text -->
            <div
              v-show="imageLoaded[item.id]"
              class="absolute bottom-0 left-0 p-3 md:p-5 w-full z-20 opacity-100 md:opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-0 md:translate-y-2 group-hover:translate-y-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent md:bg-none"
            >
              <h4
                class="text-white font-bold text-xs md:text-base leading-snug drop-shadow-md mb-1 md:mb-2 line-clamp-2"
              >
                {{ item.title }}
              </h4>
              <div class="flex items-center gap-1.5 md:gap-2">
                <div
                  class="hidden md:flex w-6 h-6 rounded-full bg-blue-600 border border-white/50 items-center justify-center text-white text-[10px] font-bold shadow-sm"
                >
                  SM
                </div>
                <span
                  class="text-gray-300 text-[10px] md:text-xs font-medium tracking-wide"
                  >{{ categories.find((c) => c.id === item.category)?.name }}</span
                >
              </div>
            </div>
          </div>
        </TransitionGroup>

        <!-- Global Skeleton / Loading Data -->
        <div v-if="isLoadingGalleries" class="flex justify-center items-center py-20">
          <div class="flex flex-col items-center">
            <PhImage
              class="w-12 h-12 text-gray-400 dark:text-gray-500 animate-pulse mb-3"
            />
            <span class="text-gray-500 dark:text-gray-400 font-medium"
              >Memuat Galeri...</span
            >
          </div>
        </div>

        <!-- Sentinel / Loading Indicator untuk Infinite Scroll -->
        <div
          ref="loadMoreSentinel"
          class="w-full flex justify-center items-center h-10 mt-8 mb-4"
        >
          <div
            v-if="hasMore && !isLoadingGalleries"
            class="flex items-center text-gray-400 dark:text-gray-500 gap-2"
          >
            <svg
              class="animate-spin w-5 h-5"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              ></circle>
              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
              ></path>
            </svg>
            <span class="text-sm font-medium tracking-wide">
              Memuat foto lainnya...
            </span>
          </div>
        </div>

        <!-- Empty State -->
        <div
          v-if="filteredGallery.length === 0 && !isLoadingGalleries"
          class="py-20 text-center bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm mt-4"
        >
          <div
            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-700 mb-4 text-gray-400"
          >
            <PhImageBroken class="w-8 h-8" />
          </div>
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tidak Ada Foto</h3>
          <p class="text-gray-500 dark:text-gray-400 mt-1">
            Koleksi galeri untuk kategori ini belum tersedia.
          </p>
        </div>
      </div>
    </section>

    <!-- Lightbox Modal -->
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
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-sm p-4"
        @click="closeModal"
      >
        <!-- Action Buttons -->
        <div
          class="absolute top-4 right-4 md:top-6 md:right-6 flex items-center gap-3 z-50"
        >
          <a
            v-if="currentImage"
            @click.stop
            :href="currentImage.image"
            download
            target="_blank"
            class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors focus:outline-none"
            title="Unduh Foto"
          >
            <PhDownloadSimple class="w-5 h-5 md:w-6 md:h-6" />
          </a>
          <button
            @click.stop="closeModal"
            class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors focus:outline-none"
            title="Tutup"
          >
            <PhX class="w-5 h-5 md:w-6 md:h-6" />
          </button>
        </div>

        <!-- Navigation Buttons -->
        <button
          v-if="filteredGallery.length > 1"
          @click.stop="prevImage"
          class="absolute left-2 md:left-6 text-white/70 hover:text-white transition-colors z-50 p-2 md:p-4 hover:bg-white/10 rounded-full focus:outline-none"
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
          v-if="filteredGallery.length > 1"
          @click.stop="nextImage"
          class="absolute right-2 md:right-6 text-white/70 hover:text-white transition-colors z-50 p-2 md:p-4 hover:bg-white/10 rounded-full focus:outline-none"
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
              d="M9 5l7 7-7-7"
            ></path>
          </svg>
        </button>

        <!-- Image -->
        <div class="relative max-w-5xl w-full flex flex-col items-center" @click.stop>
          <img
            v-if="currentImage"
            :src="currentImage.image"
            :alt="currentImage.title"
            class="w-full max-h-[80vh] object-contain rounded-xl shadow-2xl transition-transform duration-300"
          />
          <div class="mt-4 text-center">
            <h3 class="text-xl font-bold text-white">{{ currentImage?.title }}</h3>
            <p class="text-gray-400 text-sm mt-1 uppercase tracking-wider">
              {{ categories.find((c) => c.id === currentImage?.category)?.name }}
            </p>
          </div>
          <div
            v-if="filteredGallery.length > 1"
            class="absolute -bottom-8 md:-bottom-10 text-white/80 text-xs md:text-sm font-medium bg-black/50 px-3 py-1 rounded-full"
          >
            {{ currentIndex + 1 }} / {{ filteredGallery.length }}
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&display=swap");

.gallery-move,
.gallery-enter-active,
.gallery-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.gallery-enter-from,
.gallery-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(-30px);
}
.gallery-leave-active {
  position: absolute;
}

@keyframes bounce-heart {
  0% {
    transform: scale(1);
  }
  40% {
    transform: scale(1.4);
  }
  100% {
    transform: scale(1);
  }
}

.animate-bounce-heart {
  animation: bounce-heart 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
</style>
