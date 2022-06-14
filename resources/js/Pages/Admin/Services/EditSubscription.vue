<template>
    <modal :show="show" :closeable="true" @close="close">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h1 class="font-semibold text-lg text-gray-800 leading-tight text-center mb-4">
                Souscription à un service
            </h1>
            <p class="text-center">
                {{ subscription.user.full_name }} souhaiterait s'inscrire au service intitulé "{{ subscription.service.title }}".
            </p>
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <jet-label for="accept" value="Valider l'inscription ?" />
                    <label class="inline-flex items-center mr-6">
                        <input type="checkbox" id="accept" class="form-radio" value="1" v-model="form.accept">
                        <span class="ml-2">Accepter</span>
                    </label>
                </div>

                <div class="mt-4" v-if="form.accept">
                    <jet-label for="accept" value="Sélectionner une date d'expiration" />
                    <Datepicker
                        v-model="form.expires_at"
                        locale="fr"
                        cancelText="Annuler"
                        textInput
                        :min-date="new Date()"
                        :enableTimePicker="false"
                        minutesIncrement="15"
                        format="dd/MM/yyyy"
                        selectText="Confirmer"
                        inputFormat="dd/MM/yyyy"
                    />
                    <jet-input-error :message="form.errors.expires_at" class="mt-2" />
                </div>

                <div class="mt-4 flex justify-end">
                    <jet-button type="submit">
                        Enregistrer
                    </jet-button>
                </div>
            </form>
        </div>
    </modal>
</template>

<script setup>
import Modal from "@/Jetstream/Modal.vue";
import JetButton from '@/Jetstream/Button.vue';
import JetLabel from '@/Jetstream/Label.vue';
import {useForm} from "@inertiajs/inertia-vue3";
import JetInputError from '@/Jetstream/InputError.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue'
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { watch } from 'vue';

const emit = defineEmits(['close'])
const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    subscription: Object,
})

const form = useForm({
    subscription_id: null,
    accept: false,
    expires_at: '',
})

watch(() => props.subscription, (fields) => {
    form.subscription_id = fields.id;
});

const close = () => {
    // form.reset();
    emit('close');

}

const submit = () => {
    form.post(route('services.subscriptions.update'), {
        onSuccess: () => close()
    })
}

</script>
