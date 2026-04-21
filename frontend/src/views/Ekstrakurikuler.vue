<script setup>
import { ref, computed, onMounted, nextTick, onBeforeUnmount } from "vue";
import { createIcons, icons } from "lucide";
import PageHeader from "@/components/PageHeader.vue";
import EkskulModal from "@/components/EkskulModal.vue";

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

onMounted(() => {
  nextTick(() => {
    createIcons({ icons });
  });
});

onBeforeUnmount(() => {
  document.body.style.overflow = ""; // Pastikan scroll kembali normal saat komponen dihancurkan (pindah halaman)
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
    <section class="pt-12 pb-18 px-6 bg-gray-50 dark:bg-slate-900 min-h-screen">
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
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-10 md:gap-x-8 md:gap-y-12 relative w-full mt-4"
        >
          <div
            v-for="ekskul in filteredEkskul"
            :key="ekskul.id"
            class="relative group cursor-pointer"
            @click="openModal(ekskul)"
          >
            <!-- Dekorasi Card Menumpuk -->
            <div
              class="absolute inset-0 bg-blue-200/60 dark:bg-slate-800/80 rounded-3xl transform translate-y-3.5 scale-[0.88] transition-all duration-500 group-hover:translate-y-5 z-0 border border-white/50 dark:border-slate-700"
            ></div>
            <div
              class="absolute inset-0 bg-blue-100/80 dark:bg-slate-700/90 rounded-3xl transform translate-y-1.5 scale-[0.94] transition-all duration-500 group-hover:translate-y-2.5 z-0 border border-white/50 dark:border-slate-700"
            ></div>

            <!-- Main Card -->
            <div
              class="relative z-10 bg-slate-900 rounded-3xl overflow-hidden shadow-lg group-hover:shadow-2xl transition-all duration-500 aspect-[3/4] transform group-hover:-translate-y-1 border border-gray-200 dark:border-slate-700 h-full w-full"
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

    <section
      class="relative bg-gray-50 dark:bg-slate-900 pb-0 sm:pb-16 md:pb-24 px-0 sm:px-6"
    >
      <div class="w-full sm:container sm:mx-auto sm:max-w-6xl relative z-10">
        <div
          class="bg-gradient-to-br from-indigo-600 to-blue-800 rounded-none sm:rounded-xl pt-10 pb-12 px-6 sm:p-8 md:p-12 shadow-none sm:shadow-md flex flex-col lg:flex-row items-center gap-10"
        >
          <!-- Teks CTA & Ajakan -->
          <div class="w-full lg:w-1/2 text-white text-center lg:text-left pt-2 sm:pt-0">
            <span
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/20 backdrop-blur-sm text-white text-xs font-bold uppercase tracking-wider mb-4 border border-white/30"
            >
              <i data-lucide="lightbulb" class="w-3.5 h-3.5"></i> Inisiatif Siswa
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold mb-4 leading-tight">
              Punya Ide Ekstrakurikuler Baru?
            </h2>
            <p
              class="text-indigo-100 text-sm md:text-base leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0"
            >
              SMAN 1 Nogosari sangat mendukung kreativitas dan kepemimpinan siswa. Jika
              kamu memiliki minat pada bidang tertentu yang belum ada wadahnya, kamu bisa
              mengajukan pembentukan klub baru!
            </p>
            <button
              class="inline-flex items-center justify-center px-6 py-3 bg-yellow-400 text-blue-900 font-bold rounded-xl shadow-lg hover:bg-yellow-300 hover:-translate-y-1 transition-all text-sm md:text-base focus:outline-none"
            >
              Unduh Panduan Proposal <i data-lucide="download" class="w-4 h-4 ml-2"></i>
            </button>
          </div>

          <!-- Box Syarat & Ketentuan -->
          <div class="w-full lg:w-1/2">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 shadow-xl">
              <h3
                class="text-xl font-bold text-gray-900 dark:text-white mb-5 flex items-center border-b border-gray-100 dark:border-slate-800 pb-4"
              >
                <i
                  data-lucide="clipboard-list"
                  class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400"
                ></i>
                Syarat Pengajuan Klub
              </h3>
              <ul class="space-y-4">
                <li class="flex items-start group">
                  <div
                    class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shrink-0 mt-0.5 mr-3 transition-colors group-hover:bg-indigo-600 group-hover:text-white"
                  >
                    1
                  </div>
                  <p
                    class="text-gray-700 dark:text-gray-300 text-sm md:text-base leading-relaxed"
                  >
                    Mengumpulkan minimal <strong>10 orang siswa</strong> yang berkomitmen
                    menjadi anggota pertama.
                  </p>
                </li>
                <li class="flex items-start group">
                  <div
                    class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shrink-0 mt-0.5 mr-3 transition-colors group-hover:bg-indigo-600 group-hover:text-white"
                  >
                    2
                  </div>
                  <p
                    class="text-gray-700 dark:text-gray-300 text-sm md:text-base leading-relaxed"
                  >
                    Mendapat persetujuan dari minimal
                    <strong>1 orang guru pembina</strong> pendamping.
                  </p>
                </li>
                <li class="flex items-start group">
                  <div
                    class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shrink-0 mt-0.5 mr-3 transition-colors group-hover:bg-indigo-600 group-hover:text-white"
                  >
                    3
                  </div>
                  <p
                    class="text-gray-700 dark:text-gray-300 text-sm md:text-base leading-relaxed"
                  >
                    Menyusun proposal yang berisi visi, misi, dan rencana program kerja
                    selama 1 semester.
                  </p>
                </li>
                <li class="flex items-start group">
                  <div
                    class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shrink-0 mt-0.5 mr-3 transition-colors group-hover:bg-indigo-600 group-hover:text-white"
                  >
                    4
                  </div>
                  <p
                    class="text-gray-700 dark:text-gray-300 text-sm md:text-base leading-relaxed"
                  >
                    Mempresentasikan proposal di hadapan
                    <strong>Waka Kesiswaan & Pengurus OSIS</strong>.
                  </p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal Detail Ekskul -->
    <EkskulModal
      :is-open="isModalOpen"
      :ekskul="selectedEkskul"
      :categories="categories"
      @close="closeModal"
    />
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
