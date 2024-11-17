<script setup> 
import { Head, useForm } from '@inertiajs/vue3';
// import Save from '@/Components/buttons/SaveForm.vue';
// import Back from '@/Components/buttons/Back.vue';
import AppLayout from '@/Layouts/AppLayout.vue'; 
import SectionBorder from '@/Components/SectionBorder.vue';

import CreateProcessForm from './Partials/CreateProcessForm.vue';

const props = defineProps({
  errors: Object,
})

const form = useForm({
  name: null,
  prompt: null,
})
</script>


<template>
 
 
  <AppLayout title="Profile">
      <template #header>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              Process Create
          </h2>
      </template>

      <div>
          <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
   
                  <CreateProcessForm />

                  <SectionBorder />
          
           
          </div>
      </div>
  </AppLayout> 
</template>
