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
            <jet-label for="content" value="Détail des cours" />
            <wysiwyg v-model="form.detail" :tiny="tiny" />
            <jet-input-error :message="form.errors.detail" class="mt-2" />
        </div>

        <div class="mt-4">
            <jet-label for="content" value="Processus de réservation du cours" />
            <wysiwyg v-model="form.process" :tiny="tiny" />
            <jet-input-error :message="form.errors.process" class="mt-2" />
        </div>

        <div class="mt-4">
            <jet-label for="content" value="Organisation des cours" />
            <wysiwyg v-model="form.organization" :tiny="tiny" />
            <jet-input-error :message="form.errors.organization" class="mt-2" />
        </div>

        <div class="mt-4">
            <jet-label for="content" value="Conditions" />
            <wysiwyg v-model="form.conditions" :tiny="tiny" />
            <jet-input-error :message="form.errors.conditions" class="mt-2" />
        </div>

        <div class="mt-4">
            <jet-label for="content" value="Ajouter des horaires" />
            <div
                v-for="(entry, index) in schedule"
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
                <jet-button type="button" @click="add">Ajouter</jet-button>
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
import JetInput from '@/Jetstream/Input.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Wysiwyg from '@/Jetstream/Wysiwyg.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import {useForm} from "@inertiajs/inertia-vue3";
import {computed} from "vue";

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
    },

    setup (props) {

        const data = props.editing ? {
            ...props.lesson,
            _method: 'PUT',
        } : {
            title: '',
            description: '',
            detail: '',
            process: '',
            organization: '',
            schedule: [{ day: '', begin: '', end: ''}],
            conditions: '',
        }

        const form = useForm(data);

        const options = [
            {date: 'Lundi'},
            {date: 'Mardi'},
            {date: 'Mercredi'},
            {date: 'Jeudi'},
            {date: 'Vendredi'},
        ]

        const schedule = computed(() => Array.isArray(form.schedule) ? form.schedule : JSON.parse(form.schedule))

        const add = () => {
            form.schedule.push({ day: '', begin: '', end: ''})
        }

        const remove = (index) => {
            form.schedule.splice(index, 1)
        }

        function submit () {
            const path = props.editing ? route('cours.update', {lesson: props.lesson.id}) : route('cours.store');
            form.post(path);
        }

        return {
            form,
            submit,
            schedule,
            add,
            remove,
            options
        }
    },
}
</script>
