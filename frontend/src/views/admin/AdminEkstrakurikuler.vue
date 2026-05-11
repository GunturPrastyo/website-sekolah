<script setup>
import { ref, computed } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhUsers,
  PhClock,
} from "@phosphor-icons/vue";
import ImageUploader from "@/components/admin/ImageUploader.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const categories = [
  { id: "olahraga", name: "Olahraga" },
  { id: "seni", name: "Seni & Budaya" },
  { id: "akademik", name: "Akademik & Sains" },
  { id: "kepemimpinan", name: "Kepemimpinan" },
];

const ekskulList = ref([
  {
    id: 1,
    name: "Pramuka",
    category: "kepemimpinan",
    image: "https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=800",
    schedule: "Jumat, 15.00 - 17.00",
    desc: "Membentuk karakter disiplin, mandiri, tangguh, dan berjiwa kepemimpinan melalui kegiatan kepramukaan.",
    story: "Pramuka SMAN 1 Nogosari bukan sekadar ekstrakurikuler biasa. Di sini, kami adalah keluarga yang saling mendukung dan tumbuh bersama...",
    pembina: "Bapak Rudi Hermawan, S.Pd",
    members: 124,
    socials: { ig: "@pramuka_sman1", yt: "Pramuka SMAN 1", email: "pramuka@sman1nogosari.sch.id" },
  },
  {
    id: 2,
    name: "Bola Basket",
    category: "olahraga",
    image: "https://images.unsplash.com/photo-1546519638-68e109498ffc?q=80&w=800",
    schedule: "Senin & Rabu, 15.30 - 17.30",
    desc: "Mengembangkan bakat olahraga bola basket, melatih kerjasama tim, dan menjaga kebugaran fisik.",
    story: "Di lapangan ini, keringat dan kerja keras diubah menjadi prestasi gemilang...",
    pembina: "Bapak Dwi Saputra, S.Or",
    members: 32,
    socials: { ig: "@basket_sman1", yt: "Basket SMAN 1", email: "" },
  }
]);

const form = ref({
  id: null,
  name: "",
  category: "olahraga",
  image: "",
  schedule: "",
  desc: "",
  story: "",
  pembina: "",
  members: 0,
  socials: {
    ig: "",
    yt: "",
    email: ""
  }
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

const resetForm = () => {
  form.value = {
    id: null,
    name: "",
    category: "olahraga",
    image: "",
    schedule: "",
    desc: "",
    story: "",
    pembina: "",
    members: 0,
    socials: { ig: "", yt: "", email: "" }
  };
  isEditing.value = false;
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
};

const addEntry = () => {
  if (!form.value.name || !form.value.category) {
    triggerToast("Gagal Menyimpan", "Nama dan Kategori wajib diisi!", "error");
    return;
  }
  const newId = ekskulList.value.length > 0 ? Math.max(...ekskulList.value.map(s => s.id)) + 1 : 1;
  const newEntry = { ...form.value, id: newId, socials: { ...form.value.socials } };
  ekskulList.value.push(newEntry);

  isFormVisible.value = false;
  triggerToast("Berhasil Ditambahkan", "Data ekstrakurikuler baru telah ditambahkan ke sistem.");
  resetForm();
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = { ...item, socials: { ...(item.socials || { ig: "", yt: "", email: "" }) } };
  isFormVisible.value = true;
  window.scrollTo({ top: 0, behavior: "smooth" });
};

const saveEntry = () => {
  if (!form.value.name || !form.value.category) {
    triggerToast("Gagal Menyimpan", "Nama dan Kategori wajib diisi!", "error");
    return;
  }
  const index = ekskulList.value.findIndex(s => s.id === form.value.id);
  if (index !== -1) {
    ekskulList.value[index] = { ...form.value, socials: { ...form.value.socials } };
  }

  isFormVisible.value = false;
  triggerToast("Perubahan Disimpan", "Data ekstrakurikuler berhasil diperbarui.");
  resetForm();
};

const hideForm = () => {
  resetForm();
  isFormVisible.value = false;
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
  if (itemToDelete.value !== null) {
    ekskulList.value = ekskulList.value.filter(s => s.id !== itemToDelete.value);
    itemToDelete.value = null;
    triggerToast("Data Dihapus", "Data ekstrakurikuler berhasil dihapus dari sistem.", "info");
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

const filteredEkskul = computed(() => {
  if (!searchQuery.value) return ekskulList.value;
  const query = searchQuery.value.toLowerCase();
  return ekskulList.value.filter(
    (item) => item.name.toLowerCase().includes(query) || item.category.toLowerCase().includes(query)
  );
});

const getCategoryName = (id) => {
  const cat = categories.find(c => c.id === id);
  return cat ? cat.name : id;
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white" style="font-family: 'Oswald', sans-serif">
          Manajemen Ekstrakurikuler
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data ekstrakurikuler, pembina, dan jadwal kegiatan siswa.
        </p>
      </div>
      <button v-if="!isFormVisible" @click="showAddForm" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Ekstrakurikuler
      </button>
    </div>

    <!-- Form -->
    <Transition enter-active-class="transition-all duration-500 ease-out" leave-active-class="transition-all duration-300 ease-in" enter-from-class="opacity-0 -translate-y-4 max-h-0" enter-to-class="opacity-100 translate-y-0 max-h-[2000px]" leave-from-class="opacity-100 translate-y-0 max-h-[2000px]" leave-to-class="opacity-0 -translate-y-4 max-h-0">
      <div v-if="isFormVisible" class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm mb-8">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-6 border-b border-gray-100 dark:border-slate-700 pb-3">
          {{ isEditing ? 'Edit Data Ekstrakurikuler' : 'Tambah Data Ekstrakurikuler Baru' }}
        </h3>
        <form @submit.prevent="isEditing ? saveEntry() : addEntry()">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
              <ImageUploader v-model="form.image" label="Gambar Utama" containerClass="w-full aspect-[3/4] sm:aspect-[3/4] mx-auto" imageClass="object-cover rounded-xl" />
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Ekstrakurikuler</label>
                <input type="text" v-model="form.name" required class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Pramuka" />
              </div>

              <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                <select v-model="form.category" required class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>

              <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jadwal</label>
                <input type="text" v-model="form.schedule" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Jumat, 15.00 - 17.00" />
              </div>

              <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pembina</label>
                <input type="text" v-model="form.pembina" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Nama Guru Pembina" />
              </div>

              <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Anggota</label>
                <input type="number" v-model.number="form.members" min="0" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="0" />
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi Singkat</label>
                <textarea v-model="form.desc" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Deskripsi singkat yang tampil di card"></textarea>
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cerita / Detail (Story)</label>
                <textarea v-model="form.story" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Cerita lengkap ekstrakurikuler"></textarea>
              </div>

              <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instagram</label>
                  <input type="text" v-model="form.socials.ig" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="@username" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">YouTube</label>
                  <input type="text" v-model="form.socials.yt" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Nama Channel" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                  <input type="email" v-model="form.socials.email" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="email@sekolah.com" />
                </div>
              </div>
            </div>
          </div>

          <div class="flex gap-3 mt-6 justify-end border-t border-gray-100 dark:border-slate-700 pt-6">
            <button type="button" @click="hideForm" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              <PhXCircle class="w-5 h-5 mr-2" /> Batal
            </button>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
              <PhPlusCircle v-else class="w-5 h-5 mr-2" />
              {{ isEditing ? 'Simpan Perubahan' : 'Simpan Data' }}
            </button>
          </div>
        </form>
      </div>
    </Transition>

    <!-- List -->
    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm">
      <div class="mb-6 relative max-w-md">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
        </div>
        <input type="text" v-model="searchQuery" placeholder="Cari nama ekstrakurikuler atau kategori..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
      </div>

      <div v-if="filteredEkskul.length === 0" class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl">
        <p>Tidak ada ekstrakurikuler yang ditemukan.</p>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="ekskul in filteredEkskul" :key="ekskul.id" class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm flex flex-col overflow-hidden relative group">
          <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 p-1 rounded-lg border border-gray-100 dark:border-slate-700 z-10">
            <button @click="startEdit(ekskul)" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md">
              <PhPencilSimple class="w-4 h-4" />
            </button>
            <button @click="deleteEntry(ekskul.id)" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md">
              <PhTrash class="w-4 h-4" />
            </button>
          </div>

          <div class="w-full aspect-[4/3] bg-gray-100 dark:bg-slate-700 relative">
            <img v-if="ekskul.image" :src="ekskul.image" class="w-full h-full object-cover" />
            <div class="absolute bottom-3 left-3 bg-blue-600/90 backdrop-blur-sm px-2.5 py-1 text-white text-[10px] font-bold uppercase tracking-wider rounded">
              {{ getCategoryName(ekskul.category) }}
            </div>
          </div>

          <div class="p-4 flex flex-col flex-1">
            <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-1">{{ ekskul.name }}</h4>
            <p class="text-sm text-blue-600 dark:text-blue-400 mb-3 flex items-center">
              <PhClock class="w-4 h-4 mr-1" /> {{ ekskul.schedule || '-' }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 flex-1 mb-4">
              {{ ekskul.desc }}
            </p>
            
            <div class="flex items-center justify-between border-t border-gray-100 dark:border-slate-700 pt-3">
              <span class="text-xs text-gray-500 dark:text-gray-400 font-medium truncate pr-2" :title="ekskul.pembina">
                {{ ekskul.pembina || 'Belum ada pembina' }}
              </span>
              <span class="flex items-center text-xs font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded">
                <PhUsers class="w-3.5 h-3.5 mr-1" /> {{ ekskul.members }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <ConfirmModal :isOpen="isDeleteModalOpen" title="Hapus Data Ekstrakurikuler" message="Yakin ingin menghapus data ekstrakurikuler ini?" @confirm="confirmDelete" @cancel="cancelDelete" />
    <ToastNotification :isOpen="showToast" :title="toastData.title" :message="toastData.message" :type="toastData.type" @close="showToast = false" />
  </main>
</template>

<style scoped>
.transition-all {
  overflow: hidden;
}
</style>