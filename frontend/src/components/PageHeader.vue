<script setup>
defineProps({
  badge: { type: String, required: true },
  title: { type: String, required: true },
  description: { type: String, required: true },
});
</script>

<template>
  <div
    class="relative pt-32 pb-24 lg:pt-44 lg:pb-32 bg-gradient-to-br from-blue-900 via-blue-950 to-slate-900 dark:from-slate-900 dark:to-slate-950 overflow-hidden"
  >
    <div
      class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500/20 blur-3xl rounded-full pointer-events-none"
    ></div>
    <div
      class="absolute bottom-0 right-0 w-80 h-80 bg-sky-500/20 blur-3xl rounded-full pointer-events-none"
    ></div>

    <slot name="bg-ornament"></slot>

    <div class="container relative z-10 mx-auto px-6 text-center">
      <span
        class="inline-block px-5 py-1.5 mb-5 text-lg md:text-xl font-bold text-blue-900 bg-white/90 rounded-full shadow-sm backdrop-blur tracking-wide text-transform: capitalize"
        style="font-family: 'Kalam', cursive"
      >
        {{ badge }}
      </span>
      <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
        {{ title }}
      </h1>
      <p class="text-blue-100 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
        {{ description }}
      </p>
    </div>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");
</style>
