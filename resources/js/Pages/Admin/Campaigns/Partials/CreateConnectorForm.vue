<script setup>
import { ref } from 'vue';
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
    chain: Array,
    parent_id: Number
});
 
const options = ref(props.options);
const fields = ref([])
const form = useForm({
    _method: 'POST',
    //name: props.campaign.data.name,  
});

const connectors_form = useForm({
    _method: 'POST',
    process_id: null,
    jsond: {},
    parent_id: props.parent_id,
    //name: props.campaign.data.name,  
});
if(props.chain.length){
    connectors_form.defaults('parent_id', props.parent_id )
    connectors_form.reset()
}
const attachConnectorInformation = () => {
    connectors_form.post(route('admin.campaigns.attachs', props.campaign.data.id ), {
        errorBag: 'attachConnectorInformation',
        preserveScroll: true,
        onSuccess: () => router.visit(route('admin.campaigns.update', props.campaign.data.id), {
            method: 'get',
        }),
    });
};

const dettachConnectorInformation = (id) => { 
    router.visit(route('admin.connectors.destroy', id), {
        method: 'delete',
    })
}

const selectedOption = (option) => { 
    fields.value = [];
    option.fields.forEach(element => { 
        fields.value.push(element) 
    });
}
 
</script>

<template>
    <FormSection @submitted="attachConnectorInformation">
        <template #title>
            Connector Information
        </template>

        <template #description>
            Update your Connector Information and Connectors.
            <p class="mt-4 text-sm"> 
                <a @click.prevent="dettachConnectorInformation(connectors_form.id)" href="#" class="inline-flex items-center font-semibold text-indigo-700">
                    Delete
                </a>
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

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>

    
</template>
