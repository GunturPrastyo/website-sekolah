<script setup>
import { ref, watch } from "vue";
import { PhUploadSimple, PhImage, PhTrash, PhArrowsOut } from "@phosphor-icons/vue";

const props = defineProps({
  modelValue: {
    type: String,
    default: "",
  },
  label: {
    type: String,
    default: "Unggah Gambar",
  },
  containerClass: {
    type: String,
    default: "w-full md:w-1/2",
  },
  imageClass: {
    type: String,
    default: "object-cover object-center",
  },
  isCircular: {
    type: Boolean,
    default: false,
  },
  accept: {
    type: String,
    default: "image/*",
  },
});

const emit = defineEmits(["update:modelValue", "file-selected"]);

const fileInput = ref(null);
const isDragging = ref(false);

const rawImage = ref("");
const posX = ref(50);
const posY = ref(50);

const isDraggingImage = ref(false);
const startMouseX = ref(0);
const startMouseY = ref(0);
const startPosX = ref(50);
const startPosY = ref(50);

const isVideo = ref(false);

const checkIfVideo = (url) => {
  if (url.startsWith("data:video")) {
    isVideo.value = true;
  } else if (url.match(/\.(mp4|webm|ogg)$/i)) {
    isVideo.value = true;
  } else {
    isVideo.value = false;
  }
};

// Sinkronisasi awal jika form diisi data dari API/Parent
watch(
  () => props.modelValue,
  (newVal) => {
    if (newVal && !rawImage.value) {
      rawImage.value = newVal;
      checkIfVideo(newVal);
      posX.value = 50;
      posY.value = 50;
    } else if (!newVal) {
      rawImage.value = "";
      isVideo.value = false;
      posX.value = 50;
      posY.value = 50;
    }
  },
  { immediate: true }
);

const cropImage = () => {
  // Hanya memotong jika gambar berupa Data URL (hasil unggahan baru)
  // untuk menghindari error CORS pada URL gambar eksternal (seperti ui-avatars)
  if (!rawImage.value || !rawImage.value.startsWith("data:")) {
    emit("update:modelValue", rawImage.value);
    return;
  }

  const canvas = document.createElement("canvas");
  // Tentukan resolusi crop. 800x800 jika isCircular (1:1), 800x450 jika tidak (16:9)
  const width = 800;
  const height = props.isCircular ? 800 : Math.round(800 * (9 / 16));
  canvas.width = width;
  canvas.height = height;
  const ctx = canvas.getContext("2d");

  const img = new Image();
  img.onload = () => {
    const scale = Math.max(width / img.width, height / img.height);
    const drawW = img.width * scale;
    const drawH = img.height * scale;

    const x = (width - drawW) * (posX.value / 100);
    const y = (height - drawH) * (posY.value / 100);

    ctx.fillStyle = "#ffffff";
    ctx.fillRect(0, 0, width, height); // Background putih untuk gambar transparan
    ctx.drawImage(img, x, y, drawW, drawH);

    // Mengirim gambar yang sudah dipotong murni ke v-model
    emit("update:modelValue", canvas.toDataURL("image/jpeg", 0.9));
  };
  img.src = rawImage.value;
};

const startDrag = (e) => {
  if (isVideo.value) return;
  if (!rawImage.value || !rawImage.value.startsWith("data:")) return;
  isDraggingImage.value = true;
  startMouseX.value = e.clientX || e.touches?.[0]?.clientX;
  startMouseY.value = e.clientY || e.touches?.[0]?.clientY;
  startPosX.value = posX.value;
  startPosY.value = posY.value;
};

const onDrag = (e) => {
  if (isVideo.value) return;
  if (!isDraggingImage.value) return;
  if (e.cancelable) e.preventDefault(); // Mencegah halaman ikut scroll saat mengatur foto

  const currentX = e.clientX || e.touches?.[0]?.clientX;
  const currentY = e.clientY || e.touches?.[0]?.clientY;
  const dx = currentX - startMouseX.value;
  const dy = currentY - startMouseY.value;

  const sensitivity = 0.3; // Tingkat sensitivitas gesekan mouse
  posX.value = Math.max(0, Math.min(100, startPosX.value - dx * sensitivity));
  posY.value = Math.max(0, Math.min(100, startPosY.value - dy * sensitivity));
};

const stopDrag = () => {
  if (isDraggingImage.value) {
    isDraggingImage.value = false;
    cropImage(); // Proses render ulang setelah pengguna selesai menggeser
  }
};

const triggerInput = () => {
  if (fileInput.value) fileInput.value.click();
};

const handleFile = (file) => {
  if (file && (file.type.startsWith("image/") || file.type.startsWith("video/"))) {
    const reader = new FileReader();
    reader.onload = (e) => {
      rawImage.value = e.target.result;
      posX.value = 50; // Reset posisi ke tengah
      posY.value = 50;
      checkIfVideo(rawImage.value);
      if (!isVideo.value) {
        cropImage();
      } else {
        emit("update:modelValue", rawImage.value);
      }
      emit("file-selected", file); // Melempar file mentah jika ingin upload via API (FormData)
    };
    reader.readAsDataURL(file);
  } else {
    alert("Harap unggah file gambar atau video yang valid.");
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
  rawImage.value = "";
  isVideo.value = false;
  posX.value = 50;
  posY.value = 50;
  emit("update:modelValue", "");
  emit("file-selected", null);
};
</script>

<template>
  <div class="image-uploader w-full">
    <label
      v-if="label"
      class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
    >
      {{ label }}
    </label>

    <!-- Dropzone untuk unggah gambar -->
    <div
      v-if="!modelValue"
      @click="triggerInput"
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="onDrop"
      class="border-2 border-dashed flex flex-col items-center justify-center cursor-pointer transition-all duration-200 ease-in-out text-center overflow-hidden"
      :class="[
        isDragging
          ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'
          : 'border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600',
        isCircular ? 'rounded-full aspect-square p-4' : 'rounded-xl aspect-video p-6',
        containerClass,
      ]"
    >
      <PhUploadSimple
        :class="[
          isCircular ? 'w-8 h-8 mb-2' : 'w-10 h-10 mb-3',
          isDragging ? 'text-blue-500' : 'text-gray-400 dark:text-gray-500',
        ]"
      />
      <p
        :class="[
          isCircular ? 'text-xs px-2' : 'text-sm',
          'font-medium text-gray-700 dark:text-gray-300 mb-1',
        ]"
      >
        {{
          isCircular ? "Klik/Seret Foto" : "Klik untuk mengunggah atau seret file ke sini"
        }}
      </p>
      <p
        v-if="!isCircular"
        class="text-xs text-gray-500 dark:text-gray-400 mt-1 px-4 leading-relaxed"
      >
        Format yang didukung:
        {{ accept.includes("video") ? "JPG, PNG, MP4, WEBM" : "JPG, PNG, GIF, WEBP" }}
      </p>
    </div>

    <!-- Preview Gambar -->
    <div
      v-else
      class="relative overflow-hidden shadow-sm border border-gray-200 dark:border-slate-700 group"
      :class="[
        isCircular ? 'rounded-full aspect-square' : 'rounded-xl aspect-video',
        containerClass,
        isVideo ? '' : 'cursor-move',
      ]"
      @mousedown="startDrag"
      @mousemove="onDrag"
      @mouseup="stopDrag"
      @mouseleave="stopDrag"
      @touchstart="startDrag"
      @touchmove="onDrag"
      @touchend="stopDrag"
    >
      <video
        v-if="isVideo"
        :src="rawImage"
        class="w-full h-full bg-gray-100 dark:bg-slate-800 object-cover"
        autoplay
        loop
        muted
        playsinline
      ></video>
      <img
        v-else
        :src="rawImage"
        alt="Preview Gambar"
        class="w-full h-full bg-gray-100 dark:bg-slate-800 object-cover pointer-events-none"
        :style="{ objectPosition: `${posX}% ${posY}%` }"
      />

      <!-- Overlay Aksi & Keterangan (disembunyikan saat sedang didrag) -->
      <div
        class="absolute inset-0 bg-black/50 transition-opacity flex flex-col items-center justify-center gap-4 backdrop-blur-sm"
        :class="
          isDraggingImage
            ? 'opacity-0 pointer-events-none'
            : 'opacity-0 group-hover:opacity-100'
        "
      >
        <div class="flex items-center gap-3">
          <button
            type="button"
            @mousedown.stop
            @touchstart.stop
            @click.stop="triggerInput"
            class="p-2.5 bg-white/20 hover:bg-blue-600 text-white rounded-full transition-colors pointer-events-auto shadow-sm"
            title="Ubah Media"
          >
            <PhImage class="w-5 h-5" />
          </button>
          <button
            type="button"
            @mousedown.stop
            @touchstart.stop
            @click.stop="removeImage"
            class="p-2.5 bg-white/20 hover:bg-red-600 text-white rounded-full transition-colors pointer-events-auto shadow-sm"
            title="Hapus Media"
          >
            <PhTrash class="w-5 h-5" />
          </button>
        </div>

        <!-- Keterangan Bantu (hanya muncul jika file baru diupload) -->
        <span
          v-if="rawImage && rawImage.startsWith('data:') && !isVideo"
          class="bg-black/60 text-white text-[10px] font-medium px-2.5 py-1.5 rounded-full backdrop-blur-md border border-white/20 shadow-lg flex items-center gap-1.5 pointer-events-none max-w-[90%] text-center"
        >
          <PhArrowsOut class="w-3.5 h-3.5 shrink-0" />
          <span class="truncate">Geser untuk menyesuaikan</span>
        </span>
      </div>
    </div>

    <input
      type="file"
      ref="fileInput"
      :accept="accept"
      class="hidden"
      @change="onFileChange"
    />
  </div>
</template>
