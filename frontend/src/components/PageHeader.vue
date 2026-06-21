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
    class="relative pt-32 pb-24 lg:pt-44 lg:pb-32 bg-gradient-to-b from-blue-900 via-blue-950 to-slate-950 dark:from-slate-900 dark:via-slate-950 dark:to-black overflow-hidden"
  >
    <div v-if="bgImage" class="absolute inset-0 z-0">
      <img
        :src="bgImage"
        class="w-full h-full object-cover lg:object-contain lg:object-center opacity-25 mix-blend-screen dark:mix-blend-overlay transition-all duration-300"
        alt="Header Background"
      />
    </div>

    <div
      class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-slate-950/85 via-slate-950/30 to-transparent dark:from-black/95 dark:via-black/40 pointer-events-none z-0"
    ></div>

    <slot name="bg-ornament"></slot>

    <div class="container relative z-10 mx-auto px-6 text-center">
      <span
        class="inline-block px-4 md:px-5 py-1 md:py-1.5 mb-4 md:mb-5 text-base md:text-xl font-bold text-blue-900 bg-white rounded-full shadow-sm backdrop-blur tracking-wide capitalize"
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
        class="text-blue-100 text-sm sm:text-base md:text-lg max-w-2xl mx-auto leading-relaxed"
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