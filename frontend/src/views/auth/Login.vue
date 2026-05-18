<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { PhEnvelopeSimple, PhLockKey, PhSpinner, PhSignIn } from "@phosphor-icons/vue";

const router = useRouter();

// Konfigurasi default axios untuk Laravel Sanctum (Sebaiknya letakkan ini di file konfigurasi terpisah / plugin)
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
axios.defaults.baseURL = import.meta.env.VITE_BACKEND_URL || "http://localhost:8000";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
axios.defaults.headers.common["Accept"] = "application/json";

const form = ref({
  email: "",
  password: "",
  remember: false,
});

const isLoading = ref(false);
const errorMessage = ref("");

const handleLogin = async () => {
  isLoading.value = true;
  errorMessage.value = "";

  try {
    // 1. Request CSRF Cookie dari Laravel Sanctum
    await axios.get("/sanctum/csrf-cookie");

    // 2. Request Login ke endpoint Breeze
    await axios.post("/login", {
      email: form.value.email,
      password: form.value.password,
      remember: form.value.remember,
    });

    // 3. Opsional: Fetch data user untuk mengecek role
    // const { data } = await axios.get('/api/user');
    // localStorage.setItem('user_role', data.role); // misalnya untuk mengecek 'superadmin' atau 'admin'

    // 4. Redirect ke dashboard admin (sementara default masuk ke superadmin view)
    router.push("/admin/dashboard");
  } catch (error) {
    // Tangkap validasi error dari Laravel (kode 422)
    if (error.response && error.response.status === 422) {
      errorMessage.value = error.response.data.message || "Email atau password salah.";
    } else if (error.response && error.response.status === 419) {
      errorMessage.value =
        "Sesi kadaluarsa (CSRF token mismatch). Silakan refresh halaman dan coba lagi.";
    } else {
      console.error("Detail Error Login:", error.response || error);
      errorMessage.value =
        error.response?.data?.message ||
        "Terjadi kesalahan pada server. Silakan cek console browser (F12) untuk detailnya.";
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-slate-900 px-4 sm:px-6 lg:px-8"
  >
    <div
      class="max-w-md w-full space-y-8 bg-white dark:bg-slate-800 p-8 sm:p-10 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700"
    >
      <!-- Header Info -->
      <div>
        <div
          class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg"
        >
          <PhLockKey class="w-8 h-8 text-white" />
        </div>
        <h2
          class="text-center text-3xl font-extrabold text-blue-950 dark:text-white"
          style="font-family: 'Oswald', sans-serif"
        >
          Login Portal Admin
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
          Masuk ke sistem manajemen SMAN 1 Nogosari
        </p>
      </div>

      <!-- Form Login -->
      <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
        <div class="rounded-md space-y-4">
          <!-- Email Field -->
          <div>
            <label
              for="email"
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Alamat Email</label
            >
            <div class="relative">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
                <PhEnvelopeSimple class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="email"
                name="email"
                type="email"
                autocomplete="email"
                required
                v-model="form.email"
                class="appearance-none rounded-lg relative block w-full pl-10 px-3 py-2.5 border border-gray-300 dark:border-slate-600 placeholder-gray-400 text-gray-900 dark:text-white bg-gray-50 dark:bg-slate-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-colors"
                placeholder="admin@sman1nogosari.sch.id"
              />
            </div>
          </div>

          <!-- Password Field -->
          <div>
            <label
              for="password"
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Kata Sandi</label
            >
            <div class="relative">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
                <PhLockKey class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                required
                v-model="form.password"
                class="appearance-none rounded-lg relative block w-full pl-10 px-3 py-2.5 border border-gray-300 dark:border-slate-600 placeholder-gray-400 text-gray-900 dark:text-white bg-gray-50 dark:bg-slate-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-colors"
                placeholder="••••••••"
              />
            </div>
          </div>
        </div>

        <!-- Pilihan Lanjut -->
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input
              id="remember-me"
              name="remember-me"
              type="checkbox"
              v-model="form.remember"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:border-slate-600 dark:bg-slate-700"
            />
            <label
              for="remember-me"
              class="ml-2 block text-sm text-gray-900 dark:text-gray-300"
            >
              Ingat saya
            </label>
          </div>
        </div>

        <!-- Pesan Error -->
        <div
          v-if="errorMessage"
          class="p-3 rounded-md bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800"
        >
          <p class="text-sm text-red-600 dark:text-red-400 text-center font-medium">
            {{ errorMessage }}
          </p>
        </div>

        <!-- Tombol Submit -->
        <div>
          <button
            type="submit"
            :disabled="isLoading"
            class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-70 disabled:cursor-not-allowed"
          >
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <PhSignIn
                v-if="!isLoading"
                class="h-5 w-5 text-blue-300 group-hover:text-white transition-colors"
                aria-hidden="true"
              />
              <PhSpinner v-else class="h-5 w-5 text-white animate-spin" />
            </span>
            {{ isLoading ? "Memverifikasi..." : "Masuk ke Dashboard" }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
