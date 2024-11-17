<script setup>
import { ref, reactive } from 'vue'
import { Head, useForm, router, Link} from '@inertiajs/vue3';
import FormFields from './FormFields.vue';
import Save from '@/Components/buttons/SaveForm.vue';
import Delete from '@/Components/buttons/DeleteAsset.vue';
import BackCancel from '@/Components/buttons/BackCancel.vue';
import AppLayout from '@/Layouts/AppLayout.vue'; 
import SectionBorder from '@/Components/SectionBorder.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import UpdateCampaignForm from './Partials/UpdateCampaignForm.vue';
import UpdateConnectorForm from './Partials/UpdateConnectorForm.vue';
import CreateConnectorForm from './Partials/CreateConnectorForm.vue';
import 'https://js.pusher.com/8.2.0/pusher.min.js'
const props = defineProps({
  campaign: Object,
  options: Array,
  fields: Array,
  chain: Array,
  parent_id: Number,
  flash: Object
})
 
const form = useForm({
  name: props.campaign.data.name,
  prompt: props.campaign.data.prompt,
})

const create = ref(false)
const aiml_response = ref('')
const aiml_output = ref([])

const reactive_chain = reactive(props.chain[0])
for(let i in props.chain[0]){ 
  reactive_chain[i].log = ""
}


const dettachConnectorInformation = (id) => { 
    router.visit(route('admin.connectors.destroy', id), {
        method: 'delete',
    })
}
 

//Pusher.logToConsole = true;

    let pusher = null
    let channel = null
    if( !pusher ){
      pusher = new Pusher('1cec945b9c838d7e059f', {
        cluster: 'eu'
      });
    }
    if(pusher){ 
      if(!channel){
        channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {  
          for( let i in reactive_chain){
            if(reactive_chain[i].id == data.id) {
              reactive_chain[i].output = data.output
            }
          } 
        });
      } 
    }
</script>
 

<template>
  <AppLayout title="Profile">
      <template #header>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              Campaign Edit
          </h2>
            
      </template>

      <div>
          <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
   
                  <UpdateCampaignForm :campaign="campaign" />
              
                  <div v-for="(link,i) in reactive_chain" :key="link.id">
                    <SectionBorder />
            
                    <UpdateConnectorForm :campaign="campaign" :options="options" :connector="link" :chain="chain" :flash="flash"   />
                  </div>
                  <div v-if="create">
                    <SectionBorder />
                    <CreateConnectorForm :campaign="campaign" :options="options" :chain="chain" :parent_id="parent_id" />
                  </div><p class="mt-4 text-sm">
                  
              
                <a v-if="!create" @click="create=true" href="#" class="inline-flex items-center font-semibold text-indigo-700">
                    Add Process
                </a>
                |
                <Link v-if="!create" @click.prevent="dettachConnectorInformation(parent_id)" :href="route('admin.connectors.destroy', parent_id)" class="inline-flex items-center font-semibold text-indigo-700">
                    Delete Process
                </Link>
            </p>
          </div>
      </div>
  </AppLayout>
</template>
