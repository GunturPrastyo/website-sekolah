<template>
  <div>
    <!-- CONTENT -->
    <section class="relative z-20 px-0 dark:bg-slate-900">
      <div class="container max-w-full">
        <!-- MAIN -->
        <main class="w-full">
          <template v-for="category in facilityCategories" :key="category.id">
            <article
              v-if="activeCategory === category.id"
              class="bg-white dark:bg-slate-800 rounded-lg overflow-hidden shadow-xl border border-gray-100 dark:border-slate-700"
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
              <div class="p-6 md:p-10 grid md:grid-cols-3 gap-10 items-start">
                <!-- MAIN ARTICLE -->
                <div class="md:col-span-2 space-y-6 md:space-y-8">
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
                <div class="space-y-6 md:sticky md:top-28">
                  <div class="bg-blue-50 dark:bg-slate-700/50 rounded-xl p-5">
                    <h4 class="font-semibold text-blue-700 dark:text-blue-300 mb-3">
                      Informasi Singkat
                    </h4>

                    <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                      <li>✔ Digunakan setiap hari</li>
                      <li>✔ Mendukung pembelajaran aktif</li>
                      <li>✔ Fasilitas modern & terawat</li>
                    </ul>
                  </div>

                  <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white mb-3">
                      Fasilitas Lainnya
                    </h4>

                    <div class="space-y-3">
                      <div
                        v-for="other in facilityCategories.filter(
                          (c) => c.id !== activeCategory
                        )"
                        :key="other.id"
                        @click="activeCategory = other.id"
                        class="cursor-pointer flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition"
                      >
                        <img
                          :src="other.items[0].src"
                          class="w-12 h-12 rounded object-cover"
                        />
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                          {{ other.name }}
                        </span>
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
