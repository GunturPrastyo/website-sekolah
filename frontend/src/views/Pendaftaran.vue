<script setup>
import { ref, onMounted, onUpdated, nextTick } from "vue";
import { createIcons } from "lucide";

const currentStep = ref(1);
const totalSteps = 4;
const isSubmitted = ref(false);

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
  pilihanJurusan: ""
});

const nextStep = () => {
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
  // Simulasi proses pengiriman data ke server
  setTimeout(() => {
    isSubmitted.value = true;
    scrollToForm();
  }, 800);
};

const scrollToForm = () => {
  const formEl = document.getElementById("form-area");
  if (formEl) {
    window.scrollTo({ top: formEl.offsetTop - 100, behavior: "smooth" });
  }
};

onMounted(() => {
  nextTick(() => {
    createIcons();
  });
});

onUpdated(() => {
  nextTick(() => {
    createIcons();
  });
});
</script>

<template>
  <div>
    <!-- Hero Banner -->
    <div class="relative pt-32 pb-24 lg:pt-44 lg:pb-32 bg-gradient-to-br from-blue-700 via-blue-800 to-slate-900 overflow-hidden">
      <!-- Background glow -->
      <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500/20 blur-3xl rounded-full"></div>
      <div class="absolute bottom-0 right-0 w-80 h-80 bg-sky-500/20 blur-3xl rounded-full"></div>

      <div class="container relative z-10 mx-auto px-6 text-center">
        <span class="inline-block px-4 py-1.5 mb-5 text-xs md:text-sm font-semibold text-blue-900 bg-yellow-400 rounded-full shadow-sm">
          TAHUN AJARAN 2026/2027
        </span>
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
          Penerimaan Peserta Didik Baru
        </h1>
        <p class="text-blue-100 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
          Bergabunglah bersama SMAN 1 Nogosari. Isi formulir pendaftaran di bawah ini dengan data yang valid dan benar.
        </p>
      </div>
    </div>

    <!-- Main Content Section -->
    <section id="form-area" class="py-12 md:py-20 px-6 bg-gray-50 dark:bg-slate-900 min-h-screen">
      <div class="container mx-auto max-w-4xl">
        
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700 overflow-hidden">
          
          <!-- Sukses State -->
          <div v-if="isSubmitted" class="p-10 md:p-16 text-center">
            <div class="w-24 h-24 bg-green-100 dark:bg-green-900/30 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <i data-lucide="check-circle" class="w-12 h-12"></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-4">Pendaftaran Berhasil!</h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-lg mx-auto mb-8 leading-relaxed">
              Terima kasih <strong>{{ form.namaLengkap || 'Calon Siswa' }}</strong>, data pendaftaran Anda telah kami terima. Silakan cetak bukti pendaftaran dan perhatikan jadwal seleksi selanjutnya.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
              <button class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center">
                <i data-lucide="printer" class="w-5 h-5 mr-2"></i> Cetak Bukti Daftar
              </button>
              <router-link to="/" class="px-6 py-3 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-800 dark:text-gray-200 font-semibold rounded-xl transition-colors flex items-center justify-center">
                Kembali ke Beranda
              </router-link>
            </div>
          </div>

          <!-- Form Multi-step -->
          <div v-else>
            <!-- Progress Bar Indicator -->
            <div class="bg-gray-50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-700 p-6 md:p-8">
              <div class="flex items-center justify-between relative">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-200 dark:bg-slate-700 z-0 rounded-full"></div>
                <!-- Progress Fill -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-blue-600 z-0 rounded-full transition-all duration-500" :style="{ width: `${((currentStep - 1) / (totalSteps - 1)) * 100}%` }"></div>
                
                <!-- Step Dots -->
                <div v-for="step in totalSteps" :key="step" class="relative z-10 flex flex-col items-center group">
                  <div 
                    class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-sm"
                    :class="[
                      currentStep > step ? 'bg-blue-600 text-white' : '',
                      currentStep === step ? 'bg-blue-600 text-white ring-4 ring-blue-100 dark:ring-blue-900/50' : '',
                      currentStep < step ? 'bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-600 text-gray-400' : ''
                    ]"
                  >
                    <i v-if="currentStep > step" data-lucide="check" class="w-5 h-5"></i>
                    <span v-else>{{ step }}</span>
                  </div>
                  <span class="absolute -bottom-6 text-[10px] sm:text-xs font-semibold whitespace-nowrap hidden sm:block" 
                    :class="currentStep >= step ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400'">
                    {{ step === 1 ? 'Data Diri' : step === 2 ? 'Orang Tua' : step === 3 ? 'Sekolah Asal' : 'Pilihan' }}
                  </span>
                </div>
              </div>
            </div>

            <div class="p-6 md:p-10">
              <form @submit.prevent="submitForm">
                
                <!-- STEP 1: DATA DIRI -->
                <div v-show="currentStep === 1" class="space-y-6 animate-fade-in">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white border-l-4 border-blue-600 pl-3 mb-6">Identitas Calon Siswa</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">NISN <span class="text-red-500">*</span></label>
                      <input v-model="form.nisn" type="text" required placeholder="Masukkan 10 digit NISN" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap <span class="text-red-500">*</span></label>
                      <input v-model="form.namaLengkap" type="text" required placeholder="Sesuai Akta Kelahiran" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tempat Lahir</label>
                      <input v-model="form.tempatLahir" type="text" placeholder="Kota/Kabupaten Lahir" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Lahir</label>
                      <input v-model="form.tanggalLahir" type="date" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin</label>
                      <select v-model="form.jenisKelamin" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                      </select>
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Agama</label>
                      <select v-model="form.agama" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
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
                <div v-show="currentStep === 2" class="space-y-6 animate-fade-in">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white border-l-4 border-blue-600 pl-3 mb-6">Data Orang Tua / Wali</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Nama Ayah</label>
                      <input v-model="form.namaAyah" type="text" placeholder="Nama Lengkap Ayah" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Pekerjaan Ayah</label>
                      <input v-model="form.pekerjaanAyah" type="text" placeholder="Pekerjaan Ayah" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Nama Ibu</label>
                      <input v-model="form.namaIbu" type="text" placeholder="Nama Lengkap Ibu" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Pekerjaan Ibu</label>
                      <input v-model="form.pekerjaanIbu" type="text" placeholder="Pekerjaan Ibu" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" />
                    </div>
                    <div class="space-y-2 md:col-span-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">No. Telepon / WhatsApp <span class="text-red-500">*</span></label>
                      <input v-model="form.noTelp" type="tel" required placeholder="Contoh: 08123456789" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" />
                    </div>
                    <div class="space-y-2 md:col-span-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Tempat Tinggal Lengkap</label>
                      <textarea v-model="form.alamat" rows="3" placeholder="Jl. Raya No..., RT/RW, Desa, Kecamatan..." class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"></textarea>
                    </div>
                  </div>
                </div>

                <!-- STEP 3: DATA SEKOLAH ASAL -->
                <div v-show="currentStep === 3" class="space-y-6 animate-fade-in">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white border-l-4 border-blue-600 pl-3 mb-6">Riwayat Pendidikan Sebelumnya</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 md:col-span-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Asal Sekolah (SMP/MTs) <span class="text-red-500">*</span></label>
                      <input v-model="form.asalSekolah" type="text" required placeholder="Nama Sekolah Asal" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" />
                    </div>
                    <div class="space-y-2">
                      <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Lulus</label>
                      <select v-model="form.tahunLulus" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
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
                <div v-show="currentStep === 4" class="space-y-6 animate-fade-in">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white border-l-4 border-blue-600 pl-3 mb-6">Pilihan Jalur & Peminatan</h3>
                  
                  <div class="space-y-8">
                    <!-- Jalur Pendaftaran Radio Cards -->
                    <div class="space-y-3">
                      <label class="text-sm font-bold text-gray-900 dark:text-white">Jalur Pendaftaran <span class="text-red-500">*</span></label>
                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="relative flex cursor-pointer rounded-xl border bg-white dark:bg-slate-800 p-4 shadow-sm focus:outline-none transition-all" :class="form.jalurPendaftaran === 'Zonasi' ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50 dark:bg-slate-700' : 'border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700/50'">
                          <input type="radio" v-model="form.jalurPendaftaran" value="Zonasi" class="sr-only" required>
                          <div class="flex w-full items-center justify-between">
                            <div class="flex items-center">
                              <div class="text-sm">
                                <p class="font-bold text-gray-900 dark:text-white">Zonasi</p>
                                <p class="text-gray-500 dark:text-gray-400 mt-1">Berdasarkan jarak domisili terdekat.</p>
                              </div>
                            </div>
                            <i v-if="form.jalurPendaftaran === 'Zonasi'" data-lucide="check-circle-2" class="w-6 h-6 text-blue-600"></i>
                          </div>
                        </label>
                        
                        <label class="relative flex cursor-pointer rounded-xl border bg-white dark:bg-slate-800 p-4 shadow-sm focus:outline-none transition-all" :class="form.jalurPendaftaran === 'Prestasi' ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50 dark:bg-slate-700' : 'border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700/50'">
                          <input type="radio" v-model="form.jalurPendaftaran" value="Prestasi" class="sr-only">
                          <div class="flex w-full items-center justify-between">
                            <div class="flex items-center">
                              <div class="text-sm">
                                <p class="font-bold text-gray-900 dark:text-white">Prestasi</p>
                                <p class="text-gray-500 dark:text-gray-400 mt-1">Nilai rapor atau sertifikat lomba.</p>
                              </div>
                            </div>
                            <i v-if="form.jalurPendaftaran === 'Prestasi'" data-lucide="check-circle-2" class="w-6 h-6 text-blue-600"></i>
                          </div>
                        </label>
                      </div>
                    </div>

                    <!-- Pilihan Jurusan Radio Cards -->
                    <div class="space-y-3">
                      <label class="text-sm font-bold text-gray-900 dark:text-white">Peminatan Jurusan <span class="text-red-500">*</span></label>
                      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <label class="relative flex cursor-pointer rounded-xl border bg-white dark:bg-slate-800 p-4 shadow-sm focus:outline-none transition-all" :class="form.pilihanJurusan === 'IPA' ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50 dark:bg-slate-700' : 'border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700/50'">
                          <input type="radio" v-model="form.pilihanJurusan" value="IPA" class="sr-only" required>
                          <div class="text-center w-full">
                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 mb-3">
                              <i data-lucide="flask-conical" class="h-5 w-5"></i>
                            </div>
                            <p class="font-bold text-gray-900 dark:text-white">IPA</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ilmu Pengetahuan Alam</p>
                          </div>
                        </label>
                        
                        <label class="relative flex cursor-pointer rounded-xl border bg-white dark:bg-slate-800 p-4 shadow-sm focus:outline-none transition-all" :class="form.pilihanJurusan === 'IPS' ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50 dark:bg-slate-700' : 'border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700/50'">
                          <input type="radio" v-model="form.pilihanJurusan" value="IPS" class="sr-only">
                          <div class="text-center w-full">
                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 dark:bg-sky-900/30 text-sky-600 mb-3">
                              <i data-lucide="globe-2" class="h-5 w-5"></i>
                            </div>
                            <p class="font-bold text-gray-900 dark:text-white">IPS</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ilmu Pengetahuan Sosial</p>
                          </div>
                        </label>
                        
                        <label class="relative flex cursor-pointer rounded-xl border bg-white dark:bg-slate-800 p-4 shadow-sm focus:outline-none transition-all" :class="form.pilihanJurusan === 'Bahasa' ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50 dark:bg-slate-700' : 'border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700/50'">
                          <input type="radio" v-model="form.pilihanJurusan" value="Bahasa" class="sr-only">
                          <div class="text-center w-full">
                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 mb-3">
                              <i data-lucide="languages" class="h-5 w-5"></i>
                            </div>
                            <p class="font-bold text-gray-900 dark:text-white">Bahasa</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sastra & Budaya</p>
                          </div>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-10 pt-6 border-t border-gray-100 dark:border-slate-700 flex justify-between">
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
                    class="px-8 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-md transition-colors flex items-center"
                  >
                    Kirim Pendaftaran <i data-lucide="send" class="w-4 h-4 ml-2"></i>
                  </button>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
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
</style>