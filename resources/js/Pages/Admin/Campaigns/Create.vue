<script setup>
import FormFields from './FormFields.vue';
import { Head, useForm } from '@inertiajs/vue3';
// import Save from '@/Components/buttons/SaveForm.vue';
// import Back from '@/Components/buttons/Back.vue';
import AppLayout from '@/Layouts/AppLayout.vue'; 
import SectionBorder from '@/Components/SectionBorder.vue';

import CreateCampaignForm from './Partials/CreateCampaignForm.vue';

const props = defineProps({
  errors: Object,
})

const form = useForm({
  name: null,
  prompt: null,
})
</script>


<template>

  <!-- <Head title="Subjects" />
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
  </div> -->


<!-- 
  <Head title="Campaigns" />
 
  <AppLayout title="Campaigns">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Campaigns
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
         
              <form @submit.prevent="form.submit('post', route('admin.campaigns.store'), { replace: true })">

                <FormFields  :form="form" />
 
                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                  <div class="me-3">
                    <div class="text-sm text-gray-600" style="display: none;"> Saved. </div>
                  </div>
              
                  <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"> 
                    Save </button>
                </div>

                </form>
            </div>
        </div>



        
    </AppLayout> -->
 
  <AppLayout title="Profile">
      <template #header>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              Campaign Create
          </h2>
      </template>

      <div>
          <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
   
                  <CreateCampaignForm />

                  <SectionBorder />
          
           
          </div>
      </div>
  </AppLayout> 
</template>
