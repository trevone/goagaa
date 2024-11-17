<script setup>
function back()
{
    window.history.back();
}
</script>

<template>
  <button @click.prevent="back" class="btn btn-outline-primary btn-xs ms-2"><slot>Back</slot></button>
</template>
