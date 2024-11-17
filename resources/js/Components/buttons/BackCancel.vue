<script setup>
import { usePage, router } from '@inertiajs/vue3';

const props = defineProps({
  cancel: Boolean,
  url: String,
  size: {
    type: String,
    default: 'xs'
  }
})

function action()
{
  if (props.cancel) {
    router.get(props.url, {}, { preserveState: true })
  }
  else {
    if(usePage().props.backUrl != null) {
      router.get(usePage().props.backUrl, {}, { preserveState: true })
    } else {
      window.history.back();
    }
  }
}
</script>

<template>
  <button @click.prevent="action" :class="'btn btn-outline-primary btn-'+size+' ms-2'">
    <template v-if="cancel">
      <slot>Cancel</slot>

    </template>
    <template v-else>
      <slot>Back</slot>

    </template>
  </button>
</template>
