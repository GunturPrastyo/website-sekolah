<script setup>
import { ref, onMounted, nextTick } from "vue";
import { createIcons, icons } from "lucide";

const timeline = ref([
  {
    year: "1985",
    title: "Pendirian & Peresmian SMAN 1",
    icon: "building",
    description:
      "Sekolah ini resmi didirikan pada tanggal 17 Agustus 1985 berdasarkan SK Menteri Pendidikan. Pada awalnya, sekolah hanya memiliki 3 ruang kelas dengan 120 siswa angkatan pertama dan menumpang di gedung SMP terdekat selama proses pembangunan gedung utama berlangsung.",
  },
  {
    year: "1992",
    title: "Pembangunan Gedung Utama",
    icon: "hammer",
    description:
      "Pembangunan gedung sekolah mandiri akhirnya selesai dan diresmikan oleh Gubernur. Di tahun ini, SMAN 1 mulai menempati lokasi saat ini dengan fasilitas yang diperluas, meliputi 12 ruang kelas, ruang guru, dan lapangan olahraga serbaguna.",
  },
  {
    year: "2005",
    title: "Akreditasi A & Prestasi Nasional",
    icon: "award",
    description:
      "Berkat dedikasi seluruh civitas akademika, SMAN 1 berhasil meraih akreditasi A (Sangat Baik). Pada tahun yang sama, tim cerdas cermat sekolah berhasil membawa pulang piala Juara 1 tingkat Nasional untuk pertama kalinya.",
  },
  {
    year: "2015",
    title: "Era Transformasi Digital",
    icon: "monitor",
    description:
      "Menjawab tantangan abad 21, sekolah mulai mengintegrasikan teknologi ke dalam pembelajaran. Pembangunan laboratorium komputer modern, perpustakaan digital, serta pengadaan proyektor dan Wi-Fi di seluruh area sekolah mulai direalisasikan.",
  },
  {
    year: "2021",
    title: "Sekolah Adiwiyata & Peduli Lingkungan",
    icon: "leaf",
    description:
      "Berkomitmen pada lingkungan yang asri, sekolah memenangkan penghargaan Sekolah Adiwiyata tingkat Provinsi. Program bank sampah, taman hidroponik, dan ruang hijau terpadu menjadi identitas baru SMAN 1.",
  },
  {
    year: "2026",
    title: "Pelopor Kurikulum Merdeka",
    icon: "rocket",
    description:
      "Hari ini, SMAN 1 Nogosari terus melesat menjadi sekolah percontohan dalam implementasi Kurikulum Merdeka. Dengan lebih dari 1100 siswa, kami terus melahirkan lulusan yang cerdas, berkarakter, dan berdaya saing global.",
  },
]);

onMounted(() => {
  nextTick(() => {
    createIcons({ icons });
  });
});
</script>

<template>
  <div>
    <!-- Hero Banner -->
    <div
      class="relative pt-32 pb-24 lg:pt-44 lg:pb-32 bg-gradient-to-br from-blue-900 via-blue-950 to-slate-900 overflow-hidden"
    >
      <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500/20 blur-3xl rounded-full"></div>
      <div class="absolute bottom-0 right-0 w-80 h-80 bg-sky-500/20 blur-3xl rounded-full"></div>

      <div class="container relative z-10 mx-auto px-6 text-center">
        <span
          class="inline-block px-4 py-1.5 mb-5 text-xs md:text-sm font-semibold text-blue-900 bg-white/90 rounded-full shadow-sm backdrop-blur"
        >
          Profil Sekolah
        </span>
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
          Sejarah & Perjalanan
        </h1>
        <p class="text-blue-100 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
          Dari awal yang sederhana hingga menjadi institusi pendidikan unggulan. Mari menyusuri jejak langkah perjuangan dan prestasi SMAN 1 Nogosari dari masa ke masa.
        </p>
      </div>
    </div>

    <!-- Timeline Section -->
    <section class="py-16 md:py-24 bg-gray-50 dark:bg-slate-900 min-h-screen relative overflow-hidden">
      <div class="container mx-auto px-6 max-w-5xl">
        
        <div class="relative wrap overflow-hidden p-2 md:p-10 h-full">
          <!-- Garis Tengah Timeline -->
          <div class="absolute z-0 w-1 bg-blue-200 dark:bg-slate-700 h-full left-8 md:left-1/2 transform md:-translate-x-1/2 rounded-full"></div>

          <!-- Looping Data Lini Masa -->
          <div
            v-for="(item, index) in timeline"
            :key="index"
            class="relative z-10 flex items-center w-full mb-12 last:mb-0 group"
            :class="index % 2 === 0 ? 'md:flex-row-reverse' : 'md:flex-row'"
          >
            <!-- Ruang Kosong untuk Penyeimbang Grid Desktop -->
            <div class="hidden md:block md:w-1/2"></div>

            <!-- Ikon Lingkaran Timeline -->
            <div
              class="absolute left-8 md:left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full bg-blue-600 border-4 border-white dark:border-slate-800 text-white shadow-lg z-20 group-hover:scale-110 group-hover:bg-blue-500 transition-all duration-300"
            >
              <i :data-lucide="item.icon" class="w-5 h-5 md:w-6 md:h-6"></i>
            </div>

            <!-- Kartu Konten -->
            <div
              class="ml-20 md:ml-0 w-full md:w-1/2"
              :class="index % 2 === 0 ? 'md:pr-12 lg:pr-16' : 'md:pl-12 lg:pl-16'"
            >
              <div
                class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative"
              >
                <!-- Segitiga panah ke garis (opsional, disembunyikan di mobile agar lebih rapi) -->
                <div class="hidden md:block absolute top-6 w-4 h-4 bg-white dark:bg-slate-800 border-t border-r border-gray-100 dark:border-slate-700 transform rotate-45"
                     :class="index % 2 === 0 ? '-right-2' : '-left-2 border-b border-l border-t-0 border-r-0'"></div>
                
                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 font-bold text-sm rounded-lg mb-3 shadow-sm">{{ item.year }}</span>
                <h3 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">{{ item.title }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base leading-relaxed text-justify">{{ item.description }}</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>
</template>