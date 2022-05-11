<template>
    <admin-layout title="Utilisateurs en cours d'inscription">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Renouveler votre abonnement
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert" v-if="flash.success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="ml-5">{{ flash.success }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <section class="text-gray-600 body-font">
                        <div class="container p-6 sm:px-20 mx-auto">
                            <div class="grid grid-cols-6 gap-4">
                                <div class="col-span-6 sm:col-span-3">
                                    <form @submit.prevent="submit">
                                        <div class="mt-4">
                                            <jet-label value="Sélectionnez le cours que vous souhaitez rejoindre l'année prochaine" />
                                            <jet-select :choices="lessons" v-model="form.lesson_id" class="mt-4"/>
                                            <jet-input-error :message="form.errors.lesson_id" class="mt-2" />
                                        </div>

                                        <div class="mt-4">
                                            <jet-label value="Date du dernier certificat médical d’aptitude pour la gymnastique"/>
                                            <jet-textarea class="w-full" v-model="form.health_issues" />
                                            <jet-input-error :message="form.errors.health_issues" class="mt-2" />
                                        </div>

                                        <div class="mt-4">
                                            <jet-label value="Avez-vous (ou avez-vous eu) des pb de santé / des pb de dos ou des membres etc. ?"/>
                                            <jet-textarea class="w-full" v-model="form.current_health_issues" />
                                            <jet-input-error :message="form.errors.current_health_issues" class="mt-2" />
                                        </div>

                                        <div class="mt-4">
                                            <jet-label value="Date du dernier certificat médical d’aptitude pour la gymnastique"/>
                                            <jet-textarea class="w-full" v-model="form.medical_treatment" />
                                            <jet-input-error :message="form.errors.medical_treatment" class="mt-2" />
                                        </div>

                                        <div class="mt-4 flex justify-end">
                                            <jet-button>Enregistrer</jet-button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <p>
                                        Pour que votre réinscription soit complète, il est nécessaire de sélectionner le cours
                                        que vous souhaitez suivre l'année prochaine, ainsi que d'envoyer le paiement pour l'année
                                        avant la fin de la période de réinscription.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import JetInput from '@/Jetstream/Input.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetTextarea from '@/Jetstream/Textarea.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import AdminLayout from "@/Layouts/AdminLayout.vue";
import JetSelect from '@/Jetstream/Select.vue';
import {useForm} from "@inertiajs/inertia-vue3";
import Swal from "sweetalert2";

export default {
    title: 'Renouveler votre abonnement',
    props: {
        lessons: Array,
        user: Object,
        flash: {
            type: Object,
            required: false
        }
    },

    components: {
        AdminLayout,
        JetInput,
        JetLabel,
        JetInputError,
        JetTextarea,
        JetButton,
        JetSelect,
    },

    setup (props) {
        const form = useForm({
            lesson_id: null,
            health_issues: '',
            current_health_issues: '',
            medical_treatment: '',
        });

        const submit = () => {
            form.post(route('renewal.update'))
        }

        return {
            form,
            submit
        }
    }
}
</script>
