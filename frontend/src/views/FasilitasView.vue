<template>
    <div>
        <!-- Hero Banner -->
        <div class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 bg-gradient-to-br from-blue-600 to-blue-800 dark:from-blue-900 dark:to-slate-900 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-t dark:from-slate-900 to-transparent"></div>
            <div class="container relative z-10 mx-auto px-6 text-center">
                <span class="inline-block px-4 py-1.5 mb-4 text-xs md:text-sm font-semibold text-blue-900 bg-blue-100 rounded-full shadow-sm">Profil Sekolah</span>
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">Fasilitas Sekolah</h1>
                <p class="text-blue-200 text-base md:text-lg max-w-3xl mx-auto">Jelajahi berbagai fasilitas modern dan lengkap yang kami sediakan untuk mendukung proses belajar mengajar yang optimal dan pengembangan potensi siswa.</p>
            </div>
        </div>

        <!-- Main Content Section -->
        <section class="py-16 md:py-24 px-6">
            <div class="container mx-auto max-w-7xl">
                <div class="flex flex-col md:flex-row gap-8 lg:gap-12">
                    
                    <!-- Left Navigation -->
                    <aside class="w-full md:w-1/4 lg:w-1/5">
                        <div class="sticky top-28">
                            <h3 class="text-lg font-bold text-blue-950 dark:text-white mb-4 pl-3">Kategori Fasilitas</h3>
                            <ul class="space-y-2">
                                <li v-for="category in facilityCategories" :key="category.id">
                                    <a href="#" @click.prevent="activeCategory = category.id" class="block px-4 py-2.5 rounded-lg transition-colors text-sm font-semibold"
                                    :class="{
                                        'bg-blue-600 text-white shadow': activeCategory === category.id,
                                        'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-800': activeCategory !== category.id
                                    }">
                                        <span>{{ category.name }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </aside>

                    <!-- Right Content -->
                    <main class="w-full md:w-3/4 lg:w-4/5 bg-white dark:bg-slate-800 p-6 sm:p-8 md:p-10 lg:p-12 rounded-2xl shadow-lg border border-gray-100 dark:border-slate-700">
                        <template v-for="category in facilityCategories" :key="category.id">
                            <article v-if="activeCategory === category.id" class="prose prose-lg dark:prose-invert max-w-none">
                                <div class="not-prose border-b border-gray-200 dark:border-slate-700 pb-5 mb-8">
                                    <h2 class="text-3xl md:text-4xl font-bold text-blue-950 dark:text-white !mb-3">{{ category.name }}</h2>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <span>Oleh: Tim Redaksi Sekolah</span> | 
                                        <time>Diperbarui 15 April 2026</time>
                                    </div>
                                </div>
                                <p class="lead text-lg text-gray-600 dark:text-gray-300">
                                    {{ category.description }}
                                </p>
                                <div class="mt-10">
                                    <div v-for="(item, index) in category.items" :key="index" class="mb-12">
                                        <h3>{{ item.title }}</h3>
                                        <p>{{ item.text }}</p>
                                        <figure class="mt-6">
                                            <img :src="item.src" :alt="item.title" class="w-full h-auto object-cover rounded-xl shadow-lg border-4 border-white dark:border-slate-700" />
                                            <figcaption class="text-center text-sm text-gray-500 dark:text-gray-400 mt-3 italic">
                                                {{ item.title }}
                                            </figcaption>
                                        </figure>
                                        <hr v-if="index < category.items.length - 1" class="!my-12">
                                    </div>
                                </div>
                            </article>
                        </template>
                    </main>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const activeCategory = ref('kelas');

const facilityCategories = ref([
    { 
        id: 'kelas', name: 'Ruang Kelas',
        description: 'Ruang kelas yang nyaman dan representatif untuk menunjang kegiatan belajar mengajar yang efektif.', 
        items: [
            { src: 'https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800', title: 'Ruang Kelas Modern & Nyaman', text: 'Setiap ruang kelas dirancang untuk menciptakan lingkungan belajar yang kondusif. Dilengkapi dengan AC, proyektor LCD, dan papan tulis interaktif, siswa dapat mengikuti pelajaran dengan nyaman.' },
            { src: 'https://images.unsplash.com/photo-1594434533439-04c3a735d359?q=80&w=800', title: 'Suasana Belajar Interaktif', text: 'Suasana belajar yang interaktif mendorong siswa untuk aktif bertanya, berdiskusi, dan berkolaborasi dalam memecahkan masalah.' }
        ]
    },
    { 
        id: 'lab', name: 'Laboratorium',
        description: 'Laboratorium modern untuk Fisika, Kimia, Biologi, dan Komputer untuk mendukung praktikum dan penelitian.', 
        items: [
            { src: 'https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800', title: 'Laboratorium Sains Terpadu', text: 'Laboratorium IPA kami dilengkapi dengan peralatan modern untuk membuktikan teori melalui eksperimen langsung.' },
            { src: 'https://images.unsplash.com/photo-1628243422323-b5039a8a4010?q=80&w=800', title: 'Laboratorium Komputer Modern', text: 'Lab komputer dengan PC berspesifikasi tinggi dan koneksi internet cepat untuk mendukung pelajaran TIK dan skill digital siswa.' }
        ]
    },
    { 
        id: 'perpus', name: 'Perpustakaan',
        description: 'Koleksi ribuan buku dan referensi digital dalam suasana yang tenang dan nyaman.', 
        items: [
            { src: 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=800', title: 'Pusat Sumber Belajar dan Literasi', text: 'Perpustakaan kami adalah jantung pengetahuan sekolah, menumbuhkan minat baca dan budaya literasi di kalangan siswa.' },
        ]
    },
    { 
        id: 'olahraga', name: 'Sarana Olahraga',
        description: 'Fasilitas olahraga lengkap untuk menyalurkan minat dan bakat siswa.', 
        items: [
            { src: 'https://images.unsplash.com/photo-1575361204480-aadea2503aa4?q=80&w=800', title: 'Lapangan Sepak Bola Standar', text: 'Kami memiliki lapangan sepak bola berstandar yang terawat baik untuk kegiatan ekstrakurikuler dan kompetisi.' },
        ]
    },
    { 
        id: 'pendukung', name: 'Fasilitas Pendukung',
        description: 'Berbagai fasilitas lain seperti kantin, mushola, dan aula untuk menunjang kenyamanan aktivitas sekolah.', 
        items: [
            { src: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=800', title: 'Kantin Sehat dan Higienis', text: 'Kantin kami menyediakan berbagai pilihan makanan dan minuman yang sehat, higienis, dan terjangkau.' },
        ]
    }
]);
</script>
