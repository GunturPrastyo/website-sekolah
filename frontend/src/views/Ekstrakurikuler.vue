<script setup>
import { ref, computed, onMounted, onUpdated, nextTick } from "vue";
import { createIcons, icons } from "lucide";
import PageHeader from "@/components/PageHeader.vue";

const activeCategory = ref("semua");

const categories = [
  { id: "semua", name: "Semua Kategori", icon: "layout-grid" },
  { id: "olahraga", name: "Olahraga", icon: "activity" },
  { id: "seni", name: "Seni & Budaya", icon: "palette" },
  { id: "akademik", name: "Akademik & Sains", icon: "flask-conical" },
  { id: "kepemimpinan", name: "Kepemimpinan", icon: "users" },
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
        <div class="flex flex-wrap justify-center gap-4 mb-16">
          <button
            v-for="cat in categories"
            :key="cat.id"
            @click="activeCategory = cat.id"
            class="flex items-center gap-2.5 px-6 py-3 rounded-2xl font-bold text-sm transition-all duration-300 border-2 focus:outline-none"
            :class="
              activeCategory === cat.id
                ? 'bg-blue-600 border-blue-600 text-white shadow-xl shadow-blue-500/30 -translate-y-1'
                : 'bg-white dark:bg-slate-800 border-gray-100 dark:border-slate-700 text-gray-600 dark:text-gray-300 hover:border-blue-300 hover:text-blue-600 dark:hover:border-slate-600 dark:hover:text-blue-400 hover:-translate-y-1 hover:shadow-lg'
            "
          >
            <i
              :data-lucide="cat.icon"
              class="w-4 h-4"
              :class="
                activeCategory === cat.id
                  ? 'text-white'
                  : 'text-gray-400 group-hover:text-blue-500'
              "
            ></i>
            {{ cat.name }}
          </button>
        </div>

        <!-- Grid Poster Ekskul (Antimainstream Concept) -->
        <TransitionGroup
          name="gallery"
          tag="div"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8 relative w-full"
        >
          <div
            v-for="ekskul in filteredEkskul"
            :key="ekskul.id"
            class="group relative bg-slate-900 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 aspect-[3/4] cursor-pointer transform hover:-translate-y-2 border border-gray-200 dark:border-slate-700"
          >
            <!-- Full Background Image -->
            <img
              :src="ekskul.image"
              :alt="ekskul.name"
              class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-95 group-hover:opacity-100"
            />

            <!-- Gradient Overlay (Darkens on hover) -->
            <div
              class="absolute inset-0 bg-gradient-to-t from-slate-900/95 via-slate-900/30 to-transparent transition-all duration-500 group-hover:via-slate-900/70 group-hover:from-slate-900"
            ></div>

            <!-- Floating Category Badge -->
            <div class="absolute top-5 right-5">
              <div
                class="px-3.5 py-1.5 backdrop-blur-md bg-white/20 border border-white/30 rounded-full flex items-center gap-1.5 text-white text-[10px] font-bold uppercase tracking-wider shadow-lg"
              >
                <i
                  :data-lucide="categories.find((c) => c.id === ekskul.category)?.icon"
                  class="w-3 h-3"
                ></i>
                {{ categories.find((c) => c.id === ekskul.category)?.name }}
              </div>
            </div>

            <!-- Content Area (Slides up smoothly) -->
            <div
              class="absolute bottom-0 left-0 w-full p-6 md:p-8 transition-all duration-500 ease-out z-20 rounded-t-3xl group-hover:bg-slate-900/50 group-hover:backdrop-blur-md"
            >
              <h3
                class="text-2xl font-extrabold text-white mb-2 leading-tight tracking-wide drop-shadow-md"
              >
                {{ ekskul.name }}
              </h3>

              <div
                class="flex items-center text-blue-300 text-xs font-bold uppercase tracking-wider mb-2"
              >
                <i data-lucide="clock" class="w-3.5 h-3.5 mr-1.5"></i>
                {{ ekskul.schedule }}
              </div>

              <!-- Hidden Expandable Content -->
              <div
                class="max-h-0 opacity-0 overflow-hidden transition-all duration-500 ease-in-out group-hover:max-h-[300px] group-hover:opacity-100"
              >
                <div class="pt-4 mt-4 border-t border-white/20">
                  <p class="text-gray-200 text-sm leading-relaxed mb-5 line-clamp-3">
                    {{ ekskul.desc }}
                  </p>

                  <button
                    class="inline-flex items-center text-xs font-bold text-white uppercase tracking-widest hover:text-blue-300 transition-colors focus:outline-none"
                  >
                    Jelajahi Klub
                    <i
                      data-lucide="arrow-right"
                      class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform"
                    ></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </TransitionGroup>

        <!-- Empty State -->
        <div
          v-if="filteredEkskul.length === 0"
          class="col-span-full py-20 text-center bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm rounded-3xl border-2 border-dashed border-gray-200 dark:border-slate-700 shadow-sm mt-4"
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
  transform: scale(0.95) translateY(20px);
}
.gallery-leave-active {
  position: absolute;
}
</style>
