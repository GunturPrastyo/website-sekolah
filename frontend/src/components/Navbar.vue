<template>
    <nav id="navbar" class="fixed top-0 left-0 z-50 w-full px-6 py-4 transition-all duration-300 ease-in-out"
        :class="{'bg-white/80 shadow-md backdrop-blur-sm dark:bg-slate-900/80 dark:border-b dark:border-slate-800': isNavbarScrolled}">
        <div class="container flex items-center justify-between max-w-6xl mx-auto">
            <router-link to="/" class="text-2xl font-bold"
                :class="{'text-gray-800 dark:text-white': isNavbarScrolled, 'text-white': !isNavbarScrolled}">SMAN 1
                Nogosari</router-link>

            <!-- Desktop Menu -->
            <div class="hidden items-center space-x-2 lg:flex">
                <router-link to="/" class="rounded-md px-3 py-2"
                    :class="{'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400': isNavbarScrolled, 'text-white hover:bg-blue-500/10 hover:text-blue-300': !isNavbarScrolled}">Beranda</router-link>

                <!-- Profil Dropdown -->
                <div class="group relative">
                    <button
                        class="flex items-center rounded-md px-3 py-2" :class="{'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400': isNavbarScrolled, 'text-white hover:bg-blue-500/10 hover:text-blue-300': !isNavbarScrolled}">
                        <span>Profil</span>
                        <i data-lucide="chevron-down" class="ml-1 h-4 w-4"></i>
                    </button>
                    <div
                        class="absolute left-0 z-10 mt-2 hidden w-48 rounded-md bg-white dark:bg-slate-800 py-2 text-gray-800 dark:text-gray-200 shadow-lg group-hover:block">
                        <router-link to="/sejarah" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Sejarah Sekolah</router-link>
                        <router-link to="/visi-misi" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Visi & Misi</router-link>
                        <router-link to="/guru-staf" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Guru & Staf</router-link>
                        <router-link to="/fasilitas" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Fasilitas</router-link>
                    </div>
                </div>

                <!-- Akademik Dropdown -->
                 <div class="group relative">
                    <button
                        class="flex items-center rounded-md px-3 py-2" :class="{'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400': isNavbarScrolled, 'text-white hover:bg-blue-500/10 hover:text-blue-300': !isNavbarScrolled}">
                        <span>Akademik</span>
                        <i data-lucide="chevron-down" class="ml-1 h-4 w-4"></i>
                    </button>
                    <div
                        class="absolute left-0 z-10 mt-2 hidden w-48 rounded-md bg-white dark:bg-slate-800 py-2 text-gray-800 dark:text-gray-200 shadow-lg group-hover:block">
                        <router-link to="/kurikulum" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Kurikulum</router-link>
                        <router-link to="/ekstrakurikuler" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Ekstrakurikuler</router-link>
                        <router-link to="/prestasi" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Prestasi</router-link>
                    </div>
                </div>

                <router-link to="/kontak" class="rounded-md px-3 py-2"
                    :class="{'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400': isNavbarScrolled, 'text-white hover:bg-blue-500/10 hover:text-blue-300': !isNavbarScrolled}">Kontak</router-link>
            </div>

            <div class="hidden items-center lg:flex">
                <router-link to="/pendaftaran"
                    class="rounded-full bg-blue-600 px-5 py-2 font-semibold text-white transition-colors hover:bg-blue-700">Pendaftaran</router-link>
                <!-- Dark Mode Toggle -->
                <button @click="toggleDarkMode" class="ml-2 p-2 rounded-full focus:outline-none transition-colors"
                    :class="[isNavbarScrolled ? 'text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-slate-700' : 'text-white hover:bg-white/10']">
                    <i data-lucide="moon" class="w-5 h-5" v-if="!isDarkMode"></i>
                    <i data-lucide="sun" class="w-5 h-5" v-else></i>
                </button>
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex items-center gap-2 lg:hidden">
                <button @click="toggleMobileMenu"
                    :class="[isNavbarScrolled ? 'text-gray-800 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-slate-700' : 'text-white hover:bg-white/10']"
                    class="p-2 rounded-md focus:outline-none transition-colors">
                    <i data-lucide="menu" class="h-6 w-6"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div :class="{ 'max-h-screen opacity-100': isMobileMenuOpen, 'max-h-0 opacity-0': !isMobileMenuOpen }" 
             class="lg:hidden overflow-hidden transition-all duration-300 ease-in-out w-full">
            <div class="mt-4 space-y-2 rounded-xl bg-white/95 backdrop-blur-md dark:bg-slate-800/95 border border-gray-100 dark:border-slate-700/50 p-5 text-gray-800 dark:text-gray-200 shadow-2xl mx-1">
                <router-link to="/" class="block rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors">Beranda</router-link>
                <router-link to="/visi-misi" class="block rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors">Visi & Misi</router-link>
                <router-link to="/kontak" class="block rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors">Kontak</router-link>
                <div class="pt-2 mt-2 border-t border-gray-100 dark:border-slate-700/50">
                    <router-link to="/pendaftaran"
                        class="block w-full rounded-xl bg-blue-600 px-4 py-3 text-center font-semibold text-white shadow-md shadow-blue-500/30 transition-colors hover:bg-blue-700 active:scale-[0.98]">Pendaftaran</router-link>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const isNavbarScrolled = ref(false);
const isMobileMenuOpen = ref(false);
const isDarkMode = ref(false);

const handleScroll = () => {
    isNavbarScrolled.value = window.scrollY > 50;
};

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const setDarkMode = (dark) => {
    isDarkMode.value = dark;
    if (dark) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('color-theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('color-theme', 'light');
    }
};

const toggleDarkMode = () => {
    setDarkMode(!isDarkMode.value);
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    handleScroll();

    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        setDarkMode(true);
    } else {
        setDarkMode(false);
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>