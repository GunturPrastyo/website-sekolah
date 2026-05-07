<script>
import { markRaw } from "vue";
import {
  PhBuildings, PhBook, PhBookOpen, PhBooks, PhGraduationCap,
  PhChalkboard, PhChalkboardTeacher, PhPresentationChart,
  PhPencil, PhPencilSimple, PhPen, PhPalette, PhFlask,
  PhTestTube, PhAtom, PhCalculator, PhMathOperations,
  PhRuler, PhCompass, PhBasketball, PhSoccerBall,
  PhMedal, PhTrophy, PhMonitor, PhLaptop, PhCode,
  PhLeaf, PhTree, PhPlant, PhRocket, PhStar, PhFlag,
  PhTarget, PhCalendar, PhMapPin, PhHammer, PhCheckCircle,
  PhHash
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
  PhMathOperations: markRaw(PhMathOperations),
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
});

const emit = defineEmits(["update:modelValue"]);

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
</script>

<template>
  <div class="icon-picker">
    <div class="relative mb-3">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <PhMagnifyingGlass class="w-4 h-4 text-gray-400" />
      </div>
      <input type="text" v-model="searchQuery" placeholder="Cari ikon..." class="block w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors" />
    </div>
    
    <div class="bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-slate-700 rounded-xl p-3 max-h-52 overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-thumb]:bg-slate-600 [&::-webkit-scrollbar-thumb]:rounded-full">
      <div v-if="Object.keys(filteredIcons).length === 0" class="text-center text-sm text-gray-500 py-4">Ikon tidak ditemukan</div>
      <div v-else class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-8 gap-2">
        <button v-for="(iconComp, name) in filteredIcons" :key="name" type="button" @click="selectIcon(name)"
          :class="['p-2.5 rounded-lg border transition-all flex items-center justify-center group', modelValue === name ? 'border-blue-500 bg-blue-100 text-blue-600 dark:bg-blue-900/60 dark:text-blue-400 scale-105 shadow-sm ring-1 ring-blue-500' : 'border-transparent bg-white text-gray-600 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-500 dark:bg-slate-800 dark:text-gray-300 dark:hover:bg-slate-700 dark:hover:border-slate-600']"
          :title="name">
          <component :is="iconComp" class="w-6 h-6 transition-transform group-hover:scale-110" />
        </button>
      </div>
    </div>
  </div>
</template>