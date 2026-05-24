<script setup>
import { ref, onMounted } from "vue";
import api from "@/api/index.js";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhDotsSixVertical,
  PhX,
  PhEye,
  PhTarget,
  PhMegaphone,
} from "@phosphor-icons/vue";
import RichTextEditor from "@/components/RichTextEditor.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

// Data State
const visi = ref("");
const misi = ref([]);
const sambutan = ref("");
const isLoading = ref(true);

// Edit State
const isModalOpen = ref(false);
const tempVisi = ref("");
const tempMisi = ref([]);
const tempSambutan = ref("");

const openEditModal = () => {
  tempVisi.value = visi.value;
  tempMisi.value = JSON.parse(JSON.stringify(misi.value || [])); // Deep copy
  tempSambutan.value = sambutan.value;
  isModalOpen.value = true;
  document.body.style.overflow = "hidden";
};

const closeEditModal = () => {
  isModalOpen.value = false;
  document.body.style.overflow = "";
};

const addMisi = () => {
  tempMisi.value.push({ id: Date.now(), text: "" });
};

const removeMisi = (index) => {
  tempMisi.value.splice(index, 1);
};

// Logika Drag and Drop untuk mengubah urutan Misi
const draggedMisiIndex = ref(null);

const handleDragStart = (index, event) => {
  draggedMisiIndex.value = index;
  event.dataTransfer.effectAllowed = "move";
};

const handleDrop = (index) => {
  if (draggedMisiIndex.value === null) return;
  if (draggedMisiIndex.value === index) return; // Mencegah drop pada dirinya sendiri

  const draggedItem = tempMisi.value.splice(draggedMisiIndex.value, 1)[0];
  tempMisi.value.splice(index, 0, draggedItem);
  draggedMisiIndex.value = null;
};

// Toast
const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000); // Otomatis hilang setelah 4 detik
};

const fetchData = async () => {
  isLoading.value = true;
  try {
    const response = await api.get("/api/vision-mission");
    const data = response.data.data;
    visi.value = data.vision || "";
    misi.value = data.missions || [];
    sambutan.value = data.principal_speech || "";
  } catch (error) {
    console.error("Gagal mengambil data Visi & Misi:", error);
    triggerToast("Gagal Memuat", "Tidak dapat memuat data dari server.", "error");
  } finally {
    isLoading.value = false;
  }
};

onMounted(fetchData);

const saveChanges = async () => {
  // Validate
  if (!tempVisi.value.trim()) {
    triggerToast("Gagal Menyimpan", "Visi tidak boleh kosong!", "error");
    return;
  }

  const payload = {
    vision: tempVisi.value,
    missions: tempMisi.value.filter((m) => m.text.trim() !== ""), // Filter out empty missions
    principal_speech: tempSambutan.value,
  };

  try {
    const response = await api.post("/api/vision-mission", payload);

    // Update local state with data from server response
    const data = response.data.data;
    visi.value = data.vision;
    misi.value = data.missions;
    sambutan.value = data.principal_speech;

    closeEditModal();
    triggerToast(
      "Berhasil Disimpan",
      "Data Visi, Misi, dan Sambutan berhasil diperbarui."
    );
  } catch (error) {
    console.error("Gagal menyimpan perubahan:", error);
    triggerToast(
      "Gagal Menyimpan",
      error.response?.data?.message || "Terjadi kesalahan pada server.",
      "error"
    );
  }
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2
          class="text-3xl font-bold text-gray-800 dark:text-white tracking-wide"
          style="font-family: 'Oswald', sans-serif"
        >
          Manajemen Profil Sekolah
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola konten Visi, Misi, dan Sambutan Kepala Sekolah
        </p>
      </div>
      <button
        @click="openEditModal"
        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPencilSimple class="w-5 h-5 mr-2" />
        Edit Data
      </button>
    </div>

    <!-- Skeleton Loader -->
    <div v-if="isLoading" class="grid grid-cols-1 xl:grid-cols-3 gap-8">
      <div class="xl:col-span-2 space-y-8">
        <div class="bg-gray-200 dark:bg-slate-700 rounded-lg h-48 animate-pulse"></div>
        <div class="bg-gray-200 dark:bg-slate-700 rounded-lg h-64 animate-pulse"></div>
      </div>
      <div class="bg-gray-200 dark:bg-slate-700 rounded-lg h-96 animate-pulse"></div>
    </div>

    <!-- View Mode -->
    <div v-else class="grid grid-cols-1 xl:grid-cols-3 gap-8">
      <!-- Kolom Kiri: Visi & Misi -->
      <div class="xl:col-span-2 space-y-8">
        <!-- Tampilan Visi -->
        <div
          class="bg-white dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm p-6"
        >
          <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            Visi Sekolah
          </h3>
          <p
            v-if="visi"
            class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg italic border-l-4 border-blue-500 pl-4 py-2 bg-blue-50/50 dark:bg-blue-900/20 rounded-r-lg"
          >
            "{{ visi }}"
          </p>
          <div
            v-else
            class="py-8 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
          >
            <div
              class="mx-auto w-12 h-12 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-3"
            >
              <PhEye class="w-6 h-6 text-gray-400 dark:text-gray-500" />
            </div>
            <p class="text-sm font-medium">Visi sekolah belum diatur.</p>
          </div>
        </div>

        <!-- Tampilan Misi -->
        <div
          class="bg-white dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm p-6"
        >
          <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            Misi Sekolah
          </h3>
          <ul v-if="misi && misi.length > 0" class="space-y-4">
            <li
              v-for="(item, index) in misi"
              :key="item.id || index"
              class="flex items-start"
            >
              <span
                class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm mr-4 mt-0.5"
              >
                {{ index + 1 }}
              </span>
              <p class="text-gray-700 dark:text-gray-300 leading-relaxed pt-1">
                {{ item.text }}
              </p>
            </li>
          </ul>
          <div
            v-else
            class="py-8 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
          >
            <div
              class="mx-auto w-12 h-12 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-3"
            >
              <PhTarget class="w-6 h-6 text-gray-400 dark:text-gray-500" />
            </div>
            <p class="text-sm font-medium">Misi sekolah belum diatur.</p>
          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Sambutan Kepala Sekolah -->
      <div class="xl:col-span-1">
        <div
          class="bg-white dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm p-6 sticky top-6"
        >
          <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            Sambutan Kepala Sekolah
          </h3>
          <div
            v-if="sambutan"
            class="prose dark:prose-invert max-w-none text-sm text-gray-700 dark:text-gray-300 leading-relaxed editor-content-preview custom-scrollbar overflow-y-auto max-h-[600px] pr-2"
            v-html="sambutan"
          ></div>
          <div
            v-else
            class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
          >
            <div
              class="mx-auto w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4"
            >
              <PhMegaphone class="w-8 h-8 text-gray-400 dark:text-gray-500" />
            </div>
            <p class="text-sm font-medium">Sambutan kepala sekolah belum diatur.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Form Edit -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closeEditModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all"
          @click.stop
        >
          <!-- Modal Header -->
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
              Edit Visi, Misi & Sambutan
            </h3>
            <button
              @click="closeEditModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>

          <!-- Modal Body -->
          <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <!-- Kiri: Visi & Misi -->
              <div class="space-y-6">
                <!-- Visi -->
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                  >
                    Visi Sekolah
                  </label>
                  <textarea
                    v-model="tempVisi"
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 resize-none transition-colors text-sm"
                    placeholder="Tuliskan visi sekolah di sini..."
                  ></textarea>
                </div>

                <!-- Misi -->
                <div>
                  <div class="flex items-center justify-between mb-2">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                      Misi Sekolah
                    </label>
                    <button
                      @click="addMisi"
                      type="button"
                      class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
                    >
                      <PhPlusCircle class="w-4 h-4 mr-1.5" /> Tambah Misi
                    </button>
                  </div>
                  <div class="space-y-3">
                    <div
                      v-for="(item, index) in tempMisi"
                      :key="item.id"
                      class="flex gap-2 items-start group bg-gray-50 dark:bg-slate-700/30 p-2 rounded-lg border border-gray-100 dark:border-slate-600"
                      draggable="true"
                      @dragstart="handleDragStart(index, $event)"
                      @dragover.prevent
                      @dragenter.prevent
                      @drop="handleDrop(index)"
                    >
                      <div
                        class="mt-1 text-gray-400 cursor-grab active:cursor-grabbing hover:text-gray-600 dark:hover:text-gray-300 shrink-0"
                        title="Tahan dan geser untuk memindahkan"
                      >
                        <PhDotsSixVertical class="w-5 h-5" />
                      </div>
                      <div class="flex-1">
                        <textarea
                          v-model="item.text"
                          rows="2"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 resize-none transition-colors text-sm"
                          placeholder="Tuliskan butir misi..."
                        ></textarea>
                      </div>
                      <button
                        @click="removeMisi(index)"
                        type="button"
                        class="mt-0.5 p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors shrink-0"
                        title="Hapus Misi"
                      >
                        <PhTrash class="w-4 h-4" />
                      </button>
                    </div>
                    <div
                      v-if="tempMisi.length === 0"
                      class="text-center py-4 text-gray-500 dark:text-gray-400 text-sm border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-lg"
                    >
                      Belum ada data misi. Silakan klik "Tambah Misi".
                    </div>
                  </div>
                </div>
              </div>

              <!-- Kanan: Sambutan -->
              <div class="flex flex-col">
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                >
                  Sambutan Kepala Sekolah
                </label>
                <div class="flex-1">
                  <RichTextEditor
                    v-model="tempSambutan"
                    placeholder="Tuliskan sambutan kepala sekolah..."
                  />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mt-4">
                  Teks sambutan ini akan tampil pada halaman
                  <strong>"Visi, Misi & Tujuan"</strong> di antarmuka publik website.
                </p>
              </div>
            </div>
          </div>

          <!-- Modal Footer -->
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              type="button"
              @click="closeEditModal"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
              <PhXCircle class="w-5 h-5 mr-2" />
              Batal
            </button>
            <button
              @click="saveChanges"
              type="button"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
              <PhFloppyDisk class="w-5 h-5 mr-2" />
              Simpan Perubahan
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Notifikasi Toast -->
    <ToastNotification
      :isOpen="showToast"
      :title="toastData.title"
      :message="toastData.message"
      :type="toastData.type"
      @close="showToast = false"
    />
  </main>
</template>
