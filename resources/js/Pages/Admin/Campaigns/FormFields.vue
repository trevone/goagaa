<script setup>

const props = defineProps({
  subject: Object,
  form: Object,
})

</script>


<template>

  <b-form-group class="mb-3" label-cols="2" label="title">
    <b-form-input type="text" v-model="form.title" placeholder="title [text]" dusk="title" @input="form.errors.title = null"></b-form-input>
    <b-form-invalid-feedback :state="!form.errors.title">{{ form.errors.title }}</b-form-invalid-feedback>
  </b-form-group>

  <b-form-group class="mb-3" label-cols="2" label="slug">
    <b-form-input type="text" v-model="form.slug" placeholder="leave empty to auto-generate [alpha-dash]" dusk="slug" @input="form.errors.slug = null"></b-form-input>
    <b-form-invalid-feedback :state="!form.errors.slug">{{ form.errors.slug }}</b-form-invalid-feedback>
  </b-form-group>

  <hr class="mb-3" />

</template>
