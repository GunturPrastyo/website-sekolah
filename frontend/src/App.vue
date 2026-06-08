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
html,
body,
#app {
  font-family: "Poppins", sans-serif;
}
</style>
