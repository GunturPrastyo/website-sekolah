<script setup>
import { computed } from "vue";
import { PhPlay, PhImage } from "@phosphor-icons/vue";

const props = defineProps({
  appearanceSettings: { type: Object, required: true },
  schoolVideoUrl: { type: String, default: "" },
  schoolVideoTitle: { type: String, default: "" },
  schoolVideoDesc: { type: String, default: "" },
  isLoadingSchoolVideo: { type: Boolean, default: true },
  isVideoPlaying: { type: Boolean, default: false },
  galleriesByCategory: { type: Array, default: () => [] },
  fourthGalleryImage: { type: String, default: null },
});

const emit = defineEmits(["play-video"]);

const getImageUrl = (path) => {
  if (!path) return "";
  if (path.startsWith("http") || path.startsWith("data:image")) return path;
  const baseUrl = import.meta.env.VITE_API_URL || "https://api-sekolah-sma.duckdns.org";
  const cleanPath = path.startsWith("/") ? path.slice(1) : path;
  if (cleanPath.startsWith("storage/")) {
    return `${baseUrl}/${cleanPath}`;
  }
  return `${baseUrl}/storage/${cleanPath}`;
};

const videoEmbedUrl = computed(() => {
  if (!props.schoolVideoUrl) return "";
  const url = props.schoolVideoUrl;
  const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
  const match = url.match(regExp);
  if (match && match[2].length === 11) {
    return `https://www.youtube.com/embed/${match[2]}?autoplay=1&rel=0`;
  }
  return url;
});

const videoThumbnail = computed(() => {
  if (!props.schoolVideoUrl)
    return "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop";
  const url = props.schoolVideoUrl;
  const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
  const match = url.match(regExp);
  if (match && match[2].length === 11) {
    return `https://img.youtube.com/vi/${match[2]}/maxresdefault.jpg`;
  }
  return "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop";
});

const emptyCardsCount = computed(() => {
  const currentLength = props.galleriesByCategory ? props.galleriesByCategory.length : 0;
  return Math.max(0, 3 - currentLength);
});
</script>

<template>
  <section
    class="relative py-8 md:py-6 mt-0 md:mt-16 -mx-6 md:mx-0 bg-slate-900 overflow-hidden px-6 md:px-4 lg:px-6 mb-0 md:mb-12 md:rounded-lg shadow-xl bg-center bg-cover md:bg-fixed"
    :style="
      appearanceSettings?.galleryBackgroundImage
        ? { backgroundImage: `url(${appearanceSettings.galleryBackgroundImage})` }
        : {}
    "
  >
    <div
      class="absolute inset-0 bg-gradient-to-b from-blue-900/80 via-slate-900/90 to-black/95 backdrop-blur-[2px]"
    ></div>

    <div class="container relative z-10 mx-auto">
      <div
        class="mb-8 fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out"
      >
        <div class="relative block mt-4">
          <h2
            class="text-2xl md:text-4xl font-bold text-white mb-4 sm:mb-2 tracking-wide"
            style="font-family: 'Oswald', sans-serif"
          >
            Video Profil & Galeri
          </h2>
          <p class="text-blue-200 text-sm md:text-base max-w-2xl">
            Kenali lebih dekat lingkungan, fasilitas, dan berbagai kegiatan seru di
            Sekolah kami melalui tayangan video dan galeri foto.
          </p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 items-stretch">
        <!-- SEKSI VIDEO -->
        <template v-if="isLoadingSchoolVideo">
          <div
            class="lg:col-span-2 relative rounded-lg overflow-hidden shadow-2xl h-[280px] sm:h-[400px] md:h-[450px] w-full bg-slate-800/50 animate-pulse border border-slate-700/50"
          >
            <div class="absolute inset-0 flex items-center justify-center z-20">
              <div class="w-16 h-16 md:w-20 md:h-20 bg-slate-700 rounded-full"></div>
            </div>
          </div>
        </template>
        <template v-else>
          <div
            v-if="schoolVideoUrl"
            class="lg:col-span-2 relative group rounded-lg overflow-hidden shadow-2xl h-[280px] sm:h-[400px] md:h-[450px] w-full block fade-on-scroll"
          >
            <template v-if="!isVideoPlaying">
              <div class="absolute inset-0 cursor-pointer" @click="$emit('play-video')">
                <img
                  :src="videoThumbnail"
                  class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                  alt="Video Profil Sekolah"
                />
                <div
                  class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors"
                ></div>
                <div class="absolute inset-0 flex items-center justify-center z-20">
                  <div
                    class="w-16 h-16 md:w-20 md:h-20 bg-blue-600/90 rounded-full flex items-center justify-center text-white backdrop-blur-sm group-hover:scale-110 transition-all"
                  >
                    <PhPlay class="w-8 h-8 md:w-10 md:h-10 ml-1" weight="fill" />
                  </div>
                </div>
                <div
                  class="absolute bottom-0 left-0 p-5 md:p-8 w-full bg-gradient-to-t from-blue-950/90 via-blue-950/40 to-transparent z-10"
                >
                  <h3
                    class="text-xl md:text-3xl font-bold text-white mb-2"
                    style="font-family: 'Kalam', cursive"
                  >
                    {{ schoolVideoTitle || "Video Profil Sekolah" }}
                  </h3>
                  <p class="text-gray-200 text-sm md:text-base line-clamp-2">
                    {{ schoolVideoDesc }}
                  </p>
                </div>
              </div>
            </template>
            <template v-else>
              <iframe
                :src="videoEmbedUrl"
                class="absolute inset-0 w-full h-full"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
              ></iframe>
            </template>
          </div>
        </template>

        <!-- SEKSI GRID GALERI -->
        <div
          class="lg:col-span-1 grid grid-cols-2 gap-4 h-[300px] sm:h-[400px] md:h-[450px]"
        >
          <!-- Kartu Foto Kategori -->
          <router-link
            v-for="(gallery, index) in galleriesByCategory"
            :key="index"
            :to="{
              path: '/galeri',
              query: { category: gallery.category.replace(/\s+/g, '-').toLowerCase() },
            }"
            class="group relative rounded-lg overflow-hidden shadow-sm h-full block"
          >
            <img
              :src="getImageUrl(gallery.image)"
              class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
              :alt="gallery.category"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-blue-950/90 via-blue-950/20 to-transparent"
            ></div>
            <div class="absolute bottom-0 left-0 p-3 md:p-4 w-full z-10">
              <h4
                class="text-white font-bold text-sm md:text-base leading-tight group-hover:text-blue-300 transition-colors line-clamp-2 capitalize"
              >
                {{ gallery.category }}
              </h4>
            </div>
          </router-link>

          <!-- Skeleton State Aman via emptyCardsCount -->
          <div
            v-for="i in emptyCardsCount"
            :key="'empty-card-' + i"
            class="group relative rounded-lg overflow-hidden shadow-sm h-full bg-blue-900/30 border border-blue-800/30 flex flex-col items-center justify-center text-blue-300/50"
          >
            <PhImage class="w-8 h-8 mb-2 opacity-50" />
            <span class="text-xs font-semibold opacity-50">Belum ada foto</span>
          </div>

          <!-- Tombol CTA Lihat Semua (Foto ke-4) -->
          <router-link
            to="/galeri"
            class="group relative rounded-lg overflow-hidden shadow-sm h-full block bg-blue-900/40 border border-blue-800/40"
          >
            <img
              v-if="fourthGalleryImage"
              :src="getImageUrl(fourthGalleryImage)"
              class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
              alt="Lihat Galeri"
            />
            <div
              class="absolute inset-0 flex items-center justify-center bg-blue-950/70 backdrop-blur-[2px] group-hover:bg-blue-900/80 transition-colors"
            >
              <div class="text-center">
                <div
                  class="bg-blue-500/20 rounded-full w-10 h-10 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform duration-300"
                >
                  <PhImage class="w-5 h-5 text-blue-300" />
                </div>
                <span class="text-blue-300 text-sm font-semibold">Lihat Galeri</span>
              </div>
            </div>
          </router-link>
        </div>
      </div>
    </div>
  </section>
</template>
