<script setup>
import {
  PhHouse,
  PhNewspaper,
  PhNote,
  PhUsers,
  PhGear,
  PhSignOut,
} from "@phosphor-icons/vue";

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: true,
  },
});
</script>

<template>
  <aside
    :class="props.isOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-slate-800 shadow-lg transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 lg:shadow-none"
  >
    <div
      class="flex items-center justify-center h-20 border-b border-gray-100 dark:border-slate-700"
    >
      <h1 class="text-2xl font-bold text-blue-600 dark:text-blue-400">Admin Panel</h1>
    </div>
    <nav class="mt-6 flex-1">
      <a
        href="#"
        class="flex items-center px-6 py-3 text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-300 border-l-4 border-blue-600"
      >
        <PhHouse :size="20" class="mr-3" />
        <span class="font-semibold">Dashboard</span>
      </a>
      <a
        href="#"
        class="flex items-center px-6 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors"
      >
        <PhNewspaper :size="20" class="mr-3" />
        <span>Berita & Artikel</span>
      </a>
      <a
        href="#"
        class="flex items-center px-6 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors"
      >
        <PhNote :size="20" class="mr-3" />
        <span>Halaman</span>
      </a>
      <a
        href="#"
        class="flex items-center px-6 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors"
      >
        <PhUsers :size="20" class="mr-3" />
        <span>Pengguna</span>
      </a>
      <a
        href="#"
        class="flex items-center px-6 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors"
      >
        <PhGear :size="20" class="mr-3" />
        <span>Pengaturan</span>
      </a>
    </nav>
    <div class="absolute bottom-0 w-full border-t border-gray-100 dark:border-slate-700">
      <a
        href="#"
        class="flex items-center px-6 py-4 text-gray-600 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition-colors"
      >
        <PhSignOut :size="20" class="mr-3" />
        <span>Keluar</span>
      </a>
    </div>
  </aside>
</template>