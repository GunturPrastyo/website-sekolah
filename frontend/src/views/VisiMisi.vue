<template>
  <div>
    <PageHeader
      badge="Profil Sekolah"
      title="Visi, Misi & Tujuan"
      description="Mengenal lebih dekat arah, tujuan, dan landasan utama SMAN 1 Nogosari dalam mencetak generasi unggul penerus bangsa."
    />

    <!-- Sambutan Kepala Sekolah Section -->
    <section class="relative py-12 md:py-24 px-6 md:px-0 bg-white dark:bg-slate-900">
      <div class="container mx-auto px-0 md:px-4 max-w-6xl">
        <div class="flex flex-col md:flex-row items-center gap-8 md:gap-16">
          <!-- Gambar Kepala Sekolah -->
          <div
            class="w-full md:w-1/3 flex justify-center order-2 md:order-1 mt-8 md:mt-0 pl-4 pb-4 md:pl-0 md:pb-0"
          >
            <div class="relative w-full max-w-sm aspect-[4/5]">
              <div
                class="absolute -left-4 -bottom-4 md:-left-6 md:-bottom-6 w-full h-full bg-blue-600 dark:bg-blue-700 rounded-2xl z-0 shadow-lg"
              ></div>
              <div
                class="absolute inset-0 rounded-2xl overflow-hidden shadow-2xl border-4 border-white dark:border-slate-800 z-10 bg-white dark:bg-slate-800"
              >
                <img
                  src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop"
                  class="w-full h-full object-cover"
                  alt="Kepala Sekolah"
                />
                <div
                  class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 to-transparent p-6"
                >
                  <h4 class="text-white font-bold text-xl">Dr. H. Budi Santoso, M.Pd</h4>
                  <p class="text-blue-300 text-sm font-medium mt-1">
                    Kepala SMAN 1 Nogosari
                  </p>
                </div>
              </div>
              <div
                class="absolute -bottom-8 -right-8 bg-blue-500 w-32 h-32 rounded-full -z-10 blur-3xl opacity-30 dark:opacity-20"
              ></div>
              <div
                class="absolute -top-8 -left-8 bg-cyan-400 w-32 h-32 rounded-full -z-10 blur-3xl opacity-30 dark:opacity-20"
              ></div>
            </div>
          </div>

          <!-- Teks Sambutan -->
          <div class="w-full md:w-2/3 order-1 md:order-2 mt-4 md:mt-0">
            <div class="flex items-center gap-3 mb-6">
              <h2
                class="text-2xl sm:text-3xl md:text-4xl font-bold text-blue-950 dark:text-white font-serif tracking-wide"
              >
                Sambutan Kepala Sekolah
              </h2>
            </div>

            <div
              class="text-gray-700 dark:text-gray-300 space-y-4 md:space-y-5 text-sm sm:text-base md:text-lg leading-relaxed text-justify"
            >
              <p class="relative z-10 font-medium text-gray-800 dark:text-gray-200">
                <i
                  data-lucide="quote"
                  class="absolute -top-4 -left-4 md:-top-6 md:-left-6 w-8 h-8 md:w-12 md:h-12 text-blue-100 dark:text-slate-800 -z-10 rotate-180"
                ></i>
                "Selamat datang di website resmi SMA Negeri 1 Nogosari. Kami berkomitmen
                untuk memberikan pendidikan berkualitas yang tidak hanya berfokus pada
                kecerdasan akademis, tetapi juga pembentukan karakter peserta didik yang
                berakhlak mulia dan berbudaya lingkungan."
              </p>
              <p>
                Di era digital dan globalisasi saat ini, lembaga pendidikan memiliki
                tanggung jawab besar. Oleh karena itu, kami terus berinovasi dalam metode
                pembelajaran serta melengkapi berbagai fasilitas guna memastikan anak-anak
                kita siap menghadapi tantangan masa depan dengan bekal ilmu dan iman yang
                seimbang.
              </p>
              <p>
                Kolaborasi yang erat antara guru, siswa, komite sekolah, dan masyarakat
                adalah kunci utama dari kesuksesan yang kita raih bersama. Melalui visi
                dan misi yang jelas, kami berharap dapat mewujudkan sekolah sebagai tempat
                yang menyenangkan untuk belajar dan bertumbuh.
              </p>
              <p>
                Terima kasih atas dukungan dan kepercayaan Bapak/Ibu sekalian terhadap
                sekolah kami. Mari kita bersama-sama mewujudkan generasi penerus yang
                cerdas, terampil, dan siap berkontribusi bagi nusa dan bangsa.
              </p>
            </div>

            <div
              class="mt-8 pt-6 border-t border-gray-100 dark:border-slate-800 inline-block"
            >
              <p class="font-bold text-blue-950 dark:text-white text-lg">
                Dr. H. Budi Santoso, M.Pd
              </p>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                NIP. 19750817 200003 1 004
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Visi & Misi Section -->
    <section class="relative w-full">
      <div class="flex flex-col lg:flex-row w-full">
        <div
          class="w-full lg:w-6/12 bg-gradient-to-br from-blue-900 to-blue-950 dark:from-slate-900 dark:to-slate-900 p-8 sm:p-10 md:p-16 lg:p-24 text-white relative overflow-hidden flex flex-col justify-center"
        >
          <div class="relative z-10">
            <h3
              class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 md:mb-6 tracking-wide"
            >
              Visi
            </h3>
            <p
              class="text-lg sm:text-xl md:text-2xl font-medium leading-relaxed text-blue-50"
            >
              "Terwujudnya Lulusan yang Bertaqwa, Berkarakter, Cerdas, Terampil, dan
              Berbudaya Lingkungan serta Mampu Bersaing di Era Global."
            </p>
          </div>
        </div>

        <div
          class="w-full lg:w-7/12 bg-white dark:bg-slate-900 p-8 sm:p-10 md:p-16 lg:p-24 flex flex-col justify-center"
        >
          <div class="mb-6 md:mb-8 pb-4 border-b border-gray-200 dark:border-slate-700">
            <h3
              class="text-2xl sm:text-3xl md:text-4xl font-bold text-blue-950 dark:text-white"
            >
              Misi
            </h3>
          </div>

          <ul class="space-y-4 md:space-y-6">
            <li class="flex items-start group">
              <span
                class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors rounded-full flex items-center justify-center font-bold text-sm md:text-base mt-0.5 mr-3 md:mr-5 shadow-sm border border-gray-100 dark:border-slate-700"
                >1</span
              >
              <p
                class="text-gray-700 dark:text-gray-300 text-sm sm:text-base md:text-lg pt-1 md:pt-1.5 leading-relaxed"
              >
                Menumbuhkembangkan keimanan dan ketaqwaan melalui pembiasaan kegiatan
                keagamaan di lingkungan sekolah.
              </p>
            </li>
            <li class="flex items-start group">
              <span
                class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors rounded-full flex items-center justify-center font-bold text-sm md:text-base mt-0.5 mr-3 md:mr-5 shadow-sm border border-gray-100 dark:border-slate-700"
                >2</span
              >
              <p
                class="text-gray-700 dark:text-gray-300 text-sm sm:text-base md:text-lg pt-1 md:pt-1.5 leading-relaxed"
              >
                Melaksanakan proses pembelajaran dan bimbingan secara efektif, inovatif,
                dan berpusat pada peserta didik.
              </p>
            </li>
            <li class="flex items-start group">
              <span
                class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors rounded-full flex items-center justify-center font-bold text-sm md:text-base mt-0.5 mr-3 md:mr-5 shadow-sm border border-gray-100 dark:border-slate-700"
                >3</span
              >
              <p
                class="text-gray-700 dark:text-gray-300 text-sm sm:text-base md:text-lg pt-1 md:pt-1.5 leading-relaxed"
              >
                Membina peserta didik untuk mengembangkan minat, bakat, dan potensi yang
                dimiliki melalui kegiatan ekstrakurikuler.
              </p>
            </li>
            <li class="flex items-start group">
              <span
                class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors rounded-full flex items-center justify-center font-bold text-sm md:text-base mt-0.5 mr-3 md:mr-5 shadow-sm border border-gray-100 dark:border-slate-700"
                >4</span
              >
              <p
                class="text-gray-700 dark:text-gray-300 text-sm sm:text-base md:text-lg pt-1 md:pt-1.5 leading-relaxed"
              >
                Mewujudkan lingkungan sekolah yang bersih, asri, aman, dan nyaman sebagai
                pilar utama pendukung proses belajar mengajar.
              </p>
            </li>
            <li class="flex items-start group">
              <span
                class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors rounded-full flex items-center justify-center font-bold text-sm md:text-base mt-0.5 mr-3 md:mr-5 shadow-sm border border-gray-100 dark:border-slate-700"
                >5</span
              >
              <p
                class="text-gray-700 dark:text-gray-300 text-sm sm:text-base md:text-lg pt-1 md:pt-1.5 leading-relaxed"
              >
                Meningkatkan kemampuan pemanfaatan teknologi informasi dan komunikasi
                untuk siap bersaing di tingkat global.
              </p>
            </li>
          </ul>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import PageHeader from "@/components/PageHeader.vue";
</script>
