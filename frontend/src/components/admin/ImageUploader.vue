<script setup>
import { ref } from "vue";
import { PhUploadSimple, PhImage, PhTrash } from "@phosphor-icons/vue";

const props = defineProps({
  modelValue: {
    type: String,
    default: "",
  },
  label: {
    type: String,
    default: "Unggah Gambar",
  },
});

const emit = defineEmits(["update:modelValue", "file-selected"]);

const fileInput = ref(null);
const isDragging = ref(false);

const triggerInput = () => {
  if (fileInput.value) fileInput.value.click();
};

const handleFile = (file) => {
  if (file && file.type.startsWith("image/")) {
    const reader = new FileReader();
    reader.onload = (e) => {
      emit("update:modelValue", e.target.result); // Mengubah state v-model
      emit("file-selected", file); // Melempar file mentah jika ingin upload via API (FormData)
    };
    reader.readAsDataURL(file);
  } else {
    alert("Harap unggah file berupa gambar yang valid.");
  }
};

const onFileChange = (e) => {
  const file = e.target.files[0];
  handleFile(file);
  // Reset value dari input file agar kita dapat memilih file yang sama lagi jika telah dihapus
  if (fileInput.value) fileInput.value.value = "";
};

const onDrop = (e) => {
  isDragging.value = false;
  const file = e.dataTransfer.files[0];
  handleFile(file);
};

const removeImage = () => {
  emit("update:modelValue", "");
  emit("file-selected", null);
};
</script>

<template>
  <div class="image-uploader w-full">
    <label v-if="label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
      {{ label }}
    </label>

    <!-- Dropzone untuk unggah gambar -->
    <div
      v-if="!modelValue"
      @click="triggerInput"
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="onDrop"
      class="border-2 border-dashed rounded-xl p-8 flex flex-col items-center justify-center cursor-pointer transition-all duration-200 ease-in-out"
      :class="isDragging ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600'"
    >
      <PhUploadSimple class="w-10 h-10 mb-3" :class="isDragging ? 'text-blue-500' : 'text-gray-400 dark:text-gray-500'" />
      <p class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center mb-1">
        Klik untuk mengunggah atau seret gambar ke sini
      </p>
      <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
        Format yang didukung: JPG, PNG, GIF, WEBP
      </p>
    </div>

    <!-- Preview Gambar -->
    <div v-else class="relative rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-slate-700 group w-full md:w-1/2 aspect-video">
      <img :src="modelValue" alt="Preview Gambar" class="w-full h-full object-cover" />
      <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-sm">
        <button type="button" @click="triggerInput" class="p-2.5 bg-white/20 hover:bg-blue-600 text-white rounded-full transition-colors" title="Ubah Gambar"><PhImage class="w-5 h-5" /></button>
        <button type="button" @click="removeImage" class="p-2.5 bg-white/20 hover:bg-red-600 text-white rounded-full transition-colors" title="Hapus Gambar"><PhTrash class="w-5 h-5" /></button>
      </div>
    </div>

    <input type="file" ref="fileInput" accept="image/*" class="hidden" @change="onFileChange" />
  </div>
</template>