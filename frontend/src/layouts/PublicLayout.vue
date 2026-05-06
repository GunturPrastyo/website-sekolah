<template>
  <div class="bg-blue-50 dark:bg-slate-900 overflow-x-hidden relative w-full">
    <Navbar />
    <main>
      <router-view />
    </main>
    <Footer />
  </div>
</template>

<script setup>
import Navbar from "@/components/Navbar.vue";
import Footer from "@/components/Footer.vue";
</script>

<style>
/* Anda bisa menambahkan gaya khusus untuk PublicLayout di sini jika diperlukan */
/* Atau biarkan kosong jika gaya sudah ditangani secara global atau oleh komponen anak */
</style>
