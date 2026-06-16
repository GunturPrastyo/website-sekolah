<template>
  <footer
    class="bg-slate-800 text-slate-300 pt-16 pb-8 border-t border-slate-900 px-6 md:px-10"
  >
    <div class="container mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 md:gap-12 mb-12">
        <!-- Col 1: Brand & About -->
        <div class="lg:col-span-3">
          <div v-if="isLoadingSettings" class="animate-pulse">
            <div class="flex items-center gap-3 mb-6">
              <div class="h-10 w-10 bg-slate-700 rounded-full"></div>
              <div class="h-6 w-32 bg-slate-700 rounded"></div>
            </div>
            <div class="space-y-2 mb-6">
              <div class="h-4 bg-slate-700 rounded w-full"></div>
              <div class="h-4 bg-slate-700 rounded w-5/6"></div>
              <div class="h-4 bg-slate-700 rounded w-4/6"></div>
            </div>
            <div class="flex flex-wrap gap-3">
              <div
                v-for="i in 4"
                :key="i"
                class="w-10 h-10 rounded-full bg-slate-700"
              ></div>
            </div>
          </div>
          <template v-else>
            <a href="#" class="inline-flex items-center gap-2 sm:gap-3 mb-6">
              <img
                v-if="settings.logo"
                :src="settings.logo"
                alt="Logo Sekolah"
                class="h-10 w-auto object-contain"
              />
              <span
                class="text-lg sm:text-xl font-bold text-white tracking-wide"
                style="font-family: 'Montserrat', sans-serif"
                >{{ settings.namaSekolah }}</span
              >
            </a>
            <p class="text-sm text-slate-400 leading-relaxed mb-6">
              {{ settings.deskripsi }}
            </p>
            <div class="flex flex-wrap gap-3">
              <a
                v-if="settings.facebook"
                :href="settings.facebook"
                target="_blank"
                class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors"
                ><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                  /></svg
              ></a>
              <a
                v-if="settings.x"
                :href="settings.x"
                target="_blank"
                class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-black hover:text-white transition-colors"
                ><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                  /></svg
              ></a>
              <a
                v-if="settings.instagram"
                :href="settings.instagram"
                target="_blank"
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
                v-if="settings.tiktok"
                :href="settings.tiktok"
                target="_blank"
                class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-black hover:text-white transition-colors"
                ><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"
                  /></svg
              ></a>
              <a
                v-if="settings.youtube"
                :href="settings.youtube"
                target="_blank"
                class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors"
                ><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"
                  /></svg
              ></a>
              <a
                v-if="settings.linkedin"
                :href="settings.linkedin"
                target="_blank"
                class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-blue-700 hover:text-white transition-colors"
                ><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                  /></svg
              ></a>
            </div>
          </template>

          <!-- Statistik Pengunjung -->
          <div class="mt-8 pt-6 border-t border-slate-700/50" ref="footerRef">
            <p
              class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-3"
            >
              Statistik Pengunjung
            </p>
            <div
              v-if="isLoadingStats"
              class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-y-4 gap-x-2 animate-pulse"
            >
              <div v-for="i in 4" :key="'skel-stat-' + i" class="flex flex-col">
                <div class="h-6 w-12 bg-slate-700 rounded mb-1"></div>
                <div class="h-3 w-16 bg-slate-700 rounded"></div>
              </div>
            </div>
            <div
              v-else
              class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-y-4 gap-x-2"
            >
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
                to="/sejarah"
                class="hover:text-white transition-colors flex items-center"
                ><PhCaretRight class="w-4 h-4 mr-2" /> Profil Sekolah</router-link
              >
            </li>
            <li>
              <router-link
                to="/visi-misi"
                class="hover:text-white transition-colors flex items-center"
                ><PhCaretRight class="w-4 h-4 mr-2" /> Visi & Misi</router-link
              >
            </li>
            <li>
              <router-link
                to="/guru-staf"
                class="hover:text-white transition-colors flex items-center"
                ><PhCaretRight class="w-4 h-4 mr-2" /> Data Guru & Staf</router-link
              >
            </li>
            <li>
              <router-link
                to="/prestasi"
                class="hover:text-white transition-colors flex items-center"
                ><PhCaretRight class="w-4 h-4 mr-2" /> Prestasi Siswa</router-link
              >
            </li>
            <li>
              <router-link
                to="/galeri"
                class="hover:text-white transition-colors flex items-center"
                ><PhCaretRight class="w-4 h-4 mr-2" /> Galeri Kegiatan</router-link
              >
            </li>
          </ul>
        </div>

        <!-- Col 3: Contact Info -->
        <div class="lg:col-span-3">
          <h4 class="text-white font-bold mb-6 text-lg">Hubungi Kami</h4>
          <div v-if="isLoadingSettings" class="space-y-4 animate-pulse">
            <div class="flex items-start">
              <div class="w-5 h-5 bg-slate-700 rounded-full mr-3"></div>
              <div class="flex-1 space-y-2">
                <div class="h-4 w-full bg-slate-700 rounded"></div>
                <div class="h-4 w-3/4 bg-slate-700 rounded"></div>
              </div>
            </div>
            <div class="flex items-center">
              <div class="w-5 h-5 bg-slate-700 rounded-full mr-3"></div>
              <div class="h-4 w-3/4 bg-slate-700 rounded"></div>
            </div>
            <div class="flex items-center">
              <div class="w-5 h-5 bg-slate-700 rounded-full mr-3"></div>
              <div class="h-4 w-2/3 bg-slate-700 rounded"></div>
            </div>
          </div>
          <ul v-else class="space-y-4 text-sm">
            <li class="flex items-start" v-if="settings.alamat">
              <PhMapPin class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" />
              <span class="text-slate-400">{{ settings.alamat }}</span>
            </li>
            <li class="flex items-center" v-if="settings.telepon">
              <PhPhone class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" />
              <span class="text-slate-400">{{ settings.telepon }}</span>
            </li>
            <li class="flex items-center" v-if="settings.email">
              <PhEnvelope class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" />
              <span class="text-slate-400">{{ settings.email }}</span>
            </li>
          </ul>
        </div>

        <!-- Col 4: Maps -->
        <div class="lg:col-span-4">
          <h4 class="text-white font-bold mb-6 text-lg">Lokasi Kami</h4>
          <div
            v-if="isLoadingSettings"
            class="h-64 rounded-lg overflow-hidden bg-slate-700/50 border border-slate-700/50 animate-pulse"
          ></div>
          <div
            v-else-if="settings.embedMap"
            class="h-64 rounded-lg overflow-hidden bg-slate-800/50 border border-slate-700/50"
          >
            <iframe
              :src="settings.embedMap"
              width="100%"
              height="100%"
              style="border: 0"
              allowfullscreen=""
              loading="lazy"
              class="opacity-80 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"
            ></iframe>
          </div>
          <div
            v-else
            class="h-64 rounded-lg overflow-hidden bg-slate-800/50 border border-slate-700/50 flex flex-col items-center justify-center text-slate-500"
          >
            <PhMapPin class="w-8 h-8 mb-2 opacity-50" />
            <span class="text-sm">Peta belum ditambahkan</span>
          </div>
        </div>
      </div>

      <!-- Copyright -->
      <div
        class="pt-8 border-t border-slate-900 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500"
      >
        <div
          v-if="isLoadingSettings"
          class="h-4 w-64 bg-slate-700 rounded animate-pulse"
        ></div>
        <p v-else>
          &copy; {{ new Date().getFullYear() }} {{ settings.namaSekolah }}. Hak Cipta
          Dilindungi.
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { PhCaretRight, PhMapPin, PhPhone, PhEnvelope } from "@phosphor-icons/vue";
import api from "@/api/index.js";

const footerRef = ref(null);

const isLoadingSettings = ref(true);
const isLoadingStats = ref(true);

const settings = ref({
  namaSekolah: "",
  deskripsi: "",
  alamat: "",
  telepon: "",
  email: "",
  facebook: "",
  x: "",
  instagram: "",
  youtube: "",
  tiktok: "",
  linkedin: "",
  embedMap: "",
  logo: "",
});

const stats = ref({
  hari: 0,
  bulan: 0,
  tahun: 0,
  total: 0,
});

const targetStats = ref({
  hari: 0,
  bulan: 0,
  tahun: 0,
  total: 0,
});

const isIntersecting = ref(false);
const hasAnimated = ref(false);

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

const triggerAnimation = () => {
  if (isIntersecting.value && !hasAnimated.value && targetStats.value.total > 0) {
    hasAnimated.value = true;
    animateValue("hari", targetStats.value.hari);
    animateValue("bulan", targetStats.value.bulan);
    animateValue("tahun", targetStats.value.tahun);
    animateValue("total", targetStats.value.total);
  }
};

const formatNumber = (num) => {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

const fetchSettings = async () => {
  isLoadingSettings.value = true;
  try {
    const response = await api.get("/api/settings");
    if (response.data && response.data.data) {
      settings.value = { ...settings.value, ...response.data.data };
    }
  } catch (error) {
    console.error("Gagal mengambil pengaturan footer:", error);
  } finally {
    isLoadingSettings.value = false;
  }
};

const fetchVisitorStats = async () => {
  isLoadingStats.value = true;
  try {
    const response = await api.get("/api/visitor-stats");
    if (response.data && response.data.data) {
      targetStats.value = response.data.data;
      triggerAnimation();
    }
  } catch (error) {
    console.error("Gagal mengambil statistik pengunjung:", error);
  } finally {
    isLoadingStats.value = false;
  }
};

const handleStorageChange = (e) => {
  if (e.key === "settings_updated_at") {
    fetchSettings();
  }
};

onMounted(() => {
  fetchSettings();
  fetchVisitorStats();
  window.addEventListener("settings-updated", fetchSettings);
  window.addEventListener("storage", handleStorageChange);

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          isIntersecting.value = true;
          triggerAnimation();
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

onBeforeUnmount(() => {
  window.removeEventListener("settings-updated", fetchSettings);
  window.removeEventListener("storage", handleStorageChange);
});
</script>
