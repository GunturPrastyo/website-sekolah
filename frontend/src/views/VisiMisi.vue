<template>
  <div>
    <PageHeader
      v-if="!isLoading && appearanceSettings"
      badge="Profil Sekolah"
      :title="appearanceSettings.headerVisiMisi_title || 'Visi, Misi & Sambutan'"
      :description="
        appearanceSettings.headerVisiMisi_description ||
        'Arah pandang, cita-cita luhur, dan komitmen strategis sekolah dalam menyelenggarakan pendidikan unggul, serta sambutan hangat dari Kepala Sekolah.'
      "
      :bgImage="getImageUrl(appearanceSettings.headerVisiMisi_bgImage)"
      currentPage="Visi & Misi"
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
            style="font-family: 'Oswald', sans-serif"
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
                  <h4
                    class="text-white font-bold text-xl"
                    style="font-family: 'Oswald', sans-serif"
                  >
                    {{ principal.name }}
                  </h4>
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
                class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-300 tracking-wide"
                style="font-family: 'Oswald', sans-serif"
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
                <p
                  class="font-bold text-gray-800 dark:text-white text-lg"
                  style="font-family: 'Oswald', sans-serif"
                >
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
    <section class="relative w-full bg-fixed bg-center bg-cover overflow-hidden">
      <div class="absolute inset-0 z-0 bg-white/90 dark:bg-slate-900/90"></div>

      <div class="flex flex-col lg:flex-row w-full relative z-10">
        <div
          class="w-full lg:w-6/12 p-8 sm:p-10 md:p-16 lg:p-24 text-gray-800 dark:text-gray-300 flex flex-col justify-center"
        >
          <div>
            <h3
              class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 md:mb-6 tracking-wide"
              style="font-family: 'Oswald', sans-serif"
            >
              Visi
            </h3>
            <div
              v-if="isLoading"
              class="animate-pulse h-12 bg-white/20 rounded w-full"
            ></div>
            <p
              v-else
              class="text-lg sm:text-xl md:text-2xl font-medium leading-relaxed text-gray-800 dark:text-gray-300"
            >
              "{{ visi }}"
            </p>
          </div>
        </div>

        <div
          class="w-full lg:w-7/12 p-8 sm:p-10 md:p-16 lg:p-24 flex flex-col justify-center text-gray-800 dark:text-gray-300"
        >
          <div
            class="mb-6 md:mb-8 pb-4 border-b border-blue-800/60 dark:border-slate-700"
          >
            <h3
              class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 dark:text-gray-300 tracking-wide"
              style="font-family: 'Oswald', sans-serif"
            >
              Misi
            </h3>
          </div>

          <div v-if="isLoading" class="animate-pulse space-y-4">
            <div class="h-12 bg-white/10 rounded w-full"></div>
            <div class="h-12 bg-white/10 rounded w-full"></div>
            <div class="h-12 bg-white/10 rounded w-full"></div>
          </div>
          <ul v-else class="space-y-4 md:space-y-6">
            <li v-for="(item, index) in misi" :key="index" class="flex items-start group">
              <span
                class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-white/10 dark:bg-slate-800 text-gray-800 dark:text-gray-300 group-hover:bg-blue-500 group-hover:text-white transition-colors rounded-full flex items-center justify-center font-bold text-sm md:text-base mt-0.5 mr-3 md:mr-5 shadow-sm border border-white/20 dark:border-slate-700"
                >{{ index + 1 }}</span
              >
              <p
                class="text-gray-800 dark:text-gray-300 text-sm sm:text-base md:text-lg pt-1 md:pt-1.5 leading-relaxed"
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
const appearanceSettings = ref({});
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
  const backendUrl = api.defaults.baseURL;
  return `${backendUrl.replace(/\/+$/, "")}/${path.replace(/^\/+/, "")}`;
};

const fetchVisionMission = async () => {
  isLoading.value = true;
  try {
    const [visionRes, settingsRes] = await Promise.all([
      api.get("/api/vision-mission"),
      api.get("/api/settings"), // Mengambil setingan gambar dinamis dari VPS
    ]);

    const data = visionRes.data.data;
    visi.value = data.vision || "";
    misi.value = data.missions || [];
    sambutan.value = data.principal_speech || "";
    principalId.value = data.principal_id || null;

    // Set Data Global Settings untuk Background Header
    if (settingsRes.data?.success) {
      appearanceSettings.value = settingsRes.data.data;
    }

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

      if (principalId.value) {
        principalStaff = response.data.data.find(
          (staff) => staff.id === principalId.value
        );
      }

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
    { threshold: 0.15 }
  );

  if (textContainerRef.value) {
    observer.observe(textContainerRef.value);
  }
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&display=swap");

.editor-content :deep(p) {
  margin-bottom: 1.25rem;
}

.editor-content :deep(strong) {
  font-weight: 600;
}
</style>
