<template>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
        <form @submit.prevent="submit">
            <h2 class="font-semibold text-l text-gray-800 leading-tight">Réinscriptions</h2>
            <div class="grid grid-cols-6 gap-4">
                <div class="col-span-6 sm:col-span-3">
                    <jet-label value="Sélectionnez la date de début de la réinscription" />
                    <Datepicker
                        v-model="form.start"
                        locale="fr"
                        cancelText="Annuler"
                        textInput
                        :min-date="new Date()"
                        :enableTimePicker="false"
                        format="dd/MM/yyyy"
                        selectText="Confirmer"
                        inputFormat="dd/MM/yyyy"
                    />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <jet-label value="Sélectionnez la date de fin de la réinscription" />
                    <Datepicker
                        v-model="form.end"
                        locale="fr"
                        cancelText="Annuler"
                        textInput
                        :min-date="new Date()"
                        :enableTimePicker="false"
                        format="dd/MM/yyyy"
                        selectText="Confirmer"
                        inputFormat="dd/MM/yyyy"
                    />
                </div>
            </div>

            <div class="grid grid-cols-6 gap-4 mt-4">
                <div class="col-span-6 sm:col-span-3">
                    <jet-label value="Prix à l'année" />
                    <jet-input v-model="form.price_full" type="text" />
                    <jet-input-error :message="form.errors.price_full" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <jet-label value="Prix au trimestre" />
                    <jet-input v-model="form.price_quarterly" type="text" />
                    <jet-input-error :message="form.errors.price_quarterly" />
                </div>
            </div>

            <p class="text-sm mt-2 mb-4">
                Les visiteurs pourront lire un message leur indiquant la date de réinscription deux semaines avant
                le début de celle-ci. Une fois la date limite passée, tous les utilisateur n'étant pas en phase de
                réinscription auront le statut "Archivé", ce qui leur empêcheront de se connecter.
            </p>

            <h2 class="font-semibold text-l text-gray-800 leading-tight">Termes d'utilisation</h2>

            <div class="grid grid-cols-6 gap-4 mt-4">
                <div class="col-span-6 sm:col-span-3">
                    <jet-label value="Détails" />
                    <wysiwyg v-model="form.details" :tiny="tiny" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <jet-label value="Procédure" />
                    <wysiwyg v-model="form.process" :tiny="tiny" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <jet-label value="Organisation" />
                    <wysiwyg v-model="form.organization" :tiny="tiny" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <jet-label value="Conditions" />
                    <wysiwyg v-model="form.conditions" :tiny="tiny" />
                </div>
            </div>

            <div class="mt-4">
                <jet-button>Enregistrer</jet-button>
            </div>
        </form>
    </div>
</template>


<script setup>

import JetLabel from '@/Jetstream/Label.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import dayjs from "dayjs";
import { usePage } from '@inertiajs/inertia-vue3'
import {useForm} from "@inertiajs/inertia-vue3";
import Swal from "sweetalert2";
import Wysiwyg from '@/Jetstream/Wysiwyg.vue';
import {computed} from "vue";

const tiny = computed(() => {
    return usePage().props.value.tiny
})

const props = defineProps({
    start: {
        type: String,
        default: '',
    },
    end: {
        type: String,
        default: '',
    },
    price_full: {
        type: String,
        default: '',
    },
    price_quarterly: {
        type: String,
        default: '',
    },
    details: {
        type: String,
        default: '',
    },
    process: {
        type: String,
        default: '',
    },
    organization: {
        type: String,
        default: '',
    },
    conditions: {
        type: String,
        default: '',
    },
})

const form = useForm({
    start: props.start,
    end: props.end,
    price_full: props.price_full,
    price_quarterly: props.price_quarterly,
    details: props.details,
    process: props.process,
    organization: props.organization,
    conditions: props.conditions,
})

const submit = () => {

    Swal.fire({
        icon: 'warning',
        title: 'Confirmer ?',
        showCancelButton: true,
        cancelButtonText: 'Annuler',
        confirmButtonText: 'Confirmer',
        text: `
                La période de réinscription sera définie comme commençant le
                ${dayjs(form.start).format('DD/MM/YYYY')} et se finissant le ${dayjs(form.end).format('DD/MM/YYYY')}.
                `
    })
        .then((response) => {
            if (response.isConfirmed) {
                form.post(route('settings.renewal'), {
                    onSuccess: () => Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Paramètres sauvegardés',
                        position: 'top-end',
                        timerProgressBar: true,
                        showConfirmButton: false,
                        timer: 2000,
                    })
                })
            }
        })
}

</script>

