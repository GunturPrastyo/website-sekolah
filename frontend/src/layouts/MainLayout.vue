<script setup>
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
</script>

<template>
  <div class="bg-blue-50 dark:bg-slate-900 overflow-x-hidden relative w-full">
    <Navbar />
    <router-view />
    <Footer />
  </div>
</template>