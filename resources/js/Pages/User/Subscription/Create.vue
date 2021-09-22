<template>
    <user-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Inscription au cours "{{ lesson.title }}"
            </h1>
        </template>

        <div class="py-12">
            <div class="overflow-x-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="mb-5">
                        <Link :href="route('profile.index')" class="btn btn-primary">
                            Retour
                        </Link>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <div class="mb-4">
                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Détail des cours
                                </h2>
                                <div v-html="lesson.detail"></div>
                            </div>

                            <div class="mb-4">
                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Processus de réservation du cours
                                </h2>

                                <div v-html="lesson.process" class="prose"></div>
                            </div>

                            <div class="mb-4">
                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Organisation des cours
                                </h2>

                                <div v-html="lesson.organization" class="prose"></div>
                            </div>

                            <div class="mb-4">
                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Acceptation des conditions
                                </h2>

                                <div v-html="lesson.conditions" class="prose"></div>
                            </div>

                            <form @submit.prevent="submit" class="mt-5">
                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Choix du cours
                                </h2>
                                <div>
                                    <jet-label for="health_data" value="Avez-vous des informations médicales dont vous voudriez nous faire part ?" />
                                    <jet-textarea id="health_data" class="mt-1 block w-full" v-model="form.health_data" />
                                    <jet-input-error :message="form.errors.health_data" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <div>
                                        <jet-label for="health_data" value="Certificat médical" />
                                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <!-- TODO: change icon + display selected file... somehow. -->
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                        <span>Téléversez un fichier</span>
                                                        <input id="file-upload" name="file-upload" type="file" class="sr-only">
                                                    </label>
                                                    <p class="pl-1">ou faites-le glisser / déposer</p>
                                                </div>
                                                <p class="text-xs text-gray-500">
                                                    PNG, JPG, PDF jusqu'à 10Mo.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="collapse w-full border rounded-box border-base-300 collapse-arrow">
                                        <input type="checkbox">
                                        <div class="collapse-title text-xl font-medium">
                                            Sélectionner un premier créneau horaire.
                                        </div>
                                        <div class="collapse-content">
                                            <p>Collapse content reveals with focus. If you add a checkbox, you can control it using checkbox instead of focus. Or you can force-open/force-close using
                                                <span class="badge badge-outline">collapse-open</span> and
                                                <span class="badge badge-outline">collapse-close</span> classes.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="collapse w-full border rounded-box border-base-300 collapse-arrow">
                                        <input type="checkbox">
                                        <div class="collapse-title text-xl font-medium">
                                            Sélectionner un deuxième créneau horaire.
                                        </div>
                                        <div class="collapse-content">
                                            <p>Collapse content reveals with focus. If you add a checkbox, you can control it using checkbox instead of focus. Or you can force-open/force-close using
                                                <span class="badge badge-outline">collapse-open</span> and
                                                <span class="badge badge-outline">collapse-close</span> classes.
                                                <span>https://codepen.io/azrikahar/pen/zYKyQxw</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <jet-label for="content" value="Avez-vous des amies qui souhaitent s'inscrire ?" />
                                    <div
                                        v-for="(entry, index) in form.invites"
                                        :key="index"
                                        class="flex justify-between mt-5"
                                    >
                                        <!-- TODO: Needs to be displayed in a nice way. Like, 2 fields per line and select at the end. -->
                                        <div>
                                            <jet-label for="content" value="Prénom" />
                                            <jet-input type="text" class="mt-1 block w-full" v-model="entry.firstname" />
                                            <jet-input-error :message="form.errors[`schedule.${index}.firstname`]" class="mt-2" />
                                        </div>
                                        <div>
                                            <jet-label for="content" value="Nom" />
                                            <jet-input type="text" class="mt-1 block w-full" v-model="entry.lastname" />
                                            <jet-input-error :message="form.errors[`schedule.${index}.lastname`]" class="mt-2" />
                                        </div>
                                        <div>
                                            <jet-label for="content" value="Téléphone" />
                                            <jet-input type="text" class="mt-1 block w-full" v-model="entry.phone" />
                                            <jet-input-error :message="form.errors[`schedule.${index}.phone`]" class="mt-2" />
                                        </div>
                                        <div>
                                            <jet-label for="content" value="Email" />
                                            <jet-input type="email" class="mt-1 block w-full" v-model="entry.email" />
                                            <jet-input-error :message="form.errors[`schedule.${index}.email`]" class="mt-2" />
                                        </div>
                                        <div>
                                            <jet-label for="content" value="Cours souhaité" />
                                            <select v-model="entry.day">
                                                <option value="" selected="selected">Sélectionner...</option>
                                                <option v-for="option in lessons" :key="option" :value="option.id">{{ option.title }}</option>
                                                <jet-input-error :message="form.errors[`schedule.${index}.day`]" class="mt-2" />
                                            </select>
                                        </div>
                                        <div class="flex">
                                            <jet-secondary-button @click="remove(index)">Retirer</jet-secondary-button>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-center">
                                        <jet-button type="button" @click="add">Ajouter</jet-button>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div class="p-1 card bordered">
                                        <div class="form-control">
                                            <label class="cursor-pointer">
                                                <input type="checkbox" v-model="form.accepts" class="checkbox">
                                                <span class="label-text ml-5">
                                                    Le participant accepte les conditions d’inscription ci-dessus
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <jet-input-error :message="form.errors.accepts" class="mt-2" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </user-layout>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout.vue'
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";
import JetInput from '@/Jetstream/Input.vue'
import JetTextarea from '@/Jetstream/Textarea.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetInputError from '@/Jetstream/InputError.vue'

export default {
    props: ['lesson', 'lessons', 'user'],
    components: {
        Link,
        UserLayout,
        JetInput,
        JetTextarea,
        JetLabel,
        JetButton,
        JetInputError,
        JetSecondaryButton
    },

    setup (props) {
        const form = useForm({
            lesson_id: props.lesson.id,
            user_id: props.user.id,
            medical_certificate: null,
            schedule_choice: [],
            invites: [{firstname: '', lastname: '', email: '', phone: ''}],
            health_data: '',
            accepts: false,
        })

        const add = () => {
            form.invites.push({firstname: '', lastname: '', email: '', phone: ''})
        }

        const remove = (index) => {
            form.invites.splice(index, 1)
        }

        function submit() {

        }

        return  {
            form,
            submit,
            add,
            remove,
        }
    }
}
</script>
