<template>
  <div>
    <!-- CONTENT -->
    <section class="relative z-20 px-0 dark:bg-slate-700">
      <div class="container max-w-full">
        <!-- MAIN -->
        <main class="w-full">
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
                    class="inline-block px-3 py-1.5 mb-3 md:mb-4 text-xs font-semibold text-blue-800 bg-white/95 backdrop-blur-sm rounded-full shadow-sm"
                  >
                    Profil Sekolah
                  </span>

                  <h1
                    class="text-2xl md:text-4xl lg:text-5xl font-bold text-white mb-2 md:mb-3"
                  >
                    Fasilitas Sekolah
                  </h1>

                  <p class="text-blue-100 text-sm md:text-base max-w-2xl leading-relaxed">
                    Jelajahi berbagai fasilitas modern yang mendukung pengalaman belajar
                    siswa secara optimal.
                  </p>
                </div>
              </div>

              <!-- CONTENT -->
              <div class="p-6 md:p-10 grid lg:grid-cols-3 gap-10 items-start">
                <!-- MAIN ARTICLE -->
                <div class="lg:col-span-2 space-y-6 md:space-y-8">
                  <!-- Header Artikel -->
                  <div class="border-b border-gray-100 dark:border-slate-700 pb-6">
                    <span
                      class="inline-block text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 px-3 py-1 rounded-full mb-4"
                    >
                      Fasilitas Sekolah
                    </span>
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
                      class="text-xl font-medium text-gray-800 dark:text-gray-200 leading-relaxed"
                    >
                      {{ category.description }}
                    </p>

                    <blockquote
                      class="border-l-4 border-blue-500 bg-blue-50 dark:bg-slate-800/50 p-6 rounded-r-xl italic text-gray-700 dark:text-gray-300 shadow-sm"
                    >
                      “Fasilitas yang baik bukan hanya pelengkap, tetapi fondasi utama
                      dalam menciptakan pengalaman belajar yang maksimal bagi setiap siswa
                      yang menimba ilmu di sekolah kami.”
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

                      <div class="overflow-hidden rounded-2xl shadow-md my-4">
                        <img
                          :src="item.src"
                          class="w-full h-64 md:h-[400px] object-cover hover:scale-105 transition duration-700"
                        />
                      </div>

                      <p
                        class="text-gray-600 dark:text-gray-300 leading-relaxed text-justify text-lg"
                      >
                        {{ item.text }} Kami memastikan bahwa setiap detail dari sarana
                        ini dirawat dengan baik dan selalu diperbarui mengikuti standar
                        pendidikan terkini agar siswa dapat memanfaatkannya secara
                        maksimal.
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
                      Selain menyediakan sarana yang lengkap, pihak sekolah juga memiliki
                      jadwal perawatan rutin yang melibatkan staf kebersihan, teknisi, dan
                      partisipasi aktif dari para siswa. Hal ini bertujuan untuk
                      menanamkan rasa tanggung jawab dan kepemilikan terhadap fasilitas
                      bersama.
                    </p>
                    <p
                      class="text-gray-600 dark:text-gray-300 leading-relaxed text-justify text-lg"
                    >
                      Kami percaya bahwa lingkungan yang bersih, aman, dan berteknologi
                      tinggi akan sangat berdampak pada prestasi akademik maupun
                      non-akademik siswa. Ke depannya, sekolah akan terus berupaya
                      menjalin kerja sama dengan berbagai pihak untuk peningkatan
                      infrastruktur agar relevan dengan tuntutan zaman.
                    </p>
                  </div>
                </div>

                <!-- SIDEBAR ARTICLE -->
                <div class="space-y-8 lg:sticky lg:top-28">
                  <!-- Informasi Singkat -->
                  <div
                    class="bg-blue-50/80 dark:bg-slate-800/50 border border-blue-100 dark:border-slate-700 rounded-md p-6 shadow-sm"
                  >
                    <h4
                      class="font-bold text-blue-800 dark:text-blue-300 mb-4 flex items-center gap-2"
                    >
                      <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                      </svg>
                      Informasi Singkat
                    </h4>

                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-3">
                      <li class="flex items-start gap-2">
                        <svg
                          class="w-5 h-5 text-green-500 shrink-0"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                          ></path>
                        </svg>
                        <span class="pt-0.5">Digunakan setiap hari</span>
                      </li>
                      <li class="flex items-start gap-2">
                        <svg
                          class="w-5 h-5 text-green-500 shrink-0"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                          ></path>
                        </svg>
                        <span class="pt-0.5">Mendukung pembelajaran aktif</span>
                      </li>
                      <li class="flex items-start gap-2">
                        <svg
                          class="w-5 h-5 text-green-500 shrink-0"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                          ></path>
                        </svg>
                        <span class="pt-0.5">Fasilitas modern & terawat</span>
                      </li>
                    </ul>
                  </div>

                  <!-- Fasilitas Lainnya -->
                  <div
                    class="bg-white dark:bg-slate-800 rounded-md p-6 shadow-sm border border-gray-100 dark:border-slate-700"
                  >
                    <h4
                      class="font-bold text-gray-800 dark:text-white mb-4 pb-3 border-b border-gray-100 dark:border-slate-700"
                    >
                      Fasilitas Lainnya
                    </h4>

                    <div class="space-y-4 mt-2">
                      <div
                        v-for="other in facilityCategories.filter(
                          (c) => c.id !== activeCategory
                        )"
                        :key="other.id"
                        @click="activeCategory = other.id"
                        class="group cursor-pointer flex items-center gap-4 transition-all"
                      >
                        <div
                          class="overflow-hidden rounded-md w-14 h-14 md:w-16 md:h-16 shrink-0 shadow-sm"
                        >
                          <img
                            :src="other.items[0].src"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                          />
                        </div>
                        <div class="flex-1">
                          <h5
                            class="text-sm font-semibold text-gray-800 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2"
                          >
                            {{ other.name }}
                          </h5>
                        </div>
                        <div
                          class="w-8 h-8 rounded-full bg-gray-50 dark:bg-slate-700 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/30 transition-colors"
                        >
                          <svg
                            class="w-4 h-4 text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400"
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
                  </div>
                </div>
              </div>
            </article>
          </template>
        </main>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from "vue";

const activeCategory = ref("kelas");

const facilityCategories = ref([
  {
    id: "kelas",
    name: "Ruang Kelas",
    description:
      "Ruang kelas dirancang untuk menciptakan suasana belajar yang nyaman, interaktif, dan mendukung konsentrasi siswa dalam mengikuti pembelajaran.",
    items: [
      {
        src: "https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800",
        title: "Ruang Kelas Modern",
        text:
          "Setiap ruang kelas dilengkapi dengan fasilitas modern seperti proyektor, papan interaktif, dan ventilasi yang baik.",
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
        title: "Lab Sains",
        text: "Fasilitas lengkap untuk eksperimen fisika, kimia, dan biologi.",
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
