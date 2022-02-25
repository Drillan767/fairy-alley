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
                    :disabled-dates="disabledDates"
                    format="dd/MM/yyyy"
                    :enableTimePicker="false"
                    :textInputOptions="textInputOptions"
                    selectText="Confirmer"
                    inputFormat="dd/MM/yyyy"
                />
            </div>

            <div class="mt-8" v-if="setupOccurrences.occurrenceStartDate !== ''">
                <jet-button @click.prevent="generateOccurrences">Générer</jet-button>
            </div>
        </div>

        <template v-if="occurrences.length">
            <div class="mt-4">
                <jet-label>Dates des cours</jet-label>
                <div class="flex flex-wrap">
                    <div v-for="(date, i) in validDates" :key="i" class="w-1/5 px-4 gap-x-2 mb-2">
                        <p class="list-item">{{ date.date }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <jet-label>Cours annulés</jet-label>
                <div class="flex flex-wrap">
                    <div v-for="(date, i) in cancelledDates" :key="i" class="w-1/5 px-4 gap-x-2 mb-2">
                        <p class="list-item">{{ date.date }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <jet-label>Date des jours de récupération</jet-label>
                <div class="flex flex-wrap">
                    <div v-for="(date, i) in recoveryDates" :key="i" class="w-1/5 px-4 gap-x-2 mb-2">
                        <p class="list-item">{{ date.date }}</p>
                    </div>
                </div>
            </div>

        </template>

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
        JetInput,
        JetLabel,
        JetButton,
        JetSecondaryButton,
        JetInputError,
        Wysiwyg,
        Datepicker,
    },

    setup (props) {
        onMounted(() => {
            if (props.editing) {
                setupOccurrences.value.nbOccurrences = props.lesson.schedule.length
                setupOccurrences.value.occurrenceStartDate = props.lesson.schedule[0].date

                generateOccurrences();
            }
        })

        const occurrences = ref([]);

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
            ref: '',
            occurrences: []
        }
        const form = useForm(data);

        const date = dayjs().startOf('day').toDate()
        const dateList = ref([{date: date}]);
        const textInputOptions = ref({
            format: 'dd.MM.yyyy'
        })

        const validDates = computed(() => {
            if (occurrences.value.length > 0) {
                return occurrences.value.filter(o => o.status === 'ok');
            } else {
                return []
            }
        })

        const cancelledDates = computed(() => {
            if (occurrences.value.length > 0) {
                return occurrences.value.filter(o => o.status === 'cancelled');
            } else {
                return []
            }
        })

        const recoveryDates = computed(() => {
            if (occurrences.value.length > 0) {
                return occurrences.value.filter(o => o.status === 'recovery');
            } else {
                return []
            }
        })

        const generateOccurrences = () => {
            const result = [];
            let nbOccurrences = setupOccurrences.value.nbOccurrences;
            let date = setupOccurrences.value.occurrenceStartDate;

            result.push({
                date: dayjs(date).format('DD/MM/YYYY'),
                status: 'ok',
            })

            for (let i = 1; i < nbOccurrences; i++) {
                date = dayjs(date).add(1, 'w');

                if (i < setupOccurrences.value.nbOccurrences) {
                    // All of the following happens if there was no need to add days because of a holiday.
                    if (toRaw(props.holidays).includes(dayjs(date).format('YYYY-MM-DD'))) {
                        nbOccurrences++
                        result.push({
                            date: dayjs(date).format('DD/MM/YYYY'),
                            status: 'cancelled',
                        })
                    } else {
                        result.push({
                            date: dayjs(date).format('DD/MM/YYYY'),
                            status: 'ok',
                        })
                    }

                } else {
                    // All of the following happens if we're beyond the initial maximim occurrences planned.
                    // If the day is already exceeding the initial limit, we'll just ignore it and go on.
                    if (!props.holidays.includes(dayjs(date).format('YYYY-MM-DD'))) {
                        result.push({
                            date: dayjs(date).format('DD/MM/YYYY'),
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
            validDates,
            cancelledDates,
            recoveryDates,
        }
    },
}
</script>

<style>
.calendar .vc-weeks {
    padding: 12px;
}
</style>
