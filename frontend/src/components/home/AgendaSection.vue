<script setup>
import { ref, computed, nextTick } from "vue";
import {
  PhCaretLeft,
  PhCaretRight,
  PhCalendar,
  PhArrowDown,
  PhPaperclip,
  PhClock,
  PhMapPin,
} from "@phosphor-icons/vue";

const props = defineProps({
  agendas: { type: Array, required: true },
  isLoadingAgendas: { type: Boolean, required: true },
});

const agendaListContainer = ref(null);
const agendaElements = ref({});
const highlightedAgendaId = ref(null);

const currentDisplayedDate = ref(new Date());

// Desain Profesional: Warna pastel untuk lencana/chip
const themeClasses = {
  yellow: {
    badge:
      "bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20",
    dot: "bg-amber-500",
  },
  red: {
    badge:
      "bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20",
    dot: "bg-rose-500",
  },
  green: {
    badge:
      "bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20",
    dot: "bg-emerald-500",
  },
  blue: {
    badge:
      "bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20",
    dot: "bg-blue-500",
  },
};

const getCategoryName = (color) => {
  const map = {
    yellow: "Akademik",
    red: "Guru & Staf",
    green: "Kegiatan",
    blue: "Seminar & Acara",
  };
  return map[color] || "Agenda";
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

// Fungsi untuk Sembunyikan URL dengan Blob
const openDocument = async (url) => {
  if (!url) return;

  try {
    const response = await fetch(url);
    const blob = await response.blob();

    // Bikin URL lokal browser yang pakai domain frontend
    const blobUrl = URL.createObjectURL(blob);
    window.open(blobUrl, "_blank");

    // Revoke object url untuk hemat memori
    setTimeout(() => URL.revokeObjectURL(blobUrl), 10000);
  } catch (error) {
    // Kalau diblokir CORS atau gagal, buka dengan cara biasa
    window.open(url, "_blank");
  }
};
</script>

<template>
  <section
    class="relative py-8 md:py-6 pb-16 md:pb-0 mt-0 md:mt-16 -mx-6 md:mx-0 bg-white dark:bg-slate-800 overflow-hidden px-6 mb-0 md:mb-12 md:rounded-xl shadow-xl fade-on-scroll"
  >
    <div class="w-full max-w-full container z-10 mx-auto">
      <div class="mb-6 md:mt-2 md:mb-10">
        <div class="relative block">
          <h2
            class="text-2xl md:text-4xl font-bold text-slate-800 dark:text-white mb-4 sm:mb-2"
            style="font-family: 'Oswald', sans-serif"
          >
            Agenda Sekolah
          </h2>
          <p class="text-slate-800/80 dark:text-slate-300 text-sm md:text-base max-w-2xl">
            Jadwal kegiatan akademik dan non-akademik. Pantau kalender agar tidak
            tertinggal event penting kami.
          </p>
        </div>
      </div>

      <div
        class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-stretch md:pb-8 md:pr-2"
      >
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
            <h3
              class="text-lg font-bold text-slate-800 dark:text-white"
              style="font-family: 'Plus Jakarta Sans', sans-serif"
            >
              {{ calendarData.monthName }}
              <span class="font-medium text-slate-500">{{ calendarData.year }}</span>
            </h3>
            <button
              @click="nextMonth"
              class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors focus:outline-none"
            >
              <PhCaretRight class="w-5 h-5 text-gray-600 dark:text-gray-400" />
            </button>
          </div>

          <div
            class="grid grid-cols-7 gap-1 text-center mb-3 text-[11px] font-bold uppercase tracking-wider text-slate-400"
          >
            <div class="text-red-400">Min</div>
            <div>Sen</div>
            <div>Sel</div>
            <div>Rab</div>
            <div>Kam</div>
            <div>Jum</div>
            <div>Sab</div>
          </div>

          <div class="grid grid-cols-7 gap-y-2 gap-x-1 text-sm text-center">
            <template v-if="isLoadingAgendas">
              <div
                v-for="i in 35"
                :key="'skel-cal-' + i"
                class="py-4 rounded-lg bg-slate-100 dark:bg-slate-700/50 animate-pulse"
              ></div>
            </template>
            <template v-else>
              <template v-for="(item, index) in calendarGrid" :key="'grid-' + index">
                <div v-if="item.type === 'empty'" class="py-2"></div>
                <div
                  v-else-if="item.type === 'day'"
                  class="py-2 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 cursor-pointer transition-colors font-medium"
                >
                  {{ item.day }}
                </div>
                <div
                  v-else-if="item.type === 'event'"
                  :class="[
                    colSpanClasses[item.span],
                    'relative py-1 rounded-lg group cursor-pointer',
                  ]"
                  @click="scrollToAgenda(item.event)"
                  :title="item.event.title"
                >
                  <div
                    class="absolute inset-0 bg-slate-50 dark:bg-slate-700/30 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"
                  ></div>
                  <div
                    class="grid w-full relative z-10"
                    :style="`grid-template-columns: repeat(${item.span}, minmax(0, 1fr))`"
                  >
                    <div
                      v-for="d in item.days"
                      :key="'day-' + d"
                      class="py-1 text-slate-900 dark:text-white font-bold"
                    >
                      {{ d }}
                    </div>
                  </div>
                  <div
                    :class="[
                      'relative z-10 mx-0.5 mt-0.5 px-1 sm:px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-semibold truncate',
                      themeClasses[item.event.color].badge,
                    ]"
                  >
                    {{ item.event.title }}
                  </div>
                </div>
              </template>
            </template>
          </div>

          <div class="mt-8 pt-5 border-t border-gray-100 dark:border-slate-700">
            <div
              class="flex flex-wrap gap-x-4 gap-y-2 text-[11px] font-medium text-slate-500 dark:text-slate-400"
            >
              <div class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span> Akademik
              </div>
              <div class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span> Guru/Staf
              </div>
              <div class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Kegiatan
              </div>
              <div class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Seminar &
                Acara
              </div>
            </div>
          </div>
        </div>

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
                style="font-family: 'Plus Jakarta Sans', sans-serif"
              >
                Daftar Agenda
              </h3>
            </div>

            <div class="relative flex-1 min-h-0" ref="agendaListContainer">
              <div
                class="absolute inset-0 p-5 md:p-6 flex flex-col gap-4 overflow-y-auto custom-scrollbar pr-2 sm:pr-4"
              >
                <template v-if="isLoadingAgendas">
                  <div
                    v-for="i in 4"
                    :key="'skel-ag-' + i"
                    class="flex p-4 sm:p-5 rounded-xl border border-gray-100 dark:border-slate-700 animate-pulse h-[115px] sm:h-auto"
                  >
                    <div
                      class="w-20 h-16 rounded-lg bg-slate-200 dark:bg-slate-700 shrink-0"
                    ></div>
                    <div class="flex-1 ml-4 space-y-3 py-1">
                      <div
                        class="h-4 w-24 bg-slate-200 dark:bg-slate-700 rounded-full"
                      ></div>
                      <div class="h-5 w-3/4 bg-slate-200 dark:bg-slate-700 rounded"></div>
                      <div class="h-4 w-1/2 bg-slate-200 dark:bg-slate-700 rounded"></div>
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
                    class="flex flex-row items-stretch gap-2 sm:gap-4 p-4 sm:p-5 rounded-xl bg-white dark:bg-slate-800 border transition-all duration-300 relative group"
                    :class="[
                      highlightedAgendaId === agenda.id
                        ? 'border-blue-300 dark:border-blue-500 shadow-md ring-4 ring-blue-50 dark:ring-blue-900/20'
                        : 'border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-slate-300 dark:hover:border-slate-600',
                    ]"
                  >
                    <div
                      class="flex flex-col items-center justify-start pt-1 w-[85px] sm:w-[110px] shrink-0 text-center overflow-hidden"
                    >
                      <span
                        class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-0.5"
                      >
                        {{ agenda.month }}
                      </span>
                      <span
                        class="text-2xl sm:text-3xl font-light text-slate-800 dark:text-white leading-none tracking-tighter whitespace-nowrap"
                      >
                        {{ agenda.date }}
                      </span>
                    </div>

                    <div
                      class="w-px bg-slate-100 dark:bg-slate-700 self-stretch mx-1 sm:mx-3 hidden sm:block"
                    ></div>

                    <div
                      class="flex flex-col flex-1 pl-1 sm:pl-0"
                      style="font-family: 'Plus Jakarta Sans', sans-serif"
                    >
                      <div class="mb-2">
                        <span
                          :class="[
                            'text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wide',
                            themeClasses[agenda.color].badge,
                          ]"
                        >
                          {{ getCategoryName(agenda.color) }}
                        </span>
                      </div>

                      <h4
                        class="font-bold text-sm md:text-base text-slate-900 dark:text-white leading-snug mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                      >
                        {{ agenda.title }}
                      </h4>

                      <div
                        class="flex flex-col gap-2 text-xs md:text-[13px] text-slate-500 dark:text-slate-400 font-medium mt-auto"
                      >
                        <div class="flex items-center">
                          <PhClock class="w-4 h-4 mr-2.5 shrink-0 text-slate-400" />
                          <span>{{ agenda.time }}</span>
                        </div>
                        <div class="flex items-start">
                          <PhMapPin
                            class="w-4 h-4 mr-2.5 mt-0.5 shrink-0 text-slate-400"
                          />
                          <span class="leading-tight">{{ agenda.loc }}</span>
                        </div>

                        <div
                          v-if="agenda.attachment"
                          class="flex items-center mt-1 pt-2 border-t border-slate-100 dark:border-slate-700"
                        >
                          <PhPaperclip class="w-4 h-4 mr-2.5 shrink-0 text-blue-500" />
                          <button
                            @click.prevent="openDocument(agenda.attachment)"
                            class="text-blue-600 hover:text-blue-700 hover:underline transition-colors text-left focus:outline-none"
                          >
                            Lihat Dokumen
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </template>

                <template v-else>
                  <div
                    class="p-8 text-center flex flex-col items-center justify-center m-auto w-full"
                  >
                    <PhCalendar
                      class="w-10 h-10 text-slate-300 dark:text-slate-600 mb-2"
                    />
                    <p class="text-slate-500 text-sm font-medium">
                      Tidak ada agenda saat ini.
                    </p>
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
@import url("https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap");

/* Custom scrollbar yang elegan */
.custom-scrollbar::-webkit-scrollbar {
  width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1; /* slate-300 */
  border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #475569; /* slate-600 */
}
.custom-scrollbar:hover::-webkit-scrollbar-thumb {
  background-color: #94a3b8; /* slate-400 */
}
</style>
