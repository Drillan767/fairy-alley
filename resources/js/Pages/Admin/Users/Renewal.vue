<template>
    <admin-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Renouvellement de l'abonnement de {{ currentUser.full_name }}
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

                                <div class="grid grid-cols-6 gap-4">
                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Cours choisi" />
                                        <jet-select :choices="lessons" v-model="form.lesson_id" />
                                        <jet-input-error :message="form.errors.lesson_id" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Cours de l'année précédente" />
                                        <p>{{ currentUser.current_year_data.last_year_class }}</p>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Données annuelles de santé" />
                                        <jet-textarea v-model="form.yearly_health_data" class="w-full" />
                                        <jet-input-error :message="form.errors.yearly_health_data" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Statut de la réinscription" />
                                        <jet-select :choices="renewalStatuses" v-model="form.renewal_status" />
                                        <jet-input-error :message="form.errors.renewal_status" class="mt-2" />
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <jet-button>
                                        Enregistrer
                                    </jet-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import JetInput from '@/Jetstream/Input.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetSelect from '@/Jetstream/Select.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import { useForm } from "@inertiajs/inertia-vue3";
import JetTextarea from '@/Jetstream/Textarea.vue';
import {onMounted, ref} from "vue";

export default {
    title () {
        return 'Renouvellement de l\'abonnement de ' + this.currentUser.full_name
    },

    components: {
        AdminLayout,
        JetInput,
        JetButton,
        JetLabel,
        JetSelect,
        JetInputError,
        JetCheckbox,
        JetTextarea
    },

    props: {
        currentUser: Object,
        subscription: Object,
        lessons: Array,
        flash: {
            type: Object,
            required: false
        },
    },

    setup (props) {
        const form = useForm({
            user_id: props.currentUser.id,
            lesson_id: 0,
            renewal_status: 0,
            yearly_health_data: '',
        })

        const renewalStatuses = ref([
            {label: 'Réinscription en cours', value: 1},
            {label: 'Documents manquants (certificat, etc)', value: 3},
            {label: 'Paiement manquant', value: 4},
            {label: 'Réinscription validée', value: 2},
        ])

        onMounted(() => {
            form.lesson_id = props.subscription.lesson_id;
            form.renewal_status = props.currentUser.resubscription_status;
            form.yearly_health_data = props.currentUser.current_year_data.health_data
        })

        const submit = () => {
            form.post(route('utilisateur.renewal.store'))
        }

        return {
            renewalStatuses,
            form,
            submit,
        }
    }
}
</script>
