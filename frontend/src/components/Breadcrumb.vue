<script setup>
import { onMounted, onUpdated, nextTick } from 'vue';
import { createIcons, icons } from 'lucide';

defineProps({
  items: {
    type: Array,
    required: true,
    default: () => []
    // Contoh Format: 
    // [
    //   { name: 'Beranda', link: '/', icon: 'home' },
    //   { name: 'Berita' }
    // ]
  }
});

// Me-render ulang icon Lucide ketika properti / komponen diperbarui
onMounted(() => {
  createIcons({ icons });
});

onUpdated(() => {
  nextTick(() => {
    createIcons({ icons });
  });
});
</script>

<template>
  <nav class="flex text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2">
      <li
        v-for="(item, index) in items"
        :key="index"
        :aria-current="index === items.length - 1 ? 'page' : undefined"
        class="inline-flex items-center"
      >
        <!-- Separator (Ditampilkan setelah item pertama) -->
        <i v-if="index > 0" data-lucide="chevron-right" class="w-4 h-4 mx-1"></i>

        <!-- Tampilan untuk Item Terakhir (Halaman Aktif - Bukan Tautan) -->
        <div v-if="index === items.length - 1" class="flex items-center">
          <i v-if="item.icon" :data-lucide="item.icon" class="w-4 h-4 mr-1.5"></i>
          <span class="text-blue-600 dark:text-blue-400 font-semibold" :class="{ 'ml-1': index > 0 }">{{ item.name }}</span>
        </div>

        <!-- Tampilan untuk Item Sebelumnya (Berupa Tautan) -->
        <router-link v-else :to="item.link || '#'" class="inline-flex items-center hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
          <i v-if="item.icon" :data-lucide="item.icon" class="w-4 h-4 mr-1.5"></i>
          {{ item.name }}
        </router-link>
      </li>
    </ol>
  </nav>
</template>