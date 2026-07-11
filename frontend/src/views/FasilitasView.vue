<template>
  <div>
    <PageHeader
      v-if="!isLoading"
      badge="Sarana & Prasarana"
      :title="appearanceSettings.headerFasilitas_title || 'Fasilitas Sekolah'"
      :description="
        appearanceSettings.headerFasilitas_description ||
        'Jelajahi berbagai fasilitas modern yang mendukung pengalaman belajar siswa secara optimal di lingkungan sekolah kami.'
      "
      :bgImage="getImageUrl(appearanceSettings.headerFasilitas_bgImage)"
    />

    <section class="relative z-20 px-0 dark:bg-slate-900 min-h-screen">
      <main class="w-full">
        <!-- HEADER TRANSITION -->
        <template v-if="isLoading">
          <!-- Skeleton Content -->
          <div class="container mx-auto max-w-full px-0 md:px-6 lg:px-8 py-0 md:py-8">
            <div
              class="bg-white dark:bg-slate-800 rounded-none md:rounded-lg border-y md:border border-gray-100 dark:border-slate-700 shadow-sm grid lg:grid-cols-12 overflow-hidden"
            >
              <div
                class="lg:col-span-8 p-5 md:p-8 lg:p-10 border-b lg:border-b-0 lg:border-r border-gray-100 dark:border-slate-700 animate-pulse space-y-4"
              >
                <div class="h-6 w-1/3 bg-gray-200 dark:bg-slate-700 rounded"></div>
                <div class="h-4 w-full bg-gray-200 dark:bg-slate-700 rounded"></div>
                <div class="h-4 w-full bg-gray-200 dark:bg-slate-700 rounded"></div>
                <div class="h-4 w-5/6 bg-gray-200 dark:bg-slate-700 rounded"></div>
                <div
                  class="h-64 w-full bg-gray-200 dark:bg-slate-700 rounded-xl my-6"
                ></div>
                <div class="h-4 w-full bg-gray-200 dark:bg-slate-700 rounded"></div>
                <div class="h-4 w-4/5 bg-gray-200 dark:bg-slate-700 rounded"></div>
              </div>
              <div
                class="lg:col-span-4 p-5 md:p-8 lg:p-10 bg-gray-50/50 dark:bg-slate-800/50 animate-pulse"
              >
                <div class="border-gray-200 dark:border-slate-700 border-l-4 pl-4 mb-6">
                  <div class="h-5 w-1/2 bg-gray-200 dark:bg-slate-700 rounded mb-2"></div>
                  <div class="h-3 w-3/4 bg-gray-200 dark:bg-slate-700 rounded"></div>
                </div>
                <div class="space-y-4 mt-4">
                  <div v-for="i in 4" :key="i" class="flex items-start gap-4">
                    <div
                      class="w-20 h-20 bg-gray-200 dark:bg-slate-700 rounded-xl shrink-0"
                    ></div>
                    <div class="flex-1 py-1 space-y-2">
                      <div class="h-4 w-3/4 bg-gray-200 dark:bg-slate-700 rounded"></div>
                      <div class="h-3 w-full bg-gray-200 dark:bg-slate-700 rounded"></div>
                      <div class="h-3 w-5/6 bg-gray-200 dark:bg-slate-700 rounded"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
        <template v-else>
          <!-- CONTENT AREA -->
          <div
            v-if="facilityCategories.length > 0"
            class="container mx-auto max-w-full px-0 md:px-6 lg:px-8 py-0 md:py-8"
          >
            <div
              class="bg-white dark:bg-slate-800 rounded-none md:rounded-lg border-y md:border border-gray-100 dark:border-slate-700 shadow-sm grid lg:grid-cols-12 overflow-hidden"
            >
              <!-- MAIN ARTICLE -->
              <div
                class="lg:col-span-8 p-5 md:p-8 lg:p-10 border-b lg:border-b-0 lg:border-r border-gray-100 dark:border-slate-700"
              >
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
                    v-if="currentCategory"
                    :key="currentCategory.id + '-content'"
                    class="space-y-8 overflow-hidden"
                  >
                    <div class="text-gray-700 dark:text-gray-300 text-justify ql-snow">
                      <div
                        class="editor-content-preview ql-editor !p-0 [&>p:first-of-type]:first-letter:float-left [&>p:first-of-type]:first-letter:text-[4rem] [&>p:first-of-type]:first-letter:font-extrabold [&>p:first-of-type]:first-letter:leading-[0.8] [&>p:first-of-type]:first-letter:pt-1 [&>p:first-of-type]:first-letter:pr-2 [&>p:first-of-type]:first-letter:text-blue-600 dark:[&>p:first-of-type]:first-letter:text-blue-400 [&>p:first-of-type]:first-letter:font-['Oswald']"
                        style="font-family: inherit"
                        v-html="currentCategory.displayContent || currentCategory.content"
                        @click="handleContentClick"
                      ></div>
                    </div>
                  </div>
                </Transition>
              </div>

              <!-- SIDEBAR NAVIGASI -->
              <div
                class="lg:col-span-4 p-5 md:p-6 lg:p-8 bg-gray-50/50 dark:bg-slate-800/50 flex flex-col"
              >
                <div class="sticky top-28">
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
                      class="group cursor-pointer flex items-start gap-4 p-3 rounded-xl transition-all border"
                      :class="
                        other.id === activeCategory
                          ? 'bg-blue-50 border-blue-200 dark:bg-blue-900/30 dark:border-blue-800'
                          : 'bg-white dark:bg-slate-800 border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 hover:border-gray-200 dark:hover:border-slate-600 shadow-sm'
                      "
                    >
                      <div
                        class="relative overflow-hidden rounded-lg w-20 h-20 shrink-0 shadow-sm"
                      >
                        <img
                          :src="other.coverImage"
                          class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                        />
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
                          {{ other.description || "Tidak ada deskripsi" }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- EMPTY STATE KONTEN -->
          <div
            v-else
            class="container mx-auto max-w-4xl px-4 py-16 md:py-24 flex flex-col items-center justify-center text-center"
          >
            <div
              class="w-20 h-20 md:w-24 md:h-24 bg-blue-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-6 shadow-sm border border-blue-100 dark:border-slate-700"
            >
              <PhBuildings
                class="w-10 h-10 md:w-12 md:h-12 text-blue-500 dark:text-slate-400"
                weight="duotone"
              />
            </div>
            <h2
              class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white mb-3"
            >
              Fasilitas Belum Tersedia
            </h2>
            <p
              class="text-slate-500 dark:text-slate-400 text-sm md:text-base max-w-lg leading-relaxed"
            >
              Kami sedang dalam proses memperbarui data fasilitas sekolah. Silakan kembali
              lagi nanti untuk melihat pembaruan.
            </p>
          </div>
        </template>
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
          class="relative w-full max-w-7xl max-h-[95vh] px-4 md:px-24 flex flex-col items-center justify-center"
          @click.stop
        >
          <img
            :src="
              currentGalleryImages[currentImageIndex]?.src ||
              currentGalleryImages[currentImageIndex]
            "
            class="max-w-full max-h-[85vh] object-contain rounded-md shadow-2xl transition-transform duration-300"
          />
          <p
            v-if="currentGalleryImages[currentImageIndex]?.caption"
            class="mt-4 text-white/90 text-xs md:text-sm max-w-3xl text-center bg-black/60 px-5 py-2.5 rounded-lg border border-white/10"
          >
            {{ currentGalleryImages[currentImageIndex].caption }}
          </p>

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
import { ref, computed, onMounted } from "vue";
import api from "@/api/index.js";
import { PhX, PhBuildings } from "@phosphor-icons/vue";
import PageHeader from "@/components/PageHeader.vue";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

const facilityCategories = ref([]);
const isLoading = ref(true);
const defaultImage = ref("/img/gedung.jpg");
const appearanceSettings = ref({});

const getImageUrl = (path) => {
  if (!path) return "";
  if (path.startsWith("http") || path.startsWith("data:image")) return path;
  const baseUrl = api.defaults.baseURL;
  const cleanPath = path.startsWith("/") ? path.slice(1) : path;
  if (cleanPath.startsWith("storage/")) {
    return `${baseUrl}/${cleanPath}`;
  }
  return `${baseUrl}/storage/${cleanPath}`;
};

const fetchSchoolProfile = async () => {
  try {
    const response = await api.get("/api/profil-sekolah");
    if (response.data?.data?.image) {
      let imgUrl = response.data.data.image;
      if (
        imgUrl &&
        !imgUrl.startsWith("http") &&
        !imgUrl.startsWith("data:") &&
        !imgUrl.startsWith("/")
      ) {
        const backendUrl = api.defaults.baseURL;
        imgUrl = `${backendUrl.replace(/\/+$/, "")}/${imgUrl.replace(/^\/+/, "")}`;
      }
      defaultImage.value = imgUrl;
    }
  } catch (error) {
    console.error("Gagal memuat profil sekolah:", error);
  }
};

const fetchFacilities = async () => {
  isLoading.value = true;
  try {
    const [response, settingsRes] = await Promise.all([
      api.get("/api/fasilitas"),
      api.get("/api/settings"),
    ]);

    if (response.data?.data) {
      facilityCategories.value = response.data.data.map((f) => {
        let coverImage = defaultImage.value;
        if (f.images && f.images.length > 0) {
          coverImage = f.images[0];
        } else {
          // Ekstrak src img pertama jika ada
          const match = f.content.match(/<img[^>]+src="([^">]+)"/);
          if (match) coverImage = match[1];
        }

        // Pastikan url coverImage valid
        if (
          coverImage &&
          !coverImage.startsWith("http") &&
          !coverImage.startsWith("data:") &&
          !coverImage.startsWith("/")
        ) {
          const backendUrl = api.defaults.baseURL;
          coverImage = `${backendUrl.replace(/\/+$/, "")}/${coverImage.replace(
            /^\/+/,
            ""
          )}`;
        }

        // Buat deskripsi singkat dengan membersihkan tag HTML
        let excerpt = f.content.replace(/<[^>]*>?/gm, "").trim();
        if (excerpt.length > 100) excerpt = excerpt.substring(0, 100) + "...";

        return {
          id: f.id,
          name: f.category,
          description: excerpt,
          content: f.content,
          coverImage: coverImage,
        };
      });

      if (facilityCategories.value.length > 0) {
        activeCategory.value = facilityCategories.value[0].id;
      }
    }

    if (settingsRes.data?.success) {
      appearanceSettings.value = settingsRes.data.data;
    }
  } catch (error) {
    console.error("Gagal memuat data fasilitas:", error);
  } finally {
    isLoading.value = false;
  }
};

onMounted(async () => {
  await fetchSchoolProfile();
  await fetchFacilities();
});

const activeCategory = ref(null);

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

const handleContentClick = (event) => {
  if (event.target.tagName === "IMG") {
    const images = Array.from(event.currentTarget.querySelectorAll("img")).map((img) => ({
      src: img.src,
      caption: img.getAttribute("alt") || img.getAttribute("title") || "",
    }));
    const index = images.findIndex((img) => img.src === event.target.src);
    openGallery(images, index !== -1 ? index : 0);
  }
};

const changeCategory = (id) => {
  activeCategory.value = id;
  // Otomatis scroll ke atas jika di mobile / tablet (lebar layar < 1024px)
  if (window.innerWidth < 1024) {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
};

const currentCategory = computed(() => {
  const cat = facilityCategories.value.find((c) => c.id === activeCategory.value) || null;
  if (cat && cat.content) {
    // Menyisipkan teks caption secara dinamis di bawah gambar untuk tampilan publik
    const tempDiv = document.createElement("div");
    tempDiv.innerHTML = cat.content;
    const images = tempDiv.querySelectorAll("img");
    images.forEach((img) => {
      const altText = img.getAttribute("alt") || img.getAttribute("title");
      if (altText) {
        // Gunakan <span> sebagai wrapper agar tidak merusak struktur tag <p> bawaan dari editor
        const wrapper = document.createElement("span");
        wrapper.className = "caption-wrapper";
        wrapper.style.cssText = img.style.cssText;

        if (img.style.float === "left" || img.style.float === "right") {
          wrapper.style.display = "table"; // Perilaku paling baik untuk caption gambar yang di-float
        } else if (img.style.display === "block") {
          wrapper.style.display = "block";
        } else {
          wrapper.style.display = "inline-block";
        }

        wrapper.style.width = img.style.width || "max-content";
        wrapper.style.maxWidth = "100%";
        wrapper.style.textAlign = "center";
        wrapper.style.clear = img.style.clear || "both";

        // Reset style gambar
        img.style.cssText = "";
        img.style.display = "block";
        img.style.margin = "0 auto";
        img.style.maxWidth = "100%";
        img.style.height = "auto";

        img.parentNode.insertBefore(wrapper, img);
        wrapper.appendChild(img);

        const caption = document.createElement("span");
        caption.className =
          "block text-center text-xs md:text-sm text-gray-500 mt-2 italic pointer-events-none break-words w-full px-2";
        caption.textContent = altText;
        wrapper.appendChild(caption);
      }
    });
    return { ...cat, displayContent: tempDiv.innerHTML };
  }
  return cat;
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");

/* Responsivitas untuk konten rich text editor */
:deep(.editor-content-preview) {
  word-wrap: break-word;
  overflow-wrap: break-word;
  max-width: 100%;
}
:deep(.editor-content-preview iframe),
:deep(.editor-content-preview video) {
  max-width: 100% !important;
  height: auto;
}
:deep(.editor-content-preview table) {
  display: block;
  width: 100% !important;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

/* Image pointer style inside v-html */
:deep(.editor-content-preview img) {
  cursor: pointer;
  border-radius: 0.5rem;
  transition: transform 0.3s ease;
  max-width: 100% !important;
  height: auto !important;
}
:deep(.editor-content-preview img:hover) {
  transform: scale(1.02);
}

/* Reset float gambar pada layar mobile agar tidak merusak layout */
@media (max-width: 768px) {
  :deep(.caption-wrapper) {
    float: none !important;
    display: block !important;
    width: 100% !important;
    margin: 1.5rem auto !important;
    clear: both !important;
  }
  :deep(.editor-content-preview img) {
    width: 100% !important;
    float: none !important;
    margin: 0 auto !important;
  }
}
</style>
