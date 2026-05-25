<script setup>
import { ref, onMounted } from "vue";
import api from "@/api/index.js";
import {
  PhCheckCircle,
  PhXCircle,
  PhEye,
  PhNewspaper,
  PhImage,
  PhX,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const activeTab = ref("berita");

// Data dummy untuk Berita yang menunggu persetujuan
const pendingBerita = ref([]);

// Data dummy untuk Galeri yang menunggu persetujuan
const pendingGaleri = ref([
  {
    id: 1,
    title: "Upacara Hari Kemerdekaan RI Ke-80",
    author: "Dian (Admin Fotografi)",
    date: "17 Agustus 2025",
    status: "pending",
    imagesCount: 15,
  },
]);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });

const isConfirmModalOpen = ref(false);
const confirmActionType = ref(""); // 'approve' | 'reject'
const selectedItem = ref(null);

const isRejectModalOpen = ref(false);
const rejectionNote = ref("");

const isPreviewModalOpen = ref(false);
const previewItem = ref(null);

const fetchPendingBerita = async () => {
  try {
    const response = await api.get("/api/validasi-konten/berita");
    pendingBerita.value = response.data.data || [];
  } catch (error) {
    console.error("Gagal mengambil data validasi berita:", error);
  }
};

onMounted(() => {
  fetchPendingBerita();
});

const openPreview = (item) => {
  previewItem.value = item;
  isPreviewModalOpen.value = true;
};

const closePreview = () => {
  isPreviewModalOpen.value = false;
  previewItem.value = null;
};

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

const openConfirm = (type, item) => {
  if (type === "reject") {
    selectedItem.value = item;
    rejectionNote.value = "";
    isRejectModalOpen.value = true;
  } else {
    confirmActionType.value = type;
    selectedItem.value = item;
    isConfirmModalOpen.value = true;
  }
};

const handleConfirm = async () => {
  if (selectedItem.value) {
    const list = activeTab.value === "berita" ? pendingBerita : pendingGaleri;
    const index = list.value.findIndex((i) => i.id === selectedItem.value.id);

    if (index !== -1) {
      if (confirmActionType.value === "approve") {
        try {
          if (activeTab.value === "berita") {
            await api.put(`/api/validasi-konten/berita/${selectedItem.value.id}/status`, {
              status: "approved",
            });
          }
          list.value.splice(index, 1);
          triggerToast(
            "Berhasil Disetujui",
            "Konten berhasil disetujui dan telah dipublikasikan.",
            "success"
          );
        } catch (error) {
          triggerToast("Gagal", "Terjadi kesalahan pada server", "error");
        }
      }
    }
  }
  isConfirmModalOpen.value = false;
  selectedItem.value = null;
};

const handleCancel = () => {
  isConfirmModalOpen.value = false;
  selectedItem.value = null;
};

const handleReject = async () => {
  if (selectedItem.value) {
    const list = activeTab.value === "berita" ? pendingBerita : pendingGaleri;
    const index = list.value.findIndex((i) => i.id === selectedItem.value.id);

    if (index !== -1) {
      try {
        if (activeTab.value === "berita") {
          await api.put(`/api/validasi-konten/berita/${selectedItem.value.id}/status`, {
            status: "rejected",
            rejection_note: rejectionNote.value,
          });
        }
        list.value.splice(index, 1);
        triggerToast(
          "Konten Ditolak",
          rejectionNote.value.trim()
            ? `Konten dikembalikan dengan catatan: "${rejectionNote.value}"`
            : "Konten telah ditolak dan dikembalikan.",
          "error"
        );
      } catch (error) {
        triggerToast("Gagal", "Terjadi kesalahan pada server", "error");
      }
    }
  }
  closeRejectModal();
};

const closeRejectModal = () => {
  isRejectModalOpen.value = false;
  selectedItem.value = null;
  rejectionNote.value = "";
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8">
      <h2
        class="text-3xl font-bold text-gray-800 dark:text-white"
        style="font-family: 'Oswald', sans-serif"
      >
        Validasi Konten
      </h2>
      <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
        Tinjau dan setujui berita atau foto galeri yang diunggah oleh admin (guru/staf)
        sebelum dipublikasikan ke halaman utama.
      </p>
    </div>

    <!-- Sistem Tabs -->
    <div class="flex gap-4 border-b border-gray-200 dark:border-slate-700 mb-6">
      <button
        @click="activeTab = 'berita'"
        class="pb-3 px-2 text-sm font-semibold transition-colors relative"
        :class="
          activeTab === 'berita'
            ? 'text-blue-600 dark:text-blue-400'
            : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
        "
      >
        <div class="flex items-center gap-2">
          <PhNewspaper class="w-5 h-5" />
          Berita & Artikel
          <span
            v-if="pendingBerita.length > 0"
            class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full"
          >
            {{ pendingBerita.length }}
          </span>
        </div>
        <div
          v-if="activeTab === 'berita'"
          class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 dark:bg-blue-400 rounded-t-full"
        ></div>
      </button>

      <button
        @click="activeTab = 'galeri'"
        class="pb-3 px-2 text-sm font-semibold transition-colors relative"
        :class="
          activeTab === 'galeri'
            ? 'text-blue-600 dark:text-blue-400'
            : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
        "
      >
        <div class="flex items-center gap-2">
          <PhImage class="w-5 h-5" />
          Galeri Foto
          <span
            v-if="pendingGaleri.length > 0"
            class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full"
          >
            {{ pendingGaleri.length }}
          </span>
        </div>
        <div
          v-if="activeTab === 'galeri'"
          class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 dark:bg-blue-400 rounded-t-full"
        ></div>
      </button>
    </div>

    <!-- Daftar Tabel Universal untuk Kedua Tab -->
    <div
      class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden"
    >
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr
              class="bg-gray-50 dark:bg-slate-700/50 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              <th class="px-6 py-4">Judul Konten</th>
              <th class="px-6 py-4">Penulis / Pengunggah</th>
              <th class="px-6 py-4">Info Ekstra</th>
              <th class="px-6 py-4">Tanggal Diunggah</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            <tr
              v-if="(activeTab === 'berita' ? pendingBerita : pendingGaleri).length === 0"
            >
              <td
                colspan="5"
                class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
              >
                <PhCheckCircle class="w-12 h-12 mx-auto text-green-500/50 mb-3" />
                <p>
                  Bagus! Semua daftar
                  {{ activeTab === "berita" ? "berita" : "galeri foto" }} sudah divalidasi
                  dan bersih.
                </p>
              </td>
            </tr>
            <tr
              v-for="item in activeTab === 'berita' ? pendingBerita : pendingGaleri"
              :key="item.id"
              class="transition-colors"
              :class="{
                'bg-green-50/50 dark:bg-green-900/10': item.status === 'approved',
                'bg-red-50/50 dark:bg-red-900/10': item.status === 'rejected',
                'hover:bg-blue-50/50 dark:hover:bg-slate-700/30':
                  item.status === 'pending',
              }"
            >
              <td
                class="px-6 py-4 text-sm font-semibold"
                :class="{
                  'text-green-600 dark:text-green-400': item.status === 'approved',
                  'text-red-600 dark:text-red-400': item.status === 'rejected',
                  'text-gray-800 dark:text-gray-200': item.status === 'pending',
                }"
              >
                {{ item.title }}
                <span
                  v-if="item.status === 'approved'"
                  class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400"
                >
                  Disetujui
                </span>
                <span
                  v-if="item.status === 'rejected'"
                  class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400"
                >
                  Ditolak
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                {{ item.author?.name || item.author || "Admin" }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                <span
                  v-if="activeTab === 'berita'"
                  class="px-2 py-1 bg-gray-100 dark:bg-slate-700 rounded text-xs uppercase"
                  >{{ item.category }}</span
                >
                <span v-else class="text-xs font-medium"
                  >{{ item.imagesCount }} Foto</span
                >
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                {{ item.date || new Date(item.created_at).toLocaleDateString("id-ID") }}
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button
                    @click="openPreview(item)"
                    class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors"
                    title="Lihat Pratinjau"
                  >
                    <PhEye class="w-4 h-4" />
                  </button>
                  <button
                    v-if="item.status === 'pending'"
                    @click="openConfirm('approve', item)"
                    class="p-1.5 text-green-600 hover:bg-green-100 dark:hover:bg-green-900/50 rounded-md transition-colors"
                    title="Setujui (Publikasi)"
                  >
                    <PhCheckCircle class="w-4 h-4" />
                  </button>
                  <button
                    v-if="item.status === 'pending'"
                    @click="openConfirm('reject', item)"
                    class="p-1.5 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-md transition-colors"
                    title="Tolak Konten"
                  >
                    <PhXCircle class="w-4 h-4" />
                  </button>
                  <span
                    v-if="item.status !== 'pending'"
                    class="text-xs text-gray-400 italic px-1"
                    >Selesai</span
                  >
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Komponen Modal & Notifikasi -->
    <ConfirmModal
      :isOpen="isConfirmModalOpen"
      title="Setujui & Publikasikan"
      message="Apakah Anda yakin ingin menyetujui konten ini? Setelah disetujui, konten akan langsung tampil di halaman depan website."
      @confirm="handleConfirm"
      @cancel="handleCancel"
    />

    <!-- Modal Catatan Penolakan -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isRejectModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closeRejectModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-red-50 dark:bg-red-900/20"
          >
            <h3
              class="text-xl font-bold text-red-600 dark:text-red-400 flex items-center"
            >
              <PhXCircle class="w-6 h-6 mr-2" />
              Berikan Catatan Penolakan
            </h3>
            <button
              @click="closeRejectModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6">
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
              Beritahu penulis mengapa konten ini ditolak agar dapat diperbaiki:
            </p>
            <textarea
              v-model="rejectionNote"
              rows="4"
              class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-red-500 focus:border-red-500 transition-colors"
              placeholder="Contoh: Kualitas foto kurang bagus, atau judul terlalu provokatif..."
            ></textarea>
          </div>
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              @click="closeRejectModal"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors"
            >
              Batal
            </button>
            <button
              @click="handleReject"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 transition-colors"
            >
              <PhXCircle class="w-5 h-5 mr-2" />
              Tolak Konten
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Modal Pratinjau Konten -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isPreviewModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closePreview"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all flex flex-col max-h-[90vh]"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 shrink-0"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
              <PhEye class="w-6 h-6 mr-2 text-blue-600" />
              Pratinjau Konten
            </h3>
            <button
              @click="closePreview"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>

          <div
            class="p-0 flex-1 overflow-y-auto custom-scrollbar relative bg-white dark:bg-slate-800"
            v-if="previewItem"
          >
            <!-- Hero Image (Jika Ada) -->
            <div
              v-if="previewItem.images && previewItem.images.length > 0"
              class="relative w-full h-64 sm:h-80 bg-gray-100 dark:bg-slate-700 shrink-0"
            >
              <img :src="previewItem.images[0]" class="w-full h-full object-cover" />
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"
              ></div>
              <div class="absolute bottom-0 left-0 p-6 sm:p-8 w-full">
                <span
                  class="inline-block px-3 py-1 bg-blue-600 text-white text-[10px] font-bold uppercase tracking-wider rounded-full mb-3 shadow-sm"
                >
                  {{
                    previewItem.category || (activeTab === "berita" ? "Berita" : "Galeri")
                  }}
                </span>
                <h2
                  class="text-2xl sm:text-3xl font-bold text-white leading-tight drop-shadow-md"
                >
                  {{ previewItem.title }}
                </h2>
              </div>
            </div>

            <!-- Area Konten -->
            <div class="p-6 sm:p-8">
              <!-- Info Header (Jika tidak ada gambar hero) -->
              <div
                v-if="!previewItem.images || previewItem.images.length === 0"
                class="mb-6 border-b border-gray-100 dark:border-slate-700 pb-6"
              >
                <span
                  class="inline-block px-3 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-[10px] font-bold uppercase tracking-wider rounded-full mb-3"
                >
                  {{
                    previewItem.category || (activeTab === "berita" ? "Berita" : "Galeri")
                  }}
                </span>
                <h2
                  class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white leading-tight mb-4"
                >
                  {{ previewItem.title }}
                </h2>
                <div
                  class="flex items-center text-sm text-gray-500 dark:text-gray-400 gap-3"
                >
                  <div
                    class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-700 dark:text-blue-300 font-bold shrink-0"
                  >
                    {{
                      (previewItem.author?.name || previewItem.author || "A")
                        .charAt(0)
                        .toUpperCase()
                    }}
                  </div>
                  <div class="flex flex-col">
                    <span class="font-medium text-gray-900 dark:text-white"
                      >Ditulis oleh
                      {{
                        previewItem.author?.name || previewItem.author || "Admin"
                      }}</span
                    >
                    <span class="text-xs">{{
                      previewItem.date ||
                      new Date(
                        previewItem.created_at || Date.now()
                      ).toLocaleDateString("id-ID", {
                        day: "numeric",
                        month: "long",
                        year: "numeric",
                      })
                    }}</span>
                  </div>
                </div>
              </div>

              <!-- Info Penulis (Jika hero image ada) -->
              <div
                v-else
                class="flex items-center text-sm text-gray-500 dark:text-gray-400 gap-3 mb-8 border-b border-gray-100 dark:border-slate-700 pb-6"
              >
                <div
                  class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-700 dark:text-blue-300 font-bold shrink-0 shadow-sm border border-blue-200 dark:border-blue-800/50"
                >
                  {{
                    (previewItem.author?.name || previewItem.author || "A")
                      .charAt(0)
                      .toUpperCase()
                  }}
                </div>
                <div class="flex flex-col">
                  <span class="font-medium text-gray-900 dark:text-white"
                    >Ditulis oleh
                    {{ previewItem.author?.name || previewItem.author || "Admin" }}</span
                  >
                  <span class="text-xs">{{
                    previewItem.date ||
                    new Date(
                      previewItem.created_at || Date.now()
                    ).toLocaleDateString("id-ID", {
                      day: "numeric",
                      month: "long",
                      year: "numeric",
                    })
                  }}</span>
                </div>
              </div>

              <!-- Konten Berita -->
              <div
                v-if="activeTab === 'berita'"
                class="text-gray-700 dark:text-gray-300 leading-relaxed space-y-4"
              >
                <div
                  v-if="previewItem.content"
                  v-html="previewItem.content"
                  class="content-preview"
                ></div>
                <div
                  v-else
                  class="py-12 text-center text-gray-400 italic border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
                >
                  Tidak ada detail isi konten yang dilampirkan.
                </div>

                <div
                  v-if="previewItem.tags"
                  class="mt-8 pt-6 border-t border-gray-100 dark:border-slate-700 flex flex-wrap gap-2"
                >
                  <span
                    v-for="tag in typeof previewItem.tags === 'string'
                      ? previewItem.tags.split(',')
                      : previewItem.tags"
                    :key="tag"
                    class="px-3 py-1.5 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors cursor-default"
                  >
                    #{{ tag.trim() }}
                  </span>
                </div>
              </div>

              <!-- Konten Galeri -->
              <div v-if="activeTab === 'galeri'">
                <div
                  v-if="previewItem.images && previewItem.images.length > 1"
                  class="grid grid-cols-2 sm:grid-cols-3 gap-3 md:gap-4 mt-2"
                >
                  <div
                    v-for="(img, idx) in previewItem.images.slice(1)"
                    :key="idx"
                    class="aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-slate-700 shadow-sm relative group cursor-pointer"
                  >
                    <img
                      :src="img"
                      class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    />
                    <div
                      class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"
                    ></div>
                  </div>
                </div>
                <div
                  v-else-if="!previewItem.images || previewItem.images.length <= 1"
                  class="py-12 text-center text-gray-400 italic border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
                >
                  Tidak ada foto tambahan di galeri ini.
                </div>
              </div>
            </div>
          </div>

          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3 shrink-0"
          >
            <button
              @click="closePreview"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors"
            >
              Tutup Pratinjau
            </button>
            <button
              v-if="previewItem && previewItem.status === 'pending'"
              @click="
                closePreview();
                openConfirm('approve', previewItem);
              "
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 transition-colors"
            >
              <PhCheckCircle class="w-5 h-5 mr-2" /> Setujui Konten
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <ToastNotification
      :isOpen="showToast"
      :title="toastData.title"
      :message="toastData.message"
      :type="toastData.type"
      @close="showToast = false"
    />
  </main>
</template>

<style scoped>
/* Basic styling untuk v-html content supaya teks rapi tanpa harus menginstall Tailwind Typography */
:deep(.content-preview p) {
  margin-bottom: 1rem;
}
:deep(.content-preview h1),
:deep(.content-preview h2),
:deep(.content-preview h3) {
  font-weight: 700;
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
  color: inherit;
}
:deep(.content-preview ul) {
  list-style-type: disc;
  padding-left: 1.5rem;
  margin-bottom: 1rem;
}
:deep(.content-preview ol) {
  list-style-type: decimal;
  padding-left: 1.5rem;
  margin-bottom: 1rem;
}
:deep(.content-preview img) {
  border-radius: 0.5rem;
  max-width: 100%;
  height: auto;
  margin: 1.5rem auto;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}
</style>
