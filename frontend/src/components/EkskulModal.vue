<script setup>
import { watch, nextTick } from "vue";
import { createIcons, icons } from "lucide";

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
  ekskul: {
    type: Object,
    default: null,
  },
  categories: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(["close"]);

const closeModal = () => {
  emit("close");
};

// Render ulang ikon lucide saat modal terbuka
watch(
  () => props.isOpen,
  (newVal) => {
    if (newVal) {
      nextTick(() => createIcons({ icons }));
    }
  }
);
</script>

<template>
  <Transition
    enter-active-class="transition-opacity duration-300"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition-opacity duration-300"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="isOpen"
      class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 sm:p-6"
      @click="closeModal"
    >
      <div
        class="bg-white dark:bg-slate-800 rounded-3xl w-full max-w-3xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col relative z-10 transform transition-all duration-300 scale-100"
        @click.stop
      >
        <!-- Close button -->
        <button
          @click="closeModal"
          class="absolute top-4 right-4 z-30 w-10 h-10 flex items-center justify-center rounded-full bg-black/30 hover:bg-black/50 text-white backdrop-blur-md transition-colors focus:outline-none"
        >
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <!-- Wrapper Scrollable Area -->
        <div
          class="overflow-y-auto w-full h-full flex flex-col [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-slate-600 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300 dark:hover:[&::-webkit-scrollbar-thumb]:bg-slate-500"
        >
          <!-- Hero Cover Area -->
          <div class="relative w-full h-64 sm:h-80 shrink-0">
            <img :src="ekskul?.image" class="w-full h-full object-cover" />
            <div
              class="absolute inset-0 bg-gradient-to-t from-slate-900/95 via-slate-900/40 to-transparent"
            ></div>

            <div
              class="absolute bottom-0 left-0 w-full p-6 md:p-10 flex flex-col justify-end"
            >
              <div
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-500/20 backdrop-blur-md border border-blue-300/30 text-blue-100 text-xs font-bold uppercase tracking-wider mb-3 w-fit"
              >
                <i
                  :data-lucide="categories.find((c) => c.id === ekskul?.category)?.icon"
                  class="w-3.5 h-3.5"
                ></i>
                {{ categories.find((c) => c.id === ekskul?.category)?.name }}
              </div>
              <h3
                class="text-3xl lg:text-4xl font-extrabold text-white leading-tight drop-shadow-lg"
              >
                {{ ekskul?.name }}
              </h3>
            </div>
          </div>

          <!-- Article Content Area -->
          <div class="p-6 md:p-10 flex flex-col flex-1 bg-white dark:bg-slate-800">
            <!-- Story Text dengan Gaya Drop Cap (Huruf awal besar) -->
            <div class="mb-10">
              <p
                class="text-gray-700 dark:text-gray-300 text-base md:text-lg leading-relaxed text-justify first-letter:text-6xl first-letter:font-black first-letter:text-blue-600 dark:first-letter:text-blue-400 first-letter:float-left first-letter:mr-4 first-letter:mt-1 first-letter:leading-none"
              >
                {{ ekskul?.story }}
              </p>
            </div>

            <!-- Quick Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
              <div
                class="bg-gray-50 dark:bg-slate-700/50 p-4 lg:p-5 rounded-2xl border border-gray-100 dark:border-slate-600 transition-colors hover:border-blue-200 dark:hover:border-blue-800"
              >
                <span
                  class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider block mb-1.5"
                  >Jadwal Latihan</span
                >
                <div
                  class="font-bold text-gray-900 dark:text-white flex items-center text-sm"
                >
                  <i
                    data-lucide="calendar-clock"
                    class="w-4 h-4 mr-2 text-blue-500 shrink-0"
                  ></i>
                  {{ ekskul?.schedule }}
                </div>
              </div>
              <div
                class="bg-gray-50 dark:bg-slate-700/50 p-4 lg:p-5 rounded-2xl border border-gray-100 dark:border-slate-600 transition-colors hover:border-blue-200 dark:hover:border-blue-800"
              >
                <span
                  class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider block mb-1.5"
                  >Pembina Ekskul</span
                >
                <div
                  class="font-bold text-gray-900 dark:text-white flex items-center text-sm"
                >
                  <i data-lucide="user" class="w-4 h-4 mr-2 text-blue-500 shrink-0"></i>
                  {{ ekskul?.pembina }}
                </div>
              </div>
              <div
                class="bg-gray-50 dark:bg-slate-700/50 p-4 lg:p-5 rounded-2xl border border-gray-100 dark:border-slate-600 transition-colors hover:border-blue-200 dark:hover:border-blue-800"
              >
                <span
                  class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider block mb-1.5"
                  >Anggota Aktif</span
                >
                <div
                  class="font-bold text-gray-900 dark:text-white flex items-center text-sm"
                >
                  <i data-lucide="users" class="w-4 h-4 mr-2 text-blue-500 shrink-0"></i>
                  {{ ekskul?.members }} Siswa terdaftar
                </div>
              </div>
            </div>

            <!-- Social Media Info -->
            <div class="border-t border-gray-100 dark:border-slate-700 pt-6 mt-auto">
              <h4
                class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center"
              >
                <i data-lucide="link" class="w-4 h-4 mr-2"></i> Terhubung dengan Kami
              </h4>
              <div class="flex flex-wrap gap-3">
                <a
                  v-if="ekskul?.socials?.ig"
                  href="#"
                  class="px-4 py-2 rounded-full bg-pink-50 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400 font-semibold text-sm flex items-center hover:bg-pink-100 dark:hover:bg-pink-900/40 transition-colors"
                >
                  <i data-lucide="instagram" class="w-4 h-4 mr-2"></i>
                  {{ ekskul.socials.ig }}
                </a>
                <a
                  v-if="ekskul?.socials?.yt"
                  href="#"
                  class="px-4 py-2 rounded-full bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-semibold text-sm flex items-center hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors"
                >
                  <i data-lucide="youtube" class="w-4 h-4 mr-2"></i>
                  {{ ekskul.socials.yt }}
                </a>
                <a
                  v-if="ekskul?.socials?.email"
                  href="#"
                  class="px-4 py-2 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold text-sm flex items-center hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors"
                >
                  <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                  {{ ekskul.socials.email }}
                </a>
              </div>
            </div>

            <!-- Modal Footer Buttons -->
            <div
              class="mt-8 flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-700"
            >
              <button
                @click="closeModal"
                class="px-5 py-2.5 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 transition-colors focus:outline-none"
              >
                Tutup
              </button>
              <router-link
                to="/pendaftaran"
                @click="closeModal"
                class="px-5 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors flex items-center shadow-md shadow-blue-500/30 focus:outline-none"
              >
                Daftar Ekskul <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>
