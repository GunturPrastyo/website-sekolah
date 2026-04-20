<template>
  <Navbar />
  <main>
    <router-view />
  </main>
  <Footer />
</template>

<script setup>
import { onMounted, watch, nextTick } from "vue";
import { useRoute } from "vue-router";
import { createIcons, icons } from "lucide";
import Navbar from "./components/Navbar.vue";
import Footer from "./components/Footer.vue";

const route = useRoute();

const initLucideIcons = () => {
  createIcons({ icons });
};

onMounted(() => {
  initLucideIcons();
});

// Setiap kali rute berubah, ikon perlu di-render ulang untuk halaman baru.
watch(
  route,
  () => {
    // nextTick memastikan DOM sudah diperbarui sebelum menjalankan createIcons
    nextTick(() => {
      initLucideIcons();
    });
  },
  { immediate: true, deep: true }
);
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap");

html,
body,
#app {
  font-family: "Poppins", sans-serif;
}
</style>
