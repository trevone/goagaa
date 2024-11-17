<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const emit = defineEmits(['success'])

const props = defineProps({
  theroute: String,
  id: Number,
})

const loading = ref(false)

function deleteAsset() {

  loading.value = true

  axios.request({
        method: 'delete',
        url: props.theroute,
        data: {
          id: props.id,
          user: 'trt'
      }})
      .then(response => {
        // handle success
        if (response.status == 200 && response.data.status == 'success') {
          usePage().props.flash.message = response.data.message
          usePage().props.flash.status = response.data.status
          success()
        } else {
          // handle error
          usePage().props.flash.message = response.data.message
          usePage().props.flash.status = response.data.status
        }
        loading.value = false
      })
      .catch(error => {
        // handle error
        usePage().props.flash.message = 'Could not load resources: ' + error.message
        usePage().props.flash.status = 'danger'
        loading.value = false
      });
}


function success() {
console.log('deleted')
  loading.value = false
  emit('success')
}
</script>

<template>
  <b-button type="button"
    variant="danger"
    size="xs"
    :disabled="loading"
     @click.prevent="deleteAsset"
     class="ms-2"
    :class="{
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
    <span class="label" >Delete</span>
  </b-button>
</template>
