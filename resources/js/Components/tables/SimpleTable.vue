<script setup>
import Header from "./SimpleTableHeader.vue";
import Row from "./SimpleTableRow.vue";
import Pagination from "./Pagination.vue";
//import DeleteAssetXhr from "@/Components/buttons/DeleteAssetXhr.vue";
import { Link } from '@inertiajs/vue3'

const emit = defineEmits(['action', 'success'])

const props = defineProps({
  headers: {
    type: Array,
    default: [{ title: 'trt' }, { title: 'mrt' }]
  },
  data:  {
    type: Array,
    default: []
  },
  links:  {
    type: Object,
    default: []
  },
  actions:  {
    type: Object,
    default: []
  },
  partials:  {
    type: Array,
    default: []
  },
})
const concatParameters = function(...$arr1) {

  console.log('mrt');
 return [].concat(...$arr1);
}

function emitAction(id) {
  emit('action', id)
}
function success() {
  emit('success')
}
</script>


<template>
<div>
  <table>
    <thead>
      <tr>
        <Header v-for="(header, index) in headers" :key="index"
          v-bind="header"
          ></Header>
      </tr>
    </thead>
    <tbody v-if="Object.keys(data).length">
        <tr v-for="(row, index) in data" :key="index">
            <Row v-for="(header, index) in headers" :key="index"
              v-bind="header"
              :value="row[header.property]"
              :id="row.id"></Row>
            <td>
              <template v-for="(action, index) in actions" :key="index">
                 
                <button
                  v-if="action.type == 'event'"
                
                  @click="emitAction(row.id)"
                >{{ action.label }}</button>
                <Link
                  v-else
        
                  :href="route(action.route, [].concat(action.params, [row.id]))"
                >{{ action.type }}</Link>
              </template>
            </td>
        </tr>
    </tbody>
    <tbody v-else>
        <tr >
            <td collspan="100">No data.</td>
        </tr>
    </tbody>
  </table>

  <div>
    <Pagination :data="links" :partials="partials"></Pagination>
  </div>
</div>
</template>
