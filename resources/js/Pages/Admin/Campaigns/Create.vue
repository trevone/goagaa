<script setup>
import FormFields from './FormFields.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Save from '@/Components/buttons/SaveForm.vue';
import Back from '@/Components/buttons/Back.vue';

const props = defineProps({
  errors: Object,
})

const form = useForm({
  title: null,
  slug: null,
})
</script>


<template>

  <Head title="Subjects" />
  <div>
    <head-row heading="Subjects / Create new" class="mb-3" />

    <b-row>
      <b-colxx xxs="12">

        <b-card class="mb-4" title="">
          <form @submit.prevent="form.submit('post', route('admin.subjects.store'), { replace: true })">

            <FormFields v-bind="props" :form="form" />

            <b-colxx xxs="12" class="text-end">
              <Back>Cancel</Back>
              <Save :form="form" />
            </b-colxx>

          </form>
        </b-card>
      </b-colxx>
    </b-row>
  </div>

</template>
