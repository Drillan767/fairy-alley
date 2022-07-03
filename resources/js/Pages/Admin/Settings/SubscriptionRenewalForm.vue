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

            <div class="col-span-6 sm:col-span-4 my-4">
                <jet-label value="Quels cours afficher dans le formulaire de premier contact ?" />
                <div class="mt-1">
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio" value="current" v-model="form.which_year" :checked="form.which_year === 'current'">
                        <span class="ml-2">Les cours de l'année en cours ({{ currentYear }})</span>
                    </label>
                </div>
                <div>
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio" value="next" v-model="form.which_year" :checked="form.which_year === 'next'">
                        <span class="ml-2">Les cours de l'année prochaine ({{ nextYear }})</span>
                    </label>
                </div>

                <jet-input-error :message="form.errors.gender" class="mt-2" />
            </div>

            <h2 class="font-semibold text-l text-gray-800 leading-tight">Termes d'utilisation</h2>

            <div class="mt-4">
                <wysiwyg v-model="form.tos" :tiny="tiny" />
            </div>

            <div class="mt-4 flex justify-end">
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
import { usePage, useForm } from '@inertiajs/inertia-vue3'
import Swal from "sweetalert2";
import Wysiwyg from '@/Jetstream/Wysiwyg.vue';
import {computed, ref} from "vue";

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
    which_year: {
        type: String,
        default: '',
    },
    tos: {
        type: String,
        default: '',
    },
})

const form = useForm({
    start: props.start,
    end: props.end,
    price_full: props.price_full,
    price_quarterly: props.price_quarterly,
    which_year: props.which_year,
    tos: props.tos,
})

const currentYear = ref(dayjs().subtract(1, 'year').year() + ' - ' + dayjs().year())
const nextYear = ref(dayjs().year() + ' - ' + dayjs().add(1, 'year').year())

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

