<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import { createIcons } from 'lucide';

const categories = ref([
  { id: 'semua', name: 'Struktur Organisasi' },
  { id: 'pimpinan', name: 'Pimpinan Sekolah' },
  { id: 'informatika', name: 'Guru Informatika' },
  { id: 'matematika', name: 'Guru Matematika' },
  { id: 'olahraga', name: 'Guru Olahraga' },
  { id: 'bahasa_inggris', name: 'Guru Bahasa Inggris' },
  { id: 'pustakawan', name: 'Pustakawan' },
  { id: 'tata_usaha', name: 'Staff Tata Usaha' }
]);

const activeCategory = ref('semua');

// Contoh data dummy lengkap dengan atribut 'category'
const staffList = ref([
  { 
    id: 1, 
    name: 'Dr. H. Budi Santoso, M.Pd', 
    role: 'Kepala Sekolah', 
    category: 'pimpinan',
    image: 'https://ui-avatars.com/api/?name=Budi+Santoso&background=0D8ABC&color=fff&size=256' 
  },
  { 
    id: 2, 
    name: 'Siti Aminah, M.Pd', 
    role: 'Wakil Kepala Sekolah', 
    category: 'pimpinan',
    image: 'https://ui-avatars.com/api/?name=Siti+Aminah&background=0D8ABC&color=fff&size=256' 
  },
  { 
    id: 3, 
    name: 'Ahmad Fauzi, S.Kom', 
    role: 'Guru Informatika', 
    category: 'informatika',
    image: 'https://ui-avatars.com/api/?name=Ahmad+Fauzi&background=0D8ABC&color=fff&size=256' 
  },
  { 
    id: 4, 
    name: 'Rina Rahmawati, S.E', 
    role: 'Guru Matematika', 
    category: 'matematika',
    image: 'https://ui-avatars.com/api/?name=Rina+Rahmawati&background=0D8ABC&color=fff&size=256' 
  },
  { 
    id: 5, 
    name: 'Dwi Saputra, S.Or', 
    role: 'Guru Olahraga', 
    category: 'olahraga',
    image: 'https://ui-avatars.com/api/?name=Dwi+Saputra&background=0D8ABC&color=fff&size=256' 
  },
  { 
    id: 6, 
    name: 'Rudi Hermawan, S.Pd', 
    role: 'Guru Bahasa Inggris', 
    category: 'bahasa_inggris',
    image: 'https://ui-avatars.com/api/?name=Rudi+Hermawan&background=0D8ABC&color=fff&size=256' 
  },
  { 
    id: 7, 
    name: 'Maya Indah, S.Pust', 
    role: 'Pustakawan', 
    category: 'pustakawan',
    image: 'https://ui-avatars.com/api/?name=Maya+Indah&background=0D8ABC&color=fff&size=256' 
  },
  { 
    id: 8, 
    name: 'Joko Widodo', 
    role: 'Staff Tata Usaha', 
    category: 'tata_usaha',
    image: 'https://ui-avatars.com/api/?name=Joko+Widodo&background=0D8ABC&color=fff&size=256' 
  },
]);

// Computed properties untuk mode "Struktur Organisasi"
const pimpinanList = computed(() => staffList.value.filter(s => s.category === 'pimpinan'));
const guruList = computed(() => staffList.value.filter(s => ['informatika', 'matematika', 'olahraga', 'bahasa_inggris'].includes(s.category)));
const stafList = computed(() => staffList.value.filter(s => ['pustakawan', 'tata_usaha'].includes(s.category)));

// Computed property untuk mode filter kategori biasa
const filteredStaff = computed(() => {
  if (activeCategory.value === 'semua') return []; // Jika "semua", kita tangani secara terpisah di template
  return staffList.value.filter(s => s.category === activeCategory.value);
});

const activeCategoryName = computed(() => {
  return categories.value.find(c => c.id === activeCategory.value)?.name || '';
});

onMounted(() => {
  nextTick(() => {
    createIcons();
  });
});
</script>

<template>
  <div>
    <!-- Hero Banner -->
    <div class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 bg-gradient-to-br from-blue-600 to-blue-800 dark:from-blue-900 dark:to-slate-900 overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-t dark:from-slate-900 to-transparent"></div>
      <div class="container relative z-10 mx-auto px-6 text-center">
        <span class="inline-block px-4 py-1.5 mb-4 text-xs md:text-sm font-semibold text-blue-900 bg-blue-100 rounded-full shadow-sm">Profil Sekolah</span>
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">Guru dan Staf</h1>
        <p class="text-blue-200 text-base md:text-lg max-w-3xl mx-auto">Mengenal lebih dekat para pendidik dan tenaga kependidikan yang berdedikasi tinggi dalam membimbing dan melayani siswa-siswi kami.</p>
      </div>
    </div>

    <!-- Main Content Section -->
    <section class="py-16 md:py-24 px-6">
      <div class="container mx-auto max-w-7xl">
        <div class="flex flex-col md:flex-row gap-8 lg:gap-12">
          
          <!-- Left Navigation -->
          <aside class="w-full md:w-1/4 lg:w-1/5 shrink-0">
            <div class="sticky top-28 bg-white dark:bg-slate-800 p-5 md:p-6 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700">
              <h3 class="text-lg font-bold text-blue-950 dark:text-white mb-4 pl-2 border-l-4 border-blue-600 rounded-sm">Kategori</h3>
              <ul class="space-y-2">
                <li v-for="category in categories" :key="category.id">
                  <a href="#" @click.prevent="activeCategory = category.id" class="block px-4 py-2.5 rounded-lg transition-all duration-200 text-sm font-medium"
                  :class="{
                    'bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 font-semibold': activeCategory === category.id,
                    'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-700/50 hover:text-blue-600 dark:hover:text-blue-300': activeCategory !== category.id
                  }">
                    <span>{{ category.name }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </aside>

          <!-- Right Content -->
          <main class="w-full md:w-3/4 lg:w-4/5">
            
            <!-- Tampilan Struktur Organisasi (Semua) -->
            <div v-if="activeCategory === 'semua'" class="space-y-10 md:space-y-12">
              
              <!-- Pimpinan -->
              <div v-if="pimpinanList.length > 0" class="bg-white dark:bg-slate-800 p-6 md:p-10 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700">
                <div class="text-center mb-10">
                  <h2 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white inline-block border-b-4 border-yellow-400 pb-2">Pimpinan Sekolah</h2>
                </div>
                <div class="flex flex-wrap justify-center gap-8">
                  <div v-for="staff in pimpinanList" :key="staff.id" class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center w-full sm:w-64 transform hover:-translate-y-1">
                    <div class="w-full h-56 overflow-hidden bg-slate-100 dark:bg-slate-700 flex justify-center items-center">
                      <img :src="staff.image" :alt="staff.name" class="w-full h-full object-cover object-center" />
                    </div>
                    <div class="p-6 w-full">
                      <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ staff.name }}</h3>
                      <p class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ staff.role }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Guru -->
              <div v-if="guruList.length > 0" class="bg-white dark:bg-slate-800 p-6 md:p-10 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700">
                <div class="mb-8 border-b border-gray-200 dark:border-slate-700 pb-4">
                  <h2 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white pl-4 border-l-4 border-blue-600">Tenaga Pendidik (Guru)</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                  <div v-for="staff in guruList" :key="staff.id" class="bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center group">
                    <div class="w-full h-48 overflow-hidden bg-slate-100 dark:bg-slate-700">
                      <img :src="staff.image" :alt="staff.name" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500" />
                    </div>
                    <div class="p-5 w-full">
                      <h3 class="text-md font-bold text-slate-900 dark:text-white mb-1">{{ staff.name }}</h3>
                      <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ staff.role }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Staf -->
              <div v-if="stafList.length > 0" class="bg-white dark:bg-slate-800 p-6 md:p-10 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700">
                <div class="mb-8 border-b border-gray-200 dark:border-slate-700 pb-4">
                  <h2 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white pl-4 border-l-4 border-sky-500">Tenaga Kependidikan (Staf)</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                  <div v-for="staff in stafList" :key="staff.id" class="bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center group">
                    <div class="w-full h-48 overflow-hidden bg-slate-100 dark:bg-slate-700">
                      <img :src="staff.image" :alt="staff.name" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500" />
                    </div>
                    <div class="p-5 w-full">
                      <h3 class="text-md font-bold text-slate-900 dark:text-white mb-1">{{ staff.name }}</h3>
                      <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ staff.role }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tampilan per Kategori yang difilter (Grid Biasa) -->
            <div v-else class="bg-white dark:bg-slate-800 p-6 md:p-10 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700">
              <div class="mb-8 border-b border-gray-200 dark:border-slate-700 pb-4 flex justify-between items-end">
                <h2 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white">{{ activeCategoryName }}</h2>
                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800 px-3 py-1 rounded-full">{{ filteredStaff.length }} Orang</span>
              </div>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <div v-for="staff in filteredStaff" :key="staff.id" class="bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center group">
                  <div class="w-full h-56 md:h-48 overflow-hidden bg-slate-100 dark:bg-slate-700">
                    <img :src="staff.image" :alt="staff.name" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500" />
                  </div>
                  <div class="p-5 w-full">
                    <h3 class="text-md font-bold text-slate-900 dark:text-white mb-1">{{ staff.name }}</h3>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ staff.role }}</p>
                  </div>
                </div>
              </div>
            </div>

          </main>
        </div>
      </div>
    </section>
  </div>
</template>