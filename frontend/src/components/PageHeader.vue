<script setup>
defineProps({
  badge: { type: String, required: true },
  title: { type: String, required: true },
  description: { type: String, required: true },
  currentPage: { type: String, default: "" },
  bgImage: { type: String, default: "" },
});
</script>

<template>
  <div
    class="relative pt-36 pb-28 lg:pt-48 lg:pb-36 overflow-hidden"
    :class="
      bgImage
        ? 'bg-slate-900 dark:bg-slate-950'
        : 'bg-gradient-to-br from-blue-900 via-blue-950 to-slate-900 dark:from-slate-900 dark:to-slate-950'
    "
  >
    <!-- Background Image -->
    <div v-if="bgImage" class="absolute inset-0 z-0">
      <img
        :src="bgImage"
        class="w-full h-full object-cover opacity-40 md:opacity-50"
        alt="Header Background"
      />
      <!-- Overlay gradien untuk kontras teks yang lebih baik -->
      <div
        class="absolute inset-0 bg-gradient-to-b from-slate-900/70 via-slate-900/10 to-transparent"
      ></div>
    </div>

    <slot name="bg-ornament"></slot>

    <!-- Overlay Gradien Gelap di Bagian Bawah untuk Transisi Halus -->
    <div
      class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-slate-900/95 dark:from-slate-950/95 to-transparent pointer-events-none z-0"
    ></div>

    <!-- Content -->
    <div class="container relative z-10 mx-auto px-8 lg:px-16 text-left">
      <span
        class="block mb-1 md:mb-2 text-base md:text-xl font-bold text-blue-300 tracking-wide capitalize"
        style="font-family: 'Kalam', cursive"
      >
        {{ badge }}
      </span>
      <h1
        class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-3 md:mb-4 leading-tight"
        style="font-family: 'Oswald', sans-serif"
      >
        {{ title }}
      </h1>
      <p
        class="text-blue-100 text-sm sm:text-base md:text-lg max-w-3xl leading-relaxed drop-shadow-md"
      >
        {{ description }}
      </p>

      <div
        v-if="currentPage"
        class="mt-4 md:mt-6 text-sm text-blue-200/80 flex items-center gap-1"
      >
        <router-link to="/" class="hover:text-white transition-colors">Home</router-link>
        <span class="mx-1">&gt;</span>
        <span class="font-semibold text-white">{{ currentPage }}</span>
      </div>
    </div>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap");
</style>
