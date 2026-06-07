<template>
  <div
    ref="editorWrapper"
    class="rich-text-editor relative bg-white dark:bg-slate-800 rounded-lg border border-gray-300 dark:border-slate-600 focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-500"
  >
    <!-- Input file tersembunyi untuk mengambil alih upload gambar -->
    <input
      type="file"
      accept="image/*"
      ref="fileInput"
      @change="handleImageUpload"
      class="hidden"
    />

    <!-- Menu Apung (Floating Toolbar) Pengaturan Gambar -->
    <div
      v-show="selectedImage"
      :style="toolbarStyle"
      class="absolute z-10 flex flex-col gap-1 p-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 shadow-xl rounded-lg pointer-events-auto transition-opacity"
    >
      <!-- Opsi Posisi / Alignment -->
      <div
        class="flex items-center gap-1 border-b border-gray-100 dark:border-slate-700 pb-1 mb-1"
      >
        <span class="text-[10px] text-gray-500 font-bold px-1 uppercase">Posisi</span>
        <button
          @mousedown.prevent
          @click="setImageStyle('float: left; margin: 0 1rem 1rem 0;')"
          class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-700 rounded text-gray-700 dark:text-gray-300"
          title="Menyamping (Kiri)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h8m-8 6h16M4 18h.01M4 12h.01"
            />
          </svg>
        </button>
        <button
          @mousedown.prevent
          @click="
            setImageStyle('display: block; margin: 1rem auto; clear: both; float: none;')
          "
          class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-700 rounded text-gray-700 dark:text-gray-300"
          title="Tengah (Blok)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
          </svg>
        </button>
        <button
          @mousedown.prevent
          @click="setImageStyle('float: right; margin: 0 0 1rem 1rem;')"
          class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-700 rounded text-gray-700 dark:text-gray-300"
          title="Menyamping (Kanan)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M12 12h8m-8 6h16M20 18h.01M20 12h.01"
            />
          </svg>
        </button>
        <button
          @mousedown.prevent
          @click="
            setImageStyle(
              'display: block; margin: 1rem auto 1rem 0; clear: both; float: none;'
            )
          "
          class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-700 rounded text-gray-700 dark:text-gray-300"
          title="Kiri (Blok)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h10m-10 6h16"
            />
          </svg>
        </button>
      </div>
      <!-- Opsi Ukuran -->
      <div class="flex items-center gap-1">
        <span class="text-[10px] text-gray-500 font-bold px-1 uppercase">Ukuran</span>
        <button
          @mousedown.prevent
          @click="setImageWidth('100%')"
          class="px-2 py-1 text-xs hover:bg-gray-100 dark:hover:bg-slate-700 rounded text-gray-700 dark:text-gray-300 font-medium transition-colors"
        >
          100%
        </button>
        <button
          @mousedown.prevent
          @click="setImageWidth('50%')"
          class="px-2 py-1 text-xs hover:bg-gray-100 dark:hover:bg-slate-700 rounded text-gray-700 dark:text-gray-300 font-medium transition-colors"
        >
          50%
        </button>
        <button
          @mousedown.prevent
          @click="setImageWidth('25%')"
          class="px-2 py-1 text-xs hover:bg-gray-100 dark:hover:bg-slate-700 rounded text-gray-700 dark:text-gray-300 font-medium transition-colors"
        >
          25%
        </button>
        <button
          @mousedown.prevent
          @click="setImageWidth('auto')"
          class="px-2 py-1 text-xs hover:bg-gray-100 dark:hover:bg-slate-700 rounded text-gray-700 dark:text-gray-300 font-medium transition-colors"
        >
          Auto
        </button>
      </div>
      <!-- Opsi Keterangan Gambar -->
      <div
        class="flex items-center gap-2 mt-1 border-t border-gray-100 dark:border-slate-700 pt-2"
      >
        <span class="text-[10px] text-gray-500 font-bold px-1 uppercase shrink-0"
          >Info</span
        >
        <input
          type="text"
          v-model="imageCaption"
          @input="setImageCaption"
          placeholder="Keterangan / Alt text..."
          class="text-xs px-2 py-1.5 w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded text-gray-700 dark:text-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
        />
      </div>
    </div>

    <!-- Overlay Dinamis untuk Menampilkan Teks Keterangan Gambar di dalam Editor -->
    <div
      v-for="(cap, idx) in imageCaptions"
      :key="idx"
      class="absolute z-0 text-center text-[10px] sm:text-xs text-gray-500 italic pointer-events-none mt-1"
      :style="{ top: cap.top + 'px', left: cap.left + 'px', width: cap.width + 'px' }"
    >
      {{ cap.text }}
    </div>

    <QuillEditor
      ref="quillRef"
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
import { ref, computed, onUnmounted } from "vue";
import { QuillEditor, Quill } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

// 0. Patch sementara untuk mematikan warning Chrome terkait DOMNodeInserted dari Quill 1.3.x
if (typeof window !== "undefined" && !window.__quillDomNodeInsertedPatched) {
  const originalAddEventListener = HTMLElement.prototype.addEventListener;
  HTMLElement.prototype.addEventListener = function (type, listener, options) {
    // Abaikan pendaftaran event DOMNodeInserted agar Chrome tidak memberikan warning
    if (type === "DOMNodeInserted") return;
    return originalAddEventListener.call(this, type, listener, options);
  };
  window.__quillDomNodeInsertedPatched = true;
}

// 1. Register Custom Blot untuk mempertahankan attribut 'style' & 'width' saat disisipkan & disimpan
if (typeof window !== "undefined" && Quill) {
  const BaseImage = Quill.import("formats/image");
  class StylableImage extends BaseImage {
    static formats(domNode) {
      return {
        width: domNode.getAttribute("width") || "",
        style: domNode.getAttribute("style") || "",
        alt: domNode.getAttribute("alt") || "",
        title: domNode.getAttribute("title") || "",
      };
    }
    format(name, value) {
      if (["width", "style", "alt", "title"].includes(name)) {
        if (value) {
          this.domNode.setAttribute(name, value);
        } else {
          this.domNode.removeAttribute(name);
        }
      } else {
        super.format(name, value);
      }
    }
  }
  Quill.register(StylableImage, true);
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

const quillRef = ref(null);
const editorWrapper = ref(null);
const fileInput = ref(null);
const selectedImage = ref(null);
const toolbarStyle = ref({ top: "0px", left: "0px" });
const imageCaption = ref("");
let qInstance = null;

// State untuk menampilkan caption gambar secara dinamis (overlay) di dalam editor
const imageCaptions = ref([]);
const updateImageCaptions = () => {
  if (!qInstance || !editorWrapper.value) return;
  const wrapperRect = editorWrapper.value.getBoundingClientRect();
  const images = qInstance.root.querySelectorAll("img");
  const caps = [];
  images.forEach((img) => {
    const text = img.getAttribute("alt") || img.getAttribute("title");
    if (text) {
      const imgRect = img.getBoundingClientRect();
      caps.push({
        text: text,
        top: imgRect.bottom - wrapperRect.top,
        left: imgRect.left - wrapperRect.left,
        width: imgRect.width,
      });
    }
  });
  imageCaptions.value = caps;
};

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

const onEditorReady = (quill) => {
  qInstance = quill;

  // Override default click gambar dari toolbar
  quill.getModule("toolbar").addHandler("image", () => {
    if (fileInput.value) fileInput.value.click();
  });

  // Deteksi klik pada gambar untuk memunculkan floating toolbar
  quill.root.addEventListener("click", (e) => {
    if (e.target.tagName === "IMG") {
      selectedImage.value = e.target;
      imageCaption.value =
        e.target.getAttribute("alt") || e.target.getAttribute("title") || "";
      updateToolbarPosition();
    } else {
      selectedImage.value = null;
    }
  });

  // Sembunyikan toolbar jika user lanjut mengetik
  quill.on("text-change", () => {
    selectedImage.value = null;
    updateImageCaptions();
  });

  // Sinkronkan posisi caption saat di-scroll atau saat ukuran layar berubah
  quill.root.addEventListener("scroll", updateImageCaptions);
  window.addEventListener("resize", updateImageCaptions);

  setTimeout(updateImageCaptions, 200);
};

onUnmounted(() => {
  if (typeof window !== "undefined") {
    window.removeEventListener("resize", updateImageCaptions);
  }
});

const updateToolbarPosition = () => {
  if (!selectedImage.value || !editorWrapper.value) return;
  const imgRect = selectedImage.value.getBoundingClientRect();
  const wrapperRect = editorWrapper.value.getBoundingClientRect();

  let topPos = imgRect.top - wrapperRect.top + 10;
  let leftPos = imgRect.left - wrapperRect.left + 10;

  // Batasi posisi ke kiri agar tidak keluar dari area editor
  if (leftPos < 10) leftPos = 10;

  // Batasi posisi ke kanan agar tidak keluar dari area editor (lebar toolbar ~200px)
  if (leftPos + 220 > wrapperRect.width) leftPos = Math.max(10, wrapperRect.width - 230);

  // Batasi batas atas dan bawah saat user melakukan scroll dalam editor
  if (topPos < 10) topPos = 10;
  if (topPos + 80 > wrapperRect.height) topPos = Math.max(10, wrapperRect.height - 90);

  toolbarStyle.value = {
    top: `${topPos}px`,
    left: `${leftPos}px`,
  };
};

const handleImageUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (evt) => {
    const url = evt.target.result;
    const range = qInstance.getSelection(true) || { index: qInstance.getLength() };

    qInstance.insertEmbed(range.index, "image", url, "user");

    // SOLUSI BUG: Memaksa memberikan baris baru '\n' langsung di bawah gambar
    // Sehingga kursor pengguna tidak akan "nyangkut" sebelum gambar
    qInstance.insertText(range.index + 1, "\n", "user");
    qInstance.setSelection(range.index + 2, "user");

    if (fileInput.value) fileInput.value.value = "";
  };
  reader.readAsDataURL(file);
};

const applyFormat = (formatType, valueStr) => {
  if (!selectedImage.value || !qInstance) return;
  const blot = Quill.find(selectedImage.value);
  if (blot) {
    blot.format(formatType, valueStr);
    emit("update:modelValue", qInstance.root.innerHTML); // Paksa sinkronisasi V-Model
    updateToolbarPosition();
    updateImageCaptions();
  }
};

const setImageStyle = (styleStr) => applyFormat("style", styleStr);
const setImageWidth = (widthStr) => applyFormat("width", widthStr);

const setImageCaption = () => {
  if (!selectedImage.value || !qInstance) return;
  const blot = Quill.find(selectedImage.value);
  if (blot) {
    blot.format("alt", imageCaption.value);
    blot.format("title", imageCaption.value);

    // Beri sedikit margin bawah (jika belum ada) agar ruang overlay tidak bertumpuk dengan teks di bawahnya
    let currentStyle = selectedImage.value.getAttribute("style") || "";
    if (
      !currentStyle.includes("margin-bottom") &&
      !currentStyle.match(/margin:\s*[^;]*(1rem|auto)/)
    ) {
      let newStyle = currentStyle;
      if (newStyle && !newStyle.endsWith(";")) newStyle += "; ";
      newStyle += "margin-bottom: 1.5rem;";
      blot.format("style", newStyle);
    }

    emit("update:modelValue", qInstance.root.innerHTML); // Paksa sinkronisasi V-Model
    updateToolbarPosition();
    updateImageCaptions();
  }
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
  cursor: pointer;
  transition: box-shadow 0.2s;
}
:deep(.ql-editor img:hover) {
  box-shadow: 0 0 0 2px #3b82f6; /* Ring biru saat dihover menandakan gambar bisa diedit */
}
</style>
