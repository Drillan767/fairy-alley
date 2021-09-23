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

                                <div class="prose" v-html="lesson.process"></div>
                            </div>

                            <div class="mb-4">
                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Organisation des cours
                                </h2>

                                <div class="prose" v-html="lesson.organization"></div>
                            </div>

                            <div class="mb-4">
                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Acceptation des conditions
                                </h2>

                                <div class="prose" v-html="lesson.conditions"></div>
                            </div>

                            <form class="mt-5" @submit.prevent="submit">
                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Choix du cours
                                </h2>
                                <div>
                                    <jet-label for="health_data"
                                               value="Avez-vous des informations médicales dont vous voudriez nous faire part ?"/>
                                    <jet-textarea id="health_data" v-model="form.health_data"
                                                  class="mt-1 block w-full"/>
                                    <jet-input-error :message="form.errors.health_data" class="mt-2"/>
                                </div>

                                <div class="mt-4">
                                    <div>
                                        <jet-label for="health_data" value="Certificat médical"/>
                                        <jet-file-upload v-model="form.medical_certificate" :multiple="true"/>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="collapse w-full border rounded-box border-base-300 collapse-arrow">
                                        <input type="checkbox">
                                        <div class="collapse-title text-xl font-medium">
                                            Sélectionner un premier créneau horaire.
                                        </div>
                                        <div class="collapse-content">
                                            <p>Collapse content reveals with focus. If you add a checkbox, you can
                                                control it using checkbox instead of focus. Or you can
                                                force-open/force-close using
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
                                            <div class="grid sm:grid-cols-4 gap-6">
                                                <label
                                                    v-for="(day, i) in schedule"
                                                    :key="i"
                                                    class="relative bg-white p-5 rounded-lg shadow-md cursor-pointer"
                                                    for="plan-hobby">
                                                    <span class="text-2xl">{{ day.day }}</span>
                                                    <span class="font-semibold text-gray-500 leading-tight uppercase ml-1">
                                                            {{ day.begin }} - {{ day.end }}
                                                    </span>

                                                    <input id="plan-hobby" class="hidden" type="radio" v-model="form.schedule_choice2"
                                                           :value="`${day.day} ${day.begin}-${day.end}`"/>
                                                    <span aria-hidden="true"
                                                          class="hidden absolute inset-0 border-2 border-green-500 bg-green-200 bg-opacity-10 rounded-lg">
                                                    <span
                                                        class="absolute top-4 right-4 h-6 w-6 inline-flex items-center justify-center rounded-full bg-green-200">
                                                      <svg class="h-5 w-5 text-green-600" fill="currentColor"
                                                           viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path clip-rule="evenodd"
                                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                              fill-rule="evenodd"/>
                                                      </svg>
                                                    </span>
                                                  </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <jet-label for="content" value="Avez-vous des amies qui souhaitent s'inscrire ?"/>
                                    <div
                                        v-for="(entry, index) in form.invites"
                                        :key="index"
                                        class="flex justify-between mt-5"
                                    >
                                        <!-- TODO: Needs to be displayed in a nice way. Like, 2 fields per line and select at the end. -->
                                        <div>
                                            <jet-label for="content" value="Prénom"/>
                                            <jet-input v-model="entry.firstname" class="mt-1 block w-full" type="text"/>
                                            <jet-input-error :message="form.errors[`schedule.${index}.firstname`]"
                                                             class="mt-2"/>
                                        </div>
                                        <div>
                                            <jet-label for="content" value="Nom"/>
                                            <jet-input v-model="entry.lastname" class="mt-1 block w-full" type="text"/>
                                            <jet-input-error :message="form.errors[`schedule.${index}.lastname`]"
                                                             class="mt-2"/>
                                        </div>
                                        <div>
                                            <jet-label for="content" value="Téléphone"/>
                                            <jet-input v-model="entry.phone" class="mt-1 block w-full" type="text"/>
                                            <jet-input-error :message="form.errors[`schedule.${index}.phone`]"
                                                             class="mt-2"/>
                                        </div>
                                        <div>
                                            <jet-label for="content" value="Email"/>
                                            <jet-input v-model="entry.email" class="mt-1 block w-full" type="email"/>
                                            <jet-input-error :message="form.errors[`schedule.${index}.email`]"
                                                             class="mt-2"/>
                                        </div>
                                        <div>
                                            <jet-label for="content" value="Cours souhaité"/>
                                            <select v-model="entry.day">
                                                <option selected="selected" value="">Sélectionner...</option>
                                                <option v-for="option in lessons" :key="option" :value="option.id">
                                                    {{ option.title }}
                                                </option>
                                                <jet-input-error :message="form.errors[`schedule.${index}.day`]"
                                                                 class="mt-2"/>
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
                                                <input v-model="form.accepts" class="checkbox" type="checkbox">
                                                <span class="label-text ml-5">
                                                    Le participant accepte les conditions d’inscription ci-dessus
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <jet-input-error :message="form.errors.accepts" class="mt-2"/>
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
import {ref, onMounted, computed} from "vue";
import {useForm, Link} from "@inertiajs/inertia-vue3";
import JetInput from '@/Jetstream/Input.vue'
import JetTextarea from '@/Jetstream/Textarea.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetFileUpload from '@/Jetstream/FileUpload.vue'

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
        JetSecondaryButton,
        JetFileUpload,
    },

    setup(props) {
        const form = useForm({
            lesson_id: props.lesson.id,
            user_id: props.user.id,
            medical_certificate: null,
            schedule_choice1: '',
            schedule_choice2: '',
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

        onMounted(() => console.log(props.lesson.schedule))

        function submit() {

        }

        const schedule = computed(() => JSON.parse(props.lesson.schedule))

        return {
            form,
            submit,
            add,
            remove,
            schedule,
        }
    }
}
</script>

<style scoped>
input[type="radio"]:checked + span {
    display: block;
}
</style>
