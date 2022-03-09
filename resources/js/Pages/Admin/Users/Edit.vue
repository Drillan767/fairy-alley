<template>
    <admin-layout title="Pages">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Éditer {{ subscriber.full_name }}
            </h1>
        </template>

        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <form @submit.prevent="submit">
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
        return `Profil de ${this.subscriber.full_name}`
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
                                icon: 'warning',
                                title: "Valider l'inscription malgré les éléments manquants ?",
                                html: `
                                    <p>Les champs suivants sont manquants et requis :</p>
                                    <ul>
                                    ${list}
                                    </ul>
                                    <p>Continuer malgré tout ?</p>
                                    `,
                                confirmButtonText: 'Valider',
                                showCancelButton: true,
                                cancelButtonText: 'Annuler',
                            })
                                .then((result) => {
                                    if (result.isConfirmed) {
                                        Swal.fire({
                                            icon: 'info',
                                            title: "Sélectionner une heure pour le cours",
                                            text: 'Un email va être envoyé à la personne avec les informations relatives au cours.',
                                            input: 'select',
                                            inputValue: props.subscriber.subscription.selected_time,
                                            inputOptions: dates(),
                                            confirmButtonText: 'Valider',
                                            showCancelButton: true,
                                            cancelButtonText: 'Annuler',
                                            inputValidator: (value) => {
                                                if (!value) {
                                                    return 'Vous devez sélectionner quelque chose'
                                                }
                                            },
                                            preConfirm(date) {
                                                form.selected_time = date;
                                                send('accepted');
                                            }
                                        })
                                    }
                                })
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: "Confirmer l'inscription ?",
                                text: 'Un email va être envoyé à la personne. Continuer ?',
                                input: 'select',
                                inputValue: props.subscriber.subscription.selected_time,
                                inputOptions: dates(),
                                confirmButtonText: 'Valider',
                                showCancelButton: true,
                                cancelButtonText: 'Annuler',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'Vous devez sélectionner quelque chose'
                                    }
                                },
                                preConfirm(date) {
                                    form.selected_time = date;
                                    send('accepted');
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

        function dates () {
            let date = {};
            props.subscriber.subscription.lesson.schedule.forEach((day) => {
                const element = `${day.day} ${day.begin} - ${day.end}`;
                date[element] = element;
            });

            return date;
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
