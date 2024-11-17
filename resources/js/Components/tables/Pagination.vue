<script setup>

const emit = defineEmits(['page'])
import { Link } from '@inertiajs/vue3'
const props = defineProps({
  data:  {
    type: Object,
    default: {}
  },
  partials:  {
    type: Array,
    default: []
  },
  xhr:  {
    type: Boolean,
    default: false
  },
  preserveState:  {
    type: Boolean,
    default: false
  },
})

// console.log('xhrr', props.xhr)

function page(url) {
  emit('page', url)
}
</script>

<template>

  <div v-if="data.from">
    <div class="d-flex justify-content-between flex-fill d-sm-none">
      <ul class="pagination">
        <!-- {{-- Previous Page Link --}} -->
        <li v-if="data.current_page == 1" class="page-item disabled" aria-disabled="true">
          <span class="page-link">previous page</span>
        </li>
        <li v-else class="page-item">
          <Link :preserve-state="preserveState" v-if="!xhr" class="page-link" :href="data.links[0].url" rel="prev">previous page</Link>
          <button v-else class="page-link" @click="page(data.links[0].url)" rel="prev">previous page</button>
        </li>

        <!-- {{-- Next Page Link --}} -->
        <li v-if="data.current_page == data.last_page" class="page-item disabled" aria-disabled="true">
          <span class="page-link">next page</span>
        </li>
        <li v-else class="page-item">
          <Link :preserve-state="preserveState" v-if="!xhr" class="page-link" :href="data.links[data.links.length - 1].url" rel="next">next page</Link>
          <button v-else class="page-link" @click="page(data.links[data.links.length - 1].url)" rel="next">next page</button>
        </li>
      </ul>
    </div>

    <div class="row">
      <div class="col-4 text-start">
        <p class="small text-muted">
          Showing
          <span class="fw-semibold">{{ data.from }}</span>
          to
          <span class="fw-semibold">{{ data.to }}</span>
          of
          <span class="fw-semibold">{{ data.total }}</span>
          results
        </p>
      </div>

      <div class="col d-flex justify-content-end">
        <ul class="pagination">
          <!-- {{-- Previous Page Link --}} -->

            <li v-if="data.current_page == 1" class="page-item disabled" aria-disabled="true" aria-label="previous page">
              <span class="page-link" aria-hidden="true">&lsaquo;</span>
            </li>
            <li v-else class="page-item">
              <Link :preserve-state="preserveState" v-if="!xhr" class="page-link" :href="data.links[0].url" rel="prev" aria-label="previous page">&lsaquo;</Link>
              <button v-else class="page-link" @click="page(data.links[0].url)" rel="prev" aria-label="previous page">&lsaquo;</button>
            </li>

          <!-- {{-- Pagination Elements --}} -->
          <template v-for="(element, key) in data.links" :key="key">
            <!-- {{-- "Three Dots" Separator --}} -->
            <li v-if="element.label == '...'" class="page-item disabled" aria-disabled="true"><span class="page-link">{{ element.label }}</span></li>

            <!-- {{-- Array Of Links --}} -->
            <template v-if="!isNaN(parseInt(element.label, 10))">
              <li v-if="element.active == true" class="page-item active" aria-current="page"><span class="page-link">{{ element.label }}</span></li>
              <li v-else class="page-item">
                <Link :preserve-state="preserveState" v-if="!xhr" class="page-link" :href="element.url">{{ element.label }}</Link>
                <button v-else class="page-link" @click="page(element.url)">{{ element.label }}</button>
              </li>

            </template>
          </template>

          <!-- {{-- Next Page Link --}} -->
          <li v-if="data.current_page == data.last_page" class="page-item disabled" aria-disabled="true" aria-label="next page">
            <span class="page-link" aria-hidden="true">&rsaquo;</span>
          </li>
          <li v-else class="page-item">
            <Link :preserve-state="preserveState" v-if="!xhr" class="page-link" :href="data.links[data.links.length - 1].url" rel="next" aria-label="next page">&rsaquo;</Link>
            <button v-else class="page-link" @click="page(data.links[data.links.length - 1].url)" rel="next" aria-label="next page">&rsaquo;</button>
          </li>

        </ul>
      </div>
    </div>
  </div>

</template>
