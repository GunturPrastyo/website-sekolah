<template>
  <div class="rich-text-editor bg-white dark:bg-slate-800 rounded-lg border border-gray-300 dark:border-slate-600 focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-500">
    <QuillEditor
      v-model:content="content"
      contentType="html"
      theme="snow"
      :toolbar="toolbarOptions"
      :placeholder="placeholder"
      class="min-h-[250px] text-gray-900 dark:text-white border-0"
    />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: 'Tuliskan sesuatu di sini...'
  }
});

const emit = defineEmits(['update:modelValue']);

const content = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val)
});

// Konfigurasi Toolbar yang lebih lengkap (termasuk link, gambar, video, dan styling text)
const toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // Toggled buttons
  ['blockquote', 'code-block'],

  [{ 'header': 1 }, { 'header': 2 }],               // Custom button values
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  [{ 'script': 'sub'}, { 'script': 'super' }],      // Superscript/subscript
  [{ 'indent': '-1'}, { 'indent': '+1' }],          // Outdent/indent
  [{ 'direction': 'rtl' }],                         // Text direction

  [{ 'size': ['small', false, 'large', 'huge'] }],  // Custom dropdown
  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

  [{ 'color': [] }, { 'background': [] }],          // Dropdown warna dengan default dari theme
  [{ 'font': [] }],
  [{ 'align': [] }],

  ['link', 'image', 'video'],                       // Link, attach gambar, dan video
  ['clean']                                         // Tombol remove formatting
];
</script>

<style scoped>
/* Custom styles untuk Quill Editor di Mode Terang / Gelap */
:deep(.ql-toolbar) {
  border-radius: 0.5rem 0.5rem 0 0;
  border-color: inherit !important;
  background-color: #f8fafc;
}
.dark :deep(.ql-toolbar) {
  background-color: #334155;
  border-color: #475569 !important;
}
.dark :deep(.ql-stroke) {
  stroke: #cbd5e1 !important;
}
.dark :deep(.ql-fill) {
  fill: #cbd5e1 !important;
}
.dark :deep(.ql-picker) {
  color: #cbd5e1 !important;
}
:deep(.ql-container) {
  border-radius: 0 0 0.5rem 0.5rem;
  border-color: inherit !important;
  font-family: inherit;
}
.dark :deep(.ql-container) {
  border-color: #475569 !important;
}
.dark :deep(.ql-editor.ql-blank::before) {
  color: #94a3b8;
}
/* Responsif tambahan untuk gambar yang di-attach agar tidak merusak layout */
:deep(.ql-editor img) {
  max-width: 100%;
  height: auto;
  border-radius: 0.5rem;
}
</style>