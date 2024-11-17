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

const props = defineProps({
     
});

const form = useForm({
    _method: 'POST',
    class: '',  
});

const createProcessInformation = () => {

    form.post(route('admin.processes.store'), {
        errorBag: 'createProcessInformation',
        preserveScroll: true,
        //onSuccess: () => clearPhotoFileInput(),
    });
};
 
</script>

<template>
    <FormSection @submitted="createProcessInformation">
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
                <InputError :message="form.errors.name" class="mt-2" />
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
