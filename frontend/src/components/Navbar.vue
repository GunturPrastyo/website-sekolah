<template>
  <nav
    id="navbar"
    class="fixed top-0 left-0 z-50 w-full px-6 py-4 transition-all duration-300 ease-in-out"
    :class="{
      'bg-white/95 shadow-md backdrop-blur-md dark:bg-slate-900/95 dark:border-b dark:border-slate-800':
        isNavbarScrolled || isMobileMenuOpen,
    }"
    ref="navRef"
  >
    <div class="container flex items-center justify-between max-w-6xl mx-auto">
      <router-link
        to="/"
        class="text-2xl font-bold"
        :class="{
          'text-gray-800 dark:text-white': isNavbarScrolled || isMobileMenuOpen,
          'text-white': !isNavbarScrolled && !isMobileMenuOpen,
        }"
        >SMAN 1 Nogosari</router-link
      >

      <!-- Desktop Menu -->
      <div class="hidden items-center space-x-2 lg:flex">
        <router-link
          to="/"
          class="rounded-md px-3 py-2"
          :class="{
            'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400':
              isNavbarScrolled || isMobileMenuOpen,
            'text-white hover:bg-white/10': !isNavbarScrolled && !isMobileMenuOpen,
          }"
          >Beranda</router-link
        >

        <!-- Profil Dropdown -->
        <div class="group relative">
          <button
            @click.stop="toggleDropdown('profil')"
            class="flex items-center rounded-md px-3 py-2"
            :class="{
              'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400':
                isNavbarScrolled || isMobileMenuOpen,
              'text-white hover:bg-white/10': !isNavbarScrolled && !isMobileMenuOpen,
            }"
          >
            <span>Profil</span>
            <span
              class="ml-1 flex items-center transition-transform duration-300"
              :class="{ 'rotate-180': activeDropdown === 'profil' }"
            >
              <i data-lucide="chevron-down" class="h-4 w-4"></i>
            </span>
          </button>
          <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
          >
            <div
              v-show="activeDropdown === 'profil'"
              class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white dark:bg-slate-800 py-2 text-gray-800 dark:text-gray-200 shadow-lg"
            >
              <router-link
                to="/sejarah"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Sejarah Sekolah</router-link
              >
              <router-link
                to="/visi-misi"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Visi & Misi</router-link
              >
              <router-link
                to="/guru-staf"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Guru & Staf</router-link
              >
              <router-link
                to="/fasilitas"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Fasilitas</router-link
              >
            </div>
          </transition>
        </div>

        <!-- Akademik Dropdown -->
        <div class="group relative">
          <button
            @click.stop="toggleDropdown('akademik')"
            class="flex items-center rounded-md px-3 py-2"
            :class="{
              'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400':
                isNavbarScrolled || isMobileMenuOpen,
              'text-white hover:bg-white/10': !isNavbarScrolled && !isMobileMenuOpen,
            }"
          >
            <span>Akademik</span>
            <span
              class="ml-1 flex items-center transition-transform duration-300"
              :class="{ 'rotate-180': activeDropdown === 'akademik' }"
            >
              <i data-lucide="chevron-down" class="h-4 w-4"></i>
            </span>
          </button>
          <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
          >
            <div
              v-show="activeDropdown === 'akademik'"
              class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white dark:bg-slate-800 py-2 text-gray-800 dark:text-gray-200 shadow-lg"
            >
              <router-link
                to="/kurikulum"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Kurikulum</router-link
              >
              <router-link
                to="/program-jurusan"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Program Jurusan</router-link
              >
              <router-link
                to="/ekstrakurikuler"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Ekstrakurikuler</router-link
              >
              <router-link
                to="/prestasi"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Prestasi</router-link
              >
            </div>
          </transition>
        </div>

        <!-- Informasi Dropdown -->
        <div class="group relative">
          <button
            @click.stop="toggleDropdown('informasi')"
            class="flex items-center rounded-md px-3 py-2"
            :class="{
              'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400':
                isNavbarScrolled || isMobileMenuOpen,
              'text-white hover:bg-white/10': !isNavbarScrolled && !isMobileMenuOpen,
            }"
          >
            <span>Informasi</span>
            <span
              class="ml-1 flex items-center transition-transform duration-300"
              :class="{ 'rotate-180': activeDropdown === 'informasi' }"
            >
              <i data-lucide="chevron-down" class="h-4 w-4"></i>
            </span>
          </button>
          <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
          >
            <div
              v-show="activeDropdown === 'informasi'"
              class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white dark:bg-slate-800 py-2 text-gray-800 dark:text-gray-200 shadow-lg"
            >
              <router-link
                to="/berita"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Berita</router-link
              >
              <router-link
                to="/galeri"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Galeri</router-link
              >
            </div>
          </transition>
        </div>

        <router-link
          to="/kontak"
          class="rounded-md px-3 py-2"
          :class="{
            'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400':
              isNavbarScrolled || isMobileMenuOpen,
            'text-white hover:bg-white/10': !isNavbarScrolled && !isMobileMenuOpen,
          }"
          >Kontak</router-link
        >
      </div>

      <div class="hidden items-center lg:flex">
        <router-link
          to="/pendaftaran"
          class="rounded-full bg-blue-600 px-5 py-2 font-semibold text-white transition-colors hover:bg-blue-700"
          >Pendaftaran</router-link
        >
        <!-- Dark Mode Toggle -->
        <button
          @click="toggleDarkMode"
          class="ml-2 p-2 rounded-full focus:outline-none transition-colors"
          :class="[
            isNavbarScrolled || isMobileMenuOpen
              ? 'text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-slate-700'
              : 'text-white hover:bg-white/10',
          ]"
        >
          <span v-show="!isDarkMode" class="flex items-center justify-center"
            ><i data-lucide="moon" class="w-5 h-5"></i
          ></span>
          <span v-show="isDarkMode" class="flex items-center justify-center"
            ><i data-lucide="sun" class="w-5 h-5"></i
          ></span>
        </button>
      </div>

      <!-- Mobile Menu Button -->
      <div class="flex items-center gap-2 lg:hidden">
        <!-- Dark Mode Toggle -->
        <button
          @click="toggleDarkMode"
          class="p-2 rounded-full focus:outline-none transition-colors"
          :class="[
            isNavbarScrolled || isMobileMenuOpen
              ? 'text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-slate-700'
              : 'text-white hover:bg-white/10',
          ]"
        >
          <span v-show="!isDarkMode" class="flex items-center justify-center"
            ><i data-lucide="moon" class="w-5 h-5"></i
          ></span>
          <span v-show="isDarkMode" class="flex items-center justify-center"
            ><i data-lucide="sun" class="w-5 h-5"></i
          ></span>
        </button>
        <button
          @click="toggleMobileMenu"
          :class="[
            isNavbarScrolled || isMobileMenuOpen
              ? 'text-gray-800 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-slate-700'
              : 'text-white hover:bg-white/10',
          ]"
          class="p-2 rounded-md focus:outline-none transition-colors"
        >
          <span v-show="isMobileMenuOpen" class="flex items-center justify-center"
            ><i data-lucide="x" class="h-6 w-6"></i
          ></span>
          <span v-show="!isMobileMenuOpen" class="flex items-center justify-center"
            ><i data-lucide="menu" class="h-6 w-6"></i
          ></span>
        </button>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div
      :class="{
        'max-h-screen opacity-100': isMobileMenuOpen,
        'max-h-0 opacity-0': !isMobileMenuOpen,
      }"
      class="lg:hidden overflow-hidden transition-all duration-500 ease-in-out w-full"
    >
      <div
        class="mt-4 space-y-2 rounded-xl bg-white/95 backdrop-blur-md dark:bg-slate-800/95 border border-gray-100 dark:border-slate-700/50 p-5 text-gray-800 dark:text-gray-200 shadow-2xl mx-1"
      >
        <router-link
          to="/"
          @click="closeMobileMenu"
          class="block rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors"
          >Beranda</router-link
        >

        <!-- Mobile Profil Dropdown -->
        <div>
          <button
            @click="toggleMobileDropdown('profil')"
            class="flex w-full items-center justify-between rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors"
          >
            <span>Profil</span>
            <span
              class="flex items-center transition-transform duration-300"
              :class="{ 'rotate-180': mobileDropdowns.profil }"
            >
              <i data-lucide="chevron-down" class="h-5 w-5"></i>
            </span>
          </button>
          <div
            :class="{
              'max-h-screen': mobileDropdowns.profil,
              'max-h-0': !mobileDropdowns.profil,
            }"
            class="mt-1 overflow-hidden transition-all duration-300 ease-in-out pl-4 pr-2 space-y-1"
          >
            <router-link
              to="/sejarah"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Sejarah Sekolah</router-link
            >
            <router-link
              to="/visi-misi"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Visi & Misi</router-link
            >
            <router-link
              to="/guru-staf"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Guru & Staf</router-link
            >
            <router-link
              to="/fasilitas"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Fasilitas</router-link
            >
          </div>
        </div>

        <!-- Mobile Akademik Dropdown -->
        <div>
          <button
            @click="toggleMobileDropdown('akademik')"
            class="flex w-full items-center justify-between rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors"
          >
            <span>Akademik</span>
            <span
              class="flex items-center transition-transform duration-300"
              :class="{ 'rotate-180': mobileDropdowns.akademik }"
            >
              <i data-lucide="chevron-down" class="h-5 w-5"></i>
            </span>
          </button>
          <div
            :class="{
              'max-h-screen': mobileDropdowns.akademik,
              'max-h-0': !mobileDropdowns.akademik,
            }"
            class="mt-1 overflow-hidden transition-all duration-300 ease-in-out pl-4 pr-2 space-y-1"
          >
            <router-link
              to="/kurikulum"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Kurikulum</router-link
            >
            <router-link
              to="/program-jurusan"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Program Jurusan</router-link
            >
            <router-link
              to="/ekstrakurikuler"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Ekstrakurikuler</router-link
            >
            <router-link
              to="/prestasi"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Prestasi</router-link
            >
          </div>
        </div>

        <!-- Mobile Informasi Dropdown -->
        <div>
          <button
            @click="toggleMobileDropdown('informasi')"
            class="flex w-full items-center justify-between rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors"
          >
            <span>Informasi</span>
            <span
              class="flex items-center transition-transform duration-300"
              :class="{ 'rotate-180': mobileDropdowns.informasi }"
            >
              <i data-lucide="chevron-down" class="h-5 w-5"></i>
            </span>
          </button>
          <div
            :class="{
              'max-h-screen': mobileDropdowns.informasi,
              'max-h-0': !mobileDropdowns.informasi,
            }"
            class="mt-1 overflow-hidden transition-all duration-300 ease-in-out pl-4 pr-2 space-y-1"
          >
            <router-link
              to="/berita"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Berita</router-link
            >
            <router-link
              to="/galeri"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Galeri</router-link
            >
          </div>
        </div>

        <router-link
          to="/kontak"
          @click="closeMobileMenu"
          class="block rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors"
          >Kontak</router-link
        >

        <div class="pt-2 mt-2 border-t border-gray-100 dark:border-slate-700/50">
          <router-link
            to="/pendaftaran"
            @click="closeMobileMenu"
            class="block w-full rounded-xl bg-blue-600 px-4 py-3 text-center font-semibold text-white shadow-md shadow-blue-500/30 transition-colors hover:bg-blue-700 active:scale-[0.98]"
            >Pendaftaran</router-link
          >
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, reactive } from "vue";

const navRef = ref(null);
const isNavbarScrolled = ref(false);
const isMobileMenuOpen = ref(false);
const isDarkMode = ref(false);
const activeDropdown = ref(null);

const mobileDropdowns = reactive({
  profil: false,
  akademik: false,
  informasi: false,
});

const handleScroll = () => {
  isNavbarScrolled.value = window.scrollY > 50;
};

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false;
};

const toggleDropdown = (dropdown) => {
  if (activeDropdown.value === dropdown) {
    activeDropdown.value = null;
  } else {
    activeDropdown.value = dropdown;
  }
};

const closeDropdowns = () => {
  activeDropdown.value = null;
};

const toggleMobileDropdown = (dropdown) => {
  mobileDropdowns[dropdown] = !mobileDropdowns[dropdown];
  // Close other dropdowns
  for (const key in mobileDropdowns) {
    if (key !== dropdown) {
      mobileDropdowns[key] = false;
    }
  }
};

const handleClickOutside = (event) => {
  if (navRef.value && !navRef.value.contains(event.target)) {
    closeDropdowns();
  }
};

const setDarkMode = (dark) => {
  isDarkMode.value = dark;
  if (dark) {
    document.documentElement.classList.add("dark");
    localStorage.setItem("color-theme", "dark");
  } else {
    document.documentElement.classList.remove("dark");
    localStorage.setItem("color-theme", "light");
  }
};

const toggleDarkMode = () => {
  setDarkMode(!isDarkMode.value);
};

onMounted(() => {
  window.addEventListener("scroll", handleScroll);
  document.addEventListener("click", handleClickOutside);
  handleScroll();

  if (
    localStorage.getItem("color-theme") === "dark" ||
    (!("color-theme" in localStorage) &&
      window.matchMedia("(prefers-color-scheme: dark)").matches)
  ) {
    setDarkMode(true);
  } else {
    setDarkMode(false);
  }
});

onBeforeUnmount(() => {
  window.removeEventListener("scroll", handleScroll);
  document.removeEventListener("click", handleClickOutside);
});
</script>
