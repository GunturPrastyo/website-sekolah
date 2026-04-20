<script setup>
import { ref, watch, nextTick } from 'vue';
import { createIcons, icons } from 'lucide';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['close']);

const showToast = ref(false);

const copyLink = () => {
  navigator.clipboard.writeText(window.location.href).then(() => {
    showToast.value = true;
    setTimeout(() => {
      showToast.value = false;
    }, 4000);
  }).catch((err) => {
    console.error("Gagal menyalin tautan: ", err);
  });
};

const close = () => {
  emit('close');
};

// Re-render icon lucide setiap kali modal terbuka karena transisi v-if membongkar DOM
watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    nextTick(() => {
      createIcons({ icons });
    });
  }
});
</script>

<template>
  <!-- Share Modal Overlay -->
  <Transition
    enter-active-class="transition-opacity duration-300"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition-opacity duration-300"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div v-if="isOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" @click="close">
      <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all" @click.stop>
        <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
          <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Bagikan Berita</h3>
          <button @click="close" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>
        <div class="p-6">
          <p class="text-sm text-gray-600 dark:text-gray-300 mb-5 font-medium line-clamp-2">"{{ title }}"</p>
          <div class="flex gap-4 justify-center mb-6">
            <button class="w-12 h-12 rounded-full bg-blue-100 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center hover:bg-blue-200 dark:hover:bg-slate-600 transition-colors" title="Bagikan ke Facebook"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg></button>
            <button class="w-12 h-12 rounded-full bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-gray-200 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors" title="Bagikan ke X"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></button>
            <button class="w-12 h-12 rounded-full bg-green-100 dark:bg-slate-700 text-green-600 dark:text-green-400 flex items-center justify-center hover:bg-green-200 dark:hover:bg-slate-600 transition-colors" title="Bagikan ke WhatsApp"><i data-lucide="message-circle" class="w-5 h-5"></i></button>
            <button @click="copyLink" class="w-12 h-12 rounded-full bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors" title="Salin Tautan"><i data-lucide="link" class="w-5 h-5"></i></button>
          </div>
          <button @click="close" class="w-full py-2.5 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">Tutup</button>
        </div>
      </div>
    </div>
  </Transition>

  <!-- Toast Notification Overlay -->
  <Transition
    enter-active-class="transition duration-300 ease-out transform" enter-from-class="opacity-0 translate-y-[-2rem]" enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition duration-200 ease-in transform" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-[-2rem]"
  >
    <div v-if="showToast" class="fixed top-24 right-6 sm:right-10 z-[120] flex items-center p-4 w-full max-w-xs bg-white dark:bg-slate-800 rounded-xl shadow-2xl border-l-4 border-indigo-500" role="alert">
      <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 text-indigo-600 bg-indigo-100 rounded-lg dark:bg-indigo-900/50 dark:text-indigo-400">
        <i data-lucide="check-circle" class="w-6 h-6"></i>
      </div>
      <div class="ml-3 text-sm font-normal">
        <span class="mb-1 text-sm font-bold text-gray-900 dark:text-white block">Tautan Disalin!</span>
        <div class="text-xs text-gray-600 dark:text-gray-400">Tautan berita berhasil disalin ke clipboard.</div>
      </div>
      <button @click="showToast = false" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white dark:bg-slate-800 text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 dark:hover:bg-slate-700 inline-flex items-center justify-center h-8 w-8 transition-colors focus:outline-none"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
  </Transition>
</template>