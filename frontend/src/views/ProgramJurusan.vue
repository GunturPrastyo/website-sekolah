<script setup>
import { ref, onMounted, nextTick } from "vue";
import { createIcons, icons } from "lucide";
import PageHeader from "@/components/PageHeader.vue";

const programs = ref([
  {
    id: "ipa",
    title: "Ilmu Pengetahuan Alam (IPA)",
    badge: "Sains & Teknologi",
    color: "blue",
    bgClass: "bg-blue-50 dark:bg-blue-900/20",
    sectionBgClass: "bg-blue-900 dark:bg-blue-950",
    textClass: "text-blue-600 dark:text-blue-400",
    borderClass: "border-blue-200 dark:border-blue-800",
    decorationClass: "bg-blue-600 dark:bg-blue-500",
    image: "https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800",
    pattern: "https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=1200",
    description:
      "Program peminatan IPA berfokus pada ilmu-ilmu eksakta dan alam. Siswa akan diajak untuk berpikir logis, analitis, dan sistematis dalam memecahkan berbagai fenomena alam dan teknologi.",
    subjects: [
      "Fisika Lanjutan",
      "Kimia Terapan",
      "Biologi Terpadu",
      "Matematika Peminatan",
    ],
    careers: [
      "Dokter / Tenaga Medis",
      "Insinyur / Engineer",
      "Ilmuwan / Peneliti",
      "Apoteker",
    ],
  },
  {
    id: "ips",
    title: "Ilmu Pengetahuan Sosial (IPS)",
    badge: "Sosial & Humaniora",
    color: "blue",
    bgClass: "bg-blue-50 dark:bg-blue-900/20",
    sectionBgClass: "bg-blue-900 dark:bg-blue-950",
    textClass: "text-blue-600 dark:text-blue-400",
    borderClass: "border-blue-200 dark:border-blue-800",
    decorationClass: "bg-blue-600 dark:bg-blue-500",
    image: "https://images.unsplash.com/photo-1551836022-deb4988cc6c0?q=80&w=800",
    pattern: "https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1200",
    description:
      "Peminatan IPS membekali siswa dengan pemahaman mendalam tentang dinamika sosial, ekonomi, dan sejarah. Program ini sangat cocok bagi siswa yang memiliki minat pada hubungan antar manusia dan kebijakan publik.",
    subjects: ["Ekonomi & Akuntansi", "Sosiologi", "Geografi", "Sejarah Peminatan"],
    careers: [
      "Ekonom / Akuntan",
      "Pengacara / Diplomat",
      "Pengusaha / Entrepreneur",
      "Psikolog Sosial",
    ],
  },
  {
    id: "bahasa",
    title: "Bahasa dan Budaya",
    badge: "Sastra & Komunikasi",
    color: "blue",
    bgClass: "bg-blue-50 dark:bg-blue-900/20",
    sectionBgClass: "bg-blue-900 dark:bg-blue-950",
    textClass: "text-blue-600 dark:text-blue-400",
    borderClass: "border-blue-200 dark:border-blue-800",
    decorationClass: "bg-blue-600 dark:bg-blue-500",
    image: "https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=800",
    pattern: "https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=1200",
    description:
      "Program Bahasa diperuntukkan bagi siswa yang mencintai literatur, komunikasi, dan linguistik. Siswa akan mempelajari keindahan bahasa dari berbagai negara serta kebudayaan yang mengiringinya.",
    subjects: [
      "Sastra Indonesia",
      "Bahasa Inggris Lanjut",
      "Bahasa Asing (Jepang/Jerman)",
      "Antropologi Budaya",
    ],
    careers: [
      "Diplomat / Duta Besar",
      "Penerjemah / Translator",
      "Jurnalis / Penulis",
      "Pemandu Wisata Internasional",
    ],
  },
  {
    id: "informatika",
    title: "Informatika & Komputer",
    badge: "Teknologi Digital",
    color: "blue",
    bgClass: "bg-blue-50 dark:bg-blue-900/20",
    sectionBgClass: "bg-blue-900 dark:bg-blue-950",
    textClass: "text-blue-600 dark:text-blue-400",
    borderClass: "border-blue-200 dark:border-blue-800",
    decorationClass: "bg-blue-600 dark:bg-blue-500",
    image: "https://images.unsplash.com/photo-1550745165-9bc0b252726a?q=80&w=800",
    pattern: "https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1200",
    description:
      "Merupakan program unggulan baru yang merespon tantangan era Revolusi Industri 4.0. Mempelajari logika komputasional, pemrograman, dan desain perangkat lunak secara intensif.",
    subjects: [
      "Algoritma & Pemrograman",
      "Jaringan Komputer Dasar",
      "Desain Grafis & UI/UX",
      "Kecerdasan Buatan Dasar",
    ],
    careers: [
      "Software Engineer",
      "Data Scientist",
      "System Analyst",
      "Cybersecurity Specialist",
    ],
  },
]);

onMounted(() => {
  nextTick(() => {
    createIcons({ icons });

    // Intersection Observer untuk efek animasi fade-up pada saat scroll
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("opacity-100", "translate-y-0", "translate-x-0");
            entry.target.classList.remove(
              "opacity-0",
              "translate-y-10",
              "-translate-x-10",
              "translate-x-10"
            );
            observer.unobserve(entry.target); // Hentikan observasi agar animasi hanya berjalan 1x
          }
        });
      },
      { threshold: 0.1 } // Animasi terpicu saat 10% elemen terlihat di layar
    );

    document.querySelectorAll(".fade-on-scroll").forEach((el) => {
      observer.observe(el);
    });
  });
});
</script>

<template>
  <div>
    <PageHeader
      badge="Akademik"
      title="Program Jurusan Unggulan"
      description="Temukan minat, bakat, dan potensi terbaikmu melalui berbagai pilihan peminatan yang dirancang khusus untuk mempersiapkan masa depanmu di dunia perkuliahan maupun industri."
    />

    <!-- Program Details Section -->
    <div
      class="min-h-screen flex flex-col bg-white dark:bg-slate-900 transition-colors duration-700"
    >
      <!-- Render Tiap Jurusan dengan Selang-seling (Alternate Layout & Background) -->
      <section
        v-for="(program, index) in programs"
        :key="program.id"
        class="relative py-16 md:py-24 px-6 transition-colors duration-700 ease-in-out overflow-hidden"
        :class="index % 2 === 0 ? 'bg-white dark:bg-slate-900' : program.sectionBgClass"
      >
        <!-- Parallax Background Overlay -->
        <div
          class="absolute inset-0 z-0 bg-fixed bg-cover bg-center transition-opacity duration-700 pointer-events-none"
          :class="
            index % 2 === 0
              ? 'opacity-5 dark:opacity-10 mix-blend-multiply dark:mix-blend-overlay'
              : 'opacity-10 md:opacity-20 mix-blend-overlay'
          "
          :style="{ backgroundImage: `url(${program.pattern})` }"
        ></div>

        <div class="container relative z-10 mx-auto max-w-6xl">
          <div
            class="flex flex-col lg:flex-row gap-10 lg:gap-16 items-center"
            :class="{ 'lg:flex-row-reverse': index % 2 !== 0 }"
          >
            <!-- Kolom Gambar -->
            <div
              class="w-full lg:w-1/2 relative group fade-on-scroll opacity-0 transition-all duration-700 ease-out"
              :class="index % 2 === 0 ? '-translate-x-10' : 'translate-x-10'"
            >
              <div
                class="absolute inset-0 translate-x-3 translate-y-3 md:translate-x-4 md:translate-y-4 rounded-xl shadow-lg transition-all duration-700 ease-in-out group-hover:translate-x-5 group-hover:translate-y-5 md:group-hover:translate-x-6 md:group-hover:translate-y-6"
                :class="program.decorationClass"
              ></div>
              <div
                class="relative rounded-xl overflow-hidden shadow-2xl aspect-video md:aspect-[4/3] border-4 border-white dark:border-slate-800 bg-white dark:bg-slate-800 z-10 transition-colors duration-700 ease-in-out"
              >
                <img
                  :src="program.image"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                  :alt="'Jurusan ' + program.title"
                />
              </div>
            </div>

            <!-- Kolom Deskripsi -->
            <div class="w-full lg:w-1/2 space-y-6">
              <div
                class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold shadow-sm border backdrop-blur-sm"
                :class="[
                  index % 2 === 0 ? program.bgClass : 'bg-white/10 dark:bg-black/20',
                  index % 2 === 0 ? program.textClass : 'text-white',
                  index % 2 === 0
                    ? program.borderClass
                    : 'border-white/20 dark:border-white/10',
                ]"
              >
                <i data-lucide="graduation-cap" class="w-3.5 h-3.5 mr-1.5"></i>
                {{ program.badge }}
              </div>
              <h2
                class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-100 ease-out text-3xl md:text-4xl font-bold"
                :class="index % 2 === 0 ? 'text-slate-900 dark:text-white' : 'text-white'"
              >
                {{ program.title }}
              </h2>
              <p
                class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-200 ease-out text-base md:text-lg leading-relaxed text-justify"
                :class="
                  index % 2 === 0
                    ? 'text-slate-600 dark:text-slate-400'
                    : 'text-white/80 dark:text-slate-300'
                "
              >
                {{ program.description }}
              </p>

              <div
                class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-300 ease-out grid grid-cols-1 sm:grid-cols-2 gap-8 pt-4"
              >
                <!-- Fokus Pembelajaran -->
                <div>
                  <h4
                    class="font-bold mb-3 flex items-center"
                    :class="
                      index % 2 === 0 ? 'text-slate-900 dark:text-white' : 'text-white'
                    "
                  >
                    <i
                      data-lucide="book-open"
                      class="w-5 h-5 mr-2"
                      :class="index % 2 === 0 ? program.textClass : 'text-white/90'"
                    ></i>
                    Fokus Pembelajaran
                  </h4>
                  <ul class="space-y-2.5">
                    <li
                      v-for="(subject, idx) in program.subjects"
                      :key="idx"
                      class="flex items-start text-sm transition-colors duration-700 ease-in-out"
                      :class="[
                        index % 2 === 0
                          ? 'text-slate-600 dark:text-slate-400'
                          : 'text-white/80 dark:text-slate-300',
                      ]"
                    >
                      <i
                        data-lucide="check-circle-2"
                        class="w-4 h-4 mr-2 mt-0.5 shrink-0"
                        :class="index % 2 === 0 ? program.textClass : 'text-white/90'"
                      ></i
                      ><span>{{ subject }}</span>
                    </li>
                  </ul>
                </div>
                <!-- Prospek Karir -->
                <div>
                  <h4
                    class="font-bold mb-3 flex items-center"
                    :class="
                      index % 2 === 0 ? 'text-slate-900 dark:text-white' : 'text-white'
                    "
                  >
                    <i
                      data-lucide="briefcase"
                      class="w-5 h-5 mr-2"
                      :class="index % 2 === 0 ? program.textClass : 'text-white/90'"
                    ></i>
                    Prospek Karir
                  </h4>
                  <ul class="space-y-2.5">
                    <li
                      v-for="(career, idx) in program.careers"
                      :key="idx"
                      class="flex items-start text-sm transition-colors duration-700 ease-in-out"
                      :class="[
                        index % 2 === 0
                          ? 'text-slate-600 dark:text-slate-400'
                          : 'text-white/80 dark:text-slate-300',
                      ]"
                    >
                      <i
                        data-lucide="check-circle-2"
                        class="w-4 h-4 mr-2 mt-0.5 shrink-0"
                        :class="index % 2 === 0 ? program.textClass : 'text-white/90'"
                      ></i
                      ><span>{{ career }}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>
