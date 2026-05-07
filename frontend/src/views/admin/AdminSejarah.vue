<script setup>
import { ref } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
} from "@phosphor-icons/vue";
import RichTextEditor from "@/components/RichTextEditor.vue";
import IconPicker, { educationIcons } from "@/components/IconPicker.vue";

// Dummy data, in a real app this would come from an API
const timeline = ref([
  {
    id: 1,
    year: "1985",
    title: "Pendirian & Peresmian SMAN 1",
    icon: "PhBuildings",
    image: "https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?q=80&w=800",
    description:
      "Sekolah ini resmi didirikan pada tanggal 17 Agustus 1985 berdasarkan SK Menteri Pendidikan. Pada awalnya, sekolah hanya memiliki 3 ruang kelas dengan 120 siswa angkatan pertama dan menumpang di gedung SMP terdekat selama proses pembangunan gedung utama berlangsung.",
  },
  {
    id: 2,
    year: "1992",
    title: "Pembangunan Gedung Utama",
    icon: "PhHammer",
    image: "https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=800",
    description:
      "Pembangunan gedung sekolah mandiri akhirnya selesai dan diresmikan oleh Gubernur. Di tahun ini, SMAN 1 mulai menempati lokasi saat ini dengan fasilitas yang diperluas, meliputi 12 ruang kelas, ruang guru, dan lapangan olahraga serbaguna.",
  },
  {
    id: 3,
    year: "2005",
    title: "Akreditasi A & Prestasi Nasional",
    icon: "PhMedal",
    image: "https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=800",
    description:
      "Berkat dedikasi seluruh civitas akademika, SMAN 1 berhasil meraih akreditasi A (Sangat Baik). Pada tahun yang sama, tim cerdas cermat sekolah berhasil membawa pulang piala Juara 1 tingkat Nasional untuk pertama kalinya.",
  },
  {
    id: 4,
    year: "2015",
    title: "Era Transformasi Digital",
    icon: "PhMonitor",
    image: "https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=800",
    description:
      "Menjawab tantangan abad 21, sekolah mulai mengintegrasikan teknologi ke dalam pembelajaran. Pembangunan laboratorium komputer modern, perpustakaan digital, serta pengadaan proyektor dan Wi-Fi di seluruh area sekolah mulai direalisasikan.",
  },
  {
    id: 5,
    year: "2021",
    title: "Sekolah Adiwiyata & Peduli Lingkungan",
    icon: "PhLeaf",
    image: "https://images.unsplash.com/photo-1466692476868-aef1dfb1e736?q=80&w=800",
    description:
      "Berkomitmen pada lingkungan yang asri, sekolah memenangkan penghargaan Sekolah Adiwiyata tingkat Provinsi. Program bank sampah, taman hidroponik, dan ruang hijau terpadu menjadi identitas baru SMAN 1.",
  },
  {
    id: 6,
    year: "2026",
    title: "Pelopor Kurikulum Merdeka",
    icon: "PhRocket",
    image: "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800",
    description:
      "Hari ini, SMAN 1 Nogosari terus melesat menjadi sekolah percontohan dalam implementasi Kurikulum Merdeka. Dengan lebih dari 1100 siswa, kami terus melahirkan lulusan yang cerdas, berkarakter, dan berdaya saing global.",
  },
]);

const form = ref({
  id: null,
  year: "",
  title: "",
  icon: "PhBuildings", // Default icon
  image: "",
  description: "",
});

const isEditing = ref(false); // Indicates if an entry is being edited

const resetForm = () => {
  form.value = {
    id: null,
    year: "",
    title: "",
    icon: "PhBuildings",
    image: "",
    description: "",
  };
  isEditing.value = false;
};

const addEntry = () => {
  const plainDesc = form.value.description.replace(/<[^>]*>?/gm, "").trim();
  if (!form.value.year || !form.value.title || !plainDesc) {
    alert("Tahun, Judul, dan Deskripsi tidak boleh kosong!");
    return;
  }
  const newId =
    timeline.value.length > 0 ? Math.max(...timeline.value.map((e) => e.id)) + 1 : 1;
  timeline.value.push({
    ...form.value,
    id: newId,
  });
  resetForm();
};

const startEdit = (entry) => {
  isEditing.value = true;
  form.value = {
    ...entry,
  }; 
};

const saveEntry = () => {
  const plainDesc = form.value.description.replace(/<[^>]*>?/gm, "").trim();
  if (!form.value.year || !form.value.title || !plainDesc) {
    alert("Tahun, Judul, dan Deskripsi tidak boleh kosong!");
    return;
  }
  const index = timeline.value.findIndex((e) => e.id === form.value.id);
  if (index !== -1) {
    timeline.value[index] = {
      ...form.value,
    };
  }
  resetForm();
};

const cancelEdit = () => {
  resetForm();
};

const deleteEntry = (id) => {
  if (confirm("Apakah Anda yakin ingin menghapus entri ini?")) {
    timeline.value = timeline.value.filter((e) => e.id !== id);
  }
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8">
      <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
        Manajemen Sejarah Sekolah
      </h2>
      <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
        Kelola data lini masa sejarah SMAN 1 Nogosari
      </p>
    </div>

    <!-- Form Tambah/Edit Data -->
    <div
      class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm mb-8"
    >
      <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
        {{ isEditing ? "Edit Entri Timeline" : "Tambah Entri Baru" }}
      </h3>
      <form @submit.prevent="isEditing ? saveEntry() : addEntry()">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label
              for="year"
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Tahun</label
            >
            <input
              type="text"
              id="year"
              v-model="form.year"
              class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              placeholder="Contoh: 1985"
              required
            />
          </div>
          <div>
            <label
              for="title"
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Judul</label
            >
            <input
              type="text"
              id="title"
              v-model="form.title"
              class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              placeholder="Contoh: Pendirian SMAN 1"
              required
            />
          </div>
        </div>

        <div class="mb-4">
          <label
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >Ikon Representasi</label
          >
          <IconPicker v-model="form.icon" />
        </div>

        <div class="mb-4">
          <label
            for="image"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >URL Gambar</label
          >
          <input
            type="url"
            id="image"
            v-model="form.image"
            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
            placeholder="Contoh: https://images.unsplash.com/photo-..."
          />
        </div>

        <div class="mb-6">
          <label
            for="description"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >Deskripsi</label
          >
          <RichTextEditor
            v-model="form.description"
            placeholder="Masukkan deskripsi lengkap entri timeline..."
          />
        </div>

        <div class="flex gap-3">
          <button
            type="submit"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-slate-800"
          >
            <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
            <PhPlusCircle v-else class="w-5 h-5 mr-2" />
            {{ isEditing ? "Simpan Perubahan" : "Tambah Entri" }}
          </button>
          <button
            v-if="isEditing"
            type="button"
            @click="cancelEdit"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-slate-800"
          >
            <PhXCircle class="w-5 h-5 mr-2" />
            Batal
          </button>
        </div>
      </form>
    </div>

    <!-- Daftar Entri Timeline -->
    <div
      class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm"
    >
      <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
        Daftar Entri Timeline
      </h3>
      <div class="space-y-4">
        <div
          v-for="entry in timeline"
          :key="entry.id"
          class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg border border-gray-100 dark:border-slate-600"
        >
          <div class="flex items-center gap-3">
            <component :is="educationIcons[entry.icon]" class="w-6 h-6 text-blue-500 shrink-0" />
            <div>
              <p class="font-semibold text-gray-800 dark:text-white">
                {{ entry.year }} - {{ entry.title }}
              </p>
              <div
                class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mt-1"
                v-html="entry.description"
              ></div>
            </div>
          </div>
          <div class="flex gap-2">
            <button
              @click="startEdit(entry)"
              class="p-2 rounded-full text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
              title="Edit"
            >
              <PhPencilSimple class="w-5 h-5" />
            </button>
            <button
              @click="deleteEntry(entry.id)"
              class="p-2 rounded-full text-red-600 hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors"
              title="Hapus"
            >
              <PhTrash class="w-5 h-5" />
            </button>
          </div>
        </div>
        <p
          v-if="timeline.length === 0"
          class="text-center text-gray-500 dark:text-gray-400"
        >
          Belum ada entri timeline.
        </p>
      </div>
    </div>
  </main>
</template>

<style scoped>
</style>
