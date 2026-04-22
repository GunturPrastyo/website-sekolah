<template>
  <footer
    class="bg-slate-800 text-slate-300 pt-16 pb-8 border-t border-slate-900 px-6 md:px-10"
  >
    <div class="container mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 md:gap-12 mb-12">
        <!-- Col 1: Brand & About -->
        <div class="lg:col-span-3">
          <a href="#" class="text-2xl font-bold text-white mb-6 inline-block"
            >SMAN 1 Nogosari</a
          >
          <p class="text-sm text-slate-400 leading-relaxed mb-6">
            Mencetak Generasi Unggul, Berkarakter, dan Berwawasan Global melalui
            pendidikan berkualitas dan fasilitas modern.
          </p>
          <div class="flex flex-wrap gap-3">
            <a
              href="#"
              class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors"
              ><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                /></svg
            ></a>
            <a
              href="#"
              class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-black hover:text-white transition-colors"
              ><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                /></svg
            ></a>
            <a
              href="#"
              class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-pink-600 hover:text-white transition-colors"
              ><svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                viewBox="0 0 24 24"
              >
                <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" /></svg
            ></a>
            <a
              href="#"
              class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors"
              ><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"
                /></svg
            ></a>
            <a
              href="#"
              class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-blue-700 hover:text-white transition-colors"
              ><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                /></svg
            ></a>
          </div>

          <!-- Statistik Pengunjung -->
          <div class="mt-8 pt-6 border-t border-slate-700/50" ref="footerRef">
            <p
              class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-3"
            >
              Statistik Pengunjung
            </p>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-y-4 gap-x-2">
              <div class="flex flex-col overflow-hidden">
                <span
                  class="text-lg xl:text-xl font-light text-slate-200 tracking-tight"
                  >{{ formatNumber(stats.hari) }}</span
                >
                <span
                  class="text-[9px] xl:text-[10px] text-slate-500 mt-0.5 tracking-wider truncate"
                  >HARI INI</span
                >
              </div>
              <div class="flex flex-col overflow-hidden">
                <span
                  class="text-lg xl:text-xl font-light text-slate-200 tracking-tight"
                  >{{ formatNumber(stats.bulan) }}</span
                >
                <span
                  class="text-[9px] xl:text-[10px] text-slate-500 mt-0.5 tracking-wider truncate"
                  >BULAN INI</span
                >
              </div>
              <div class="flex flex-col overflow-hidden">
                <span
                  class="text-lg xl:text-xl font-light text-slate-200 tracking-tight"
                  >{{ formatNumber(stats.tahun) }}</span
                >
                <span
                  class="text-[9px] xl:text-[10px] text-slate-500 mt-0.5 tracking-wider truncate"
                  >TAHUN INI</span
                >
              </div>
              <div class="flex flex-col overflow-hidden">
                <span
                  class="text-lg xl:text-xl font-light text-slate-200 tracking-tight"
                  >{{ formatNumber(stats.total) }}</span
                >
                <span
                  class="text-[9px] xl:text-[10px] text-slate-500 mt-0.5 tracking-wider truncate"
                  >TOTAL</span
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Col 2: Quick Links -->
        <div class="lg:col-span-2">
          <h4 class="text-white font-bold mb-6 text-lg">Tautan Cepat</h4>
          <ul class="space-y-3 text-sm">
            <li>
              <router-link
                to="/profil"
                class="hover:text-white transition-colors flex items-center"
                ><i data-lucide="chevron-right" class="w-4 h-4 mr-2"></i> Profil
                Sekolah</router-link
              >
            </li>
            <li>
              <router-link
                to="/visi-misi"
                class="hover:text-white transition-colors flex items-center"
                ><i data-lucide="chevron-right" class="w-4 h-4 mr-2"></i> Visi &
                Misi</router-link
              >
            </li>
            <li>
              <router-link
                to="/guru-staf"
                class="hover:text-white transition-colors flex items-center"
                ><i data-lucide="chevron-right" class="w-4 h-4 mr-2"></i> Data Guru &
                Staf</router-link
              >
            </li>
            <li>
              <router-link
                to="/prestasi"
                class="hover:text-white transition-colors flex items-center"
                ><i data-lucide="chevron-right" class="w-4 h-4 mr-2"></i> Prestasi
                Siswa</router-link
              >
            </li>
            <li>
              <router-link
                to="/galeri"
                class="hover:text-white transition-colors flex items-center"
                ><i data-lucide="chevron-right" class="w-4 h-4 mr-2"></i> Galeri
                Kegiatan</router-link
              >
            </li>
          </ul>
        </div>

        <!-- Col 3: Contact Info -->
        <div class="lg:col-span-3">
          <h4 class="text-white font-bold mb-6 text-lg">Hubungi Kami</h4>
          <ul class="space-y-4 text-sm">
            <li class="flex items-start">
              <i
                data-lucide="map-pin"
                class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0"
              ></i>
              <span class="text-slate-400"
                >Jl. Raya Nogosari No. 123, Kec. Nogosari, Kab. Boyolali, Jawa Tengah
                57378</span
              >
            </li>
            <li class="flex items-center">
              <i data-lucide="phone" class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0"></i>
              <span class="text-slate-400">(0276) 1234567</span>
            </li>
            <li class="flex items-center">
              <i data-lucide="mail" class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0"></i>
              <span class="text-slate-400">info@sman1nogosari.sch.id</span>
            </li>
          </ul>
        </div>

        <!-- Col 4: Maps -->
        <div class="lg:col-span-4">
          <h4 class="text-white font-bold mb-6 text-lg">Lokasi Kami</h4>
          <div
            class="h-64 rounded-xl overflow-hidden bg-slate-800/50 border border-slate-700/50"
          >
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.5103639967265!2d110.79379!3d-7.4682055!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMjgnMDUuNSJTIDExMMKwNDcnMzcuNiJF!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid"
              width="100%"
              height="100%"
              style="border: 0"
              allowfullscreen=""
              loading="lazy"
              class="opacity-80 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"
            ></iframe>
          </div>
        </div>
      </div>

      <!-- Copyright -->
      <div
        class="pt-8 border-t border-slate-900 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500"
      >
        <p>&copy; 2026 SMAN 1 Nogosari. Hak Cipta Dilindungi.</p>
        <div class="flex gap-4">
          <router-link
            to="/kebijakan-privasi"
            class="hover:text-slate-300 transition-colors"
            >Kebijakan Privasi</router-link
          >
          <router-link
            to="/syarat-ketentuan"
            class="hover:text-slate-300 transition-colors"
            >Syarat & Ketentuan</router-link
          >
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref, onMounted } from "vue";

const footerRef = ref(null);

const stats = ref({
  hari: 0,
  bulan: 0,
  tahun: 0,
  total: 0,
});

const targetStats = {
  hari: 124,
  bulan: 1250,
  tahun: 14320,
  total: 258940,
};

const animateValue = (key, target, duration = 2000) => {
  if (target === 0) return;
  let startTimestamp = null;
  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    const easeProgress = 1 - Math.pow(1 - progress, 4); // Efek Ease-Out agar melambat di akhir
    stats.value[key] = Math.floor(easeProgress * target);
    if (progress < 1) {
      window.requestAnimationFrame(step);
    } else {
      stats.value[key] = target;
    }
  };
  window.requestAnimationFrame(step);
};

const formatNumber = (num) => {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

onMounted(() => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateValue("hari", targetStats.hari);
          animateValue("bulan", targetStats.bulan);
          animateValue("tahun", targetStats.tahun);
          animateValue("total", targetStats.total);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 }
  );

  if (footerRef.value) {
    observer.observe(footerRef.value);
  }
});
</script>
