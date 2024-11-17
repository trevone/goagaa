<script setup>

const props = defineProps({
 
  form: Object,
})

</script>


<template>
 
 
  <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
    <div class="grid grid-cols-6 gap-6">
      <!-- Profile Photo --><!--v-if--><!-- Name -->
      <div class="col-span-6 sm:col-span-4">
        <label class="block font-medium text-sm text-gray-700" for="name">
          <span>Name</span>
        </label>
        <input v-model="form.name" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" id="name" type="text" required=""  >
        <div class="mt-2" style="display: none;">
          <p class="text-sm text-red-600"></p>
        </div>
      </div> 
      <!-- <div class="col-span-6 sm:col-span-4">
        <label class="block font-medium text-sm text-gray-700" for="email">
          <span>Prompt</span>
        </label>
        <input v-model="form.prompt" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" id="email" type="text" required="" >
        <div class="mt-2" style="display: none;">
          <p class="text-sm text-red-600"></p>
        </div> 
      </div> -->
    </div>
  </div>
   

</template>
