<script setup>
import {
  PhMagnifyingGlass,
  PhBell,
  PhCaretDown,
  PhList,
} from "@phosphor-icons/vue";

const emit = defineEmits(["toggle-sidebar"]);
</script>

<template>
  <header
    class="flex items-center justify-between h-20 px-6 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 shadow-sm"
  >
    <div class="flex items-center">
      <button
        @click="emit('toggle-sidebar')"
        class="lg:hidden text-gray-600 dark:text-gray-300 focus:outline-none mr-4"
      >
        <PhList :size="24" />
      </button>
      <div class="relative hidden md:block">
        <PhMagnifyingGlass
          class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
        />
        <input
          type="text"
          placeholder="Cari sesuatu..."
          class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
        />
      </div>
    </div>
    <div class="flex items-center gap-4">
      <button
        class="p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
      >
        <PhBell :size="20" />
      </button>
      <div class="relative group">
        <button class="flex items-center gap-2">
          <img
            src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop"
            alt="Admin"
            class="w-10 h-10 rounded-full object-cover border-2 border-blue-500"
          />
          <div class="hidden md:block text-left">
            <div class="font-semibold text-sm text-gray-800 dark:text-white">
              Budi Santoso
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Admin Utama</div>
          </div>
          <PhCaretDown :size="16" class="text-gray-500 hidden md:block" />
        </button>
        <div
          class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-md shadow-lg py-1 hidden group-hover:block z-20 border border-gray-100 dark:border-slate-700"
        >
          <a
            href="#"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700"
            >Profil Saya</a
          >
          <a
            href="#"
            class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/50"
            >Keluar</a
          >
        </div>
      </div>
    </div>
  </header>
</template>