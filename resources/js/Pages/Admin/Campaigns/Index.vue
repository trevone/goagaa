<script setup>
// import { onActivated, onDeactivated } from 'vue'
// import { Head, router  } from '@inertiajs/vue3';
// import Table from "@/Components/tables/SimpleTable.vue";
// import Search from "@/Components/utils/Search.vue";

// defineProps({
//   data: Object
// })

// onActivated(() => {
//   router.reload({ replace: true, preserveState: true })
// })

import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';

defineProps({
  data: Object
})
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
    <AppLayout title="Campaigns">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Campaigns
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    
                </div>
            </div>
        </div>
    </AppLayout>
</template>
