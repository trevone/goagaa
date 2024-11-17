<script setup>
import { Head, useForm } from '@inertiajs/vue3'; 
import Save from '@/Components/buttons/SaveForm.vue';
import Delete from '@/Components/buttons/DeleteAsset.vue';
import BackCancel from '@/Components/buttons/BackCancel.vue';
import AppLayout from '@/Layouts/AppLayout.vue'; 
import SectionBorder from '@/Components/SectionBorder.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import UpdateProcessForm from './Partials/UpdateProcessForm.vue';
const props = defineProps({
  process: Object, 
})
  
</script>

<!-- 
<template>

  <Head title="Campaigns" />
 
  <AppLayout title="Campaigns">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Campaigns
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
         
              <form @submit.prevent="form.submit('put', route('admin.campaigns.update', campaign.data.id), { replace: true })">

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


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
         
              <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"> 
                    Add Connector </button>
               
            </div>
        </div>
        
    </AppLayout>
</template> -->

<template>
  <AppLayout title="Process">
      <template #header>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              Process Edit
          </h2>
      </template>

      <div>
          <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
   
                  <UpdateProcessForm :process="process" />

                  
 
          
           
          </div>
      </div>
  </AppLayout>
</template>
