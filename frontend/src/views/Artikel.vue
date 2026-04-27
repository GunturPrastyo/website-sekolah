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

const relatedArticles = [
  {
    id: 5,
    title: "Kunjungan Edukasi Sejarah ke Museum Nasional Jakarta",
    date: "20 Mar 2026",
    image: "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=600",
    category: "Kegiatan",
  },
  {
    id: 6,
    title: "Pendaftaran Peserta Didik Baru (PPDB) Jalur Prestasi Dibuka",
    date: "15 Apr 2026",
    image: "https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=600",
    category: "Pengumuman",
  },
];

const otherAuthors = [
  {
    id: 1,
    name: "Bapak Ahmad Fauzi",
    role: "Guru Bahasa Indonesia",
    initials: "AF",
    color: "bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400",
    articles: 12,
  },
  {
    id: 2,
    name: "Ibu Siti Aminah",
    role: "Pembina OSIS",
    initials: "SA",
    color: "bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-400",
    articles: 8,
  },
  {
    id: 3,
    name: "Rizky Pratama",
    role: "Ketua Ekskul Jurnalistik",
    initials: "RP",
    color: "bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400",
    articles: 24,
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

          <!-- Artikel Terkait -->
          <div class="p-6 md:p-10 border-b border-gray-100 dark:border-slate-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
              Artikel Terkait
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <router-link
                to="/artikel"
                v-for="article in relatedArticles"
                :key="article.id"
                class="group flex flex-col bg-gray-50 dark:bg-slate-700/30 rounded-xl overflow-hidden border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md transition-all"
              >
                <div class="h-40 overflow-hidden relative shrink-0">
                  <img
                    :src="article.image"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                  />
                  <div
                    class="absolute top-3 left-3 bg-blue-600/90 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm"
                  >
                    {{ article.category }}
                  </div>
                </div>
                <div class="p-4 flex flex-col justify-between flex-1">
                  <h4
                    class="text-sm font-bold text-gray-900 dark:text-white leading-snug line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mb-3"
                  >
                    {{ article.title }}
                  </h4>
                  <div
                    class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center mt-auto"
                  >
                    <i data-lucide="calendar" class="w-3.5 h-3.5 mr-1.5"></i>
                    {{ article.date }}
                  </div>
                </div>
              </router-link>
            </div>
          </div>

          <!-- Area Komentar -->
          <div class="p-6 md:p-10 bg-gray-50/50 dark:bg-slate-800/50">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
              Tinggalkan Komentar
            </h3>

            <!-- Form Komentar Statis -->
            <form @submit.prevent class="space-y-4 mb-10">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Nama Lengkap <span class="text-red-500">*</span></label
                  >
                  <input
                    type="text"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                    placeholder="Nama Anda"
                    required
                  />
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Email <span class="text-red-500">*</span></label
                  >
                  <input
                    type="email"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                    placeholder="alamat@email.com"
                    required
                  />
                </div>
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Komentar <span class="text-red-500">*</span></label
                >
                <textarea
                  rows="4"
                  class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                  placeholder="Tulis komentar Anda di sini..."
                  required
                ></textarea>
              </div>
              <button
                type="submit"
                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-colors flex items-center"
              >
                Kirim Komentar <i data-lucide="send" class="w-4 h-4 ml-2"></i>
              </button>
            </form>

            <!-- Daftar Komentar (Dummy) -->
            <div class="space-y-6">
              <h4
                class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-3"
              >
                1 Komentar
              </h4>

              <div class="flex gap-4">
                <div
                  class="w-10 h-10 rounded-full bg-blue-100 dark:bg-slate-700 flex items-center justify-center shrink-0"
                >
                  <i
                    data-lucide="user"
                    class="w-5 h-5 text-blue-600 dark:text-gray-400"
                  ></i>
                </div>
                <div>
                  <div class="flex items-center gap-2 mb-1">
                    <span class="font-bold text-gray-900 dark:text-white text-sm"
                      >Alumni Sukses 2010</span
                    >
                    <span class="text-xs text-gray-500 dark:text-gray-400"
                      >25 November 2025</span
                    >
                  </div>
                  <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                    Acara yang sangat luar biasa! Bangga menjadi bagian dari keluarga
                    besar SMAN 1 Nogosari. Semoga guru-guru selalu diberikan kesehatan dan
                    terus menginspirasi.
                  </p>
                  <button
                    class="text-xs font-semibold text-blue-600 dark:text-blue-400 mt-2 hover:underline"
                  >
                    Balas
                  </button>
                </div>
              </div>
            </div>
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

            <!-- Penulis Lainnya -->
            <div
              class="mt-6 pt-5 border-t border-gray-100 dark:border-slate-700 text-left"
            >
              <h4
                class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center"
              >
                <i data-lucide="users" class="w-4 h-4 mr-1.5 text-gray-400"></i> Penulis
                Lainnya
              </h4>
              <div class="flex flex-wrap gap-2">
                <a
                  href="#"
                  v-for="author in otherAuthors"
                  :key="author.id"
                  class="inline-flex items-center px-3 py-1.5 bg-gray-50 hover:bg-gray-100 dark:bg-slate-700/50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-lg text-xs font-medium transition-colors border border-gray-200 dark:border-slate-600 group"
                >
                  {{ author.name }}
                  <span
                    class="ml-1.5 inline-flex items-center gap-1 px-1.5 py-0.5 bg-gray-200 group-hover:bg-blue-100 dark:bg-slate-600 dark:group-hover:bg-blue-900/40 text-gray-600 group-hover:text-blue-600 dark:text-gray-400 dark:group-hover:text-blue-400 rounded-md text-[10px] font-bold transition-colors"
                  >
                    <i data-lucide="file-text" class="w-3 h-3"></i>
                    {{ author.articles }}
                  </span>
                </a>
              </div>
            </div>
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
