<script setup>
import { ref, onMounted, nextTick } from "vue";
import {
  PhListChecks,
  PhCheckCircle,
  PhGitMerge,
  PhArrowDown,
  PhMapPin,
  PhMedal,
  PhHandshake,
  PhBriefcase,
  PhCheck,
  PhFlask,
  PhGlobe,
  PhTranslate,
  PhArrowRight,
  PhSpinner,
  PhPaperPlaneRight,
  PhPrinter,
  PhX,
  PhDownloadSimple,
  PhLink,
} from "@phosphor-icons/vue";
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

const ppdbInfo = ref(null);
const isLoading = ref(true);

const fetchPpdbInfo = async () => {
  try {
    const apiUrl = import.meta.env.VITE_API_URL || "http://localhost:8000/api";
    const response = await fetch(`${apiUrl}/ppdb-info`);
    const result = await response.json();
    // Ambil object data (Bisa berbeda tergantung format respon API Controller)
    ppdbInfo.value = result.data || result;
  } catch (error) {
    console.error("Gagal mengambil data PPDB:", error);
  } finally {
    isLoading.value = false;
  }
};

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
  fetchPpdbInfo();

  nextTick(() => {
    // Inisialisasi Swiper untuk Jalur Pendaftaran
    new Swiper(".jalur-swiper", {
      direction: "horizontal", // default slider horizontal untuk versi Mobile
      loop: true,
      loopAdditionalSlides: 4,
      speed: 800,
      autoplay: {
        delay: 3500,
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
            TAHUN AJARAN {{ new Date().getFullYear() }}/{{ new Date().getFullYear() + 1 }}
          </span>
          <h1
            class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight"
            style="font-family: 'Oswald', sans-serif"
          >
            Penerimaan Peserta Didik Baru
          </h1>
          <p class="text-blue-100 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
            Bergabunglah bersama SMAN 1 Nogosari. Pelajari informasi pendaftaran dan isi
            formulir di bawah ini dengan data yang valid.
          </p>

          <div
            v-if="!isLoading && ppdbInfo"
            class="flex flex-col sm:flex-row gap-4 justify-center items-center mt-8"
          >
            <a
              v-if="ppdbInfo.registration_link && ppdbInfo.status === 'open'"
              :href="ppdbInfo.registration_link"
              target="_blank"
              class="px-6 py-3 bg-yellow-400 text-blue-950 font-bold rounded-xl shadow-lg hover:bg-yellow-300 transition-all flex items-center"
            >
              <PhLink class="w-5 h-5 mr-2" />
              Daftar Sekarang
            </a>
            <span
              v-else-if="ppdbInfo.status !== 'open'"
              class="px-6 py-3 bg-gray-500 text-white font-bold rounded-xl shadow-lg flex items-center"
            >
              Pendaftaran Sedang Ditutup
            </span>

            <a
              v-if="ppdbInfo.brosur"
              :href="ppdbInfo.brosur"
              target="_blank"
              class="px-6 py-3 bg-white/10 text-white font-semibold border border-white/30 rounded-xl shadow-lg hover:bg-white/20 transition-all flex items-center"
            >
              <PhDownloadSimple class="w-5 h-5 mr-2" />
              Unduh Brosur
            </a>
          </div>
          <div v-else-if="isLoading" class="flex justify-center mt-8">
            <PhSpinner class="w-6 h-6 animate-spin text-blue-400" />
          </div>
        </div>
      </div>

      <!-- Informasi & Syarat Pendaftaran -->
      <section
        class="py-12 md:py-10 px-6 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700"
      >
        <div class="container mx-auto max-w-full px-0 lg:px-16">
          <div class="text-center mb-10">
            <h2
              class="text-3xl md:text-4xl font-bold text-blue-950 dark:text-white"
              style="font-family: 'Oswald', sans-serif"
            >
              Informasi & Persyaratan
            </h2>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
              Pastikan Anda membaca ketentuan berikut sebelum mengisi formulir.
            </p>
          </div>

          <!-- Menampilkan Konten yang dibuat via Rich Text Editor dari Admin -->
          <div
            v-if="!isLoading && ppdbInfo && (ppdbInfo.content || ppdbInfo.description)"
            class="bg-blue-50/30 dark:bg-slate-700/30 p-6 md:p-8 rounded-xl border border-gray-200 dark:border-slate-700 mb-10 shadow-sm max-w-4xl mx-auto prose dark:prose-invert max-w-none"
            v-html="ppdbInfo.content || ppdbInfo.description"
          ></div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
            <!-- Syarat Utama -->
            <div
              class="bg-blue-50/20 dark:bg-slate-700/30 p-6 md:p-8 rounded-xl border border-gray-300 dark:border-slate-700 shadow-lg"
            >
              <h3
                class="text-xl font-bold text-blue-950 dark:text-white mb-5 flex items-center"
              >
                <PhListChecks class="w-6 h-6 mr-2 text-blue-950 dark:text-white" />
                Syarat Pendaftaran
              </h3>
              <ul class="space-y-4">
                <li class="flex items-start">
                  <PhCheckCircle class="w-5 h-5 text-green-500 mr-3 shrink-0 mt-0.5" />
                  <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"
                    >Lulusan SMP/MTs sederajat tahun 2024, 2025, atau 2026.</span
                  >
                </li>
                <li class="flex items-start">
                  <PhCheckCircle class="w-5 h-5 text-green-500 mr-3 shrink-0 mt-0.5" />
                  <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"
                    >Memiliki Nomor Induk Siswa Nasional (NISN) yang valid.</span
                  >
                </li>
                <li class="flex items-start">
                  <PhCheckCircle class="w-5 h-5 text-green-500 mr-3 shrink-0 mt-0.5" />
                  <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"
                    >Menyiapkan berkas digital (Scan KK, Akta Kelahiran, dan
                    Ijazah/SKL).</span
                  >
                </li>
                <li class="flex items-start">
                  <PhCheckCircle class="w-5 h-5 text-green-500 mr-3 shrink-0 mt-0.5" />
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
                class="text-xl font-bold text-blue-950 dark:text-white mb-5 flex items-center"
              >
                <PhGitMerge class="w-6 h-6 mr-2 text-blue-950 dark:text-white" />
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
                    <h4 class="font-bold text-blue-950 dark:text-white text-sm">
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
                    <h4 class="font-bold text-blue-950 dark:text-white text-sm">
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
                    <h4 class="font-bold text-blue-950 dark:text-white text-sm">
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
        <div class="container mx-auto max-w-full px-0 lg:px-16 relative z-10">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
            <!-- Left: Teks Deskripsi -->
            <div class="lg:col-span-5 text-center lg:text-left">
              <h2
                class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight"
                style="font-family: 'Oswald', sans-serif"
              >
                Jalur Pendaftaran PPDB
              </h2>
              <p
                class="text-blue-100 dark:text-gray-300 text-base md:text-lg leading-relaxed"
              >
                Sistem Penerimaan Peserta Didik Baru terbagi menjadi 4 jalur utama.
                Pastikan Anda memilih jalur yang paling sesuai dengan kondisi dan
                kualifikasi untuk memperbesar peluang diterima.
              </p>

              <!-- Decorative Element on Desktop -->
              <div
                class="hidden lg:flex items-center gap-3 text-yellow-400 font-semibold mt-8"
              >
                <div
                  class="w-10 h-10 rounded-full border-2 border-yellow-400 flex items-center justify-center animate-bounce"
                >
                  <PhArrowDown class="w-5 h-5" />
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
                            <PhMapPin class="w-6 h-6" />
                          </div>
                          <h3
                            class="text-xl font-bold text-blue-950 dark:text-white mb-3"
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
                            <PhMedal class="w-6 h-6" />
                          </div>
                          <h3
                            class="text-xl font-bold text-blue-950 dark:text-white mb-3"
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
                            <PhHandshake class="w-6 h-6" />
                          </div>
                          <h3
                            class="text-xl font-bold text-blue-950 dark:text-white mb-3"
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
                            <PhBriefcase class="w-6 h-6" />
                          </div>
                          <h3
                            class="text-xl font-bold text-blue-950 dark:text-white mb-3"
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
          <PhCheckCircle class="w-6 h-6" />
        </div>
        <div class="ml-3 text-sm font-normal">
          <span class="mb-1 text-sm font-bold text-blue-950 dark:text-white block"
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
          <PhX class="w-4 h-4" />
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
