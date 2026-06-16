<script setup>
import { ref, computed, onMounted } from "vue";
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

const files = ref([]);
const isLoading = ref(true);

const fetchDownloads = async () => {
  isLoading.value = true;
  try {
    const apiUrl = import.meta.env.VITE_API_URL || "http://localhost:8000/api";
    const response = await fetch(`${apiUrl}/public-downloads`);
    const result = await response.json();

    files.value = result.data.map((item) => {
      let ext = "file";
      const filePath = item.file || item.file_path;
      if (filePath) {
        const parts = filePath.split(".");
        ext = parts[parts.length - 1].toLowerCase();
      }

      return {
        id: item.id,
        name: item.title || "Dokumen Unduhan",
        type: ext,
        size: item.size || "-",
        date: new Date(item.created_at).toLocaleDateString("id-ID", {
          day: "2-digit",
          month: "short",
          year: "numeric",
        }),
        category: item.category
          ? item.category.toLowerCase().replace(/\s+/g, "-")
          : "umum",
        url: filePath,
      };
    });
  } catch (error) {
    console.error("Gagal mengambil data unduhan:", error);
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchDownloads();
});

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

const downloadFile = (file) => {
  if (file.url) {
    window.open(file.url, "_blank");
  } else {
    alert("URL file tidak tersedia");
  }
};
</script>

<template>
  <div>
    <PageHeader
      badge="Informasi"
      title="Unduhan Publik"
      description="Pusat dokumen, modul, dan formulir resmi SMAN 1 Nogosari yang dapat diakses dan diunduh oleh siswa maupun masyarakat umum."
    />

    <section class="py-0 md:py-12 bg-gray-50 dark:bg-gray-900 min-h-[60vh]">
      <div class="container mx-auto max-w-full px-0 md:px-8">
        <div
          class="flex flex-col lg:flex-row bg-white dark:bg-gray-800 rounded-none md:rounded-xl shadow-sm md:shadow-md border-y md:border border-gray-200 dark:border-slate-700 overflow-hidden min-h-[600px]"
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
          <div class="flex-1 flex flex-col bg-white dark:bg-gray-800 min-w-0">
            <!-- Toolbar Top -->
            <div
              class="px-5 py-4 border-b border-gray-200 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-gray-800"
            >
              <!-- Breadcrumb -->
              <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                <span class="hover:text-blue-600 cursor-pointer">Beranda</span>
                <PhCaretRight class="w-3.5 h-3.5 mx-2 text-gray-300 dark:text-gray-600" />
                <span class="hover:text-blue-600 cursor-pointer">Unduhan</span>
                <PhCaretRight class="w-3.5 h-3.5 mx-2 text-gray-300 dark:text-gray-600" />
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
              <!-- Loading State -->
              <div
                v-if="isLoading"
                class="h-full flex flex-col items-center justify-center text-center py-16"
              >
                <div
                  class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-4"
                ></div>
                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                  Memuat Data...
                </h4>
              </div>

              <!-- Empty State -->
              <div
                v-else-if="filteredFiles.length === 0"
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
                          @click="downloadFile(file)"
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
                    class="text-xs font-semibold text-gray-800 dark:text-gray-200 line-clamp-2 break-all w-full px-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                  >
                    {{ file.name }}
                  </span>
                  <span class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">
                    {{ file.size }}
                  </span>

                  <!-- Tombol Unduh Hover -->
                  <button
                    @click.stop="downloadFile(file)"
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
