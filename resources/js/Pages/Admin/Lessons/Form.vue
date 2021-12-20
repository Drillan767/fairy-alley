<template>
    <form @submit.prevent="submit">
        <div v-for="i in form.occurrence" class="pb-10">
            <Datepicker
                v-model="date"
                locale="fr"
                cancelText="Annuler"
                selectText="Confirmer"
                minutesIncrement="30"
                inputFormat="dd/MM/yyyy HH:mm"
            >

            </Datepicker>
        </div>

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
                <jet-label for="content" value="Ajouter des occurrences" />
                <jet-input id="occurrences" type="number" min="0" class="mt-1 block w-full" v-model="form.occurrence" />
                <jet-input-error :message="form.errors.occurrence" class="mt-2" />
            </div>
            <div class="w-1/4 flex items-end justify-center">
                <div>
                    <jet-button type="button" @click="add">Ajouter</jet-button>
                </div>
            </div>


<!--            <div
                v-for="(entry, index) in form.schedule"
                :key="index"
                class="flex justify-between mt-5"
            >
                <div>
                    <jet-label for="content" value="Sélectionner un jour" />
                    <select v-model="entry.day">
                        <option value="" selected="selected">Sélectionner...</option>
                        <option v-for="option in options" :key="option">{{ option.date }}</option>
                        <jet-input-error :message="form.errors[`schedule.${index}.day`]" class="mt-2" />
                    </select>
                </div>
                <div>
                    <jet-label for="content" value="Sélectionner une heure de début (format 00:00)" />
                    <jet-input type="text" class="mt-1 block w-full" v-model="entry.begin" />
                    <jet-input-error :message="form.errors[`schedule.${index}.begin`]" class="mt-2" />
                </div>
                <div>
                    <jet-label for="content" value="Sélectionner une heure de fin (format 00:00)" />
                    <jet-input type="text" class="mt-1 block w-full" v-model="entry.end" />
                    <jet-input-error :message="form.errors[`schedule.${index}.end`]" class="mt-2" />
                </div>
                <div class="flex">
                    <jet-secondary-button @click="remove(index)">Retirer</jet-secondary-button>
                </div>
            </div>
            <div class="mt-4 flex justify-center">

            </div>-->

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
import JetInput from '@/Jetstream/Input.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Wysiwyg from '@/Jetstream/Wysiwyg.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import Datepicker from 'vue3-date-time-picker';
import 'vue3-date-time-picker/dist/main.css'
import {useForm} from "@inertiajs/inertia-vue3";
import {computed, ref} from "vue";

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
        const date = ref(new Date());
        const data = props.editing ? {
            ...props.lesson,
            _method: 'PUT',
        } : {
            title: '',
            description: '',
            ref: '',
            occurrence: 0,
            schedule: [{ day: '', begin: '', end: ''}],
        }

        const form = useForm(data);

        const options = [
            {date: 'Lundi'},
            {date: 'Mardi'},
            {date: 'Mercredi'},
            {date: 'Jeudi'},
            {date: 'Vendredi'},
            {date: 'Samedi'},
            {date: 'Dimanche'},
        ]

        const schedule = computed(() => Array.isArray(form.schedule) ? form.schedule : JSON.parse(form.schedule))

        const add = () => {
            form.schedule.push({ day: '', begin: '', end: ''})
        }

        const remove = (index) => {
            form.schedule.splice(index, 1)
        }

        function submit () {
            const path = props.editing ? route('cours.update', {cour: props.lesson.id}) : route('cours.store');
            form.post(path);
        }

        return {
            form,
            submit,
            schedule,
            date,
            add,
            remove,
            options,
        }
    },
}
</script>
