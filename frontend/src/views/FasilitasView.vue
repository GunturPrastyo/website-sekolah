<template>
  <div>
    <!-- CONTENT -->
    <section class="relative z-20 px-0 dark:bg-slate-700">
      <div class="container max-w-full">
        <!-- MAIN -->
        <main class="w-full">
          <Transition
            mode="out-in"
            enter-active-class="transition-all duration-500 ease-out"
            enter-from-class="opacity-0 translate-y-8 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 -translate-y-4 sm:scale-95"
          >
            <div :key="activeCategory" class="w-full">
              <template v-for="category in facilityCategories" :key="category.id">
                <article
                  v-if="activeCategory === category.id"
                  class="bg-white dark:bg-gray-800 overflow-hidden"
                >
                  <!-- FEATURED IMAGE -->
                  <div class="relative h-[300px] md:h-[400px] overflow-hidden">
                    <img
                      :src="category.items[0].src"
                      class="absolute inset-0 w-full h-full object-cover blur-sm scale-110"
                    />
                    <div
                      class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"
                    ></div>

                    <div class="absolute bottom-0 left-0 w-full p-6 md:p-10 text-white">
                      <span
                        class="inline-block px-5 py-1.5 mb-3 md:mb-4 text-lg md:text-xl font-bold text-blue-900 bg-white/95 backdrop-blur-sm rounded-full shadow-sm"
                        style="font-family: 'Kalam', cursive"
                      >
                        Profil Sekolah
                      </span>

                      <h1
                        class="text-2xl md:text-4xl lg:text-5xl font-bold text-white mb-2 md:mb-3"
                      >
                        Fasilitas Sekolah
                      </h1>

                      <p
                        class="text-blue-100 text-sm md:text-base max-w-2xl leading-relaxed"
                      >
                        Jelajahi berbagai fasilitas modern yang mendukung pengalaman
                        belajar siswa secara optimal.
                      </p>
                    </div>
                  </div>

                  <!-- CONTENT -->
                  <div class="p-6 md:p-10 grid lg:grid-cols-3 gap-10 items-start">
                    <!-- MAIN ARTICLE -->
                    <div class="lg:col-span-2 space-y-6 md:space-y-8">
                      <!-- Header Artikel -->
                      <div class="border-b border-gray-100 dark:border-slate-700 pb-6">
                        <h2
                          class="text-2xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4"
                        >
                          {{ category.name }}
                        </h2>
                        <div
                          class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400"
                        >
                          <div
                            class="w-10 h-10 rounded-full bg-blue-100 dark:bg-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-lg"
                          >
                            R
                          </div>
                          <div>
                            <div class="font-medium text-gray-800 dark:text-gray-200">
                              Tim Redaksi SMAN 1
                            </div>
                            <div>15 April 2026 • 5 min read</div>
                          </div>
                        </div>
                      </div>

                      <!-- Konten Pengantar -->
                      <div class="space-y-6">
                        <p
                          class="text-xl font-medium text-gray-800 dark:text-gray-200 leading-relaxed first-letter:text-6xl md:first-letter:text-7xl first-letter:font-bold first-letter:font-serif first-letter:text-blue-600 dark:first-letter:text-blue-400 first-letter:mr-3 first-letter:float-left first-letter:leading-none"
                        >
                          {{ category.description }}
                        </p>

                        <blockquote
                          class="border-l-4 border-blue-500 bg-blue-50 dark:bg-slate-800/50 p-6 rounded-r-xl italic text-gray-700 dark:text-gray-300 shadow-sm"
                        >
                          “Fasilitas yang baik bukan hanya pelengkap, tetapi fondasi utama
                          dalam menciptakan pengalaman belajar yang maksimal bagi setiap
                          siswa yang menimba ilmu di sekolah kami.”
                        </blockquote>
                      </div>

                      <!-- List Fasilitas -->
                      <div class="space-y-12 pt-4">
                        <div
                          v-for="(item, index) in category.items"
                          :key="index"
                          class="space-y-4"
                        >
                          <h3
                            class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-3"
                          >
                            <span
                              class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center text-sm shadow-md"
                              >{{ index + 1 }}</span
                            >
                            {{ item.title }}
                          </h3>

                          <!-- Galeri Grid (Multi-image) -->
                          <div
                            v-if="item.images && item.images.length > 1"
                            class="grid grid-cols-2 gap-2 md:gap-3 my-4"
                          >
                            <template
                              v-for="(img, imgIdx) in item.images.slice(0, 4)"
                              :key="imgIdx"
                            >
                              <div
                                class="relative overflow-hidden rounded-xl shadow-md h-48 md:h-64 cursor-pointer group bg-gray-100 dark:bg-slate-800"
                                :class="{
                                  'col-span-2 h-56 md:h-80':
                                    item.images.length === 3 && imgIdx === 0,
                                }"
                                @click="openGallery(item.images, imgIdx)"
                              >
                                <img
                                  :src="img"
                                  class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                />

                                <!-- Overlay untuk gambar ke-4 jika lebih dari 4 gambar -->
                                <div
                                  v-if="imgIdx === 3 && item.images.length > 4"
                                  class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center text-white backdrop-blur-[2px] transition-colors group-hover:bg-black/50"
                                >
                                  <span class="text-2xl md:text-3xl font-bold"
                                    >+{{ item.images.length - 4 }}</span
                                  >
                                  <span class="text-sm md:text-base font-medium mt-1"
                                    >Lihat Semua</span
                                  >
                                </div>
                              </div>
                            </template>
                          </div>

                          <!-- Single Image -->
                          <div
                            v-else
                            class="overflow-hidden rounded-2xl shadow-md my-4 cursor-pointer group"
                            @click="openGallery(item.images || [item.src], 0)"
                          >
                            <img
                              :src="item.images ? item.images[0] : item.src"
                              class="w-full h-64 md:h-[400px] object-cover group-hover:scale-105 transition duration-700"
                            />
                          </div>

                          <p
                            class="text-gray-600 dark:text-gray-300 leading-relaxed text-justify text-lg"
                          >
                            {{ item.text }} Kami memastikan bahwa setiap detail dari
                            sarana ini dirawat dengan baik dan selalu diperbarui mengikuti
                            standar pendidikan terkini agar siswa dapat memanfaatkannya
                            secara maksimal.
                          </p>
                        </div>
                      </div>

                      <!-- Tambahan Teks Dummy (Scroll Tester) -->
                      <div
                        class="pt-8 border-t border-gray-100 dark:border-slate-700 mt-8 space-y-4"
                      >
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                          Komitmen Perawatan Fasilitas
                        </h3>
                        <p
                          class="text-gray-600 dark:text-gray-300 leading-relaxed text-justify text-lg"
                        >
                          Selain menyediakan sarana yang lengkap, pihak sekolah juga
                          memiliki jadwal perawatan rutin yang melibatkan staf kebersihan,
                          teknisi, dan partisipasi aktif dari para siswa. Hal ini
                          bertujuan untuk menanamkan rasa tanggung jawab dan kepemilikan
                          terhadap fasilitas bersama.
                        </p>
                        <p
                          class="text-gray-600 dark:text-gray-300 leading-relaxed text-justify text-lg"
                        >
                          Kami percaya bahwa lingkungan yang bersih, aman, dan
                          berteknologi tinggi akan sangat berdampak pada prestasi akademik
                          maupun non-akademik siswa. Ke depannya, sekolah akan terus
                          berupaya menjalin kerja sama dengan berbagai pihak untuk
                          peningkatan infrastruktur agar relevan dengan tuntutan zaman.
                        </p>
                      </div>
                    </div>

                    <!-- SIDEBAR ARTICLE -->
                    <div class="space-y-8 lg:sticky lg:top-28">
                      <!-- Fasilitas Lainnya -->
                      <div
                        class="relative bg-white dark:bg-slate-800 rounded-lg p-6 shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col"
                      >
                        <div
                          class="border-sky-600 dark:border-slate-600 shrink-0 border-l-6 pl-3 rounded-sm"
                        >
                          <h4 class="font-bold text-gray-800 dark:text-white">
                            Fasilitas Lainnya
                          </h4>
                          <p class="text-sm text-gray-500 dark:text-gray-400">
                            Jelajahi berbagai sarana dan prasarana lain yang tersedia.
                          </p>
                        </div>
                        <div
                          class="space-y-3 mt-6 max-h-[550px] overflow-y-auto pr-2 pb-6 [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-slate-600 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300 dark:hover:[&::-webkit-scrollbar-thumb]:bg-slate-500"
                        >
                          <div
                            v-for="other in facilityCategories.filter(
                              (c) => c.id !== activeCategory
                            )"
                            :key="other.id"
                            @click="changeCategory(other.id)"
                            class="group cursor-pointer flex items-start gap-4 p-2.5 -ml-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-all border border-transparent hover:border-gray-100 dark:hover:border-slate-600"
                          >
                            <div
                              class="relative overflow-hidden rounded-lg w-20 h-20 shrink-0 shadow-sm"
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
                                class="text-sm font-bold text-gray-800 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-1"
                              >
                                {{ other.name }}
                              </h5>
                              <p
                                class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 line-clamp-2 leading-relaxed"
                              >
                                {{ other.description }}
                              </p>
                            </div>
                            <div
                              class="mt-1.5 w-7 h-7 shrink-0 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 transition-colors"
                            >
                              <svg
                                class="w-3.5 h-3.5 text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-transform duration-300 group-hover:translate-x-0.5"
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
                            </div>
                          </div>
                        </div>

                        <!-- Scroll Indicator Fade -->
                        <div
                          class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-white dark:from-slate-800 to-transparent pointer-events-none rounded-b-2xl"
                        ></div>
                      </div>
                    </div>
                  </div>
                </article>
              </template>
            </div>
          </Transition>
        </main>
      </div>
    </section>

    <!-- Tombol Scroll Navigation (Floating) -->
    <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-3">
      <Transition
        enter-active-class="transition-all duration-300"
        enter-from-class="opacity-0 translate-y-5"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-300"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-5"
      >
        <button
          v-show="showScrollTop"
          @click="scrollToTop"
          class="w-10 h-10 md:w-12 md:h-12 bg-blue-600/90 hover:bg-blue-700 text-white rounded-full shadow-lg backdrop-blur-sm flex items-center justify-center transition-all hover:-translate-y-1 focus:outline-none"
          title="Scroll ke Atas"
        >
          <svg
            class="w-5 h-5 md:w-6 md:h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 15l7-7 7 7"
            ></path>
          </svg>
        </button>
      </Transition>

      <Transition
        enter-active-class="transition-all duration-300"
        enter-from-class="opacity-0 -translate-y-5"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-300"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-5"
      >
        <button
          v-show="showScrollBottom"
          @click="scrollToBottom"
          class="w-10 h-10 md:w-12 md:h-12 bg-blue-600/90 hover:bg-blue-700 text-white rounded-full shadow-lg backdrop-blur-sm flex items-center justify-center transition-all hover:translate-y-1 focus:outline-none"
          title="Scroll ke Bawah"
        >
          <svg
            class="w-5 h-5 md:w-6 md:h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 9l-7 7-7-7"
            ></path>
          </svg>
        </button>
      </Transition>
    </div>

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
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            ></path>
          </svg>
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
import { ref, onMounted, onBeforeUnmount } from "vue";

const activeCategory = ref("kelas");
const showScrollTop = ref(false);
const showScrollBottom = ref(true);

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

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
};

const scrollToBottom = () => {
  window.scrollTo({ top: document.documentElement.scrollHeight, behavior: "smooth" });
};

const handleScroll = () => {
  const scrollY = window.scrollY;
  const innerHeight = window.innerHeight;
  const scrollHeight = document.documentElement.scrollHeight;

  // Munculkan tombol scroll atas jika sudah scroll ke bawah lebih dari 300px
  showScrollTop.value = scrollY > 300;

  // Sembunyikan tombol scroll bawah jika sudah mencapai bagian paling bawah (toleransi 50px)
  showScrollBottom.value = scrollY + innerHeight < scrollHeight - 50;
};

onMounted(() => {
  window.addEventListener("scroll", handleScroll);
  handleScroll(); // inisialisasi awal
});

onBeforeUnmount(() => {
  window.removeEventListener("scroll", handleScroll);
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
</script>
