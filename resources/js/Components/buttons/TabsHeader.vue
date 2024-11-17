<script setup>

const props = defineProps({
  active: String,
  options: {
    type: Array,
    default: []
  }
})

</script>

<template>
  <Link v-for="(tab, index) in options"
    class="btn btn-outline-dark btn-xs mb-0 me-1 rounded-bottom-0 rounded-top-3 border-bottom-0"
    :class="{ 'active': active == tab.ref }"
    :href="tab.href"
  >{{ tab.label }}</Link>
</template>
