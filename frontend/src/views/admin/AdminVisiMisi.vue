<script setup>
import { ref } from "vue";
import {
  PhPlusCircle,
  PhTrash,
  PhFloppyDisk,
  PhDotsSixVertical,
} from "@phosphor-icons/vue";
import RichTextEditor from "@/components/RichTextEditor.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

// Data dummy state. Nanti bisa diganti dengan fetch dari API
const visi = ref(
  "Terwujudnya Lulusan yang Bertaqwa, Berkarakter, Cerdas, Terampil, dan Berbudaya Lingkungan serta Mampu Bersaing di Era Global."
);

const misi = ref([
  {
    id: 1,
    text:
      "Menumbuhkembangkan keimanan dan ketaqwaan melalui pembiasaan kegiatan keagamaan di lingkungan sekolah.",
  },
  {
    id: 2,
    text:
      "Melaksanakan proses pembelajaran dan bimbingan secara efektif, inovatif, dan berpusat pada peserta didik.",
  },
  {
    id: 3,
    text:
      "Membina peserta didik untuk mengembangkan minat, bakat, dan potensi yang dimiliki melalui kegiatan ekstrakurikuler.",
  },
  {
    id: 4,
    text:
      "Mewujudkan lingkungan sekolah yang bersih, asri, aman, dan nyaman sebagai pilar utama pendukung proses belajar mengajar.",
  },
  {
    id: 5,
    text:
      "Meningkatkan kemampuan pemanfaatan teknologi informasi dan komunikasi untuk siap bersaing di tingkat global.",
  },
]);

const sambutan = ref(
  `<p>"Selamat datang di website resmi SMA Negeri 1 Nogosari. Kami berkomitmen untuk memberikan pendidikan berkualitas yang tidak hanya berfokus pada kecerdasan akademis, tetapi juga pembentukan karakter peserta didik yang berakhlak mulia dan berbudaya lingkungan."</p><br><p>Di era digital dan globalisasi saat ini, lembaga pendidikan memiliki tanggung jawab besar. Oleh karena itu, kami terus berinovasi dalam metode pembelajaran serta melengkapi berbagai fasilitas guna memastikan anak-anak kita siap menghadapi tantangan masa depan dengan bekal ilmu dan iman yang seimbang.</p><br><p>Kolaborasi yang erat antara guru, siswa, komite sekolah, dan masyarakat adalah kunci utama dari kesuksesan yang kita raih bersama. Melalui visi dan misi yang jelas, kami berharap dapat mewujudkan sekolah sebagai tempat yang menyenangkan untuk belajar dan bertumbuh.</p><br><p>Terima kasih atas dukungan dan kepercayaan Bapak/Ibu sekalian terhadap sekolah kami. Mari kita bersama-sama mewujudkan generasi penerus yang cerdas, terampil, dan siap berkontribusi bagi nusa dan bangsa.</p>`
);

const addMisi = () => {
  misi.value.push({ id: Date.now(), text: "" });
};

const removeMisi = (index) => {
  misi.value.splice(index, 1);
};

const isSaveModalOpen = ref(false);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000); // Otomatis hilang setelah 4 detik
};

const confirmSave = () => {
  // Logika untuk menyimpan ke API
  triggerToast(
    "Berhasil Disimpan!",
    "Pembaruan pada Visi, Misi, dan Sambutan Kepala Sekolah berhasil diterapkan ke sistem."
  );
  console.log({
    visi: visi.value,
    misi: misi.value,
    sambutan: sambutan.value,
  });
  isSaveModalOpen.value = false;
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

  const draggedItem = misi.value.splice(draggedMisiIndex.value, 1)[0];
  misi.value.splice(index, 0, draggedItem);
  draggedMisiIndex.value = null;
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
          Manajemen Profil Sekolah
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola konten Visi, Misi, dan Sambutan Kepala Sekolah
        </p>
      </div>
      <button
        @click="isSaveModalOpen = true"
        class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhFloppyDisk class="w-5 h-5 mr-2" />
        Simpan Perubahan
      </button>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
      <!-- Kolom Kiri: Visi & Misi -->
      <div class="xl:col-span-2 space-y-8">
        <!-- Form Visi -->
        <div
          class="bg-white dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm p-6"
        >
          <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            Visi Sekolah
          </h3>
          <textarea
            v-model="visi"
            rows="3"
            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 resize-none transition-colors text-sm sm:text-base"
            placeholder="Tuliskan visi sekolah di sini..."
          ></textarea>
        </div>

        <!-- Form Misi -->
        <div
          class="bg-white dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm p-6"
        >
          <div class="flex flex-wrap items-center justify-between mb-4 gap-2">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
              Misi Sekolah
            </h3>
            <button
              @click="addMisi"
              class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
            >
              <PhPlusCircle class="w-5 h-5 mr-1.5" /> Tambah Misi
            </button>
          </div>
          <div class="space-y-3">
            <div
              v-for="(item, index) in misi"
              :key="item.id"
              class="flex gap-3 items-start group"
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
                <PhDotsSixVertical class="w-6 h-6" />
              </div>
              <div class="flex-1">
                <textarea
                  v-model="item.text"
                  rows="2"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 resize-none transition-colors text-sm sm:text-base"
                  placeholder="Tuliskan butir misi..."
                ></textarea>
              </div>
              <button
                @click="removeMisi(index)"
                class="mt-1 p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors shrink-0"
                title="Hapus Misi"
              >
                <PhTrash class="w-5 h-5" />
              </button>
            </div>
            <div
              v-if="misi.length === 0"
              class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-lg"
            >
              Belum ada data misi. Silakan klik "Tambah Misi".
            </div>
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
          <div class="mb-4">
            <RichTextEditor
              v-model="sambutan"
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

    <!-- Modal Konfirmasi Simpan -->
    <ConfirmModal
      :isOpen="isSaveModalOpen"
      title="Simpan Perubahan"
      message="Apakah Anda yakin ingin menyimpan perubahan pada Visi, Misi, dan Sambutan Kepala Sekolah?"
      confirmText="Simpan"
      theme="primary"
      @confirm="confirmSave"
      @cancel="isSaveModalOpen = false"
    />

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
