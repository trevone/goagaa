<script setup>

const props = defineProps({
  loading: {
    type: Boolean,
    default: false,
  },
  label: {
    type: String,
    default: 'Save'
  },
  size: {
    type: String,
    default: 'xs'
  },
  variant: {
    type: String,
    default: 'primary'
  },
})

</script>

<template>
  <b-button type="submit" :variant="variant" :size="size" :disabled="loading" :class="{
    'btn-multiple-state btn-shadow': true,
    'show-spinner': loading,
  }">
    <span class="spinner d-inline-block">
      <span class="bounce1"></span>
      <span class="bounce2"></span>
      <span class="bounce3"></span>
    </span>
    <span class="icon success">
      <i class="simple-icon-check"></i>
    </span>
    <span class="icon fail">
      <i class="simple-icon-exclamation"></i>
    </span>
    <span class="label" >{{ label }}</span>
  </b-button>
</template>
