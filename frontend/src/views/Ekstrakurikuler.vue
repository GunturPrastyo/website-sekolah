<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import { createIcons, icons } from "lucide";
import PageHeader from "@/components/PageHeader.vue";

const activeCategory = ref("semua");

const categories = [
  { id: "semua", name: "Semua Kategori" },
  { id: "olahraga", name: "Olahraga" },
  { id: "seni", name: "Seni & Budaya" },
  { id: "akademik", name: "Akademik & Sains" },
  { id: "kepemimpinan", name: "Kepemimpinan" },
];

const ekskulList = ref([
  {
    id: 1,
    name: "Pramuka",
    category: "kepemimpinan",
    image: "https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=800",
    schedule: "Jumat, 15.00 - 17.00",
    desc:
      "Membentuk karakter disiplin, mandiri, tangguh, dan berjiwa kepemimpinan melalui kegiatan kepramukaan.",
  },
  {
    id: 2,
    name: "Paskibra",
    category: "kepemimpinan",
    image: "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800",
    schedule: "Selasa & Kamis, 15.30 - 17.00",
    desc:
      "Melatih kedisiplinan baris-berbaris dan memupuk rasa nasionalisme serta patriotisme cinta tanah air.",
  },
  {
    id: 3,
    name: "Bola Basket",
    category: "olahraga",
    image: "https://images.unsplash.com/photo-1546519638-68e109498ffc?q=80&w=800",
    schedule: "Senin & Rabu, 15.30 - 17.30",
    desc:
      "Mengembangkan bakat olahraga bola basket, melatih kerjasama tim, dan menjaga kebugaran fisik.",
  },
  {
    id: 4,
    name: "Futsal",
    category: "olahraga",
    image: "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=800",
    schedule: "Selasa & Jumat, 15.30 - 17.30",
    desc:
      "Bina prestasi futsal tingkat pelajar dengan latihan rutin dan persiapan partisipasi berbagai turnamen.",
  },
  {
    id: 5,
    name: "Tari Tradisional",
    category: "seni",
    image: "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800",
    schedule: "Rabu, 15.00 - 17.00",
    desc:
      "Melestarikan warisan budaya nusantara melalui seni gerak tari tradisional dari berbagai daerah.",
  },
  {
    id: 6,
    name: "Paduan Suara",
    category: "seni",
    image: "https://images.unsplash.com/photo-1516280440502-37f8e1224f4a?q=80&w=800",
    schedule: "Kamis, 15.00 - 17.00",
    desc:
      "Mengembangkan bakat tarik suara dan merajut harmonisasi vokal yang indah dalam bentuk paduan suara.",
  },
  {
    id: 7,
    name: "Karya Ilmiah Remaja",
    category: "akademik",
    image: "https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800",
    schedule: "Senin, 15.00 - 16.30",
    desc:
      "Wadah bagi siswa untuk melakukan penelitian, observasi, bereksperimen, dan penulisan karya ilmiah.",
  },
  {
    id: 8,
    name: "English Club",
    category: "akademik",
    image: "https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=800",
    schedule: "Rabu, 15.00 - 16.30",
    desc:
      "Meningkatkan kemampuan berbahasa Inggris dengan asik melalui debat, pidato, dan percakapan interaktif.",
  },
]);

const filteredEkskul = computed(() => {
  if (activeCategory.value === "semua") return ekskulList.value;
  return ekskulList.value.filter((ekskul) => ekskul.category === activeCategory.value);
});

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
      title="Ekstrakurikuler & Klub Siswa"
      description="Kembangkan bakat, minat, dan potensimu di luar jam pelajaran melalui berbagai pilihan kegiatan ekstrakurikuler yang seru dan inspiratif."
    />

    <!-- Gallery Section -->
    <section class="pt-12 pb-24 px-6 bg-gray-50 dark:bg-slate-900 min-h-screen">
      <div class="container mx-auto max-w-6xl">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
          <button
            v-for="cat in categories"
            :key="cat.id"
            @click="activeCategory = cat.id"
            class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 focus:outline-none"
            :class="
              activeCategory === cat.id
                ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30'
                : 'bg-white text-gray-600 dark:bg-slate-800 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:border-blue-300 hover:text-blue-600 dark:hover:text-blue-400'
            "
          >
            {{ cat.name }}
          </button>
        </div>

        <!-- Grid Galeri Ekskul (Dengan Animasi Transisi) -->
        <TransitionGroup
          name="gallery"
          tag="div"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8 relative"
        >
          <div
            v-for="ekskul in filteredEkskul"
            :key="ekskul.id"
            class="group relative bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden border border-gray-100 dark:border-slate-700 flex flex-col h-full cursor-pointer"
          >
            <!-- Image Area -->
            <div class="relative h-56 overflow-hidden shrink-0">
              <img
                :src="ekskul.image"
                :alt="ekskul.name"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity"
              ></div>

              <!-- Badge Kategori -->
              <div
                class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm text-blue-700 text-[10px] font-bold rounded-full uppercase tracking-wider shadow-sm"
              >
                {{ categories.find((c) => c.id === ekskul.category)?.name }}
              </div>
            </div>

            <!-- Content Area -->
            <div
              class="p-6 flex flex-col flex-1 relative -mt-6 bg-white dark:bg-slate-800 rounded-t-2xl z-10 transition-transform duration-300 group-hover:-translate-y-2"
            >
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                {{ ekskul.name }}
              </h3>

              <div
                class="flex items-center text-xs text-blue-600 dark:text-blue-400 mb-4 font-semibold bg-blue-50 dark:bg-slate-700 w-fit px-3 py-1.5 rounded-lg border border-blue-100 dark:border-slate-600"
              >
                <i data-lucide="clock" class="w-3.5 h-3.5 mr-2"></i> {{ ekskul.schedule }}
              </div>

              <p
                class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-3 mb-6"
              >
                {{ ekskul.desc }}
              </p>
            </div>
          </div>
        </TransitionGroup>

        <!-- Empty State -->
        <div
          v-if="filteredEkskul.length === 0"
          class="py-20 text-center bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm mt-4"
        >
          <div
            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-700 mb-4 text-gray-400"
          >
            <i data-lucide="inbox" class="w-8 h-8"></i>
          </div>
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">
            Klub Belum Tersedia
          </h3>
          <p class="text-gray-500 dark:text-gray-400 mt-1">
            Belum ada data ekstrakurikuler yang didaftarkan untuk kategori ini.
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.gallery-move,
.gallery-enter-active,
.gallery-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.gallery-enter-from,
.gallery-leave-to {
  opacity: 0;
  transform: scale(0.9) translateY(30px);
}
.gallery-leave-active {
  position: absolute;
}
</style>
