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
    textClass: "text-blue-600 dark:text-blue-400",
    borderClass: "border-blue-200 dark:border-blue-800",
    decorationClass: "bg-blue-600 dark:bg-blue-500",
    image: "https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800",
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
    color: "sky",
    bgClass: "bg-sky-50 dark:bg-sky-900/20",
    textClass: "text-sky-600 dark:text-sky-400",
    borderClass: "border-sky-200 dark:border-sky-800",
    decorationClass: "bg-sky-600 dark:bg-sky-500",
    image: "https://images.unsplash.com/photo-1551836022-deb4988cc6c0?q=80&w=800",
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
    color: "cyan",
    bgClass: "bg-cyan-50 dark:bg-cyan-900/20",
    textClass: "text-cyan-600 dark:text-cyan-400",
    borderClass: "border-cyan-200 dark:border-cyan-800",
    decorationClass: "bg-cyan-600 dark:bg-cyan-500",
    image: "https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=800",
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
    color: "purple",
    bgClass: "bg-purple-50 dark:bg-purple-900/20",
    textClass: "text-purple-600 dark:text-purple-400",
    borderClass: "border-purple-200 dark:border-purple-800",
    decorationClass: "bg-purple-600 dark:bg-purple-500",
    image: "https://images.unsplash.com/photo-1550745165-9bc0b252726a?q=80&w=800",
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
    <section class="py-16 md:py-24 px-6 bg-gray-50 dark:bg-slate-900 min-h-screen">
      <div class="container mx-auto max-w-6xl space-y-20 md:space-y-32">
        <!-- Render Tiap Jurusan dengan Selang-seling (Alternate Layout) -->
        <div
          v-for="(program, index) in programs"
          :key="program.id"
          class="flex flex-col lg:flex-row gap-10 lg:gap-16 items-center"
          :class="{ 'lg:flex-row-reverse': index % 2 !== 0 }"
        >
          <!-- Kolom Gambar -->
          <div class="w-full lg:w-1/2 relative group">
            <div
              class="absolute inset-0 translate-x-3 translate-y-3 md:translate-x-4 md:translate-y-4 rounded-xl shadow-lg"
              :class="program.decorationClass"
            ></div>
            <div
              class="relative rounded-xl overflow-hidden shadow-2xl aspect-video md:aspect-[4/3] border-4 border-white dark:border-slate-800 bg-white dark:bg-slate-800 z-10"
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
              class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold shadow-sm border"
              :class="[program.bgClass, program.textClass, program.borderClass]"
            >
              <i data-lucide="graduation-cap" class="w-3.5 h-3.5 mr-1.5"></i>
              {{ program.badge }}
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white">
              {{ program.title }}
            </h2>
            <p
              class="text-slate-600 dark:text-slate-400 text-base md:text-lg leading-relaxed text-justify"
            >
              {{ program.description }}
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-4">
              <!-- Fokus Pembelajaran -->
              <div>
                <h4
                  class="font-bold text-slate-900 dark:text-white mb-3 flex items-center"
                >
                  <i
                    data-lucide="book-open"
                    class="w-5 h-5 mr-2"
                    :class="program.textClass"
                  ></i>
                  Fokus Pembelajaran
                </h4>
                <ul class="space-y-2.5">
                  <li
                    v-for="(subject, idx) in program.subjects"
                    :key="idx"
                    class="flex items-start text-slate-600 dark:text-slate-400 text-sm"
                  >
                    <i
                      data-lucide="check-circle-2"
                      class="w-4 h-4 mr-2 mt-0.5 shrink-0"
                      :class="program.textClass"
                    ></i
                    ><span>{{ subject }}</span>
                  </li>
                </ul>
              </div>
              <!-- Prospek Karir -->
              <div>
                <h4
                  class="font-bold text-slate-900 dark:text-white mb-3 flex items-center"
                >
                  <i
                    data-lucide="briefcase"
                    class="w-5 h-5 mr-2"
                    :class="program.textClass"
                  ></i>
                  Prospek Karir
                </h4>
                <ul class="space-y-2.5">
                  <li
                    v-for="(career, idx) in program.careers"
                    :key="idx"
                    class="flex items-start text-slate-600 dark:text-slate-400 text-sm"
                  >
                    <i
                      data-lucide="check-circle-2"
                      class="w-4 h-4 mr-2 mt-0.5 shrink-0"
                      :class="program.textClass"
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
</template>
