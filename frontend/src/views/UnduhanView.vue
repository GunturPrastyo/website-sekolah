<script setup>
import { ref, computed } from "vue";
import PageHeader from "@/components/PageHeader.vue";
import {
  PhFolder,
  PhFolderOpen,
  PhFile,
  PhFileText,
  PhArchive,
  PhImage,
  PhDownloadSimple,
  PhMagnifyingGlass,
  PhCaretRight,
  PhList,
  PhGridFour,
  PhHardDrives,
  PhDotsThreeVertical,
  PhFileX,
} from "@phosphor-icons/vue";

const categories = ref([
  { id: "semua", name: "Semua Direktori" },
  { id: "akademik", name: "Akademik & Kurikulum" },
  { id: "kesiswaan", name: "Kesiswaan & OSIS" },
  { id: "ppdb", name: "Brosur & PPDB" },
  { id: "umum", name: "Dokumen Umum" },
]);

const activeCategory = ref("semua");
const searchQuery = ref("");
const viewMode = ref("list"); // 'list' atau 'grid'

// Data Dummy Files
const files = ref([
  {
    id: 1,
    name: "Kalender_Akademik_2025_2026.pdf",
    type: "pdf",
    size: "2.4 MB",
    date: "15 Jan 2026",
    category: "akademik",
  },
  {
    id: 2,
    name: "Brosur_PPDB_SMAN1_Nogosari.pdf",
    type: "pdf",
    size: "5.1 MB",
    date: "10 Feb 2026",
    category: "ppdb",
  },
  {
    id: 3,
    name: "Formulir_Pendaftaran_Ekskul.docx",
    type: "docx",
    size: "1.2 MB",
    date: "05 Feb 2026",
    category: "kesiswaan",
  },
  {
    id: 4,
    name: "SOP_Tata_Tertib_Siswa_Rev.pdf",
    type: "pdf",
    size: "3.5 MB",
    date: "20 Jan 2026",
    category: "umum",
  },
  {
    id: 5,
    name: "Modul_Latihan_UTBK_Saintek.zip",
    type: "zip",
    size: "15.8 MB",
    date: "12 Jan 2026",
    category: "akademik",
  },
  {
    id: 6,
    name: "Buku_Panduan_OSIS_2026.pdf",
    type: "pdf",
    size: "4.2 MB",
    date: "02 Feb 2026",
    category: "kesiswaan",
  },
  {
    id: 7,
    name: "Logo_Resmi_SMAN1_Nogosari.png",
    type: "image",
    size: "1.8 MB",
    date: "25 Jan 2026",
    category: "umum",
  },
  {
    id: 8,
    name: "Juknis_Pelaksanaan_PPDB.docx",
    type: "docx",
    size: "850 KB",
    date: "11 Feb 2026",
    category: "ppdb",
  },
]);

const filteredFiles = computed(() => {
  return files.value.filter((file) => {
    const matchCategory =
      activeCategory.value === "semua" || file.category === activeCategory.value;
    const matchSearch = file.name.toLowerCase().includes(searchQuery.value.toLowerCase());
    return matchCategory && matchSearch;
  });
});

const getFileIcon = (type) => {
  switch (type) {
    case "pdf":
      return PhFileText;
    case "docx":
      return PhFileText;
    case "zip":
      return PhArchive;
    case "image":
      return PhImage;
    default:
      return PhFile;
  }
};

const getFileIconColor = (type) => {
  switch (type) {
    case "pdf":
      return "text-red-500 dark:text-red-400";
    case "docx":
      return "text-blue-500 dark:text-blue-400";
    case "zip":
      return "text-yellow-500 dark:text-yellow-400";
    case "image":
      return "text-emerald-500 dark:text-emerald-400";
    default:
      return "text-gray-500 dark:text-gray-400";
  }
};

const activeCategoryName = computed(() => {
  return categories.value.find((c) => c.id === activeCategory.value)?.name;
});
</script>

<template>
  <div>
    <PageHeader
      badge="Informasi"
      title="Unduhan Publik"
      description="Pusat dokumen, modul, dan formulir resmi SMAN 1 Nogosari yang dapat diakses dan diunduh oleh siswa maupun masyarakat umum."
      bgImage="https://images.unsplash.com/photo-1568044852337-9cbac391ce42?q=80&w=1600&auto=format&fit=crop"
    />

    <section class="py-0 md:py-12 bg-gray-50 dark:bg-slate-900 min-h-[60vh]">
      <div class="container mx-auto max-w-full px-0 md:px-8">
        <div
          class="flex flex-col lg:flex-row bg-white dark:bg-slate-800 rounded-none md:rounded-2xl shadow-sm md:shadow-md border-y md:border border-gray-200 dark:border-slate-700 overflow-hidden min-h-[600px]"
        >
          <!-- SIDEBAR (File Manager Tree) -->
          <div
            class="w-full lg:w-72 bg-gray-50 dark:bg-slate-800/50 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-slate-700 flex flex-col shrink-0"
          >
            <div class="p-5 border-b border-gray-200 dark:border-slate-700">
              <h3
                class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider flex items-center gap-2"
              >
                <PhHardDrives class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                Direktori Utama
              </h3>
            </div>
            <div class="p-3 flex-1 overflow-y-auto max-h-52 lg:max-h-none">
              <ul class="space-y-1">
                <li v-for="folder in categories" :key="folder.id">
                  <button
                    @click="activeCategory = folder.id"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none"
                    :class="
                      activeCategory === folder.id
                        ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400'
                        : 'text-gray-600 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-slate-700/50'
                    "
                  >
                    <component
                      :is="activeCategory === folder.id ? PhFolderOpen : PhFolder"
                      class="w-5 h-5"
                      :class="
                        activeCategory === folder.id
                          ? 'text-yellow-500 dark:text-yellow-400'
                          : 'text-yellow-400 dark:text-yellow-500/80'
                      "
                      weight="fill"
                    />
                    {{ folder.name }}
                  </button>
                </li>
              </ul>
            </div>
          </div>

          <!-- MAIN AREA (File Viewer) -->
          <div class="flex-1 flex flex-col bg-white dark:bg-slate-800 min-w-0">
            <!-- Toolbar Top -->
            <div
              class="px-5 py-4 border-b border-gray-200 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800"
            >
              <!-- Breadcrumb -->
              <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                <span class="hover:text-blue-600 cursor-pointer">Beranda</span>
                <PhCaretRight class="w-3.5 h-3.5 mx-2" />
                <span class="hover:text-blue-600 cursor-pointer">Unduhan</span>
                <PhCaretRight class="w-3.5 h-3.5 mx-2" />
                <span class="font-bold text-gray-800 dark:text-gray-200">{{
                  activeCategoryName
                }}</span>
              </div>

              <!-- Tools: Search & View Toggle -->
              <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-60">
                  <PhMagnifyingGlass
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"
                  />
                  <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Cari file..."
                    class="w-full pl-9 pr-4 py-2 bg-gray-100 dark:bg-slate-900 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:text-white outline-none transition-shadow"
                  />
                </div>
                <div
                  class="flex items-center bg-gray-100 dark:bg-slate-900 rounded-lg p-1 shrink-0"
                >
                  <button
                    @click="viewMode = 'list'"
                    class="p-1.5 rounded-md transition-colors"
                    :class="
                      viewMode === 'list'
                        ? 'bg-white dark:bg-slate-700 shadow-sm text-gray-800 dark:text-white'
                        : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300'
                    "
                  >
                    <PhList class="w-4 h-4" />
                  </button>
                  <button
                    @click="viewMode = 'grid'"
                    class="p-1.5 rounded-md transition-colors"
                    :class="
                      viewMode === 'grid'
                        ? 'bg-white dark:bg-slate-700 shadow-sm text-gray-800 dark:text-white'
                        : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300'
                    "
                  >
                    <PhGridFour class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>

            <!-- File List Content -->
            <div class="flex-1 p-5 overflow-auto">
              <!-- Empty State -->
              <div
                v-if="filteredFiles.length === 0"
                class="h-full flex flex-col items-center justify-center text-center py-16"
              >
                <PhFileX class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                  Folder Kosong
                </h4>
                <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">
                  Tidak ada file yang ditemukan pada direktori ini.
                </p>
              </div>

              <!-- List View -->
              <div v-else-if="viewMode === 'list'" class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr
                      class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-200 dark:border-slate-700"
                    >
                      <th class="pb-3 font-medium">Nama File</th>
                      <th class="pb-3 font-medium hidden md:table-cell">Ukuran</th>
                      <th class="pb-3 font-medium hidden sm:table-cell">Modifikasi</th>
                      <th class="pb-3 font-medium text-right">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="file in filteredFiles"
                      :key="file.id"
                      class="border-b last:border-0 border-gray-100 dark:border-slate-700/50 hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors group cursor-default"
                    >
                      <td class="py-4">
                        <div class="flex items-center gap-3">
                          <component
                            :is="getFileIcon(file.type)"
                            class="w-8 h-8 shrink-0"
                            :class="getFileIconColor(file.type)"
                            weight="fill"
                          />
                          <div>
                            <span
                              class="block text-sm font-semibold text-gray-800 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                            >
                              {{ file.name }}
                            </span>
                            <span
                              class="block md:hidden text-xs text-gray-400 dark:text-gray-500 mt-0.5"
                              >{{ file.size }} • {{ file.date }}</span
                            >
                          </div>
                        </div>
                      </td>
                      <td
                        class="py-4 text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell"
                      >
                        {{ file.size }}
                      </td>
                      <td
                        class="py-4 text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell"
                      >
                        {{ file.date }}
                      </td>
                      <td class="py-4 text-right">
                        <button
                          class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-full transition-all focus:outline-none"
                          title="Unduh File"
                        >
                          <PhDownloadSimple class="w-5 h-5" />
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Grid View -->
              <div
                v-else-if="viewMode === 'grid'"
                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4"
              >
                <div
                  v-for="file in filteredFiles"
                  :key="file.id"
                  class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-4 flex flex-col items-center text-center hover:shadow-md hover:border-blue-300 dark:hover:border-blue-500 transition-all group cursor-pointer relative"
                >
                  <!-- Hover Options (Top Right) -->
                  <button
                    class="absolute top-2 right-2 p-1.5 opacity-0 group-hover:opacity-100 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all"
                  >
                    <PhDotsThreeVertical class="w-5 h-5" />
                  </button>

                  <component
                    :is="getFileIcon(file.type)"
                    class="w-14 h-14 mb-3"
                    :class="getFileIconColor(file.type)"
                    weight="fill"
                  />
                  <span
                    class="text-xs font-semibold text-gray-800 dark:text-gray-200 line-clamp-2 break-words group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                  >
                    {{ file.name }}
                  </span>
                  <span class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">
                    {{ file.size }}
                  </span>

                  <!-- Tombol Unduh Hover -->
                  <button
                    class="mt-3 w-full py-1.5 text-[11px] font-bold uppercase tracking-wider bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white transition-colors flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100"
                  >
                    <PhDownloadSimple class="w-3.5 h-3.5" /> Unduh
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
