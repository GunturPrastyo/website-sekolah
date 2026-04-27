<script setup>
import { ref, onMounted, onUpdated, nextTick } from "vue";
import { createIcons, icons } from "lucide";
import Swiper from "swiper/bundle";
import "swiper/swiper-bundle.css";

const currentStep = ref(1);
const totalSteps = 4;
const isSubmitted = ref(false);
const showToast = ref(false);
const isSubmitting = ref(false);

const form = ref({
  // Step 1: Data Diri
  nisn: "",
  namaLengkap: "",
  tempatLahir: "",
  tanggalLahir: "",
  jenisKelamin: "",
  agama: "",

  // Step 2: Data Orang Tua
  namaAyah: "",
  pekerjaanAyah: "",
  namaIbu: "",
  pekerjaanIbu: "",
  noTelp: "",
  alamat: "",

  // Step 3: Data Asal Sekolah
  asalSekolah: "",
  tahunLulus: "",

  // Step 4: Program & Jalur
  jalurPendaftaran: "",
  pilihanJurusan: "",
});

const regNumber = ref("");
const registrationDate = ref("");

const nextStep = () => {
  // Validasi step saat ini sebelum bisa ke step selanjutnya
  const formEl = document.getElementById("pendaftaran-form");
  if (formEl && !formEl.reportValidity()) {
    return;
  }

  if (currentStep.value < totalSteps) {
    currentStep.value++;
    scrollToForm();
  }
};

const prevStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--;
    scrollToForm();
  }
};

const submitForm = () => {
  isSubmitting.value = true;
  // Simulasi proses pengiriman data ke server
  setTimeout(() => {
    isSubmitting.value = false;
    isSubmitted.value = true;
    showToast.value = true;
    regNumber.value = "PPDB-" + (Math.floor(Math.random() * 90000) + 10000);
    scrollToForm();

    // Sembunyikan toast otomatis setelah 4 detik
    setTimeout(() => {
      showToast.value = false;
    }, 4000);
  }, 1500); // Waktu loading ditingkatkan menjadi 1.5 detik agar lebih realistis
};

const scrollToForm = () => {
  const formEl = document.getElementById("form-area");
  if (formEl) {
    window.scrollTo({ top: formEl.offsetTop - 100, behavior: "smooth" });
  }
};

const printBukti = async () => {
  registrationDate.value = new Date().toLocaleDateString("id-ID", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });

  await nextTick();

  try {
    // Import html2pdf secara dinamis
    const html2pdf = (await import("html2pdf.js")).default;
    const element = document.getElementById("pdf-content");

    // Tampilkan elemen sementara untuk dirender oleh canvas
    element.classList.remove("hidden");
    element.style.display = "block";

    const opt = {
      margin: 0.5,
      filename: `Bukti_Pendaftaran_${regNumber.value}.pdf`,
      image: { type: "jpeg", quality: 0.98 },
      html2canvas: { scale: 2, useCORS: true },
      jsPDF: { unit: "in", format: "a4", orientation: "portrait" },
    };

    await html2pdf().set(opt).from(element).save();

    // Sembunyikan kembali
    element.style.display = "";
    element.classList.add("hidden");
  } catch (error) {
    console.error("html2pdf.js belum terinstall atau gagal memproses PDF:", error);
    alert(
      "Sedang mengalihkan ke mode cetak standar. Untuk mengaktifkan mode unduh PDF, pastikan module html2pdf.js terinstall (npm install html2pdf.js)."
    );
    window.print();
  }
};

onMounted(() => {
  nextTick(() => {
    createIcons({ icons });

    // Inisialisasi Swiper untuk Jalur Pendaftaran
    new Swiper(".jalur-swiper", {
      direction: "horizontal", // default slider horizontal untuk versi Mobile
      loop: true,
      loopAdditionalSlides: 4,
      speed: 4000,
      autoplay: {
        delay: 0,
        disableOnInteraction: false,
      },
      slidesPerView: 1.2,
      centeredSlides: true,
      spaceBetween: 20,
      mousewheel: {
        forceToAxis: true, // Menghindari halaman tertahan saat scroll beda sumbu
      },
      grabCursor: true,
      pagination: {
        el: ".jalur-pagination",
        clickable: true,
      },
      breakpoints: {
        1024: {
          direction: "vertical", // Beralih ke slider vertikal untuk versi Desktop
          slidesPerView: 1.5,
          spaceBetween: 24,
        },
      },
    });
  });
});

onUpdated(() => {
  nextTick(() => {
    createIcons({ icons });
  });
});
</script>

<template>
  <div>
    <div class="print:hidden">
      <!-- Hero Banner -->
      <div
        class="relative pt-32 pb-24 lg:pt-44 lg:pb-32 bg-blue-950 dark:bg-slate-900 overflow-hidden"
      >
        <div class="container relative z-10 mx-auto px-6 text-center">
          <span
            class="inline-block px-4 py-1.5 mb-5 text-xs md:text-sm font-bold text-blue-900 bg-yellow-400 rounded-full shadow-sm tracking-wide"
          >
            TAHUN AJARAN 2026/2027
          </span>
          <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
            Penerimaan Peserta Didik Baru
          </h1>
          <p class="text-blue-100 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
            Bergabunglah bersama SMAN 1 Nogosari. Pelajari informasi pendaftaran dan isi
            formulir di bawah ini dengan data yang valid.
          </p>
        </div>
      </div>

      <!-- Informasi & Syarat Pendaftaran -->
      <section
        class="py-12 md:py-16 px-6 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700"
      >
        <div class="container mx-auto max-w-5xl">
          <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
              Informasi & Persyaratan
            </h2>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
              Pastikan Anda membaca ketentuan berikut sebelum mengisi formulir.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
            <!-- Syarat Utama -->
            <div
              class="bg-blue-50/20 dark:bg-slate-700/30 p-6 md:p-8 rounded-xl border border-gray-300 dark:border-slate-700 shadow-lg"
            >
              <h3
                class="text-xl font-bold text-blue-900 dark:text-white mb-5 flex items-center"
              >
                <i
                  data-lucide="file-check-2"
                  class="w-6 h-6 mr-2 text-blue-600 dark:text-blue-400"
                ></i>
                Syarat Pendaftaran
              </h3>
              <ul class="space-y-4">
                <li class="flex items-start">
                  <i
                    data-lucide="check-circle"
                    class="w-5 h-5 text-green-500 mr-3 shrink-0 mt-0.5"
                  ></i>
                  <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"
                    >Lulusan SMP/MTs sederajat tahun 2024, 2025, atau 2026.</span
                  >
                </li>
                <li class="flex items-start">
                  <i
                    data-lucide="check-circle"
                    class="w-5 h-5 text-green-500 mr-3 shrink-0 mt-0.5"
                  ></i>
                  <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"
                    >Memiliki Nomor Induk Siswa Nasional (NISN) yang valid.</span
                  >
                </li>
                <li class="flex items-start">
                  <i
                    data-lucide="check-circle"
                    class="w-5 h-5 text-green-500 mr-3 shrink-0 mt-0.5"
                  ></i>
                  <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"
                    >Menyiapkan berkas digital (Scan KK, Akta Kelahiran, dan
                    Ijazah/SKL).</span
                  >
                </li>
                <li class="flex items-start">
                  <i
                    data-lucide="check-circle"
                    class="w-5 h-5 text-green-500 mr-3 shrink-0 mt-0.5"
                  ></i>
                  <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"
                    >Pas foto terbaru ukuran 3x4 berwarna (pakaian seragam asal).</span
                  >
                </li>
              </ul>
            </div>

            <!-- Alur Pendaftaran -->
            <div
              class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-xl border border-gray-300 dark:border-slate-700 shadow-lg"
            >
              <h3
                class="text-xl font-bold text-gray-900 dark:text-white mb-5 flex items-center"
              >
                <i
                  data-lucide="git-merge"
                  class="w-6 h-6 mr-2 text-blue-600 dark:text-blue-400"
                ></i>
                Alur Pendaftaran
              </h3>
              <div
                class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-blue-200 before:via-blue-200 dark:before:via-slate-600 before:to-transparent"
              >
                <div class="relative flex items-center gap-4">
                  <div
                    class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border-2 border-blue-500 dark:border-blue-400 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold shrink-0 z-10"
                  >
                    1
                  </div>
                  <div>
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm">
                      Isi Formulir Online
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                      Lengkapi data diri, asal sekolah, dan jurusan di bawah ini.
                    </p>
                  </div>
                </div>
                <div class="relative flex items-center gap-4">
                  <div
                    class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border-2 border-blue-500 dark:border-blue-400 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold shrink-0 z-10"
                  >
                    2
                  </div>
                  <div>
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm">
                      Cetak Bukti Daftar
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                      Setelah submit, simpan dan cetak bukti pendaftaran otomatis.
                    </p>
                  </div>
                </div>
                <div class="relative flex items-center gap-4">
                  <div
                    class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border-2 border-blue-500 dark:border-blue-400 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold shrink-0 z-10"
                  >
                    3
                  </div>
                  <div>
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm">
                      Verifikasi Berkas
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                      Bawa dokumen fisik ke sekolah sesuai jadwal yang tertera.
                    </p>
                  </div>
                </div>
                <div class="relative flex items-center gap-4">
                  <div
                    class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border-2 border-gray-300 dark:border-slate-600 flex items-center justify-center text-gray-400 font-bold shrink-0 z-10"
                  >
                    4
                  </div>
                  <div>
                    <h4 class="font-bold text-gray-400 dark:text-gray-500 text-sm">
                      Pengumuman
                    </h4>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                      Hasil kelulusan akan diinformasikan di portal resmi.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Jalur Pendaftaran Section -->
      <section
        class="py-12 md:py-8 px-6 bg-blue-950 dark:bg-slate-900 relative overflow-hidden border-b border-blue-900 dark:border-slate-800"
      >
        <div class="container mx-auto max-w-6xl relative z-10">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
            <!-- Left: Teks Deskripsi -->
            <div class="lg:col-span-5 text-center lg:text-left">
              <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">
                Jalur Pendaftaran PPDB
              </h2>
              <p
                class="text-blue-100 dark:text-gray-300 text-base md:text-lg leading-relaxed mb-8"
              >
                Sistem Penerimaan Peserta Didik Baru terbagi menjadi 4 jalur utama.
                Pastikan Anda memilih jalur yang paling sesuai dengan kondisi dan
                kualifikasi untuk memperbesar peluang diterima.
              </p>

              <!-- Decorative Element on Desktop -->
              <div
                class="hidden lg:flex items-center gap-3 text-yellow-400 font-semibold"
              >
                <div
                  class="w-10 h-10 rounded-full border-2 border-yellow-400 flex items-center justify-center animate-bounce"
                >
                  <i data-lucide="arrow-down" class="w-5 h-5"></i>
                </div>
                Scroll untuk melihat jalur
              </div>
            </div>

            <!-- Right: Slider Container -->
            <div
              class="lg:col-span-7 relative h-[380px] lg:h-[520px] w-full flex items-center justify-center"
            >
              <!-- Slider with Mask -->
              <div class="fade-mask-slider w-full h-full absolute inset-0">
                <div class="swiper jalur-swiper h-full w-full lg:!py-8 lg:!px-10">
                  <div class="swiper-wrapper items-stretch">
                    <!-- Card Zonasi -->
                    <div class="swiper-slide">
                      <div
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-lg hover:shadow-2xl relative group transform transition-all duration-500 h-full flex flex-col overflow-hidden border border-gray-100 dark:border-slate-700/60 border-b-4 border-b-yellow-400 dark:border-b-yellow-500"
                      >
                        <!-- Solid Color Header -->
                        <div
                          class="h-32 sm:h-36 overflow-hidden relative shrink-0 bg-blue-600 dark:bg-blue-800 transition-colors duration-500 group-hover:bg-blue-700 dark:group-hover:bg-blue-600"
                        >
                          <div
                            class="absolute top-5 left-5 px-3.5 py-1.5 bg-yellow-400 text-blue-950 text-xs font-bold rounded-full shadow-sm border border-yellow-300"
                          >
                            Kuota 50%
                          </div>
                        </div>
                        <div
                          class="relative z-10 px-6 md:px-8 pb-8 flex-1 flex flex-col -mt-8"
                        >
                          <div
                            class="w-12 h-12 bg-yellow-400 dark:bg-yellow-500 text-blue-950 rounded-2xl flex items-center justify-center mb-4 shadow-md border border-yellow-300 dark:border-yellow-400 group-hover:scale-110 transition-transform"
                          >
                            <i data-lucide="map" class="w-6 h-6"></i>
                          </div>
                          <h3
                            class="text-xl font-bold text-gray-900 dark:text-white mb-3"
                          >
                            Jalur Zonasi
                          </h3>
                          <p
                            class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed"
                          >
                            Diperuntukkan bagi calon peserta didik yang berdomisili di
                            dalam wilayah zonasi yang telah ditetapkan berdasarkan jarak
                            titik koordinat terdekat dari sekolah.
                          </p>
                        </div>
                      </div>
                    </div>

                    <!-- Card Prestasi -->
                    <div class="swiper-slide">
                      <div
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-lg hover:shadow-2xl relative group transform transition-all duration-500 h-full flex flex-col overflow-hidden border border-gray-100 dark:border-slate-700/60 border-b-4 border-b-yellow-400 dark:border-b-yellow-500"
                      >
                        <!-- Solid Color Header -->
                        <div
                          class="h-32 sm:h-36 overflow-hidden relative shrink-0 bg-blue-600 dark:bg-blue-800 transition-colors duration-500 group-hover:bg-blue-700 dark:group-hover:bg-blue-600"
                        >
                          <div
                            class="absolute top-5 left-5 px-3.5 py-1.5 bg-yellow-400 text-blue-950 text-xs font-bold rounded-full shadow-sm border border-yellow-300"
                          >
                            Kuota 30%
                          </div>
                        </div>
                        <div
                          class="relative z-10 px-6 md:px-8 pb-8 flex-1 flex flex-col -mt-8"
                        >
                          <div
                            class="w-12 h-12 bg-yellow-400 dark:bg-yellow-500 text-blue-950 rounded-2xl flex items-center justify-center mb-4 shadow-md border border-yellow-300 dark:border-yellow-400 group-hover:scale-110 transition-transform"
                          >
                            <i data-lucide="award" class="w-6 h-6"></i>
                          </div>
                          <h3
                            class="text-xl font-bold text-gray-900 dark:text-white mb-3"
                          >
                            Jalur Prestasi
                          </h3>
                          <p
                            class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed"
                          >
                            Penerimaan berdasarkan akumulasi nilai rapor semester 1-5 atau
                            sertifikat prestasi kejuaraan akademik maupun non-akademik.
                          </p>
                        </div>
                      </div>
                    </div>

                    <!-- Card Afirmasi -->
                    <div class="swiper-slide">
                      <div
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-lg hover:shadow-2xl relative group transform transition-all duration-500 h-full flex flex-col overflow-hidden border border-gray-100 dark:border-slate-700/60 border-b-4 border-b-yellow-400 dark:border-b-yellow-500"
                      >
                        <!-- Solid Color Header -->
                        <div
                          class="h-32 sm:h-36 overflow-hidden relative shrink-0 bg-blue-600 dark:bg-blue-800 transition-colors duration-500 group-hover:bg-blue-700 dark:group-hover:bg-blue-600"
                        >
                          <div
                            class="absolute top-5 left-5 px-3.5 py-1.5 bg-yellow-400 text-blue-950 text-xs font-bold rounded-full shadow-sm border border-yellow-300"
                          >
                            Kuota 15%
                          </div>
                        </div>
                        <div
                          class="relative z-10 px-6 md:px-8 pb-8 flex-1 flex flex-col -mt-8"
                        >
                          <div
                            class="w-12 h-12 bg-yellow-400 dark:bg-yellow-500 text-blue-950 rounded-2xl flex items-center justify-center mb-4 shadow-md border border-yellow-300 dark:border-yellow-400 group-hover:scale-110 transition-transform"
                          >
                            <i data-lucide="heart-handshake" class="w-6 h-6"></i>
                          </div>
                          <h3
                            class="text-xl font-bold text-gray-900 dark:text-white mb-3"
                          >
                            Jalur Afirmasi
                          </h3>
                          <p
                            class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed"
                          >
                            Khusus ditujukan bagi calon peserta didik dari keluarga
                            ekonomi tidak mampu (dibuktikan dengan KIP/PKH) dan penyandang
                            disabilitas.
                          </p>
                        </div>
                      </div>
                    </div>

                    <!-- Card Pindah Tugas -->
                    <div class="swiper-slide">
                      <div
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-lg hover:shadow-2xl relative group transform transition-all duration-500 h-full flex flex-col overflow-hidden border border-gray-100 dark:border-slate-700/60 border-b-4 border-b-yellow-400 dark:border-b-yellow-500"
                      >
                        <!-- Solid Color Header -->
                        <div
                          class="h-32 sm:h-36 overflow-hidden relative shrink-0 bg-blue-600 dark:bg-blue-800 transition-colors duration-500 group-hover:bg-blue-700 dark:group-hover:bg-blue-600"
                        >
                          <div
                            class="absolute top-5 left-5 px-3.5 py-1.5 bg-yellow-400 text-blue-950 text-xs font-bold rounded-full shadow-sm border border-yellow-300"
                          >
                            Kuota 5%
                          </div>
                        </div>
                        <div
                          class="relative z-10 px-6 md:px-8 pb-8 flex-1 flex flex-col -mt-8"
                        >
                          <div
                            class="w-12 h-12 bg-yellow-400 dark:bg-yellow-500 text-blue-950 rounded-2xl flex items-center justify-center mb-4 shadow-md border border-yellow-300 dark:border-yellow-400 group-hover:scale-110 transition-transform"
                          >
                            <i data-lucide="briefcase" class="w-6 h-6"></i>
                          </div>
                          <h3
                            class="text-xl font-bold text-gray-900 dark:text-white mb-3"
                          >
                            Pindah Tugas
                          </h3>
                          <p
                            class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed"
                          >
                            Diperuntukkan bagi peserta didik yang mengikuti kepindahan
                            tugas orang tua/wali dari instansi, atau anak kandung dari
                            guru.
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pagination Component -->
            <div class="jalur-pagination absolute z-30 pointer-events-auto"></div>
          </div>
        </div>
      </section>
    </div>

    <!-- Main Content Section -->
    <section
      id="form-area"
      class="py-12 md:py-16 lg:py-20 px-6 bg-gray-50 dark:bg-slate-800"
    >
      <div class="container mx-auto max-w-6xl">
        <!-- Form Header -->
        <div class="text-center mb-10">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Formulir Pendaftaran
          </h2>
          <p
            class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-sm md:text-base"
          >
            Silakan lengkapi data diri, informasi orang tua, riwayat sekolah, dan pilihan
            jurusan Anda pada formulir di bawah ini dengan benar dan valid.
          </p>
        </div>

        <div
          class="bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-gray-100 dark:border-slate-700 overflow-hidden"
        >
          <!-- Sukses State -->
          <div v-if="isSubmitted" class="p-10 md:p-16 text-center">
            <div
              class="w-24 h-24 bg-green-100 dark:bg-green-900/30 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6"
            >
              <i data-lucide="check-circle" class="w-12 h-12"></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-4">
              Pendaftaran Berhasil!
            </h2>
            <p
              class="text-gray-600 dark:text-gray-400 max-w-lg mx-auto mb-8 leading-relaxed"
            >
              Terima kasih <strong>{{ form.namaLengkap || "Calon Siswa" }}</strong
              >, data pendaftaran Anda telah kami terima. Silakan cetak bukti pendaftaran
              di bawah ini dan bawa saat melakukan verifikasi berkas di sekolah.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
              <button
                @click="printBukti"
                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center"
              >
                <i data-lucide="printer" class="w-5 h-5 mr-2"></i> Cetak Bukti Daftar
              </button>
              <router-link
                to="/"
                class="px-6 py-3 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-800 dark:text-gray-200 font-semibold rounded-xl transition-colors flex items-center justify-center"
              >
                Kembali ke Beranda
              </router-link>
            </div>
          </div>

          <!-- Form Multi-step -->
          <div v-else>
            <!-- Progress Bar Indicator -->
            <div
              class="bg-gray-50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-700 p-6 md:p-8"
            >
              <div class="flex items-center justify-between relative">
                <div
                  class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-200 dark:bg-slate-700 z-0 rounded-full"
                ></div>
                <!-- Progress Fill -->
                <div
                  class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-blue-600 z-0 rounded-full transition-all duration-500"
                  :style="{ width: `${((currentStep - 1) / (totalSteps - 1)) * 100}%` }"
                ></div>

                <!-- Step Dots -->
                <div
                  v-for="step in totalSteps"
                  :key="step"
                  class="relative z-10 flex flex-col items-center group"
                >
                  <div
                    class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-sm"
                    :class="[
                      currentStep > step ? 'bg-blue-600 text-white' : '',
                      currentStep === step
                        ? 'bg-blue-600 text-white ring-4 ring-blue-100 dark:ring-blue-900/50'
                        : '',
                      currentStep < step
                        ? 'bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-600 text-gray-400'
                        : '',
                    ]"
                  >
                    <i v-if="currentStep > step" data-lucide="check" class="w-5 h-5"></i>
                    <span v-else>{{ step }}</span>
                  </div>
                  <span
                    class="absolute -bottom-6 text-[10px] sm:text-xs font-semibold whitespace-nowrap hidden sm:block"
                    :class="
                      currentStep >= step
                        ? 'text-blue-600 dark:text-blue-400'
                        : 'text-gray-400'
                    "
                  >
                    {{
                      step === 1
                        ? "Data Diri"
                        : step === 2
                        ? "Orang Tua"
                        : step === 3
                        ? "Sekolah Asal"
                        : "Pilihan"
                    }}
                  </span>
                </div>
              </div>
            </div>

            <div class="p-6 md:p-10">
              <form id="pendaftaran-form" @submit.prevent="submitForm">
                <!-- STEP 1: DATA DIRI -->
                <div v-if="currentStep === 1" class="space-y-6 animate-fade-in">
                  <h3
                    class="text-xl font-bold text-gray-900 dark:text-white border-l-4 border-blue-600 pl-3 mb-6"
                  >
                    Identitas Calon Siswa
                  </h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >NISN <span class="text-red-500">*</span></label
                      >
                      <input
                        v-model="form.nisn"
                        type="text"
                        required
                        placeholder="Masukkan 10 digit NISN"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Nama Lengkap <span class="text-red-500">*</span></label
                      >
                      <input
                        v-model="form.namaLengkap"
                        type="text"
                        required
                        placeholder="Sesuai Akta Kelahiran"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Tempat Lahir</label
                      >
                      <input
                        v-model="form.tempatLahir"
                        type="text"
                        placeholder="Kota/Kabupaten Lahir"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Tanggal Lahir</label
                      >
                      <input
                        v-model="form.tanggalLahir"
                        type="date"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Jenis Kelamin</label
                      >
                      <select
                        v-model="form.jenisKelamin"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      >
                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                      </select>
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Agama</label
                      >
                      <select
                        v-model="form.agama"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      >
                        <option value="" disabled selected>Pilih Agama</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Konghucu">Konghucu</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 2: DATA ORANG TUA -->
                <div v-if="currentStep === 2" class="space-y-6 animate-fade-in">
                  <h3
                    class="text-xl font-bold text-gray-900 dark:text-white border-l-4 border-blue-600 pl-3 mb-6"
                  >
                    Data Orang Tua / Wali
                  </h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Nama Ayah</label
                      >
                      <input
                        v-model="form.namaAyah"
                        type="text"
                        placeholder="Nama Lengkap Ayah"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Pekerjaan Ayah</label
                      >
                      <input
                        v-model="form.pekerjaanAyah"
                        type="text"
                        placeholder="Pekerjaan Ayah"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Nama Ibu</label
                      >
                      <input
                        v-model="form.namaIbu"
                        type="text"
                        placeholder="Nama Lengkap Ibu"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Pekerjaan Ibu</label
                      >
                      <input
                        v-model="form.pekerjaanIbu"
                        type="text"
                        placeholder="Pekerjaan Ibu"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      />
                    </div>
                    <div class="space-y-2 md:col-span-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >No. Telepon / WhatsApp <span class="text-red-500">*</span></label
                      >
                      <input
                        v-model="form.noTelp"
                        type="tel"
                        required
                        placeholder="Contoh: 08123456789"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      />
                    </div>
                    <div class="space-y-2 md:col-span-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Alamat Tempat Tinggal Lengkap</label
                      >
                      <textarea
                        v-model="form.alamat"
                        rows="3"
                        placeholder="Jl. Raya No..., RT/RW, Desa, Kecamatan..."
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      ></textarea>
                    </div>
                  </div>
                </div>

                <!-- STEP 3: DATA SEKOLAH ASAL -->
                <div v-if="currentStep === 3" class="space-y-6 animate-fade-in">
                  <h3
                    class="text-xl font-bold text-gray-900 dark:text-white border-l-4 border-blue-600 pl-3 mb-6"
                  >
                    Riwayat Pendidikan Sebelumnya
                  </h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 md:col-span-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Asal Sekolah (SMP/MTs) <span class="text-red-500">*</span></label
                      >
                      <input
                        v-model="form.asalSekolah"
                        type="text"
                        required
                        placeholder="Nama Sekolah Asal"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Tahun Lulus</label
                      >
                      <select
                        v-model="form.tahunLulus"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                      >
                        <option value="" disabled selected>Pilih Tahun</option>
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="Sebelumnya">Sebelumnya</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 4: PILIHAN JURUSAN & JALUR -->
                <div v-if="currentStep === 4" class="space-y-6 animate-fade-in">
                  <h3
                    class="text-xl font-bold text-gray-900 dark:text-white border-l-4 border-blue-600 pl-3 mb-6"
                  >
                    Pilihan Jalur & Peminatan
                  </h3>

                  <div class="space-y-8">
                    <!-- Jalur Pendaftaran Radio Cards -->
                    <div class="space-y-3">
                      <label class="text-sm font-bold text-gray-900 dark:text-white"
                        >Jalur Pendaftaran <span class="text-red-500">*</span></label
                      >
                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label
                          class="relative flex cursor-pointer rounded-xl border bg-white dark:bg-slate-800 p-4 shadow-sm focus:outline-none transition-all"
                          :class="
                            form.jalurPendaftaran === 'Zonasi'
                              ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50 dark:bg-slate-700'
                              : 'border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700/50'
                          "
                        >
                          <input
                            type="radio"
                            v-model="form.jalurPendaftaran"
                            value="Zonasi"
                            class="sr-only"
                            required
                          />
                          <div class="flex w-full items-center justify-between">
                            <div class="flex items-center">
                              <div class="text-sm">
                                <p class="font-bold text-gray-900 dark:text-white">
                                  Zonasi
                                </p>
                                <p class="text-gray-500 dark:text-gray-400 mt-1">
                                  Berdasarkan jarak domisili terdekat.
                                </p>
                              </div>
                            </div>
                            <i
                              v-if="form.jalurPendaftaran === 'Zonasi'"
                              data-lucide="check-circle-2"
                              class="w-6 h-6 text-blue-600"
                            ></i>
                          </div>
                        </label>

                        <label
                          class="relative flex cursor-pointer rounded-xl border bg-white dark:bg-slate-800 p-4 shadow-sm focus:outline-none transition-all"
                          :class="
                            form.jalurPendaftaran === 'Prestasi'
                              ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50 dark:bg-slate-700'
                              : 'border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700/50'
                          "
                        >
                          <input
                            type="radio"
                            v-model="form.jalurPendaftaran"
                            value="Prestasi"
                            class="sr-only"
                          />
                          <div class="flex w-full items-center justify-between">
                            <div class="flex items-center">
                              <div class="text-sm">
                                <p class="font-bold text-gray-900 dark:text-white">
                                  Prestasi
                                </p>
                                <p class="text-gray-500 dark:text-gray-400 mt-1">
                                  Nilai rapor atau sertifikat lomba.
                                </p>
                              </div>
                            </div>
                            <i
                              v-if="form.jalurPendaftaran === 'Prestasi'"
                              data-lucide="check-circle-2"
                              class="w-6 h-6 text-blue-600"
                            ></i>
                          </div>
                        </label>
                      </div>
                    </div>

                    <!-- Pilihan Jurusan Radio Cards -->
                    <div class="space-y-3">
                      <label class="text-sm font-bold text-gray-900 dark:text-white"
                        >Peminatan Jurusan <span class="text-red-500">*</span></label
                      >
                      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <label
                          class="relative flex cursor-pointer rounded-xl border bg-white dark:bg-slate-800 p-4 shadow-sm focus:outline-none transition-all"
                          :class="
                            form.pilihanJurusan === 'IPA'
                              ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50 dark:bg-slate-700'
                              : 'border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700/50'
                          "
                        >
                          <input
                            type="radio"
                            v-model="form.pilihanJurusan"
                            value="IPA"
                            class="sr-only"
                            required
                          />
                          <div class="text-center w-full">
                            <div
                              class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 mb-3"
                            >
                              <i data-lucide="flask-conical" class="h-5 w-5"></i>
                            </div>
                            <p class="font-bold text-gray-900 dark:text-white">IPA</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                              Ilmu Pengetahuan Alam
                            </p>
                          </div>
                        </label>

                        <label
                          class="relative flex cursor-pointer rounded-xl border bg-white dark:bg-slate-800 p-4 shadow-sm focus:outline-none transition-all"
                          :class="
                            form.pilihanJurusan === 'IPS'
                              ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50 dark:bg-slate-700'
                              : 'border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700/50'
                          "
                        >
                          <input
                            type="radio"
                            v-model="form.pilihanJurusan"
                            value="IPS"
                            class="sr-only"
                          />
                          <div class="text-center w-full">
                            <div
                              class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 dark:bg-sky-900/30 text-sky-600 mb-3"
                            >
                              <i data-lucide="globe-2" class="h-5 w-5"></i>
                            </div>
                            <p class="font-bold text-gray-900 dark:text-white">IPS</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                              Ilmu Pengetahuan Sosial
                            </p>
                          </div>
                        </label>

                        <label
                          class="relative flex cursor-pointer rounded-xl border bg-white dark:bg-slate-800 p-4 shadow-sm focus:outline-none transition-all"
                          :class="
                            form.pilihanJurusan === 'Bahasa'
                              ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50 dark:bg-slate-700'
                              : 'border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700/50'
                          "
                        >
                          <input
                            type="radio"
                            v-model="form.pilihanJurusan"
                            value="Bahasa"
                            class="sr-only"
                          />
                          <div class="text-center w-full">
                            <div
                              class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 mb-3"
                            >
                              <i data-lucide="languages" class="h-5 w-5"></i>
                            </div>
                            <p class="font-bold text-gray-900 dark:text-white">Bahasa</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                              Sastra & Budaya
                            </p>
                          </div>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Navigation Buttons -->
                <div
                  class="mt-10 pt-6 border-t border-gray-100 dark:border-slate-700 flex justify-between"
                >
                  <button
                    type="button"
                    @click="prevStep"
                    class="px-6 py-2.5 rounded-xl font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
                    :class="currentStep === 1 ? 'invisible' : ''"
                  >
                    Sebelumnya
                  </button>

                  <button
                    v-if="currentStep < totalSteps"
                    type="button"
                    @click="nextStep"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-colors flex items-center"
                  >
                    Selanjutnya <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                  </button>

                  <button
                    v-else
                    type="submit"
                    :disabled="isSubmitting"
                    class="px-8 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-md transition-colors flex items-center disabled:opacity-75 disabled:cursor-not-allowed"
                  >
                    <span v-if="isSubmitting" class="flex items-center">
                      <i data-lucide="loader-2" class="w-5 h-5 mr-2 animate-spin"></i>
                      Mengirim...
                    </span>
                    <span v-else class="flex items-center">
                      Kirim Pendaftaran <i data-lucide="send" class="w-4 h-4 ml-2"></i>
                    </span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Toast Notification -->
    <Transition
      enter-active-class="transition duration-300 ease-out transform"
      enter-from-class="opacity-0 translate-y-[-2rem]"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-200 ease-in transform"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-[-2rem]"
    >
      <div
        v-if="showToast"
        class="fixed top-24 right-6 sm:right-10 z-[100] flex items-center p-4 w-full max-w-xs bg-white dark:bg-slate-800 rounded-xl shadow-2xl border-l-4 border-green-500"
        role="alert"
      >
        <div
          class="inline-flex items-center justify-center shrink-0 w-10 h-10 text-green-600 bg-green-100 rounded-lg dark:bg-green-900/50 dark:text-green-400"
        >
          <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
        <div class="ml-3 text-sm font-normal">
          <span class="mb-1 text-sm font-bold text-gray-900 dark:text-white block"
            >Pendaftaran Berhasil!</span
          >
          <div class="text-xs text-gray-600 dark:text-gray-400">
            Data Anda telah masuk ke sistem.
          </div>
        </div>
        <button
          @click="showToast = false"
          type="button"
          class="ml-auto -mx-1.5 -my-1.5 bg-white dark:bg-slate-800 text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 dark:hover:bg-slate-700 inline-flex items-center justify-center h-8 w-8 transition-colors"
        >
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>
    </Transition>

    <!-- AREA KHUSUS CETAK BUKTI PENDAFTARAN -->
    <!-- Div ini hanya akan terlihat ketika pengguna menekan CTRL+P atau tombol Cetak -->
    <div
      id="pdf-content"
      class="hidden print:block bg-white text-black p-8 md:p-12 z-[99999]"
    >
      <div
        class="max-w-4xl mx-auto border-[3px] border-gray-900 p-10 rounded-xl relative"
      >
        <!-- Header Kop Surat -->
        <div
          class="flex items-center justify-center border-b-4 border-gray-900 pb-6 mb-8 gap-6"
        >
          <!-- Placeholder Logo -->
          <div
            class="w-20 h-20 bg-gray-200 border-2 border-gray-800 rounded-full flex items-center justify-center font-bold text-xs text-gray-500"
          >
            LOGO
          </div>
          <div class="text-center">
            <h1 class="text-2xl font-black uppercase tracking-wider">
              Pemerintah Provinsi Jawa Tengah
            </h1>
            <h2 class="text-3xl font-black uppercase tracking-widest mt-1">
              SMA Negeri 1 Nogosari
            </h2>
            <p class="text-sm font-medium mt-2">
              Jl. Raya Nogosari No. 123, Kab. Boyolali, Jawa Tengah 57378
            </p>
          </div>
        </div>

        <div class="text-center mb-8">
          <h3
            class="text-xl font-bold bg-gray-200 inline-block px-6 py-2 border-2 border-gray-900 uppercase"
          >
            Tanda Bukti Pendaftaran PPDB
          </h3>
        </div>

        <!-- Meta Info -->
        <div
          class="flex justify-between items-start mb-8 text-sm font-bold bg-gray-100 p-4 border-2 border-gray-900"
        >
          <div>
            <p class="text-gray-600 uppercase text-xs mb-1">Waktu Pendaftaran</p>
            <p>{{ registrationDate }}</p>
          </div>
          <div class="text-right">
            <p class="text-gray-600 uppercase text-xs mb-1">Nomor Registrasi</p>
            <p class="text-2xl tracking-widest">{{ regNumber }}</p>
          </div>
        </div>

        <!-- Table Data Siswa -->
        <table
          class="w-full text-left border-collapse border-2 border-gray-900 text-sm mb-12"
        >
          <tbody>
            <tr class="border-b border-gray-900">
              <td class="py-4 px-6 border-r border-gray-900 font-bold w-1/3 bg-gray-50">
                NISN
              </td>
              <td class="py-4 px-6 font-bold text-lg">{{ form.nisn }}</td>
            </tr>
            <tr class="border-b border-gray-900">
              <td class="py-4 px-6 border-r border-gray-900 font-bold bg-gray-50">
                Nama Lengkap Siswa
              </td>
              <td class="py-4 px-6 font-bold text-lg">{{ form.namaLengkap }}</td>
            </tr>
            <tr class="border-b border-gray-900">
              <td class="py-4 px-6 border-r border-gray-900 font-bold bg-gray-50">
                Asal Sekolah
              </td>
              <td class="py-4 px-6 uppercase">
                {{ form.asalSekolah }} (Lulus: {{ form.tahunLulus }})
              </td>
            </tr>
            <tr class="border-b border-gray-900">
              <td class="py-4 px-6 border-r border-gray-900 font-bold bg-gray-50">
                Jalur Pendaftaran
              </td>
              <td class="py-4 px-6 font-black uppercase text-blue-800">
                {{ form.jalurPendaftaran }}
              </td>
            </tr>
            <tr>
              <td class="py-4 px-6 border-r border-gray-900 font-bold bg-gray-50">
                Pilihan Jurusan/Peminatan
              </td>
              <td class="py-4 px-6 font-black uppercase text-blue-800">
                {{ form.pilihanJurusan }}
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Catatan Footer -->
        <div
          class="text-xs text-gray-800 text-center border-t-2 border-gray-900 pt-6 font-medium"
        >
          <p>
            Kertas ini merupakan bukti sah pendaftaran online Anda. Harap dibawa saat
            melakukan verifikasi dokumen fisik di sekolah.
          </p>
          <p class="mt-1 italic">
            Dicetak secara otomatis oleh Sistem PPDB SMAN 1 Nogosari.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Mengatur transisi animasi menjadi linear tanpa henti (efek ticker) */
:deep(.jalur-swiper .swiper-wrapper) {
  transition-timing-function: linear !important;
}

/* Custom styles for Swiper Mask & Fade */
.fade-mask-slider {
  -webkit-mask-image: linear-gradient(
    to right,
    transparent 0%,
    black 5%,
    black 95%,
    transparent 100%
  );
  mask-image: linear-gradient(
    to right,
    transparent 0%,
    black 5%,
    black 95%,
    transparent 100%
  );
}

@media (min-width: 1024px) {
  .fade-mask-slider {
    -webkit-mask-image: linear-gradient(
      to bottom,
      transparent 0%,
      black 10%,
      black 90%,
      transparent 100%
    );
    mask-image: linear-gradient(
      to bottom,
      transparent 0%,
      black 10%,
      black 90%,
      transparent 100%
    );
  }
}

/* Custom styles for Jalur Swiper pagination */
:deep(.jalur-pagination) {
  display: flex;
  align-items: center;
  justify-content: center;
  bottom: 0px !important;
  left: 0;
  width: 100%;
  flex-direction: row;
}

@media (min-width: 1024px) {
  :deep(.jalur-pagination) {
    right: 0px !important;
    left: auto;
    top: 50% !important;
    transform: translateY(-50%);
    flex-direction: column;
    width: auto;
    bottom: auto !important;
  }
}

:deep(.jalur-pagination .swiper-pagination-bullet) {
  background-color: rgba(255, 255, 255, 0.4);
  transition: all 0.3s ease;
  margin: 6px !important;
}

:deep(.jalur-pagination .swiper-pagination-bullet-active) {
  background-color: #ffffff;
  width: 24px;
  border-radius: 6px;
}

@media (min-width: 1024px) {
  :deep(.jalur-pagination .swiper-pagination-bullet-active) {
    height: 24px;
    width: 8px;
    /* default bullet width */
  }
}
</style>
