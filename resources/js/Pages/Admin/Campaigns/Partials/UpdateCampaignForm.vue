<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    campaign: Object,
    post_processes: Array
});

let pp = [];
for(let i in props.post_processes){
    let c = false;
    for(let j in props.campaign.data.processes){
        console.log(props.campaign.data.processes[j])
        if(props.campaign.data.processes[j].id==props.post_processes[i].id){
            pp.push({
                name: props.campaign.data.processes[j].class,
                value: `${props.campaign.data.processes[j].id}`,
                checked: true
            })
            c = true;
        }
        
    }
    if(!c){
        pp.push({
            name: props.post_processes[i].class,
            value: `${props.post_processes[i].id}`,
            checked: false
        })
    }
}
console.log(pp)
const form = useForm({
    _method: 'PUT',
    name: props.campaign.data.name,  
    processes: pp
});

const updateCampaignInformation = () => { 
    form.post(route('admin.campaigns.update', props.campaign.data.id), {
        errorBag: 'updateCampaignInformation',
        preserveScroll: true,
        //onSuccess: () => clearPhotoFileInput(),
    });
};
 
</script>

<template>
    <FormSection @submitted="updateCampaignInformation">
        <template #title>
            Campaign Information
        </template>

        <template #description>
            Update your Campaign Information and Connectors.
        </template>
        
        <template #form>
  
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="name"
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>
            <!-- Name -->
            <div v-for="(process,i) in form.processes" class="col-span-6 sm:col-span-4">
             
                <InputLabel for="process.class" :value="form.processes[i].name" />   
         
                <Checkbox v-model:checked="form.processes[i].checked" :value="form.processes[i].value" />
              
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
