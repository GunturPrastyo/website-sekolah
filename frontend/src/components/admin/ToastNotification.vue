<script setup>
import { PhCheckCircle, PhWarningCircle, PhInfo, PhX } from "@phosphor-icons/vue";

defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: "Sukses!",
  },
  message: {
    type: String,
    default: "Aksi berhasil dilakukan.",
  },
  type: {
    type: String,
    default: "success", // success, error, info
  },
});

defineEmits(["close"]);
</script>

<template>
  <Transition
    enter-active-class="transition duration-300 ease-out transform"
    enter-from-class="opacity-0 translate-y-[-2rem] sm:translate-y-0 sm:translate-x-4"
    enter-to-class="opacity-100 translate-y-0 sm:translate-x-0"
    leave-active-class="transition duration-200 ease-in transform"
    leave-from-class="opacity-100 translate-y-0 sm:translate-x-0"
    leave-to-class="opacity-0 translate-y-[-2rem] sm:translate-y-0 sm:translate-x-4"
  >
    <div
      v-if="isOpen"
      class="fixed top-24 right-6 sm:right-10 z-[120] flex items-start p-4 w-full max-w-xs bg-white dark:bg-slate-800 rounded-xl shadow-2xl border-l-4"
      :class="{
        'border-green-500': type === 'success',
        'border-red-500': type === 'error',
        'border-blue-500': type === 'info',
      }"
      role="alert"
    >
      <div
        class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-lg mt-0.5"
        :class="{
          'text-green-600 bg-green-100 dark:bg-green-900/50 dark:text-green-400': type === 'success',
          'text-red-600 bg-red-100 dark:bg-red-900/50 dark:text-red-400': type === 'error',
          'text-blue-600 bg-blue-100 dark:bg-blue-900/50 dark:text-blue-400': type === 'info',
        }"
      >
        <PhCheckCircle v-if="type === 'success'" class="w-6 h-6" />
        <PhWarningCircle v-else-if="type === 'error'" class="w-6 h-6" />
        <PhInfo v-else class="w-6 h-6" />
      </div>
      <div class="ml-3 text-sm font-normal flex-1">
        <span class="mb-1 text-sm font-bold text-gray-900 dark:text-white block">{{ title }}</span>
        <div class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">{{ message }}</div>
      </div>
      <button @click="$emit('close')" type="button" class="ml-2 -mx-1.5 -my-1.5 bg-white dark:bg-slate-800 text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 dark:hover:bg-slate-700 inline-flex items-center justify-center h-8 w-8 transition-colors focus:outline-none"><PhX class="w-4 h-4" /></button>
    </div>
  </Transition>
</template>