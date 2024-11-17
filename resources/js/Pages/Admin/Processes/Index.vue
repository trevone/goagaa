<script setup>
import { onActivated, onDeactivated } from 'vue'
import { Head, router  } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3'
// import Table from "@/Components/tables/SimpleTable.vue";
// import Search from "@/Components/utils/Search.vue";

// defineProps({
//   data: Object
// })

 onActivated(() => {
  router.reload({ replace: true, preserveState: true })
 })

import AppLayout from '@/Layouts/AppLayout.vue';  

defineProps({
  data: Object
})

const destroy = (id) => {
    router.visit( route('admin.processes.destroy', [id]), { method: 'delete' } )
}
</script>

<!-- 
<template>
  Campaigns

  <Head title="Campaigns" />
  <div>
    <head-row heading="Campaigns" />

    <b-row>
      <b-colxx xxs="12">

        <b-row class="pb-4">
          <Search :f="route().params.f"/>

          <b-colxx xxs="8" class="text-end">
            <Link class="btn btn-outline-primary btn-xs ms-2" :href="route('admin.subjects.index')">Clear filter</Link>
            <Link class="btn btn-primary btn-xs ms-2" :href="route('admin.subjects.create')">Create new subject</Link>
          </b-colxx>
        </b-row>

        <b-card class="mb-4" title="">
          <Table class="vuetable ui blue selectable celled stackable attached table"
            :data="data.data"
            :headers="data.headers"
            :links="data.meta"
            :actions="[{type:'edit', route:'admin.subjects.edit', params: [] }]">
          </Table>
        </b-card>
      </b-colxx>
    </b-row>
  </div>

</template> -->


 

<template>

    

    <AppLayout title="Processes">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Processes
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="flex items-center justify-end px-4 py-3  text-end sm:px-6 sm:rounded-bl-md sm:rounded-br-md">
                  <div class="me-3">
                    <div class="text-sm text-gray-600" style="display: none;"> Saved. </div>
                  </div>
                  <Link :href="route('admin.processes.create')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"> 
                    Create </Link>
                </div>
            

              <div class=" bg-white  shadow sm:rounded-tl-md sm:rounded-tr-md"> 
              

             
       

       <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
           <div v-for="d in data.data">
               <div class="flex items-center">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-6 h-6 stroke-gray-400">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                   </svg>
                   <h2 class="ms-3 text-xl font-semibold text-gray-900">
                       <Link :href="route('admin.processes.edit', [d.id])">{{ d.class }}</Link>
                   </h2>
               </div>

               <p class="mt-4 text-gray-500 text-sm leading-relaxed" >
                   Manage your Campaigns, frequency, prompt text for the ai, various other settings
               </p>
               
               <p class="mt-4 text-sm"> 
                    <Link 
                      class="inline-flex items-center font-semibold text-indigo-700"
                      :href="route('admin.processes.edit', [d.id])"
                    >  
                       Manage 
                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 w-5 h-5 fill-indigo-500">
                           <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                       </svg>
                    </Link>
                    <Link 
                      @click.prevent="destroy(d.id)"
                      class="inline-flex items-center font-semibold text-indigo-700"
                      :href="route('admin.processes.destroy', [d.id])"
                    >  
                       Delete 
                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 w-5 h-5 fill-indigo-500">
                           <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                       </svg>
                    </Link>
               </p>
           </div>
  
 
       </div>
 

              <!-- <Table class="vuetable ui blue selectable celled stackable attached table"
                :data="data.data"
                :headers="data.headers"
                :links="data.meta"
                :actions="[{type:'edit', route:'admin.campaigns.edit', params: [] }]">
              </Table> -->
              </div>
            </div>
        </div>
    </AppLayout>
</template>
