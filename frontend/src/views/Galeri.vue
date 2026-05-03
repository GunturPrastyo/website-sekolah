<script setup>
import { ref, computed } from "vue";
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

const categories = [
  {
    id: "semua",
    name: "Semua Foto",
    count: 124,
    image:
      "https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800&auto=format&fit=crop",
  },
  {
    id: "fasilitas",
    name: "Fasilitas",
    count: 42,
    image:
      "https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800&auto=format&fit=crop",
  },
  {
    id: "kegiatan",
    name: "Kegiatan",
    count: 56,
    image:
      "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800&auto=format&fit=crop",
  },
  {
    id: "ekskul",
    name: "Ekstrakurikuler",
    count: 26,
    image:
      "https://images.unsplash.com/photo-1546519638-68e109498ffc?q=80&w=800&auto=format&fit=crop",
  },
  {
    id: "pentas-seni",
    name: "Pentas Seni",
    count: 18,
    image:
      "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800&auto=format&fit=crop",
  },
  {
    id: "hut-ri",
    name: "HUT RI",
    count: 32,
    image:
      "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800&auto=format&fit=crop",
  },
];

// Data Dummy Galeri
const galleryList = ref(
  [
    {
      id: 1,
      title: "Gedung Utama Sekolah",
      category: "fasilitas",
      image:
        "https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800&h=1000&fit=crop",
      likes: 120,
    },
    {
      id: 2,
      title: "Upacara Bendera HUT RI",
      category: "hut-ri",
      image:
        "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1200&h=800&fit=crop",
      likes: 540,
    },
    {
      id: 3,
      title: "Praktikum Laboratorium Kimia",
      category: "fasilitas",
      image:
        "https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=800&h=1200&fit=crop",
      likes: 85,
    },
    {
      id: 4,
      title: "Pertandingan Bola Basket",
      category: "ekskul",
      image:
        "https://images.unsplash.com/photo-1546519638-68e109498ffc?q=80&w=1000&h=1000&fit=crop",
      likes: 210,
    },
    {
      id: 5,
      title: "Perpustakaan Digital",
      category: "fasilitas",
      image:
        "https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=800&h=1100&fit=crop",
      likes: 150,
    },
    {
      id: 6,
      title: "Pentas Seni Tradisional",
      category: "pentas-seni",
      image:
        "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1200&h=700&fit=crop",
      likes: 430,
    },
    {
      id: 7,
      title: "Klub Robotika",
      category: "ekskul",
      image:
        "https://images.unsplash.com/photo-1561557944-6e7860d1a7eb?q=80&w=900&h=1200&fit=crop",
      likes: 95,
    },
    {
      id: 8,
      title: "Ruang Kelas Modern",
      category: "fasilitas",
      image:
        "https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=1200&h=900&fit=crop",
      likes: 180,
    },
    {
      id: 9,
      title: "Kegiatan Pramuka Kemah Bakti",
      category: "ekskul",
      image:
        "https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=800&h=800&fit=crop",
      likes: 275,
    },
  ].map((item) => ({ ...item, liked: false }))
);

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

// Lightbox Modal State
const isModalOpen = ref(false);
const currentImage = ref(null);

const openModal = (item) => {
  currentImage.value = item;
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
      <div class="container mx-auto max-w-full px-0 lg:px-8">
        <!-- Video Profil Section -->
        <div
          class="mb-6 relative rounded-none sm:rounded-xl overflow-hidden shadow-xl aspect-[4/3] sm:aspect-video md:aspect-[21/9] group cursor-pointer w-full block"
        >
          <img
            src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1600&auto=format&fit=crop"
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
              style="font-family: 'Oswald', sans-serif"
              >Video Profil</span
            >
            <h3
              class="text-xl md:text-3xl font-bold text-white mb-2 group-hover:text-blue-200 transition-colors"
            >
              Company Profile SMAN 1 Nogosari 2026
            </h3>
          </div>
        </div>

        <!-- Category Cards (Flexible Wrap untuk Ganjil/Genap) -->
        <div class="flex flex-wrap gap-2 md:gap-4 mb-8 md:mb-10 px-3 md:px-0">
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
          class="columns-2 md:columns-3 lg:columns-4 gap-1 sm:gap-4 md:gap-6 w-full pt-0 md:pt-4"
        >
          <div
            v-for="item in filteredGallery"
            :key="item.id"
            @click="openModal(item)"
            class="group relative overflow-hidden rounded-none sm:rounded-xl cursor-pointer shadow-sm hover:shadow-xl transition-all duration-500 bg-gray-200 dark:bg-slate-800 block break-inside-avoid mb-1 sm:mb-4 md:mb-6"
          >
            <img
              :src="item.image"
              :alt="item.title"
              class="w-full h-auto block transition-transform duration-700 group-hover:scale-105"
            />

            <!-- Dark Overlay on Hover -->
            <div
              class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
            ></div>

            <!-- Top Right Actions (Stock Photo Style) -->
            <div
              class="absolute top-4 right-4 flex flex-col gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-0 md:translate-x-2 group-hover:translate-x-0 z-20"
            >
              <button
                @click.stop="toggleLike(item)"
                class="h-9 px-3 rounded-lg bg-white/90 hover:bg-white flex items-center justify-center shadow-sm transition-colors font-semibold text-xs gap-1.5"
                :class="item.liked ? 'text-red-500' : 'text-gray-700 hover:text-red-500'"
                title="Suka"
              >
                <PhHeart class="w-4 h-4" :weight="item.liked ? 'fill' : 'regular'" />
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

        <!-- Empty State -->
        <div
          v-if="filteredGallery.length === 0"
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

        <!-- Image -->
        <div class="relative max-w-5xl w-full flex flex-col items-center" @click.stop>
          <img
            v-if="currentImage"
            :src="currentImage.image"
            :alt="currentImage.title"
            class="w-full max-h-[80vh] object-contain rounded-xl shadow-2xl"
          />
          <div class="mt-4 text-center">
            <h3 class="text-xl font-bold text-white">{{ currentImage?.title }}</h3>
            <p class="text-gray-400 text-sm mt-1 uppercase tracking-wider">
              {{ categories.find((c) => c.id === currentImage?.category)?.name }}
            </p>
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
  transform: scale(0.95) translateY(20px);
}
.gallery-leave-active {
  position: absolute;
}
</style>
