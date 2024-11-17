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
    campaign: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.campaign.data.name,  
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
