<template>
    <admin-layout title="Pages">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ subscriber.full_name }}
            </h1>
        </template>

        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <form @submit.prevent="submit">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-5">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                <h2 class="font-semibold text-l text-gray-700 leading-tight mb-5">
                                    Information sur le cours choisi
                                </h2>
                                <a class="btn btn-sm" :href="route('cours.edit', {cour: subscriber.subscription.lesson.id})" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                    {{ subscriber.subscription.lesson.title }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                            </div>
                        </div>
                    </div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-5">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                <h2 class="font-semibold text-l text-gray-700 leading-tight mb-5">
                                    Information pour l'année {{ years }}
                                </h2>
                                <div>
                                    <jet-label value="Informations de santé" />
                                    <p v-html="subscriber.current_year_data.health_data" v-if="subscriber.current_year_data.health_data"/>
                                    <p v-else>Non fournies</p>
                                </div>

                                <div class="mt-4">
                                    <jet-label value="Possibilités" />
                                    <ul class="list-disc">
                                        <li v-html="subscriber.subscription.selected_time"/>
                                        <li v-html="subscriber.subscription.fallback_time"/>
                                    </ul>
                                </div>

                                <div class="mt-4" v-if="subscriber.subscription.invites.length">
                                    <jet-label value="Personne(s) invité(es)" />
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>
                                                Prénom
                                            </th>
                                            <th>
                                                Nom de famille
                                            </th>
                                            <th>
                                                Adresse e-mail
                                            </th>
                                            <th>
                                                Numéro de téléphone
                                            </th>
                                            <th>
                                                Cours souhaité
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(invite, i) in subscriber.subscription.invites" :key="i">
                                            <td>{{ invite.firstname }}</td>
                                            <td>{{ invite.lastname }}</td>
                                            <td>{{ invite.email }}</td>
                                            <td>{{ invite.phone }}</td>
                                            <td>{{ invite.title }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4">
                                    <jet-label value="Certificat médical" />
                                    <div v-if="subscriber.current_year_data.file">
                                        <a class="btn btn-sm btn-outline" :href="subscriber.current_year_data.file.url" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            {{ subscriber.current_year_data.file.title }}
                                        </a>
                                    </div>
                                    <div v-else>
                                        <p>Non fourni</p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <jet-label value="Paiement" />
                                    <div v-if="subscriber.current_year_data.payment_received_at">
                                        <p>Reçu le {{ subscriber.current_year_data.payment_received_at }}</p>
                                    </div>
                                    <div v-else>
                                        <jet-label value="Reçu le :" class="mb-2" />
                                        <jet-input type="date" class="form-checkbox" v-model="form.payment_received_at" />
                                    </div>
                                </div>
                                <!-- v-if="reply_transmitted_via !== 'Site internet'" -->
                                <div class="mt-4" >
                                    <jet-label value="Signature de la préinscription" />
                                    <div v-if="subscriber.current_year_data.pre_registration_signature">
                                        <p>Faite le {{ subscriber.current_year_data.pre_registration_signature }}</p>
                                    </div>
                                    <div v-else>
                                        <jet-label value="Faite le" class="mb-2" />
                                        <jet-input type="date" class="form-checkbox" v-model="form.pre_registration_signature" />
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <jet-label for="observations" value="Observations" />
                                    <jet-textarea id="observations" v-model="form.observations" class="mt-1 block w-full"/>
                                    <jet-input-error :message="form.errors.observations" class="mt-2" />
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
import JetInput from '@/Jetstream/Input.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import { useForm } from "@inertiajs/inertia-vue3";
import JetTextarea from '@/Jetstream/Textarea.vue'
import dayjs from 'dayjs';
import {computed} from "vue";
import Swal from "sweetalert2";

export default {
    title () {
        return `Inscription de ${this.subscriber.full_name}`
    },

    props: ['subscriber'],

    components: {
        AdminLayout,
        JetInput,
        JetButton,
        JetLabel,
        JetSecondaryButton,
        JetInputError,
        JetTextarea,
    },

    setup (props) {
        const form = useForm({
            // User info.
            ...props.subscriber,
            // Year data infos.
            decision: '',
            selected_time: '',
            payment_received_at: '',
            pre_registration_signature: '',
            observations: '',
            feedback: '',
        });

        function submit () {
            Swal.fire({
                icon: 'question',
                title: 'Que voulez-vous faire ?',
                showDenyButton: true,
                denyButtonText: 'Marquer comme incomplet',
                showCancelButton: true,
                cancelButtonText: 'Annuler',
                confirmButtonText: "Valider l'inscription",
                confirmButtonColor: '#10B981',
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let fields = [];
                    if (!form.payment_received_at) fields.push('le paiement');
                    if (!form.pre_registration_signature) fields.push('la signature');
                    if (!props.subscriber.current_year_data.file) fields.push('le certificat médical');

                    if (fields.length > 0) {
                        let list = '';
                        fields.forEach((field) => {
                            list += `<li>- ${field}</li>`
                        })

                        Swal.fire({
                            icon: 'error',
                            title: 'Inscription impossible',
                            html: `
                                    <p>Les champs suivants sont manquants et requis :</p>
                                    <ul>
                                    ${list}
                                    </ul>
                                    `,
                            confirmButtonText: 'Valider',
                        })
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: "Confirmer l'inscription ?",
                            text: 'Un email va être envoyé à la personne. Continuer ?',
                            confirmButtonText: 'Valider',
                            showCancelButton: true,
                            cancelButtonText: 'Annuler',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                send('accepted')
                            }
                        })
                    }
                } else if (result.isDenied) {
                    Swal.fire({
                        icon: 'info',
                        text: 'Veuillez indiquer les informations manquantes. Un email sera envoyé à la personne pour le lui informer.',
                        input: 'textarea',
                        confirmButtonText: 'Valider',
                        showCancelButton: true,
                        cancelButtonText: 'Annuler',
                        inputValidator: (feedback) => {
                            if (!feedback) return 'Le champs est requis';
                        },
                        preConfirm: (feedback) => {
                            form.feedback = feedback;
                            send('missing');
                        }
                    })
                }
            })
        }

        function send (decision) {
            form.decision = decision;
            form.post(route('utilisateurs.subscribe'));
        }

        const years = computed(() => `${dayjs().year()} - ${dayjs().add(1, 'year').year()}`)

        return {
            form,
            submit,
            years,
        }
    }
}
</script>
