<script setup>
import { onMounted, onUpdated, nextTick } from 'vue';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import { createIcons } from 'lucide';

// Fungsi ini akan dijalankan saat komponen pertama kali dimuat
onMounted(() => {
  createIcons();
});

// Fungsi ini akan dijalankan setiap kali halaman berganti atau ada pembaruan DOM
onUpdated(() => {
  nextTick(createIcons);
});
</script>

<template>
  <div class="bg-blue-50 dark:bg-slate-900 overflow-x-hidden relative w-full">
    <Navbar />
    <router-view />
    <Footer />
  </div>
</template>