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
            "md:translate-x-0"
          );
          entry.target.classList.remove(
            "opacity-0",
            "translate-y-10",
            "scale-95",
            "md:-translate-x-20",
            "md:translate-x-20"
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
      title="Sejarah & Perjalanan"
      description="Dari awal yang sederhana hingga menjadi institusi pendidikan unggulan. Mari menyusuri jejak langkah perjuangan dan prestasi SMAN 1 Nogosari dari masa ke masa."
    />

    <!-- Timeline Section -->
    <section
      class="py-16 md:py-24 min-h-screen relative overflow-hidden bg-fixed bg-center bg-cover"
      style="
        background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1600&auto=format&fit=crop');
      "
    >
      <!-- Parallax Overlay -->
      <div
        class="absolute inset-0 bg-gray-50/90 dark:bg-slate-900/90 backdrop-blur-[2px] transition-colors duration-500"
      ></div>

      <div class="container relative z-10 mx-auto px-6 max-w-7xl">
        <div class="relative wrap overflow-hidden p-2 md:p-10 h-full" ref="timelineRef">
          <!-- Garis Tengah Timeline (Latar Belakang) -->
          <div
            class="absolute z-0 w-1 bg-gray-200 dark:bg-slate-700 h-full left-8 md:left-1/2 transform md:-translate-x-1/2 rounded-full"
          >
            <!-- Garis Animasi Menjalar (Foreground) -->
            <div
              class="absolute top-0 left-0 w-full bg-green-500 dark:bg-green-400 shadow-[0_0_12px_rgba(34,197,94,0.8)] rounded-full transition-all duration-200 ease-out"
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
                ? 'md:flex-row-reverse md:-translate-x-20'
                : 'md:flex-row md:translate-x-20'
            "
          >
            <!-- Ruang Kosong untuk Penyeimbang Grid Desktop -->
            <div class="hidden md:block md:w-1/2"></div>

            <!-- Ikon Lingkaran Timeline -->
            <div
              class="absolute left-8 md:left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full border-4 border-white dark:border-slate-800 shadow-lg z-20 group-hover:scale-110 transition-all duration-500"
              :class="
                currentProgress >= index / timeline.length
                  ? 'bg-green-500 dark:bg-green-400 text-white shadow-[0_0_15px_rgba(34,197,94,0.8)]'
                  : 'bg-gray-200 dark:bg-slate-700 text-gray-500 dark:text-gray-400'
              "
            >
              <i :data-lucide="item.icon" class="w-5 h-5 md:w-6 md:h-6"></i>
            </div>

            <!-- Kartu Konten -->
            <div
              class="ml-20 md:ml-0 w-full md:w-1/2"
              :class="index % 2 === 0 ? 'md:pr-12 lg:pr-16' : 'md:pl-12 lg:pl-16'"
            >
              <div
                class="group/card relative hover:-translate-y-1.5 transition-transform duration-400"
              >
                <div
                  class="hidden md:block absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white dark:bg-slate-800 border-gray-100 dark:border-slate-700 transform rotate-45 z-20"
                  :class="
                    index % 2 === 0
                      ? '-right-2 border-t border-r'
                      : '-left-2 border-b border-l'
                  "
                ></div>

                <div
                  class="relative z-10 flex flex-col items-stretch bg-white dark:bg-slate-800 rounded-2xl shadow-md group-hover/card:shadow-2xl border border-gray-100 dark:border-slate-700 overflow-hidden transition-shadow duration-400"
                  :class="index % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse'"
                >
                  <!-- Gambar Dokumentasi Timeline -->
                  <div
                    class="relative w-full lg:w-6/12 shrink-0 h-48 lg:h-auto min-h-[200px]"
                  >
                    <img
                      :src="item.image"
                      class="absolute inset-0 w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-700"
                      :alt="item.title"
                    />
                    <div class="absolute top-4 left-4">
                      <span
                        class="inline-block px-3 py-1.5 bg-blue-600/90 backdrop-blur-sm text-white font-bold text-xs md:text-sm rounded-lg shadow-sm border border-white/20"
                        >{{ item.year }}</span
                      >
                    </div>
                  </div>

                  <!-- Teks/Deskripsi -->
                  <div
                    class="w-full lg:w-7/12 flex flex-col justify-center p-5 md:p-6 lg:p-8"
                  >
                    <h3
                      class="text-lg md:text-xl font-bold text-blue-950 dark:text-white mb-2 leading-tight group-hover/card:text-blue-600 dark:group-hover/card:text-blue-400 transition-colors"
                    >
                      {{ item.title }}
                    </h3>
                    <p
                      class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed text-justify"
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
      </div>
    </section>
  </div>
</template>
