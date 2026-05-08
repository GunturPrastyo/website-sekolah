<template>
  <div
    class="rich-text-editor bg-white dark:bg-slate-800 rounded-lg border border-gray-300 dark:border-slate-600 focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-500"
  >
    <!-- Hidden file input untuk mendukung multi-upload -->
    <input
      type="file"
      multiple
      accept="image/*"
      ref="fileInput"
      @change="handleFiles"
      class="hidden"
    />

    <QuillEditor
      ref="editorRef"
      v-model:content="content"
      contentType="html"
      theme="snow"
      :toolbar="toolbarOptions"
      :placeholder="placeholder"
      @ready="onEditorReady"
      class="min-h-[250px] text-gray-900 dark:text-white border-0"
    />
  </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { QuillEditor, Quill } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

// 1. Registrasi Custom Blot untuk Grid Gambar Teks Editor
if (Quill) {
  const BlockEmbed = Quill.import("blots/block/embed");
  class ImageGridBlot extends BlockEmbed {
    static create(value) {
      const node = super.create();
      node.setAttribute("class", "custom-image-grid grid gap-2 my-4");

      if (value.length === 2) node.classList.add("grid-cols-2");
      else if (value.length === 3) node.classList.add("grid-cols-3");
      else if (value.length >= 4) node.classList.add("grid-cols-2", "md:grid-cols-4");
      else node.classList.add("grid-cols-1");

      node.setAttribute("contenteditable", "false"); // Mencegah grid hancur saat diedit user

      value.forEach((url) => {
        const wrapper = document.createElement("div");
        wrapper.className =
          "relative aspect-video rounded-xl overflow-hidden shadow-sm border dark:border-slate-700";
        const img = document.createElement("img");
        img.setAttribute("src", url);
        img.className = "w-full h-full object-cover";
        wrapper.appendChild(img);
        node.appendChild(wrapper);
      });

      return node;
    }

    static value(node) {
      const urls = [];
      node.querySelectorAll("img").forEach((img) => urls.push(img.getAttribute("src")));
      return urls;
    }
  }

  ImageGridBlot.blotName = "imageGrid";
  ImageGridBlot.tagName = "div";
  ImageGridBlot.className = "custom-image-grid";

  try {
    Quill.register(ImageGridBlot, true);
  } catch (e) {
    console.error("Gagal meregistrasi Quill custom blot:", e);
  }
}

const props = defineProps({
  modelValue: {
    type: String,
    default: "",
  },
  placeholder: {
    type: String,
    default: "Tuliskan sesuatu di sini...",
  },
});

const emit = defineEmits(["update:modelValue"]);

const content = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});

const editorRef = ref(null);
const fileInput = ref(null);

// Konfigurasi Toolbar yang lebih lengkap (termasuk link, gambar, video, dan styling text)
const toolbarOptions = [
  ["bold", "italic", "underline", "strike"], // Toggled buttons
  ["blockquote", "code-block"],

  [{ header: 1 }, { header: 2 }], // Custom button values
  [{ list: "ordered" }, { list: "bullet" }],
  [{ script: "sub" }, { script: "super" }], // Superscript/subscript
  [{ indent: "-1" }, { indent: "+1" }], // Outdent/indent
  [{ direction: "rtl" }], // Text direction

  [{ size: ["small", false, "large", "huge"] }], // Custom dropdown
  [{ header: [1, 2, 3, 4, 5, 6, false] }],

  [{ color: [] }, { background: [] }], // Dropdown warna dengan default dari theme
  [{ font: [] }],
  [{ align: [] }],

  ["link", "image", "video"], // Link, attach gambar, dan video
  ["clean"], // Tombol remove formatting
];

// 2. Modifikasi Image Handler Default Quill agar menggunakan multi-upload kita
const onEditorReady = (quillInstance) => {
  const toolbar = quillInstance.getModule("toolbar");
  toolbar.addHandler("image", () => {
    if (fileInput.value) {
      fileInput.value.click();
    }
  });
};

// 3. Proses File ke dalam Text Editor
const handleFiles = (event) => {
  const files = event.target.files;
  if (!files || files.length === 0) return;

  const quill = editorRef.value.getQuill();
  // Dapatkan posisi kursor, jika tidak ada taruh di akhir
  const range = quill.getSelection(true) || { index: quill.getLength() };

  const imageUrls = Array.from(files).map((file) => URL.createObjectURL(file));

  if (imageUrls.length === 1) {
    // Jika 1 gambar, sisipkan sebagai image biasa agar bisa di-align (left, center, right)
    quill.insertEmbed(range.index, "image", imageUrls[0]);
  } else {
    // Jika banyak gambar, otomatis sisipkan sebagai Grid Custom
    quill.insertEmbed(range.index, "imageGrid", imageUrls);
  }

  // Geser kursor ke setelah gambar
  quill.setSelection(range.index + 1);

  // Reset input agar bisa upload file yang sama lagi
  if (fileInput.value) fileInput.value.value = "";
};
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
