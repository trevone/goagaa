<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  theroute: String,
  id: Number,
  title: String,
  copy: String,
  confirm: Boolean,
  disabled: Boolean,
})

const form = useForm({
  id: props.id,
  user: 'trt',
})

const confirmed = ref(false)

function doConfirm() {
  console.log(props.confirm)
  if(props.confirm == true) {
    confirmed.value = true
  } else {
    submit()
  }
}

function submit() {
  form.delete(props.theroute)
}
</script>

<template>
  <button class="btn btn-danger btn-xs ms-2" @click.prevent="doConfirm" :disabled="disabled">Delete</button>

  <b-modal :modelValue="confirmed" :title="props.title ?? 'Delate asset?'" modal-class="modal-right" hideFooter size="xs">

    <p>Are you sure?</p>
    <div v-if="!copy"><p>Deleting an asset can have profound consequences: for instance, if you delete an user who created brochures, you will delete all the brochures as well.<br /><br />
    Or if you delete an image it might be missing form the live brochure...<br /><br />
    Please make sure that is safe to delete the asset.</p></div>
    <div v-else v-html="copy"></div>

    <div class="mt-5 text-end">

      <hr />

      <b-button variant="primary" size="sm" class="me-2" @click="confirmed = false">Cancel</b-button>
      <b-button variant="outline-primary" size="sm" class="me-2" @click="submit">I am sure!</b-button>
    </div>

    <template #footer>
    </template>
  </b-modal>
</template>
