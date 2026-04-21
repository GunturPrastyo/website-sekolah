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

const activeDay = ref("semua");

const days = [
  { id: "semua", name: "Semua Hari" },
  { id: "senin", name: "Senin" },
  { id: "selasa", name: "Selasa" },
  { id: "rabu", name: "Rabu" },
  { id: "kamis", name: "Kamis" },
  { id: "jumat", name: "Jumat" },
  { id: "sabtu", name: "Sabtu" },
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
    story:
      "Pramuka SMAN 1 Nogosari bukan sekadar ekstrakurikuler biasa. Di sini, kami adalah keluarga yang saling mendukung dan tumbuh bersama. Melalui berbagai kegiatan menantang seperti kemah bakti, penjelajahan alam, hingga pelatihan survival, setiap anggota diajak untuk keluar dari zona nyaman. Kami percaya bahwa kepemimpinan sejati lahir dari kedisiplinan dan empati terhadap sesama dan lingkungan sekitar. Mari bergabung dan ukir cerita petualanganmu bersama kami!",
    pembina: "Bapak Rudi Hermawan, S.Pd",
    members: 124,
    socials: {
      ig: "@pramuka_sman1",
      yt: "Pramuka SMAN 1",
      email: "pramuka@sman1nogosari.sch.id",
    },
  },
  {
    id: 2,
    name: "Paskibra",
    category: "kepemimpinan",
    image: "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800",
    schedule: "Selasa & Kamis, 15.30 - 17.00",
    desc:
      "Melatih kedisiplinan baris-berbaris dan memupuk rasa nasionalisme serta patriotisme cinta tanah air.",
    story:
      "Tegap, disiplin, dan berwibawa. Paskibra adalah tempat bagi mereka yang memiliki jiwa nasionalisme tinggi dan semangat pantang menyerah. Kami tidak hanya belajar baris-berbaris, namun juga ditempa untuk menjadi individu yang memiliki integritas dan loyalitas tinggi terhadap nusa dan bangsa. Setiap derap langkah kami adalah wujud kebanggaan, dan mengibarkan Sang Saka Merah Putih adalah kehormatan terbesar kami.",
    pembina: "Ibu Siti Aminah, M.Pd",
    members: 45,
    socials: { ig: "@paskibra_sman1", yt: "Paskibra SMAN 1" },
  },
  {
    id: 3,
    name: "Bola Basket",
    category: "olahraga",
    image: "https://images.unsplash.com/photo-1546519638-68e109498ffc?q=80&w=800",
    schedule: "Senin & Rabu, 15.30 - 17.30",
    desc:
      "Mengembangkan bakat olahraga bola basket, melatih kerjasama tim, dan menjaga kebugaran fisik.",
    story:
      "Di lapangan ini, keringat dan kerja keras diubah menjadi prestasi gemilang. Klub Basket kami berfokus pada pengembangan fundamental fisik, ketangkasan, serta yang paling utama: kerja sama tim. Menang atau kalah adalah hal biasa, namun komitmen untuk selalu memberikan yang terbaik di setiap pertandingan adalah prinsip kami. Bergabunglah, asah bakatmu, dan bersiaplah menjadi bintang lapangan!",
    pembina: "Bapak Dwi Saputra, S.Or",
    members: 32,
    socials: { ig: "@basket_sman1", yt: "Basket SMAN 1" },
  },
  {
    id: 4,
    name: "Futsal",
    category: "olahraga",
    image: "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=800",
    schedule: "Selasa & Jumat, 15.30 - 17.30",
    desc:
      "Bina prestasi futsal tingkat pelajar dengan latihan rutin dan persiapan partisipasi berbagai turnamen.",
    story:
      "Giring bola, susun strategi, dan cetak gol! Tim futsal SMAN 1 Nogosari adalah salah satu tim yang paling disegani di kancah pelajar tingkat daerah. Kami percaya bahwa kehebatan individu tidak akan bisa mengalahkan kekompakan sebuah tim. Dengan porsi latihan fisik dan taktik yang seimbang, kami selalu bersiap untuk menjuarai berbagai turnamen. Jadilah bagian dari skuad juara kami!",
    pembina: "Bapak Dwi Saputra, S.Or",
    members: 40,
    socials: { ig: "@futsal_sman1" },
  },
  {
    id: 5,
    name: "Tari Tradisional",
    category: "seni",
    image: "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800",
    schedule: "Rabu, 15.00 - 17.00",
    desc:
      "Melestarikan warisan budaya nusantara melalui seni gerak tari tradisional dari berbagai daerah.",
    story:
      "Setiap gerakan adalah harmoni, setiap irama adalah cerita. Kami mengajak para siswa untuk menyelami keindahan warisan budaya nusantara melalui seni tari. Ekstrakurikuler Tari Tradisional bukan hanya tentang menghafal gerakan, melainkan tentang mengekspresikan jiwa dan menghidupkan kembali tradisi leluhur agar tidak lekang oleh zaman. Mari menari dan lestarikan budaya Indonesia dengan bangga.",
    pembina: "Ibu Maya Indah, S.Sn",
    members: 28,
    socials: { ig: "@tari_sman1", yt: "Tari SMAN 1" },
  },
  {
    id: 6,
    name: "Paduan Suara",
    category: "seni",
    image: "https://images.unsplash.com/photo-1516280440502-37f8e1224f4a?q=80&w=800",
    schedule: "Kamis, 15.00 - 17.00",
    desc:
      "Mengembangkan bakat tarik suara dan merajut harmonisasi vokal yang indah dalam bentuk paduan suara.",
    story:
      "Menyatukan berbagai warna suara menjadi satu harmoni yang menggetarkan jiwa. Di paduan suara ini, kami belajar teknik vokal, pernapasan, dan kekompakan dalam melantunkan berbagai genre lagu. Dari panggung sekolah hingga kompetisi paduan suara tingkat nasional, suara kami telah menyentuh banyak hati. Jika kamu memiliki passion bernyanyi, di sinilah tempat suaramu akan benar-benar bersinar.",
    pembina: "Bapak Ahmad Fauzi, S.Sn",
    members: 35,
    socials: { ig: "@padus_sman1", yt: "Padus SMAN 1" },
  },
  {
    id: 7,
    name: "Karya Ilmiah Remaja",
    category: "akademik",
    image: "https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800",
    schedule: "Senin, 15.00 - 16.30",
    desc:
      "Wadah bagi siswa untuk melakukan penelitian, observasi, bereksperimen, dan penulisan karya ilmiah.",
    story:
      "Rasa ingin tahu adalah awal dari sebuah penemuan besar yang mengubah dunia. KIR SMAN 1 menjadi laboratorium ide bagi para siswa yang gemar meneliti, berinovasi, dan mencari solusi atas berbagai permasalahan di sekitar kita. Di sini, hipotesis diuji dan kreativitas diasah tanpa batas. Bersama kami, temukan jawaban dari rasa penasaranmu dan ciptakan karya ilmiah yang bermanfaat bagi masyarakat!",
    pembina: "Ibu Rina Rahmawati, S.E",
    members: 25,
    socials: { ig: "@kir_sman1", email: "kir@sman1nogosari.sch.id" },
  },
  {
    id: 8,
    name: "English Club",
    category: "akademik",
    image: "https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=800",
    schedule: "Rabu, 15.00 - 16.30",
    desc:
      "Meningkatkan kemampuan berbahasa Inggris dengan asik melalui debat, pidato, dan percakapan interaktif.",
    story:
      "Let's speak up and conquer the world! English Club adalah wadah yang asik, interaktif, dan bebas tekanan untuk melatih kepercayaan diri dalam berbahasa Inggris. Melalui simulasi debat, pidato (speech), hingga permainan peran (roleplay), kami membuktikan bahwa belajar bahasa asing itu sangat menyenangkan. Jangan biarkan kendala bahasa menghentikan mimpimu, come join us and let's grow together!",
    pembina: "Bapak Rudi Hermawan, S.Pd",
    members: 42,
    socials: { ig: "@englishclub_sman1", yt: "English Club SMAN 1" },
  },
]);

const searchQuery = ref("");

const filteredEkskul = computed(() => {
  let filtered = ekskulList.value;

  if (activeCategory.value !== "semua") {
    filtered = filtered.filter((ekskul) => ekskul.category === activeCategory.value);
  }

  if (activeDay.value !== "semua") {
    filtered = filtered.filter((ekskul) =>
      ekskul.schedule.toLowerCase().includes(activeDay.value.toLowerCase())
    );
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
        <!-- Search Bar, Filter Hari, & Filter Card -->
        <div class="flex flex-col gap-6 mb-12">
          <!-- Top Row: Day Dropdown + Search Bar -->
          <div class="flex flex-col sm:flex-row items-center gap-4 w-full">
            <!-- Dropdown Hari -->
            <div class="relative w-full sm:w-48 shrink-0">
              <i
                data-lucide="calendar-days"
                class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
              ></i>
              <select
                v-model="activeDay"
                class="w-full pl-12 pr-10 py-4 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm text-sm appearance-none cursor-pointer"
              >
                <option v-for="day in days" :key="day.id" :value="day.id">
                  {{ day.name }}
                </option>
              </select>
              <div
                class="absolute inset-y-0 right-5 flex items-center pointer-events-none"
              >
                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
              </div>
            </div>

            <!-- Search Bar -->
            <div class="relative w-full lg:w-[350px] shrink-0">
              <i
                data-lucide="search"
                class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
              ></i>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari nama ekstrakurikuler..."
                class="w-full pl-12 pr-5 py-4 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm text-sm"
              />
            </div>
          </div>

          <!-- Bottom Row: Filter Card (Kategori) -->
          <div
            class="w-full bg-white dark:bg-slate-800 p-5 lg:p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col gap-4"
          >
            <h4 class="text-sm font-bold text-gray-900 dark:text-white flex items-center">
              <i
                data-lucide="filter"
                class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400"
              ></i>
              Kategori:
            </h4>
            <div class="flex flex-wrap items-center gap-2 md:gap-2.5">
              <button
                v-for="cat in categories"
                :key="cat.id"
                @click="activeCategory = cat.id"
                class="px-3.5 md:px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold transition-all duration-300 focus:outline-none flex items-center border"
                :class="
                  activeCategory === cat.id
                    ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/30'
                    : 'bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-slate-600 hover:border-blue-300 hover:text-blue-600 dark:hover:text-blue-400'
                "
              >
                <i :data-lucide="cat.icon" class="w-3.5 h-3.5 mr-1.5 hidden sm:block"></i>
                {{ cat.name }}
                <span class="ml-1 text-[11px] font-bold opacity-70">
                  ({{ getCategoryCount(cat.id) }})
                </span>
              </button>
            </div>
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
          class="bg-white dark:bg-slate-800 rounded-3xl w-full max-w-3xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col relative z-10 transform transition-all duration-300 scale-100"
          @click.stop
        >
          <!-- Close button -->
          <button
            @click="closeModal"
            class="absolute top-4 right-4 z-30 w-10 h-10 flex items-center justify-center rounded-full bg-black/30 hover:bg-black/50 text-white backdrop-blur-md transition-colors focus:outline-none"
          >
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>

          <!-- Wrapper Scrollable Area -->
          <div
            class="overflow-y-auto w-full h-full flex flex-col [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-slate-600 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300 dark:hover:[&::-webkit-scrollbar-thumb]:bg-slate-500"
          >
            <!-- Hero Cover Area -->
            <div class="relative w-full h-64 sm:h-80 shrink-0">
              <img :src="selectedEkskul?.image" class="w-full h-full object-cover" />
              <div
                class="absolute inset-0 bg-gradient-to-t from-slate-900/95 via-slate-900/40 to-transparent"
              ></div>

              <div
                class="absolute bottom-0 left-0 w-full p-6 md:p-10 flex flex-col justify-end"
              >
                <div
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-500/20 backdrop-blur-md border border-blue-300/30 text-blue-100 text-xs font-bold uppercase tracking-wider mb-3 w-fit"
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
                  class="text-3xl lg:text-4xl font-extrabold text-white leading-tight drop-shadow-lg"
                >
                  {{ selectedEkskul?.name }}
                </h3>
              </div>
            </div>

            <!-- Article Content Area -->
            <div class="p-6 md:p-10 flex flex-col flex-1 bg-white dark:bg-slate-800">
              <!-- Story Text dengan Gaya Drop Cap (Huruf awal besar) -->
              <div class="mb-10">
                <p
                  class="text-gray-700 dark:text-gray-300 text-base md:text-lg leading-relaxed text-justify first-letter:text-6xl first-letter:font-black first-letter:text-blue-600 dark:first-letter:text-blue-400 first-letter:float-left first-letter:mr-4 first-letter:mt-1 first-letter:leading-none"
                >
                  {{ selectedEkskul?.story }}
                </p>
              </div>

              <!-- Quick Info Grid -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
                <div
                  class="bg-gray-50 dark:bg-slate-700/50 p-4 lg:p-5 rounded-2xl border border-gray-100 dark:border-slate-600 transition-colors hover:border-blue-200 dark:hover:border-blue-800"
                >
                  <span
                    class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider block mb-1.5"
                    >Jadwal Latihan</span
                  >
                  <div
                    class="font-bold text-gray-900 dark:text-white flex items-center text-sm"
                  >
                    <i
                      data-lucide="calendar-clock"
                      class="w-4 h-4 mr-2 text-blue-500 shrink-0"
                    ></i>
                    {{ selectedEkskul?.schedule }}
                  </div>
                </div>
                <div
                  class="bg-gray-50 dark:bg-slate-700/50 p-4 lg:p-5 rounded-2xl border border-gray-100 dark:border-slate-600 transition-colors hover:border-blue-200 dark:hover:border-blue-800"
                >
                  <span
                    class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider block mb-1.5"
                    >Pembina Ekskul</span
                  >
                  <div
                    class="font-bold text-gray-900 dark:text-white flex items-center text-sm"
                  >
                    <i data-lucide="user" class="w-4 h-4 mr-2 text-blue-500 shrink-0"></i>
                    {{ selectedEkskul?.pembina }}
                  </div>
                </div>
                <div
                  class="bg-gray-50 dark:bg-slate-700/50 p-4 lg:p-5 rounded-2xl border border-gray-100 dark:border-slate-600 transition-colors hover:border-blue-200 dark:hover:border-blue-800"
                >
                  <span
                    class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider block mb-1.5"
                    >Anggota Aktif</span
                  >
                  <div
                    class="font-bold text-gray-900 dark:text-white flex items-center text-sm"
                  >
                    <i
                      data-lucide="users"
                      class="w-4 h-4 mr-2 text-blue-500 shrink-0"
                    ></i>
                    {{ selectedEkskul?.members }} Siswa terdaftar
                  </div>
                </div>
              </div>

              <!-- Social Media Info -->
              <div class="border-t border-gray-100 dark:border-slate-700 pt-6 mt-auto">
                <h4
                  class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center"
                >
                  <i data-lucide="link" class="w-4 h-4 mr-2"></i> Terhubung dengan Kami
                </h4>
                <div class="flex flex-wrap gap-3">
                  <a
                    v-if="selectedEkskul?.socials?.ig"
                    href="#"
                    class="px-4 py-2 rounded-full bg-pink-50 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400 font-semibold text-sm flex items-center hover:bg-pink-100 dark:hover:bg-pink-900/40 transition-colors"
                  >
                    <i data-lucide="instagram" class="w-4 h-4 mr-2"></i>
                    {{ selectedEkskul.socials.ig }}
                  </a>
                  <a
                    v-if="selectedEkskul?.socials?.yt"
                    href="#"
                    class="px-4 py-2 rounded-full bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-semibold text-sm flex items-center hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors"
                  >
                    <i data-lucide="youtube" class="w-4 h-4 mr-2"></i>
                    {{ selectedEkskul.socials.yt }}
                  </a>
                  <a
                    v-if="selectedEkskul?.socials?.email"
                    href="#"
                    class="px-4 py-2 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold text-sm flex items-center hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors"
                  >
                    <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                    {{ selectedEkskul.socials.email }}
                  </a>
                </div>
              </div>

              <!-- Modal Footer Buttons -->
              <div
                class="mt-8 flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-700"
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
