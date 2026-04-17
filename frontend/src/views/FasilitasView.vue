<template>
  <div>
    <!-- HERO -->
    <div class="relative pt-32 pb-24 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 dark:from-slate-900 dark:via-blue-900 dark:to-slate-950 overflow-hidden">
      <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top,white,transparent_60%)]"></div>

      <div class="container mx-auto px-6 text-center relative z-10">
        <span class="inline-block px-4 py-1.5 mb-4 text-xs font-semibold text-blue-700 bg-white/90 rounded-full shadow">
          Profil Sekolah
        </span>

        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">
          Fasilitas Sekolah
        </h1>

        <p class="text-blue-100 max-w-2xl mx-auto">
          Jelajahi berbagai fasilitas modern yang mendukung pengalaman belajar siswa secara optimal.
        </p>
      </div>
    </div>

    <!-- CONTENT -->
    <section class="py-16 md:py-24 px-6 bg-gray-50 dark:bg-slate-900">
      <div class="container mx-auto max-w-7xl">
        <div class="flex flex-col md:flex-row gap-10">

          <!-- MAIN -->
          <main class="w-full md:w-3/4 lg:w-4/5">
            <template v-for="category in facilityCategories" :key="category.id">

              <article
                v-if="activeCategory === category.id"
                class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-lg border border-gray-100 dark:border-slate-700"
              >

                <!-- FEATURED IMAGE -->
                <div class="relative h-64 md:h-80">
                  <img
                    :src="category.items[0].src"
                    class="w-full h-full object-cover"
                  />
                  <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>

                  <div class="absolute bottom-6 left-6 right-6 text-white">
                    <span class="text-xs bg-white/20 px-3 py-1 rounded-full backdrop-blur">
                      Fasilitas Sekolah
                    </span>
                    <h2 class="text-2xl md:text-4xl font-bold mt-3">
                      {{ category.name }}
                    </h2>
                  </div>
                </div>

                <!-- CONTENT -->
                <div class="p-6 md:p-10 grid md:grid-cols-3 gap-10">

                  <!-- MAIN ARTICLE -->
                  <div class="md:col-span-2 space-y-6">

                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      Oleh Tim Redaksi • 15 April 2026
                    </div>

                    <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                      {{ category.description }}
                    </p>

                    <blockquote class="border-l-4 border-blue-600 pl-4 italic text-gray-600 dark:text-gray-300">
                      “Fasilitas yang baik bukan hanya pelengkap, tetapi fondasi utama dalam menciptakan pengalaman belajar yang maksimal.”
                    </blockquote>

                    <div v-for="(item, index) in category.items" :key="index" class="space-y-4 pt-6">

                      <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                        {{ item.title }}
                      </h3>

                      <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        {{ item.text }}
                      </p>

                      <div class="overflow-hidden rounded-xl">
                        <img
                          :src="item.src"
                          class="w-full h-64 object-cover hover:scale-105 transition duration-500"
                        />
                      </div>

                    </div>
                  </div>

                  <!-- SIDEBAR ARTICLE -->
                  <div class="space-y-6">

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
                          v-for="other in facilityCategories.filter(c => c.id !== activeCategory)"
                          :key="other.id"
                          @click="activeCategory = other.id"
                          class="cursor-pointer flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition"
                        >
                          <img :src="other.items[0].src" class="w-12 h-12 rounded object-cover" />
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
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const activeCategory = ref('kelas');

const facilityCategories = ref([
  { 
    id: 'kelas',
    name: 'Ruang Kelas',
    description: 'Ruang kelas dirancang untuk menciptakan suasana belajar yang nyaman, interaktif, dan mendukung konsentrasi siswa dalam mengikuti pembelajaran.',
    items: [
      {
        src: 'https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800',
        title: 'Ruang Kelas Modern',
        text: 'Setiap ruang kelas dilengkapi dengan fasilitas modern seperti proyektor, papan interaktif, dan ventilasi yang baik.'
      },
      {
        src: 'https://images.unsplash.com/photo-1594434533439-04c3a735d359?q=80&w=800',
        title: 'Suasana Interaktif',
        text: 'Lingkungan belajar mendorong siswa aktif berdiskusi dan berkolaborasi.'
      }
    ]
  },
  { 
    id: 'lab',
    name: 'Laboratorium',
    description: 'Laboratorium menjadi sarana penting dalam mendukung pembelajaran berbasis praktik dan eksperimen.',
    items: [
      {
        src: 'https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800',
        title: 'Lab Sains',
        text: 'Fasilitas lengkap untuk eksperimen fisika, kimia, dan biologi.'
      },
      {
        src: 'https://images.unsplash.com/photo-1628243422323-b5039a8a4010?q=80&w=800',
        title: 'Lab Komputer',
        text: 'Komputer modern dengan koneksi internet cepat.'
      }
    ]
  },
  { 
    id: 'perpus',
    name: 'Perpustakaan',
    description: 'Perpustakaan menjadi pusat literasi dan sumber belajar bagi seluruh siswa.',
    items: [
      {
        src: 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=800',
        title: 'Ruang Baca Nyaman',
        text: 'Lingkungan tenang dengan koleksi buku lengkap.'
      }
    ]
  },
  { 
    id: 'olahraga',
    name: 'Sarana Olahraga',
    description: 'Fasilitas olahraga mendukung pengembangan fisik dan bakat siswa.',
    items: [
      {
        src: 'https://images.unsplash.com/photo-1575361204480-aadea2503aa4?q=80&w=800',
        title: 'Lapangan Olahraga',
        text: 'Lapangan luas dan terawat untuk berbagai kegiatan olahraga.'
      }
    ]
  },
  { 
    id: 'pendukung',
    name: 'Fasilitas Pendukung',
    description: 'Fasilitas tambahan untuk menunjang kenyamanan siswa selama di sekolah.',
    items: [
      {
        src: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=800',
        title: 'Kantin Sehat',
        text: 'Menyediakan makanan sehat dan higienis.'
      }
    ]
  }
]);
</script>