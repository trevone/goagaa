<script setup>
import { ref,onMounted  } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import VueSelect from "vue3-select-component";
const selected = ref("");



const props = defineProps({
    campaign: Object,
    options: Array,
    fields: Array,
    connector: Object,
    chain: Array,
    flash: Object, 
});
 
const options = ref(props.options);
const fields = ref([])
const processing = ref(false)

const form = useForm({
    _method: 'POST',
    //name: props.campaign.data.name,  
});

const connectors_form = useForm({
   
    id: props.connector.id,
    process_id: props.connector.process_id,
    jsond: props.connector.data,
    parent_id: null,
    //name: props.campaign.data.name,  
});

const updateConnectorInformation = () => {
    if(connectors_form.id){ 
        connectors_form.put(route('admin.connectors.update', connectors_form.id ), {
            errorBag: 'attachConnectorInformation',
            preserveScroll: true,
            //onSuccess: () => clearPhotoFileInput(),
        })
    } else {
        if(props.chain.length > 0){
            connectors_form.defaults('parent_id', props.chain[props.chain.length - 1][0].id )
            connectors_form.post(route('admin.campaigns.attachs', null ), {
                errorBag: 'attachConnectorInformation',
                preserveScroll: true,
                //onSuccess: () => clearPhotoFileInput(),
            });
        } else {
            connectors_form.defaults('parent_id', null )
            connectors_form.post(route('admin.campaigns.attachs', props.campaign.data.id ), {
                errorBag: 'attachConnectorInformation',
                preserveScroll: true,
                //onSuccess: () => clearPhotoFileInput(),
            });
        }
        
    } 
};

const dettachConnectorInformation = (id) => { 
    router.visit(route('admin.connectors.destroy', id), {
        method: 'delete',
    })
}
 
const testRun = () => {  
    router.visit(route('admin.connectors.aimltest', connectors_form.id), {
        method: 'post', 
        preserveScroll: true,
        preserveState: true,
    })
}

const selectedOption = (option) => { 
    fields.value = [];
    option.fields.forEach(element => { 
        fields.value.push(element) 
    });
}

onMounted(() => {
  const item = props.options.find(option => option.value === props.connector.process_id);
  selectedOption(item)
})
</script>

<template>
    <FormSection @submitted="updateConnectorInformation">
        <template #title>
            Connector Information
        </template>

        <template #description>
            Update your Connector Information and Connectors.
            <p class="mt-4 text-sm">  
                <a @click.prevent="testRun" href="#" class="inline-flex items-center font-semibold text-indigo-700">
                    Test Run
                </a> 
                <div class="max-h-64 overflow-y-scroll">
                    <p v-if="processing">processing</p>
                    <p>{{ connector.output }}</p>
                </div>
            </p>
        </template>
        
        <template #form>
  
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4"> 
                <InputLabel for="name" value="Name" />
                <VueSelect 
                    v-model="connectors_form.process_id"
                    :options="options"
                    placeholder="Select an option"
                    @option-selected="(option) => selectedOption(option)"
                /> 
                <InputError :message="form.errors.connector_id" class="mt-2" />
            </div>
 
            <div v-for="value in fields" class="col-span-6 sm:col-span-4">
              
               <InputLabel :for="value" :value="value" />
               <TextInput
                   :id="value"
                   v-model="connectors_form.jsond[value]"
                   type="text"
                   class="mt-1 block w-full"
                   required
                   :autocomplete="value"
               />
               <InputError :message="form.errors.jsond" class="mt-2" />
            
           </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Saved.
            </ActionMessage>

            <PrimaryButton v-if="connectors_form.id" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
            <PrimaryButton v-else :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create
            </PrimaryButton>
        </template>
    </FormSection>

    
</template>
