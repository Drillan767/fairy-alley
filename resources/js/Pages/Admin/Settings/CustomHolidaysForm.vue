<template>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
        <form @submit.prevent="submit">
            <h2 class="font-semibold text-l text-gray-800 leading-tight">Vacances personnalisées</h2>
<!--            <div class="grid grid-cols-6 gap-4">-->
<!--                <div class="col-span-6 sm:col-span-3">-->
            <div
                v-for="(date, index) in form.dates"
                :key="index"
                class="grid grid-cols-6 gap-4 mt-5"

            >
                <div class="col-span-6 sm:col-span-2">
                    <jet-label value="Indiquer une raison" class="required" />
                    <jet-input v-model="date.reason" type="text" required />
                    <jet-input-error :message="form.errors[`dates.${index}.reason`]" class="mt-2"/>
                </div>

                <div class="col-span-6 sm:col-span-2">
                    <jet-label value="Indiquer une date" class="required" />
                    <Datepicker
                        v-model="date.date"
                        locale="fr"
                        cancelText="Annuler"
                        textInput
                        :min-date="new Date()"
                        :enableTimePicker="false"
                        format="dd/MM/yyyy"
                        selectText="Confirmer"
                        inputFormat="dd/MM/yyyy"
                        class="mt-2"
                    />
                    <jet-input-error :message="form.errors[`dates.${index}.date`]" class="mt-2"/>
                </div>
                <div class="col-span-6 sm:col-span-2 flex justify-center items-end">
                    <div>
                        <jet-secondary-button @click="remove(index)">Retirer</jet-secondary-button>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <jet-button type="button" @click="add">Ajouter</jet-button>
            </div>

            <div class="mt-4">
                <jet-button>Enregistrer</jet-button>
            </div>
        </form>
    </div>
</template>

<script setup>
import JetLabel from '@/Jetstream/Label.vue';
import JetInput from '@/Jetstream/Input.vue'
import JetButton from '@/Jetstream/Button.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import Swal from "sweetalert2";
import {useForm} from "@inertiajs/inertia-vue3";
import {computed} from "vue";

const props = defineProps({
    holidays: Object,
})

const formatHolidays = () => {
    let result = [];

    for (const holiday in props.holidays) {
        result.push({
            date: holiday,
            reason: props.holidays[holiday]
        })
    }

    return result
}

const form = useForm({
    dates: formatHolidays()
})

const add = () => {
    form.dates.push({date: '', reason: ''})
}

const remove = (index) => {
    form.dates.splice(index, 1)
}

const submit = () => {
    form.post(route('settings.holidays'), {
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



</script>
