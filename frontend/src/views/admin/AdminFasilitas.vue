<script setup>
import { ref, computed } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhCaretRight,
  PhImage,
  PhSquaresFour,
  PhX
} from "@phosphor-icons/vue";
import ImageUploader from "@/components/admin/ImageUploader.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const categories = ref([
  {
    id: 1,
    name: "Ruang Kelas",
    description: "Ruang kelas dirancang untuk menciptakan suasana belajar yang nyaman, interaktif, dan mendukung konsentrasi siswa dalam mengikuti pembelajaran.",
    items: [
      {
        id: 101,
        title: "Ruang Kelas Modern",
        text: "Setiap ruang kelas dilengkapi dengan fasilitas modern seperti proyektor, papan interaktif, dan ventilasi yang baik. Ruangan kami didesain khusus agar pencahayaan dari luar masuk dengan sempurna.",
        images: ["https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=800"]
      },
      {
        id: 102,
        title: "Suasana Interaktif",
        text: "Lingkungan belajar mendorong siswa aktif berdiskusi dan berkolaborasi.",
        images: ["https://images.unsplash.com/photo-1594434533439-04c3a735d359?q=80&w=800"]
      }
    ]
  },
  {
    id: 2,
    name: "Laboratorium",
    description: "Laboratorium menjadi sarana penting dalam mendukung pembelajaran berbasis praktik dan eksperimen.",
    items: [
      {
        id: 201,
        title: "Lab Sains",
        text: "Fasilitas lengkap untuk eksperimen fisika, kimia, dan biologi dengan standar keamanan tertinggi.",
        images: ["https://images.unsplash.com/photo-1581093458791-9d42e7e9c1c4?q=80&w=800"]
      }
    ]
  },
  {
    id: 3,
    name: "Perpustakaan",
    description: "Perpustakaan menjadi pusat literasi dan sumber belajar bagi seluruh siswa.",
    items: [
      {
        id: 301,
        title: "Ruang Baca Nyaman",
        text: "Lingkungan tenang dengan koleksi buku lengkap.",
        images: ["https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=800"]
      }
    ]
  }
]);

const activeCategoryId = ref(categories.value[0].id);

const activeCategory = computed(() => {
  return categories.value.find(c => c.id === activeCategoryId.value);
});

const setActiveCategory = (id) => {
  activeCategoryId.value = id;
  isItemFormVisible.value = false;
};

// State Toast & Modal Delete
const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const isDeleteModalOpen = ref(false);
const itemToDeleteType = ref("");
const itemToDeleteId = ref(null);

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => showToast.value = false, 4000);
};

// Kategori Form Modals
const isCategoryModalOpen = ref(false);
const isEditingCategory = ref(false);
const categoryForm = ref({ id: null, name: "", description: "" });

const openAddCategory = () => {
  categoryForm.value = { id: null, name: "", description: "" };
  isEditingCategory.value = false;
  isCategoryModalOpen.value = true;
};

const openEditCategory = (cat) => {
  categoryForm.value = { ...cat };
  isEditingCategory.value = true;
  isCategoryModalOpen.value = true;
};

const saveCategory = () => {
  if (!categoryForm.value.name.trim()) {
    triggerToast("Gagal", "Nama kategori tidak boleh kosong!", "error");
    return;
  }
  if (isEditingCategory.value) {
    const idx = categories.value.findIndex(c => c.id === categoryForm.value.id);
    if(idx !== -1) {
      categories.value[idx].name = categoryForm.value.name;
      categories.value[idx].description = categoryForm.value.description;
    }
    triggerToast("Disimpan", "Kategori fasilitas berhasil diperbarui.");
  } else {
    const newId = Date.now();
    categories.value.push({
      id: newId,
      name: categoryForm.value.name,
      description: categoryForm.value.description,
      items: []
    });
    activeCategoryId.value = newId;
    triggerToast("Ditambahkan", "Kategori fasilitas baru berhasil ditambahkan.");
  }
  isCategoryModalOpen.value = false;
};

// Item (Fasilitas Detail) Form
const isItemFormVisible = ref(false);
const isEditingItem = ref(false);
const itemForm = ref({ id: null, title: "", text: "", images: [""] });

const openAddItem = () => {
  itemForm.value = { id: null, title: "", text: "", images: [""] };
  isEditingItem.value = false;
  isItemFormVisible.value = true;
};

const openEditItem = (item) => {
  itemForm.value = { ...item, images: item.images && item.images.length ? [...item.images] : [""] };
  isEditingItem.value = true;
  isItemFormVisible.value = true;
};

const addImageField = () => {
  itemForm.value.images.push("");
};

const removeImageField = (index) => {
  itemForm.value.images.splice(index, 1);
};

const saveItem = () => {
  if (!itemForm.value.title.trim()) {
    triggerToast("Gagal", "Nama fasilitas wajib diisi!", "error");
    return;
  }
  
  const validImages = itemForm.value.images.filter(img => img && img.trim() !== "");
  if(validImages.length === 0) {
    triggerToast("Gagal", "Minimal 1 foto fasilitas wajib diunggah!", "error");
    return;
  }

  const cat = activeCategory.value;
  if(isEditingItem.value) {
    const idx = cat.items.findIndex(i => i.id === itemForm.value.id);
    if(idx !== -1) {
      cat.items[idx] = { ...itemForm.value, images: validImages };
    }
    triggerToast("Disimpan", "Data fasilitas berhasil diperbarui.");
  } else {
    cat.items.push({
      id: Date.now(),
      title: itemForm.value.title,
      text: itemForm.value.text,
      images: validImages
    });
    triggerToast("Ditambahkan", "Fasilitas baru berhasil ditambahkan.");
  }
  isItemFormVisible.value = false;
};

const hideItemForm = () => {
  isItemFormVisible.value = false;
};

// Hapus Data
const deleteCategory = (id) => {
  itemToDeleteType.value = 'category';
  itemToDeleteId.value = id;
  isDeleteModalOpen.value = true;
};

const deleteItem = (id) => {
  itemToDeleteType.value = 'item';
  itemToDeleteId.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
  if (itemToDeleteType.value === 'category') {
    categories.value = categories.value.filter(c => c.id !== itemToDeleteId.value);
    if (activeCategoryId.value === itemToDeleteId.value) {
      activeCategoryId.value = categories.value.length > 0 ? categories.value[0].id : null;
    }
    triggerToast("Dihapus", "Kategori berhasil dihapus.", "info");
  } else if (itemToDeleteType.value === 'item') {
    if(activeCategory.value) {
      activeCategory.value.items = activeCategory.value.items.filter(i => i.id !== itemToDeleteId.value);
    }
    triggerToast("Dihapus", "Fasilitas berhasil dihapus.", "info");
  }
  isDeleteModalOpen.value = false;
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Manajemen Fasilitas</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola data kategori dan daftar fasilitas unggulan sekolah.</p>
      </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
      <!-- Kolom Kiri: Kategori -->
      <div class="xl:col-span-1 space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-bold text-gray-800 dark:text-white">Kategori</h3>
          <button @click="openAddCategory" class="text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-800 p-1.5 rounded-lg transition-colors" title="Tambah Kategori">
            <PhPlusCircle class="w-6 h-6" />
          </button>
        </div>
        <div class="flex flex-col gap-2">
          <div v-for="cat in categories" :key="cat.id" 
               @click="setActiveCategory(cat.id)"
               class="flex items-center justify-between p-3 rounded-xl border transition-all cursor-pointer group"
               :class="activeCategoryId === cat.id ? 'bg-blue-50 border-blue-200 dark:bg-blue-900/30 dark:border-blue-800' : 'bg-white border-gray-100 hover:border-blue-200 dark:bg-slate-800 dark:border-slate-700'">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg flex items-center justify-center" :class="activeCategoryId === cat.id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-500 dark:bg-slate-700'">
                <PhSquaresFour class="w-4 h-4" />
              </div>
              <div>
                <h4 class="font-semibold text-sm text-gray-800 dark:text-gray-200">{{ cat.name }}</h4>
                <p class="text-xs text-gray-500">{{ cat.items.length }} Fasilitas</p>
              </div>
            </div>
            <div class="flex opacity-0 group-hover:opacity-100 transition-opacity">
              <button @click.stop="openEditCategory(cat)" class="p-1.5 text-blue-600 hover:bg-white dark:hover:bg-slate-700 rounded"><PhPencilSimple class="w-4 h-4" /></button>
              <button @click.stop="deleteCategory(cat.id)" class="p-1.5 text-red-600 hover:bg-white dark:hover:bg-slate-700 rounded"><PhTrash class="w-4 h-4" /></button>
            </div>
          </div>
          <div v-if="categories.length === 0" class="text-center py-6 border-2 border-dashed rounded-xl text-gray-500 text-sm">
            Belum ada kategori.
          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Detail & Form -->
      <div class="xl:col-span-3">
        <div v-if="activeCategory">
          <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 p-6 mb-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ activeCategory.name }}</h3>
              <p class="text-gray-500 dark:text-gray-400 mt-1">{{ activeCategory.description || 'Tidak ada deskripsi.' }}</p>
            </div>
            <button v-if="!isItemFormVisible" @click="openAddItem" class="shrink-0 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
              <PhPlusCircle class="w-5 h-5" /> Tambah Fasilitas
            </button>
          </div>

          <!-- Form Tambah/Edit Fasilitas -->
          <Transition enter-active-class="transition-all duration-300" enter-from-class="opacity-0 -translate-y-4" enter-to-class="opacity-100 translate-y-0">
            <div v-if="isItemFormVisible" class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 p-6 mb-6 shadow-sm">
              <h4 class="text-lg font-bold mb-4 border-b pb-2 dark:border-slate-700">{{ isEditingItem ? 'Edit Fasilitas' : 'Tambah Fasilitas Baru' }}</h4>
              <form @submit.prevent="saveItem">
                <div class="space-y-4 mb-6">
                  <div>
                    <label class="block text-sm font-medium mb-1">Nama Fasilitas</label>
                    <input type="text" v-model="itemForm.title" required class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-slate-600 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Lab Biologi Dasar" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-1">Deskripsi Fasilitas</label>
                    <textarea v-model="itemForm.text" rows="3" class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-slate-600 focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan fasilitas ini..."></textarea>
                  </div>
                  
                  <!-- Dynamic Images Form -->
                  <div>
                    <label class="block text-sm font-medium mb-2">Galeri Foto Fasilitas</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                      <div v-for="(img, index) in itemForm.images" :key="index" class="relative group">
                        <ImageUploader v-model="itemForm.images[index]" containerClass="w-full" label="" />
                        <button v-if="itemForm.images.length > 1" @click="removeImageField(index)" type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 transition-colors z-10" title="Hapus Foto">
                          <PhX class="w-4 h-4" />
                        </button>
                      </div>
                      <div class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-xl flex items-center justify-center aspect-video cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors" @click="addImageField">
                        <div class="text-center">
                          <PhPlusCircle class="w-8 h-8 mx-auto text-gray-400 mb-1" />
                          <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tambah Foto Lain</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t dark:border-slate-700">
                  <button type="button" @click="hideItemForm" class="px-4 py-2 text-sm font-medium border rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">Batal</button>
                  <button type="submit" class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2"><PhFloppyDisk class="w-5 h-5"/> Simpan</button>
                </div>
              </form>
            </div>
          </Transition>

          <!-- List Fasilitas Items -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="item in activeCategory.items" :key="item.id" class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col group">
              <div class="h-48 relative bg-gray-100 dark:bg-slate-700 overflow-hidden border-b dark:border-slate-700">
                <img v-if="item.images && item.images.length" :src="item.images[0]" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                <div v-else class="flex h-full items-center justify-center text-gray-400"><PhImage class="w-10 h-10"/></div>
                <div v-if="item.images && item.images.length > 1" class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded backdrop-blur-sm">+{{ item.images.length - 1 }} Foto</div>
                
                <!-- Hover Actions -->
                <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 dark:bg-slate-800/90 backdrop-blur rounded-lg p-1 shadow-sm">
                  <button @click="openEditItem(item)" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md transition-colors" title="Edit"><PhPencilSimple class="w-4 h-4"/></button>
                  <button @click="deleteItem(item.id)" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md transition-colors" title="Hapus"><PhTrash class="w-4 h-4"/></button>
                </div>
              </div>
              <div class="p-5 flex flex-col flex-1">
                <h4 class="font-bold text-lg text-gray-900 dark:text-white mb-2">{{ item.title }}</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 leading-relaxed">{{ item.text }}</p>
              </div>
            </div>
            <div v-if="activeCategory.items.length === 0" class="col-span-full py-12 text-center border-2 border-dashed rounded-xl dark:border-slate-700">
              <PhImage class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" />
              <p class="text-gray-500 dark:text-gray-400">Belum ada fasilitas di kategori ini.</p>
            </div>
          </div>
        </div>
        
        <div v-else class="py-20 text-center border-2 border-dashed rounded-2xl dark:border-slate-700 bg-white dark:bg-slate-800 flex flex-col items-center justify-center">
          <p class="text-gray-500 dark:text-gray-400">Pilih kategori untuk melihat atau mengelola fasilitas.</p>
        </div>
      </div>
    </div>

    <!-- Modal Tambah/Edit Kategori -->
    <Transition enter-active-class="transition-opacity duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition-opacity duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-if="isCategoryModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden transform transition-all">
          <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ isEditingCategory ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h3>
            <button @click="isCategoryModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"><PhX class="w-5 h-5"/></button>
          </div>
          <div class="p-6 space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nama Kategori</label>
              <input type="text" v-model="categoryForm.name" class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-slate-600 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white" placeholder="Contoh: Sarana Olahraga" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Deskripsi Singkat</label>
              <textarea v-model="categoryForm.description" rows="3" class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-slate-600 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white resize-none" placeholder="Deskripsikan kategori ini..."></textarea>
            </div>
          </div>
          <div class="px-6 py-4 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3 border-t border-gray-100 dark:border-slate-700">
            <button @click="isCategoryModalOpen = false" class="px-4 py-2 text-sm font-medium bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-slate-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">Batal</button>
            <button @click="saveCategory" class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Simpan</button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Modal Konfirmasi Hapus -->
    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      :title="itemToDeleteType === 'category' ? 'Hapus Kategori' : 'Hapus Fasilitas'"
      :message="itemToDeleteType === 'category' ? 'Apakah Anda yakin ingin menghapus kategori ini beserta semua fasilitas di dalamnya?' : 'Apakah Anda yakin ingin menghapus data fasilitas ini?'"
      @confirm="confirmDelete"
      @cancel="isDeleteModalOpen = false"
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