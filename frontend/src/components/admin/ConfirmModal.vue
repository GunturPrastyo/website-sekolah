<script setup>
import { PhWarningCircle, PhX } from "@phosphor-icons/vue";

defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: "Konfirmasi Penghapusan",
  },
  message: {
    type: String,
    default: "Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.",
  },
  confirmText: {
    type: String,
    default: "Hapus",
  },
  cancelText: {
    type: String,
    default: "Batal",
  },
});

defineEmits(["confirm", "cancel"]);
</script>

<template>
  <Transition
    enter-active-class="transition ease-out duration-200"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="$emit('cancel')"></div>
      
      <!-- Modal Panel -->
      <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100 dark:border-slate-700 transform transition-all">
        <div class="p-6">
          <div class="flex items-start gap-4">
            <div class="shrink-0 inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">
              <PhWarningCircle class="w-6 h-6" weight="fill" />
            </div>
            <div class="flex-1 mt-1">
              <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ title }}</h3>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">{{ message }}</p>
            </div>
            <button @click="$emit('cancel')" class="shrink-0 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
              <PhX class="w-5 h-5" />
            </button>
          </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 dark:bg-slate-700/50 border-t border-gray-100 dark:border-slate-700 flex justify-end gap-3">
          <button @click="$emit('cancel')" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 dark:focus:ring-offset-slate-800">{{ cancelText }}</button>
          <button @click="$emit('confirm')" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-slate-800">{{ confirmText }}</button>
        </div>
      </div>
    </div>
  </Transition>
</template>