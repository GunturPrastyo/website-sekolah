<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "../../api/index.js";
import {
  PhEnvelopeSimple,
  PhLockKey,
  PhSpinner,
  PhSignIn,
  PhGoogleLogo,
  PhGraduationCap,
} from "@phosphor-icons/vue";

const router = useRouter();

const form = ref({
  email: "",
  password: "",
  remember: false,
});

const isLoading = ref(false);
const errorMessage = ref("");

const settings = ref({
  loginBackground:
    "https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1600&auto=format&fit=crop",
  namaSekolah: "SMAN 1 Nogosari",
});

const fetchSettings = async () => {
  try {
    const response = await api.get("/api/settings");
    if (response.data && response.data.data) {
      if (response.data.data.loginBackground) {
        settings.value.loginBackground = response.data.data.loginBackground;
      }
      if (response.data.data.namaSekolah) {
        settings.value.namaSekolah = response.data.data.namaSekolah;
      }
    }
  } catch (error) {
    console.error("Gagal mengambil pengaturan:", error);
  }
};

const handleGoogleLogin = () => {
  // Arahkan browser langsung ke endpoint backend Laravel Socialite.
  // Anda bisa mengganti URL di bawah sesuai dengan domain production/development Anda.
  const backendUrl = import.meta.env.VITE_API_URL || "http://localhost:8000";
  window.location.href = `${backendUrl}/api/auth/google/redirect`;
};

const handleLogin = async () => {
  isLoading.value = true;
  errorMessage.value = "";

  try {
    // 1. Request CSRF Cookie dari Laravel Sanctum
    await api.get("/sanctum/csrf-cookie");

    // 2. Request Login ke endpoint Breeze
    await api.post("/login", {
      email: form.value.email,
      password: form.value.password,
      remember: form.value.remember,
    });

    // 3. Fetch data user untuk mengecek role
    const { data } = await api.get("/api/user");
    localStorage.setItem("user_role", data.role); // Simpan role untuk validasi route

    // Simpan penanda bahwa pengguna sudah terautentikasi
    localStorage.setItem("isLoggedIn", "true");

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

onMounted(() => {
  fetchSettings();
});
</script>

<template>
  <div class="min-h-screen flex bg-white dark:bg-slate-900">
    <!-- Bagian Kiri: Gambar & Branding (Hanya tampil di layar besar) -->
    <div
      class="hidden lg:flex lg:w-1/2 relative bg-blue-900 overflow-hidden items-center justify-center"
    >
      <!-- Background Image with Overlay -->
      <img
        :src="settings.loginBackground"
        alt="School Background"
        class="absolute inset-0 w-full h-full object-cover opacity-30 transition-all duration-1000"
      />
      <!-- Gradient Overlay -->
      <div
        class="absolute inset-0 bg-gradient-to-br from-blue-900/90 to-blue-600/90 mix-blend-multiply"
      ></div>

      <!-- Content Kiri -->
      <div class="relative z-10 p-12 text-white max-w-lg">
        <div class="mb-8">
          <div
            class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30 shadow-lg transform -rotate-3"
          >
            <PhGraduationCap class="w-10 h-10 text-white rotate-3" />
          </div>
        </div>
        <h1
          class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight"
          style="font-family: 'Oswald', sans-serif"
        >
          Portal Admin<br /><span class="text-blue-200">{{ settings.namaSekolah }}</span>
        </h1>
        <p class="text-lg text-blue-100/80 leading-relaxed">
          Sistem informasi manajemen terpadu untuk mengelola konten website, data
          akademik, dan administrasi sekolah.
        </p>

        <div class="mt-12 flex items-center gap-4 text-sm text-blue-200/60">
          <span
            >&copy; {{ new Date().getFullYear() }} {{ settings.namaSekolah }}. All rights
            reserved.</span
          >
        </div>
      </div>

      <!-- Ornamen Blobs Animasi -->
      <div
        class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-50 animate-blob"
      ></div>
      <div
        class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-50 animate-blob animation-delay-2000"
      ></div>
    </div>

    <!-- Bagian Kanan: Form Login -->
    <div
      class="w-full lg:w-1/2 flex items-center justify-center px-6 sm:px-12 lg:px-20 bg-white dark:bg-slate-900 relative"
    >
      <div class="max-w-md w-full space-y-10 relative z-10 py-12">
        <!-- Header Form -->
        <div class="text-center">
          <div
            class="lg:hidden w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/30 transform rotate-3"
          >
            <PhGraduationCap class="w-8 h-8 text-white -rotate-3" />
          </div>

          <h2
            class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight"
            style="font-family: 'Oswald', sans-serif"
          >
            Selamat Datang!
          </h2>
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Silakan masuk untuk melanjutkan ke dashboard.
          </p>
        </div>

        <!-- Form Login -->
        <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
          <div class="space-y-5">
            <!-- Email Field -->
            <div>
              <label
                for="email"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                >Alamat Email</label
              >
              <div class="relative">
                <div
                  class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"
                >
                  <PhEnvelopeSimple class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                </div>
                <input
                  id="email"
                  name="email"
                  type="email"
                  autocomplete="email"
                  required
                  v-model="form.email"
                  class="appearance-none rounded-xl relative block w-full pl-11 px-4 py-3.5 border border-gray-200 dark:border-slate-700 placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-600"
                  placeholder="admin@sman1nogosari.sch.id"
                />
              </div>
            </div>

            <!-- Password Field -->
            <div>
              <label
                for="password"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                >Kata Sandi</label
              >
              <div class="relative">
                <div
                  class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"
                >
                  <PhLockKey class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                </div>
                <input
                  id="password"
                  name="password"
                  type="password"
                  autocomplete="current-password"
                  required
                  v-model="form.password"
                  class="appearance-none rounded-xl relative block w-full pl-11 px-4 py-3.5 border border-gray-200 dark:border-slate-700 placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-600"
                  placeholder="••••••••"
                />
              </div>
            </div>
          </div>

          <!-- Pilihan Lanjut -->
          <div class="flex items-center">
            <input
              id="remember-me"
              name="remember-me"
              type="checkbox"
              v-model="form.remember"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:border-slate-600 dark:bg-slate-700 cursor-pointer"
            />
            <label
              for="remember-me"
              class="ml-2 block text-sm text-gray-600 dark:text-gray-300 cursor-pointer"
            >
              Ingat saya
            </label>
          </div>

          <!-- Pesan Error -->
          <div
            v-if="errorMessage"
            class="p-3 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-800"
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
              class="group relative w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:shadow-md"
            >
              <PhSignIn
                v-if="!isLoading"
                class="h-5 w-5 mr-2 text-blue-200 group-hover:text-white transition-colors"
                aria-hidden="true"
              />
              <PhSpinner v-else class="h-5 w-5 mr-2 text-white animate-spin" />
              {{ isLoading ? "Memverifikasi..." : "Login" }}
            </button>
          </div>

          <!-- Garis Pemisah (Divider) -->
          <div class="mt-6 flex items-center justify-center">
            <div class="w-full border-t border-gray-200 dark:border-slate-700"></div>
            <span
              class="px-4 text-xs uppercase tracking-wider font-semibold text-gray-400 dark:text-gray-500 bg-white dark:bg-slate-900 whitespace-nowrap"
            >
              Atau masuk dengan
            </span>
            <div class="w-full border-t border-gray-200 dark:border-slate-700"></div>
          </div>

          <!-- Tombol Login Google -->
          <div>
            <button
              type="button"
              @click="handleGoogleLogin"
              class="w-full flex justify-center items-center py-3 px-4 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm bg-white dark:bg-slate-800 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-all duration-200"
            >
              <PhGoogleLogo class="w-5 h-5 mr-2 text-red-500" weight="bold" />
              Google
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Keyframes untuk efek dekorasi latar belakang animasi (blobs) */
@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}
</style>
