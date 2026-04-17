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
    <div class="relative pt-32 pb-24 lg:pt-44 lg:pb-32 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 dark:from-slate-900 dark:to-slate-950 overflow-hidden">
      
      <!-- Glow effect -->
      <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-400/20 blur-3xl rounded-full"></div>
      <div class="absolute bottom-0 right-0 w-80 h-80 bg-indigo-400/20 blur-3xl rounded-full"></div>

      <div class="container relative z-10 mx-auto px-6 text-center">
        <span class="inline-block px-4 py-1.5 mb-5 text-xs md:text-sm font-semibold text-blue-800 bg-white/90 rounded-full shadow-sm backdrop-blur">
          Profil Sekolah
        </span>

        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
          Guru & Staf Sekolah
        </h1>

        <p class="text-blue-100 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
          Mengenal lebih dekat para pendidik dan tenaga kependidikan yang berperan penting dalam membimbing serta mendukung perkembangan siswa.
        </p>
      </div>
    </div>

    <!-- Main Content -->
    <section class="py-16 md:py-24 px-6 bg-gray-50 dark:bg-slate-900">
      <div class="container mx-auto max-w-7xl">
        <div class="flex flex-col md:flex-row gap-10">

          <!-- Sidebar -->
          <aside class="w-full md:w-1/4 lg:w-1/5">
            <div class="sticky top-28 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-slate-700">
              
              <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-5">
                Kategori
              </h3>

              <ul class="space-y-2">
                <li v-for="category in categories" :key="category.id">
                  <a
                    href="#"
                    @click.prevent="activeCategory = category.id"
                    class="flex items-center justify-between px-4 py-2.5 rounded-lg text-sm transition-all duration-200"
                    :class="{
                      'bg-blue-600 text-white shadow': activeCategory === category.id,
                      'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700': activeCategory !== category.id
                    }"
                  >
                    {{ category.name }}
                  </a>
                </li>
              </ul>
            </div>
          </aside>

          <!-- Content -->
          <main class="w-full md:w-3/4 lg:w-4/5 space-y-10">

            <!-- Pimpinan -->
            <div v-if="pimpinanList.length > 0" class="bg-white dark:bg-slate-800 p-8 md:p-10 rounded-2xl shadow-md border border-gray-100 dark:border-slate-700">
              
              <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
                  Pimpinan Sekolah
                </h2>
                <div class="w-16 h-1 bg-yellow-400 mx-auto mt-3 rounded-full"></div>
              </div>

              <div class="flex flex-wrap justify-center gap-8">
                <div
                  v-for="staff in pimpinanList"
                  :key="staff.id"
                  class="group bg-white dark:bg-slate-800 rounded-2xl shadow hover:shadow-xl transition duration-300 overflow-hidden w-full sm:w-64"
                >
                  <div class="h-56 overflow-hidden">
                    <img
                      :src="staff.image"
                      :alt="staff.name"
                      class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                    />
                  </div>

                  <div class="p-6 text-center">
                    <h3 class="font-bold text-slate-900 dark:text-white">
                      {{ staff.name }}
                    </h3>
                    <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                      {{ staff.role }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Guru -->
            <div v-if="guruList.length > 0" class="bg-white dark:bg-slate-800 p-8 md:p-10 rounded-2xl shadow-md border border-gray-100 dark:border-slate-700">

              <div class="mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
                  Tenaga Pendidik
                </h2>
                <p class="text-sm text-gray-500 mt-1">Guru yang berpengalaman dan profesional</p>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                  v-for="staff in guruList"
                  :key="staff.id"
                  class="group bg-white dark:bg-slate-800 rounded-xl shadow hover:shadow-lg transition overflow-hidden"
                >
                  <div class="h-48 overflow-hidden">
                    <img
                      :src="staff.image"
                      class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                    />
                  </div>

                  <div class="p-5 text-center">
                    <h3 class="font-semibold text-slate-900 dark:text-white">
                      {{ staff.name }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      {{ staff.role }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Staf -->
            <div v-if="stafList.length > 0" class="bg-white dark:bg-slate-800 p-8 md:p-10 rounded-2xl shadow-md border border-gray-100 dark:border-slate-700">

              <div class="mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
                  Tenaga Kependidikan
                </h2>
                <p class="text-sm text-gray-500 mt-1">Staf pendukung operasional sekolah</p>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                  v-for="staff in stafList"
                  :key="staff.id"
                  class="group bg-white dark:bg-slate-800 rounded-xl shadow hover:shadow-lg transition overflow-hidden"
                >
                  <div class="h-48 overflow-hidden">
                    <img
                      :src="staff.image"
                      class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                    />
                  </div>

                  <div class="p-5 text-center">
                    <h3 class="font-semibold text-slate-900 dark:text-white">
                      {{ staff.name }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      {{ staff.role }}
                    </p>
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