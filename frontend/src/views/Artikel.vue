<script setup>
import { ref, onMounted, nextTick, onUpdated } from "vue";
import { createIcons, icons } from "lucide";
import Breadcrumb from "@/components/Breadcrumb.vue";
import ShareModal from "@/components/ShareModal.vue";

const isShareModalOpen = ref(false);
const articleTitle =
  "Peringatan Hari Guru Nasional Berlangsung Meriah di SMAN 1 Nogosari";

const openShareModal = () => {
  isShareModalOpen.value = true;
};

const closeShareModal = () => {
  isShareModalOpen.value = false;
};

const popularNews = [
  {
    id: 2,
    title: "Siswa SMAN 1 Meraih Juara 1 Olimpiade Sains Tingkat Nasional",
    date: "10 Jan 2026",
    image: "https://images.unsplash.com/photo-1567057419565-4349c49d8a04?q=80&w=800",
    views: 3420,
  },
  {
    id: 3,
    title: "Jadwal Pelaksanaan Ujian Akhir Semester Ganjil 2025/2026",
    date: "01 Des 2025",
    image: "https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=800",
    views: 890,
  },
  {
    id: 4,
    title: "Peresmian Laboratorium Komputer Baru",
    date: "05 Feb 2026",
    image: "https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800",
    views: 2105,
  },
];

onMounted(() => {
  createIcons({ icons });
  window.scrollTo({ top: 0, behavior: "smooth" });
});

onUpdated(() => {
  nextTick(() => {
    createIcons({ icons });
  });
});
</script>

<template>
  <div class="pt-24 pb-16 bg-gray-50 dark:bg-slate-900 min-h-screen">
    <div class="container mx-auto max-w-7xl px-6">
      <!-- Breadcrumb -->
      <div class="mb-6 md:mb-8">
        <Breadcrumb
          :items="[
            { name: 'Beranda', link: '/', icon: 'home' },
            { name: 'Berita', link: '/berita' },
            { name: 'Kegiatan' },
          ]"
        />
      </div>

      <div class="flex flex-col lg:flex-row gap-10">
        <!-- KIRI: Konten Utama Artikel -->
        <main
          class="w-full lg:w-2/3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden"
        >
          <!-- Header Artikel -->
          <div class="p-6 md:p-10 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-3 mb-4">
              <span
                class="px-3 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400 text-xs font-bold rounded-md uppercase tracking-wider"
              >
                Kegiatan
              </span>
              <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                <i data-lucide="clock" class="w-3.5 h-3.5 mr-1.5"></i> 3 menit baca
              </span>
            </div>

            <h1
              class="text-2xl md:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight mb-6"
            >
              {{ articleTitle }}
            </h1>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <!-- Profil Penulis -->
              <div class="flex items-center gap-3">
                <div
                  class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xl shrink-0"
                >
                  TR
                </div>
                <div>
                  <div class="font-bold text-gray-900 dark:text-white text-sm">
                    Tim Redaksi SMAN 1
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">
                    24 November 2025 • 1250 Tayangan
                  </div>
                </div>
              </div>

              <!-- Tombol Aksi -->
              <div class="flex items-center gap-2">
                <button
                  @click="openShareModal"
                  class="p-2 rounded-full bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-gray-300 hover:bg-blue-50 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                >
                  <i data-lucide="share-2" class="w-5 h-5"></i>
                </button>
                <button
                  class="p-2 rounded-full bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-gray-300 hover:bg-red-50 hover:text-red-500 dark:hover:text-red-400 transition-colors"
                >
                  <i data-lucide="bookmark" class="w-5 h-5"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Featured Image -->
          <div class="w-full h-64 md:h-96 relative bg-gray-200 dark:bg-slate-700">
            <img
              src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1200"
              alt="Peringatan Hari Guru"
              class="w-full h-full object-cover"
            />
            <div
              class="absolute bottom-0 right-0 bg-black/60 backdrop-blur-sm text-white text-[10px] px-3 py-1"
            >
              Sumber Gambar: Dokumentasi OSIS
            </div>
          </div>

          <!-- Isi Artikel -->
          <article
            class="p-6 md:p-10 text-gray-700 dark:text-gray-300 leading-relaxed space-y-6 text-base md:text-lg text-justify"
          >
            <p>
              <span
                class="text-4xl font-bold float-left mr-2 mt-1 text-blue-600 dark:text-blue-400 font-serif"
                >S</span
              >MAN 1 Nogosari kembali menggelar peringatan Hari Guru Nasional dengan
              sangat meriah dan penuh haru pada Senin (24/11). Acara yang diselenggarakan
              di lapangan utama sekolah ini tidak hanya dihadiri oleh jajaran guru dan
              staf, tetapi juga diikuti dengan antusias oleh seluruh siswa dari kelas X
              hingga XII.
            </p>
            <p>
              Peringatan tahun ini mengusung tema
              <strong>"Guru Inovatif, Generasi Kreatif"</strong>. Acara dimulai dengan
              upacara bendera yang dipimpin langsung oleh Bapak Kepala Sekolah,
              dilanjutkan dengan prosesi pelepasan balon ke udara sebagai simbol cita-cita
              tinggi yang harus diraih oleh para siswa melalui bimbingan para guru.
            </p>

            <blockquote
              class="border-l-4 border-blue-500 bg-blue-50 dark:bg-slate-700/50 p-6 rounded-r-xl italic text-gray-800 dark:text-gray-200 my-8 shadow-sm"
            >
              "Seorang guru itu bukan sekadar mentransfer ilmu, tetapi juga menanamkan
              karakter dan nilai kehidupan yang akan terus membekas di hati anak-anak kita
              selamanya."
              <footer class="mt-3 text-sm font-semibold text-blue-600 dark:text-blue-400">
                — Dr. H. Budi Santoso, M.Pd
              </footer>
            </blockquote>

            <p>
              Puncak acara dimeriahkan dengan berbagai penampilan pentas seni dari
              perwakilan masing-masing kelas. Ada yang membawakan puisi, teaterikal
              singkat, hingga nyanyian paduan suara yang dipersembahkan khusus untuk para
              pahlawan tanpa tanda jasa tersebut. Suasana seketika berubah haru ketika
              beberapa perwakilan OSIS memberikan bunga mawar kepada setiap guru yang
              hadir.
            </p>

            <!-- Galeri Inline (Beberapa Gambar Pendukung) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 my-8">
              <img
                src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=600"
                class="rounded-xl w-full h-48 object-cover shadow-sm"
                alt="Pentas Seni"
              />
              <img
                src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=600"
                class="rounded-xl w-full h-48 object-cover shadow-sm"
                alt="Penampilan Siswa"
              />
              <p class="col-span-full text-center text-xs text-gray-500 italic mt-2">
                Keseruan penampilan pentas seni siswa SMAN 1 Nogosari.
              </p>
            </div>

            <p>
              Sebagai penutup, sekolah memberikan apresiasi dan piagam penghargaan kepada
              sejumlah "Guru Favorit" dan "Guru Terinovatif" berdasarkan hasil *voting*
              yang dilakukan oleh siswa selama sepekan terakhir. Acara kemudian ditutup
              dengan pemotongan tumpeng dan makan bersama yang mempererat tali silaturahmi
              seluruh warga sekolah.
            </p>
          </article>

          <!-- Tags -->
          <div
            class="px-6 md:px-10 pb-10 flex flex-wrap gap-2 border-b border-gray-100 dark:border-slate-700"
          >
            <a
              href="#"
              class="px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded hover:bg-blue-100 hover:text-blue-600 transition-colors"
              >#HariGuru</a
            >
            <a
              href="#"
              class="px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded hover:bg-blue-100 hover:text-blue-600 transition-colors"
              >#Pendidikan</a
            >
            <a
              href="#"
              class="px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded hover:bg-blue-100 hover:text-blue-600 transition-colors"
              >#KegiatanSiswa</a
            >
          </div>
        </main>

        <!-- KANAN: Sidebar -->
        <aside class="w-full lg:w-1/3 space-y-8">
          <!-- Author Profile Widget -->
          <div
            class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 text-center"
          >
            <div
              class="w-20 h-20 mx-auto rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-2xl mb-4 border-4 border-white shadow-sm"
            >
              TR
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
              Tim Redaksi
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
              Pengelola informasi dan publikasi resmi SMA Negeri 1 Nogosari.
            </p>
            <button
              class="w-full py-2 bg-blue-50 text-blue-600 dark:bg-slate-700 dark:text-blue-400 rounded-lg text-sm font-semibold hover:bg-blue-100 transition-colors"
            >
              Lihat Artikel Lainnya
            </button>
          </div>

          <!-- Berita Populer Widget -->
          <div
            class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700"
          >
            <h3
              class="text-lg font-bold text-gray-900 dark:text-white mb-5 flex items-center"
            >
              <i data-lucide="trending-up" class="w-5 h-5 mr-2 text-blue-500"></i>
              Terpopuler
            </h3>
            <div class="space-y-5">
              <router-link
                to="/artikel"
                v-for="(news, index) in popularNews"
                :key="news.id"
                class="flex items-start gap-4 group"
              >
                <div
                  class="w-20 h-20 shrink-0 rounded-lg overflow-hidden shadow-sm relative"
                >
                  <img
                    :src="news.image"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                  />
                  <div
                    class="absolute top-0 left-0 bg-blue-600 text-white w-5 h-5 flex items-center justify-center text-[10px] font-bold rounded-br-lg shadow-sm z-10"
                  >
                    {{ index + 1 }}
                  </div>
                </div>
                <div class="flex flex-col justify-center flex-1 py-0.5">
                  <h4
                    class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-snug line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mb-1"
                  >
                    {{ news.title }}
                  </h4>
                  <span class="text-[10px] font-medium text-gray-400 flex items-center">
                    <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                    {{ news.date }}
                  </span>
                </div>
              </router-link>
            </div>
          </div>
        </aside>
      </div>
    </div>

    <!-- Share Modal Component -->
    <ShareModal
      :is-open="isShareModalOpen"
      :title="articleTitle"
      @close="closeShareModal"
    />
  </div>
</template>
