<template>
    <form @submit.prevent="submit">
        <div>
            <jet-label for="title" value="Titre" />
            <jet-input id="title" type="text" class="mt-1 block w-full" v-model="form.title" required autofocus />
            <jet-input-error :message="form.errors.title" class="mt-2" />
        </div>

        <div class="mt-4">
            <jet-label for="description" value="Description" />
            <jet-input id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
            <jet-input-error :message="form.errors.description" class="mt-2" />
        </div>

        <div class="mt-4">
            <jet-label for="ref" value="Réference" />
            <jet-input id="ref" type="text" class="mt-1 block w-full" v-model="form.ref" />
            <jet-input-error :message="form.errors.ref" class="mt-2" />
        </div>

        <div class="mt-4">
            <jet-label for="gender" value="Le cours est pour :" />
            <label class="inline-flex items-center mr-6">
                <input type="radio" class="form-radio" name="accountType" value="F" v-model="form.gender" :checked="form.gender === 'F'">
                <span class="ml-2">Les femmes</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" class="form-radio" name="accountType" value="H" v-model="form.gender" :checked="form.gender === 'H'">
                <span class="ml-2">Les hommes</span>
            </label>
            <jet-input-error :message="form.errors.gender" class="mt-2" />
        </div>

        <div class="mt-4 flex gap-x-4">
            <div class="mt-2">
                <jet-label for="nbOccurrences" value="Nombre d'occurrences" />
                <jet-input type="number" v-model="setupOccurrences.nbOccurrences" id="nbOccurrences"/>
            </div>

            <div class="mt-2" v-if="!['', 0].includes(setupOccurrences.nbOccurrences)">
                <jet-label for="nbOccurrences" value="Date de la première occurrence" />
                <Datepicker
                    v-model="setupOccurrences.occurrenceStartDate"
                    locale="fr"
                    cancelText="Annuler"
                    textInput
                    minutesIncrement="15"
                    :disabled-dates="disabledDates"
                    format="dd/MM/yyyy HH:mm"
                    :textInputOptions="textInputOptions"
                    selectText="Confirmer"
                    inputFormat="dd/MM/yyyy HH:mm"
                />
            </div>

            <div class="mt-8" v-if="setupOccurrences.occurrenceStartDate !== ''">
                <jet-button @click.prevent="generateOccurrences">Générer</jet-button>
            </div>
        </div>

        <div v-if="occurrences.length" class="mt-4">
            <jet-label>Dates des cours</jet-label>
            <div class="flex flex-wrap">
                <div
                    v-for="(date, i) in occurrences"
                    :key="i"
                    class="w-1/3 px-4 py-2 gap-x-2 flex"
                    :title="`Le cours est ${lexicon[date.status]}.`"
                    :class="{
                        'bg-red-100': date.status === 'cancelled',
                        'bg-blue-100': date.status === 'recovery',
                        'bg-green-100': date.status === 'ok'

                    }"
                >
                    <Datepicker
                        v-model="occurrences[i].date"
                        locale="fr"
                        cancelText="Annuler"
                        textInput
                        minutesIncrement="15"
                        :disabled-dates="disabledDates"
                        format="dd/MM/yyyy HH:mm"
                        :textInputOptions="textInputOptions"
                        selectText="Confirmer"
                        inputFormat="dd/MM/yyyy HH:mm"
                    />
                    <Button @click.prevent="editSpecificDate(date, i)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </Button>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <div class="flex justify-end">
                <jet-button type="submit" class="ml-2" :disabled="form.processing">
                    Enregistrer
                </jet-button>
            </div>
        </div>
    </form>
</template>

<script>
import JetInput from '@/Jetstream/Input.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetLabel from '@/Jetstream/Label.vue';
import Wysiwyg from '@/Jetstream/Wysiwyg.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import Datepicker from 'vue3-date-time-picker';
import dayjs from "dayjs";
import 'vue3-date-time-picker/dist/main.css';
import {useForm} from "@inertiajs/inertia-vue3";
import {computed, onMounted, ref, toRaw} from "vue";
import Button from "@/Jetstream/Button.vue";
import Swal from 'sweetalert2';

export default {
    props: {
        lesson: {
            type: Object,
            required: false
        },
        editing: Boolean,
        tiny: String,
        holidays: Array,
    },

    components: {
        Button,
        JetInput,
        JetLabel,
        JetButton,
        JetSecondaryButton,
        JetInputError,
        Wysiwyg,
        Datepicker,
    },

    setup (props) {
        const occurrences = ref([]);
        const lexicon = ref({
            'ok': 'assuré',
            'cancelled': 'annulé',
            'recovery': 'récupéré',
        })

        onMounted(() => {
            if (props.editing) {
                setupOccurrences.value.nbOccurrences = props.lesson.schedule.length
                setupOccurrences.value.occurrenceStartDate = props.lesson.schedule[0].date

                occurrences.value = props.lesson.schedule;
            }
        })

        const editSpecificDate = (detail, index) => {
            Swal.fire({
                title: "Éditer les détails de l'occurrence",
                text: `Que voulez-vous faire pour l'occurrence du ${dayjs(detail.date).format('DD/MM/YYYY à HH:mm')} ?`,
                input: 'select',
                inputValue: detail.status,
                inputOptions: {
                    ok: 'Définir comme ayant lieu',
                    cancelled: 'Définir comme annulée',
                    recovery: 'Définir comme jour de rattrapage',
                },
                preConfirm: (select) => {
                    console.log(occurrences.value[index])
                    console.log(detail.date)

                    occurrences.value[index] = {
                        date: detail.date,
                        status: select,
                    }

                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Statut du cours mis à jour.',
                        position: 'top-end',
                        timerProgressBar: true,
                        showConfirmButton: false,
                        timer: 2000,
                    })
                }
            })
        }

        const disabledDates = computed(() => {
            const dates = []
            props.holidays.map((day) => dates.push(dayjs(day).toDate()))
            return dates;
        });

        const setupOccurrences = ref({
            nbOccurrences: 0,
            occurrenceStartDate: '',
        })

        const data = props.editing ? {
            ...props.lesson,
            _method: 'PUT',
        } : {
            title: '',
            description: '',
            gender: '',
            ref: '',
            occurrences: []
        }
        const form = useForm(data);

        const date = dayjs().startOf('day').toDate()
        const dateList = ref([{date: date}]);
        const textInputOptions = ref({
            format: 'dd.MM.yyyy'
        })

        const generateOccurrences = () => {
            const result = [];
            let nbOccurrences = setupOccurrences.value.nbOccurrences;
            let date = setupOccurrences.value.occurrenceStartDate;

            result.push({
                date: dayjs(date).format('YYYY-MM-DD HH:mm'),
                status: 'ok',
            })

            for (let i = 1; i < nbOccurrences; i++) {
                date = dayjs(date).add(1, 'w');

                if (i < setupOccurrences.value.nbOccurrences) {
                    // All of the following happens if there was no need to add days because of a holiday.
                    if (toRaw(props.holidays).includes(dayjs(date).format('YYYY-MM-DD'))) {
                        nbOccurrences++
                        result.push({
                            date: dayjs(date).format('YYYY-MM-DD HH:mm'),
                            status: 'cancelled',
                        })
                    } else {
                        result.push({
                            date: dayjs(date).format('YYYY-MM-DD HH:mm'),
                            status: 'ok',
                        })
                    }

                } else {
                    // All the following happens if we're beyond the initial maximum occurrences planned.
                    // If the day is already exceeding the initial limit, we'll just ignore it and go on.
                    if (!props.holidays.includes(dayjs(date).format('YYYY-MM-DD'))) {
                        result.push({
                            date: dayjs(date).format('YYYY-MM-DD HH:mm'),
                            status: 'recovery',
                        })
                    }
                }
            }

            occurrences.value = result;
        };

        const submit = () => {
            form.transform((data) => ({
                ...data,
                schedule: occurrences.value
            }))
            const path = props.editing ? route('cours.update', {cour: props.lesson.id}) : route('cours.store');
            form.post(path);
        }

        return {
            form,
            submit,
            setupOccurrences,
            generateOccurrences,
            dateList,
            occurrences,
            disabledDates,
            textInputOptions,
            editSpecificDate,
            lexicon,
        }
    },
}
</script>

<style>
.calendar .vc-weeks {
    padding: 12px;
}
</style>
