<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from "vue";
import { createIcons, icons } from "lucide";
import PageHeader from "@/components/PageHeader.vue";

const timelineRef = ref(null);
const lineHeight = ref("0%");
const currentProgress = ref(0);

const handleScroll = () => {
  if (!timelineRef.value) return;

  const rect = timelineRef.value.getBoundingClientRect();
  const windowHeight = window.innerHeight;

  // Animasi terpicu (garis menjalar) saat konten masuk 60% layar dari atas
  const startOffset = windowHeight * 0.6;
  const scrollPosition = startOffset - rect.top;

  let progress = scrollPosition / rect.height;
  progress = Math.max(0, Math.min(1, progress)); // Membatasi nilai antara 0 - 1
  lineHeight.value = `${progress * 100}%`;
  currentProgress.value = progress;
};

const timeline = ref([
  {
    year: "1985",
    title: "Pendirian & Peresmian SMAN 1",
    icon: "building",
    image: "https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?q=80&w=800",
    description:
      "Sekolah ini resmi didirikan pada tanggal 17 Agustus 1985 berdasarkan SK Menteri Pendidikan. Pada awalnya, sekolah hanya memiliki 3 ruang kelas dengan 120 siswa angkatan pertama dan menumpang di gedung SMP terdekat selama proses pembangunan gedung utama berlangsung.",
  },
  {
    year: "1992",
    title: "Pembangunan Gedung Utama",
    icon: "hammer",
    image: "https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=800",
    description:
      "Pembangunan gedung sekolah mandiri akhirnya selesai dan diresmikan oleh Gubernur. Di tahun ini, SMAN 1 mulai menempati lokasi saat ini dengan fasilitas yang diperluas, meliputi 12 ruang kelas, ruang guru, dan lapangan olahraga serbaguna.",
  },
  {
    year: "2005",
    title: "Akreditasi A & Prestasi Nasional",
    icon: "award",
    image: "https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=800",
    description:
      "Berkat dedikasi seluruh civitas akademika, SMAN 1 berhasil meraih akreditasi A (Sangat Baik). Pada tahun yang sama, tim cerdas cermat sekolah berhasil membawa pulang piala Juara 1 tingkat Nasional untuk pertama kalinya.",
  },
  {
    year: "2015",
    title: "Era Transformasi Digital",
    icon: "monitor",
    image: "https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=800",
    description:
      "Menjawab tantangan abad 21, sekolah mulai mengintegrasikan teknologi ke dalam pembelajaran. Pembangunan laboratorium komputer modern, perpustakaan digital, serta pengadaan proyektor dan Wi-Fi di seluruh area sekolah mulai direalisasikan.",
  },
  {
    year: "2021",
    title: "Sekolah Adiwiyata & Peduli Lingkungan",
    icon: "leaf",
    image: "https://images.unsplash.com/photo-1466692476868-aef1dfb1e736?q=80&w=800",
    description:
      "Berkomitmen pada lingkungan yang asri, sekolah memenangkan penghargaan Sekolah Adiwiyata tingkat Provinsi. Program bank sampah, taman hidroponik, dan ruang hijau terpadu menjadi identitas baru SMAN 1.",
  },
  {
    year: "2026",
    title: "Pelopor Kurikulum Merdeka",
    icon: "rocket",
    image: "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800",
    description:
      "Hari ini, SMAN 1 Nogosari terus melesat menjadi sekolah percontohan dalam implementasi Kurikulum Merdeka. Dengan lebih dari 1100 siswa, kami terus melahirkan lulusan yang cerdas, berkarakter, dan berdaya saing global.",
  },
]);

const splitSentences = (text) => {
  // Memecah teks per kalimat (berdasarkan titik/tanya/seru) beserta spasi setelahnya
  return text.match(/[^.!?]+[.!?]+\s*/g) || [text];
};

onMounted(() => {
  window.addEventListener("scroll", handleScroll);
  handleScroll(); // Inisialisasi kalkulasi awal pada saat dimuat

  // Intersection Observer untuk efek pop-up kartu timeline
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add(
            "opacity-100",
            "translate-y-0",
            "scale-100",
            "lg:translate-x-0"
          );
          entry.target.classList.remove(
            "opacity-0",
            "translate-y-10",
            "scale-95",
            "lg:-translate-x-20",
            "lg:translate-x-20"
          );

          // Animasi fade-in berurutan untuk setiap kalimat di dalam card
          const sentences = entry.target.querySelectorAll(".fade-sentence");
          sentences.forEach((el, idx) => {
            setTimeout(() => {
              el.classList.add("opacity-100");
              el.classList.remove("opacity-0");
            }, 400 + idx * 500); // Menunggu card pop-up (400ms), lalu jeda 500ms per kalimat
          });

          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 } // Terpicu saat 15% elemen terlihat
  );

  nextTick(() => {
    createIcons({ icons });
    document.querySelectorAll(".fade-on-scroll").forEach((el) => {
      observer.observe(el);
    });
  });
});

onBeforeUnmount(() => {
  window.removeEventListener("scroll", handleScroll);
});
</script>

<template>
  <div>
    <PageHeader
      badge="Profil Sekolah"
      title="Tentang Kami & Sejarah"
      description="Mengenal lebih dekat profil SMAN 1 Nogosari dan menyusuri jejak langkah perjuangan serta prestasi dari masa ke masa."
    />

    <!-- Profil Singkat Section -->
    <section class="pt-12 md:pt-20 pb-4 md:pb-10 bg-white dark:bg-slate-900">
      <div class="container mx-auto max-w-6xl px-6">
        <div
          class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out flex flex-col lg:flex-row items-center gap-10 lg:gap-16"
        >
          <!-- Gambar Profil -->
          <div
            class="w-full lg:w-5/12 h-72 sm:h-80 lg:h-[450px] relative rounded-3xl overflow-hidden shadow-xl border border-gray-100 dark:border-slate-700"
          >
            <img
              src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800"
              class="absolute inset-0 w-full h-full object-cover"
              alt="Gedung Sekolah"
            />
            <div class="absolute inset-0 bg-blue-900/20 mix-blend-multiply"></div>
            <!-- Badge Overlay -->
            <div
              class="absolute bottom-4 left-4 bg-white/95 dark:bg-slate-800/95 backdrop-blur-sm px-4 py-2.5 rounded-2xl shadow-md border border-white/20 dark:border-slate-700"
            >
              <span
                class="text-blue-700 dark:text-blue-400 font-bold text-sm flex items-center"
              >
                <i data-lucide="check-circle-2" class="w-4 h-4 mr-1.5"></i> Terakreditasi
                A (Sangat Baik)
              </span>
            </div>
          </div>

          <!-- Info & Fakta -->
          <div class="w-full lg:w-7/12 flex flex-col justify-center lg:py-6">
            <span
              class="text-lg md:text-xl font-bold text-blue-600 dark:text-blue-400 tracking-wider mb-2"
              style="font-family: 'Kalam', cursive"
              >Mengenal Lebih Dekat</span
            >
            <h2 class="text-3xl md:text-4xl font-bold text-blue-950 dark:text-white mb-5">
              Profil Singkat Sekolah
            </h2>
            <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed text-justify">
              SMA Negeri 1 Nogosari adalah lembaga pendidikan menengah atas yang
              berdedikasi tinggi dalam mencetak generasi penerus bangsa yang unggul,
              cerdas, dan berkarakter. Berada di lingkungan yang asri, kami senantiasa
              berupaya memberikan suasana belajar yang kondusif, didukung oleh tenaga
              pendidik profesional dan fasilitas yang terus berkembang mengikuti zaman.
            </p>

            <!-- Grid Fakta -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div class="flex items-start group">
                <div
                  class="w-11 h-11 rounded-full bg-blue-50 dark:bg-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors"
                >
                  <i data-lucide="hash" class="w-5 h-5"></i>
                </div>
                <div>
                  <h4 class="text-sm font-bold text-gray-900 dark:text-white">NPSN</h4>
                  <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">20301234</p>
                </div>
              </div>
              <div class="flex items-start group">
                <div
                  class="w-11 h-11 rounded-full bg-blue-50 dark:bg-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors"
                >
                  <i data-lucide="calendar-days" class="w-5 h-5"></i>
                </div>
                <div>
                  <h4 class="text-sm font-bold text-gray-900 dark:text-white">
                    Tahun Berdiri
                  </h4>
                  <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    17 Agustus 1985
                  </p>
                </div>
              </div>
              <div class="flex items-start group">
                <div
                  class="w-11 h-11 rounded-full bg-blue-50 dark:bg-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors"
                >
                  <i data-lucide="map-pin" class="w-5 h-5"></i>
                </div>
                <div>
                  <h4 class="text-sm font-bold text-gray-900 dark:text-white">Lokasi</h4>
                  <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Nogosari, Kab. Boyolali
                  </p>
                </div>
              </div>
              <div class="flex items-start group">
                <div
                  class="w-11 h-11 rounded-full bg-blue-50 dark:bg-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors"
                >
                  <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <div>
                  <h4 class="text-sm font-bold text-gray-900 dark:text-white">Status</h4>
                  <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Sekolah Negeri
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Timeline Section -->
    <section
      class="pt-6 pb-12 md:pb-24 min-h-screen relative overflow-hidden bg-gray-50 dark:bg-slate-950"
    >
      <div
        class="container relative z-10 mx-auto px-5 sm:px-8 md:px-14 lg:px-8 w-full max-w-full"
      >
        <!-- Bridging Title -->
        <div
          class="mb-10 md:mb-18 text-center fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out pt-6 md:pt-12"
        >
          <div class="inline-flex items-center justify-center space-x-2 mb-4">
            <span
              class="h-px w-8 sm:w-12 bg-blue-600 dark:bg-blue-400 rounded-full"
            ></span>
            <span
              class="text-blue-600 dark:text-blue-400 font-bold text-lg sm:text-xl tracking-wider"
              style="font-family: 'Kalam', cursive"
              >Lini Masa</span
            >
            <span
              class="h-px w-8 sm:w-12 bg-blue-600 dark:bg-blue-400 rounded-full"
            ></span>
          </div>
          <h2
            class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-blue-950 dark:text-white mb-4 sm:mb-6 tracking-tight"
          >
            Jejak Langkah Kami
          </h2>
          <p
            class="text-gray-600 dark:text-gray-400 text-sm sm:text-base md:text-lg max-w-2xl mx-auto leading-relaxed"
          >
            Menelusuri kembali tonggak sejarah dan perjalanan panjang berdirinya institusi
            pendidikan kami hingga menjadi seperti sekarang.
          </p>
        </div>

        <div class="relative wrap overflow-hidden h-full" ref="timelineRef">
          <!-- Garis Tengah Timeline (Latar Belakang) -->
          <div
            class="absolute z-0 w-1 bg-gray-200 dark:bg-slate-700 h-full left-6 sm:left-8 lg:left-1/2 transform -translate-x-1/2 rounded-full"
          >
            <!-- Garis Animasi Menjalar (Foreground) -->
            <div
              class="absolute top-0 left-0 w-full bg-blue-600 dark:bg-blue-500 rounded-full transition-all duration-200 ease-out"
              :style="{ height: lineHeight }"
            ></div>
          </div>

          <!-- Looping Data Lini Masa -->
          <div
            v-for="(item, index) in timeline"
            :key="index"
            class="fade-on-scroll opacity-0 translate-y-10 scale-95 transition-all duration-1000 ease-out relative z-10 flex items-center w-full mb-12 last:mb-0 group"
            :class="
              index % 2 === 0
                ? 'lg:flex-row-reverse lg:-translate-x-20'
                : 'lg:flex-row lg:translate-x-20'
            "
          >
            <!-- Ruang Kosong untuk Penyeimbang Grid Desktop -->
            <div class="hidden lg:block lg:w-1/2"></div>

            <!-- Ikon Lingkaran Timeline -->
            <div
              class="absolute left-6 sm:left-8 lg:left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full border-4 border-white dark:border-slate-800 shadow-lg z-20 group-hover:scale-110 transition-all duration-500"
              :class="
                currentProgress >= index / timeline.length
                  ? 'bg-blue-600 dark:bg-blue-500 text-white shadow-md'
                  : 'bg-gray-200 dark:bg-slate-700 text-gray-500 dark:text-gray-400'
              "
            >
              <i :data-lucide="item.icon" class="w-5 h-5 md:w-6 md:h-6"></i>
            </div>

            <!-- Kartu Konten -->
            <div
              class="ml-16 sm:ml-20 lg:ml-0 flex-1 lg:flex-none lg:w-1/2"
              :class="index % 2 === 0 ? 'lg:pr-12 xl:pr-16' : 'lg:pl-12 xl:pl-16'"
            >
              <div
                class="group/card relative hover:-translate-y-1.5 transition-transform duration-400"
              >
                <div
                  class="hidden lg:block absolute top-1/2 -translate-y-1/2 w-6 h-6 bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 transform rotate-45 z-20"
                  :class="
                    index % 2 === 0
                      ? '-right-3 border-t border-r'
                      : '-left-3 border-b border-l'
                  "
                ></div>

                <div
                  class="relative z-10 flex flex-col bg-white dark:bg-slate-800 rounded-xl shadow-md group-hover/card:shadow-2xl border border-gray-200 dark:border-slate-700 overflow-hidden transition-shadow duration-400 p-6 md:p-8 lg:p-10"
                >
                  <!-- Badge Tahun -->
                  <div class="mb-5">
                    <div
                      class="block w-full px-5 py-1 bg-blue-600 dark:bg-blue-500 text-white font-bold text-md sm:text-lg tracking-wider rounded-2xl shadow-md text-left"
                      style="font-family: 'Kalam', cursive"
                    >
                      Tahun {{ item.year }}
                    </div>
                  </div>

                  <!-- Teks/Deskripsi -->
                  <h3
                    class="text-xl md:text-2xl font-bold text-blue-950 dark:text-white mb-3 leading-tight group-hover/card:text-blue-600 dark:group-hover/card:text-blue-400 transition-colors"
                  >
                    {{ item.title }}
                  </h3>
                  <p
                    class="text-gray-600 dark:text-gray-400 text-sm md:text-base leading-relaxed text-justify"
                  >
                    <span
                      v-for="(sentence, sIdx) in splitSentences(item.description)"
                      :key="sIdx"
                      class="fade-sentence opacity-0 transition-opacity duration-1000 ease-in-out"
                    >
                      {{ sentence }}
                    </span>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");
</style>
