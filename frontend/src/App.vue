<template>
  <router-view />
</template>

<script setup>
import { onMounted, watch, nextTick } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();

watch(
  route,
  () => {
    nextTick(() => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  },
  { immediate: true, deep: true }
);
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap");

html,
body,
#app {
  font-family: "Poppins", sans-serif;
}
</style>
