<script setup>
import { ref, computed, onMounted, nextTick, onUpdated } from "vue";
import { createIcons, icons } from "lucide";

const activeCategory = ref("semua");

const categories = [
  { id: "semua", name: "Semua Foto" },
  { id: "fasilitas", name: "Fasilitas" },
  { id: "kegiatan", name: "Kegiatan" },
  { id: "ekskul", name: "Ekstrakurikuler" },
];

// Data Dummy Galeri
const galleryList = ref([
  {
    id: 1,
    title: "Gedung Utama Sekolah",
    category: "fasilitas",
    image: "https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1200",
  },
  {
    id: 2,
    title: "Upacara Bendera HUT RI",
    category: "kegiatan",
    image: "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1200",
  },
  {
    id: 3,
    title: "Praktikum Laboratorium Kimia",
    category: "fasilitas",
    image: "https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=1200",
  },
  {
    id: 4,
    title: "Pertandingan Bola Basket",
    category: "ekskul",
    image: "https://images.unsplash.com/photo-1546519638-68e109498ffc?q=80&w=1200",
  },
  {
    id: 5,
    title: "Perpustakaan Digital",
    category: "fasilitas",
    image: "https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1200",
  },
  {
    id: 6,
    title: "Pentas Seni Tradisional",
    category: "kegiatan",
    image: "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1200",
  },
  {
    id: 7,
    title: "Klub Robotika",
    category: "ekskul",
    image: "https://images.unsplash.com/photo-1561557944-6e7860d1a7eb?q=80&w=1200",
  },
  {
    id: 8,
    title: "Ruang Kelas Modern",
    category: "fasilitas",
    image: "https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=1200",
  },
  {
    id: 9,
    title: "Kegiatan Pramuka Kemah Bakti",
    category: "ekskul",
    image: "https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=1200",
  },
]);

const filteredGallery = computed(() => {
  if (activeCategory.value === "semua") return galleryList.value;
  return galleryList.value.filter((item) => item.category === activeCategory.value);
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

onMounted(() => {
  createIcons({ icons });
});

onUpdated(() => {
  nextTick(() => {
    createIcons({ icons });
  });
});
</script>

<template>
  <div>
    <!-- Hero Banner -->
    <div
      class="relative pt-32 pb-24 lg:pt-44 lg:pb-32 bg-gradient-to-br from-fuchsia-600 via-purple-700 to-slate-900 overflow-hidden"
    >
      <div
        class="absolute -top-32 -left-32 w-96 h-96 bg-fuchsia-400/20 blur-3xl rounded-full"
      ></div>
      <div
        class="absolute bottom-0 right-0 w-80 h-80 bg-purple-400/20 blur-3xl rounded-full"
      ></div>

      <div class="container relative z-10 mx-auto px-6 text-center">
        <span
          class="inline-block px-4 py-1.5 mb-5 text-xs md:text-sm font-semibold text-fuchsia-900 bg-white/90 rounded-full shadow-sm backdrop-blur"
        >
          Koleksi Visual
        </span>
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
          Galeri Kegiatan
        </h1>
        <p
          class="text-fuchsia-100 text-base md:text-lg max-w-2xl mx-auto leading-relaxed"
        >
          Merekam jejak langkah, fasilitas, dan berbagai momen tak terlupakan di
          lingkungan SMAN 1 Nogosari.
        </p>
      </div>
    </div>

    <!-- Gallery Section -->
    <section class="py-16 md:py-24 px-6 bg-gray-50 dark:bg-slate-900 min-h-screen">
      <div class="container mx-auto max-w-6xl">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
          <button
            v-for="cat in categories"
            :key="cat.id"
            @click="activeCategory = cat.id"
            class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 focus:outline-none"
            :class="
              activeCategory === cat.id
                ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30'
                : 'bg-white text-gray-600 dark:bg-slate-800 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:border-purple-300 hover:text-purple-600 dark:hover:text-purple-400'
            "
          >
            {{ cat.name }}
          </button>
        </div>

        <!-- Photo Grid (Masonry-like layout using columns) -->
        <TransitionGroup
          name="gallery"
          tag="div"
          class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 relative"
        >
          <div
            v-for="item in filteredGallery"
            :key="item.id"
            @click="openModal(item)"
            class="group relative overflow-hidden rounded-2xl cursor-pointer shadow-sm hover:shadow-xl transition-all duration-500 bg-gray-200 dark:bg-slate-800 aspect-square md:aspect-[4/3]"
          >
            <img
              :src="item.image"
              :alt="item.title"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            />

            <!-- Hover Overlay -->
            <div
              class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center p-4"
            >
              <div
                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mb-3 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300"
              >
                <i data-lucide="zoom-in" class="w-6 h-6 text-white"></i>
              </div>
              <h4
                class="text-white font-bold text-center text-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 delay-75"
              >
                {{ item.title }}
              </h4>
              <span
                class="text-purple-200 text-xs font-semibold uppercase tracking-wider mt-2 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 delay-100"
              >
                {{ categories.find((c) => c.id === item.category)?.name }}
              </span>
            </div>
          </div>
        </TransitionGroup>

        <!-- Empty State -->
        <div
          v-if="filteredGallery.length === 0"
          class="py-20 text-center bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm mt-4"
        >
          <div
            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-700 mb-4 text-gray-400"
          >
            <i data-lucide="image-off" class="w-8 h-8"></i>
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
        <!-- Close Button -->
        <button
          @click.stop="closeModal"
          class="absolute top-4 right-4 md:top-6 md:right-6 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors z-50 focus:outline-none"
        >
          <i data-lucide="x" class="w-6 h-6"></i>
        </button>

        <!-- Image -->
        <div class="relative max-w-5xl w-full flex flex-col items-center" @click.stop>
          <img
            v-if="currentImage"
            :src="currentImage.image"
            :alt="currentImage.title"
            class="w-full max-h-[80vh] object-contain rounded-lg shadow-2xl"
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
.gallery-move,
.gallery-enter-active,
.gallery-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.gallery-enter-from,
.gallery-leave-to {
  opacity: 0;
  transform: scale(0.9) translateY(30px);
}
.gallery-leave-active {
  position: absolute;
}
</style>
