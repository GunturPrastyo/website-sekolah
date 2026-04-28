<script setup>
import { ref, computed, onMounted, nextTick, onBeforeUnmount, watch } from "vue";
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

// --- Fitur Load More ---
const itemsPerPage = 6;
const itemsToShow = ref(itemsPerPage);

// Reset jumlah item ke awal setiap kali filter atau pencarian berubah
watch([activeCategory, activeDay, searchQuery], () => {
  itemsToShow.value = itemsPerPage;
});

const paginatedEkskul = computed(() => {
  return filteredEkskul.value.slice(0, itemsToShow.value);
});

const hasMoreItems = computed(() => {
  return itemsToShow.value < filteredEkskul.value.length;
});

const isLoading = ref(false);

// Hitung berapa banyak skeleton yang perlu ditampilkan (maks 6 atau sisa item yang belum di-load)
const skeletonCount = computed(() => {
  if (!isLoading.value) return 0;
  return Math.min(itemsPerPage, filteredEkskul.value.length - itemsToShow.value);
});

const loadMore = () => {
  isLoading.value = true;
  setTimeout(() => {
    itemsToShow.value += itemsPerPage;
    isLoading.value = false;
  }, 800); // Simulasi delay 800ms
};
// ------------------------

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

let observer;

onMounted(() => {
  // Intersection Observer untuk animasi fade-up
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("opacity-100", "translate-y-0");
          entry.target.classList.remove("opacity-0", "translate-y-10");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 } // Terpicu saat 10% elemen terlihat di layar
  );

  nextTick(() => {
    createIcons({ icons });
    document.querySelectorAll(".fade-on-scroll").forEach((el) => {
      observer.observe(el);
    });
  });
});

// Pantau perubahan halaman/data agar kartu yang baru dirender tetap ikut dianismasi dan render icon terbaru
watch(paginatedEkskul, () => {
  nextTick(() => {
    createIcons({ icons });
    if (observer) {
      document.querySelectorAll(".fade-on-scroll").forEach((el) => {
        if (el.classList.contains("opacity-0")) {
          observer.observe(el);
        }
      });
    }
  });
});

onBeforeUnmount(() => {
  if (observer) observer.disconnect();
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
    <section
      class="relative pt-0 md:pt-12 pb-0 md:pb-12 md:px-6 min-h-screen bg-gradient-to-b from-blue-50/50 to-white dark:from-slate-900 dark:to-slate-950 overflow-hidden"
    >
      <!-- Elemen Dekorasi Latar Belakang (Blob Cahaya) -->
      <div
        class="absolute top-0 right-0 w-96 h-96 bg-blue-400/10 dark:bg-blue-600/10 rounded-full blur-3xl -translate-y-1/4 translate-x-1/4 pointer-events-none"
      ></div>

      <div class="container relative z-10 mx-auto max-w-full">
        <div
          class="bg-white dark:bg-slate-800 md:rounded-lg shadow-sm md:shadow-md border-y md:border border-gray-100 dark:border-slate-700 p-5 md:p-10 lg:p-12 flex flex-col w-full"
        >
          <!-- Search Bar, Filter Hari, & Filter Card -->
          <div class="flex flex-col gap-6 mb-4">
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
                  class="w-full pl-12 pr-10 py-4 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-inner text-sm appearance-none cursor-pointer"
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
              <div class="relative w-full sm:flex-1 lg:flex-none lg:w-[350px]">
                <i
                  data-lucide="search"
                  class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                ></i>
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Cari nama ekstrakurikuler..."
                  class="w-full pl-12 pr-5 py-4 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-inner text-sm"
                />
              </div>
            </div>

            <!-- Bottom Row: Filter Card (Kategori) -->
            <div
              class="w-full bg-gray-50 dark:bg-slate-900/50 p-5 lg:p-6 rounded-xl shadow-inner border border-gray-200 dark:border-slate-700 flex flex-col gap-4"
            >
              <h4
                class="text-sm font-bold text-blue-950 dark:text-white flex items-center"
              >
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
                  class="px-3.5 md:px-4 py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-300 focus:outline-none flex items-center border"
                  :class="
                    activeCategory === cat.id
                      ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/30'
                      : 'bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-slate-700 hover:border-blue-300 hover:text-blue-600 dark:hover:text-blue-400 shadow-sm'
                  "
                >
                  <i
                    :data-lucide="cat.icon"
                    class="w-3.5 h-3.5 mr-1.5 hidden sm:block"
                  ></i>
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
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 md:gap-12 lg:gap-16 relative w-full mt-8"
          >
            <div
              v-for="ekskul in paginatedEkskul"
              :key="ekskul.id"
              class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out relative group cursor-pointer max-w-[280px] sm:max-w-none mx-auto w-full"
              @click="openModal(ekskul)"
            >
              <!-- Dekorasi Card Belakang (Offset Kanan Bawah) -->
              <div
                class="absolute inset-0 bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 dark:from-slate-700 dark:to-slate-800 rounded-2xl transform translate-x-3 translate-y-3 transition-all duration-500 group-hover:translate-x-4 group-hover:translate-y-4 z-0"
              ></div>

              <!-- Main Card -->
              <div
                class="relative z-10 bg-slate-900 rounded-2xl overflow-hidden shadow-lg group-hover:shadow-2xl transition-all duration-500 aspect-[3/4] transform group-hover:-translate-y-1 group-hover:-translate-x-1 border border-gray-200 dark:border-slate-700 h-full w-full"
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
                    class="px-3.5 py-1.5 backdrop-blur-md bg-white/20 border border-white/30 rounded-full flex items-center gap-1.5 text-white text-[12px] font-bold capitalize tracking-wide shadow-lg"
                    style="font-family: 'Kalam', cursive"
                  >
                    <i
                      :data-lucide="
                        categories.find((c) => c.id === ekskul.category)?.icon
                      "
                      class="w-3 h-3"
                    ></i>
                    {{ categories.find((c) => c.id === ekskul.category)?.name }}
                  </div>
                </div>

                <!-- Content Area (Slides up smoothly) -->
                <div
                  class="absolute bottom-0 left-0 w-full p-6 md:p-8 transition-all duration-500 ease-out z-20 group-hover:bg-slate-900/60 group-hover:backdrop-blur-md"
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
                      <!-- Ekstra Info: Members & Pembina -->
                      <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span
                          class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider text-blue-100 bg-blue-900/60 border border-blue-400/30 px-2.5 py-1 rounded backdrop-blur-md shadow-sm"
                        >
                          <i data-lucide="users" class="w-3 h-3 mr-1.5"></i>
                          {{ ekskul.members }} Anggota
                        </span>
                        <span
                          class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider text-emerald-100 bg-emerald-900/60 border border-emerald-400/30 px-2.5 py-1 rounded backdrop-blur-md shadow-sm"
                        >
                          <i data-lucide="user" class="w-3 h-3 mr-1.5"></i>
                          {{ ekskul.pembina.split(",")[0] }}
                        </span>
                      </div>

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

            <!-- Skeleton Loading -->
            <template v-if="isLoading">
              <div
                v-for="n in skeletonCount"
                :key="'skeleton-' + n"
                class="relative transition-all duration-500 ease-out max-w-[280px] sm:max-w-none mx-auto w-full"
              >
                <!-- Dekorasi Card Belakang -->
                <div
                  class="absolute inset-0 bg-gray-200 dark:bg-slate-700 rounded-2xl transform translate-x-3 translate-y-3 z-0 animate-pulse"
                ></div>
                <!-- Main Card Skeleton -->
                <div
                  class="relative z-10 bg-gray-100 dark:bg-slate-800 rounded-2xl overflow-hidden shadow-lg aspect-[3/4] border border-gray-200 dark:border-slate-700 h-full w-full animate-pulse"
                >
                  <!-- Dummy Badge Kategori -->
                  <div class="absolute top-5 right-5">
                    <div
                      class="w-24 h-6 bg-gray-300 dark:bg-slate-600 rounded-full"
                    ></div>
                  </div>
                  <!-- Dummy Judul & Teks -->
                  <div class="absolute bottom-0 left-0 w-full p-6 md:p-8">
                    <div
                      class="w-3/4 h-8 bg-gray-300 dark:bg-slate-600 rounded mb-3"
                    ></div>
                    <div class="w-1/2 h-4 bg-gray-300 dark:bg-slate-600 rounded"></div>
                  </div>
                </div>
              </div>
            </template>
          </TransitionGroup>

          <!-- Load More Button -->
          <div
            v-if="hasMoreItems"
            class="flex justify-center items-center mt-14 relative z-10 fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-300 ease-out w-full"
          >
            <div
              class="h-px bg-gray-200 dark:bg-slate-700 flex-grow max-w-[80px] md:max-w-[150px]"
            ></div>
            <button
              @click="loadMore"
              :disabled="isLoading"
              class="mx-5 inline-flex items-center text-sm md:text-base font-semibold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors group focus:outline-none disabled:opacity-70 disabled:cursor-not-allowed"
            >
              <span v-if="!isLoading" class="flex items-center">
                Muat Lebih Banyak
                <i
                  data-lucide="chevron-down"
                  class="w-4 h-4 ml-1.5 transform group-hover:translate-y-1 transition-transform"
                ></i>
              </span>
              <span v-else class="flex items-center">
                <svg
                  class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600 dark:text-blue-400"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                  ></circle>
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  ></path>
                </svg>
                Memuat...
              </span>
            </button>
            <div
              class="h-px bg-gray-200 dark:bg-slate-700 flex-grow max-w-[80px] md:max-w-[150px]"
            ></div>
          </div>

          <!-- Empty State -->
          <div
            v-if="filteredEkskul.length === 0"
            class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out col-span-full py-20 text-center bg-gray-50 dark:bg-slate-900/50 backdrop-blur-sm rounded-3xl border-2 border-dashed border-gray-200 dark:border-slate-700 shadow-sm mt-4"
          >
            <div
              class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white dark:bg-slate-800 mb-4 text-gray-400 shadow-sm"
            >
              <i data-lucide="inbox" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-blue-950 dark:text-white">
              Tidak Ditemukan
            </h3>
            <p class="text-gray-500 dark:text-gray-400 mt-1" v-if="searchQuery">
              Tidak ada ekstrakurikuler dengan nama "{{ searchQuery }}".
            </p>
            <p class="text-gray-500 dark:text-gray-400 mt-1" v-else>
              Belum ada data ekstrakurikuler yang didaftarkan untuk kategori ini.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="relative bg-white dark:bg-slate-950 pb-0 md:pb-20 md:px-6 overflow-hidden">
      <div class="container mx-auto max-w-full relative z-10">
        <div
          class="bg-gradient-to-br from-blue-600 to-blue-800 dark:from-blue-900 dark:to-blue-950 md:rounded-lg shadow-sm md:shadow-md border-y md:border border-blue-500/30 dark:border-slate-700 p-6 md:p-10 lg:p-12 flex flex-col lg:flex-row items-center gap-10 lg:gap-16 relative overflow-hidden w-full"
        >
          <!-- Teks CTA & Ajakan -->
          <div
            class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out w-full lg:w-1/2 text-white text-center lg:text-left relative z-10"
          >
            <span
              class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-sm text-white text-sm md:text-md font-bold mb-4 border border-white/30"
              style="font-family: 'Kalam', cursive"
            >
              <i data-lucide="lightbulb" class="w-4 h-4 text-white animate-pulse"></i>
              Inisiatif Siswa
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold mb-4 leading-tight">
              Punya Ide Ekstrakurikuler Baru?
            </h2>
            <p
              class="text-blue-100 text-sm md:text-base leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0"
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
          <div
            class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-200 ease-out w-full lg:w-1/2 relative z-10"
          >
            <div
              class="bg-yellow-400 rounded-xl p-6 sm:p-8 shadow-xl border-t-4 border-t-blue-600 border border-transparent"
            >
              <h3
                class="text-xl font-bold text-gray-900 mb-5 flex items-center border-b border-gray-900/20 pb-4"
              >
                <i data-lucide="clipboard-list" class="w-5 h-5 mr-2 text-gray-900"></i>
                Syarat Pengajuan Klub
              </h3>
              <ul class="space-y-5 relative">
                <!-- Garis Vertikal Timeline -->
                <div
                  class="absolute left-[15px] top-4 bottom-4 w-[2px] bg-gray-900/20 z-0"
                ></div>

                <li class="flex items-start group relative z-10">
                  <div
                    class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0 mr-4 border-4 border-yellow-300 transition-colors group-hover:bg-blue-800 shadow-sm"
                  >
                    1
                  </div>
                  <p class="text-gray-900 text-sm md:text-base leading-relaxed pt-1">
                    Mengumpulkan minimal <strong>10 orang siswa</strong> yang berkomitmen
                    menjadi anggota pertama.
                  </p>
                </li>
                <li class="flex items-start group relative z-10">
                  <div
                    class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0 mr-4 border-4 border-yellow-300 transition-colors group-hover:bg-blue-800 shadow-sm"
                  >
                    2
                  </div>
                  <p class="text-gray-900 text-sm md:text-base leading-relaxed pt-1">
                    Mendapat persetujuan dari minimal
                    <strong>1 orang guru pembina</strong> pendamping.
                  </p>
                </li>
                <li class="flex items-start group relative z-10">
                  <div
                    class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0 mr-4 border-4 border-yellow-300 transition-colors group-hover:bg-blue-800 shadow-sm"
                  >
                    3
                  </div>
                  <p class="text-gray-900 text-sm md:text-base leading-relaxed pt-1">
                    Menyusun proposal yang berisi visi, misi, dan rencana program kerja
                    selama 1 semester.
                  </p>
                </li>
                <li class="flex items-start group relative z-10">
                  <div
                    class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0 mr-4 border-4 border-yellow-300 transition-colors group-hover:bg-blue-800 shadow-sm"
                  >
                    4
                  </div>
                  <p class="text-gray-900 text-sm md:text-base leading-relaxed pt-1">
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
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap");

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
