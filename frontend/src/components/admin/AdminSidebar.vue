<script setup>
import { ref, computed, markRaw, onMounted, onBeforeUnmount, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import {
  PhHouse,
  PhUsers,
  PhGear,
  PhSignOut,
  PhCaretDown,
  PhBuildings,
  PhMegaphone,
  PhClipboardText,
  PhDatabase,
  PhGraduationCap,
  PhBooks,
  PhCalendar,
  PhNewspaper,
  PhImages,
  PhPencilSimple,
} from "@phosphor-icons/vue";
import api from "@/api/index.js";

const route = useRoute();
const router = useRouter();

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(["close"]);

const openDropdown = ref(null);

const toggleDropdown = (menuName) => {
  if (openDropdown.value === menuName) {
    openDropdown.value = null;
  } else {
    openDropdown.value = menuName;
  }
};

const userRole = localStorage.getItem("user_role") || "admin";

const menu = computed(() => {
  if (userRole === "super_admin") {
    return [
      { heading: "Menu Utama" },
      { name: "Dashboard", icon: markRaw(PhHouse), link: "/admin/dashboard" },

      { heading: "Manajemen Sekolah" },
      {
        name: "Profil Sekolah",
        icon: markRaw(PhBuildings),
        children: [
          { name: "Sejarah", link: "/admin/sejarah" },
          { name: "Visi & Misi", link: "/admin/visi-misi" },
          { name: "Fasilitas", link: "/admin/fasilitas" },
        ],
      },
      {
        name: "Data Master",
        icon: markRaw(PhDatabase),
        children: [
          { name: "Data Guru & Staf", link: "/admin/guru-staf" },
          { name: "Data Kelas", link: "/admin/data-kelas" },
          { name: "Data Siswa", link: "/admin/data-siswa" },
          { name: "Data Alumni", link: "/admin/data-alumni" },
        ],
      },
      {
        name: "Akademik",
        icon: markRaw(PhBooks),
        children: [
          { name: "Program Jurusan", link: "/admin/program-jurusan" },
          { name: "Kurikulum", link: "/admin/kurikulum" },
          { name: "Jadwal Pelajaran", link: "/admin/jadwal-pelajaran" },
        ],
      },
      {
        name: "Kesiswaan",
        icon: markRaw(PhGraduationCap),
        children: [
          { name: "Ekstrakurikuler", link: "/admin/ekstrakurikuler" },
          { name: "Prestasi Siswa", link: "/admin/prestasi" },
        ],
      },

      { heading: "Informasi & Publikasi" },
      { name: "Agenda Sekolah", icon: markRaw(PhCalendar), link: "/admin/agenda" },
      {
        name: "Informasi PPDB",
        icon: markRaw(PhClipboardText),
        link: "/admin/info-ppdb",
      },
      {
        name: "Publikasi & Media",
        icon: markRaw(PhMegaphone),
        children: [
          { name: "Berita & Artikel", link: "/admin/berita" },
          { name: "Galeri Foto & Video", link: "/admin/galeri" },
          { name: "Unduhan File", link: "/admin/unduhan" },
          { name: "Validasi Konten", link: "/admin/validasi-konten" },
        ],
      },

      { heading: "Sistem & Pengaturan" },
      { name: "Pengguna Sistem", icon: markRaw(PhUsers), link: "/admin/pengguna" },
      { name: "Pengaturan Umum", icon: markRaw(PhGear), link: "/admin/pengaturan" },
    ];
  }

  // Tampilan menu untuk role admin biasa (bukan super_admin)
  return [
    { heading: "Menu Utama" },
    { name: "Dashboard", icon: markRaw(PhHouse), link: "/admin/dashboard" },

    { heading: "Informasi & Publikasi" },
    { name: "Berita & Artikel", icon: markRaw(PhNewspaper), link: "/admin/berita" },
    { name: "Galeri Foto & Video", icon: markRaw(PhImages), link: "/admin/galeri" },
    {
      name: "Draft & Revisi",
      icon: markRaw(PhPencilSimple),
      link: "/admin/draft-berita",
    },

    { heading: "Sistem & Pengaturan" },
    { name: "Pengaturan Akun", icon: markRaw(PhGear), link: "/admin/pengaturan" },
  ];
});

const checkActiveMenu = () => {
  for (const item of menu.value) {
    if (item.children) {
      const isActive = item.children.some((child) => child.link === route.path);
      if (isActive) {
        openDropdown.value = item.name;
      }
    }
  }
};

// Fetch nama dan logo sekolah dari backend
const schoolName = ref("Admin Panel");
const schoolLogo = ref(null);

const fetchSettings = async () => {
  try {
    const response = await api.get("/api/settings");
    if (response.data && response.data.data) {
      if (response.data.data.namaSekolah)
        schoolName.value = response.data.data.namaSekolah;
      if (response.data.data.logo) schoolLogo.value = response.data.data.logo;
    }
  } catch (error) {
    console.error("Gagal mengambil pengaturan sekolah:", error);
  }
};

const handleStorageChange = (e) => {
  if (e.key === "settings_updated_at") {
    fetchSettings();
  }
};

onMounted(() => {
  checkActiveMenu();
  fetchSettings();
  window.addEventListener("settings-updated", fetchSettings);
  window.addEventListener("storage", handleStorageChange);
});
watch(
  () => route.path,
  () => {
    checkActiveMenu();
    // Otomatis menutup sidebar di layar mobile setelah menu diklik
    if (typeof window !== "undefined" && window.innerWidth < 1024) {
      emit("close");
    }
  }
);

onBeforeUnmount(() => {
  window.removeEventListener("settings-updated", fetchSettings);
  window.removeEventListener("storage", handleStorageChange);
});

const handleLogout = async () => {
  try {
    await api.post("/logout");
  } catch (error) {
    console.error("Gagal logout:", error);
  } finally {
    // Hapus status login dan data user lainnya dari localStorage jika ada
    localStorage.removeItem("isLoggedIn");
    localStorage.removeItem("user_role");
    // Kembalikan pengguna ke halaman login
    router.push("/login");
  }
};
</script>

<template>
  <!-- Overlay background khusus mobile untuk menutup sidebar jika area luar diklik -->
  <div
    v-show="props.isOpen"
    @click="emit('close')"
    class="fixed inset-0 z-20 bg-black/50 transition-opacity lg:hidden"
  ></div>

  <aside
    :class="[
      props.isOpen ? 'translate-x-0' : '-translate-x-full',
      'fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-slate-800 shadow-lg transform transition-transform duration-300 ease-in-out flex flex-col',
    ]"
  >
    <div
      class="flex items-center px-6 h-20 shrink-0 border-b border-gray-100 dark:border-slate-700"
    >
      <img
        v-if="schoolLogo"
        :src="schoolLogo"
        alt="Logo Sekolah"
        class="w-10 h-10 object-contain mr-3 shrink-0"
      />
      <div
        v-else
        class="w-10 h-10 rounded-full bg-blue-50 dark:bg-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400 mr-3 shrink-0"
      >
        <PhBuildings :size="20" />
      </div>
      <h1
        class="text-base font-bold text-gray-800 dark:text-white line-clamp-2 leading-tight"
      >
        {{ schoolName }}
      </h1>
    </div>
    <nav class="py-2 flex-1 overflow-y-auto text-sm custom-scrollbar">
      <div v-for="(item, index) in menu" :key="index" class="mb-1">
        <!-- Category Heading -->
        <div
          v-if="item.heading"
          class="px-6 pt-4 pb-2 text-[11px] font-bold tracking-wider text-gray-400 dark:text-gray-500 uppercase"
        >
          {{ item.heading }}
        </div>

        <!-- Item with children (dropdown) -->
        <div v-else-if="item.children">
          <button
            @click="toggleDropdown(item.name)"
            class="w-full flex items-center justify-between px-6 py-3 text-sm transition-colors border-l-4"
            :class="
              item.children.some((child) => child.link === route.path)
                ? 'text-blue-600 bg-blue-50/50 dark:bg-blue-900/20 dark:text-blue-400 border-blue-600/50 font-semibold'
                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/50 border-transparent'
            "
          >
            <span class="flex items-center">
              <component :is="item.icon" :size="20" class="mr-3" />
              <span class="font-semibold">{{ item.name }}</span>
            </span>
            <PhCaretDown
              :size="16"
              class="transition-transform duration-300"
              :class="openDropdown === item.name && 'rotate-180'"
            />
          </button>
          <div
            class="overflow-hidden transition-all duration-300"
            :class="openDropdown === item.name ? 'max-h-96' : 'max-h-0'"
          >
            <div class="py-1 pl-12 pr-6 bg-gray-50/50 dark:bg-slate-900/30">
              <router-link
                v-for="child in item.children"
                :key="child.name"
                :to="child.link"
                class="flex items-center py-2.5 transition-colors"
                :class="
                  route.path === child.link
                    ? 'text-blue-600 dark:text-blue-400 font-semibold'
                    : 'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400'
                "
              >
                {{ child.name }}
              </router-link>
            </div>
          </div>
        </div>
        <!-- Item without children (link) -->
        <router-link
          v-else-if="item.link"
          :to="item.link"
          class="flex items-center px-6 py-3 border-l-4"
          :class="
            route.path === item.link
              ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-300 border-blue-600 font-semibold'
              : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors font-semibold border-transparent'
          "
        >
          <component :is="item.icon" :size="20" class="mr-3" />
          <span>{{ item.name }}</span>
        </router-link>
      </div>
    </nav>
    <div
      class="w-full mt-auto shrink-0 border-t border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800"
    >
      <button
        @click="handleLogout"
        class="w-full flex items-center px-6 py-4 text-gray-600 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition-colors"
      >
        <PhSignOut :size="20" class="mr-3" />
        <span>Keluar</span>
      </button>
    </div>
  </aside>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 10px;
}
</style>
