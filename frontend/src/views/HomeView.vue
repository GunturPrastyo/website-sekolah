<template>
  <!-- Header Section -->
  <header
    class="relative flex flex-col items-center justify-center h-[70vh] md:h-screen text-center text-white"
  >
    <!-- Swiper Carousel Background -->
    <div class="absolute top-0 left-0 w-full h-full swiper-container -z-10">
      <div class="swiper-wrapper">
        <div
          class="swiper-slide bg-cover bg-center"
          style="
            background-image: linear-gradient(rgba(0, 18, 32, 0.7), rgba(0, 18, 32, 0.7)),
              url('/assets/img/banner2.jpeg');
          "
        ></div>
        <div
          class="swiper-slide bg-cover bg-center"
          style="
            background-image: linear-gradient(rgba(0, 18, 32, 0.7), rgba(0, 18, 32, 0.7)),
              url('/assets/img/banner1.jpg');
          "
        ></div>
      </div>
      <!-- Swiper Controls -->
      <div class="swiper-pagination"></div>
    </div>

    <!-- Hero Content -->
    <div class="z-20 p-5">
      <h1 class="text-2xl sm:text-4xl font-bold md:text-6xl">SMA Negeri 1 Nogosari</h1>
      <p class="mt-4 text-md sm:text-lg opacity-90 md:text-xl -mt-2 text-center">
        <span class="inline-flex items-center justify-center">
          <i data-lucide="quote" class="w-5 h-5 mr-2 hidden sm:block"></i>
          Mencetak Generasi Unggul, Berkarakter, dan Berwawasan Global
        </span>
      </p>
      <div
        class="w-11/12 sm:w-full max-w-96 sm:max-w-2xl mx-auto mt-12 overflow-hidden bg-white/15 backdrop-blur-sm rounded-lg shadow-lg border border-white/20"
      >
        <div class="p-4 stats-swiper">
          <div class="swiper-wrapper text-lg sm:text-2xl">
            <div class="text-center swiper-slide">
              <h3 class="font-bold text-white">A</h3>
              <p class="mt-1 text-xs font-semibold uppercase text-white/80">Akreditasi</p>
            </div>
            <div class="text-center swiper-slide">
              <h3 class="font-bold text-white">1100+</h3>
              <p class="mt-1 text-xs font-semibold uppercase text-white/80">
                Jumlah Siswa
              </p>
            </div>
            <div class="text-center swiper-slide">
              <h3 class="font-bold text-white">75</h3>
              <p class="mt-1 text-xs font-semibold uppercase text-white/80">
                Tenaga Pendidik
              </p>
            </div>
            <div class="text-center swiper-slide">
              <h3 class="font-bold text-white">20+</h3>
              <p class="mt-1 text-xs font-semibold uppercase text-white/80">
                Ekstrakurikuler
              </p>
            </div>
            <div class="text-center swiper-slide">
              <h3 class="font-bold text-white">32</h3>
              <p class="mt-1 text-xs font-semibold uppercase text-white/80">
                Ruang Kelas
              </p>
            </div>
            <div class="text-center swiper-slide">
              <h3 class="font-bold text-white">5</h3>
              <p class="mt-1 text-xs font-semibold uppercase text-white/80">
                Laboratorium
              </p>
            </div>
            <div class="text-center swiper-slide">
              <h3 class="font-bold text-white">10k+</h3>
              <p class="mt-1 text-xs font-semibold uppercase text-white/80">
                Koleksi Buku
              </p>
            </div>
            <div class="text-center swiper-slide">
              <h3 class="font-bold text-white">50+</h3>
              <p class="mt-1 text-xs font-semibold uppercase text-white/80">Prestasi</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="px-6 bg-blue-50 dark:bg-slate-900">
    <!-- SECTION -->
    <section class="relative -mx-6 md:mx-0 overflow-hidden">
      <div class="container mt-12 sm:mt-8 relative z-10 mx-auto px-0 lg:px-4">
        <!-- HEADER -->
        <div class="mb-4 md:mb-8 px-2 md:px-0 mt-4 sm:mt-6">
          <div class="relative block">
            <h2
              class="text-xl md:text-4xl font-bold text-blue-950 dark:text-white pl-4 border-l-4 border-blue-600 dark:border-blue-500 mb-2"
            >
              Kenapa Pilih Sekolah Ini?
            </h2>
            <p
              class="text-slate-800/80 dark:text-slate-300 text-sm md:text-base ml-4 max-w-2xl"
            >
              Kenali lebih jauh berbagai keunggulan dan fasilitas yang kami sediakan
            </p>
          </div>
        </div>

        <!-- SWIPER / GRID -->
        <div class="swiper alasan-swiper overflow-visible px-4 md:px-0">
          <div
            class="swiper-wrapper md:grid md:grid-cols-4 md:auto-rows-[250px] md:gap-6"
          >
            <!-- FASILITAS (2x2 Desktop) -->
            <div
              class="swiper-slide md:!w-auto relative rounded-lg sm:rounded-xl overflow-hidden shadow-lg group flex flex-col min-h-[220px] md:min-h-0 md:col-span-2 md:row-span-2"
            >
              <img
                src="https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=1200&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
              />

              <div
                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"
              ></div>

              <!-- GLASS EFFECT -->
              <div
                class="relative z-10 p-4 md:p-6 text-white flex flex-col justify-end h-full backdrop-blur-[2px]"
              >
                <h3 class="text-lg md:text-2xl font-semibold mb-1 md:mb-2">
                  Fasilitas Lengkap
                </h3>
                <p class="text-gray-200 text-xs md:text-sm leading-relaxed line-clamp-3">
                  Didukung dengan lab modern, ruang kelas nyaman, dan sarana belajar yang
                  menunjang.
                </p>
                <a
                  href="#"
                  class="mt-2 text-xs md:text-sm font-medium text-blue-400 inline-flex items-center"
                >
                  Lihat Selengkapnya <span class="ml-1">&gt;</span>
                </a>
              </div>
            </div>

            <!-- GURU -->
            <div
              class="swiper-slide md:!w-auto relative rounded-lg sm:rounded-xl overflow-hidden shadow-lg group flex flex-col min-h-[220px] md:min-h-0 md:col-span-2"
            >
              <img
                src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1200&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
              />

              <div
                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"
              ></div>

              <div
                class="relative z-10 p-4 md:p-6 text-white flex flex-col justify-end h-full backdrop-blur-[2px]"
              >
                <h3 class="text-lg md:text-2xl font-semibold mb-1 md:mb-2">
                  Guru Berpengalaman
                </h3>
                <p class="text-gray-200 text-xs md:text-sm leading-relaxed line-clamp-3">
                  Tenaga pengajar profesional yang siap membimbing siswa mencapai prestasi
                  terbaik.
                </p>
                <a
                  href="#"
                  class="mt-2 text-xs md:text-sm font-medium text-blue-400 inline-flex items-center"
                >
                  Lihat Selengkapnya <span class="ml-1">&gt;</span>
                </a>
              </div>
            </div>

            <!-- PRESTASI -->
            <div
              class="swiper-slide md:!w-auto relative rounded-lg md:rounded-xl overflow-hidden shadow-lg group flex flex-col min-h-[220px] md:min-h-0 md:col-span-2"
            >
              <img
                src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=1200&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
              />

              <div
                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"
              ></div>

              <div
                class="relative z-10 p-4 md:p-6 text-white flex flex-col justify-end h-full backdrop-blur-[2px]"
              >
                <h3 class="text-lg md:text-2xl font-semibold mb-1 md:mb-2">
                  Prestasi Siswa
                </h3>
                <p class="text-gray-200 text-xs md:text-sm leading-relaxed line-clamp-3">
                  Berbagai pencapaian di tingkat lokal hingga nasional.
                </p>
                <a
                  href="#"
                  class="mt-2 text-xs md:text-sm font-medium text-blue-400 inline-flex items-center"
                >
                  Lihat Selengkapnya <span class="ml-1">&gt;</span>
                </a>
              </div>
            </div>
          </div>

          <!-- PAGINATION -->
          <div
            class="alasan-pagination absolute bottom-4 z-20 w-full flex justify-center md:hidden"
          ></div>
        </div>
      </div>

      <!-- ALUMNI / PTN -->
      <div class="mt-8 px-0 md:px-8 lg:px-4 mb-20 md:mb-24 -mx-0 md:-mx-8 lg:-mx-0">
        <div
          class="bg-gradient-to-br from-blue-900 to-blue-950 rounded-sm sm:rounded-xl shadow-xl p-3 md:p-5 flex flex-col md:flex-row items-center gap-6 overflow-hidden"
        >
          <!-- LEFT TEXT -->
          <div class="md:w-1/2 lg:w-1/3">
            <h3 class="text-lg md:text-2xl font-bold text-white mb-2">
              Alumni Diterima di PTN Favorit
            </h3>
            <p class="text-sm md:text-base text-sky-50 leading-relaxed">
              Lulusan kami telah berhasil melanjutkan pendidikan ke berbagai perguruan
              tinggi ternama di Indonesia.
            </p>
          </div>

          <!-- RIGHT LOGO SCROLL -->
          <div class="md:w-2/3 w-full overflow-hidden relative">
            <!-- GRADIENT FADE (biar smooth pinggirnya) -->
            <div
              class="absolute left-0 top-0 h-full w-10 bg-gradient-to-r from-blue-900 to-transparent z-10"
            ></div>
            <div
              class="absolute right-0 top-0 h-full w-10 bg-gradient-to-l from-blue-950 to-transparent z-10"
            ></div>

            <!-- SCROLL WRAPPER -->
            <div class="flex items-center gap-6 md:gap-8 animate-scroll-x py-2">
              <!-- LOGO ITEM -->
              <img
                src="/assets/img/ipb.png"
                class="h-12 md:h-16 w-auto flex-shrink-0 object-contain bg-white dark:bg-slate-200 rounded-lg p-2 shadow-sm hover:scale-105 transition-transform"
              />

              <img
                src="/assets/img/itb.png"
                class="h-12 md:h-16 w-auto flex-shrink-0 object-contain bg-white dark:bg-slate-200 rounded-lg p-2 shadow-sm hover:scale-105 transition-transform"
              />

              <img
                src="/assets/img/ugm.png"
                class="h-12 md:h-16 w-auto flex-shrink-0 object-contain bg-white dark:bg-slate-200 rounded-lg p-2 shadow-sm hover:scale-105 transition-transform"
              />

              <img
                src="/assets/img/ui.png"
                class="h-12 md:h-16 w-auto flex-shrink-0 object-contain bg-white dark:bg-slate-200 rounded-lg p-2 shadow-sm hover:scale-105 transition-transform"
              />

              <img
                src="/assets/img/undip.png"
                class="h-12 md:h-16 w-auto flex-shrink-0 object-contain bg-white dark:bg-slate-200 rounded-lg p-2 shadow-sm hover:scale-105 transition-transform"
              />

              <!-- DUPLIKASI (biar infinite loop mulus) -->
              <img
                src="/assets/img/ipb.png"
                class="h-12 md:h-16 w-auto flex-shrink-0 object-contain bg-white dark:bg-slate-200 rounded-lg p-2 shadow-sm hover:scale-105 transition-transform"
              />

              <img
                src="/assets/img/itb.png"
                class="h-12 md:h-16 w-auto flex-shrink-0 object-contain bg-white dark:bg-slate-200 rounded-lg p-2 shadow-sm hover:scale-105 transition-transform"
              />

              <img
                src="/assets/img/ugm.png"
                class="h-12 md:h-16 w-auto flex-shrink-0 object-contain bg-white dark:bg-slate-200 rounded-lg p-2 shadow-sm hover:scale-105 transition-transform"
              />

              <img
                src="/assets/img/ui.png"
                class="h-12 md:h-16 w-auto flex-shrink-0 object-contain bg-white dark:bg-slate-200 rounded-lg p-2 shadow-sm hover:scale-105 transition-transform"
              />

              <img
                src="/assets/img/undip.png"
                class="h-12 md:h-16 w-auto flex-shrink-0 object-contain bg-white dark:bg-slate-200 rounded-lg p-2 shadow-sm hover:scale-105 transition-transform"
              />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Jurusan Section -->
    <section
      class="relative -my-12 -mx-6 md:-mx-6 lg:-mx-0 bg-blue-50 dark:bg-slate-900 overflow-hidden px-0 md:px-6 mb-12 md:mb-16"
    >
      <div class="container relative z-10 mx-auto">
        <!-- HEADER -->
        <div
          class="relative overflow-hidden shadow-2xl min-h-[240px] sm:min-h-[450px] flex items-center md:rounded-2xl bg-fixed bg-center bg-cover"
          style="
            background-image: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1600&auto=format&fit=crop');
          "
        >
          <div
            class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/40 backdrop-blur-[2px]"
          ></div>
          <div class="relative z-10 -mt-12 pl-4 pr-4 md:px-14 max-w-2xl text-white">
            <h2 class="text-xl md:text-5xl font-bold mb-2 md:mb-4">
              Pilihan Jurusan Unggulan
            </h2>
            <p class="text-sm md:text-xl text-gray-200">
              Temukan minat dan bakatmu dengan pilihan jurusan terbaik untuk masa depanmu.
            </p>
          </div>
        </div>

        <!-- CARD JURUSAN -->
        <div class="relative z-20 mt-[-80px] md:mt-[-100px]">
          <!-- dibuat 80% dari header -->
          <div class="max-w-[90%] mx-auto md:max-w-[95%]">
            <div class="swiper jurusan-swiper rounded-2xl overflow-hidden shadow-xl">
              <div class="swiper-wrapper">
                <!-- IPA -->
                <div
                  class="swiper-slide relative min-h-[280px] overflow-hidden border border-white/10 md:border-none"
                >
                  <div class="absolute inset-0">
                    <img
                      src="https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=1200&auto=format&fit=crop"
                      class="w-full h-full object-cover blur-md scale-110"
                    />
                  </div>

                  <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-900/80 to-black/70"
                  ></div>

                  <!-- TEXT DI ATAS -->
                  <div
                    class="relative z-10 p-6 text-white flex flex-col justify-start h-full"
                  >
                    <h3 class="text-2xl font-bold mb-2">IPA</h3>
                    <p class="text-gray-200 text-sm md:text-md lg:text-lg mb-4">
                      Fokus pada sains seperti fisika, kimia, dan biologi untuk membuka
                      peluang di bidang teknologi.
                    </p>
                    <a
                      href="#"
                      class="mt-auto inline-flex items-center text-sm font-medium text-blue-300 hover:text-white transition-colors w-fit"
                    >
                      Lihat Selengkapnya
                      <i data-lucide="chevron-right" class="w-4 h-4 ml-1.5"></i>
                    </a>
                  </div>
                </div>

                <!-- IPS -->
                <div
                  class="swiper-slide relative min-h-[280px] overflow-hidden border border-white/10 md:border-none"
                >
                  <div class="absolute inset-0">
                    <img
                      src="https://images.unsplash.com/photo-1551836022-deb4988cc6c0?q=80&w=1200&auto=format&fit=crop"
                      class="w-full h-full object-cover blur-md scale-110"
                    />
                  </div>

                  <div
                    class="absolute inset-0 bg-gradient-to-br from-sky-900/80 to-black/70"
                  ></div>

                  <div
                    class="relative z-10 p-6 text-white flex flex-col justify-start h-full"
                  >
                    <h3 class="text-2xl font-bold mb-2">IPS</h3>
                    <p class="text-gray-200 text-sm md:text-md lg:text-lg mb-4">
                      Mempelajari ekonomi, sejarah, dan geografi untuk memahami masyarakat
                      dan dunia.
                    </p>
                    <a
                      href="#"
                      class="mt-auto inline-flex items-center text-sm font-medium text-sky-300 hover:text-white transition-colors w-fit"
                    >
                      Lihat Selengkapnya
                      <i data-lucide="chevron-right" class="w-4 h-4 ml-1.5"></i>
                    </a>
                  </div>
                </div>

                <!-- BAHASA -->
                <div
                  class="swiper-slide relative min-h-[280px] overflow-hidden border border-white/10 md:border-none"
                >
                  <div class="absolute inset-0">
                    <img
                      src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1200&auto=format&fit=crop"
                      class="w-full h-full object-cover blur-md scale-110"
                    />
                  </div>

                  <div
                    class="absolute inset-0 bg-gradient-to-br from-cyan-900/80 to-black/70"
                  ></div>

                  <div
                    class="relative z-10 p-6 text-white flex flex-col justify-start h-full"
                  >
                    <h3 class="text-2xl font-bold mb-2">Bahasa</h3>
                    <p class="text-gray-200 text-sm md:text-md lg:text-lg mb-4">
                      Mendalami bahasa dan budaya untuk peluang di bidang komunikasi dan
                      pariwisata.
                    </p>
                    <a
                      href="#"
                      class="mt-auto inline-flex items-center text-sm font-medium text-cyan-300 hover:text-white transition-colors w-fit"
                    >
                      Lihat Selengkapnya
                      <i data-lucide="chevron-right" class="w-4 h-4 ml-1.5"></i>
                    </a>
                  </div>
                </div>

                <!-- INFORMATIKA -->
                <div
                  class="swiper-slide relative min-h-[280px] overflow-hidden border border-white/10 md:border-none"
                >
                  <div class="absolute inset-0">
                    <img
                      src="https://images.unsplash.com/photo-1550745165-9bc0b252726a?q=80&w=1200&auto=format&fit=crop"
                      class="w-full h-full object-cover blur-md scale-110"
                    />
                  </div>

                  <div
                    class="absolute inset-0 bg-gradient-to-br from-purple-900/80 to-black/70"
                  ></div>

                  <div
                    class="relative z-10 p-6 text-white flex flex-col justify-start h-full"
                  >
                    <h3 class="text-2xl font-bold mb-2">Informatika</h3>
                    <p class="text-gray-200 text-sm md:text-md lg:text-lg mb-4">
                      Mendalami dunia koding, jaringan, dan pengembangan software untuk
                      karir di industri digital.
                    </p>
                    <a
                      href="#"
                      class="mt-auto inline-flex items-center text-sm font-medium text-purple-300 hover:text-white transition-colors w-fit"
                    >
                      Lihat Selengkapnya
                      <i data-lucide="chevron-right" class="w-4 h-4 ml-1.5"></i>
                    </a>
                  </div>
                </div>
              </div>

              <!-- Jurusan Pagination (Mobile Only) -->
              <div
                class="jurusan-pagination absolute bottom-4 left-0 z-30 flex w-full justify-center md:hidden"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Berita & Pengumuman Section -->
    <section class="relative py-0 -mx-6 md:mx-0 overflow-hidden px-6 md:px-0">
      <div class="container mx-auto px-0 md:px-4">
        <!-- Header Section -->
        <div
          class="mb-4 md:mb-10 border-b border-slate-200 dark:border-slate-700 pb-4 md:pb-0"
        >
          <div class="relative block">
            <h2
              class="text-xl md:text-4xl font-bold text-blue-950 dark:text-white pl-4 border-l-4 border-blue-600 dark:border-blue-500 mb-4 sm:mb-2"
            >
              Berita & Pengumuman
            </h2>
            <div
              class="flex flex-col md:flex-row md:justify-between md:items-end ml-0 sm:ml-4 gap-4"
            >
              <p
                class="text-slate-800/80 dark:text-slate-300 text-sm md:text-base max-w-2xl md:pb-4"
              >
                Ikuti terus perkembangan informasi terbaru seputar kegiatan, prestasi, dan
                agenda penting di lingkungan sekolah kami.
              </p>
              <a
                href="#"
                class="hidden md:inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-semibold transition-colors shrink-0 md:pb-4 md:-mb-px border-b-2 border-transparent hover:border-blue-600 dark:hover:border-blue-400"
              >
                Lihat berita lainnya
                <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
              </a>
            </div>
          </div>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
          <!-- KIRI: Bagian Kegiatan & Berita (Grid 1 Atas, 3 Bawah) -->
          <div class="lg:col-span-2 flex flex-col gap-6">
            <!-- Berita Utama (Atas - 1 Besar) -->
            <a
              href="#"
              class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out group relative rounded-2xl overflow-hidden shadow-lg h-[280px] sm:h-[350px] md:h-[400px] w-full block"
            >
              <img
                src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1200&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                alt="Berita Utama"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"
              ></div>
              <div class="absolute bottom-0 left-0 p-5 md:p-8 w-full">
                <span
                  class="inline-block px-3 py-1 mb-3 text-xs font-semibold text-white bg-blue-600 rounded-full"
                  >Kegiatan Utama</span
                >
                <h3
                  class="text-xl md:text-3xl font-bold text-white mb-2 group-hover:text-blue-300 transition-colors"
                >
                  Peringatan Hari Guru Nasional Berlangsung Meriah
                </h3>
                <p class="text-gray-200 text-sm md:text-base line-clamp-3">
                  Seluruh siswa dan staf pengajar berpartisipasi dalam rangkaian acara
                  yang dimeriahkan dengan berbagai penampilan pentas seni dan penghargaan
                  bagi guru berprestasi.
                </p>
                <div
                  class="mt-4 flex items-center justify-between text-gray-300 text-xs md:text-sm"
                >
                  <div class="flex items-center">
                    <i data-lucide="calendar" class="w-4 h-4 mr-1.5"></i> 24 November 2025
                  </div>
                  <button
                    @click.prevent.stop="
                      openShareModal('Peringatan Hari Guru Nasional Berlangsung Meriah')
                    "
                    class="hover:text-blue-400 flex items-center transition-colors z-10 relative"
                  >
                    <i data-lucide="share" class="w-4 h-4 mr-1"></i> Bagikan
                  </button>
                </div>
              </div>
            </a>

            <!-- Berita Pendukung (Bawah - 3 Kecil) -->
            <div class="relative">
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 pb-8">
                <!-- Card 1 -->
                <a
                  href="#"
                  class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-100 ease-out group bg-white dark:bg-slate-800 rounded-xl overflow-hidden shadow-sm hover:shadow-lg border border-gray-100 dark:border-slate-700 flex flex-col h-full"
                >
                  <div class="h-40 overflow-hidden relative">
                    <img
                      src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=600&auto=format&fit=crop"
                      class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                      alt="Berita 1"
                    />
                  </div>
                  <div class="p-4 flex flex-col flex-grow">
                    <span class="text-xs font-semibold text-blue-600 mb-1.5"
                      >Prestasi</span
                    >
                    <h4
                      class="font-bold text-gray-800 dark:text-slate-100 text-sm mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2 min-h-[40px]"
                    >
                      Siswa SMAN 1 Meraih Juara 1 Olimpiade Sains Tingkat Nasional
                    </h4>
                    <p
                      class="text-xs text-gray-500 dark:text-slate-400 line-clamp-2 mb-2 min-h-[32px]"
                    >
                      Prestasi membanggakan kembali ditorehkan oleh siswa-siswi kita di
                      kancah nasional dalam bidang sains terapan.
                    </p>
                    <div
                      class="mt-auto flex items-center justify-between text-gray-500 dark:text-slate-400 text-xs pt-3"
                    >
                      <div class="flex items-center">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 mr-1"></i> 10 Jan
                        2026
                      </div>
                      <button
                        @click.prevent.stop="
                          openShareModal(
                            'Siswa SMAN 1 Meraih Juara 1 Olimpiade Sains Tingkat Nasional'
                          )
                        "
                        class="hover:text-blue-600 dark:hover:text-blue-400 flex items-center transition-colors z-10 relative"
                      >
                        <i data-lucide="share" class="w-3.5 h-3.5 mr-1"></i> Bagikan
                      </button>
                    </div>
                  </div>
                </a>

                <!-- Card 2 -->
                <a
                  href="#"
                  class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-200 ease-out group bg-white dark:bg-slate-800 rounded-xl overflow-hidden shadow-sm hover:shadow-lg border border-gray-100 dark:border-slate-700 flex flex-col h-full"
                >
                  <div class="h-40 overflow-hidden relative">
                    <img
                      src="https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=600&auto=format&fit=crop"
                      class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                      alt="Berita 2"
                    />
                  </div>
                  <div class="p-4 flex flex-col flex-grow">
                    <span class="text-xs font-semibold text-sky-600 mb-1.5"
                      >Fasilitas</span
                    >
                    <h4
                      class="font-bold text-gray-800 dark:text-slate-100 text-sm mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2 min-h-[40px]"
                    >
                      Peresmian Laboratorium Komputer Baru untuk Menunjang Digitalisasi
                    </h4>
                    <p
                      class="text-xs text-gray-500 dark:text-slate-400 line-clamp-2 mb-2 min-h-[32px]"
                    >
                      Fasilitas lab baru telah dilengkapi dengan 40 unit komputer
                      berspesifikasi tinggi untuk mendukung pembelajaran siswa.
                    </p>
                    <div
                      class="mt-auto flex items-center justify-between text-gray-500 dark:text-slate-400 text-xs pt-3"
                    >
                      <div class="flex items-center">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 mr-1"></i> 05 Feb
                        2026
                      </div>
                      <button
                        @click.prevent.stop="
                          openShareModal(
                            'Peresmian Laboratorium Komputer Baru untuk Menunjang Digitalisasi'
                          )
                        "
                        class="hover:text-blue-600 dark:hover:text-blue-400 flex items-center transition-colors z-10 relative"
                      >
                        <i data-lucide="share" class="w-3.5 h-3.5 mr-1"></i> Bagikan
                      </button>
                    </div>
                  </div>
                </a>

                <!-- Card 3 -->
                <a
                  href="#"
                  class="fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-300 ease-out group bg-white dark:bg-slate-800 rounded-xl overflow-hidden shadow-sm hover:shadow-lg border border-gray-100 dark:border-slate-700 flex flex-col h-full"
                >
                  <div class="h-40 overflow-hidden relative">
                    <img
                      src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=600&auto=format&fit=crop"
                      class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                      alt="Berita 3"
                    />
                  </div>
                  <div class="p-4 flex flex-col flex-grow">
                    <span class="text-xs font-semibold text-cyan-600 mb-1.5"
                      >Kegiatan</span
                    >
                    <h4
                      class="font-bold text-gray-800 dark:text-slate-100 text-sm mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2 min-h-[40px]"
                    >
                      Kunjungan Edukasi Sejarah ke Museum Nasional Jakarta
                    </h4>
                    <p
                      class="text-xs text-gray-500 dark:text-slate-400 line-clamp-2 mb-2 min-h-[32px]"
                    >
                      Kegiatan rutin tahunan ini diikuti oleh seluruh siswa kelas X guna
                      mengenal lebih dekat peninggalan sejarah bangsa.
                    </p>
                    <div
                      class="mt-auto flex items-center justify-between text-gray-500 dark:text-slate-400 text-xs pt-3"
                    >
                      <div class="flex items-center">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 mr-1"></i> 20 Mar
                        2026
                      </div>
                      <button
                        @click.prevent.stop="
                          openShareModal(
                            'Kunjungan Edukasi Sejarah ke Museum Nasional Jakarta'
                          )
                        "
                        class="hover:text-blue-600 dark:hover:text-blue-400 flex items-center transition-colors z-10 relative"
                      >
                        <i data-lucide="share" class="w-3.5 h-3.5 mr-1"></i> Bagikan
                      </button>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Fade Blur Overlay -->
              <div
                class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-blue-50/95 via-blue-50/70 to-transparent dark:from-slate-900/95 dark:via-slate-900/70 flex items-end justify-center z-10 pointer-events-none md:hidden"
              >
                <a
                  href="#"
                  class="pointer-events-auto text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 text-sm font-semibold transition-colors"
                >
                  Lihat berita lainnya
                </a>
              </div>
            </div>
          </div>

          <!-- KANAN: Bagian Pengumuman (Auto Scroll-Y) -->
          <div
            class="lg:col-span-1 relative h-[500px] lg:h-auto fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-300 ease-out"
          >
            <div
              class="absolute inset-0 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-100 dark:border-slate-700 flex flex-col overflow-hidden"
            >
              <!-- Header Pengumuman -->
              <div
                class="p-5 md:p-6 border-b border-blue-100 dark:border-slate-700/50 bg-gray-50 dark:bg-slate-700/50 z-20 flex items-center justify-between shadow-sm"
              >
                <h3
                  class="text-md md:text-xl font-bold text-blue-950 dark:text-blue-200 flex items-center"
                >
                  <i data-lucide="bell" class="w-5 h-5 mr-2 text-blue-600"></i> Pengumuman
                </h3>
              </div>

              <!-- Scroll Y Container -->
              <div
                class="relative flex-1 overflow-hidden group bg-white dark:bg-slate-800 z-10 min-h-0"
              >
                <!-- Efek Gradasi Atas-Bawah (Biar scrollnya terlihat smooth) -->
                <div
                  class="absolute top-0 left-0 w-full h-10 bg-gradient-to-b from-white dark:from-slate-800 to-transparent z-20 pointer-events-none"
                ></div>
                <div
                  class="absolute bottom-0 left-0 w-full h-10 bg-gradient-to-t from-white dark:from-slate-800 to-transparent z-20 pointer-events-none"
                ></div>

                <!-- Wrapper Animasi Scroll Y -->
                <!-- group-hover pause akan membuat scroll berhenti saat mouse diletakkan ke area ini -->
                <div
                  class="animate-scroll-y group-hover:[animation-play-state:paused] flex flex-col"
                >
                  <!-- Karena butuh infinite loop, isi pengumuman akan kita duplikat 2x -->
                  <template v-for="i in 2" :key="i">
                    <div class="flex flex-col">
                      <!-- Item Pengumuman -->
                      <a
                        href="#"
                        class="p-5 border-b border-blue-50 dark:border-slate-700/50 hover:bg-blue-100/50 dark:hover:bg-slate-700 transition-colors flex items-start"
                        v-for="(item, index) in [
                          {
                            tgl: '15',
                            bln: 'Apr',
                            title: 'Pengambilan Raport Semester Ganjil 2025/2026',
                            desc:
                              'Wajib dihadiri oleh orang tua/wali murid di ruang kelas. Harap membawa surat undangan dan datang tepat waktu sesuai jadwal.',
                          },
                          {
                            tgl: '20',
                            bln: 'Apr',
                            title: 'Jadwal Ujian Sekolah (USBN) Kelas XII',
                            desc:
                              'Persiapkan diri dengan baik dan perhatikan jadwal ujian. Pastikan membawa kartu peserta serta kelengkapan alat tulis.',
                          },
                          {
                            tgl: '01',
                            bln: 'Mei',
                            title: 'Libur Nasional Idul Fitri 1447 H',
                            desc:
                              'Kegiatan belajar mengajar diliburkan selama 2 minggu. Diharapkan siswa tetap menjaga kesehatan dan manfaatkan waktu bersama keluarga.',
                          },
                          {
                            tgl: '10',
                            bln: 'Mei',
                            title: 'Pendaftaran PPDB TA 2026/2027 Dibuka',
                            desc:
                              'Daftarkan putra-putri Anda melalui portal pendaftaran online kami. Jangan lewatkan kesempatan menjadi bagian dari SMAN 1.',
                          },
                        ]"
                        :key="index"
                      >
                        <!-- Date Badge -->
                        <div
                          class="bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 rounded-lg p-2 mr-4 text-center min-w-[60px] flex-shrink-0"
                        >
                          <span class="block text-xl font-bold leading-none">{{
                            item.tgl
                          }}</span>
                          <span class="block text-xs uppercase mt-1 font-semibold">{{
                            item.bln
                          }}</span>
                        </div>
                        <!-- Content -->
                        <div>
                          <h4
                            class="font-semibold text-blue-950 dark:text-slate-100 text-sm mb-1 line-clamp-2"
                          >
                            {{ item.title }}
                          </h4>
                          <p
                            class="text-xs text-gray-500 dark:text-slate-400 line-clamp-3 leading-relaxed"
                          >
                            {{ item.desc }}
                          </p>
                        </div>
                      </a>
                    </div>
                  </template>
                </div>
              </div>

              <!-- Action Button Bawah -->
              <div
                class="p-4 bg-white dark:bg-slate-800 z-20 border-t border-white dark:border-slate-800 text-center shadow-md"
              >
                <a
                  href="#"
                  class="text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white transition-colors inline-flex items-center"
                >
                  Lihat Semua Pengumuman
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Video Profil & Galeri Section -->
    <section
      class="relative py-8 md:py-6 mt-12 md:mt-16 -mx-6 md:mx-0 bg-blue-950 overflow-hidden px-6 md:px-10 mb-12 md:rounded-2xl shadow-xl bg-fixed bg-center bg-cover"
      style="
        background-image: url('https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1600&auto=format&fit=crop');
      "
    >
      <!-- Overlay Parallax -->
      <div class="absolute inset-0 bg-blue-950/85 dark:bg-slate-900/90"></div>

      <div class="container relative z-10 mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
          <div class="relative block">
            <h2
              class="text-2xl md:text-4xl font-normal text-white mb-4 sm:mb-2 tracking-wide"
              style="font-family: 'Dancing Script', cursive"
            >
              Video Profil & Galeri
            </h2>
            <p class="text-blue-200 text-sm md:text-base max-w-2xl">
              Kenali lebih dekat lingkungan, fasilitas, dan berbagai kegiatan seru di SMAN
              1 Nogosari melalui tayangan video dan galeri foto kami.
            </p>
          </div>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 items-stretch">
          <!-- KIRI: Video Profil Utama -->
          <div
            class="lg:col-span-2 relative group rounded-2xl overflow-hidden shadow-2xl h-[280px] sm:h-[400px] md:h-[450px] w-full block cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop"
              class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
              alt="Video Profil Sekolah"
            />
            <div
              class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors"
            ></div>

            <!-- Play Button Center -->
            <div class="absolute inset-0 flex items-center justify-center z-20">
              <div
                class="w-16 h-16 md:w-20 md:h-20 bg-blue-600/90 rounded-full flex items-center justify-center text-white backdrop-blur-sm group-hover:bg-blue-500 group-hover:scale-110 transition-all shadow-[0_0_30px_rgba(37,99,235,0.6)]"
              >
                <i data-lucide="play" class="w-8 h-8 md:w-10 md:h-10 ml-1"></i>
              </div>
            </div>

            <!-- Text Bottom -->
            <div
              class="absolute bottom-0 left-0 p-5 md:p-8 w-full bg-gradient-to-t from-blue-950/90 via-blue-950/40 to-transparent z-10"
            >
              <span
                class="inline-block px-3 py-1 mb-3 text-xs font-semibold text-blue-900 bg-blue-100 rounded-full"
                >Video Terbaru</span
              >
              <h3
                class="text-xl md:text-3xl font-bold text-white mb-2 group-hover:text-blue-200 transition-colors"
              >
                Company Profile SMAN 1 Nogosari 2026
              </h3>
              <p class="text-gray-200 text-sm md:text-base line-clamp-2">
                Saksikan cuplikan fasilitas, metode pembelajaran, dan prestasi yang
                menjadikan kami sekolah pilihan utama.
              </p>
            </div>
          </div>

          <!-- KANAN: Grid Galeri 2x2 -->
          <div
            class="lg:col-span-1 grid grid-cols-2 gap-4 h-[300px] sm:h-[400px] md:h-[450px]"
          >
            <!-- Foto 1 -->
            <a
              href="#"
              class="group relative rounded-xl overflow-hidden shadow-sm h-full block"
            >
              <img
                src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=600&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                alt="Kegiatan Ekstrakurikuler"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-blue-950/90 via-blue-950/20 to-transparent"
              ></div>
              <div class="absolute bottom-0 left-0 p-3 md:p-4 w-full z-10">
                <h4
                  class="text-white font-bold text-sm md:text-base leading-tight group-hover:text-blue-300 transition-colors"
                >
                  Ekstrakurikuler
                </h4>
              </div>
            </a>

            <!-- Foto 2 -->
            <a
              href="#"
              class="group relative rounded-xl overflow-hidden shadow-sm h-full block"
            >
              <img
                src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=600&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                alt="HUT Sekolah"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-blue-950/90 via-blue-950/20 to-transparent"
              ></div>
              <div class="absolute bottom-0 left-0 p-3 md:p-4 w-full z-10">
                <h4
                  class="text-white font-bold text-sm md:text-base leading-tight group-hover:text-blue-300 transition-colors"
                >
                  HUT Sekolah
                </h4>
              </div>
            </a>

            <!-- Foto 3 -->
            <a
              href="#"
              class="group relative rounded-xl overflow-hidden shadow-sm h-full block"
            >
              <img
                src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=600&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                alt="Pentas Seni"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-blue-950/90 via-blue-950/20 to-transparent"
              ></div>
              <div class="absolute bottom-0 left-0 p-3 md:p-4 w-full z-10">
                <h4
                  class="text-white font-bold text-sm md:text-base leading-tight group-hover:text-blue-300 transition-colors"
                >
                  Pentas Seni
                </h4>
              </div>
            </a>

            <!-- Foto 4 (Lihat Semua) -->
            <a
              href="#"
              class="group relative rounded-xl overflow-hidden shadow-sm h-full block"
            >
              <img
                src="https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=600&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                alt="Galeri 4"
              />
              <div
                class="absolute inset-0 bg-blue-950/70 flex items-center justify-center backdrop-blur-[2px] group-hover:bg-blue-900/80 transition-colors"
              >
                <div class="text-center">
                  <div
                    class="bg-white/20 rounded-full w-10 h-10 flex items-center justify-center mx-auto mb-2"
                  >
                    <i data-lucide="image" class="w-5 h-5 text-white"></i>
                  </div>
                  <span class="text-white text-sm font-semibold">Lihat Galeri</span>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Agenda Sekolah Section -->
    <section class="relative py-0 md:py-4 -mx-6 md:mx-0 px-6 md:px-0 mb-12">
      <div class="container mx-auto px-0 md:px-4">
        <!-- Header Section -->
        <div class="mb-6 md:mb-10">
          <div class="relative block">
            <h2
              class="text-xl md:text-4xl font-bold text-blue-950 dark:text-white pl-4 border-l-4 border-blue-600 dark:border-blue-500 mb-4 sm:mb-2"
            >
              Agenda Sekolah
            </h2>
            <p
              class="text-slate-800/80 dark:text-slate-300 text-sm md:text-base ml-0 sm:ml-5 max-w-2xl"
            >
              Jadwal kegiatan akademik dan non-akademik SMAN 1 Nogosari. Pantau kalender
              agar tidak tertinggal event penting kami.
            </p>
          </div>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-stretch">
          <!-- KIRI: Kalender -->
          <div
            class="lg:col-span-5 bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-gray-100 dark:border-slate-700 p-5 md:p-6 flex flex-col"
          >
            <div class="flex items-center justify-between mb-6">
              <button
                class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors focus:outline-none"
              >
                <i
                  data-lucide="chevron-left"
                  class="w-5 h-5 text-gray-600 dark:text-gray-400"
                ></i>
              </button>
              <h3 class="text-lg font-bold text-blue-950 dark:text-blue-200">
                April 2026
              </h3>
              <button
                class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors focus:outline-none"
              >
                <i
                  data-lucide="chevron-right"
                  class="w-5 h-5 text-gray-600 dark:text-gray-400"
                ></i>
              </button>
            </div>

            <div class="grid grid-cols-7 gap-2 text-center mb-2">
              <div class="text-xs font-bold text-red-500 dark:text-red-400 py-1">Min</div>
              <div class="text-xs font-bold text-gray-500 dark:text-gray-400 py-1">
                Sen
              </div>
              <div class="text-xs font-bold text-gray-500 dark:text-gray-400 py-1">
                Sel
              </div>
              <div class="text-xs font-bold text-gray-500 dark:text-gray-400 py-1">
                Rab
              </div>
              <div class="text-xs font-bold text-gray-500 dark:text-gray-400 py-1">
                Kam
              </div>
              <div class="text-xs font-bold text-gray-500 dark:text-gray-400 py-1">
                Jum
              </div>
              <div class="text-xs font-bold text-gray-500 dark:text-gray-400 py-1">
                Sab
              </div>
            </div>

            <div class="grid grid-cols-7 gap-y-2 text-sm text-center">
              <!-- Kosong (Mulai dari Rabu) -->
              <div class="py-2"></div>
              <div class="py-2"></div>
              <div class="py-2"></div>

              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                1
              </div>

              <!-- Event Gantt Chart UTS (2-8) -->
              <!-- Baris 1 -->
              <div
                class="col-span-3 mx-0.5 relative bg-yellow-100 rounded-lg overflow-hidden cursor-pointer hover:bg-yellow-200 transition-colors flex flex-col justify-between"
                title="Ujian Tengah Semester"
              >
                <div
                  class="bg-yellow-400 text-yellow-900 text-[10px] font-bold px-2 py-0.5 text-left truncate"
                >
                  Ujian Tengah Semester
                </div>
                <div class="grid grid-cols-3 w-full py-1">
                  <div class="text-yellow-800 font-bold">2</div>
                  <div class="text-yellow-800 font-bold">3</div>
                  <div class="text-yellow-800 font-bold">4</div>
                </div>
              </div>

              <!-- Baris 2 -->
              <div
                class="col-span-4 mx-0.5 relative bg-yellow-100 rounded-lg overflow-hidden cursor-pointer hover:bg-yellow-200 transition-colors flex flex-col justify-between"
                title="Ujian Tengah Semester"
              >
                <div
                  class="bg-yellow-400 text-yellow-900 text-[10px] font-bold px-2 py-0.5 text-left truncate"
                >
                  Ujian Tengah Semester
                </div>
                <div class="grid grid-cols-4 w-full py-1">
                  <div class="text-red-500 font-bold">5</div>
                  <div class="text-yellow-800 font-bold">6</div>
                  <div class="text-yellow-800 font-bold">7</div>
                  <div class="text-yellow-800 font-bold">8</div>
                </div>
              </div>

              <!-- Event 2 -->
              <div
                class="mx-0.5 relative bg-red-100 rounded-lg overflow-hidden cursor-pointer hover:bg-red-200 transition-colors flex flex-col justify-between"
                title="Rapat Evaluasi Guru & Staf"
              >
                <div
                  class="bg-red-400 text-red-900 text-[10px] font-bold px-2 py-0.5 text-left truncate"
                >
                  Rapat Guru
                </div>
                <div class="py-1">
                  <div class="text-red-800 font-bold">9</div>
                </div>
              </div>

              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                10
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                11
              </div>

              <div
                class="py-2 mx-0.5 rounded-lg text-red-500 dark:text-red-400 font-medium hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer transition-colors"
              >
                12
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                13
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                14
              </div>

              <!-- Event 3 -->
              <div
                class="mx-0.5 relative bg-green-100 rounded-lg overflow-hidden cursor-pointer hover:bg-green-200 transition-colors flex flex-col justify-between"
                title="Peringatan Hari Bumi (Kerja Bakti)"
              >
                <div
                  class="bg-green-400 text-green-900 text-[10px] font-bold px-2 py-0.5 text-left truncate"
                >
                  Hari Bumi
                </div>
                <div class="py-1">
                  <div class="text-green-800 font-bold">15</div>
                </div>
              </div>

              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                16
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                17
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                18
              </div>

              <div
                class="py-2 mx-0.5 rounded-lg text-red-500 dark:text-red-400 font-medium hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer transition-colors"
              >
                19
              </div>

              <!-- Event 4 -->
              <div
                class="mx-0.5 relative bg-blue-100 rounded-lg overflow-hidden cursor-pointer hover:bg-blue-200 transition-colors flex flex-col justify-between"
                title="Seminar Persiapan Kuliah Kelas XII"
              >
                <div
                  class="bg-blue-400 text-blue-900 text-[10px] font-bold px-2 py-0.5 text-left truncate"
                >
                  Seminar XII
                </div>
                <div class="py-1">
                  <div class="text-blue-800 font-bold">20</div>
                </div>
              </div>

              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                21
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                22
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                23
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                24
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                25
              </div>

              <div
                class="py-2 mx-0.5 rounded-lg text-red-500 dark:text-red-400 font-medium hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer transition-colors"
              >
                26
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                27
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                28
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                29
              </div>
              <div
                class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
              >
                30
              </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-700">
              <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">
                Keterangan Label
              </h4>
              <div class="flex flex-wrap gap-3">
                <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                  <span class="w-2 h-2 rounded-full bg-yellow-400 mr-2"></span> Akademik
                </div>
                <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                  <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span> Guru/Staf
                </div>
                <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                  <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span> Kegiatan
                </div>
                <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                  <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Seminar
                </div>
              </div>
            </div>
          </div>

          <!-- KANAN: Daftar Agenda -->
          <div class="lg:col-span-7 relative h-[500px] lg:h-auto">
            <div
              class="absolute inset-0 bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col"
            >
              <div
                class="p-5 md:p-6 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex items-center justify-between shrink-0"
              >
                <h3
                  class="text-lg md:text-xl font-bold text-blue-950 dark:text-blue-200 flex items-center"
                >
                  <i data-lucide="calendar-days" class="w-5 h-5 mr-2 text-blue-600"></i>
                  Daftar Agenda
                </h3>
              </div>
              <div class="relative flex-1 min-h-0">
                <div
                  class="absolute inset-0 p-5 md:p-6 flex flex-col gap-4 overflow-y-auto [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-slate-600 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300 dark:hover:[&::-webkit-scrollbar-thumb]:bg-slate-500"
                >
                  <div
                    v-for="(agenda, index) in agendas"
                    :key="index"
                    class="flex flex-col sm:flex-row gap-4 p-4 rounded-xl border hover:shadow-md transition-all group cursor-pointer shrink-0"
                    :class="themeClasses[agenda.color].card"
                  >
                    <!-- Date Box -->
                    <div
                      class="rounded-lg p-3 flex flex-row sm:flex-col items-center justify-center min-w-[80px] sm:min-w-[90px] border transition-colors gap-2 sm:gap-0"
                      :class="themeClasses[agenda.color].dateBox"
                    >
                      <span
                        class="text-xl sm:text-2xl font-bold leading-none whitespace-nowrap tracking-tighter transition-colors"
                        :class="themeClasses[agenda.color].dateText"
                        >{{ agenda.date }}</span
                      >
                      <span
                        class="text-xs font-semibold sm:mt-1 uppercase transition-colors"
                        :class="themeClasses[agenda.color].monthText"
                        >{{ agenda.month }}</span
                      >
                    </div>

                    <!-- Details -->
                    <div class="flex flex-col justify-center flex-1">
                      <div
                        class="flex items-start sm:items-center justify-between mb-1 gap-2"
                      >
                        <h4
                          class="font-bold transition-colors text-sm md:text-base leading-tight"
                          :class="themeClasses[agenda.color].title"
                        >
                          {{ agenda.title }}
                        </h4>
                      </div>
                      <div
                        class="flex flex-wrap items-center text-xs gap-x-4 gap-y-2 mt-2 transition-colors"
                        :class="themeClasses[agenda.color].infoText"
                      >
                        <span class="flex items-center"
                          ><svg
                            class="w-3.5 h-3.5 mr-1.5"
                            :class="themeClasses[agenda.color].infoIcon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                          >
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                          </svg>
                          {{ agenda.time }}</span
                        >
                        <span class="flex items-center"
                          ><svg
                            class="w-3.5 h-3.5 mr-1.5"
                            :class="themeClasses[agenda.color].infoIcon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                          >
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                            <circle cx="12" cy="10" r="3" />
                          </svg>
                          {{ agenda.loc }}</span
                        >
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Bottom Fade & Scroll Indicator -->
                <div
                  class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white dark:from-slate-800 to-transparent pointer-events-none z-10 flex justify-center items-end"
                >
                  <div
                    class="text-sm font-semibold text-gray-500 flex items-center animate-bounce pb-2"
                  >
                    <i data-lucide="arrow-down" class="w-4 h-4 mr-1.5"></i>
                    Scroll ke bawah
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ & CTA PPDB Section (Sejajar di Desktop) -->
    <section class="relative -mx-6 md:mx-0 overflow-hidden">
      <div
        class="container mx-auto p-8 mb-8 px-6 md:px-4 md:mx-0 rounded-sm sm:rounded-xl bg-gradient-to-br from-blue-900 to-blue-950"
      >
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-12 items-stretch">
          <!-- FAQ Kolom Kiri -->
          <div class="flex flex-col justify-center p-0 sm:p-4">
            <div class="mb-8 md:mb-10 text-center lg:text-left">
              <h2 class="text-xl md:text-4xl font-bold text-white mb-3">
                Pertanyaan Seputar PPDB
              </h2>
              <p
                class="text-blue-200 font-medium text-sm md:text-base max-w-2xl mx-auto lg:mx-0"
              >
                Jawaban atas beberapa pertanyaan yang paling sering ditanyakan oleh calon
                siswa dan orang tua.
              </p>
            </div>

            <div class="w-full flex flex-col gap-4">
              <div
                v-for="(faq, index) in faqs"
                :key="index"
                class="bg-gray-50 border border-gray-50 rounded-xl shadow-md"
              >
                <button
                  @click="toggleFaq(index)"
                  class="w-full text-left px-5 md:px-6 py-4 font-semibold text-blue-950 dark:text-slate-700 flex justify-between items-center hover:bg-gray-100 dark:hover:bg-blue-900 transition-colors rounded-xl focus:outline-none"
                >
                  <span class="pr-0 sm:pr-2 text-sm md:text-base">{{ faq.q }}</span>
                  <svg
                    class="w-5 h-5 text-blue-800 transform transition-transform duration-300 flex-shrink-0"
                    :class="{ 'rotate-180': activeFaq === index }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7"
                    ></path>
                  </svg>
                </button>
                <div
                  :class="{
                    'max-h-96 opacity-100 pb-4': activeFaq === index,
                    'max-h-0 opacity-0 overflow-hidden': activeFaq !== index,
                  }"
                  class="transition-all duration-300 px-5 md:px-6 text-blue-900 dark:text-blue-200 text-sm md:text-base"
                >
                  <div
                    class="border-t border-yellow-500/50 dark:border-yellow-400/50 pt-3"
                  >
                    {{ faq.a }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- CTA Kolom Kanan -->
          <div
            class="relative py-12 md:py-16 bg-gradient-to-br from-blue-600 to-blue-800 border border-blue-500/30 px-6 md:px-10 text-center rounded-2xl shadow-2xl overflow-hidden flex flex-col justify-center items-center"
          >
            <!-- Decoration elements -->
            <div
              class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"
            ></div>
            <div
              class="absolute bottom-0 left-0 w-64 h-64 bg-blue-400 opacity-20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"
            ></div>

            <div class="relative z-10 w-full max-w-xl mx-auto">
              <span
                class="inline-block px-4 py-1.5 mb-6 text-xs md:text-sm font-semibold text-blue-950 bg-yellow-400 rounded-full shadow-sm"
                >TAHUN AJARAN 2026/2027</span
              >
              <h2
                class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-6 leading-tight"
              >
                Siap Menjadi Bagian dari Generasi Berprestasi?
              </h2>
              <p class="text-blue-100 text-sm md:text-base mb-8 max-w-md mx-auto">
                Pendaftaran Peserta Didik Baru (PPDB) SMAN 1 Nogosari akan segera dibuka.
                Daftarkan dirimu dan raih masa depan gemilang bersama kami!
              </p>
              <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a
                  href="#"
                  class="px-6 py-3.5 bg-yellow-400 text-blue-950 font-bold rounded-xl shadow-lg hover:bg-yellow-300 hover:-translate-y-1 transition-all text-sm md:text-base"
                  >Daftar Sekarang</a
                >
                <a
                  href="#"
                  class="px-6 py-3.5 bg-gray-50/10 text-white font-semibold rounded-xl border border-white/50 hover:bg-gray-50/20 transition-all flex items-center justify-center text-sm md:text-base"
                >
                  <i data-lucide="download" class="w-5 h-5 mr-2"></i> Unduh Brosur
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Share Modal -->
    <div
      v-show="isShareModalOpen"
      style="display: none"
      class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity"
      @click="closeShareModal"
    >
      <div
        class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-11/12 max-w-md overflow-hidden transform transition-all"
        @click.stop
      >
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
          <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">
            Bagikan Berita
          </h3>
          <button
            @click="closeShareModal"
            class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
          >
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>
        <div class="p-6">
          <p
            class="text-sm text-gray-600 dark:text-gray-300 mb-5 font-medium line-clamp-2"
          >
            "{{ shareData.title }}"
          </p>
          <div class="flex gap-4 justify-center mb-6">
            <button
              class="w-12 h-12 rounded-full bg-blue-100 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center hover:bg-blue-200 dark:hover:bg-slate-600 transition-colors"
              title="Bagikan ke Facebook"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                />
              </svg>
            </button>
            <button
              class="w-12 h-12 rounded-full bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-gray-200 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors"
              title="Bagikan ke X"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                />
              </svg>
            </button>
            <button
              class="w-12 h-12 rounded-full bg-green-100 dark:bg-slate-700 text-green-600 dark:text-green-400 flex items-center justify-center hover:bg-green-200 dark:hover:bg-slate-600 transition-colors"
              title="Bagikan ke WhatsApp"
            >
              <i data-lucide="message-circle" class="w-5 h-5"></i>
            </button>
            <button
              class="w-12 h-12 rounded-full bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors"
              title="Salin Tautan"
            >
              <i data-lucide="link" class="w-5 h-5"></i>
            </button>
          </div>
          <button
            @click="closeShareModal"
            class="w-full py-2.5 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
    <!-- Share Modal Component -->
    <ShareModal
      :is-open="isShareModalOpen"
      :title="shareData.title"
      @close="closeShareModal"
    />
  </main>
</template>

<script setup>
import { ref, onMounted, reactive } from "vue";
import Swiper from "swiper/bundle";
import "swiper/swiper-bundle.css";
import ShareModal from "@/components/ShareModal.vue";

const activeFaq = ref(null);
const toggleFaq = (index) => {
  activeFaq.value = activeFaq.value === index ? null : index;
};

const isShareModalOpen = ref(false);
const shareData = reactive({ title: "" });

const openShareModal = (title) => {
  shareData.title = title;
  isShareModalOpen.value = true;
};

const closeShareModal = () => {
  isShareModalOpen.value = false;
};

const themeClasses = {
  yellow: {
    card:
      "bg-yellow-50 border-yellow-100 hover:border-yellow-300 hover:shadow-yellow-50/50 dark:bg-slate-800 dark:border-slate-700 dark:hover:border-yellow-500/50",
    dateBox: "bg-yellow-400 border-yellow-200 group-hover:bg-yellow-600",
    dateText: "text-yellow-800 group-hover:text-white",
    monthText: "text-yellow-700 group-hover:text-yellow-100",
    title:
      "text-yellow-900 group-hover:text-yellow-700 dark:text-slate-100 dark:group-hover:text-yellow-400",
    infoIcon: "text-yellow-500",
    infoText: "text-yellow-700 dark:text-slate-400",
  },
  red: {
    card:
      "bg-red-50 border-red-100 hover:border-red-300 hover:shadow-red-50/50 dark:bg-slate-800 dark:border-slate-700 dark:hover:border-red-500/50",
    dateBox: "bg-red-400 border-red-200 group-hover:bg-red-600",
    dateText: "text-red-800 group-hover:text-white",
    monthText: "text-red-700 group-hover:text-red-100",
    title:
      "text-red-900 group-hover:text-red-700 dark:text-slate-100 dark:group-hover:text-red-400",
    infoIcon: "text-red-500",
    infoText: "text-red-700 dark:text-slate-400",
  },
  green: {
    card:
      "bg-green-50 border-green-100 hover:border-green-300 hover:shadow-green-50/50 dark:bg-slate-800 dark:border-slate-700 dark:hover:border-green-500/50",
    dateBox: "bg-green-400 border-green-200 group-hover:bg-green-600",
    dateText: "text-green-800 group-hover:text-white",
    monthText: "text-green-700 group-hover:text-green-100",
    title:
      "text-green-900 group-hover:text-green-700 dark:text-slate-100 dark:group-hover:text-green-400",
    infoIcon: "text-green-500",
    infoText: "text-green-700 dark:text-slate-400",
  },
  blue: {
    card:
      "bg-blue-50 border-blue-100 hover:border-blue-300 hover:shadow-blue-50/50 dark:bg-slate-800 dark:border-slate-700 dark:hover:border-blue-500/50",
    dateBox: "bg-blue-400 border-blue-200 group-hover:bg-blue-600",
    dateText: "text-blue-800 group-hover:text-white",
    monthText: "text-blue-700 group-hover:text-blue-100",
    title:
      "text-blue-900 group-hover:text-blue-700 dark:text-slate-100 dark:group-hover:text-blue-400",
    infoIcon: "text-blue-500",
    infoText: "text-blue-700 dark:text-slate-400",
  },
};

const agendas = [
  {
    date: "02-08",
    month: "Apr",
    title: "Ujian Tengah Semester (UTS)",
    time: "07:30 - Selesai",
    loc: "Ruang Kelas",
    color: "yellow",
  },
  {
    date: "09",
    month: "Apr",
    title: "Rapat Evaluasi Guru & Staf",
    time: "13:00 - 15:00",
    loc: "Ruang Guru",
    color: "red",
  },
  {
    date: "15",
    month: "Apr",
    title: "Peringatan Hari Bumi (Kerja Bakti)",
    time: "08:00 - 11:00",
    loc: "Lingkungan Sekolah",
    color: "green",
  },
  {
    date: "20",
    month: "Apr",
    title: "Seminar Persiapan Kuliah Kelas XII",
    time: "09:00 - 12:00",
    loc: "Aula Utama",
    color: "blue",
  },
  {
    date: "22",
    month: "Apr",
    title: "Lomba Olahraga Antar Kelas",
    time: "08:00 - 15:00",
    loc: "Lapangan Olahraga",
    color: "green",
  },
  {
    date: "28",
    month: "Apr",
    title: "Kunjungan Industri Kelas XI",
    time: "07:00 - 16:00",
    loc: "Kawasan Industri",
    color: "blue",
  },
];

const faqs = [
  {
    q: "Kapan pendaftaran PPDB SMAN 1 Nogosari dibuka?",
    a:
      "Pendaftaran PPDB tahun ajaran 2026/2027 akan dibuka pada minggu pertama bulan Juni 2026 secara online melalui portal resmi kami.",
  },
  {
    q: "Apa saja jalur pendaftaran yang tersedia?",
    a:
      "Kami membuka 4 jalur pendaftaran: Jalur Zonasi (50%), Afirmasi (15%), Perpindahan Tugas Orang Tua (5%), dan Prestasi Akademik/Non-Akademik (30%).",
  },
  {
    q: "Bagaimana cara mendaftar secara online?",
    a:
      "Pendaftaran dapat dilakukan dengan membuat akun di menu Pendaftaran, mengisi formulir data diri, dan mengunggah berkas persyaratan sesuai jalur yang dipilih.",
  },
  {
    q: "Berapa biaya pendaftaran dan SPP bulanan?",
    a:
      "Sebagai sekolah negeri binaan pemerintah, seluruh proses pendaftaran PPDB dan biaya pendidikan (SPP bulanan) adalah GRATIS tanpa dipungut biaya.",
  },
];

onMounted(() => {
  // Intersection Observer untuk animasi fade-up pada saat scroll
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("opacity-100", "translate-y-0");
          entry.target.classList.remove("opacity-0", "translate-y-10");
          observer.unobserve(entry.target); // Animasi hanya berjalan 1x
        }
      });
    },
    { threshold: 0.1 }
  );

  document.querySelectorAll(".fade-on-scroll").forEach((el) => {
    observer.observe(el);
  });

  // Initialize main Swiper
  new Swiper(".swiper-container", {
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    effect: "fade",
    fadeEffect: {
      crossFade: true,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
  });

  // Initialize Stats Swiper
  new Swiper(".stats-swiper", {
    loop: true,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    slidesPerView: 2,
    spaceBetween: 10,
    breakpoints: {
      400: { slidesPerView: 3, spaceBetween: 15 },
      640: { slidesPerView: 4, spaceBetween: 20 },
    },
  });

  // Initialize Jurusan Swiper
  new Swiper(".jurusan-swiper", {
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    slidesPerView: 1,
    spaceBetween: 10,
    pagination: { el: ".jurusan-pagination", clickable: true },
    breakpoints: {
      768: {
        slidesPerView: 3,
        spaceBetween: 0,
        pagination: { enabled: false },
      },
    },
  });

  // Initialize Alasan Swiper
  new Swiper(".alasan-swiper", {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 0,
    autoplay: { delay: 3500, disableOnInteraction: false },
    pagination: { el: ".alasan-pagination", clickable: true },
    breakpoints: {
      768: {
        slidesPerView: 4,
        spaceBetween: 24,
        allowTouchMove: false,
        loop: false,
        autoplay: false,
      },
    },
  });
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap");
</style>
