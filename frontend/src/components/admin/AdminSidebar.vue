<script setup>
import { ref, markRaw, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import {
  PhHouse,
  PhUsers,
  PhGear,
  PhSignOut,
  PhCaretDown,
  PhBuildings,
  PhChalkboard,
  PhMegaphone,
  PhClipboardText,
  PhDatabase,
  PhGraduationCap,
  PhBooks,
  PhCalendar,
} from "@phosphor-icons/vue";

const route = useRoute();

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: true,
  },
});

const openDropdown = ref(null);

const toggleDropdown = (menuName) => {
  if (openDropdown.value === menuName) {
    openDropdown.value = null;
  } else {
    openDropdown.value = menuName;
  }
};

const menu = ref([
  { name: "Dashboard", icon: markRaw(PhHouse), link: "/admin/dashboard" },
  {
    name: "Profil Sekolah",
    icon: markRaw(PhBuildings),
    children: [
      { name: "Sejarah", link: "/admin/sejarah" }, // Link ke halaman admin sejarah
      { name: "Visi & Misi", link: "/admin/visi-misi" },
      { name: "Fasilitas", link: "/admin/fasilitas" },
    ],
  },
  {
    name: "Data Master",
    icon: markRaw(PhDatabase),
    children: [
      { name: "Data Siswa", link: "/admin/data-siswa" },
      { name: "Data Guru & Staf", link: "/admin/guru-staf" },
      { name: "Data Alumni", link: "/admin/data-alumni" },
      { name: "Data Kelas", link: "/admin/data-kelas" },
    ],
  },
  {
    name: "Akademik",
    icon: markRaw(PhBooks),
    children: [
      { name: "Kurikulum", link: "/admin/kurikulum" },
      { name: "Program Jurusan", link: "/admin/program-jurusan" },
      { name: "Jadwal Pelajaran", link: "/admin/jadwal-pelajaran" },
    ],
  },
  {
    name: "Kesiswaan",
    icon: markRaw(PhGraduationCap),
    children: [
      { name: "Ekstrakurikuler", link: "/admin/ekstrakurikuler" },
      { name: "Prestasi Siswa", link: "/admin/prestasi" },
      { name: "Pelanggaran & Tatib", link: "/admin/pelanggaran" },
    ],
  },
  { name: "Agenda Sekolah", icon: markRaw(PhCalendar), link: "/admin/agenda" },
  { name: "Informasi PPDB", icon: markRaw(PhClipboardText), link: "/admin/info-ppdb" },
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

  { name: "Pengguna Sistem", icon: markRaw(PhUsers), link: "/admin/pengguna" },
  { name: "Pengaturan Umum", icon: markRaw(PhGear), link: "/admin/pengaturan" },
]);

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

onMounted(checkActiveMenu);
watch(() => route.path, checkActiveMenu);
</script>

<template>
  <aside
    :class="props.isOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-slate-800 shadow-lg transform transition-transform duration-300 ease-in-out"
  >
    <div
      class="flex items-center justify-center h-20 border-b border-gray-100 dark:border-slate-700"
    >
      <h1 class="text-xl font-bold text-blue-600 dark:text-blue-400">Admin Panel</h1>
    </div>
    <nav class="mt-6 flex-1 overflow-y-auto text-sm">
      <div v-for="item in menu" :key="item.name" class="mb-1">
        <!-- Item with children (dropdown) -->
        <div v-if="item.children">
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
          v-else
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
