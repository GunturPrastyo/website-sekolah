<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import api from "@/api/index.js";

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
</script>

<template>
  <div class="w-screen h-screen bg-gray-500">
    <iframe :src="documentUrl" class="w-full h-full border-0" :title="documentTitle">
      <p class="p-8 text-center text-white">
        Browser Anda tidak mendukung tampilan dokumen. Silakan klik tombol unduh untuk
        melihat file.
      </p>
    </iframe>
  </div>
</template>
