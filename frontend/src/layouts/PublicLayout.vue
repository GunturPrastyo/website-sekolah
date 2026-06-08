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
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Merriweather:wght@700;900&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap");
/* Anda bisa menambahkan gaya khusus untuk PublicLayout di sini jika diperlukan */
/* Atau biarkan kosong jika gaya sudah ditangani secara global atau oleh komponen anak */
</style>
