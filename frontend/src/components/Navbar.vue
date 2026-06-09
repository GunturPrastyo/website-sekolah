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
        class="flex items-center gap-2 sm:gap-3 text-lg sm:text-xl font-bold transition-colors tracking-wide"
        :class="{
          'text-gray-800 dark:text-white': isNavbarScrolled || isMobileMenuOpen,
          'text-white': !isNavbarScrolled && !isMobileMenuOpen,
        }"
        style="font-family: 'Montserrat', sans-serif"
      >
        <img
          v-if="settings.logo"
          :src="settings.logo"
          alt="Logo Sekolah"
          class="h-8 md:h-10 w-auto"
        />
        <span>{{ settings.namaSekolah }}</span>
      </router-link>

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
        <div
          class="group relative"
          @mouseenter="activeDropdown = 'profil'"
          @mouseleave="activeDropdown = null"
        >
          <button
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
              <PhCaretDown class="h-4 w-4" />
            </span>
          </button>
          <transition
            enter-active-class="transition-all ease-out duration-300"
            enter-from-class="transform opacity-0 -translate-y-4"
            enter-to-class="transform opacity-100 translate-y-0"
            leave-active-class="transition-all ease-in duration-200"
            leave-from-class="transform opacity-100 translate-y-0"
            leave-to-class="transform opacity-0 -translate-y-4"
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
              <router-link
                to="/alumni"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Data Alumni</router-link
              >
            </div>
          </transition>
        </div>

        <!-- Akademik Dropdown -->
        <div
          class="group relative"
          @mouseenter="activeDropdown = 'akademik'"
          @mouseleave="activeDropdown = null"
        >
          <button
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
              <PhCaretDown class="h-4 w-4" />
            </span>
          </button>
          <transition
            enter-active-class="transition-all ease-out duration-300"
            enter-from-class="transform opacity-0 -translate-y-4"
            enter-to-class="transform opacity-100 translate-y-0"
            leave-active-class="transition-all ease-in duration-200"
            leave-from-class="transform opacity-100 translate-y-0"
            leave-to-class="transform opacity-0 -translate-y-4"
          >
            <div
              v-show="activeDropdown === 'akademik'"
              class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white dark:bg-slate-800 py-2 text-gray-800 dark:text-gray-200 shadow-lg"
            >
              <router-link
                to="/program-jurusan"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Program Jurusan</router-link
              >
              <router-link
                to="/kurikulum"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Kurikulum</router-link
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

        <!-- Layanan / Portal Dropdown (Dinonaktifkan Sementara) -->
        <!-- <div class="group relative" @mouseenter="activeDropdown = 'layanan'" @mouseleave="activeDropdown = null">
          <button
            class="flex items-center rounded-md px-3 py-2"
            :class="{
              'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400':
                isNavbarScrolled || isMobileMenuOpen,
              'text-white hover:bg-white/10': !isNavbarScrolled && !isMobileMenuOpen,
            }"
          >
            <span>Layanan</span>
            <span
              class="ml-1 flex items-center transition-transform duration-300"
              :class="{ 'rotate-180': activeDropdown === 'layanan' }"
            >
              <PhCaretDown class="h-4 w-4" />
            </span>
          </button>
          <transition
            enter-active-class="transition-all ease-out duration-300"
            enter-from-class="transform opacity-0 -translate-y-4"
            enter-to-class="transform opacity-100 translate-y-0"
            leave-active-class="transition-all ease-in duration-200"
            leave-from-class="transform opacity-100 translate-y-0"
            leave-to-class="transform opacity-0 -translate-y-4"
          >
            <div
              v-show="activeDropdown === 'layanan'"
              class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white dark:bg-slate-800 py-2 text-gray-800 dark:text-gray-200 shadow-lg"
            >
              <a
                href="#"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >E-Learning / CBT</a
              >
              <a
                href="#"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >E-Rapor Siswa</a
              >
              <a
                href="#"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >E-Perpustakaan</a
              >
            </div>
          </transition>
        </div> -->

        <!-- Informasi Dropdown -->
        <div
          class="group relative"
          @mouseenter="activeDropdown = 'informasi'"
          @mouseleave="activeDropdown = null"
        >
          <button
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
              <PhCaretDown class="h-4 w-4" />
            </span>
          </button>
          <transition
            enter-active-class="transition-all ease-out duration-300"
            enter-from-class="transform opacity-0 -translate-y-4"
            enter-to-class="transform opacity-100 translate-y-0"
            leave-active-class="transition-all ease-in duration-200"
            leave-from-class="transform opacity-100 translate-y-0"
            leave-to-class="transform opacity-0 -translate-y-4"
          >
            <div
              v-show="activeDropdown === 'informasi'"
              class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white dark:bg-slate-800 py-2 text-gray-800 dark:text-gray-200 shadow-lg"
            >
              <router-link
                to="/pendaftaran"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Info PPDB</router-link
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
              <router-link
                to="/unduhan"
                @click="closeDropdowns"
                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700"
                >Unduhan Publik</router-link
              >
            </div>
          </transition>
        </div>
      </div>

      <div class="hidden items-center lg:flex"></div>

      <!-- Mobile Menu Button -->
      <div class="flex items-center gap-2 lg:hidden">
        <button
          @click="toggleMobileMenu"
          :class="[
            isNavbarScrolled || isMobileMenuOpen
              ? 'text-gray-800 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-slate-700'
              : 'text-white hover:bg-white/10',
          ]"
          class="w-10 h-10 flex items-center justify-center rounded-md focus:outline-none transition-all active:scale-90 relative overflow-hidden"
        >
          <span
            class="absolute transition-all duration-500 ease-in-out flex items-center justify-center"
            :class="
              isMobileMenuOpen
                ? 'rotate-90 scale-0 opacity-0'
                : 'rotate-0 scale-100 opacity-100'
            "
          >
            <PhList class="h-6 w-6" />
          </span>
          <span
            class="absolute transition-all duration-500 ease-in-out flex items-center justify-center"
            :class="
              isMobileMenuOpen
                ? 'rotate-0 scale-100 opacity-100'
                : '-rotate-90 scale-0 opacity-0'
            "
          >
            <PhX class="h-6 w-6" />
          </span>
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
        class="mt-4 pt-4 pb-2 space-y-2 text-gray-800 dark:text-gray-200 border-t border-gray-100 dark:border-slate-700/50"
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
              <PhCaretDown class="h-5 w-5" />
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
            <router-link
              to="/alumni"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Data Alumni</router-link
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
              <PhCaretDown class="h-5 w-5" />
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
              to="/program-jurusan"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Program Jurusan</router-link
            >
            <router-link
              to="/kurikulum"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Kurikulum</router-link
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

        <!-- Mobile Layanan Dropdown (Dinonaktifkan Sementara) -->
        <!-- <div>
          <button
            @click="toggleMobileDropdown('layanan')"
            class="flex w-full items-center justify-between rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors"
          >
            <span>Layanan</span>
            <span
              class="flex items-center transition-transform duration-300"
              :class="{ 'rotate-180': mobileDropdowns.layanan }"
            >
              <PhCaretDown class="h-5 w-5" />
            </span>
          </button>
          <div
            :class="{
              'max-h-screen': mobileDropdowns.layanan,
              'max-h-0': !mobileDropdowns.layanan,
            }"
            class="mt-1 overflow-hidden transition-all duration-300 ease-in-out pl-4 pr-2 space-y-1"
          >
            <a
              href="#"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >E-Learning / CBT</a
            >
            <a
              href="#"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >E-Rapor Siswa</a
            >
            <a
              href="#"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >E-Perpustakaan</a
            >
          </div>
        </div> -->

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
              <PhCaretDown class="h-5 w-5" />
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
              to="/pendaftaran"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Info PPDB</router-link
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
            <router-link
              to="/unduhan"
              @click="closeMobileMenu"
              class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors"
              >Unduhan Publik</router-link
            >
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Floating Action Buttons -->
  <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-3">
    <!-- Dark Mode Toggle -->
    <button
      @click="toggleDarkMode"
      class="w-10 h-10 md:w-12 md:h-12 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 rounded-full shadow-lg border border-gray-200 dark:border-slate-700 flex items-center justify-center transition-all hover:scale-110 focus:outline-none"
      title="Ganti Tema"
    >
      <span v-show="!isDarkMode" class="flex items-center justify-center">
        <PhMoon class="w-5 h-5 md:w-6 md:h-6" />
      </span>
      <span v-show="isDarkMode" class="flex items-center justify-center">
        <PhSun class="w-5 h-5 md:w-6 md:h-6" />
      </span>
    </button>

    <!-- Scroll to Top -->
    <transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-300 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-4"
    >
      <button
        v-show="showScrollTop"
        @click="scrollToTop"
        class="w-10 h-10 md:w-12 md:h-12 bg-blue-600/90 hover:bg-blue-700 text-white rounded-full shadow-lg backdrop-blur-sm flex items-center justify-center transition-all hover:-translate-y-1 focus:outline-none"
        title="Kembali ke Atas"
      >
        <PhCaretUp class="w-5 h-5 md:w-6 md:h-6" />
      </button>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, reactive, watch } from "vue";
import { useRoute } from "vue-router";
import { PhCaretDown, PhMoon, PhSun, PhList, PhX, PhCaretUp } from "@phosphor-icons/vue";
import api from "@/api/index.js";

const navRef = ref(null);
const isNavbarScrolled = ref(false);
const showScrollTop = ref(false);
const isMobileMenuOpen = ref(false);
const isDarkMode = ref(false);
const activeDropdown = ref(null);

const route = useRoute();

const settings = ref({
  namaSekolah: localStorage.getItem("app_namaSekolah") || "",
  logo: localStorage.getItem("app_logoSekolah") || "",
});

const fetchSettings = async () => {
  try {
    const response = await api.get("/api/settings");
    if (response.data && response.data.data) {
      settings.value = { ...settings.value, ...response.data.data };

      // Simpan ke cache agar load berikutnya instan (tanpa delay)
      if (response.data.data.namaSekolah)
        localStorage.setItem("app_namaSekolah", response.data.data.namaSekolah);
      if (response.data.data.logo)
        localStorage.setItem("app_logoSekolah", response.data.data.logo);
    }
  } catch (error) {
    console.error("Gagal mengambil pengaturan navbar:", error);
  }
};

const mobileDropdowns = reactive({
  profil: false,
  akademik: false,
  informasi: false,
  layanan: false,
});

const handleScroll = () => {
  const forceSolid = route.path.startsWith("/artikel");
  isNavbarScrolled.value = window.scrollY > 50 || forceSolid;
  showScrollTop.value = window.scrollY > 300;
};

const handleResize = () => {
  if (window.innerWidth >= 1024 && isMobileMenuOpen.value) {
    isMobileMenuOpen.value = false;
  }
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

const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
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

const handleStorageChange = (e) => {
  if (e.key === "settings_updated_at") {
    fetchSettings();
  }
};

watch(
  () => route.path,
  () => {
    handleScroll();
  }
);

onMounted(() => {
  fetchSettings();
  window.addEventListener("settings-updated", fetchSettings);
  window.addEventListener("storage", handleStorageChange);
  window.addEventListener("scroll", handleScroll);
  document.addEventListener("click", handleClickOutside);
  window.addEventListener("resize", handleResize);
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
  window.removeEventListener("settings-updated", fetchSettings);
  window.removeEventListener("storage", handleStorageChange);
  window.removeEventListener("scroll", handleScroll);
  document.removeEventListener("click", handleClickOutside);
  window.removeEventListener("resize", handleResize);
});
</script>

<style scoped></style>
