<template>
    <admin-layout title="Pages">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ currentUser.full_name }}
            </h1>
        </template>

        <div class="py-12">
            <div class="overflow-x-auto">
                <form @submit.prevent="submit">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert" v-if="flash.success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <p class="ml-5">{{ flash.success }}</p>
                        </div>
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                <h2 class="font-semibold text-l text-gray-700 leading-tight mb-5">
                                    Informations sur l'utilisateur
                                </h2>
                                <div>
                                    <jet-label for="firstname" value="Prénom" />
                                    <jet-input id="firstname" type="text" class="mt-1 block w-full" v-model="form.firstname" />
                                    <jet-input-error :message="form.errors.firstname" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="lastname" value="Nom de famille" />
                                    <jet-input id="lastname" type="text" class="mt-1 block w-full" v-model="form.lastname" />
                                    <jet-input-error :message="form.errors.lastname" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="email" value="Adresse email" />
                                    <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                                    <jet-input-error :message="form.errors.email" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label value="Genre" />
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio" name="accountType" value="M" v-model="form.gender" :checked="form.gender === 'M'">
                                        <span class="ml-2">Homme</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="radio" class="form-radio" name="accountType" value="F" v-model="form.gender" :checked="form.gender === 'F'">
                                        <span class="ml-2">Femme</span>
                                    </label>
                                    <jet-input-error :message="form.errors.gender" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="birthday" value="Date de naissance" />
                                    <jet-input id="birthday" type="date" class="mt-1 block w-full" v-model="form.birthday" />
                                    <jet-input-error :message="form.errors.birthday" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="phone" value="Numéro de téléphone" />
                                    <jet-input id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" />
                                    <jet-input-error :message="form.errors.phone" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="pro" value="Téléphone professionnel" />
                                    <jet-input id="pro" type="text" class="mt-1 block w-full" v-model="form.pro" />
                                    <jet-input-error :message="form.errors.pro" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="address1" value="Adresse" />
                                    <jet-input id="address1" type="text" class="mt-1 block w-full" v-model="form.address1" />
                                    <jet-input-error :message="form.errors.address1" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="address2" value="Complément d'adresse" />
                                    <jet-input id="address2" type="text" class="mt-1 block w-full" v-model="form.address2" />
                                    <jet-input-error :message="form.errors.address2" class="mt-2" />
                                </div>

                                <div class="mt-4 flex justify-between">
                                    <div class="w-1/4">
                                        <jet-label for="zipcode" value="Code postal" />
                                        <jet-input id="zipcode" type="text" class="mt-1 block w-full" v-model="form.zipcode" />
                                        <jet-input-error :message="form.errors.zipcode" class="mt-2" />
                                    </div>

                                    <div class="flex-1 ml-4">
                                        <jet-label for="city" value="Ville" />
                                        <jet-input id="city" type="text" class="mt-1 block w-full" v-model="form.city" />
                                        <jet-input-error :message="form.errors.city" class="mt-2" />
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <jet-label for="other_data" value="Autres informations" />
                                    <jet-textarea id="other_data" type="text" class="mt-1 block w-full" v-model="form.other_data" />
                                    <jet-input-error :message="form.errors.other_data" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="max-w-7xl mx-auto mt-5 sm:px-6 lg:px-8 mb-5">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                <h2 class="font-semibold text-l text-gray-700 leading-tight mb-5">
                                    Inscriptions
                                </h2>

                                <div class="mt-4" v-if="['subscriber', 'substitute', 'administrator'].includes(currentUser.role)">
                                    <jet-label for="suggestions" value="Services suggérés" />
                                    <smart-tagz
                                        input-placeholder="Sélectionnez un service..."
                                        autosuggest
                                        @keypress.enter.prevent
                                        @click.prevent
                                        :sources="availableServices"
                                        :default-tags="defaultServices"
                                        :on-changed="serviceSelected"
                                        v-model="form.suggestions"
                                        :allow-duplicates="false"
                                        :theme="{
                                            primary: '#212A39FF',
                                            background: '#fff',
                                            tagTextColor: '#fff',
                                        }"
                                    />
                                </div>

                                <hr />

                                <div class="mt-4">
                                    <jet-label for="suggestions" value="Statut actuel" />
                                    <div class="flex items-center mt-2">
                                        <p>
                                            {{ roles[currentUser.role].display }}
                                        </p>

                                        <template v-if="currentUser.role !== 'administrator'">
                                            <jet-button-secondary class="ml-5" @click.prevent="changeRole">
                                                Changer le rôle
                                            </jet-button-secondary>
                                        </template>

                                    </div>
                                </div>

                                <hr class="mt-4"/>

                                <div class="mt-4">
                                    <jet-label for="suggestions" value="Informations sur le cours choisi" />
                                    <div class="flex mt-2">
                                        <template v-if="currentUser.lesson">
                                            <a class="btn btn-sm" :href="route('cours.edit', {cour: currentUser.lesson.id})" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                                {{ currentUser.lesson.title }}
                                            </a>

                                            <jet-button-secondary class="ml-5" @click.prevent="changeLesson">
                                                Changer le cours
                                            </jet-button-secondary>
                                        </template>
                                        <template v-else>
                                            <span class="btn btn-sm">Aucun</span>

                                            <jet-button-secondary class="ml-5" @click.prevent="changeLesson">
                                                Choisir un cours
                                            </jet-button-secondary>
                                        </template>

                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="max-w-7xl mx-auto mt-5 sm:px-6 lg:px-8 mb-5" v-if="currentUser.first_contact_data">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                <h2 class="font-semibold text-l text-gray-700 leading-tight mb-5">
                                    Informations personnelles
                                </h2>
                                <div class="mb-4">
                                    <p class="block font-bold text-sm text-gray-700">Situation familiale</p>
                                <p>{{ currentUser.first_contact_data.family_situation ?? 'N/C' }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="block font-bold text-sm text-gray-700">Nombre d'enfants</p>
                                    <p>{{ currentUser.first_contact_data.nb_children ?? 'N/C' }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="block font-bold text-sm text-gray-700">Profession</p>
                                    <p>{{ currentUser.first_contact_data.work ?? 'N/C' }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="block font-bold text-sm text-gray-700">Sports, loisirs, autres activités :</p>
                                    <p>{{ currentUser.first_contact_data.sports ?? 'N/C' }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="block font-bold text-sm text-gray-700">Objectifs :</p>
                                    <p>{{ currentUser.first_contact_data.objectives ?? 'N/C' }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="block font-bold text-sm text-gray-700">Traitement médical actuel :</p>
                                    <p>{{ currentUser.first_contact_data.medical_treatment ?? 'N/C' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="max-w-7xl mx-auto mt-5 sm:px-6 lg:px-8 mb-5" v-if="currentUser.current_year_data">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                <h2 class="font-semibold text-l text-gray-700 leading-tight mb-5">
                                    Informations de santé annuelle
                                </h2>

                                <div class="mb-4">
                                    <p class="block font-bold text-sm text-gray-700">Problème de santé connu : </p>
                                    <p>{{ currentUser.current_year_data.health_data ?? 'N/C' }}</p>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-5 mt-4 flex justify-end">
                        <jet-button type="submit">
                            Enregistrer
                        </jet-button>
                    </div>
                </form>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import JetButton from '@/Jetstream/Button.vue';
import JetButtonSecondary from "@/Jetstream/SecondaryButton.vue"
import JetInput from '@/Jetstream/Input.vue';
import JetTextarea from '@/Jetstream/Textarea.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import { useForm } from "@inertiajs/inertia-vue3";
import { SmartTagz } from "smart-tagz";
import "smart-tagz/dist/smart-tagz.css";
import {computed, toRaw, ref, onMounted} from "vue";
import Swal from "sweetalert2";
import {Inertia} from "@inertiajs/inertia";

export default {
    title () {
        return this.currentUser.full_name;
    },

    props: {
        currentUser: Object,
        services: Array,
        roles: Object,
        lessons: Object,
        flash: {
            type: Object,
            required: false
        },
    },

    components: {
        AdminLayout,
        JetButton,
        JetInput,
        JetTextarea,
        JetLabel,
        JetInputError,
        SmartTagz,
        JetButtonSecondary,
    },

    setup (props) {
        const form = useForm({
            _method: 'PUT',
            suggestions: [],
            ...props.currentUser
        })

        const roleList = ref({});

        const submit = () => {
            form.post(route('utilisateurs.update', {utilisateur: props.currentUser.id}))
        }

        onMounted(() => {
            for (const key in props.roles) {
                roleList.value[key] = props.roles[key].display;
            }
        })

        const serviceSelected = (items) => {
            let serviceList = [];
            const services = toRaw(props.services);
            items.forEach((item) => {
                const found = services.find((s) => s.title === item)?.id;
                if (found) serviceList.push(found)
            })
            form.suggestions = serviceList;
        }

        const defaultServices = computed(() => {
            if (props.currentUser.suggestions) {
                return props.currentUser.suggestions.map((s) => s.title)
            } else {
                return [];
            }
        })

        const changeLesson = async () => {
            const {value: lesson} = await Swal.fire({
                icon: 'info',
                title: 'Sélectionnez un nouveau cours',
                input: 'select',
                inputOptions: props.lessons,
                inputPlaceholder: 'Sélectionner...',
                showCancelButton: true,
                cancelButtonText: 'Annuler',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Veuillez sélectionner un cours'
                    }
                }
            })

            if (lesson) {
                Inertia.post(route('utilisateurs.change-lesson'), {
                    lid: lesson,
                    user: props.currentUser.id
                })
            }
        }

        const changeRole = async () => {
            console.log('coucou ?');
            const {value: role} = await Swal.fire({
                icon: 'info',
                title: 'Sélectionnez un nouveau rôle',
                input: 'select',
                inputOptions: roleList.value,
                inputPlaceholder: 'Sélectionner...',
                showCancelButton: true,
                cancelButtonText: 'Annuler',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Veuillez sélectionner un rôle'
                    }
                }
            })

            if (role) {
                const choice = await Swal.fire({
                    icon: 'warning',
                    title: `Confirmer le rôle "${props.roles[role].display}" ?`,
                    html: `<p>${props.roles[role].warning}</p><br /><p>Continuer ?</p>`,
                    confirmButtonText: 'Confirmer',
                    showCancelButton: true,
                    cancelButtonText: 'Annuler',
                })

                if (choice.isConfirmed) {
                    Inertia.post(route('utilisateurs.change-role'), {
                        role: role,
                        user: props.currentUser.id
                    })
                }
            }
        }

        const availableServices = computed(() => {
            return props.services.map((s) => s.title);
        })

        return {
            form,
            changeLesson,
            changeRole,
            availableServices,
            defaultServices,
            serviceSelected,
            submit,
        }
    }
}
</script>
