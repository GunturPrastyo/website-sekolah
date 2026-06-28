<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import api from "@/api/index.js";

const route = useRoute();
const pdfUrl = ref("");
const isLoading = ref(true);

onMounted(async () => {
  const filename = route.params.filename;

  const baseUrl = api.defaults.baseURL;

  const fileUrl = `${baseUrl.replace(/\/api$/, "")}/storage/agendas/${filename}`;

  try {
    const response = await fetch(fileUrl);
    if (!response.ok) throw new Error("File tidak ditemukan");

    const blob = await response.blob();
    pdfUrl.value = URL.createObjectURL(blob);
  } catch (error) {
    console.error("Gagal memuat dokumen:", error);
  } finally {
    isLoading.value = false;
  }
});
</script>

<template>
  <div class="w-screen h-screen bg-gray-900 flex flex-col overflow-hidden">
    <div
      class="h-14 bg-gray-800 border-b border-gray-700 flex items-center px-6 justify-between shrink-0"
    >
      <span class="text-white font-medium text-sm truncate pr-4">
        {{ route.params.filename }}
      </span>
      <button
        @click="window.close()"
        class="text-gray-300 hover:text-white text-sm bg-gray-700 px-3 py-1.5 rounded transition-colors"
      >
        Tutup
      </button>
    </div>

    <div class="flex-1 relative bg-gray-900">
      <div
        v-if="isLoading"
        class="absolute inset-0 flex flex-col items-center justify-center text-white"
      >
        <p class="text-sm text-gray-400">Memuat dokumen...</p>
      </div>

      <iframe
        v-if="pdfUrl"
        :src="pdfUrl"
        class="w-full h-full border-none"
        title="Dokumen Viewer"
      ></iframe>
    </div>
  </div>
</template>
