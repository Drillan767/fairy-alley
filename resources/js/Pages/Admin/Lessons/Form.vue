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

        <div class="mt-4 flex">
            <div class="w-3/4">
                <jet-label for="content" value="Occurrences" />
                <p>Nombre d'occurrences : {{ form.occurrence }}</p>
            </div>
            <div class="w-1/4 flex items-end justify-center">
                <div>
                    <jet-button type="button" @click="add">Ajouter</jet-button>
                    <jet-secondary-button @click="remove" class="ml-2">Retirer</jet-secondary-button>
                </div>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap">
            <div v-for="(date, i) in dateList" :key="i" class="w-1/4 px-4 mb-2">
                <jet-label :value="`Date pour l'occurence n°${i + 1}`" />
                <Datepicker
                    v-model="date.date"
                    locale="fr"
                    cancelText="Annuler"
                    textInput
                    format="dd/MM/yyyy HH:mm"
                    :textInputOptions="textInputOptions"
                    selectText="Confirmer"
                    minutesIncrement="15"
                    inputFormat="dd/MM/yyyy HH:mm"
                />
                <jet-input-error :message="form.errors[`schedule.${i}.date`]" class="mt-2" />
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
import {computed, onMounted, ref} from "vue";

export default {
    title: 'Nouveau cours',
    props: {
        lesson: {
            type: Object,
            required: false
        },
        editing: Boolean,
        tiny: String,

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
                form.occurrence = props.lesson.schedule.length
                dateList.value = props.lesson.schedule;
            }
        })

        const data = props.editing ? {
            ...props.lesson,
            _method: 'PUT',
        } : {
            title: '',
            description: '',
            ref: '',
            occurrence: 1,
            schedule: [],
        }
        const form = useForm(data);

        const date = dayjs().startOf('day').toDate()
        const dateList = ref([{date: date}]);
        const textInputOptions = ref({
            format: 'dd.MM.yyyy'
        })

        const add = () => {
            form.occurrence++;
            dateList.value.push({date: date});
        }

        const remove = () => {
            form.occurrence--;
            dateList.value.pop();
        }

        function submit () {
            form.schedule = dateList.value;
            const path = props.editing ? route('cours.update', {cour: props.lesson.id}) : route('cours.store');
            form.post(path);
        }

        return {
            form,
            submit,
            dateList,
            textInputOptions,
            add,
            remove,
        }
    },
}
</script>
