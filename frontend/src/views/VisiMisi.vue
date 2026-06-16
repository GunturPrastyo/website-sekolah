<template>
  <div>
    <PageHeader
      badge="Profil Sekolah"
      title="Visi, Misi & Tujuan"
      description="Mengenal lebih dekat arah, tujuan, dan landasan utama sekolah kami dalam mencetak generasi unggul penerus bangsa."
    />

    <!-- Sambutan Kepala Sekolah Section -->
    <section
      class="relative py-12 md:py-16 px-6 md:px-0 bg-fixed bg-center bg-cover overflow-hidden"
      style="
        background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1600&auto=format&fit=crop');
      "
    >
      <!-- Parallax Overlay -->
      <div
        class="absolute inset-0 bg-white/90 dark:bg-slate-900/90 backdrop-blur-[2px] transition-colors duration-500"
      ></div>
      <div class="container relative z-10 mx-auto px-0 md:px-4 max-w-6xl">
        <!-- Judul Mobile/Tablet -->
        <div class="flex lg:hidden items-center gap-3 mb-8 md:px-12">
          <h2
            class="text-2xl md:text-4xl font-bold text-gray-900 dark:text-white tracking-wide"
            style="font-family: 'Kalam', cursive"
          >
            Sambutan Kepala Sekolah
          </h2>
        </div>

        <div class="flex flex-col lg:flex-row items-center gap-8 md:gap-12 lg:gap-16">
          <!-- Gambar Kepala Sekolah -->
          <div
            class="w-full lg:w-1/3 flex justify-center pl-4 pb-4 md:pl-8 md:pb-6 lg:pl-0 lg:pb-0"
          >
            <div class="relative w-full max-w-sm aspect-[4/5]">
              <div
                class="absolute -left-4 -bottom-4 md:-left-6 md:-bottom-6 w-full h-full bg-blue-600 dark:bg-blue-950 rounded-lg z-0 shadow-lg"
              ></div>
              <div
                class="absolute inset-0 rounded-lg overflow-hidden shadow-2xl border-4 border-white dark:border-slate-800 z-10 bg-white dark:bg-slate-800"
              >
                <img
                  v-if="principal.photo"
                  :src="principal.photo"
                  class="w-full h-full object-cover rounded-lg"
                  :alt="principal.position"
                />
                <div
                  v-else
                  class="w-full h-full flex flex-col items-center justify-center bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-gray-500 rounded-lg"
                >
                  <PhUser
                    class="w-32 h-32 text-gray-300 dark:text-gray-500 mb-4"
                    weight="fill"
                  />
                </div>
                <div
                  class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 to-transparent p-6"
                >
                  <h4 class="text-white font-bold text-xl">{{ principal.name }}</h4>
                  <p class="text-gray-300 text-sm font-medium mt-1">
                    {{ principal.position }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Teks Sambutan -->
          <div class="w-full lg:w-2/3 mt-4 lg:mt-0 md:px-12 lg:px-0">
            <div class="hidden lg:flex items-center gap-3 mb-6">
              <h2
                class="text-2xl md:text-4xl font-bold text-gray-900 dark:text-white tracking-wide"
                style="font-family: 'Kalam', cursive"
              >
                Sambutan Kepala Sekolah
              </h2>
            </div>

            <div v-if="isLoading" class="animate-pulse space-y-4 mt-6">
              <div class="h-4 bg-gray-200 dark:bg-slate-700 rounded w-full"></div>
              <div class="h-4 bg-gray-200 dark:bg-slate-700 rounded w-5/6"></div>
              <div class="h-4 bg-gray-200 dark:bg-slate-700 rounded w-4/6"></div>
            </div>
            <div
              v-else
              class="relative z-10 mt-6 opacity-0 translate-y-4 transition-all duration-1000 ease-in-out"
              ref="textContainerRef"
            >
              <PhQuotes
                class="absolute -top-4 right-0 md:right-4 w-24 h-24 md:w-32 md:h-32 text-blue-50 dark:text-slate-800 -z-10"
                weight="fill"
              />
              <div
                class="text-gray-700 dark:text-gray-300 text-sm sm:text-base md:text-lg leading-relaxed text-justify editor-content relative z-10"
                v-html="sambutan"
              ></div>

              <div class="mt-8 pt-6 border-t border-gray-100 dark:border-slate-800 block">
                <p class="font-bold text-gray-800 dark:text-white text-lg">
                  {{ principal.name }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  {{ principal.position }}
                  <span v-if="principal.nip && principal.nip !== '-'">
                    | NIP. {{ principal.nip }}</span
                  >
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Visi & Misi Section -->
    <section
      class="relative w-full bg-fixed bg-center bg-cover"
      style="
        background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1600&auto=format&fit=crop');
      "
    >
      <div class="flex flex-col lg:flex-row w-full">
        <div
          class="w-full lg:w-6/12 p-8 sm:p-10 md:p-16 lg:p-24 text-white relative overflow-hidden flex flex-col justify-center"
        >
          <!-- Parallax Overlay Visi -->
          <div
            class="absolute inset-0 bg-gradient-to-br from-blue-900/90 to-blue-950/90 dark:from-slate-900/95 dark:to-slate-950/95 backdrop-blur-[2px]"
          ></div>
          <div class="relative z-10">
            <h3
              class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 md:mb-6 tracking-wide"
              style="font-family: 'Kalam', cursive"
            >
              Visi
            </h3>
            <div
              v-if="isLoading"
              class="animate-pulse h-12 bg-white/20 rounded w-full"
            ></div>
            <p
              v-else
              class="text-lg sm:text-xl md:text-2xl font-medium leading-relaxed text-blue-50"
            >
              "{{ visi }}"
            </p>
          </div>
        </div>

        <div
          class="w-full lg:w-7/12 relative p-8 sm:p-10 md:p-16 lg:p-24 flex flex-col justify-center text-white overflow-hidden"
        >
          <!-- Parallax Overlay Misi -->
          <div
            class="absolute inset-0 bg-gradient-to-br from-blue-950/90 to-blue-900/90 dark:from-slate-950/95 dark:to-slate-900/95 backdrop-blur-[2px]"
          ></div>
          <div
            class="relative z-10 mb-6 md:mb-8 pb-4 border-b border-blue-800 dark:border-slate-700"
          >
            <h3
              class="text-3xl sm:text-4xl md:text-5xl font-bold text-white tracking-wide"
              style="font-family: 'Kalam', cursive"
            >
              Misi
            </h3>
          </div>

          <div v-if="isLoading" class="relative z-10 animate-pulse space-y-4">
            <div class="h-12 bg-white/10 rounded w-full"></div>
            <div class="h-12 bg-white/10 rounded w-full"></div>
            <div class="h-12 bg-white/10 rounded w-full"></div>
          </div>
          <ul v-else class="relative z-10 space-y-4 md:space-y-6">
            <li v-for="(item, index) in misi" :key="index" class="flex items-start group">
              <span
                class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-white/10 dark:bg-slate-800 text-blue-200 dark:text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-colors rounded-full flex items-center justify-center font-bold text-sm md:text-base mt-0.5 mr-3 md:mr-5 shadow-sm border border-white/20 dark:border-slate-700"
                >{{ index + 1 }}</span
              >
              <p
                class="text-blue-50 dark:text-gray-300 text-sm sm:text-base md:text-lg pt-1 md:pt-1.5 leading-relaxed"
              >
                {{ item.text }}
              </p>
            </li>
          </ul>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { PhQuotes, PhUser } from "@phosphor-icons/vue";
import PageHeader from "@/components/PageHeader.vue";
import api from "@/api/index.js";

const textContainerRef = ref(null);

const visi = ref("");
const misi = ref([]);
const sambutan = ref("");
const principalPosition = ref("Kepala Sekolah");
const principalId = ref(null);
const isLoading = ref(true);

const principal = ref({
  name: "-",
  position: "Kepala Sekolah",
  nip: "-",
  photo: "",
});

const getImageUrl = (path) => {
  if (!path) return "";
  if (path.startsWith("http") || path.startsWith("data:")) return path;
  const backendUrl = import.meta.env.VITE_API_URL || "http://localhost:8000";
  return `${backendUrl.replace(/\/+$/, "")}/${path.replace(/^\/+/, "")}`;
};

const fetchVisionMission = async () => {
  isLoading.value = true;
  try {
    const response = await api.get("/api/vision-mission");
    const data = response.data.data;
    visi.value = data.vision || "";
    misi.value = data.missions || [];
    sambutan.value = data.principal_speech || "";
    principalId.value = data.principal_id || null;

    await fetchPrincipal();
  } catch (error) {
    console.error("Gagal mengambil data Visi & Misi:", error);
  } finally {
    isLoading.value = false;
  }
};

const fetchPrincipal = async () => {
  try {
    const response = await api.get("/api/guru-staf");
    if (response.data && response.data.data) {
      let principalStaff = null;

      // 1. Prioritaskan pencarian menggunakan ID Staf berdasarkan setelan dari Admin
      if (principalId.value) {
        principalStaff = response.data.data.find(
          (staff) => staff.id === principalId.value
        );
      }

      // 2. Fallback pencarian berdasarkan jabatan jika ID Staf kosong / tidak disetel
      if (!principalStaff) {
        principalStaff = response.data.data.find(
          (staff) =>
            staff.position &&
            staff.position.toLowerCase() === principalPosition.value.toLowerCase()
        );
      }

      if (principalStaff) {
        principal.value.name = principalStaff.name;
        principal.value.position = principalStaff.position;
        principal.value.nip = principalStaff.nip || "-";
        if (principalStaff.image || principalStaff.photo) {
          principal.value.photo = getImageUrl(
            principalStaff.image || principalStaff.photo
          );
        }
      } else {
        principal.value.position = principalPosition.value;
      }
    }
  } catch (error) {
    console.error("Gagal mengambil data Kepala Sekolah:", error);
  }
};

onMounted(async () => {
  await fetchVisionMission();

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          setTimeout(() => {
            entry.target.classList.add("opacity-100", "translate-y-0");
            entry.target.classList.remove("opacity-0", "translate-y-4");
          }, 200);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 } // Efek dijalankan saat 15% blok teks terlihat di layar
  );

  if (textContainerRef.value) {
    observer.observe(textContainerRef.value);
  }
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap");

.editor-content :deep(p) {
  margin-bottom: 1.25rem;
}
.editor-content :deep(strong) {
  font-weight: 600;
}
</style>
