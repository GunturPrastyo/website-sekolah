<script setup>
defineProps({
  badge: { type: String, required: true },
  title: { type: String, required: true },
  description: { type: String, required: true },
  bgImage: { type: String, default: "" },
});
</script>

<template>
  <div
    class="relative pt-32 pb-24 lg:pt-44 lg:pb-32 overflow-hidden"
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
        class="w-full h-full object-cover opacity-30 md:opacity-40"
        alt="Header Background"
      />
      <!-- Tambahan overlay gelap transparan (opsional) agar teks makin kontras -->
      <div class="absolute inset-0 bg-slate-900/30"></div>
    </div>

    <slot name="bg-ornament"></slot>

    <!-- Overlay Gradien Gelap di Bagian Bawah untuk Transisi Halus -->
    <div
      class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-slate-900 dark:from-slate-950 to-transparent pointer-events-none z-0"
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
    </div>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap");
</style>
