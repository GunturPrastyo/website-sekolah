<template>
  <div class="overflow-x-hidden w-full max-w-full">
    <!-- Header Section -->
    <header
      class="relative z-0 flex flex-col items-center justify-center h-screen md:h-[90vh] lg:h-screen text-center text-white"
    >
      <!-- Video Background -->
      <div class="absolute top-0 left-0 w-full h-full -z-10 overflow-hidden bg-slate-900">
        <video
          autoplay
          loop
          muted
          playsinline
          class="absolute inset-0 w-full h-full object-cover"
        >
          <source src="/img/footage.webm" type="video/webm" />
        </video>
        <!-- Dark Overlay -->
        <div
          class="absolute inset-0 bg-gradient-to-b from-blue-900/70 via-slate-900/80 to-black/90"
        ></div>
      </div>

      <!-- Hero Content -->
      <div class="z-20 p-5">
        <h1
          class="text-3xl md:text-5xl lg:text-5xl font-bold tracking-wide uppercase drop-shadow-lg"
          style="font-family: 'Oswald', sans-serif"
        >
          {{ displayedTitle
          }}<span class="animate-pulse font-light text-white/70">|</span>
        </h1>
        <p
          class="mt-6 text-lg md:text-xl lg:text-2xl text-center transition-all duration-1000 ease-out text-gray-100 drop-shadow-lg"
          :class="showSubtitle ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5'"
          style="font-family: 'Merriweather', serif"
        >
          <span class="inline-flex items-center justify-center">
            <PhQuotes
              class="w-6 h-6 sm:w-8 sm:h-8 mr-2 hidden sm:block opacity-80"
              weight="fill"
            />
            {{ slogan }}
          </span>
        </p>
        <div
          class="w-11/12 sm:w-full max-w-96 sm:max-w-2xl mx-auto mt-12 overflow-hidden bg-white/15 backdrop-blur-sm rounded-lg shadow-lg border border-white/20 transition-all duration-1000 delay-500 ease-out"
          :class="showSubtitle ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
        >
          <div class="p-3 sm:p-4 stats-swiper">
            <div class="swiper-wrapper">
              <div
                v-for="(stat, index) in statsArray"
                :key="index"
                class="text-center swiper-slide"
              >
                <h3
                  class="font-bold text-white text-base min-[400px]:text-lg sm:text-2xl"
                >
                  {{ stat.prefix }}{{ formatStatValue(stat) }}
                </h3>
                <p
                  class="mt-0.5 sm:mt-1 text-[9px] min-[400px]:text-[10px] sm:text-xs font-semibold uppercase text-white/80 tracking-tighter sm:tracking-normal line-clamp-1"
                >
                  {{ stat.label }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="px-6 bg-gray-100 dark:bg-gray-900">
      <!-- SECTION -->
      <section class="relative -mx-6 md:mx-0 overflow-hidden">
        <div
          class="w-full max-w-full container mt-12 sm:mt-8 relative z-10 mx-auto px-0 lg:px-4"
        >
          <!-- HEADER -->
          <div class="mb-8 px-2 md:px-0 mt-4 sm:mt-6">
            <div class="relative block">
              <h2
                class="text-2xl md:text-4xl font-bold text-slate-800 dark:text-white pl-4 border-l-4 border-blue-600 dark:border-blue-500 mb-2"
                style="font-family: 'Oswald', sans-serif"
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
              class="swiper-wrapper md:grid md:grid-cols-4 md:auto-rows-[280px] lg:auto-rows-[320px] md:gap-6"
            >
              <!-- FASILITAS (2x2 Desktop) -->
              <div
                class="swiper-slide md:!w-auto relative bg-center bg-cover md:bg-fixed sm:rounded-lg overflow-hidden shadow-lg hover:shadow-2xl group flex flex-col min-h-[290px] md:min-h-0 md:col-span-2 md:row-span-2 transition-shadow duration-500 before:absolute before:inset-0 before:bg-inherit before:blur-[3px] md:before:blur-none"
                :style="{
                  backgroundImage: `url(${
                    appearanceSettings.benefitFasilitasImage || '/img/fasilitas.jpg'
                  })`,
                }"
              >
                <!-- Overlay Parallax -->
                <div
                  class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-900/80 to-slate-800/40 group-hover:via-slate-800/70 group-hover:to-slate-700/40 transition-all duration-500"
                ></div>

                <!-- TEXT CONTAINER -->
                <div
                  class="relative z-10 p-6 pt-10 sm:p-8 flex flex-col justify-start items-center text-left md:justify-end md:items-start h-full w-full min-h-[290px] md:min-h-0"
                >
                  <div class="flex flex-col items-start w-full max-w-sm mx-auto md:mx-0">
                    <h3
                      class="text-xl md:text-2xl font-bold text-white mb-2 group-hover:text-blue-300 transition-colors duration-300"
                      style="font-family: 'Oswald', sans-serif"
                    >
                      Fasilitas Lengkap
                    </h3>
                    <p
                      class="text-slate-300 text-sm md:text-base leading-relaxed mb-5 line-clamp-3 group-hover:text-white transition-colors duration-300"
                    >
                      Didukung dengan laboratorium modern, ruang kelas yang nyaman, serta
                      sarana olahraga lengkap untuk menunjang kegiatan belajar mengajar
                      secara optimal.
                    </p>
                    <router-link
                      to="/fasilitas"
                      class="inline-flex items-center text-sm font-bold text-blue-400 group-hover:text-blue-300 transition-colors w-fit"
                    >
                      Jelajahi Fasilitas
                      <svg
                        class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1.5 transition-transform duration-300"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 5l7 7-7 7"
                        ></path>
                      </svg>
                    </router-link>
                  </div>
                </div>
              </div>

              <!-- GURU -->
              <div
                class="swiper-slide md:!w-auto relative bg-center bg-cover md:bg-fixed sm:rounded-lg overflow-hidden shadow-lg hover:shadow-2xl group flex flex-col min-h-[290px] md:min-h-0 md:col-span-2 md:row-span-1 transition-shadow duration-500 before:absolute before:inset-0 before:bg-inherit before:blur-[3px] md:before:blur-none"
                :style="{
                  backgroundImage: `url(${
                    appearanceSettings.benefitGuruImage || '/img/pengajar.jpg'
                  })`,
                }"
              >
                <!-- Overlay Parallax -->
                <div
                  class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-900/80 to-slate-800/40 group-hover:via-slate-800/70 group-hover:to-slate-700/40 transition-all duration-500"
                ></div>

                <!-- TEXT CONTAINER -->
                <div
                  class="relative z-10 p-6 pt-10 sm:p-8 flex flex-col justify-start items-center text-left md:justify-end md:items-start h-full w-full min-h-[290px] md:min-h-0"
                >
                  <div class="flex flex-col items-start w-full max-w-sm mx-auto md:mx-0">
                    <h3
                      class="text-xl md:text-2xl font-bold text-white mb-2 group-hover:text-blue-300 transition-colors duration-300"
                      style="font-family: 'Oswald', sans-serif"
                    >
                      Guru Berpengalaman
                    </h3>
                    <p
                      class="text-slate-300 text-sm leading-relaxed mb-4 line-clamp-3 md:line-clamp-2 group-hover:text-white transition-colors duration-300"
                    >
                      Tenaga pendidik profesional yang siap membimbing serta mendampingi
                      siswa mencapai potensi terbaiknya.
                    </p>
                    <router-link
                      to="/guru-staf"
                      class="inline-flex items-center text-sm font-bold text-blue-400 group-hover:text-blue-300 transition-colors w-fit"
                    >
                      Profil Pendidik
                      <svg
                        class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1.5 transition-transform duration-300"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 5l7 7-7 7"
                        ></path>
                      </svg>
                    </router-link>
                  </div>
                </div>
              </div>

              <!-- PRESTASI -->
              <div
                class="swiper-slide md:!w-auto relative bg-center bg-cover md:bg-fixed sm:rounded-lg overflow-hidden shadow-lg hover:shadow-2xl group flex flex-col min-h-[290px] md:min-h-0 md:col-span-2 md:row-span-1 transition-shadow duration-500 before:absolute before:inset-0 before:bg-inherit before:blur-[3px] md:before:blur-none"
                :style="{
                  backgroundImage: `url(${
                    appearanceSettings.benefitPrestasiImage || '/img/prestasi.jpg'
                  })`,
                }"
              >
                <!-- Overlay Parallax -->
                <div
                  class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-900/80 to-slate-800/40 group-hover:via-slate-800/70 group-hover:to-slate-700/40 transition-all duration-500"
                ></div>

                <!-- TEXT CONTAINER -->
                <div
                  class="relative z-10 p-6 pt-10 sm:p-8 flex flex-col justify-start items-center text-left md:justify-end md:items-start h-full w-full min-h-[290px] md:min-h-0"
                >
                  <div class="flex flex-col items-start w-full max-w-sm mx-auto md:mx-0">
                    <h3
                      class="text-xl md:text-2xl font-bold text-white mb-2 group-hover:text-blue-300 transition-colors duration-300"
                      style="font-family: 'Oswald', sans-serif"
                    >
                      Prestasi Siswa
                    </h3>
                    <p
                      class="text-slate-300 text-sm leading-relaxed mb-4 line-clamp-3 md:line-clamp-2 group-hover:text-white transition-colors duration-300"
                    >
                      Berbagai pencapaian membanggakan di tingkat regional hingga nasional
                      yang diraih oleh siswa-siswi berbakat.
                    </p>
                    <router-link
                      to="/prestasi"
                      class="inline-flex items-center text-sm font-bold text-blue-400 group-hover:text-blue-300 transition-colors w-fit"
                    >
                      Daftar Prestasi
                      <svg
                        class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1.5 transition-transform duration-300"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 5l7 7-7 7"
                        ></path>
                      </svg>
                    </router-link>
                  </div>
                </div>
              </div>
            </div>

            <!-- PAGINATION -->
            <div
              class="alasan-pagination absolute bottom-4 z-20 w-full flex justify-center md:hidden mb-6"
            ></div>
          </div>
        </div>
        <!-- ALUMNI / PTN -->
        <div class="mt-8 px-0 md:px-8 lg:px-4 mb-20 md:mb-24 -mx-0 md:-mx-8 lg:-mx-0">
          <div
            class="relative bg-center bg-fixed sm:rounded-xl shadow-xl p-3 md:p-2 flex flex-col md:flex-row items-center gap-0 md:gap-6 overflow-hidden"
            style="
              background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1200');
            "
          >
            <!-- Overlay Parallax -->
            <div
              class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-blue-950/80 to-slate-900/90 backdrop-blur-[2px]"
            ></div>

            <!-- LEFT TEXT -->
            <div
              class="relative z-10 md:w-2/5 lg:w-1/3 mx-0 sm:mx-6 mt-4 md:mt-0 fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out alumni-stats-container"
            >
              <!-- <span
                class="inline-block px-3 py-1 mb-3 text-xs sm:text-sm font-bold tracking-wide text-blue-900 bg-yellow-400 rounded-full shadow-sm"
                style="font-family: 'Kalam', cursive"
              >
                Jejak Lulusan
              </span> -->
              <h3
                class="text-xl md:text-2xl lg:text-3xl font-bold text-white mb-2"
                style="font-family: 'Oswald', sans-serif"
              >
                Persebaran Alumni Kami
              </h3>
              <p class="text-sm lg:text-base text-sky-50 leading-relaxed mb-4">
                Tersebar di berbagai perguruan tinggi favorit dan sukses berkarir di
                berbagai sektor industri terkemuka.
              </p>

              <div class="grid grid-cols-3 gap-2 sm:gap-4">
                <div>
                  <h4
                    class="text-2xl md:text-3xl font-bold text-yellow-400"
                    style="font-family: 'Oswald', sans-serif"
                  >
                    {{
                      alumniStats.alumni.value >= 1000
                        ? (alumniStats.alumni.value / 1000).toFixed(1) + "k+"
                        : Math.floor(alumniStats.alumni.value)
                    }}
                  </h4>
                  <p class="text-[10px] sm:text-xs text-sky-200 mt-1 font-medium">
                    Alumni Sukses
                  </p>
                </div>
                <div>
                  <h4
                    class="text-2xl md:text-3xl font-bold text-yellow-400"
                    style="font-family: 'Oswald', sans-serif"
                  >
                    {{
                      alumniStats.ptn.value >= 1000
                        ? (alumniStats.ptn.value / 1000).toFixed(1) + "k+"
                        : Math.floor(alumniStats.ptn.value)
                    }}
                  </h4>
                  <p class="text-[10px] sm:text-xs text-sky-200 mt-1 font-medium">
                    PTN & Kampus Top
                  </p>
                </div>
                <div>
                  <h4
                    class="text-2xl md:text-3xl font-bold text-yellow-400"
                    style="font-family: 'Oswald', sans-serif"
                  >
                    {{
                      alumniStats.instansi.value >= 1000
                        ? (alumniStats.instansi.value / 1000).toFixed(1) + "k+"
                        : Math.floor(alumniStats.instansi.value)
                    }}
                  </h4>
                  <p class="text-[10px] sm:text-xs text-sky-200 mt-1 font-medium">
                    Instansi & Kedinasan
                  </p>
                </div>
              </div>
            </div>

            <!-- RIGHT MAP VISUALIZATION -->
            <div
              class="relative z-10 md:w-3/5 lg:w-2/3 w-full flex items-center justify-center min-h-[220px] md:min-h-[280px] mt-0"
            >
              <!-- Container Map Indonesia (Dibuat lebih pipih/lebar) -->
              <div
                class="relative w-full max-w-4xl group/map transition-transform duration-700 ease-out hover:scale-105 aspect-[2.5/1] md:aspect-[2/1] lg:aspect-[3.5/1]"
              >
                <!-- Base Map (Peta Indonesia) menggunakan tag img agar bebas blokir CORS -->
                <img
                  src="/img/indonesia.svg"
                  alt="Ilustrasi Persebaran Alumni"
                  class="absolute inset-0 w-full h-full object-fill pointer-events-none opacity-70 dark:opacity-40 transition-opacity duration-700 group-hover/map:opacity-100 dark:group-hover/map:opacity-70"
                  style="
                    filter: invert(56%) sepia(61%) saturate(3000%) hue-rotate(175deg)
                      brightness(101%) contrast(101%)
                      drop-shadow(0px 0px 8px rgba(56, 189, 248, 0.4));
                  "
                />

                <!-- Skeleton / Loading Markers -->
                <template v-if="isLoadingAlumniLocations">
                  <div
                    v-for="(loc, index) in skeletonLocations"
                    :key="'skeleton-map-' + index"
                    class="absolute flex justify-center items-end w-8 h-10 md:w-10 md:h-12 -translate-x-1/2 -translate-y-full animate-pulse opacity-60"
                    :style="{ top: loc.top, left: loc.left, zIndex: 10 }"
                  >
                    <div
                      class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-4 h-1.5 md:w-5 md:h-2 bg-black/20 rounded-[100%] blur-[2px]"
                    ></div>
                    <div class="relative drop-shadow-[0_5px_8px_rgba(0,0,0,0.2)]">
                      <PhMapPin
                        weight="fill"
                        class="w-8 h-8 md:w-10 md:h-10 text-slate-400"
                      />
                      <div
                        class="absolute top-[8px] left-1/2 -translate-x-1/2 w-2.5 h-2.5 md:top-[10px] md:w-3 md:h-3 bg-slate-300 rounded-full"
                      ></div>
                    </div>
                  </div>
                </template>

                <!-- Actual Data Markers -->
                <template v-else>
                  <div
                    v-for="loc in alumniLocations"
                    :key="loc.id"
                    tabindex="0"
                    class="absolute flex justify-center items-end group cursor-pointer hover:z-50 focus:z-50 w-8 h-10 md:w-10 md:h-12 -translate-x-1/2 -translate-y-full focus:outline-none"
                    :style="{ top: loc.top, left: loc.left, zIndex: 10 }"
                    @mouseenter="showTooltip($event, loc)"
                    @mousemove="updateTooltipPos($event)"
                    @mouseleave="hideTooltip"
                    @touchstart.passive="showTooltip($event, loc)"
                    @touchmove.passive="updateTooltipPos($event)"
                    @touchend.passive="hideTooltip"
                  >
                    <!-- Shadow Map -->
                    <div
                      class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-4 h-1.5 md:w-5 md:h-2 bg-black/40 rounded-[100%] blur-[2px] group-hover:scale-50 transition-transform duration-300"
                    ></div>

                    <!-- Pin Icon (Bounce effect on hover) -->
                    <div
                      class="relative drop-shadow-[0_5px_8px_rgba(0,0,0,0.4)] group-hover:-translate-y-2 transition-transform duration-300 origin-bottom"
                    >
                      <PhMapPin
                        weight="fill"
                        class="w-8 h-8 md:w-10 md:h-10"
                        :class="
                          loc.type === 'ptn'
                            ? 'text-sky-500'
                            : loc.type === 'kedinasan'
                            ? 'text-yellow-500'
                            : loc.type === 'instansi'
                            ? 'text-emerald-500'
                            : 'text-blue-500'
                        "
                      />
                      <!-- Inner Dot -->
                      <div
                        class="absolute top-[8px] left-1/2 -translate-x-1/2 w-2.5 h-2.5 md:top-[10px] md:w-3 md:h-3 bg-white rounded-full"
                      ></div>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Jurusan Section -->
      <section
        class="relative -my-12 -mx-6 md:-mx-6 lg:-mx-0 overflow-hidden px-0 md:px-6 mb-10 md:mb-16"
      >
        <div class="w-full max-w-full container relative z-10 mx-auto">
          <!-- HEADER -->
          <div
            class="relative overflow-hidden shadow-2xl min-h-[340px] md:min-h-[450px] flex items-center md:rounded-lg bg-center bg-cover lg:bg-fixed"
            style="background-image: url('/img/jurusan.jpg')"
            :style="{
              backgroundImage: `url(${
                appearanceSettings.programCoverImage || '/img/jurusan.jpg'
              })`,
            }"
          >
            <!-- Overlay Parallax -->
            <div
              class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/80 to-slate-800/50 backdrop-blur-[2px]"
            ></div>
            <div class="relative z-10 -mt-12 pl-4 pr-4 md:px-14 max-w-2xl text-white">
              <h2
                class="text-3xl md:text-5xl font-bold mb-2 md:mb-4"
                style="font-family: 'Oswald', sans-serif"
              >
                Pilihan Jurusan Unggulan
              </h2>
              <p class="text-sm md:text-xl text-gray-200">
                Temukan minat dan bakatmu dengan pilihan jurusan terbaik untuk masa
                depanmu.
              </p>
            </div>
          </div>

          <!-- CARD JURUSAN -->
          <div class="relative z-20 mt-[-80px] md:mt-[-100px]">
            <!-- dibuat 80% dari header -->
            <div class="max-w-[90%] mx-auto md:max-w-[95%]">
              <div
                class="swiper jurusan-swiper rounded-lg md:rounded-2xl overflow-hidden shadow-xl"
              >
                <div class="swiper-wrapper items-stretch">
                  <template v-if="isLoadingPrograms">
                    <!-- Skeleton Card -->
                    <div
                      v-for="i in 3"
                      :key="'skeleton-' + i"
                      class="swiper-slide !h-auto relative min-h-[320px] overflow-hidden border-b border-white/10 md:border-b-0 md:border-r md:border-white/10 bg-slate-800 animate-pulse"
                    >
                      <div
                        class="relative z-10 p-6 sm:p-8 flex flex-col justify-start h-full"
                      >
                        <div class="w-14 h-14 rounded-xl bg-slate-700 mb-4"></div>
                        <div class="h-8 bg-slate-700 rounded w-1/2 mb-4"></div>
                        <div class="space-y-2 mb-4">
                          <div class="h-4 bg-slate-700 rounded w-full"></div>
                          <div class="h-4 bg-slate-700 rounded w-5/6"></div>
                          <div class="h-4 bg-slate-700 rounded w-4/6"></div>
                        </div>
                        <div class="mt-auto w-32 h-4 bg-slate-700 rounded"></div>
                      </div>
                    </div>
                  </template>
                  <template v-else-if="programs.length > 0">
                    <!-- Actual Data -->
                    <div
                      v-for="(program, index) in programs"
                      :key="program.id"
                      class="swiper-slide !h-auto relative min-h-[320px] overflow-hidden border-b border-white/10 md:border-b-0 md:border-r md:border-white/10 group cursor-pointer"
                    >
                      <div
                        class="absolute inset-0 bg-gradient-to-br transition-colors duration-500"
                        :class="getProgramGradient(index)"
                      ></div>
                      <div
                        class="relative z-10 p-6 sm:p-8 flex flex-col justify-start h-full text-white"
                      >
                        <div
                          class="w-14 h-14 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-white mb-4 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-inner"
                        >
                          <component
                            :is="getProgramIcon(program.icon || index)"
                            class="w-7 h-7"
                          />
                        </div>
                        <h3 class="text-2xl font-bold mb-2 text-white">
                          {{ program.title }}
                        </h3>
                        <p
                          class="text-blue-100 text-sm md:text-base mb-4 leading-relaxed line-clamp-3"
                        >
                          {{ program.description || stripTags(program.content) }}
                        </p>
                        <router-link
                          to="/program-jurusan"
                          class="mt-auto inline-flex items-center text-sm font-bold text-white group-hover:text-blue-200 transition-colors w-fit"
                        >
                          Lihat Selengkapnya
                          <PhCaretRight
                            class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1.5 transition-transform duration-300"
                          />
                        </router-link>
                      </div>
                    </div>
                  </template>
                  <template v-else>
                    <!-- Fallback Empty State -->
                    <div class="p-8 text-center text-white w-full">
                      <p>Data program jurusan belum tersedia.</p>
                    </div>
                  </template>
                </div>

                <!-- Jurusan Pagination -->
                <div
                  class="jurusan-pagination absolute bottom-4 left-0 z-30 flex w-full justify-center mb-4"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Berita & Pengumuman Section -->
      <section
        class="relative py-8 md:rounded-lg -mx-6 md:mx-0 overflow-hidden px-6 md:px-0 bg-white dark:bg-slate-800 shadow-lg"
      >
        <div class="w-full max-w-full container mx-auto px-0 md:px-4">
          <!-- Header Section -->
          <div
            class="mb-4 md:mb-10 border-b border-slate-200 dark:border-slate-700 pb-4 md:pb-0"
          >
            <div class="relative block">
              <h2
                class="text-2xl md:text-4xl font-bold text-slate-800 dark:text-white pl-4 border-l-4 border-blue-600 dark:border-blue-500 mb-4 sm:mb-2"
                style="font-family: 'Oswald', sans-serif"
              >
                Berita & Pengumuman
              </h2>
              <div
                class="flex flex-col md:flex-row md:justify-between md:items-end ml-0 sm:ml-4 gap-4"
              >
                <p
                  class="text-slate-800/80 dark:text-slate-300 text-sm md:text-base max-w-2xl"
                >
                  Ikuti terus perkembangan informasi terbaru seputar kegiatan, prestasi,
                  dan agenda penting di lingkungan sekolah kami.
                </p>
                <router-link
                  to="/berita"
                  class="hidden md:inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-semibold transition-colors shrink-0 md:pb-4 md:-mb-px border-b-2 border-transparent hover:border-blue-600 dark:hover:border-blue-400"
                >
                  Lihat berita lainnya
                  <PhCaretRight class="w-4 h-4 ml-1" />
                </router-link>
              </div>
            </div>
          </div>

          <!-- Main Grid Layout -->
          <div
            class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-0 lg:gap-8 items-stretch"
          >
            <!-- KIRI: Bagian Kegiatan & Berita (Grid 1 Atas, 3 Bawah) -->
            <div class="lg:col-span-2 flex flex-col gap-6">
              <!-- Berita Utama (Atas - 1 Besar) -->
              <template v-if="isLoadingNews">
                <div
                  class="rounded-lg overflow-hidden shadow-lg h-[280px] sm:h-[350px] md:h-[400px] w-full bg-slate-200 dark:bg-slate-700 animate-pulse relative"
                >
                  <div class="absolute bottom-0 left-0 p-5 md:p-8 w-full space-y-3">
                    <div
                      class="h-6 w-20 bg-slate-300 dark:bg-slate-600 rounded-full"
                    ></div>
                    <div class="h-8 w-3/4 bg-slate-300 dark:bg-slate-600 rounded"></div>
                    <div class="h-4 w-full bg-slate-300 dark:bg-slate-600 rounded"></div>
                    <div class="h-4 w-5/6 bg-slate-300 dark:bg-slate-600 rounded"></div>
                  </div>
                </div>
              </template>
              <template v-else>
                <router-link
                  v-if="mainNews"
                  :to="`/artikel/${mainNews.slug}`"
                  class="group relative rounded-lg overflow-hidden shadow-lg h-[280px] sm:h-[350px] md:h-[400px] w-full block transition-all duration-500"
                >
                  <img
                    v-if="getNewsImage(mainNews)"
                    :src="getNewsImage(mainNews)"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                    :alt="mainNews.title"
                  />
                  <div
                    v-else
                    class="absolute inset-0 w-full h-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center"
                  >
                    <PhNewspaper class="w-16 h-16 text-slate-400 opacity-50" />
                  </div>
                  <div
                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"
                  ></div>
                  <div class="absolute bottom-0 left-0 p-5 md:p-8 w-full">
                    <span
                      class="inline-block px-3 py-1 mb-3 text-sm font-bold tracking-wide text-white bg-blue-600 rounded-full"
                      style="font-family: 'Kalam', cursive"
                      >{{ mainNews.category || "Kegiatan" }}</span
                    >
                    <h3
                      class="text-lg sm:text-xl md:text-3xl font-bold text-white mb-1 sm:mb-2 group-hover:text-blue-300 transition-colors line-clamp-2"
                    >
                      {{ mainNews.title }}
                    </h3>
                    <p
                      class="text-gray-200 text-xs sm:text-sm md:text-base line-clamp-2 sm:line-clamp-3"
                    >
                      {{ mainNews.excerpt || stripTags(mainNews.content) }}
                    </p>
                    <div
                      class="mt-4 flex items-center justify-between text-gray-300 text-xs md:text-sm"
                    >
                      <div class="flex items-center">
                        <PhCalendarBlank class="w-4 h-4 mr-1.5" />
                        {{ formatDate(mainNews.created_at) }}
                      </div>
                      <span class="flex items-center font-medium">
                        <PhEye class="w-4 h-4 mr-1.5 text-blue-400" />
                        {{ mainNews.views || 0 }}
                      </span>
                    </div>
                  </div>
                </router-link>
                <div
                  v-else
                  class="h-[280px] sm:h-[350px] md:h-[400px] w-full bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center"
                >
                  <span class="text-slate-400 dark:text-slate-500"
                    >Berita Belum Tersedia</span
                  >
                </div>
              </template>

              <!-- Berita Pendukung (Bawah - 3 Kecil) -->
              <div class="relative">
                <div
                  class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6"
                >
                  <template v-if="isLoadingNews">
                    <div
                      v-for="i in 3"
                      :key="'skel-news-' + i"
                      class="bg-white dark:bg-slate-800 rounded-lg overflow-hidden shadow-sm border border-gray-100 dark:border-slate-700 flex flex-row sm:flex-col h-full animate-pulse"
                      :class="{ 'sm:hidden xl:flex': i === 3 }"
                    >
                      <div class="w-2/5 sm:w-full sm:h-40 relative shrink-0">
                        <div
                          class="absolute inset-0 bg-slate-200 dark:bg-slate-700"
                        ></div>
                      </div>
                      <div
                        class="p-3 sm:p-4 flex flex-col flex-grow justify-center sm:justify-start space-y-3"
                      >
                        <div
                          class="h-4 w-16 bg-slate-200 dark:bg-slate-700 rounded-full"
                        ></div>
                        <div
                          class="h-4 w-full bg-slate-200 dark:bg-slate-700 rounded"
                        ></div>
                        <div
                          class="h-4 w-full bg-slate-200 dark:bg-slate-700 rounded"
                        ></div>
                        <div
                          class="h-3 w-4/5 bg-slate-200 dark:bg-slate-700 rounded"
                        ></div>
                        <div class="mt-auto pt-1 sm:pt-3 flex justify-between">
                          <div
                            class="h-3 w-24 bg-slate-200 dark:bg-slate-700 rounded"
                          ></div>
                          <div
                            class="h-3 w-10 bg-slate-200 dark:bg-slate-700 rounded"
                          ></div>
                        </div>
                      </div>
                    </div>
                  </template>
                  <template v-else>
                    <router-link
                      v-for="(news, index) in subNews"
                      :key="news.id"
                      :to="`/artikel/${news.slug}`"
                      class="group bg-white dark:bg-slate-800 rounded-lg overflow-hidden shadow-sm hover:shadow-lg border border-gray-100 dark:border-slate-700 flex flex-row sm:flex-col h-full transition-all duration-500"
                      :class="{ 'sm:hidden xl:flex': index === 2 }"
                    >
                      <div
                        class="w-2/5 sm:w-full sm:h-40 overflow-hidden relative shrink-0"
                      >
                        <img
                          v-if="getNewsImage(news)"
                          :src="getNewsImage(news)"
                          class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                          :alt="news.title"
                        />
                        <div
                          v-else
                          class="absolute inset-0 w-full h-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center"
                        >
                          <PhNewspaper
                            class="w-8 h-8 sm:w-10 sm:h-10 text-slate-400 opacity-50"
                          />
                        </div>
                      </div>
                      <div
                        class="p-3 sm:p-4 flex flex-col flex-grow justify-center sm:justify-start"
                      >
                        <span
                          class="text-xs sm:text-sm font-bold tracking-wide text-blue-600 mb-1 sm:mb-1.5"
                          style="font-family: 'Kalam', cursive"
                          >{{ news.category || "Berita" }}</span
                        >
                        <h4
                          class="font-bold text-blue-950 dark:text-slate-100 text-sm sm:text-base mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2"
                        >
                          {{ news.title }}
                        </h4>
                        <p
                          class="text-xs text-gray-500 dark:text-slate-400 line-clamp-2 mb-2"
                        >
                          {{ news.excerpt || stripTags(news.content) }}
                        </p>
                        <div
                          class="mt-auto flex items-center justify-between text-gray-500 dark:text-slate-400 text-[10px] sm:text-xs pt-1 sm:pt-3"
                        >
                          <div class="flex items-center">
                            <PhCalendarBlank class="w-3 sm:w-3.5 h-3 sm:h-3.5 mr-1" />
                            {{ formatDate(news.created_at) }}
                          </div>
                          <span class="flex items-center font-medium">
                            <PhEye
                              class="w-3 sm:w-4 h-3 sm:h-4 mr-1 sm:mr-1.5 text-blue-400"
                            />
                            {{ news.views || 0 }}
                          </span>
                        </div>
                      </div>
                    </router-link>
                  </template>
                </div>

                <!-- Mobile "Lihat berita lainnya" Link -->
                <div
                  class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-end md:hidden"
                >
                  <router-link
                    to="/berita"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-semibold transition-colors"
                  >
                    Lihat berita lainnya
                    <PhCaretRight class="w-4 h-4 ml-1" />
                  </router-link>
                </div>
              </div>
            </div>

            <!-- KANAN: Bagian Pengumuman (Auto Scroll-Y) -->
            <div
              class="lg:col-span-1 relative h-[550px] lg:h-auto mt-0 md:mt-8 lg:mt-0 fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-300 ease-out"
            >
              <div
                class="absolute inset-0 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-100 dark:border-slate-700 flex flex-col overflow-hidden"
              >
                <!-- Header Pengumuman -->
                <div
                  class="p-5 md:p-6 border-b border-blue-100 dark:border-slate-700/50 bg-gray-50 dark:bg-slate-700/50 z-20 flex items-center justify-between shadow-sm"
                >
                  <h3
                    class="text-md md:text-xl font-bold text-slate-800 dark:text-slate-200 flex items-center"
                  >
                    <PhBell class="w-5 h-5 mr-2 text-slate-800 dark:text-slate-200" />
                    Pengumuman
                  </h3>
                </div>

                <!-- Scroll Y Container -->
                <div
                  ref="announcementsWrapper"
                  class="relative flex-1 overflow-hidden group bg-white dark:bg-slate-800 z-10 min-h-0"
                >
                  <!-- Efek Gradasi Atas-Bawah (Biar scrollnya terlihat smooth) -->
                  <div
                    v-if="shouldAutoScroll && !isLoadingNews"
                    class="absolute top-0 left-0 w-full h-10 bg-gradient-to-b from-white dark:from-slate-800 to-transparent z-20 pointer-events-none"
                  ></div>
                  <div
                    v-if="shouldAutoScroll && !isLoadingNews"
                    class="absolute bottom-0 left-0 w-full h-10 bg-gradient-to-t from-white dark:from-slate-800 to-transparent z-20 pointer-events-none"
                  ></div>

                  <template v-if="isLoadingNews">
                    <div class="flex flex-col p-5 space-y-6">
                      <div
                        v-for="i in 4"
                        :key="'skel-ann-' + i"
                        class="flex items-start animate-pulse border-b border-blue-50 dark:border-slate-700/50 pb-5"
                      >
                        <div
                          class="w-[60px] h-[60px] rounded-lg bg-slate-200 dark:bg-slate-700 mr-4 shrink-0"
                        ></div>
                        <div class="flex-1 space-y-2 py-1">
                          <div
                            class="h-4 w-full bg-slate-200 dark:bg-slate-700 rounded"
                          ></div>
                          <div
                            class="h-4 w-5/6 bg-slate-200 dark:bg-slate-700 rounded"
                          ></div>
                          <div
                            class="h-3 w-4/5 bg-slate-200 dark:bg-slate-700 rounded mt-2"
                          ></div>
                        </div>
                      </div>
                    </div>
                  </template>
                  <template v-else>
                    <!-- Wrapper Animasi Scroll Y -->
                    <!-- group-hover pause akan membuat scroll berhenti saat mouse diletakkan ke area ini -->
                    <div
                      ref="announcementsContent"
                      class="flex flex-col"
                      :class="{
                        'animate-scroll-y group-hover:[animation-play-state:paused]': shouldAutoScroll,
                        'overflow-y-auto custom-scrollbar': !shouldAutoScroll,
                      }"
                    >
                      <!-- Karena butuh infinite loop, isi pengumuman akan kita duplikat 2x -->
                      <template v-if="announcements.length > 0">
                        <template
                          v-for="i in shouldAutoScroll ? 2 : 1"
                          :key="'loop-' + i"
                        >
                          <div class="flex flex-col">
                            <!-- Item Pengumuman -->
                            <router-link
                              v-for="(item, index) in announcements"
                              :key="item.id + '-' + i"
                              :to="`/artikel/${item.slug}`"
                              class="p-5 border-b border-blue-50 dark:border-slate-700/50 hover:bg-blue-100/50 dark:hover:bg-slate-700 transition-colors flex items-start"
                            >
                              <!-- Date Badge -->
                              <div
                                class="bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 rounded-lg p-2 mr-4 text-center min-w-[60px] flex-shrink-0"
                                style="font-family: 'Kalam', cursive"
                              >
                                <span class="block text-xl font-bold leading-none">{{
                                  formatDay(item.created_at)
                                }}</span>
                                <span
                                  class="block text-xs uppercase mt-1 font-semibold"
                                  >{{ formatMonth(item.created_at) }}</span
                                >
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
                                  {{ item.excerpt || stripTags(item.content) }}
                                </p>
                              </div>
                            </router-link>
                          </div>
                        </template>
                      </template>
                      <div
                        v-else
                        class="p-6 text-center text-gray-500 dark:text-gray-400"
                      >
                        Tidak ada pengumuman saat ini.
                      </div>
                    </div>
                  </template>
                </div>

                <!-- Action Button Bawah -->
                <div
                  class="p-4 bg-white dark:bg-slate-800 z-20 border-t border-white dark:border-slate-800 text-center shadow-md"
                >
                  <router-link
                    to="/berita"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center"
                  >
                    Lihat Semua Pengumuman
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Video Profil & Galeri Section -->
      <section
        class="relative py-8 md:py-6 mt-0 md:mt-16 -mx-6 md:mx-0 bg-slate-900 overflow-hidden px-6 md:px-4 lg:px-6 mb-0 md:mb-12 md:rounded-lg shadow-xl bg-center bg-cover md:bg-fixed"
        :style="
          appearanceSettings.galleryBackgroundImage
            ? { backgroundImage: `url(${appearanceSettings.galleryBackgroundImage})` }
            : {}
        "
      >
        <!-- Overlay Parallax -->
        <div
          class="absolute inset-0 bg-gradient-to-b from-blue-900/80 via-slate-900/90 to-black/95 backdrop-blur-[2px]"
        ></div>

        <div class="container relative z-10 mx-auto">
          <!-- Header Section -->
          <div
            class="mb-8 fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out"
          >
            <div class="relative block mt-4">
              <h2
                class="text-2xl md:text-4xl font-bold text-white mb-4 sm:mb-2 tracking-wide"
                style="font-family: 'Oswald', sans-serif"
              >
                Video Profil & Galeri
              </h2>
              <p class="text-blue-200 text-sm md:text-base max-w-2xl">
                Kenali lebih dekat lingkungan, fasilitas, dan berbagai kegiatan seru di
                Sekolah kami melalui tayangan video dan galeri foto.
              </p>
            </div>
          </div>

          <!-- Main Grid Layout -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 items-stretch">
            <!-- KIRI: Video Profil Utama -->
            <template v-if="isLoadingSchoolVideo">
              <div
                class="lg:col-span-2 relative rounded-lg overflow-hidden shadow-2xl h-[280px] sm:h-[400px] md:h-[450px] w-full bg-slate-800/50 animate-pulse border border-slate-700/50"
              >
                <!-- Skeleton Play Button -->
                <div class="absolute inset-0 flex items-center justify-center z-20">
                  <div class="w-16 h-16 md:w-20 md:h-20 bg-slate-700 rounded-full"></div>
                </div>
                <!-- Skeleton Text Bottom -->
                <div class="absolute bottom-0 left-0 p-5 md:p-8 w-full">
                  <div class="h-6 w-3/4 md:w-1/2 bg-slate-700 rounded mb-3"></div>
                  <div class="h-4 w-full bg-slate-700 rounded mb-2"></div>
                  <div class="h-4 w-5/6 bg-slate-700 rounded"></div>
                </div>
              </div>
            </template>
            <template v-else>
              <div
                v-if="schoolVideoUrl"
                class="lg:col-span-2 relative group rounded-lg overflow-hidden shadow-2xl h-[280px] sm:h-[400px] md:h-[450px] w-full block fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-100 ease-out"
              >
                <template v-if="!isVideoPlaying">
                  <div
                    class="absolute inset-0 cursor-pointer"
                    @click="isVideoPlaying = true"
                  >
                    <img
                      :src="videoThumbnail"
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
                        <PhPlay class="w-8 h-8 md:w-10 md:h-10 ml-1" weight="fill" />
                      </div>
                    </div>

                    <!-- Text Bottom -->
                    <div
                      class="absolute bottom-0 left-0 p-5 md:p-8 w-full bg-gradient-to-t from-blue-950/90 via-blue-950/40 to-transparent z-10"
                    >
                      <h3
                        class="text-xl md:text-3xl font-bold text-white mb-2 group-hover:text-blue-200 transition-colors"
                        style="font-family: 'Kalam', cursive"
                      >
                        {{ schoolVideoTitle || "Video Profil Sekolah" }}
                      </h3>
                      <p class="text-gray-200 text-sm md:text-base line-clamp-2">
                        {{
                          schoolVideoDesc ||
                          "Saksikan cuplikan fasilitas, metode pembelajaran, dan prestasi kami."
                        }}
                      </p>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <iframe
                    :src="videoEmbedUrl"
                    class="absolute inset-0 w-full h-full"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                  ></iframe>
                </template>
              </div>
              <div
                v-else
                class="lg:col-span-2 relative group rounded-lg overflow-hidden shadow-2xl h-[280px] sm:h-[400px] md:h-[450px] w-full flex items-center justify-center bg-blue-900/40 border border-blue-800/40 cursor-default fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-100 ease-out"
              >
                <div class="text-center">
                  <div
                    class="w-16 h-16 md:w-20 md:h-20 bg-blue-800/50 rounded-full flex items-center justify-center text-blue-300 mx-auto mb-4"
                  >
                    <PhPlay class="w-8 h-8 md:w-10 md:h-10 ml-1" weight="fill" />
                  </div>
                  <h3 class="text-xl md:text-2xl font-bold text-blue-200 mb-2">
                    Video Profil Belum Tersedia
                  </h3>
                  <p class="text-blue-300/80 text-sm md:text-base">
                    Video profil sekolah akan segera hadir.
                  </p>
                </div>
              </div>
            </template>

            <!-- KANAN: Grid Galeri 2x2 -->
            <div
              class="lg:col-span-1 grid grid-cols-2 gap-4 h-[300px] sm:h-[400px] md:h-[450px] fade-on-scroll opacity-0 translate-y-10 transition-all duration-700 delay-200 ease-out"
            >
              <!-- Foto 1, 2, 3 Dinamis -->
              <router-link
                v-for="(gallery, index) in galleriesByCategory"
                :key="index"
                :to="{
                  path: '/galeri',
                  query: {
                    category: gallery.category.replace(/\s+/g, '-').toLowerCase(),
                  },
                }"
                class="group relative rounded-lg overflow-hidden shadow-sm h-full block"
              >
                <img
                  :src="getImageUrl(gallery.image)"
                  class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                  :alt="gallery.category"
                />
                <div
                  class="absolute inset-0 bg-gradient-to-t from-blue-950/90 via-blue-950/20 to-transparent"
                ></div>
                <div class="absolute bottom-0 left-0 p-3 md:p-4 w-full z-10">
                  <h4
                    class="text-white font-bold text-sm md:text-base leading-tight group-hover:text-blue-300 transition-colors line-clamp-2 capitalize"
                  >
                    {{ gallery.category }}
                  </h4>
                </div>
              </router-link>

              <!-- Skeleton / Empty State Jika Galeri Kurang Dari 3 -->
              <div
                v-for="i in 3 - galleriesByCategory.length"
                :key="'empty-' + i"
                class="group relative rounded-lg overflow-hidden shadow-sm h-full block bg-blue-900/30 border border-blue-800/30 flex flex-col items-center justify-center text-blue-300/50"
              >
                <PhImage class="w-8 h-8 mb-2 opacity-50" />
                <span class="text-xs font-semibold opacity-50">Belum ada foto</span>
              </div>

              <!-- Foto 4 (Lihat Semua) -->
              <router-link
                to="/galeri"
                class="group relative rounded-lg overflow-hidden shadow-sm h-full block bg-blue-900/40 border border-blue-800/40"
              >
                <img
                  v-if="fourthGalleryImage"
                  :src="getImageUrl(fourthGalleryImage)"
                  class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                  alt="Lihat Galeri"
                />
                <div
                  class="absolute inset-0 flex items-center justify-center bg-blue-950/70 backdrop-blur-[2px] group-hover:bg-blue-900/80 transition-colors"
                >
                  <div class="text-center">
                    <div
                      class="bg-blue-500/20 rounded-full w-10 h-10 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform duration-300"
                    >
                      <PhImage class="w-5 h-5 text-blue-300" />
                    </div>
                    <span class="text-blue-300 text-sm font-semibold">Lihat Galeri</span>
                  </div>
                </div>
              </router-link>
            </div>
          </div>
        </div>
      </section>

      <!-- Agenda Sekolah Section -->
      <section
        class="relative py-8 md:py-6 pb-16 md:pb-0 mt-0 md:mt-16 -mx-6 md:mx-0 bg-white dark:bg-slate-800 overflow-hidden px-6 md:px-2 mb-0 md:mb-12 md:rounded-xl shadow-xl"
      >
        <div class="container mx-auto px-0 md:px-2">
          <!-- Header Section -->
          <div class="mb-6 md:mb-10">
            <div class="relative block">
              <h2
                class="text-2xl md:text-4xl font-bold text-slate-800 dark:text-white pl-4 border-l-4 border-blue-600 dark:border-blue-500 mb-4 sm:mb-2"
                style="font-family: 'Oswald', sans-serif"
              >
                Agenda Sekolah
              </h2>
              <p
                class="text-slate-800/80 dark:text-slate-300 text-sm md:text-base ml-0 sm:ml-5 max-w-2xl"
              >
                Jadwal kegiatan akademik dan non-akademik. Pantau kalender agar tidak
                tertinggal event penting kami.
              </p>
            </div>
          </div>

          <!-- Main Grid Layout -->
          <div
            class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-stretch md:pb-8 md:pr-2"
          >
            <!-- KIRI: Kalender -->
            <div
              class="lg:col-span-5 bg-white dark:bg-slate-800 rounded-lg shadow-md border border-gray-200 dark:border-slate-700 p-5 md:p-6 flex flex-col"
            >
              <div class="flex items-center justify-between mb-6">
                <button
                  @click="prevMonth"
                  class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors focus:outline-none"
                >
                  <PhCaretLeft class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                </button>
                <h3 class="text-lg font-bold text-blue-950 dark:text-blue-200">
                  {{ calendarData.monthName }} {{ calendarData.year }}
                </h3>
                <button
                  @click="nextMonth"
                  class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors focus:outline-none"
                >
                  <PhCaretRight class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                </button>
              </div>

              <div class="grid grid-cols-7 gap-2 text-center mb-2">
                <div class="text-xs font-bold text-red-500 dark:text-red-400 py-1">
                  Min
                </div>
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
                <template v-if="isLoadingAgendas">
                  <div
                    v-for="i in 35"
                    :key="'skel-cal-' + i"
                    class="py-4 mx-0.5 rounded-lg bg-slate-100 dark:bg-slate-700/50 animate-pulse"
                  ></div>
                </template>
                <template v-else>
                  <template v-for="(item, index) in calendarGrid" :key="'grid-' + index">
                    <!-- Empty Offset -->
                    <div v-if="item.type === 'empty'" class="py-2"></div>

                    <!-- Normal Day -->
                    <div
                      v-else-if="item.type === 'day'"
                      class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
                    >
                      {{ item.day }}
                    </div>

                    <!-- Event Spanning Day(s) -->
                    <div
                      v-else-if="item.type === 'event'"
                      :class="[
                        colSpanClasses[item.span],
                        'relative mx-0.5 my-0.5 rounded-md overflow-hidden cursor-pointer transition-colors flex flex-col justify-between',
                        themeClasses[item.event.color].eventBg,
                      ]"
                      @click="scrollToAgenda(item.event)"
                      :title="item.event.title"
                    >
                      <div
                        :class="[
                          themeClasses[item.event.color].eventHeaderBg,
                          themeClasses[item.event.color].eventHeaderText,
                          'text-[10px] font-bold px-2 py-0.5 text-left truncate',
                        ]"
                      >
                        {{ item.event.title }}
                      </div>
                      <div
                        class="grid w-full py-1"
                        :style="`grid-template-columns: repeat(${item.span}, minmax(0, 1fr))`"
                      >
                        <div
                          v-for="d in item.days"
                          :key="'day-' + d"
                          :class="[
                            themeClasses[item.event.color].eventDayText,
                            'font-bold',
                          ]"
                        >
                          {{ d }}
                        </div>
                      </div>
                    </div>
                  </template>
                </template>
              </div>

              <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-700">
                <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">
                  Keterangan Label
                </h4>
                <div class="flex flex-wrap gap-3">
                  <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                    <span class="w-3 h-3 rounded-sm bg-slate-600 mr-2"></span> Akademik
                  </div>
                  <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                    <span class="w-3 h-3 rounded-sm bg-gray-600 mr-2"></span> Guru/Staf
                  </div>
                  <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                    <span class="w-3 h-3 rounded-sm bg-zinc-600 mr-2"></span> Kegiatan
                  </div>
                  <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                    <span class="w-3 h-3 rounded-sm bg-neutral-600 mr-2"></span> Seminar &
                    Acara
                  </div>
                </div>
              </div>
            </div>

            <!-- KANAN: Daftar Agenda -->
            <div class="lg:col-span-7 relative h-[540px] md:h-[640px] lg:h-auto">
              <div
                class="absolute inset-0 bg-white dark:bg-slate-800 rounded-lg shadow-md border border-gray-200 dark:border-slate-700 overflow-hidden flex flex-col"
              >
                <div
                  class="p-5 md:p-6 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex items-center justify-between shrink-0"
                >
                  <h3
                    class="text-lg md:text-xl font-bold text-slate-800 dark:text-slate-200 flex items-center"
                    style="font-family: 'Poppins', sans-serif"
                  >
                    <PhCalendar class="w-6 h-6 mr-2 text-slate-600 dark:text-slate-200" />
                    Daftar Agenda
                  </h3>
                </div>
                <div class="relative flex-1 min-h-0" ref="agendaListContainer">
                  <div
                    class="absolute inset-0 p-5 md:p-6 flex flex-col gap-4 overflow-y-auto [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-slate-600 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300 dark:hover:[&::-webkit-scrollbar-thumb]:bg-slate-500"
                  >
                    <template v-if="isLoadingAgendas">
                      <div
                        v-for="i in 4"
                        :key="'skel-ag-' + i"
                        class="flex flex-row items-center gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl border border-gray-100 dark:border-slate-700 animate-pulse shrink-0 h-[115px] sm:h-auto"
                      >
                        <div
                          class="w-[65px] h-[80px] sm:w-[95px] sm:h-[95px] rounded-xl bg-slate-200 dark:bg-slate-700 shrink-0"
                        ></div>
                        <div class="flex flex-col justify-center flex-1 space-y-2">
                          <div
                            class="h-5 w-3/4 bg-slate-200 dark:bg-slate-700 rounded"
                          ></div>
                          <div
                            class="h-4 w-1/2 bg-slate-200 dark:bg-slate-700 rounded mt-2"
                          ></div>
                          <div
                            class="h-4 w-2/3 bg-slate-200 dark:bg-slate-700 rounded"
                          ></div>
                        </div>
                      </div>
                    </template>
                    <template v-else-if="agendas.length > 0">
                      <div
                        :ref="
                          (el) => {
                            if (el) agendaElements[agenda.id] = el;
                          }
                        "
                        v-for="(agenda, index) in agendas"
                        :key="index"
                        class="flex flex-row items-center gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl border hover:shadow-md transition-all duration-300 group cursor-pointer shrink-0 relative"
                        :class="[
                          themeClasses[agenda.color].card,
                          highlightedAgendaId === agenda.id ? 'highlight-agenda' : '',
                        ]"
                      >
                        <!-- Date Box (Calendar Style) -->
                        <div
                          class="rounded-xl flex flex-col items-center justify-center min-w-[65px] w-[65px] sm:min-w-[95px] sm:w-[95px] border transition-all duration-300 overflow-hidden shrink-0"
                          style="font-family: 'Kalam', cursive"
                          :class="themeClasses[agenda.color].dateBox"
                        >
                          <div
                            class="w-full text-center py-1 sm:py-1.5 transition-colors"
                            style="font-family: 'Kalam', cursive"
                            :class="themeClasses[agenda.color].monthBg"
                          >
                            <span
                              class="text-[10px] sm:text-xs font-bold uppercase tracking-wider"
                              :class="themeClasses[agenda.color].monthText"
                              >{{ agenda.month }}</span
                            >
                          </div>
                          <div
                            class="w-full text-center py-1.5 sm:py-3 transition-colors flex items-center justify-center min-h-[48px]"
                            :class="themeClasses[agenda.color].dateBg"
                          >
                            <span
                              class="font-extrabold leading-none tracking-tight"
                              :class="[
                                themeClasses[agenda.color].dateText,
                                agenda.date.length > 2
                                  ? 'text-lg sm:text-xl'
                                  : 'text-xl sm:text-3xl',
                              ]"
                              style="font-family: 'Kalam', cursive"
                              >{{ agenda.date }}</span
                            >
                          </div>
                        </div>

                        <!-- Details -->
                        <div
                          class="flex flex-col justify-center flex-1"
                          style="font-family: 'Poppins', sans-serif"
                        >
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
                            class="flex flex-col gap-1.5 sm:gap-2 mt-2 transition-colors"
                            :class="themeClasses[agenda.color].infoText"
                          >
                            <!-- Waktu -->
                            <div class="flex items-start text-[11px] sm:text-xs">
                              <svg
                                class="w-4 h-4 mr-2 mt-0.5 shrink-0"
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
                              <div class="flex flex-wrap items-center mt-0.5">
                                <span
                                  class="font-semibold text-gray-800 dark:text-gray-100 mr-1"
                                  >Waktu :</span
                                >
                                <span
                                  class="font-medium text-gray-600 dark:text-gray-300"
                                  >{{ agenda.time }}</span
                                >
                              </div>
                            </div>

                            <!-- Lokasi -->
                            <div class="flex items-start text-[11px] sm:text-xs">
                              <svg
                                class="w-4 h-4 mr-2 mt-0.5 shrink-0"
                                :class="themeClasses[agenda.color].infoIcon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                              >
                                <path
                                  d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"
                                />
                                <circle cx="12" cy="10" r="3" />
                              </svg>
                              <div class="flex flex-wrap items-center mt-0.5">
                                <span
                                  class="font-semibold text-gray-800 dark:text-gray-100 mr-1"
                                  >Tempat :</span
                                >
                                <span
                                  class="font-medium text-gray-600 dark:text-gray-300"
                                  >{{ agenda.loc }}</span
                                >
                              </div>
                            </div>

                            <!-- File Lampiran -->
                            <div
                              v-if="agenda.file"
                              class="flex items-start text-[11px] sm:text-xs pt-1"
                            >
                              <PhPaperclip
                                class="w-4 h-4 mr-2 mt-0.5 shrink-0"
                                :class="themeClasses[agenda.color].infoIcon"
                              />
                              <div class="flex flex-wrap items-center mt-0.5">
                                <span
                                  class="font-semibold text-gray-800 dark:text-gray-100 mr-1"
                                  >Lampiran :</span
                                >
                                <a
                                  :href="agenda.file"
                                  target="_blank"
                                  class="inline-flex items-center font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:underline transition-colors"
                                  @click.stop
                                >
                                  Lihat Dokumen
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </template>
                    <template v-else>
                      <div
                        class="p-6 text-center text-gray-500 dark:text-gray-400 m-auto"
                      >
                        Tidak ada agenda saat ini.
                      </div>
                    </template>
                  </div>
                  <!-- Bottom Fade & Scroll Indicator -->
                  <div
                    class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white dark:from-slate-800 to-transparent pointer-events-none z-10 flex justify-center items-end"
                  >
                    <div
                      class="text-sm font-semibold text-gray-500 flex items-center animate-bounce pb-2"
                    >
                      <PhArrowDown class="w-4 h-4 mr-1.5" />
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
          class="w-full max-w-full relative mx-auto mb-0 md:mb-12 p-8 px-4 md:px-6 lg:px-4 md:rounded-xl bg-center bg-cover md:bg-fixed overflow-hidden shadow-xl bg-slate-900"
          :style="
            appearanceSettings.ppdbBackgroundImage
              ? { backgroundImage: `url(${appearanceSettings.ppdbBackgroundImage})` }
              : {}
          "
        >
          <!-- Overlay Parallax -->
          <div
            class="absolute inset-0 bg-gradient-to-b from-blue-900/80 via-slate-900/90 to-black/95 backdrop-blur-[2px]"
          ></div>

          <div
            class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-12 items-stretch"
          >
            <!-- FAQ Kolom Kiri -->
            <div class="flex flex-col justify-center p-0 sm:p-4">
              <div class="mb-8 md:mb-10 text-center lg:text-left">
                <h2
                  class="text-2xl md:text-4xl font-bold text-white mb-3"
                  style="font-family: 'Oswald', sans-serif"
                >
                  Pertanyaan Seputar PPDB
                </h2>
                <p
                  class="text-white font-medium text-sm md:text-base max-w-2xl mx-auto lg:mx-0"
                >
                  Jawaban atas beberapa pertanyaan yang paling sering ditanyakan oleh
                  calon siswa dan orang tua.
                </p>
              </div>

              <div v-if="isLoadingPpdb" class="w-full flex flex-col gap-4">
                <div
                  v-for="i in 4"
                  :key="'skel-faq-' + i"
                  class="bg-white dark:bg-slate-800/80 border border-gray-100 dark:border-slate-700 rounded-xl shadow-md p-5 md:p-6 animate-pulse backdrop-blur-sm"
                >
                  <div class="h-5 bg-gray-200 dark:bg-slate-700 rounded w-3/4 mb-2"></div>
                </div>
              </div>

              <div v-else-if="faqs.length > 0" class="w-full flex flex-col gap-4">
                <div
                  v-for="(faq, index) in faqs"
                  :key="index"
                  class="bg-white dark:bg-slate-800/80 border border-gray-100 dark:border-slate-700 rounded-xl shadow-md backdrop-blur-sm"
                >
                  <button
                    @click="toggleFaq(index)"
                    class="w-full text-left px-5 md:px-6 py-4 font-semibold text-gray-900 dark:text-white flex justify-between items-center hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors rounded-xl focus:outline-none"
                  >
                    <span class="pr-0 sm:pr-2 text-sm md:text-base">{{
                      faq.question || faq.q
                    }}</span>
                    <svg
                      class="w-5 h-5 text-blue-600 dark:text-blue-400 transform transition-transform duration-300 flex-shrink-0"
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
                    class="transition-all duration-300 px-5 md:px-6 text-gray-700 dark:text-gray-300 text-sm md:text-base"
                  >
                    <div class="border-t border-gray-100 dark:border-slate-700 pt-3">
                      {{ faq.answer || faq.a }}
                    </div>
                  </div>
                </div>
              </div>
              <div
                v-else
                class="text-white/80 p-4 border border-white/20 rounded-lg text-center backdrop-blur-sm mt-4"
              >
                Belum ada informasi FAQ PPDB yang tersedia.
              </div>
            </div>

            <!-- CTA Kolom Kanan -->
            <div
              class="relative py-12 md:py-16 bg-gradient-to-br from-blue-600 to-blue-800 border border-blue-500/30 px-6 md:px-10 text-center rounded-lg shadow-xl overflow-hidden flex flex-col justify-center items-center"
            >
              <!-- Decoration elements -->
              <div
                class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"
              ></div>
              <div
                class="absolute bottom-0 left-0 w-64 h-64 bg-blue-400 opacity-20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"
              ></div>

              <div class="relative z-10 w-full max-w-xl mx-auto">
                <!-- Skeleton CTA -->
                <template v-if="isLoadingPpdb">
                  <div class="animate-pulse flex flex-col items-center w-full">
                    <div class="h-6 bg-white/20 rounded-full w-32 mb-6"></div>
                    <div class="h-10 bg-white/20 rounded-lg w-3/4 mb-4"></div>
                    <div class="h-10 bg-white/20 rounded-lg w-2/4 mb-6"></div>
                    <div
                      class="h-4 bg-white/20 rounded w-full max-w-md mx-auto mb-2"
                    ></div>
                    <div
                      class="h-4 bg-white/20 rounded w-4/5 max-w-md mx-auto mb-8"
                    ></div>
                    <!-- skeleton countdown -->
                    <div class="flex justify-center gap-3 sm:gap-4 mb-8">
                      <div
                        v-for="i in 4"
                        :key="'skel-cd-' + i"
                        class="bg-white/10 rounded-lg w-[60px] h-[60px] sm:w-[70px] sm:h-[70px] border border-white/20"
                      ></div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center w-full">
                      <div class="h-12 bg-white/20 rounded-xl w-full sm:w-40"></div>
                      <div class="h-12 bg-white/20 rounded-xl w-full sm:w-40"></div>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <span
                    class="inline-block px-4 py-1.5 mb-6 text-xs md:text-sm font-semibold text-blue-950 bg-yellow-400 rounded-full shadow-sm"
                    >TAHUN AJARAN {{ ppdbInfo.academic_year }}</span
                  >
                  <h2
                    class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-6 leading-tight"
                    style="font-family: 'Oswald', sans-serif"
                  >
                    {{ ppdbInfo.title }}
                  </h2>
                  <p class="text-blue-100 text-sm md:text-base mb-6 max-w-md mx-auto">
                    {{ ppdbInfo.description }}
                  </p>

                  <!-- Countdown Timer -->
                  <div class="flex justify-center gap-3 sm:gap-4 mb-8">
                    <div
                      class="group flex flex-col items-center bg-white/10 backdrop-blur-sm rounded-lg p-2 sm:p-3 min-w-[60px] sm:min-w-[70px] border border-white/20 hover:bg-white/20 hover:-translate-y-1 hover:border-white/40 hover:shadow-[0_0_15px_rgba(255,255,255,0.2)] transition-all duration-300 cursor-default"
                    >
                      <span
                        class="text-xl sm:text-2xl font-bold text-white mb-1 transform group-hover:scale-110 group-hover:text-yellow-300 transition-all duration-300 inline-block"
                        style="font-family: 'Oswald', sans-serif"
                        >{{ ppdbCountdown.days }}</span
                      >
                      <span
                        class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wider group-hover:text-white transition-colors duration-300"
                        >Hari</span
                      >
                    </div>
                    <div
                      class="group flex flex-col items-center bg-white/10 backdrop-blur-sm rounded-lg p-2 sm:p-3 min-w-[60px] sm:min-w-[70px] border border-white/20 hover:bg-white/20 hover:-translate-y-1 hover:border-white/40 hover:shadow-[0_0_15px_rgba(255,255,255,0.2)] transition-all duration-300 cursor-default"
                    >
                      <span
                        class="text-xl sm:text-2xl font-bold text-white mb-1 transform group-hover:scale-110 group-hover:text-yellow-300 transition-all duration-300 inline-block"
                        style="font-family: 'Oswald', sans-serif"
                        >{{ ppdbCountdown.hours }}</span
                      >
                      <span
                        class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wider group-hover:text-white transition-colors duration-300"
                        >Jam</span
                      >
                    </div>
                    <div
                      class="group flex flex-col items-center bg-white/10 backdrop-blur-sm rounded-lg p-2 sm:p-3 min-w-[60px] sm:min-w-[70px] border border-white/20 hover:bg-white/20 hover:-translate-y-1 hover:border-white/40 hover:shadow-[0_0_15px_rgba(255,255,255,0.2)] transition-all duration-300 cursor-default"
                    >
                      <span
                        class="text-xl sm:text-2xl font-bold text-white mb-1 transform group-hover:scale-110 group-hover:text-yellow-300 transition-all duration-300 inline-block"
                        style="font-family: 'Oswald', sans-serif"
                        >{{ ppdbCountdown.minutes }}</span
                      >
                      <span
                        class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wider group-hover:text-white transition-colors duration-300"
                        >Menit</span
                      >
                    </div>
                    <div
                      class="group flex flex-col items-center bg-white/10 backdrop-blur-sm rounded-lg p-2 sm:p-3 min-w-[60px] sm:min-w-[70px] border border-white/20 hover:bg-white/20 hover:-translate-y-1 hover:border-white/40 hover:shadow-[0_0_15px_rgba(255,255,255,0.2)] transition-all duration-300 cursor-default"
                    >
                      <span
                        class="text-xl sm:text-2xl font-bold text-white mb-1 transform group-hover:scale-110 group-hover:text-yellow-300 transition-all duration-300 inline-block"
                        style="font-family: 'Oswald', sans-serif"
                        >{{ ppdbCountdown.seconds }}</span
                      >
                      <span
                        class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wider group-hover:text-white transition-colors duration-300"
                        >Detik</span
                      >
                    </div>
                  </div>

                  <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a
                      v-if="ppdbInfo.registration_link"
                      :href="ppdbInfo.registration_link"
                      target="_blank"
                      class="px-6 py-3.5 bg-yellow-400 text-blue-950 font-bold rounded-xl shadow-lg hover:bg-yellow-300 hover:scale-105 transition-all text-sm md:text-base animate-float flex items-center justify-center"
                    >
                      Daftar Sekarang
                    </a>
                    <router-link
                      v-else
                      to="/pendaftaran"
                      class="px-6 py-3.5 bg-yellow-400 text-blue-950 font-bold rounded-xl shadow-lg hover:bg-yellow-300 hover:scale-105 transition-all text-sm md:text-base animate-float flex items-center justify-center"
                    >
                      Info Pendaftaran
                    </router-link>
                    <a
                      v-if="ppdbInfo.brochure_file"
                      :href="ppdbInfo.brochure_file"
                      target="_blank"
                      class="px-6 py-3.5 bg-gray-50/10 text-white font-semibold rounded-xl border border-white/50 hover:bg-gray-50/20 transition-all flex items-center justify-center text-sm md:text-base"
                    >
                      <PhDownloadSimple class="w-5 h-5 mr-2" /> Unduh Brosur
                    </a>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Global Dynamic Tooltip Map -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-opacity duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="tooltip.show && tooltip.data"
          class="fixed pointer-events-none z-[100] w-64 md:w-72 bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] border border-gray-100 dark:border-slate-700"
          :style="{
            top: tooltip.y + 'px',
            left: tooltip.x + 'px',
            transform: 'translate(-50%, -100%)',
          }"
        >
          <!-- Chat Bubble Tail -->
          <div
            class="absolute -bottom-2 -translate-x-1/2 w-4 h-4 bg-white dark:bg-slate-800 rotate-45 border-b border-r border-gray-100 dark:border-slate-700 rounded-sm transition-all"
            :style="{ left: `calc(50% + ${tooltip.tailOffset || 0}px)` }"
          ></div>

          <div class="flex flex-col relative z-10 max-h-64 overflow-y-auto pr-1">
            <h4
              class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-2 mb-2 flex items-center overflow-hidden"
            >
              <PhMapPin weight="fill" class="w-4 h-4 mr-1.5 text-blue-500 shrink-0" />
              <div class="text-marquee-container flex-1">
                <span class="text-marquee-content">{{ tooltip.data.name }}</span>
              </div>
            </h4>
            <div class="flex flex-col gap-2 mb-2">
              <div
                v-for="inst in tooltip.data.institutions"
                :key="inst.name"
                class="flex items-center gap-3 bg-gray-50 dark:bg-slate-700/50 p-2 rounded-lg border border-gray-100 dark:border-slate-600/50"
              >
                <div
                  class="w-8 h-8 rounded-full bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 p-1 shrink-0 flex items-center justify-center"
                >
                  <img
                    :src="inst.logo || 'https://img.icons8.com/color/96/school.png'"
                    class="w-full h-full object-contain"
                  />
                </div>
                <div class="flex flex-col flex-1 min-w-0">
                  <div class="text-marquee-container">
                    <span
                      class="font-bold text-xs text-gray-900 dark:text-white leading-tight text-marquee-content"
                    >
                      {{ inst.name }}
                    </span>
                  </div>
                  <span
                    class="text-[10px] text-gray-500 dark:text-gray-400 font-medium"
                    >{{
                      inst.type === "ptn"
                        ? "PTN"
                        : inst.type === "kedinasan"
                        ? "Kedinasan"
                        : "Instansi & BUMN"
                    }}</span
                  >
                </div>
                <div
                  class="bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-[10px] font-bold px-2 py-1 rounded shrink-0"
                >
                  {{ inst.alumni }}
                </div>
              </div>
            </div>
            <div
              class="bg-blue-50 dark:bg-blue-900/30 rounded-lg py-2 px-3 flex justify-between items-center border border-blue-100 dark:border-blue-800/50 mt-1"
            >
              <span class="text-[11px] font-medium text-blue-600 dark:text-blue-300"
                >Total Alumni di Kawasan</span
              >
              <span class="font-bold text-blue-700 dark:text-blue-400 text-xs">
                {{ tooltip.data.totalAlumni }} Orang
              </span>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import {
  ref,
  computed,
  onMounted,
  onBeforeUnmount,
  reactive,
  nextTick,
  onBeforeUpdate,
} from "vue";
import api from "@/api/index.js";
import Swiper from "swiper/bundle";
import "swiper/swiper-bundle.css";
import {
  PhQuotes,
  PhFlask,
  PhMapPin,
  PhGlobe,
  PhTranslate,
  PhMonitor,
  PhBookOpen,
  PhCalculator,
  PhBuildings,
  PhBriefcase,
  PhPalette,
  PhStethoscope,
  PhGraduationCap,
  PhCaretRight,
  PhCaretLeft,
  PhCalendarBlank,
  PhCalendar,
  PhEye,
  PhBell,
  PhArrowDown,
  PhPlay,
  PhImage,
  PhDownloadSimple,
  PhPaperclip,
  PhX,
  PhNewspaper,
} from "@phosphor-icons/vue";

const agendaListContainer = ref(null);
const agendaElements = ref({});
const highlightedAgendaId = ref(null);

onBeforeUpdate(() => {
  // Kosongkan refs sebelum setiap pembaruan untuk menghindari kebocoran memori
  agendaElements.value = {};
});

const displayedTitle = ref("");
const fullTitle = ref(localStorage.getItem("app_namaSekolah") || "");
const showSubtitle = ref(false);
const slogan = ref(localStorage.getItem("app_sloganSekolah") || "");
const isTypewriterStarted = ref(false);

const appearanceSettings = ref({
  benefitFasilitasImage: "",
  benefitGuruImage: "",
  benefitPrestasiImage: "",
  programCoverImage: "",
  ppdbBackgroundImage: "",
  galleryBackgroundImage: "",
});

const formatStatValue = (stat) => {
  if (!stat.isNumber) return stat.value + (stat.suffix || "");
  if (stat.value >= 1000) {
    return (stat.value / 1000).toFixed(1).replace(/\.0$/, "") + "k+";
  }
  return stat.value; // Tidak menampilkan "+" jika di bawah ribuan
};

const activeFaq = ref(null);
const toggleFaq = (index) => {
  activeFaq.value = activeFaq.value === index ? null : index;
};

const scrollToAgenda = (agendaToScrollTo) => {
  if (!agendaListContainer.value || !agendaToScrollTo) return;

  // Jika user klik label yang sama, matikan sorotan (toggle off)
  if (highlightedAgendaId.value === agendaToScrollTo.id) {
    highlightedAgendaId.value = null;
    return;
  }

  // Set sorotan ke agenda yang baru (termasuk memindahkannya jika klik label lain)
  highlightedAgendaId.value = agendaToScrollTo.id;

  // Gunakan nextTick agar update class selesai sebelum menghitung posisi gulir
  nextTick(() => {
    const targetElement = agendaElements.value[agendaToScrollTo.id];
    if (targetElement) {
      // Gulir otomatis menyesuaikan apakah target ada di bawah/atas
      targetElement.scrollIntoView({
        top: targetElement.offsetTop - 24, // 24px untuk padding wadah
        behavior: "smooth",
        block: "center",
      });
    }
  });
};

let scrollObserver = null;
const observeElements = () => {
  if (!scrollObserver) return;
  document.querySelectorAll(".fade-on-scroll").forEach((el) => {
    if (!el.dataset.observed) {
      scrollObserver.observe(el);
      el.dataset.observed = "true";
    }
  });
};

const programs = ref([]);
const isLoadingPrograms = ref(true);

const programGradients = [
  "from-blue-900 to-slate-900 group-hover:from-blue-800",
  "from-cyan-900 to-slate-900 group-hover:from-cyan-800",
  "from-sky-900 to-slate-900 group-hover:from-sky-800",
  "from-indigo-900 to-slate-900 group-hover:from-indigo-800",
  "from-purple-900 to-slate-900 group-hover:from-purple-800",
];

const getProgramGradient = (index) => {
  return programGradients[index % programGradients.length];
};

const iconList = [PhFlask, PhGlobe, PhTranslate, PhMonitor, PhBookOpen, PhGraduationCap];

const programIconMap = {
  PhFlask,
  PhGlobe,
  PhTranslate,
  PhMonitor,
  PhBookOpen,
  PhGraduationCap,
  monitor: PhMonitor,
  microscope: PhFlask,
  "book-open": PhBookOpen,
  languages: PhTranslate,
  calculator: PhCalculator,
  building: PhBuildings,
  briefcase: PhBriefcase,
  palette: PhPalette,
  stethoscope: PhStethoscope,
  globe: PhGlobe,
};

const getProgramIcon = (iconOrIndex) => {
  if (typeof iconOrIndex === "string" && programIconMap[iconOrIndex]) {
    return programIconMap[iconOrIndex];
  }
  if (typeof iconOrIndex === "number") {
    return iconList[iconOrIndex % iconList.length];
  }
  return PhGraduationCap;
};

const fetchPrograms = async () => {
  isLoadingPrograms.value = true;
  try {
    const response = await api.get("/api/public-programs");
    if (response.data && response.data.data && response.data.data.length > 0) {
      programs.value = response.data.data;
    }
  } catch (error) {
    console.error("Gagal mengambil data program jurusan:", error);
  } finally {
    isLoadingPrograms.value = false;
    nextTick(() => {
      initJurusanSwiper();
    });
  }
};

const recentNews = ref([]);
const isLoadingNews = ref(true);
const announcements = ref([]);

const announcementsWrapper = ref(null);
const announcementsContent = ref(null);
const shouldAutoScroll = ref(false);

const checkScroll = () => {
  if (
    announcementsWrapper.value &&
    announcementsContent.value &&
    announcementsContent.value.children.length > 0
  ) {
    const singleContentHeight = announcementsContent.value.children[0].scrollHeight;
    shouldAutoScroll.value =
      singleContentHeight > announcementsWrapper.value.clientHeight;
  }
};

const mainNews = computed(() =>
  recentNews.value.length > 0 ? recentNews.value[0] : null
);
const subNews = computed(() => recentNews.value.slice(1, 4));

const fetchNewsAndAnnouncements = async () => {
  isLoadingNews.value = true;
  try {
    const [newsRes, annRes] = await Promise.all([
      api.get("/api/public-news?per_page=4&exclude_category=pengumuman"),
      api.get("/api/public-news?per_page=10&category=pengumuman"),
    ]);
    if (newsRes.data && newsRes.data.data) {
      recentNews.value = newsRes.data.data;
    }
    if (annRes.data && annRes.data.data) {
      announcements.value = annRes.data.data;
    }
    nextTick(() => {
      setTimeout(checkScroll, 500);
      observeElements();
    });
  } catch (error) {
    console.error("Gagal mengambil data berita dan pengumuman:", error);
  } finally {
    isLoadingNews.value = false;
  }
};

const formatDate = (dateString) => {
  if (!dateString) return "";
  const date = new Date(dateString);
  return new Intl.DateTimeFormat("id-ID", {
    day: "numeric",
    month: "long",
    year: "numeric",
  }).format(date);
};

const formatDay = (dateString) => {
  if (!dateString) return "01";
  return new Date(dateString).getDate().toString().padStart(2, "0");
};

const formatMonth = (dateString) => {
  if (!dateString) return "Jan";
  const date = new Date(dateString);
  const months = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "Mei",
    "Jun",
    "Jul",
    "Agt",
    "Sep",
    "Okt",
    "Nov",
    "Des",
  ];
  return months[date.getMonth()];
};

const stripTags = (html) => {
  if (!html) return "";
  const tmp = document.createElement("DIV");
  tmp.innerHTML = html;
  return (tmp.textContent || tmp.innerText || "").substring(0, 150) + "...";
};

const getImageUrl = (path) => {
  if (!path) return "";
  if (path.startsWith("http") || path.startsWith("data:image")) return path;

  const baseUrl = import.meta.env.VITE_API_URL || "http://localhost:8000";
  const cleanPath = path.startsWith("/") ? path.slice(1) : path;
  if (cleanPath.startsWith("storage/")) {
    return `${baseUrl}/${cleanPath}`;
  }
  return `${baseUrl}/storage/${cleanPath}`;
};

const getNewsImage = (newsItem) => {
  if (!newsItem) return "";
  let imagePath = newsItem.image;
  if (newsItem.images && newsItem.images.length > 0) {
    imagePath = newsItem.images[0];
  }
  if (!imagePath) return "";
  return getImageUrl(imagePath);
};

// State & Logika Kalender Dinamis
const currentDisplayedDate = ref(new Date());

const calendarData = computed(() => {
  const date = currentDisplayedDate.value;
  const year = date.getFullYear();
  const month = date.getMonth();

  const monthName = new Intl.DateTimeFormat("id-ID", { month: "long" }).format(date);
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const firstDayOffset = new Date(year, month, 1).getDay(); // 0 (Minggu) - 6 (Sabtu)

  return {
    monthName,
    year,
    daysInMonth,
    firstDayOffset,
  };
});

const prevMonth = () => {
  const date = currentDisplayedDate.value;
  currentDisplayedDate.value = new Date(date.getFullYear(), date.getMonth() - 1, 1);
};

const nextMonth = () => {
  const date = currentDisplayedDate.value;
  currentDisplayedDate.value = new Date(date.getFullYear(), date.getMonth() + 1, 1);
};

// Data Grid Kalender
const colSpanClasses = {
  1: "col-span-1",
  2: "col-span-2",
  3: "col-span-3",
  4: "col-span-4",
  5: "col-span-5",
  6: "col-span-6",
  7: "col-span-7",
};

const calendarGrid = computed(() => {
  const year = currentDisplayedDate.value.getFullYear();
  const month = currentDisplayedDate.value.getMonth();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const firstDayOffset = new Date(year, month, 1).getDay();

  const gridItems = [];
  for (let i = 0; i < firstDayOffset; i++) gridItems.push({ type: "empty" });

  let currentDay = 1;
  while (currentDay <= daysInMonth) {
    const currentDayDate = new Date(year, month, currentDay);
    currentDayDate.setHours(0, 0, 0, 0);

    const eventOnDay = agendas.value.find((agenda) => {
      if (!agenda.startDate) return false;
      const start = new Date(agenda.startDate);
      start.setHours(0, 0, 0, 0);
      const end = agenda.endDate ? new Date(agenda.endDate) : new Date(start);
      end.setHours(0, 0, 0, 0);
      return currentDayDate >= start && currentDayDate <= end;
    });

    if (eventOnDay) {
      const start = new Date(eventOnDay.startDate);
      start.setHours(0, 0, 0, 0);
      const end = eventOnDay.endDate ? new Date(eventOnDay.endDate) : new Date(start);
      end.setHours(0, 0, 0, 0);

      const currentDayOfWeek = currentDayDate.getDay();
      const daysLeftInWeek = 7 - currentDayOfWeek;

      let span = 1;
      for (let i = 1; i < daysLeftInWeek; i++) {
        const nextDayDate = new Date(year, month, currentDay + i);
        nextDayDate.setHours(0, 0, 0, 0);
        if (currentDay + i > daysInMonth) break;
        if (nextDayDate >= start && nextDayDate <= end) span++;
        else break;
      }

      const daysArr = [];
      for (let i = 0; i < span; i++) daysArr.push(currentDay + i);
      gridItems.push({ type: "event", span: span, days: daysArr, event: eventOnDay });
      currentDay += span;
    } else {
      gridItems.push({ type: "day", day: currentDay });
      currentDay++;
    }
  }
  return gridItems;
});

// State Tooltip Dinamis Map
const tooltip = ref({
  show: false,
  x: 0,
  y: 0,
  tailOffset: 0,
  data: null,
});
const showTooltip = (e, loc) => {
  tooltip.value.show = true;
  tooltip.value.data = loc;
  updateTooltipPos(e);
};
const updateTooltipPos = (e) => {
  if (!tooltip.value.show) return;
  const clientX = e.touches && e.touches.length > 0 ? e.touches[0].clientX : e.clientX;
  const clientY = e.touches && e.touches.length > 0 ? e.touches[0].clientY : e.clientY;

  let tooltipX = clientX;
  const tooltipHalfWidth = window.innerWidth < 768 ? 128 : 144; // Estimasi w-64 = 256px, w-72 = 288px
  const margin = 16; // Jarak aman dari tepi layar
  let tailOffset = 0;

  // Penyesuaian batas horizontal agar tooltip tidak terpotong (keluar layar)
  if (tooltipX < tooltipHalfWidth + margin) {
    tailOffset = tooltipX - (tooltipHalfWidth + margin);
    tooltipX = tooltipHalfWidth + margin;
  } else if (tooltipX > window.innerWidth - tooltipHalfWidth - margin) {
    tailOffset = tooltipX - (window.innerWidth - tooltipHalfWidth - margin);
    tooltipX = window.innerWidth - tooltipHalfWidth - margin;
  }

  // Batasi pergeseran ekor (tail) agar tidak terlepas dari area card (menjaga sudut membulat)
  const maxTailOffset = tooltipHalfWidth - 24;
  if (tailOffset > maxTailOffset) tailOffset = maxTailOffset;
  if (tailOffset < -maxTailOffset) tailOffset = -maxTailOffset;

  tooltip.value.x = tooltipX;
  tooltip.value.y = clientY - 15; // Beri sedikit jarak vertikal ke atas agar tidak menutupi jari/kursor
  tooltip.value.tailOffset = tailOffset;
};
const hideTooltip = () => {
  tooltip.value.show = false;
  tooltip.value.tailOffset = 0;
};

let resizeObserver = null;

// Modal Lampiran
const isAttachmentModalOpen = ref(false);
const selectedAgenda = ref(null);

const openAttachmentModal = (agenda) => {
  selectedAgenda.value = agenda;
  isAttachmentModalOpen.value = true;
  document.body.style.overflow = "hidden";
};

const closeAttachmentModal = () => {
  isAttachmentModalOpen.value = false;
  document.body.style.overflow = "";
  setTimeout(() => {
    selectedAgenda.value = null;
  }, 300);
};

// State Countdown PPDB
const ppdbCountdown = ref({
  days: "00",
  hours: "00",
  minutes: "00",
  seconds: "00",
});

let countdownInterval;

const updateCountdown = () => {
  if (!ppdbInfo.value.opening_date) {
    // Jika tidak ada tanggal dari API, set ke nol sementara menunggu data
    ppdbCountdown.value = { days: "00", hours: "00", minutes: "00", seconds: "00" };
    return;
  }

  const targetDate = new Date(ppdbInfo.value.opening_date).getTime();
  const now = new Date().getTime();
  const distance = targetDate - now;

  if (distance < 0) {
    clearInterval(countdownInterval);
    ppdbCountdown.value = { days: "00", hours: "00", minutes: "00", seconds: "00" };
    return;
  }

  const days = Math.floor(distance / (1000 * 60 * 60 * 24));
  const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((distance % (1000 * 60)) / 1000);

  ppdbCountdown.value = {
    days: String(days).padStart(2, "0"),
    hours: String(hours).padStart(2, "0"),
    minutes: String(minutes).padStart(2, "0"),
    seconds: String(seconds).padStart(2, "0"),
  };
};

// --- Data Animasi Statistik Header ---
const statsArray = ref([
  {
    key: "akreditasi",
    value: "-",
    target: "-",
    label: "Akreditasi",
    prefix: "",
    suffix: "",
    isNumber: false,
  },
  {
    key: "siswa",
    value: 0,
    target: 0,
    label: "Siswa Aktif",
    prefix: "",
    suffix: "+",
    isNumber: true,
  },
  {
    key: "guru",
    value: 0,
    target: 0,
    label: "Tenaga Pendidik",
    prefix: "",
    suffix: "",
    isNumber: true,
  },
  {
    key: "ekskul",
    value: 0,
    target: 0,
    label: "Ekstrakurikuler",
    prefix: "",
    suffix: "+",
    isNumber: true,
  },
  {
    key: "prestasi",
    value: 0,
    target: 0,
    label: "Prestasi",
    prefix: "",
    suffix: "+",
    isNumber: true,
  },
]);

const fetchSchoolStats = async () => {
  try {
    // Ambil data dari cache terlebih dahulu agar langsung tampil (instan)
    const cachedData = localStorage.getItem("app_schoolStats");
    if (cachedData) {
      const data = JSON.parse(cachedData);
      statsArray.value.forEach((stat) => {
        if (data[stat.key] !== undefined) {
          stat.target = stat.isNumber ? Number(data[stat.key]) || 0 : data[stat.key];
          if (!stat.isNumber) {
            stat.value = stat.target;
          }
        }
      });
    }

    // Tetap panggil API endpoint untuk mengambil data statistik terbaru
    const response = await api.get("/api/public-stats");
    if (response.data && response.data.data) {
      const data = response.data.data;
      localStorage.setItem("app_schoolStats", JSON.stringify(data));

      let needsAnimation = false;
      statsArray.value.forEach((stat) => {
        if (data[stat.key] !== undefined) {
          const newTarget = stat.isNumber ? Number(data[stat.key]) || 0 : data[stat.key];
          if (stat.target !== newTarget) {
            stat.target = newTarget;
            needsAnimation = true;
          }
          if (!stat.isNumber) {
            stat.value = stat.target; // Langsung set nilai string untuk akreditasi
          }
        }
      });
      // Jika efek ketik & animasi kotak sudah selesai/muncul, dan ada data baru, animasikan angka agar terupdate
      if (showSubtitle.value && needsAnimation) {
        animateStats();
      }
    }
  } catch (error) {
    console.error("Gagal mengambil data statistik sekolah:", error);
  }
};

let statsAnimationId = null;

const animateStats = () => {
  if (statsAnimationId) cancelAnimationFrame(statsAnimationId);

  const duration = 3000; // Durasi diperpanjang menjadi 3 detik agar efek naiknya lebih dramatis
  let startTimestamp = null;

  const startValues = statsArray.value.map((stat) => (stat.isNumber ? stat.value : 0));

  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    const easeProgress = 1 - Math.pow(1 - progress, 4); // Efek ease-out

    statsArray.value.forEach((stat, index) => {
      if (stat.isNumber) {
        stat.value = Math.floor(
          startValues[index] + easeProgress * (stat.target - startValues[index])
        );
      }
    });

    if (progress < 1) {
      statsAnimationId = window.requestAnimationFrame(step);
    } else {
      statsArray.value.forEach((stat) => {
        if (stat.isNumber) stat.value = stat.target;
      });
    }
  };
  statsAnimationId = window.requestAnimationFrame(step);
};

// --- Data Animasi Statistik Alumni ---
const isAlumniStatsVisible = ref(false);
const alumniStats = ref({
  alumni: { value: 0, target: 0 },
  ptn: { value: 0, target: 0 },
  instansi: { value: 0, target: 0 },
});

let alumniAnimationId = null;

const animateAlumniStats = () => {
  if (alumniAnimationId) cancelAnimationFrame(alumniAnimationId);

  const duration = 2500;
  let startTimestamp = null;

  const startAlumni = alumniStats.value.alumni.value;
  const startPtn = alumniStats.value.ptn.value;
  const startInstansi = alumniStats.value.instansi.value;

  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    const easeProgress = 1 - Math.pow(1 - progress, 4); // Efek ease-out

    alumniStats.value.alumni.value =
      startAlumni + easeProgress * (alumniStats.value.alumni.target - startAlumni);
    alumniStats.value.ptn.value = Math.floor(
      startPtn + easeProgress * (alumniStats.value.ptn.target - startPtn)
    );
    alumniStats.value.instansi.value = Math.floor(
      startInstansi + easeProgress * (alumniStats.value.instansi.target - startInstansi)
    );

    if (progress < 1) {
      alumniAnimationId = window.requestAnimationFrame(step);
    } else {
      alumniStats.value.alumni.value = alumniStats.value.alumni.target;
      alumniStats.value.ptn.value = alumniStats.value.ptn.target;
      alumniStats.value.instansi.value = alumniStats.value.instansi.target;
    }
  };
  alumniAnimationId = window.requestAnimationFrame(step);
};

const themeClasses = {
  yellow: {
    card:
      "bg-white border-gray-100 hover:border-slate-300 hover:shadow-slate-100/50 dark:bg-slate-800 dark:border-slate-700 dark:hover:border-slate-500/50",
    dateBox: "border-slate-600 border-b-4",
    monthBg: "bg-slate-600 group-hover:bg-slate-700",
    monthText: "text-white",
    dateBg: "bg-slate-50",
    dateText: "text-slate-700 group-hover:text-slate-800",
    title:
      "text-gray-900 group-hover:text-slate-700 dark:text-slate-100 dark:group-hover:text-slate-400",
    infoIcon: "text-slate-500",
    infoText: "text-gray-700 dark:text-slate-300",
    fileBtn:
      "bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100 dark:bg-slate-900/30 dark:text-slate-400 dark:border-slate-700/50 dark:hover:bg-slate-900/50",
    eventBg:
      "bg-slate-100 hover:bg-slate-200 dark:bg-slate-900/30 dark:hover:bg-slate-900/50",
    eventHeaderBg: "bg-slate-600 dark:bg-slate-700",
    eventHeaderText: "text-white drop-shadow-md",
    eventDayText: "text-slate-800 dark:text-slate-400",
  },
  red: {
    card:
      "bg-white border-gray-100 hover:border-gray-300 hover:shadow-gray-100/50 dark:bg-slate-800 dark:border-slate-700 dark:hover:border-gray-500/50",
    dateBox: "border-gray-600 border-b-4",
    monthBg: "bg-gray-600 group-hover:bg-gray-700",
    monthText: "text-white",
    dateBg: "bg-gray-50",
    dateText: "text-gray-700 group-hover:text-gray-800",
    title:
      "text-gray-900 group-hover:text-gray-700 dark:text-slate-100 dark:group-hover:text-gray-400",
    infoIcon: "text-gray-500",
    infoText: "text-gray-700 dark:text-slate-300",
    fileBtn:
      "bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100 dark:bg-gray-900/30 dark:text-gray-400 dark:border-gray-700/50 dark:hover:bg-gray-900/50",
    eventBg:
      "bg-gray-100 hover:bg-gray-200 dark:bg-gray-900/30 dark:hover:bg-gray-900/50",
    eventHeaderBg: "bg-gray-600 dark:bg-gray-700",
    eventHeaderText: "text-white drop-shadow-md",
    eventDayText: "text-gray-800 dark:text-gray-400",
  },
  green: {
    card:
      "bg-white border-gray-100 hover:border-zinc-300 hover:shadow-zinc-100/50 dark:bg-slate-800 dark:border-slate-700 dark:hover:border-zinc-500/50",
    dateBox: "border-zinc-600 border-b-4",
    monthBg: "bg-zinc-600 group-hover:bg-zinc-700",
    monthText: "text-white",
    dateBg: "bg-zinc-50",
    dateText: "text-zinc-700 group-hover:text-zinc-800",
    title:
      "text-gray-900 group-hover:text-zinc-700 dark:text-slate-100 dark:group-hover:text-zinc-400",
    infoIcon: "text-zinc-500",
    infoText: "text-gray-700 dark:text-slate-300",
    fileBtn:
      "bg-zinc-50 text-zinc-700 border border-zinc-200 hover:bg-zinc-100 dark:bg-zinc-900/30 dark:text-zinc-400 dark:border-zinc-700/50 dark:hover:bg-zinc-900/50",
    eventBg:
      "bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-900/30 dark:hover:bg-zinc-900/50",
    eventHeaderBg: "bg-zinc-600 dark:bg-zinc-700",
    eventHeaderText: "text-white drop-shadow-md",
    eventDayText: "text-zinc-800 dark:text-zinc-400",
  },
  blue: {
    card:
      "bg-white border-gray-100 hover:border-neutral-300 hover:shadow-neutral-100/50 dark:bg-slate-800 dark:border-slate-700 dark:hover:border-neutral-500/50",
    dateBox: "border-neutral-600 border-b-4",
    monthBg: "bg-neutral-600 group-hover:bg-neutral-700",
    monthText: "text-white",
    dateBg: "bg-neutral-50",
    dateText: "text-neutral-700 group-hover:text-neutral-800",
    title:
      "text-gray-900 group-hover:text-neutral-700 dark:text-slate-100 dark:group-hover:text-neutral-400",
    infoIcon: "text-neutral-500",
    infoText: "text-gray-700 dark:text-slate-300",
    fileBtn:
      "bg-neutral-50 text-neutral-700 border border-neutral-200 hover:bg-neutral-100 dark:bg-neutral-900/30 dark:text-neutral-400 dark:border-neutral-700/50 dark:hover:bg-neutral-900/50",
    eventBg:
      "bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-900/30 dark:hover:bg-neutral-900/50",
    eventHeaderBg: "bg-neutral-600 dark:bg-neutral-700",
    eventHeaderText: "text-white drop-shadow-md",
    eventDayText: "text-neutral-800 dark:text-neutral-400",
  },
};

const agendas = ref([]);
const isLoadingAgendas = ref(true);

const galleries = ref([]);
const galleriesByCategory = ref([]);
const fourthGalleryImage = ref(null);

const fetchGalleries = async () => {
  try {
    const response = await api.get("/api/public-galleries");
    if (response.data && response.data.data) {
      galleries.value = response.data.data.slice(0, 3);
      const allGalleries = response.data.data;

      const grouped = {};
      allGalleries.forEach((gallery) => {
        if (!grouped[gallery.category]) {
          grouped[gallery.category] = gallery; // Data sudah diorder `desc` dari API, jadi kita akan dapat foto terbaru dari setiap kategori pertama kali
        }
      });

      const groupedArr = Object.values(grouped);
      galleriesByCategory.value = groupedArr.slice(0, 3);

      if (groupedArr.length > 3) {
        fourthGalleryImage.value = groupedArr[3].image;
      } else if (allGalleries.length > 3) {
        fourthGalleryImage.value = allGalleries[3].image;
      } else if (allGalleries.length > 0) {
        fourthGalleryImage.value = allGalleries[0].image;
      }
    }
  } catch (error) {
    console.error("Gagal mengambil data galeri:", error);
  } finally {
    nextTick(() => {
      observeElements();
    });
  }
};

const schoolVideoUrl = ref("");
const schoolVideoTitle = ref("");
const schoolVideoDesc = ref("");
const isVideoPlaying = ref(false);
const isLoadingSchoolVideo = ref(true);

const videoEmbedUrl = computed(() => {
  if (!schoolVideoUrl.value) return "";
  const url = schoolVideoUrl.value;
  const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
  const match = url.match(regExp);
  if (match && match[2].length === 11) {
    return `https://www.youtube.com/embed/${match[2]}?autoplay=1&rel=0`;
  }
  return url;
});

const videoThumbnail = computed(() => {
  if (!schoolVideoUrl.value)
    return "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop";
  const url = schoolVideoUrl.value;
  const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
  const match = url.match(regExp);
  if (match && match[2].length === 11) {
    return `https://img.youtube.com/vi/${match[2]}/maxresdefault.jpg`;
  }
  return "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop";
});

const fetchSchoolVideo = async () => {
  isLoadingSchoolVideo.value = true;
  try {
    const response = await api.get("/api/public-school-video");
    if (response.data && response.data.data) {
      schoolVideoUrl.value = response.data.data.url || "";
      if (response.data.data.title) schoolVideoTitle.value = response.data.data.title;
      if (response.data.data.description)
        schoolVideoDesc.value = response.data.data.description;
    }
  } catch (error) {
    console.error("Gagal mengambil data video profil:", error);
  } finally {
    isLoadingSchoolVideo.value = false;
    nextTick(() => {
      observeElements();
    });
  }
};

const fetchAgendas = async () => {
  isLoadingAgendas.value = true;
  try {
    const response = await api.get("/api/public-agendas");
    if (response.data && response.data.data && response.data.data.length > 0) {
      agendas.value = response.data.data.map((agenda) => {
        let dateText = "01";
        let monthText = "Jan";
        let startDate = null;
        let endDate = null;

        const rawStartDate =
          agenda.start_date ||
          agenda.startDate ||
          agenda.tanggal_mulai ||
          agenda.date ||
          agenda.tanggal;
        const rawEndDate = agenda.end_date || agenda.endDate || agenda.tanggal_selesai;

        if (rawStartDate) {
          const d = new Date(rawStartDate);

          // Cek apakah tanggal valid ketika diparse ke object Date
          if (!isNaN(d.getTime())) {
            startDate = d;
            dateText = d.getDate().toString().padStart(2, "0");
            const months = [
              "Jan",
              "Feb",
              "Mar",
              "Apr",
              "Mei",
              "Jun",
              "Jul",
              "Agt",
              "Sep",
              "Okt",
              "Nov",
              "Des",
            ];
            monthText = months[d.getMonth()];

            if (rawEndDate && rawEndDate !== rawStartDate) {
              const endD = new Date(rawEndDate);
              if (!isNaN(endD.getTime())) {
                endDate = endD;
                dateText = `${d
                  .getDate()
                  .toString()
                  .padStart(2, "0")}-${endD.getDate().toString().padStart(2, "0")}`;
                if (d.getMonth() !== endD.getMonth()) {
                  monthText = `${months[d.getMonth()]}-${months[endD.getMonth()]}`;
                }
              }
            }
          } else {
            // Fallback jika API membalas dengan format literal seperti "18 - 24 Juli"
            dateText = rawStartDate;
            monthText = "Agenda";
          }
        }

        // Membaca warna langsung dari database sama seperti halaman Admin
        let color = agenda.color || "blue";

        // Fallback untuk data lama yang mungkin tidak memiliki properti color
        if (!agenda.color) {
          const type = (
            agenda.category ||
            agenda.type ||
            agenda.kategori ||
            ""
          ).toLowerCase();
          if (type.includes("akademik")) color = "yellow";
          else if (
            type.includes("guru") ||
            type.includes("staf") ||
            type.includes("pendidik")
          )
            color = "red";
          else if (type.includes("kegiatan") || type.includes("lomba")) color = "green";
        }

        return {
          id: agenda.id,
          date: dateText,
          month: monthText,
          title: agenda.title || agenda.nama || "Agenda Tanpa Judul",
          time: agenda.time || agenda.waktu || "08:00 - Selesai",
          loc: agenda.location || agenda.lokasi || "Lingkungan Sekolah",
          color: color,
          file: agenda.file || agenda.lampiran || null,
          startDate: startDate,
          endDate: endDate || startDate,
        };
      });
    }
  } catch (error) {
    console.error("Gagal mengambil data agenda:", error);
  } finally {
    isLoadingAgendas.value = false;
  }
};

const ppdbInfo = ref({
  academic_year: "2026/2027",
  title: "Siap Menjadi Bagian dari Generasi Berprestasi?",
  description:
    "Pendaftaran Peserta Didik Baru (PPDB) di sekolah kami akan segera dibuka. Siapkan berkas dan pantau informasi selengkapnya!",
  opening_date: null,
  brochure_file: null,
  registration_link: null,
});

const faqs = ref([]);
const isLoadingPpdb = ref(true);

const fetchPpdbInfo = async () => {
  isLoadingPpdb.value = true;
  try {
    const response = await api.get("/api/ppdb-info");
    if (response.data && response.data.data) {
      const data = response.data.data;
      if (data.academic_year) ppdbInfo.value.academic_year = data.academic_year;
      if (data.title) ppdbInfo.value.title = data.title;
      if (data.description) ppdbInfo.value.description = data.description;
      if (data.opening_date) ppdbInfo.value.opening_date = data.opening_date;
      if (data.brochure_file) ppdbInfo.value.brochure_file = data.brochure_file;
      else if (data.brosur_url) ppdbInfo.value.brochure_file = data.brosur_url;
      if (data.registration_link)
        ppdbInfo.value.registration_link = data.registration_link;

      if (data.faqs && Array.isArray(data.faqs)) {
        faqs.value = data.faqs;
      }
    }
  } catch (error) {
    console.error("Gagal mengambil data PPDB:", error);
  } finally {
    isLoadingPpdb.value = false;
    updateCountdown();
    if (!ppdbInfo.value.opening_date && countdownInterval) {
      clearInterval(countdownInterval);
    }
  }
};

// State Koordinat Persebaran Alumni di Peta Indonesia (Pendidikan & Karir)
const alumniLocations = ref([]);
const isLoadingAlumniLocations = ref(true);

// Ambil cache koordinat dari LocalStorage (jika ada) untuk posisi skeleton yang akurat
const cachedMapLocations = localStorage.getItem("alumniMapCache");
const defaultSkeletons = [
  { top: "73%", left: "27%" }, // Jabodetabek
  { top: "77%", left: "31%" }, // Jawa Barat
  { top: "80%", left: "38%" }, // Jawa Tengah & DIY
];
const skeletonLocations = ref(
  cachedMapLocations ? JSON.parse(cachedMapLocations) : defaultSkeletons
);

const fetchAlumniLocations = async () => {
  isLoadingAlumniLocations.value = true;
  try {
    // Ambil cache statistik alumni agar tidak delay
    const cachedStats = localStorage.getItem("app_alumniStats");
    if (cachedStats) {
      const data = JSON.parse(cachedStats);
      alumniStats.value.alumni.target = data.alumni || 0;
      alumniStats.value.ptn.target = data.ptn || 0;
      alumniStats.value.instansi.target = data.instansi || 0;
    }

    const response = await api.get("/api/public-map-locations");
    if (response.data && response.data.data) {
      let totalAlumniCount = 0;
      let totalPTNCount = 0;
      let totalInstansiCount = 0;

      alumniLocations.value = response.data.data.map((loc) => {
        let locPTN = 0;
        let locInstansi = 0;
        let locAlumni = parseInt(loc.total_alumni || loc.totalAlumni) || 0;

        if (loc.institutions && Array.isArray(loc.institutions)) {
          let calcAlumni = 0;
          loc.institutions.forEach((inst) => {
            const count = parseInt(inst.alumni) || 0;
            calcAlumni += count;
            if (inst.type === "ptn") {
              locPTN += count;
            } else if (inst.type === "instansi" || inst.type === "kedinasan") {
              locInstansi += count;
            }
          });
          if (locAlumni < calcAlumni) {
            locAlumni = calcAlumni;
          }
        }

        totalAlumniCount += locAlumni;
        totalPTNCount += locPTN;
        totalInstansiCount += locInstansi;

        return {
          id: loc.id,
          name: loc.name,
          type: loc.type || "mixed",
          totalAlumni: locAlumni,
          top: loc.top,
          left: loc.left,
          institutions: loc.institutions || [],
        };
      });

      // Simpan data koordinat ke cache agar saat loading selanjutnya, skeleton 100% akurat di titik yg sama
      const cacheCoords = alumniLocations.value.map((loc) => ({
        top: loc.top,
        left: loc.left,
      }));
      localStorage.setItem("alumniMapCache", JSON.stringify(cacheCoords));

      // Simpan data stats terbaru ke cache
      localStorage.setItem(
        "app_alumniStats",
        JSON.stringify({
          alumni: totalAlumniCount,
          ptn: totalPTNCount,
          instansi: totalInstansiCount,
        })
      );

      // Update target animasi dengan data real dari API
      if (totalAlumniCount > 0) alumniStats.value.alumni.target = totalAlumniCount;
      if (totalPTNCount > 0) alumniStats.value.ptn.target = totalPTNCount;
      if (totalInstansiCount > 0) alumniStats.value.instansi.target = totalInstansiCount;

      // Jika container alumni sudah di view, perbarui / jalankan lagi animasinya
      if (isAlumniStatsVisible.value) {
        animateAlumniStats();
      }
    }
  } catch (error) {
    console.error("Gagal mengambil data persebaran alumni:", error);
  } finally {
    isLoadingAlumniLocations.value = false;
  }
};

const fetchSettings = async () => {
  try {
    const response = await api.get("/api/settings");
    if (response.data && response.data.data) {
      const isFirstLoad = !fullTitle.value;
      fullTitle.value = response.data.data.namaSekolah || "";
      slogan.value = response.data.data.deskripsi || "";

      localStorage.setItem("app_namaSekolah", fullTitle.value);
      localStorage.setItem("app_sloganSekolah", slogan.value);

      appearanceSettings.value.benefitFasilitasImage =
        response.data.data.benefitFasilitasImage || "";
      appearanceSettings.value.benefitGuruImage =
        response.data.data.benefitGuruImage || "";
      appearanceSettings.value.benefitPrestasiImage =
        response.data.data.benefitPrestasiImage || "";
      appearanceSettings.value.programCoverImage =
        response.data.data.programCoverImage || "";
      appearanceSettings.value.ppdbBackgroundImage =
        response.data.data.ppdbBackgroundImage || "";
      appearanceSettings.value.galleryBackgroundImage =
        response.data.data.galleryBackgroundImage || "";

      // Jika pertama kali load (cache kosong), jalankan animasi setelah data turun
      if (isFirstLoad && !isTypewriterStarted.value) {
        startTypewriter();
      }
    }
  } catch (error) {
    console.error("Gagal mengambil pengaturan:", error);
    if (!isTypewriterStarted.value) startTypewriter();
  } finally {
  }
};

const handleStorageChange = (e) => {
  if (e.key === "settings_updated_at") {
    fetchSettings();
  }
};

onMounted(() => {
  // Fetch data dari API Backend
  fetchPrograms();
  fetchAgendas();
  fetchGalleries();
  fetchSchoolVideo();
  fetchNewsAndAnnouncements();
  fetchAlumniLocations();
  // Prioritaskan fetch data untuk Header dan Navbar terlebih dahulu
  fetchSchoolStats();
  fetchPpdbInfo();
  fetchSettings();

  // Tunda fetch data seksi lain agar tidak memblokir koneksi di awal load
  setTimeout(() => {
    fetchPrograms();
    fetchAgendas();
    fetchGalleries();
    fetchSchoolVideo();
    fetchNewsAndAnnouncements();
    fetchAlumniLocations();
    fetchPpdbInfo();
  }, 200);

  // Dengarkan sinyal pembaruan pengaturan
  window.addEventListener("settings-updated", fetchSettings);
  window.addEventListener("storage", handleStorageChange);

  // Jika sudah ada cache, langsung mulai efek ketik tanpa delay menunggu backend
  if (fullTitle.value && !isTypewriterStarted.value) {
    startTypewriter();
  }

  // Initialize countdown
  updateCountdown();
  countdownInterval = setInterval(updateCountdown, 1000);

  resizeObserver = new ResizeObserver(() => {
    checkScroll();
  });
  if (announcementsWrapper.value) {
    resizeObserver.observe(announcementsWrapper.value);
  }

  // Intersection Observer untuk animasi fade-up pada saat scroll
  scrollObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("opacity-100", "translate-y-0");
          entry.target.classList.remove("opacity-0", "translate-y-10");

          // Jalankan animasi angka spesifik untuk area alumni
          if (entry.target.classList.contains("alumni-stats-container")) {
            isAlumniStatsVisible.value = true;
            animateAlumniStats();
          }
          scrollObserver.unobserve(entry.target); // Animasi hanya berjalan 1x
        }
      });
    },
    { threshold: 0.1 }
  );

  observeElements();

  // Initialize main Swiper
  new Swiper(".swiper-container", {
    loop: true,
    speed: 1000,
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
    speed: 800,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    slidesPerView: 3,
    spaceBetween: 5,
    breakpoints: {
      400: { slidesPerView: 3, spaceBetween: 10 },
      640: { slidesPerView: 4, spaceBetween: 20 },
    },
  });

  // Initialize Swipers
  initJurusanSwiper();

  // Initialize Alasan Swiper
  new Swiper(".alasan-swiper", {
    loop: true,
    speed: 800,
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

// Dipisahkan ke dalam fungsi agar bisa dipanggil setelah fetching data pengaturan
const startTypewriter = () => {
  if (isTypewriterStarted.value) return;
  isTypewriterStarted.value = true;

  let i = 0;
  displayedTitle.value = "";
  const typeWriter = setInterval(() => {
    if (i < fullTitle.value.length) {
      displayedTitle.value += fullTitle.value.charAt(i);
      i++;
    } else {
      clearInterval(typeWriter);
      showSubtitle.value = true;

      // Jalankan animasi angka bersamaan saat kotak statistik melayang naik
      setTimeout(() => {
        animateStats();
      }, 500);
    }
  }, 120); // Kecepatan mengetik 120ms per huruf
};

let jurusanSwiperInstance = null;
const initJurusanSwiper = () => {
  if (jurusanSwiperInstance) {
    jurusanSwiperInstance.destroy(true, true);
  }

  const totalPrograms = programs.value.length;

  // Hitung slide dinamis (maksimal 3 untuk desktop, 2 untuk tablet)
  const desktopSlides = totalPrograms > 0 ? Math.min(3, totalPrograms) : 3;
  const tabletSlides = totalPrograms > 0 ? Math.min(2, totalPrograms) : 2;

  // Matikan loop jika card sedikit agar card bisa meregang (tidak diduplikasi)
  const enableLoop = totalPrograms > 3;

  jurusanSwiperInstance = new Swiper(".jurusan-swiper", {
    loop: true,
    loop: enableLoop,
    speed: 800,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    slidesPerView: 1,
    spaceBetween: 0,
    pagination: { el: ".jurusan-pagination", clickable: true },
    breakpoints: {
      768: {
        slidesPerView: 2,
        slidesPerView: tabletSlides,
        spaceBetween: 0,
      },
      1024: {
        slidesPerView: 3,
        slidesPerView: desktopSlides,
        spaceBetween: 0,
      },
    },
  });
};

onBeforeUnmount(() => {
  if (countdownInterval) clearInterval(countdownInterval);
  window.removeEventListener("settings-updated", fetchSettings);
  window.removeEventListener("storage", handleStorageChange);
  if (resizeObserver) {
    resizeObserver.disconnect();
  }
  if (scrollObserver) {
    scrollObserver.disconnect();
  }
});
</script>

<style scoped>
.highlight-agenda {
  box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
  transform: translateY(-4px) scale(1.01);
  z-index: 10;
}

@keyframes float {
  0%,
  100% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(-8px);
  }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

.animate-float:hover {
  animation-play-state: paused;
}

/* Efek Marquee Horizontal Otomatis (Hanya berjalan jika teks overflow) */
.text-marquee-container {
  container-type: inline-size;
  overflow: hidden;
  white-space: nowrap;
  width: 100%;
}

.text-marquee-content {
  display: inline-block;
  min-width: 100%;
  animation: slide-text 5s linear infinite alternate;
}

@keyframes slide-text {
  0%,
  20% {
    transform: translateX(0);
  }

  /* Jika text (100%) > kontainer (100cqw), hasil kalkulasi akan negatif dan memicu scroll. 
     Jika lebih pendek, hasil min(0, positif) tetap 0 (tidak bergerak). */
  80%,
  100% {
    transform: translateX(min(0px, calc(100cqw - 100%)));
  }
}
</style>
