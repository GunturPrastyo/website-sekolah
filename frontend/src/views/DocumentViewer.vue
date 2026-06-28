<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import api from "@/api/index.js";
import { PhDownloadSimple, PhArrowLeft } from "@phosphor-icons/vue";

const route = useRoute();
const filename = ref(route.params.filename);
const documentTitle = ref("Melihat Dokumen");

const documentUrl = computed(() => {
  if (!filename.value) return "";

  const baseUrl = api.defaults.baseURL.endsWith("/")
    ? api.defaults.baseURL.slice(0, -1)
    : api.defaults.baseURL;
  return `${baseUrl}/storage/agendas/${filename.value}`;
});

onMounted(() => {
  const prettyName = filename.value
    .replace(/-\d+\.(pdf|jpg|jpeg|png)$/i, "")
    .replace(/-/g, " ");

  documentTitle.value = prettyName.replace(/\b\w/g, (l) => l.toUpperCase());
  document.title = `${documentTitle.value} | ${
    localStorage.getItem("app_namaSekolah") || "Website Sekolah"
  }`;
});

const goBack = () => {
  window.history.length > 1 ? window.history.back() : window.close();
};
</script>

<template>
  <div class="w-screen h-screen bg-gray-200 dark:bg-slate-900 flex flex-col">
    <header
      class="bg-white dark:bg-slate-800 shadow-sm p-3 flex justify-between items-center z-10 shrink-0 border-b border-gray-200 dark:border-slate-700"
    >
      <div class="flex items-center gap-3 min-w-0">
        <button
          @click="goBack"
          title="Kembali"
          class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors shrink-0"
        >
          <PhArrowLeft class="w-5 h-5 text-gray-600 dark:text-gray-300" />
        </button>
        <h1 class="text-base md:text-lg font-bold text-gray-800 dark:text-white truncate">
          {{ documentTitle }}
        </h1>
      </div>
      <a
        :href="documentUrl"
        :download="filename"
        :title="`Unduh ${documentTitle}`"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shrink-0"
      >
        <PhDownloadSimple class="w-5 h-5 mr-2" />
        <span class="hidden sm:inline">Unduh File</span>
      </a>
    </header>
    <main class="flex-1 bg-gray-500">
      <iframe :src="documentUrl" class="w-full h-full border-0" :title="documentTitle">
        <p class="p-8 text-center text-white">
          Browser Anda tidak mendukung tampilan dokumen. Silakan klik tombol unduh untuk
          melihat file.
        </p>
      </iframe>
    </main>
  </div>
</template>
