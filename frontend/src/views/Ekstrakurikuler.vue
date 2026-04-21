<script setup>
import { ref, computed, onMounted, onUpdated, nextTick, watch } from "vue";
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
    pembina: "Bapak Rudi Hermawan, S.Pd",
    members: 124,
  },
  {
    id: 2,
    name: "Paskibra",
    category: "kepemimpinan",
    image: "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800",
    schedule: "Selasa & Kamis, 15.30 - 17.00",
    desc:
      "Melatih kedisiplinan baris-berbaris dan memupuk rasa nasionalisme serta patriotisme cinta tanah air.",
    pembina: "Ibu Siti Aminah, M.Pd",
    members: 45,
  },
  {
    id: 3,
    name: "Bola Basket",
    category: "olahraga",
    image: "https://images.unsplash.com/photo-1546519638-68e109498ffc?q=80&w=800",
    schedule: "Senin & Rabu, 15.30 - 17.30",
    desc:
      "Mengembangkan bakat olahraga bola basket, melatih kerjasama tim, dan menjaga kebugaran fisik.",
    pembina: "Bapak Dwi Saputra, S.Or",
    members: 32,
  },
  {
    id: 4,
    name: "Futsal",
    category: "olahraga",
    image: "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=800",
    schedule: "Selasa & Jumat, 15.30 - 17.30",
    desc:
      "Bina prestasi futsal tingkat pelajar dengan latihan rutin dan persiapan partisipasi berbagai turnamen.",
    pembina: "Bapak Dwi Saputra, S.Or",
    members: 40,
  },
  {
    id: 5,
    name: "Tari Tradisional",
    category: "seni",
    image: "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800",
    schedule: "Rabu, 15.00 - 17.00",
    desc:
      "Melestarikan warisan budaya nusantara melalui seni gerak tari tradisional dari berbagai daerah.",
    pembina: "Ibu Maya Indah, S.Sn",
    members: 28,
  },
  {
    id: 6,
    name: "Paduan Suara",
    category: "seni",
    image: "https://images.unsplash.com/photo-1516280440502-37f8e1224f4a?q=80&w=800",
    schedule: "Kamis, 15.00 - 17.00",
    desc:
      "Mengembangkan bakat tarik suara dan merajut harmonisasi vokal yang indah dalam bentuk paduan suara.",
    pembina: "Bapak Ahmad Fauzi, S.Sn",
    members: 35,
  },
  {
    id: 7,
    name: "Karya Ilmiah Remaja",
    category: "akademik",
    image: "https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800",
    schedule: "Senin, 15.00 - 16.30",
    desc:
      "Wadah bagi siswa untuk melakukan penelitian, observasi, bereksperimen, dan penulisan karya ilmiah.",
    pembina: "Ibu Rina Rahmawati, S.E",
    members: 25,
  },
  {
    id: 8,
    name: "English Club",
    category: "akademik",
    image: "https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=800",
    schedule: "Rabu, 15.00 - 16.30",
    desc:
      "Meningkatkan kemampuan berbahasa Inggris dengan asik melalui debat, pidato, dan percakapan interaktif.",
    pembina: "Bapak Rudi Hermawan, S.Pd",
    members: 42,
  },
]);

const searchQuery = ref("");

const filteredEkskul = computed(() => {
  let filtered = ekskulList.value;

  if (activeCategory.value !== "semua") {
    filtered = filtered.filter((ekskul) => ekskul.category === activeCategory.value);
  }

  if (searchQuery.value.trim()) {
    const query = searchQuery.value.trim().toLowerCase();
    filtered = filtered.filter((ekskul) => ekskul.name.toLowerCase().includes(query));
  }

  return filtered;
});

const getCategoryCount = (categoryId) => {
  if (categoryId === "semua") return ekskulList.value.length;
  return ekskulList.value.filter((ekskul) => ekskul.category === categoryId).length;
};

const isModalOpen = ref(false);
const selectedEkskul = ref(null);

const openModal = (ekskul) => {
  selectedEkskul.value = ekskul;
  isModalOpen.value = true;
  document.body.style.overflow = "hidden"; // Mencegah background di-scroll
};

const closeModal = () => {
  isModalOpen.value = false;
  document.body.style.overflow = ""; // Kembalikan scroll
};

// Render ulang ikon lucide saat modal terbuka
watch(isModalOpen, (newVal) => {
  if (newVal) {
    nextTick(() => createIcons({ icons }));
  }
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
        <!-- Search Bar & Filter Tabs -->
        <div class="flex flex-col lg:flex-row justify-between items-center gap-6 mb-12">
          <!-- Search Bar -->
          <div class="relative w-full lg:w-[350px] shrink-0">
            <i
              data-lucide="search"
              class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
            ></i>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari nama ekstrakurikuler..."
              class="w-full pl-11 pr-4 py-2.5 rounded-full border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm text-sm"
            />
          </div>

          <!-- Filter Tabs -->
          <div class="flex flex-wrap justify-center lg:justify-end gap-3">
            <button
              v-for="cat in categories"
              :key="cat.id"
              @click="activeCategory = cat.id"
              class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 focus:outline-none flex items-center gap-2.5"
              :class="
                activeCategory === cat.id
                  ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30'
                  : 'bg-white text-gray-600 dark:bg-slate-800 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:border-blue-300 hover:text-blue-600 dark:hover:text-blue-400'
              "
            >
              <i :data-lucide="cat.icon" class="w-4 h-4"></i>
              {{ cat.name }}
            </button>
          </div>
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
            @click="openModal(ekskul)"
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
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tidak Ditemukan</h3>
          <p class="text-gray-500 dark:text-gray-400 mt-1" v-if="searchQuery">
            Tidak ada ekstrakurikuler dengan nama "{{ searchQuery }}".
          </p>
          <p class="text-gray-500 dark:text-gray-400 mt-1" v-else>
            Belum ada data ekstrakurikuler yang didaftarkan untuk kategori ini.
          </p>
        </div>
      </div>
    </section>

    <!-- Modal Detail Ekskul -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 sm:p-6"
        @click="closeModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-3xl w-full max-w-4xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col md:flex-row relative z-10 transform transition-all duration-300 scale-100"
          @click.stop
        >
          <!-- Close button -->
          <button
            @click="closeModal"
            class="absolute top-4 right-4 z-20 w-10 h-10 flex items-center justify-center rounded-full bg-black/20 hover:bg-black/40 text-white backdrop-blur-md transition-colors focus:outline-none"
          >
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>

          <!-- Left Image (Desktop & Mobile) -->
          <div class="w-full md:w-2/5 h-64 md:h-auto relative shrink-0">
            <img :src="selectedEkskul?.image" class="w-full h-full object-cover" />

            <!-- Mobile Title Overlay -->
            <div
              class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent flex flex-col justify-end p-6 md:hidden"
            >
              <div
                class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider rounded-full mb-2 w-fit border border-white/30"
              >
                <i
                  :data-lucide="
                    categories.find((c) => c.id === selectedEkskul?.category)?.icon
                  "
                  class="w-3 h-3"
                ></i>
                {{ categories.find((c) => c.id === selectedEkskul?.category)?.name }}
              </div>
              <h3 class="text-3xl font-extrabold text-white">
                {{ selectedEkskul?.name }}
              </h3>
            </div>
          </div>

          <!-- Right Content Area -->
          <div
            class="w-full md:w-3/5 p-6 md:p-8 lg:p-10 flex flex-col overflow-y-auto [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-slate-600 [&::-webkit-scrollbar-thumb]:rounded-full"
          >
            <!-- Desktop Title -->
            <div class="hidden md:block mb-6">
              <div
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold uppercase tracking-wider mb-3 border border-blue-100 dark:border-blue-800"
              >
                <i
                  :data-lucide="
                    categories.find((c) => c.id === selectedEkskul?.category)?.icon
                  "
                  class="w-3.5 h-3.5"
                ></i>
                {{ categories.find((c) => c.id === selectedEkskul?.category)?.name }}
              </div>
              <h3
                class="text-3xl lg:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight"
              >
                {{ selectedEkskul?.name }}
              </h3>
            </div>

            <!-- Desc & Meta Details -->
            <div class="space-y-6">
              <p
                class="text-gray-600 dark:text-gray-300 leading-relaxed text-base lg:text-lg"
              >
                {{ selectedEkskul?.desc }}
              </p>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div
                  class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-2xl border border-gray-100 dark:border-slate-600"
                >
                  <span
                    class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider"
                    >Jadwal Latihan</span
                  >
                  <div
                    class="font-bold text-gray-900 dark:text-white mt-1.5 flex items-center text-sm lg:text-base"
                  >
                    <i
                      data-lucide="calendar-clock"
                      class="w-4 h-4 mr-2 text-blue-500 shrink-0"
                    ></i>
                    {{ selectedEkskul?.schedule }}
                  </div>
                </div>
                <div
                  class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-2xl border border-gray-100 dark:border-slate-600"
                >
                  <span
                    class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider"
                    >Pembina Ekskul</span
                  >
                  <div
                    class="font-bold text-gray-900 dark:text-white mt-1.5 flex items-center text-sm lg:text-base"
                  >
                    <i data-lucide="user" class="w-4 h-4 mr-2 text-blue-500 shrink-0"></i>
                    {{ selectedEkskul?.pembina }}
                  </div>
                </div>
                <div
                  class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-2xl border border-gray-100 dark:border-slate-600 sm:col-span-2"
                >
                  <span
                    class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider"
                    >Anggota Aktif</span
                  >
                  <div
                    class="font-bold text-gray-900 dark:text-white mt-1.5 flex items-center text-sm lg:text-base"
                  >
                    <i
                      data-lucide="users"
                      class="w-4 h-4 mr-2 text-blue-500 shrink-0"
                    ></i>
                    {{ selectedEkskul?.members }} Siswa terdaftar
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Footer Buttons -->
            <div
              class="mt-8 pt-6 border-t border-gray-100 dark:border-slate-700 flex flex-wrap justify-end gap-3 mt-auto shrink-0"
            >
              <button
                @click="closeModal"
                class="px-5 py-2.5 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 transition-colors focus:outline-none"
              >
                Tutup
              </button>
              <router-link
                to="/pendaftaran"
                class="px-5 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors flex items-center shadow-md shadow-blue-500/30 focus:outline-none"
              >
                Daftar Ekskul <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </Transition>
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
