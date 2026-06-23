<script setup>
import { ref, computed, nextTick } from "vue";
import {
  PhCaretLeft,
  PhCaretRight,
  PhCalendar,
  PhArrowDown,
  PhPaperclip,
} from "@phosphor-icons/vue";

const props = defineProps({
  agendas: { type: Array, required: true },
  isLoadingAgendas: { type: Boolean, required: true },
});

const agendaListContainer = ref(null);
const agendaElements = ref({});
const highlightedAgendaId = ref(null);

const currentDisplayedDate = ref(new Date());

const themeClasses = {
  akademik: {
    card:
      "bg-white border-gray-100 hover:border-slate-300 hover:shadow-slate-100/50 dark:bg-slate-800 dark:border-slate-700 dark:hover:border-slate-500/50",
    dateBox: "border-slate-600 border-b-4",
    monthBg: "bg-slate-600 group-hover:bg-slate-700",
    monthText: "text-white",
    dateBg: "bg-slate-50",
    dateText: "text-slate-700 group-hover:text-slate-900",
    title:
      "text-gray-900 group-hover:text-slate-800 dark:text-slate-100 dark:group-hover:text-slate-200",
    infoIcon: "text-slate-500",
    infoText: "text-gray-700 dark:text-slate-300",
    fileBtn:
      "bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100 dark:bg-slate-900/30 dark:text-slate-400 dark:border-slate-700/50 dark:hover:bg-slate-900/50",
    eventBg:
      "bg-slate-100 hover:bg-slate-200 dark:bg-slate-900/30 dark:hover:bg-slate-900/50",
    eventHeaderBg: "bg-slate-600 dark:bg-slate-700",
    eventHeaderText: "text-white drop-shadow-md",
    eventDayText: "text-slate-700 dark:text-slate-400",
  },
  "guru-staf": {
    card:
      "bg-white border-gray-100 hover:border-gray-300 hover:shadow-gray-100/50 dark:bg-slate-800 dark:border-slate-700 dark:hover:border-gray-500/50",
    dateBox: "border-gray-600 border-b-4",
    monthBg: "bg-gray-600 group-hover:bg-gray-700",
    monthText: "text-white",
    dateBg: "bg-gray-50",
    dateText: "text-gray-700 group-hover:text-gray-900",
    title:
      "text-gray-900 group-hover:text-gray-800 dark:text-slate-100 dark:group-hover:text-gray-200",
    infoIcon: "text-gray-500",
    infoText: "text-gray-700 dark:text-slate-300",
    fileBtn:
      "bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100 dark:bg-gray-900/30 dark:text-gray-400 dark:border-gray-700/50 dark:hover:bg-gray-900/50",
    eventBg:
      "bg-gray-100 hover:bg-gray-200 dark:bg-gray-900/30 dark:hover:bg-gray-900/50",
    eventHeaderBg: "bg-gray-600 dark:bg-gray-700",
    eventHeaderText: "text-white drop-shadow-md",
    eventDayText: "text-gray-700 dark:text-gray-400",
  },
  kegiatan: {
    card:
      "bg-white border-gray-100 hover:border-zinc-300 hover:shadow-zinc-100/50 dark:bg-slate-800 dark:border-slate-700 dark:hover:border-zinc-500/50",
    dateBox: "border-zinc-600 border-b-4",
    monthBg: "bg-zinc-600 group-hover:bg-zinc-700",
    monthText: "text-white",
    dateBg: "bg-zinc-50",
    dateText: "text-zinc-700 group-hover:text-zinc-900",
    title:
      "text-gray-900 group-hover:text-zinc-800 dark:text-slate-100 dark:group-hover:text-zinc-200",
    infoIcon: "text-zinc-500",
    infoText: "text-gray-700 dark:text-slate-300",
    fileBtn:
      "bg-zinc-50 text-zinc-700 border border-zinc-200 hover:bg-zinc-100 dark:bg-zinc-900/30 dark:text-zinc-400 dark:border-zinc-700/50 dark:hover:bg-zinc-900/50",
    eventBg:
      "bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-900/30 dark:hover:bg-zinc-900/50",
    eventHeaderBg: "bg-zinc-600 dark:bg-zinc-700",
    eventHeaderText: "text-white drop-shadow-md",
    eventDayText: "text-zinc-700 dark:text-zinc-400",
  },
  acara: {
    card:
      "bg-white border-gray-100 hover:border-neutral-300 hover:shadow-neutral-100/50 dark:bg-slate-800 dark:border-slate-700 dark:hover:border-neutral-500/50",
    dateBox: "border-neutral-600 border-b-4",
    monthBg: "bg-neutral-600 group-hover:bg-neutral-700",
    monthText: "text-white",
    dateBg: "bg-neutral-50",
    dateText: "text-neutral-700 group-hover:text-neutral-900",
    title:
      "text-gray-900 group-hover:text-neutral-800 dark:text-slate-100 dark:group-hover:text-neutral-200",
    infoIcon: "text-neutral-500",
    infoText: "text-gray-700 dark:text-slate-300",
    fileBtn:
      "bg-neutral-50 text-neutral-700 border border-neutral-200 hover:bg-neutral-100 dark:bg-neutral-900/30 dark:text-neutral-400 dark:border-neutral-700/50 dark:hover:bg-neutral-900/50",
    eventBg:
      "bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-900/30 dark:hover:bg-neutral-900/50",
    eventHeaderBg: "bg-neutral-600 dark:bg-neutral-700",
    eventHeaderText: "text-white drop-shadow-md",
    eventDayText: "text-neutral-700 dark:text-neutral-400",
  },
};

const colSpanClasses = {
  1: "col-span-1",
  2: "col-span-2",
  3: "col-span-3",
  4: "col-span-4",
  5: "col-span-5",
  6: "col-span-6",
  7: "col-span-7",
};

const calendarData = computed(() => {
  const date = currentDisplayedDate.value;
  return {
    monthName: new Intl.DateTimeFormat("id-ID", { month: "long" }).format(date),
    year: date.getFullYear(),
    daysInMonth: new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate(),
    firstDayOffset: new Date(date.getFullYear(), date.getMonth(), 1).getDay(),
  };
});

const prevMonth = () => {
  const d = currentDisplayedDate.value;
  currentDisplayedDate.value = new Date(d.getFullYear(), d.getMonth() - 1, 1);
};
const nextMonth = () => {
  const d = currentDisplayedDate.value;
  currentDisplayedDate.value = new Date(d.getFullYear(), d.getMonth() + 1, 1);
};

const calendarGrid = computed(() => {
  const year = currentDisplayedDate.value.getFullYear();
  const month = currentDisplayedDate.value.getMonth();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const firstDayOffset = new Date(year, month, 1).getDay();

  const gridItems = [];
  for (let i = 0; i < firstDayOffset; i++) gridItems.push({ type: "empty" });

  let currentDay = 1;
  while (currentDay <= daysInMonth) {
    const currentDayDate = new Date(year, month, currentDay);
    currentDayDate.setHours(0, 0, 0, 0);

    const eventOnDay = props.agendas.find((agenda) => {
      if (!agenda.startDate) return false;
      const start = new Date(agenda.startDate);
      start.setHours(0, 0, 0, 0);
      const end = agenda.endDate ? new Date(agenda.endDate) : new Date(start);
      end.setHours(0, 0, 0, 0);
      return currentDayDate >= start && currentDayDate <= end;
    });

    if (eventOnDay) {
      const start = new Date(eventOnDay.startDate);
      start.setHours(0, 0, 0, 0);
      const end = eventOnDay.endDate ? new Date(eventOnDay.endDate) : new Date(start);
      end.setHours(0, 0, 0, 0);

      const daysLeftInWeek = 7 - currentDayDate.getDay();
      let span = 1;
      for (let i = 1; i < daysLeftInWeek; i++) {
        const nextDayDate = new Date(year, month, currentDay + i);
        nextDayDate.setHours(0, 0, 0, 0);
        if (currentDay + i > daysInMonth) break;
        if (nextDayDate >= start && nextDayDate <= end) span++;
        else break;
      }

      const daysArr = Array.from({ length: span }, (_, i) => currentDay + i);
      gridItems.push({ type: "event", span, days: daysArr, event: eventOnDay });
      currentDay += span;
    } else {
      gridItems.push({ type: "day", day: currentDay });
      currentDay++;
    }
  }
  return gridItems;
});

const scrollToAgenda = (agendaToScrollTo) => {
  if (!agendaListContainer.value || !agendaToScrollTo) return;
  if (highlightedAgendaId.value === agendaToScrollTo.id) {
    highlightedAgendaId.value = null;
    return;
  }
  highlightedAgendaId.value = agendaToScrollTo.id;
  nextTick(() => {
    const targetElement = agendaElements.value[agendaToScrollTo.id];
    if (targetElement) {
      targetElement.scrollIntoView({
        top: targetElement.offsetTop - 24,
        behavior: "smooth",
        block: "center",
      });
    }
  });
};
</script>

<template>
  <section
    class="relative py-8 md:py-6 pb-16 md:pb-0 mt-0 md:mt-16 -mx-6 md:mx-0 bg-white dark:bg-slate-800 overflow-hidden px-6 md:px-2 mb-0 md:mb-12 md:rounded-xl shadow-xl fade-on-scroll"
  >
    <div class="container mx-auto px-0 md:px-2">
      <!-- Header Section -->
      <div class="mb-6 md:mb-10">
        <div class="relative block">
          <h2
            class="text-2xl md:text-4xl font-bold text-slate-800 dark:text-white pl-4 border-l-4 border-blue-600 dark:border-blue-500 mb-4 sm:mb-2"
            style="font-family: 'Oswald', sans-serif"
          >
            Agenda Sekolah
          </h2>
          <p
            class="text-slate-800/80 dark:text-slate-300 text-sm md:text-base ml-0 sm:ml-5 max-w-2xl"
          >
            Jadwal kegiatan akademik dan non-akademik. Pantau kalender agar tidak
            tertinggal event penting kami.
          </p>
        </div>
      </div>

      <!-- Main Grid Layout -->
      <div
        class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-stretch md:pb-8 md:pr-2"
      >
        <!-- KIRI: Kalender -->
        <div
          class="lg:col-span-5 bg-white dark:bg-slate-800 rounded-lg shadow-md border border-gray-200 dark:border-slate-700 p-5 md:p-6 flex flex-col"
        >
          <div class="flex items-center justify-between mb-6">
            <button
              @click="prevMonth"
              class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors focus:outline-none"
            >
              <PhCaretLeft class="w-5 h-5 text-gray-600 dark:text-gray-400" />
            </button>
            <h3 class="text-lg font-bold text-blue-950 dark:text-blue-200">
              {{ calendarData.monthName }} {{ calendarData.year }}
            </h3>
            <button
              @click="nextMonth"
              class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors focus:outline-none"
            >
              <PhCaretRight class="w-5 h-5 text-gray-600 dark:text-gray-400" />
            </button>
          </div>

          <div
            class="grid grid-cols-7 gap-2 text-center mb-2 text-xs font-bold py-1 text-gray-500 dark:text-gray-400"
          >
            <div class="text-red-500 dark:text-red-400">Min</div>
            <div>Sen</div>
            <div>Sel</div>
            <div>Rab</div>
            <div>Kam</div>
            <div>Jum</div>
            <div>Sab</div>
          </div>

          <div class="grid grid-cols-7 gap-y-2 text-sm text-center">
            <template v-if="isLoadingAgendas">
              <div
                v-for="i in 35"
                :key="'skel-cal-' + i"
                class="py-4 mx-0.5 rounded-lg bg-slate-100 dark:bg-slate-700/50 animate-pulse"
              ></div>
            </template>
            <template v-else>
              <template v-for="(item, index) in calendarGrid" :key="'grid-' + index">
                <div v-if="item.type === 'empty'" class="py-2"></div>
                <div
                  v-else-if="item.type === 'day'"
                  class="py-2 mx-0.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
                >
                  {{ item.day }}
                </div>
                <div
                  v-else-if="item.type === 'event'"
                  :class="[
                    colSpanClasses[item.span],
                    'relative mx-0.5 my-0.5 rounded-md overflow-hidden cursor-pointer transition-colors flex flex-col justify-between',
                    themeClasses[item.event.color].eventBg,
                  ]"
                  @click="scrollToAgenda(item.event)"
                  :title="item.event.title"
                >
                  <div
                    :class="[
                      themeClasses[item.event.color].eventHeaderBg,
                      themeClasses[item.event.color].eventHeaderText,
                      'text-[10px] font-bold px-2 py-0.5 text-left truncate',
                    ]"
                  >
                    {{ item.event.title }}
                  </div>
                  <div
                    class="grid w-full py-1"
                    :style="`grid-template-columns: repeat(${item.span}, minmax(0, 1fr))`"
                  >
                    <div
                      v-for="d in item.days"
                      :key="'day-' + d"
                      :class="[themeClasses[item.event.color].eventDayText, 'font-bold']"
                    >
                      {{ d }}
                    </div>
                  </div>
                </div>
              </template>
            </template>
          </div>

          <!-- Keterangan Label -->
          <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-700">
            <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">
              Keterangan Label
            </h4>
            <div class="flex flex-wrap gap-3">
              <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                <span class="w-3 h-3 rounded-sm bg-slate-600 mr-2"></span> Akademik
              </div>
              <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                <span class="w-3 h-3 rounded-sm bg-gray-600 mr-2"></span> Guru/Staf
              </div>
              <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                <span class="w-3 h-3 rounded-sm bg-zinc-600 mr-2"></span> Kegiatan
              </div>
              <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                <span class="w-3 h-3 rounded-sm bg-neutral-600 mr-2"></span> Seminar &
                Acara
              </div>
            </div>
          </div>
        </div>

        <!-- KANAN: Daftar Agenda -->
        <div class="lg:col-span-7 relative h-[540px] md:h-[640px] lg:h-auto">
          <div
            class="absolute inset-0 bg-white dark:bg-slate-800 rounded-lg shadow-md border border-gray-200 dark:border-slate-700 overflow-hidden flex flex-col"
          >
            <div
              class="p-5 md:p-6 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex items-center shrink-0"
            >
              <PhCalendar class="w-6 h-6 mr-2 text-slate-600 dark:text-slate-200" />
              <h3
                class="text-lg md:text-xl font-bold text-slate-800 dark:text-slate-200"
                style="font-family: 'Poppins', sans-serif"
              >
                Daftar Agenda
              </h3>
            </div>

            <div class="relative flex-1 min-h-0" ref="agendaListContainer">
              <div
                class="absolute inset-0 p-5 md:p-6 flex flex-col gap-4 overflow-y-auto [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-slate-600 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300"
              >
                <template v-if="isLoadingAgendas">
                  <div
                    v-for="i in 4"
                    :key="'skel-ag-' + i"
                    class="flex flex-row items-center gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl border border-gray-100 dark:border-slate-700 animate-pulse h-[115px] sm:h-auto"
                  >
                    <div
                      class="w-[65px] h-[80px] sm:w-[95px] sm:h-[95px] rounded-xl bg-slate-200 dark:bg-slate-700 shrink-0"
                    ></div>
                    <div class="flex flex-col flex-1 space-y-2">
                      <div class="h-5 w-3/4 bg-slate-200 dark:bg-slate-700 rounded"></div>
                      <div
                        class="h-4 w-1/2 bg-slate-200 dark:bg-slate-700 rounded mt-2"
                      ></div>
                      <div class="h-4 w-2/3 bg-slate-200 dark:bg-slate-700 rounded"></div>
                    </div>
                  </div>
                </template>
                <template v-else-if="agendas.length > 0">
                  <div
                    v-for="(agenda, index) in agendas"
                    :key="index"
                    :ref="
                      (el) => {
                        if (el) agendaElements[agenda.id] = el;
                      }
                    "
                    class="flex flex-row items-center gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl border hover:shadow-md transition-all duration-300 shrink-0 relative"
                    :class="[
                      themeClasses[agenda.color].card,
                      highlightedAgendaId === agenda.id ? 'highlight-agenda' : '',
                    ]"
                  >
                    <div
                      class="rounded-xl flex flex-col items-center justify-center min-w-[65px] w-[65px] sm:min-w-[95px] sm:w-[95px] border overflow-hidden shrink-0"
                      :class="themeClasses[agenda.color].dateBox"
                    >
                      <div
                        class="w-full text-center py-1 sm:py-1.5"
                        style="font-family: 'Kalam', cursive"
                        :class="themeClasses[agenda.color].monthBg"
                      >
                        <span
                          class="text-[10px] sm:text-xs font-bold uppercase tracking-wider"
                          :class="themeClasses[agenda.color].monthText"
                          >{{ agenda.month }}</span
                        >
                      </div>
                      <div
                        class="w-full text-center py-1.5 sm:py-3 flex items-center justify-center min-h-[48px]"
                        :class="themeClasses[agenda.color].dateBg"
                      >
                        <span
                          class="font-extrabold leading-none tracking-tight"
                          :class="[
                            themeClasses[agenda.color].dateText,
                            agenda.date.length > 2
                              ? 'text-lg sm:text-xl'
                              : 'text-xl sm:text-3xl',
                          ]"
                          style="font-family: 'Kalam', cursive"
                          >{{ agenda.date }}</span
                        >
                      </div>
                    </div>

                    <div
                      class="flex flex-col justify-center flex-1"
                      style="font-family: 'Poppins', sans-serif"
                    >
                      <h4
                        class="font-bold text-sm md:text-base leading-tight mb-1"
                        :class="themeClasses[agenda.color].title"
                      >
                        {{ agenda.title }}
                      </h4>
                      <div
                        class="flex flex-col gap-1.5 mt-2"
                        :class="themeClasses[agenda.color].infoText"
                      >
                        <div class="flex items-start text-[11px] sm:text-xs">
                          <svg
                            class="w-4 h-4 mr-2 mt-0.5 shrink-0"
                            :class="themeClasses[agenda.color].infoIcon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                          >
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                          </svg>
                          <span class="font-medium text-gray-600 dark:text-gray-300">{{
                            agenda.time
                          }}</span>
                        </div>
                        <div class="flex items-start text-[11px] sm:text-xs">
                          <svg
                            class="w-4 h-4 mr-2 mt-0.5 shrink-0"
                            :class="themeClasses[agenda.color].infoIcon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                          >
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                            <circle cx="12" cy="10" r="3" />
                          </svg>
                          <span class="font-medium text-gray-600 dark:text-gray-300">{{
                            agenda.loc
                          }}</span>
                        </div>
                        <div
                          v-if="agenda.file"
                          class="flex items-start text-[11px] sm:text-xs pt-1"
                        >
                          <PhPaperclip
                            class="w-4 h-4 mr-2 mt-0.5 shrink-0"
                            :class="themeClasses[agenda.color].infoIcon"
                          />
                          <a
                            :href="agenda.file"
                            target="_blank"
                            class="font-medium text-blue-600 hover:underline"
                            >Lihat Dokumen</a
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <div class="p-6 text-center text-gray-500 m-auto">
                    Tidak ada agenda saat ini.
                  </div>
                </template>
              </div>
              <div
                class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white dark:from-slate-800 to-transparent pointer-events-none z-10 flex justify-center items-end"
              >
                <div
                  class="text-sm font-semibold text-gray-500 flex items-center animate-bounce pb-2"
                >
                  <PhArrowDown class="w-4 h-4 mr-1.5" /> Scroll ke bawah
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.highlight-agenda {
  box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
  transform: translateY(-4px) scale(1.01);
  z-index: 10;
}
</style>
