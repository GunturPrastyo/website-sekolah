<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from "vue";
import { PhMapPin } from "@phosphor-icons/vue";

const props = defineProps({
  alumniLocations: { type: Array, required: true },
  isLoading: { type: Boolean, required: true },
  alumniStatsTarget: { type: Object, required: true },
});

const alumniStats = ref({
  alumni: { value: 0 },
  ptn: { value: 0 },
  instansi: { value: 0 },
});

// Posisi cache agar skeleton akurat
const cachedMapLocations = localStorage.getItem("alumniMapCache");
const defaultSkeletons = [
  { top: "73%", left: "27%" },
  { top: "77%", left: "31%" },
  { top: "80%", left: "38%" },
];
const skeletonLocations = ref(
  cachedMapLocations ? JSON.parse(cachedMapLocations) : defaultSkeletons
);

// Tooltip Logic
const tooltip = ref({ show: false, x: 0, y: 0, tailOffset: 0, data: null });
const showTooltip = (e, loc) => {
  tooltip.value.show = true;
  tooltip.value.data = loc;
  updateTooltipPos(e);
};
const updateTooltipPos = (e) => {
  if (!tooltip.value.show) return;
  const clientX = e.touches && e.touches.length > 0 ? e.touches[0].clientX : e.clientX;
  const clientY = e.touches && e.touches.length > 0 ? e.touches[0].clientY : e.clientY;

  let tooltipX = clientX;
  const tooltipHalfWidth = window.innerWidth < 768 ? 128 : 144;
  const margin = 16;
  let tailOffset = 0;

  if (tooltipX < tooltipHalfWidth + margin) {
    tailOffset = tooltipX - (tooltipHalfWidth + margin);
    tooltipX = tooltipHalfWidth + margin;
  } else if (tooltipX > window.innerWidth - tooltipHalfWidth - margin) {
    tailOffset = tooltipX - (window.innerWidth - tooltipHalfWidth - margin);
    tooltipX = window.innerWidth - tooltipHalfWidth - margin;
  }

  const maxTailOffset = tooltipHalfWidth - 24;
  if (tailOffset > maxTailOffset) tailOffset = maxTailOffset;
  if (tailOffset < -maxTailOffset) tailOffset = -maxTailOffset;

  tooltip.value.x = tooltipX;
  tooltip.value.y = clientY - 15;
  tooltip.value.tailOffset = tailOffset;
};
const hideTooltip = () => {
  tooltip.value.show = false;
  tooltip.value.tailOffset = 0;
};

// Animasi Angka
let alumniAnimationId = null;
const isAlumniStatsVisible = ref(false);

const animateAlumniStats = () => {
  if (alumniAnimationId) cancelAnimationFrame(alumniAnimationId);
  const duration = 2500;
  let startTimestamp = null;

  const startAlumni = alumniStats.value.alumni.value;
  const startPtn = alumniStats.value.ptn.value;
  const startInstansi = alumniStats.value.instansi.value;

  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    const easeProgress = 1 - Math.pow(1 - progress, 4);

    alumniStats.value.alumni.value =
      startAlumni + easeProgress * (props.alumniStatsTarget.alumni - startAlumni);
    alumniStats.value.ptn.value = Math.floor(
      startPtn + easeProgress * (props.alumniStatsTarget.ptn - startPtn)
    );
    alumniStats.value.instansi.value = Math.floor(
      startInstansi + easeProgress * (props.alumniStatsTarget.instansi - startInstansi)
    );

    if (progress < 1) {
      alumniAnimationId = window.requestAnimationFrame(step);
    } else {
      alumniStats.value.alumni.value = props.alumniStatsTarget.alumni;
      alumniStats.value.ptn.value = props.alumniStatsTarget.ptn;
      alumniStats.value.instansi.value = props.alumniStatsTarget.instansi;
    }
  };
  alumniAnimationId = window.requestAnimationFrame(step);
};

let observer = null;
onMounted(() => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("opacity-100", "translate-y-0");
          entry.target.classList.remove("opacity-0", "translate-y-10");
          isAlumniStatsVisible.value = true;
          animateAlumniStats();
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 }
  );

  const target = document.querySelector(".alumni-stats-container");
  if (target) observer.observe(target);
});

onBeforeUnmount(() => {
  if (observer) observer.disconnect();
});

watch(
  () => props.alumniStatsTarget,
  () => {
    if (isAlumniStatsVisible.value) animateAlumniStats();
  },
  { deep: true }
);
</script>

<template>
  <div class="mt-8 px-0 md:px-8 lg:px-4 mb-20 md:mb-24 -mx-0 md:-mx-8 lg:-mx-0">
    <div
      class="relative bg-center bg-fixed sm:rounded-xl shadow-xl p-3 md:p-2 flex flex-col md:flex-row items-center gap-0 md:gap-6 overflow-hidden"
      style="
        background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1200');
      "
    >
      <div
        class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-blue-950/80 to-slate-900/90 backdrop-blur-[2px]"
      ></div>

      <!-- LEFT TEXT -->
      <div
        class="relative z-10 md:w-2/5 lg:w-1/3 mx-0 sm:mx-6 mt-4 md:mt-0 opacity-0 translate-y-10 transition-all duration-700 ease-out alumni-stats-container"
      >
        <h3
          class="text-xl md:text-2xl lg:text-3xl font-bold text-white mb-2"
          style="font-family: 'Oswald', sans-serif"
        >
          Persebaran Alumni Kami
        </h3>
        <p class="text-sm lg:text-base text-sky-50 leading-relaxed mb-4">
          Tersebar di berbagai perguruan tinggi favorit dan sukses berkarir di berbagai
          sektor industri terkemuka.
        </p>

        <div class="grid grid-cols-3 gap-2 sm:gap-4">
          <div>
            <h4
              class="text-2xl md:text-3xl font-bold text-yellow-400"
              style="font-family: 'Oswald', sans-serif"
            >
              {{
                alumniStats.alumni.value >= 1000
                  ? (alumniStats.alumni.value / 1000).toFixed(1) + "k+"
                  : Math.floor(alumniStats.alumni.value)
              }}
            </h4>
            <p class="text-[10px] sm:text-xs text-sky-200 mt-1 font-medium">
              Alumni Sukses
            </p>
          </div>
          <div>
            <h4
              class="text-2xl md:text-3xl font-bold text-yellow-400"
              style="font-family: 'Oswald', sans-serif"
            >
              {{
                alumniStats.ptn.value >= 1000
                  ? (alumniStats.ptn.value / 1000).toFixed(1) + "k+"
                  : Math.floor(alumniStats.ptn.value)
              }}
            </h4>
            <p class="text-[10px] sm:text-xs text-sky-200 mt-1 font-medium">
              PTN & Kampus Top
            </p>
          </div>
          <div>
            <h4
              class="text-2xl md:text-3xl font-bold text-yellow-400"
              style="font-family: 'Oswald', sans-serif"
            >
              {{
                alumniStats.instansi.value >= 1000
                  ? (alumniStats.instansi.value / 1000).toFixed(1) + "k+"
                  : Math.floor(alumniStats.instansi.value)
              }}
            </h4>
            <p class="text-[10px] sm:text-xs text-sky-200 mt-1 font-medium">
              Instansi & Kedinasan
            </p>
          </div>
        </div>
      </div>

      <!-- RIGHT MAP VISUALIZATION -->
      <div
        class="relative z-10 md:w-3/5 lg:w-2/3 w-full flex items-center justify-center min-h-[220px] md:min-h-[280px] mt-0"
      >
        <div
          class="relative w-full max-w-4xl group/map transition-transform duration-700 ease-out hover:scale-105 aspect-[2.5/1] md:aspect-[2/1] lg:aspect-[3.5/1]"
        >
          <img
            src="/img/indonesia.svg"
            alt="Ilustrasi Persebaran Alumni"
            class="absolute inset-0 w-full h-full object-fill pointer-events-none opacity-70 dark:opacity-40 transition-opacity duration-700 group-hover/map:opacity-100"
            style="
              filter: invert(56%) sepia(61%) saturate(3000%) hue-rotate(175deg)
                brightness(101%) contrast(101%)
                drop-shadow(0px 0px 8px rgba(56, 189, 248, 0.4));
            "
          />

          <template v-if="isLoading">
            <div
              v-for="(loc, index) in skeletonLocations"
              :key="'skeleton-map-' + index"
              class="absolute flex justify-center items-end w-8 h-10 md:w-10 md:h-12 -translate-x-1/2 -translate-y-full animate-pulse opacity-60"
              :style="{ top: loc.top, left: loc.left, zIndex: 10 }"
            >
              <div
                class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-4 h-1.5 md:w-5 md:h-2 bg-black/20 rounded-[100%] blur-[2px]"
              ></div>
              <div class="relative drop-shadow-[0_5px_8px_rgba(0,0,0,0.2)]">
                <PhMapPin weight="fill" class="w-8 h-8 md:w-10 md:h-10 text-slate-400" />
                <div
                  class="absolute top-[8px] left-1/2 -translate-x-1/2 w-2.5 h-2.5 md:top-[10px] md:w-3 md:h-3 bg-slate-300 rounded-full"
                ></div>
              </div>
            </div>
          </template>

          <template v-else>
            <div
              v-for="loc in alumniLocations"
              :key="loc.id"
              tabindex="0"
              class="absolute flex justify-center items-end group cursor-pointer hover:z-50 focus:z-50 w-8 h-10 md:w-10 md:h-12 -translate-x-1/2 -translate-y-full focus:outline-none"
              :style="{ top: loc.top, left: loc.left, zIndex: 10 }"
              @mouseenter="showTooltip($event, loc)"
              @mousemove="updateTooltipPos($event)"
              @mouseleave="hideTooltip"
              @touchstart.passive="showTooltip($event, loc)"
              @touchmove.passive="updateTooltipPos($event)"
              @touchend.passive="hideTooltip"
            >
              <div
                class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-4 h-1.5 md:w-5 md:h-2 bg-black/40 rounded-[100%] blur-[2px] group-hover:scale-50 transition-transform duration-300"
              ></div>
              <div
                class="relative drop-shadow-[0_5px_8px_rgba(0,0,0,0.4)] group-hover:-translate-y-2 transition-transform duration-300 origin-bottom"
              >
                <PhMapPin
                  weight="fill"
                  class="w-8 h-8 md:w-10 md:h-10"
                  :class="
                    loc.type === 'ptn'
                      ? 'text-sky-500'
                      : loc.type === 'kedinasan'
                      ? 'text-yellow-500'
                      : loc.type === 'instansi'
                      ? 'text-emerald-500'
                      : 'text-blue-500'
                  "
                />
                <div
                  class="absolute top-[8px] left-1/2 -translate-x-1/2 w-2.5 h-2.5 md:top-[10px] md:w-3 md:h-3 bg-white rounded-full"
                ></div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>

    <!-- TOOLTIP TELEPORT -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-opacity duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="tooltip.show && tooltip.data"
          class="fixed pointer-events-none z-[100] w-64 md:w-72 bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] border border-gray-100 dark:border-slate-700"
          :style="{
            top: tooltip.y + 'px',
            left: tooltip.x + 'px',
            transform: 'translate(-50%, -100%)',
          }"
        >
          <div
            class="absolute -bottom-2 -translate-x-1/2 w-4 h-4 bg-white dark:bg-slate-800 rotate-45 border-b border-r border-gray-100 dark:border-slate-700 rounded-sm transition-all"
            :style="{ left: `calc(50% + ${tooltip.tailOffset || 0}px)` }"
          ></div>
          <div class="flex flex-col relative z-10 max-h-64 overflow-y-auto pr-1">
            <h4
              class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-2 mb-2 flex items-center overflow-hidden"
            >
              <PhMapPin weight="fill" class="w-4 h-4 mr-1.5 text-blue-500 shrink-0" />
              <div class="text-marquee-container flex-1">
                <span class="text-marquee-content">{{ tooltip.data.name }}</span>
              </div>
            </h4>
            <div class="flex flex-col gap-2 mb-2">
              <div
                v-for="inst in tooltip.data.institutions"
                :key="inst.name"
                class="flex items-center gap-3 bg-gray-50 dark:bg-slate-700/50 p-2 rounded-lg border border-gray-100 dark:border-slate-600/50"
              >
                <div
                  class="w-8 h-8 rounded-full bg-white dark:bg-slate-700 border p-1 shrink-0 flex items-center justify-center"
                >
                  <img
                    :src="inst.logo || 'https://img.icons8.com/color/96/school.png'"
                    class="w-full h-full object-contain"
                  />
                </div>
                <div class="flex flex-col flex-1 min-w-0">
                  <div class="text-marquee-container">
                    <span
                      class="font-bold text-xs text-gray-900 dark:text-white leading-tight text-marquee-content"
                      >{{ inst.name }}</span
                    >
                  </div>
                  <span class="text-[10px] text-gray-500 font-medium">{{
                    inst.type === "ptn"
                      ? "PTN"
                      : inst.type === "kedinasan"
                      ? "Kedinasan"
                      : "Instansi & BUMN"
                  }}</span>
                </div>
                <div
                  class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded shrink-0"
                >
                  {{ inst.alumni }}
                </div>
              </div>
            </div>
            <div
              class="bg-blue-50 rounded-lg py-2 px-3 flex justify-between items-center border border-blue-100 mt-1"
            >
              <span class="text-[11px] font-medium text-blue-600"
                >Total Alumni di Kawasan</span
              >
              <span class="font-bold text-blue-700 text-xs"
                >{{ tooltip.data.totalAlumni }} Orang</span
              >
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<style scoped>
.text-marquee-container {
  container-type: inline-size;
  overflow: hidden;
  white-space: nowrap;
  width: 100%;
}
.text-marquee-content {
  display: inline-block;
  min-width: 100%;
  animation: slide-text 5s linear infinite alternate;
}
@keyframes slide-text {
  0%,
  20% {
    transform: translateX(0);
  }
  80%,
  100% {
    transform: translateX(min(0px, calc(100cqw - 100%)));
  }
}
</style>
