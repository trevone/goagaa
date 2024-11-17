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

import SectionBorder from '@/Components/SectionBorder.vue';
const props = defineProps({
    process: Object,
});

const form = useForm({
    _method: 'PUT',
    class: props.process.class,  
    jsond: props.process.data,
});

const updateProcessInformation = () => {
console.log(form)
    form.post(route('admin.processes.update', props.process.id), {
        errorBag: 'updateProcessInformation',
        preserveScroll: true,
        //onSuccess: () => clearPhotoFileInput(),
    });
};
 
</script>

<template>
    <FormSection @submitted="updateProcessInformation">
        <template #title>
            Process Information
        </template>

        <template #description>
            Update your Process Information and Connectors.
 
        </template>
        
        <template #form>
  
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="class" value="class" />
                <TextInput
                    id="class"
                    v-model="form.class"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="class"
                />
                <InputError :message="form.errors.class" class="mt-2" />
            </div>
            <div v-for="(value, key) in form.jsond" class="col-span-6 sm:col-span-4">
               
                <InputLabel :for="key" :value="key" />
                <TextInput
                    :id="key"
                    v-model="form.jsond[key]"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    :autocomplete="key"
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
    

        <SectionBorder />

    </FormSection>
</template>
