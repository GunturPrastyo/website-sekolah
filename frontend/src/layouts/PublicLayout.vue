<template>
  <div class="bg-gray-50 dark:bg-slate-900 overflow-x-hidden relative w-full">
    <Navbar />
    <main>
      <router-view v-slot="{ Component, route }">
        <keep-alive :max="10">
          <component :is="Component" :key="route.path" />
        </keep-alive>
      </router-view>
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
</style>
