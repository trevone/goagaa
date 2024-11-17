<script setup>
import { computed } from 'vue' 
import { Link } from '@inertiajs/vue3'

const emit = defineEmits(['page'])

const props = defineProps({
  label: {
    type: String,
    default: 'col'
  },
  property: {
    type: String,
    default: 'id'
  },
  type: {
    type: String,
    default: 'string'
  },
  editable: {
    type: Boolean,
    default: false
  },
  sortable: {
    type: Boolean,
    default: false
  },
  display: {
    type: Boolean,
    default: true
  },
  xhr:  {
    type: Boolean,
    default: false
  },
  current_url: {
    type: String,
    default: ''
  }
})

const getRoute = computed(() => {
  if(!props.xhr) {
    var par = route().params;
    par['_query'] = {
      by: props.sortable === true ? props.property : null,
      order: par.order == 'desc' ? 'asc' : 'desc'
    };
    //par['_query'] = Object.assign(par['_query'], filters)
    return route(route().current(), par)
  }

  var url = new URL(props.current_url);

  var par = url.searchParams
  par.set('by', (props.sortable === true ? props.property : null))
  par.set('order', (par.get('order') == 'desc' ? 'asc' : 'desc'))

  return url.href.replace(url.search, '?'+par.toString())
})
const order = computed(() => {
  return (route().params.order ?? 'asc');
})
const by = computed(() => {
  return (route().params.by ?? 'id');
})
const sortClass = computed(() => {
  if(!props.xhr) {
    if(by.value == props.property) {
      return 'sorted-' + (order.value ?? 'asc');
    }
  }

  if(props.current_url) {
    var url = new URL(props.current_url);
    var par = url.searchParams
    if(par.get('by') == props.property) {
      return 'sorted-' + (par.get('order') ?? 'asc');
    }
  }

  return ''

})

function page(url) {
  emit('page', url)
}
</script>

<template>
  <th v-if="display" :class="sortClass">
    <template v-if="sortable">
      <Link v-if="!xhr" :href="getRoute">{{ label }}</Link>
      <a v-else href="" @click.prevent="page(getRoute)" rel="prev">{{ label }}</a>
    </template>
    <span v-else>{{ label }}</span>
  </th>
</template>
