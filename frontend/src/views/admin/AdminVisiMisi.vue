<script setup>
import { ref, onMounted, computed } from "vue";
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
  PhUser,
  PhQuotes,
} from "@phosphor-icons/vue";
import RichTextEditor from "@/components/RichTextEditor.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

// Data State
const visi = ref("");
const misi = ref([]);
const sambutan = ref("");
const principalId = ref(null);
const isLoading = ref(true);
const staffList = ref([]);

// Edit State
const isModalOpen = ref(false);
const tempVisi = ref("");
const tempMisi = ref([]);
const tempSambutan = ref("");
const tempPrincipalId = ref(null);

const selectedPrincipal = computed(() => {
  if (!principalId.value) return null;
  return staffList.value.find((s) => s.id === principalId.value) || null;
});

const openEditModal = () => {
  tempVisi.value = visi.value;
  tempMisi.value = JSON.parse(JSON.stringify(misi.value || [])); // Deep copy
  tempSambutan.value = sambutan.value;
  tempPrincipalId.value = principalId.value;
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

const fetchStaff = async () => {
  try {
    const response = await api.get("/api/guru-staf");
    staffList.value = response.data.data;
  } catch (error) {
    console.error("Gagal memuat daftar guru staf:", error);
  }
};

const fetchData = async () => {
  isLoading.value = true;
  try {
    const response = await api.get("/api/vision-mission");
    const data = response.data.data;
    visi.value = data.vision || "";
    misi.value = data.missions || [];
    sambutan.value = data.principal_speech || "";
    principalId.value = data.principal_id || null;
  } catch (error) {
    console.error("Gagal mengambil data Visi & Misi:", error);
    triggerToast("Gagal Memuat", "Tidak dapat memuat data dari server.", "error");
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchStaff();
  fetchData();
});

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
    principal_id: tempPrincipalId.value,
  };

  try {
    const response = await api.post("/api/vision-mission", payload);

    // Update local state with data from server response
    const data = response.data.data;
    visi.value = data.vision;
    misi.value = data.missions;
    sambutan.value = data.principal_speech;
    principalId.value = data.principal_id || payload.principal_id;

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
    <div v-if="isLoading" class="space-y-8">
      <div class="bg-gray-200 dark:bg-slate-700 rounded-lg h-64 animate-pulse"></div>
      <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <div class="bg-gray-200 dark:bg-slate-700 rounded-lg h-48 animate-pulse"></div>
        <div class="bg-gray-200 dark:bg-slate-700 rounded-lg h-64 animate-pulse"></div>
      </div>
    </div>

    <!-- View Mode -->
    <div v-else class="space-y-8">
      <!-- Section Sambutan (Paling Atas) -->
      <div
        class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 lg:p-8 flex flex-col md:flex-row gap-8 items-start relative overflow-hidden"
      >
        <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
          <PhQuotes class="w-48 h-48 text-blue-900" weight="fill" />
        </div>
        <div
          class="shrink-0 w-full md:w-72 flex flex-col items-center text-center relative z-10"
        >
          <div
            v-if="selectedPrincipal && selectedPrincipal.image"
            class="w-48 h-48 md:w-56 md:h-56 rounded-2xl overflow-hidden shadow-lg mb-5 border-4 border-white dark:border-slate-700 bg-gray-100"
          >
            <img
              :src="selectedPrincipal.image"
              alt="Kepala Sekolah"
              class="w-full h-full object-cover"
            />
          </div>
          <div
            v-else
            class="w-48 h-48 md:w-56 md:h-56 rounded-2xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center mb-5 shadow-lg border-4 border-white dark:border-slate-700"
          >
            <PhUser class="w-20 h-20 text-gray-400" />
          </div>
          <template v-if="selectedPrincipal">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
              {{ selectedPrincipal.name }}
            </h3>
            <p
              class="text-blue-600 dark:text-blue-400 font-semibold mt-1 bg-blue-50 dark:bg-blue-900/30 px-4 py-1 rounded-full text-sm inline-block"
            >
              {{ selectedPrincipal.position }}
            </p>
          </template>
          <template v-else>
            <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300">
              Profil Belum Dipilih
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
              Pilih pada mode edit
            </p>
          </template>
        </div>
        <div class="flex-1 w-full relative z-10">
          <div
            class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-slate-700"
          >
            <div class="p-2 bg-blue-50 dark:bg-blue-900/50 rounded-lg">
              <PhMegaphone class="w-6 h-6 text-blue-600 dark:text-blue-400" />
            </div>
            <h3
              class="text-2xl font-bold text-gray-800 dark:text-white"
              style="font-family: 'Oswald', sans-serif"
            >
              Sambutan Kepala Sekolah
            </h3>
          </div>
          <div
            v-if="sambutan"
            class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed editor-content-preview custom-scrollbar overflow-y-auto max-h-[500px] pr-4"
            v-html="sambutan"
          ></div>
          <div
            v-else
            class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
          >
            <p class="text-sm font-medium">Sambutan kepala sekolah belum diatur.</p>
          </div>
        </div>
      </div>

      <!-- Section Visi & Misi (Bawah) -->
      <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Tampilan Visi -->
        <div
          class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 lg:p-8 flex flex-col"
        >
          <div
            class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-slate-700"
          >
            <div class="p-2 bg-emerald-50 dark:bg-emerald-900/50 rounded-lg">
              <PhEye class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
            </div>
            <h3
              class="text-xl font-bold text-gray-800 dark:text-white"
              style="font-family: 'Oswald', sans-serif"
            >
              Visi Sekolah
            </h3>
          </div>
          <div class="flex-1 flex items-center justify-center">
            <p
              v-if="visi"
              class="text-gray-700 dark:text-gray-300 leading-relaxed text-xl lg:text-2xl font-medium italic text-center px-4"
            >
              "{{ visi }}"
            </p>
            <div
              v-else
              class="py-8 text-center text-gray-500 dark:text-gray-400 w-full border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
            >
              <p class="text-sm font-medium">Visi sekolah belum diatur.</p>
            </div>
          </div>
        </div>

        <!-- Tampilan Misi -->
        <div
          class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 lg:p-8"
        >
          <div
            class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-slate-700"
          >
            <div class="p-2 bg-red-50 dark:bg-red-900/50 rounded-lg">
              <PhTarget class="w-6 h-6 text-red-600 dark:text-red-400" />
            </div>
            <h3
              class="text-xl font-bold text-gray-800 dark:text-white"
              style="font-family: 'Oswald', sans-serif"
            >
              Misi Sekolah
            </h3>
          </div>
          <ul v-if="misi && misi.length > 0" class="space-y-4">
            <li
              v-for="(item, index) in misi"
              :key="item.id || index"
              class="flex items-start bg-gray-50 dark:bg-slate-700/30 p-4 rounded-xl border border-gray-100 dark:border-slate-700"
            >
              <span
                class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 flex items-center justify-center font-bold text-sm mr-4"
              >
                {{ index + 1 }}
              </span>
              <p
                class="text-gray-700 dark:text-gray-300 leading-relaxed text-sm lg:text-base pt-1"
              >
                {{ item.text }}
              </p>
            </li>
          </ul>
          <div
            v-else
            class="py-8 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
          >
            <p class="text-sm font-medium">Misi sekolah belum diatur.</p>
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
          <div
            class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-slate-800"
          >
            <div class="space-y-8">
              <!-- Pengaturan Sambutan -->
              <div
                class="bg-gray-50 dark:bg-slate-700/30 rounded-xl p-5 md:p-6 border border-gray-100 dark:border-slate-600"
              >
                <h4
                  class="text-lg font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2 pb-3 border-b border-gray-200 dark:border-slate-600"
                >
                  <PhMegaphone class="w-5 h-5 text-blue-500" /> Pengaturan Sambutan
                </h4>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                  <div class="lg:col-span-1 flex flex-col">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                      Pilih Profil Pemberi Sambutan
                    </label>
                    <select
                      v-model="tempPrincipalId"
                      class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm mb-4"
                    >
                      <option :value="null">-- Pilih Dari Guru & Staf --</option>
                      <option
                        v-for="staff in staffList"
                        :key="staff.id"
                        :value="staff.id"
                      >
                        {{ staff.name }} ({{ staff.position }})
                      </option>
                    </select>
                    <div
                      v-if="tempPrincipalId"
                      class="mt-2 p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-600 flex items-center gap-4 shadow-sm"
                    >
                      <div
                        class="w-14 h-14 rounded-full overflow-hidden shrink-0 bg-gray-100 border-2 border-gray-100 dark:border-slate-700"
                      >
                        <img
                          v-if="staffList.find((s) => s.id === tempPrincipalId)?.image"
                          :src="staffList.find((s) => s.id === tempPrincipalId).image"
                          class="w-full h-full object-cover"
                        />
                        <PhUser v-else class="w-6 h-6 m-auto mt-4 text-gray-400" />
                      </div>
                      <div class="overflow-hidden">
                        <p
                          class="text-sm font-bold text-gray-800 dark:text-white truncate"
                        >
                          {{ staffList.find((s) => s.id === tempPrincipalId)?.name }}
                        </p>
                        <p
                          class="text-xs text-blue-600 dark:text-blue-400 truncate mt-0.5"
                        >
                          {{ staffList.find((s) => s.id === tempPrincipalId)?.position }}
                        </p>
                      </div>
                    </div>
                    <p
                      class="text-xs text-gray-500 dark:text-gray-400 mt-4 leading-relaxed"
                    >
                      Pilih dari data Guru & Staf untuk otomatis menampilkan nama,
                      jabatan, dan foto pada sambutan Kepala Sekolah.
                    </p>
                  </div>
                  <div class="lg:col-span-2">
                    <label
                      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                      Teks Sambutan
                    </label>
                    <div
                      class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 overflow-hidden"
                    >
                      <RichTextEditor
                        v-model="tempSambutan"
                        placeholder="Tuliskan isi pesan atau sambutan kepala sekolah..."
                      />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Pengaturan Visi & Misi -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Visi -->
                <div
                  class="bg-gray-50 dark:bg-slate-700/30 rounded-xl p-5 md:p-6 border border-gray-100 dark:border-slate-600"
                >
                  <h4
                    class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2 pb-3 border-b border-gray-200 dark:border-slate-600"
                  >
                    <PhEye class="w-5 h-5 text-emerald-500" /> Visi Sekolah
                  </h4>
                  <textarea
                    v-model="tempVisi"
                    rows="6"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 resize-none transition-colors text-sm"
                    placeholder="Tuliskan rumusan visi sekolah di sini..."
                  ></textarea>
                </div>

                <!-- Misi -->
                <div
                  class="bg-gray-50 dark:bg-slate-700/30 rounded-xl p-5 md:p-6 border border-gray-100 dark:border-slate-600"
                >
                  <div
                    class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200 dark:border-slate-600"
                  >
                    <h4
                      class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2"
                    >
                      <PhTarget class="w-5 h-5 text-red-500" /> Misi Sekolah
                    </h4>
                    <button
                      @click="addMisi"
                      type="button"
                      class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-white bg-red-500 dark:bg-red-600 rounded-md hover:bg-red-600 dark:hover:bg-red-700 transition-colors shadow-sm"
                    >
                      <PhPlusCircle class="w-4 h-4 mr-1.5" /> Tambah Misi
                    </button>
                  </div>
                  <div
                    class="space-y-3 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar"
                  >
                    <div
                      v-for="(item, index) in tempMisi"
                      :key="item.id"
                      class="flex gap-3 items-start group bg-white dark:bg-slate-800 p-3 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm"
                      draggable="true"
                      @dragstart="handleDragStart(index, $event)"
                      @dragover.prevent
                      @dragenter.prevent
                      @drop="handleDrop(index)"
                    >
                      <div
                        class="mt-2 text-gray-400 cursor-grab active:cursor-grabbing hover:text-gray-600 dark:hover:text-gray-300 shrink-0"
                        title="Tahan dan geser untuk memindahkan"
                      >
                        <PhDotsSixVertical class="w-5 h-5" />
                      </div>
                      <div class="flex-1">
                        <textarea
                          v-model="item.text"
                          rows="2"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-md bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-red-500 focus:border-red-500 resize-none transition-colors text-sm"
                          placeholder="Tuliskan butir misi..."
                        ></textarea>
                      </div>
                      <button
                        @click="removeMisi(index)"
                        type="button"
                        class="mt-2 p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors shrink-0"
                        title="Hapus Misi"
                      >
                        <PhTrash class="w-4 h-4" />
                      </button>
                    </div>
                    <div
                      v-if="tempMisi.length === 0"
                      class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-lg"
                    >
                      Belum ada data misi. Silakan klik "Tambah Misi" untuk mulai mengisi.
                    </div>
                  </div>
                </div>
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
