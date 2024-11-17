<script setup>
import { ref, computed } from 'vue';
import { DateTime } from 'luxon';
//import VueEasyLightbox from 'vue-easy-lightbox';

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
  id: {
    type: Number,
    default: null
  },
  value: {
    type: [Array, String, Number, Boolean],
    default: ''
  },
  display: {
    type: Boolean,
    default: true
  },
  align: {
    type: String,
    default: 'left'
  },
})

const display_value = computed(() => {

  switch (props.type) {
    case 'array':
      return props.value.join(', ')
    case 'json_array_string':
      return props.value ? props.value.join(', ') : ''
    case 'boolean':
      return props.value ? 'true' : 'false'
    case 'date':
      return !props.value ? 'never' : DateTime.fromISO(props.value).toFormat('dd-MM-yyyy')
    case 'datetime':
      return !props.value ? 'never' : DateTime.fromISO(props.value).toFormat('dd-MM-yyyy HH:mm:ss')
    case 'string':
    case 'numeric':
    default:
      return props.value;
  }
})

// lightbox
const visibleRef = ref(false)
const indexRef = ref(0) // default 0
const imgsRef = ref([])

const onShow = () => {
  visibleRef.value = true
}
const showSingle = (preview, title) => {
console.log(preview)
  imgsRef.value = [{src: preview, title: title}]
  onShow()
}
const onHide = () => (visibleRef.value = false)
</script>


<template>
  <td v-if="display" class="vuetable-th-title sortable align-middle" :class="{'p-1': props.type == 'image'}">
    <template v-if="props.type == 'image' && props.value">
      <img :src="$page.props.admin_media_prefix+props.value"
        class="thumbnail rounded-1"
        style="cursor: pointer"
        @click="showSingle($page.props.admin_media_prefix+props.value, '')"
      />

      <!-- <vue-easy-lightbox
        :visible="visibleRef"
        :imgs="imgsRef"
        :index="indexRef"
        @hide="onHide"
      ></vue-easy-lightbox> -->
    </template>
    <template v-else-if="props.type == 'image' && !props.value">
      embedded
    </template>
    <span v-else-if="props.type == 'html'" v-html="display_value">
    </span>
    <template v-else>
      {{ display_value }}
    </template>
  </td>
</template>
