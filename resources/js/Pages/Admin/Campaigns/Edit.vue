<script setup>
import { Head, useForm } from '@inertiajs/vue3';
// import FormFields from './FormFields.vue';
// import Save from '@/Components/buttons/SaveForm.vue';
// import Delete from '@/Components/buttons/DeleteAsset.vue';
// import BackCancel from '@/Components/buttons/BackCancel.vue';

const props = defineProps({
  subject: Object,
})

const form = useForm({
  title: props.subject.title,
  slug: props.subject.slug,
})
</script>


<template>

  <Head title="Subjects" />
  <div>
    <head-row heading="Country / Edit" :identifier="form.title" class="mb-3" />

    <b-row>
      <b-colxx xxs="12">

        <b-card class="mb-4" title="">
          <form @submit.prevent="form.submit('put', route('admin.subjects.update', subject.id), { replace: true })">

            <FormFields v-bind="props" :form="form" />

            <b-colxx xxs="12" class="text-end">
              <BackCancel :cancel="true" :url="route('admin.subjects.index')">Cancel / Back to index</BackCancel>
              <Delete
                :theroute="route('admin.subjects.destroy', props.subject.id)"
                :id="props.subject.id"
                :disabled="form.processing"
                confirm
                copy="Deleting an subject can have profound consequences: all the tiles connected to the subject will permanently lose subject info.<br /><br />This operation is irreversible."
               />
              <Save :form="form" />
            </b-colxx>

          </form>
        </b-card>
      </b-colxx>
    </b-row>
  </div>

</template>
