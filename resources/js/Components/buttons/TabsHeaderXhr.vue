<script setup>
import { ref } from 'vue';

const emit = defineEmits(['update:modelValue'])
const props = defineProps({
  modelValue: Object,
})
const add_tab = ref(props.modelValue.options.add ?? false)

// const model = ref(props.modelValue)

function updateModel(newValue) {

  var model = props.modelValue
  model.active = newValue
  emit('update:modelValue', model)
}
function addTab() {

  var model = props.modelValue
  var refs = pluck(model.tabs, 'ref')
  if(refs.includes('new_tab')) {
    return
  }

  model.active = 'new_tab'
  model.tabs.push({ ref: 'new_tab', label: 'New tab' })
  emit('update:modelValue', model)
}

function pluck(array, key) {
  return array.map(o => o[key]);
}
</script>

<template>
  <button v-for="(tab, index) in modelValue.tabs"
    :key="index"
    class="btn btn-outline-dark btn-xs mb-0 me-1 rounded-bottom-0 rounded-top-3 border-bottom-0"
    :class="{ 'active': modelValue.active == tab.ref }"
    @click="updateModel(tab.ref)"
  >{{ tab.label }}</button>
  <button v-if="add_tab"
    class="btn btn-outline-dark btn-xs mb-0 me-1 rounded-bottom-0 rounded-top-3 border-bottom-0"
    @click="addTab()"
  >add new</button>
</template>
