<template>
    <nav id="navbar" class="fixed top-0 left-0 z-50 w-full px-6 py-4 transition-all duration-300 ease-in-out"
        :class="{'bg-white/80 shadow-md backdrop-blur-sm dark:bg-slate-900/80 dark:border-b dark:border-slate-800': isNavbarScrolled, 'bg-transparent': !isNavbarScrolled}">
        <div class="container flex items-center justify-between max-w-6xl mx-auto">
            <a href="#" class="text-2xl font-bold"
                :class="{'text-gray-800 dark:text-white': isNavbarScrolled, 'text-white': !isNavbarScrolled}">SMAN 1
                Nogosari</a>

            <!-- Desktop Menu -->
            <div class="hidden items-center space-x-2 lg:flex">
                <a href="#" class="rounded-md px-3 py-2"
                    :class="{'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400': isNavbarScrolled, 'text-white hover:bg-blue-500/10 hover:text-blue-300': !isNavbarScrolled}">Beranda</a>

                <!-- Profil Dropdown -->
                <div class="group relative">
                    <button
                        class="flex items-center rounded-md px-3 py-2" :class="{'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400': isNavbarScrolled, 'text-white hover:bg-blue-500/10 hover:text-blue-300': !isNavbarScrolled}">
                        <span>Profil</span>
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div
                        class="absolute left-0 z-10 mt-2 hidden w-48 rounded-md bg-white dark:bg-slate-800 py-2 text-gray-800 dark:text-gray-200 shadow-lg group-hover:block">
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Sejarah Sekolah</a>
                        <a href="visi-misi.html" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Visi & Misi</a>
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Struktur Organisasi</a>
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Guru & Staf</a>
                        <a href="fasilitas.html" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Fasilitas</a>
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Akreditasi</a>
                    </div>
                </div>

                <!-- Akademik Dropdown -->
                <div class="group relative">
                    <button
                        class="flex items-center rounded-md px-3 py-2" :class="{'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400': isNavbarScrolled, 'text-white hover:bg-blue-500/10 hover:text-blue-300': !isNavbarScrolled}">
                        <span>Akademik</span>
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div
                        class="absolute left-0 z-10 mt-2 hidden w-48 rounded-md bg-white dark:bg-slate-800 py-2 text-gray-800 dark:text-gray-200 shadow-lg group-hover:block">
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Kurikulum</a>
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Program Jurusan</a>
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Ekstrakurikuler</a>
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Prestasi</a>
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-slate-700">Kalender Akademik</a>
                    </div>
                </div>

                <a href="#" class="rounded-md px-3 py-2"
                    :class="{'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400': isNavbarScrolled, 'text-white hover:bg-blue-500/10 hover:text-blue-300': !isNavbarScrolled}">Kontak</a>
            </div>

            <div class="hidden items-center lg:flex">
                <a href="#"
                    class="rounded-full bg-blue-600 px-5 py-2 font-semibold text-white transition-colors hover:bg-blue-700">Pendaftaran</a>
                <!-- Dark Mode Toggle -->
                <button @click="toggleDarkMode" class="ml-2 p-2 rounded-full focus:outline-none transition-colors"
                    :class="[isNavbarScrolled ? 'text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-slate-700' : 'text-white hover:bg-white/10']">
                    <span v-show="!isDarkMode" class="flex items-center justify-center"><i data-lucide="moon" class="w-5 h-5"></i></span>
                    <span v-show="isDarkMode" class="flex items-center justify-center"><i data-lucide="sun" class="w-5 h-5"></i></span>
                </button>
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex items-center gap-2 lg:hidden">
                <!-- Mobile Dark Mode Toggle -->
                <button @click="toggleDarkMode" class="p-2 rounded-full focus:outline-none transition-colors"
                    :class="[isNavbarScrolled ? 'text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-slate-700' : 'text-white hover:bg-white/10']">
                    <span v-show="!isDarkMode" class="flex items-center justify-center"><i data-lucide="moon" class="w-5 h-5"></i></span>
                    <span v-show="isDarkMode" class="flex items-center justify-center"><i data-lucide="sun" class="w-5 h-5"></i></span>
                </button>
                
                <button @click="toggleMobileMenu"
                    :class="[isNavbarScrolled ? 'text-gray-800 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-slate-700' : 'text-white hover:bg-white/10']"
                    class="p-2 rounded-md focus:outline-none transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div :class="{ 
                'max-h-screen opacity-100': isMobileMenuOpen, 
                'max-h-0 opacity-0': !isMobileMenuOpen 
            }" class="lg:hidden overflow-hidden transition-all duration-300 ease-in-out">
            <div class="mt-4 space-y-2 rounded-xl text-left bg-white/95 backdrop-blur-md dark:bg-slate-800/95 border border-gray-100 dark:border-slate-700/50 p-5 text-gray-800 dark:text-gray-200 shadow-2xl mx-1">
                <a href="#" class="block rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors">Beranda</a>

                <!-- Mobile Profil Dropdown -->
                <div>
                    <button
                        class="mobile-dropdown-toggle flex w-full items-center justify-between rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors">
                        <span>Profil</span>
                        <svg @click.stop="toggleMobileDropdown('profil')"
                            :class="{'rotate-180': mobileDropdowns.profil}"
                            class="h-5 w-5 transform transition-transform duration-300 ease-in-out" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div :class="{
                            'max-h-screen': mobileDropdowns.profil, 
                            'max-h-0': !mobileDropdowns.profil 
                        }" class="mt-1 overflow-hidden transition-all duration-300 ease-in-out pl-4 pr-2 space-y-1">
                        <a href="#" class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors">Sejarah Sekolah</a>
                        <a href="visi-misi.html" class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors">Visi & Misi</a>
                        <a href="#" class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors">Struktur Organisasi</a>
                        <a href="#" class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors">Guru & Staf</a>
                        <a href="fasilitas.html" class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors">Fasilitas</a>
                        <a href="#" class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors">Akreditasi</a>
                    </div>
                </div>

                <!-- Mobile Akademik Dropdown -->
                <div>
                    <button
                        class="mobile-dropdown-toggle flex w-full items-center justify-between rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors">
                        <span>Akademik</span>
                        <svg @click.stop="toggleMobileDropdown('akademik')"
                            :class="{'rotate-180': mobileDropdowns.akademik}"
                            class="h-5 w-5 transform transition-transform duration-300 ease-in-out" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div :class="{ 
                            'max-h-screen': mobileDropdowns.akademik, 
                            'max-h-0': !mobileDropdowns.akademik 
                        }" class="mt-1 overflow-hidden transition-all duration-300 ease-in-out pl-4 pr-2 space-y-1">
                        <a href="#" class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors">Kurikulum</a>
                        <a href="#" class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors">Program Jurusan</a>
                        <a href="#" class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors">Ekstrakurikuler</a>
                        <a href="#" class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors">Prestasi</a>
                        <a href="#" class="block rounded-md px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-slate-700/50 transition-colors">Kalender Akademik</a>
                    </div>
                </div>

                <a href="#" class="block rounded-lg px-4 py-2.5 text-base font-medium hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors">Kontak</a>
                
                <div class="pt-2 mt-2 border-t border-gray-100 dark:border-slate-700/50">
                    <a href="#"
                        class="block w-full rounded-xl bg-blue-600 px-4 py-3 text-center font-semibold text-white shadow-md shadow-blue-500/30 transition-colors hover:bg-blue-700 active:scale-[0.98]">Pendaftaran</a>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, reactive, onMounted, onBeforeUnmount } from 'vue';

const isNavbarScrolled = ref(false);
const isMobileMenuOpen = ref(false);
const mobileDropdowns = reactive({
    profil: false,
    akademik: false
});

const isDarkMode = ref(false);

const setDarkMode = (isDark) => {
    isDarkMode.value = isDark;
    if (isDark) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('color-theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('color-theme', 'light');
    }
    // You might need to re-initialize icons if they don't update automatically
    // setTimeout(() => {
    //     lucide.createIcons();
    // }, 0);
};

const toggleDarkMode = () => {
    setDarkMode(!isDarkMode.value);
};

const handleScroll = () => {
    isNavbarScrolled.value = window.scrollY > 50;
};

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
    mobileDropdowns.profil = false;
    mobileDropdowns.akademik = false;
};

const toggleMobileDropdown = (dropdownName) => {
    mobileDropdowns[dropdownName] = !mobileDropdowns[dropdownName];
    for (const key in mobileDropdowns) {
        if (key !== dropdownName) {
            mobileDropdowns[key] = false;
        }
    }
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