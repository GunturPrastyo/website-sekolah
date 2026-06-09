<script>
import { markRaw } from "vue";
import {
  PhBuildings,
  PhBook,
  PhBookOpen,
  PhBooks,
  PhGraduationCap,
  PhChalkboard,
  PhChalkboardTeacher,
  PhPresentationChart,
  PhPencil,
  PhPencilSimple,
  PhPen,
  PhPalette,
  PhFlask,
  PhTestTube,
  PhAtom,
  PhCalculator,
  PhRuler,
  PhCompass,
  PhBasketball,
  PhSoccerBall,
  PhMedal,
  PhTrophy,
  PhMonitor,
  PhLaptop,
  PhCode,
  PhLeaf,
  PhTree,
  PhPlant,
  PhRocket,
  PhStar,
  PhFlag,
  PhTarget,
  PhCalendar,
  PhMapPin,
  PhHammer,
  PhCheckCircle,
  PhHash,
  PhGlobe,
  PhHandsPraying,
  PhHandshake,
  PhPerson,
  PhBrain,
  PhHeart,
  PhGlobeHemisphereWest,
  PhUsers,
  PhUserCheck,
  PhLightbulb,
  PhLightning,
  PhHandsClapping,
  PhRecycle,
  PhScales,
  PhShieldCheck,
  PhHandHeart,
  PhUsersFour,
  PhSparkle,
} from "@phosphor-icons/vue";

// Ekspor objek ini agar komponen lain bisa me-render ikon dari string nama ikon
export const educationIcons = {
  PhBuildings: markRaw(PhBuildings),
  PhBook: markRaw(PhBook),
  PhBookOpen: markRaw(PhBookOpen),
  PhBooks: markRaw(PhBooks),
  PhGraduationCap: markRaw(PhGraduationCap),
  PhChalkboard: markRaw(PhChalkboard),
  PhChalkboardTeacher: markRaw(PhChalkboardTeacher),
  PhPresentationChart: markRaw(PhPresentationChart),
  PhPencil: markRaw(PhPencil),
  PhPencilSimple: markRaw(PhPencilSimple),
  PhPen: markRaw(PhPen),
  PhPalette: markRaw(PhPalette),
  PhFlask: markRaw(PhFlask),
  PhTestTube: markRaw(PhTestTube),
  PhAtom: markRaw(PhAtom),
  PhCalculator: markRaw(PhCalculator),
  PhRuler: markRaw(PhRuler),
  PhCompass: markRaw(PhCompass),
  PhBasketball: markRaw(PhBasketball),
  PhSoccerBall: markRaw(PhSoccerBall),
  PhMedal: markRaw(PhMedal),
  PhTrophy: markRaw(PhTrophy),
  PhMonitor: markRaw(PhMonitor),
  PhLaptop: markRaw(PhLaptop),
  PhCode: markRaw(PhCode),
  PhLeaf: markRaw(PhLeaf),
  PhTree: markRaw(PhTree),
  PhPlant: markRaw(PhPlant),
  PhRocket: markRaw(PhRocket),
  PhStar: markRaw(PhStar),
  PhFlag: markRaw(PhFlag),
  PhTarget: markRaw(PhTarget),
  PhCalendar: markRaw(PhCalendar),
  PhMapPin: markRaw(PhMapPin),
  PhHammer: markRaw(PhHammer),
  PhCheckCircle: markRaw(PhCheckCircle),
  PhHash: markRaw(PhHash),
  PhGlobe: markRaw(PhGlobe),
  PhHandsPraying: markRaw(PhHandsPraying),
  PhHandshake: markRaw(PhHandshake),
  PhPerson: markRaw(PhPerson),
  PhBrain: markRaw(PhBrain),
  PhHeart: markRaw(PhHeart),
  PhGlobeHemisphereWest: markRaw(PhGlobeHemisphereWest),
  PhUsers: markRaw(PhUsers),
  PhUserCheck: markRaw(PhUserCheck),
  PhLightbulb: markRaw(PhLightbulb),
  PhLightning: markRaw(PhLightning),
  PhHandsClapping: markRaw(PhHandsClapping),
  PhRecycle: markRaw(PhRecycle),
  PhScales: markRaw(PhScales),
  PhShieldCheck: markRaw(PhShieldCheck),
  PhHandHeart: markRaw(PhHandHeart),
  PhUsersFour: markRaw(PhUsersFour),
  PhSparkle: markRaw(PhSparkle),
};
</script>

<script setup>
import { ref, computed } from "vue";
import { PhMagnifyingGlass } from "@phosphor-icons/vue";

const props = defineProps({
  modelValue: {
    type: String,
    default: "PhBuildings",
  },
  colorValue: {
    type: String,
    default: "text-blue-500",
  },
});

const emit = defineEmits([
  "update:modelValue",
  "update:colorValue",
  "update:color-value",
]);

const searchQuery = ref("");

const filteredIcons = computed(() => {
  if (!searchQuery.value) return educationIcons;
  const lowerQuery = searchQuery.value.toLowerCase();
  return Object.keys(educationIcons).reduce((acc, key) => {
    if (key.toLowerCase().includes(lowerQuery)) {
      acc[key] = educationIcons[key];
    }
    return acc;
  }, {});
});

const selectIcon = (name) => {
  emit("update:modelValue", name);
};

const selectColor = (colorClass) => {
  emit("update:colorValue", colorClass);
  emit("update:color-value", colorClass);
};

const colors = [
  {
    name: "Biru",
    text: "text-blue-500",
    bg: "bg-blue-500",
    active: "bg-blue-600 dark:bg-blue-500 text-white border-transparent",
  },
  {
    name: "Merah",
    text: "text-red-500",
    bg: "bg-red-500",
    active: "bg-red-600 dark:bg-red-500 text-white border-transparent",
  },
  {
    name: "Hijau",
    text: "text-green-500",
    bg: "bg-green-500",
    active: "bg-green-600 dark:bg-green-500 text-white border-transparent",
  },
  {
    name: "Kuning",
    text: "text-yellow-500",
    bg: "bg-yellow-500",
    active: "bg-yellow-500 dark:bg-yellow-600 text-white border-transparent",
  },
  {
    name: "Ungu",
    text: "text-purple-500",
    bg: "bg-purple-500",
    active: "bg-purple-600 dark:bg-purple-500 text-white border-transparent",
  },
  {
    name: "Jingga",
    text: "text-orange-500",
    bg: "bg-orange-500",
    active: "bg-orange-500 dark:bg-orange-600 text-white border-transparent",
  },
  {
    name: "Merah Muda",
    text: "text-pink-500",
    bg: "bg-pink-500",
    active: "bg-pink-600 dark:bg-pink-500 text-white border-transparent",
  },
  {
    name: "Teal",
    text: "text-teal-500",
    bg: "bg-teal-500",
    active: "bg-teal-600 dark:bg-teal-500 text-white border-transparent",
  },
  {
    name: "Abu-abu",
    text: "text-gray-500",
    bg: "bg-gray-500",
    active: "bg-gray-600 dark:bg-gray-500 text-white border-transparent",
  },
];

const activeClasses = computed(() => {
  const found = colors.find((c) => c.text === props.colorValue);
  return found
    ? found.active
    : "bg-blue-600 dark:bg-blue-500 text-white border-transparent";
});

const selectedIconComponent = computed(() => educationIcons[props.modelValue]);
</script>

<template>
  <div class="icon-picker">
    <div class="flex items-center gap-4 mb-4">
      <!-- Preview Ikon & Warna -->
      <div
        :class="[
          'w-14 h-14 rounded-xl flex items-center justify-center shrink-0 shadow-sm border transition-all duration-300',
          activeClasses,
        ]"
      >
        <component
          v-if="selectedIconComponent"
          :is="selectedIconComponent"
          class="w-7 h-7"
        />
      </div>
      <div class="flex-1">
        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2"
          >Warna Ikon</label
        >
        <div class="flex flex-wrap gap-2">
          <button
            v-for="color in colors"
            :key="color.text"
            type="button"
            @click="selectColor(color.text)"
            :class="[
              'w-6 h-6 rounded-full transition-all duration-200 shadow-sm border border-white/20 cursor-pointer',
              color.bg,
              props.colorValue === color.text
                ? 'ring-2 ring-offset-2 ring-offset-white dark:ring-offset-slate-800 ring-gray-400 dark:ring-gray-300 scale-110'
                : 'hover:scale-110',
            ]"
            :title="color.name"
          ></button>
        </div>
      </div>
    </div>

    <div class="relative mb-3">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <PhMagnifyingGlass class="w-4 h-4 text-gray-400" />
      </div>
      <input
        type="text"
        v-model="searchQuery"
        placeholder="Cari ikon..."
        class="block w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors"
      />
    </div>

    <div
      class="bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-slate-700 rounded-xl p-3 max-h-52 overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-thumb]:bg-slate-600 [&::-webkit-scrollbar-thumb]:rounded-full"
    >
      <div
        v-if="Object.keys(filteredIcons).length === 0"
        class="text-center text-sm text-gray-500 py-4"
      >
        Ikon tidak ditemukan
      </div>
      <div v-else class="grid grid-cols-[repeat(auto-fill,minmax(44px,1fr))] gap-2">
        <button
          v-for="(iconComp, name) in filteredIcons"
          :key="name"
          type="button"
          @click="selectIcon(name)"
          :class="[
            'p-2.5 rounded-lg border transition-all flex items-center justify-center group shadow-sm',
            modelValue === name
              ? `scale-105 ring-1 ${activeClasses}`
              : 'border-transparent bg-white text-gray-600 hover:border-gray-300 hover:bg-gray-100 hover:text-gray-900 dark:bg-slate-800 dark:text-gray-400 dark:hover:bg-slate-700 dark:hover:border-slate-600 dark:hover:text-white',
          ]"
          :title="name"
        >
          <component
            :is="iconComp"
            class="w-6 h-6 transition-transform group-hover:scale-110"
          />
        </button>
      </div>
    </div>
  </div>
</template>
