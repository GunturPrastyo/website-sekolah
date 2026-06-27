<script setup>
import { onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();

onMounted(() => {
  const token = route.query.token;
  const role = route.query.role;

  if (token && role) {
    // Simpan informasi ke localStorage agar status login persist
    localStorage.setItem("auth_token", token);
    localStorage.setItem("user_role", role);
    localStorage.setItem("isLoggedIn", "true");

    // Arahkan ke dashboard admin
    router.push({ name: "dashboard" });
  } else {
    // Jika gagal, kembali ke halaman login dengan pesan error
    router.push({ name: "login", query: { error: "auth_failed" } });
  }
});
</script>

<template>
  <div
    class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-slate-900"
  >
    <div class="text-center p-6">
      <h2 class="text-lg font-medium text-gray-700 dark:text-gray-200">
        Mengarahkan Anda, mohon tunggu...
      </h2>
      <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
        Sedang memproses autentikasi Anda.
      </p>
    </div>
  </div>
</template>
