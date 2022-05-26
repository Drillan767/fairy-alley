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
                                        <jet-label value="Choix n°1"/>
                                        <p>{{ firstLessonChoice }}</p>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Choix n°2"/>
                                        <p>{{ secondLessonChoice }}</p>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Cours de l'année précédente" />
                                        <p>{{ currentUser.current_year_data.last_year_class }}</p>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Décision de l'administration" />
                                        <jet-select :choices="lessons" v-model="form.lesson_decision" />
                                        <jet-input-error :message="form.errors.lesson_id" class="mt-2" />
                                    </div>

                                    <div class="col-span-6">
                                        <jet-label value="Données annuelles de santé" />
                                        <jet-textarea v-model="form.year_data.health_data" class="w-full" />
                                        <jet-input-error :message="form.errors.yearly_health_data" class="mt-2" />
                                    </div>

                                    <div class="col-span-6">
                                        <jet-label value="Document joint par l'utilisateur" />
                                        <jet-file-upload
                                            :currentFile="currentUser.current_year_data.file ?? ''"
                                            @input="handleUpload"
                                        />

                                        <a
                                            target="_blank"
                                            v-if="currentUser.current_year_data.file"
                                            :href="currentUser.current_year_data.file.url"
                                            class="btn btn-sm btn-ghost mt-4"
                                        >
                                            Télécharger le document
                                        </a>
                                    </div>

                                    <h2 class="font-semibold text-l text-gray-700 leading-tight mb-5">
                                        Paiement
                                    </h2>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Total" />
                                        <jet-input />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">

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
import JetFileUpload from '@/Jetstream/FileUpload.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetSelect from '@/Jetstream/Select.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import { useForm } from "@inertiajs/inertia-vue3";
import JetTextarea from '@/Jetstream/Textarea.vue';
import {computed, onMounted, ref} from "vue";

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
        JetFileUpload,
        JetTextarea
    },

    props: {
        currentUser: Object,
        subscription: Object,
        lessons: Array,
        renewalData: Object,
        flash: {
            type: Object,
            required: false
        },
    },

    setup (props) {
        const form = useForm({
            lesson_decision: 0,
            renewal_status: 0,
            year_data: {
                health_data: '',
                total: 0,
                payments: [],
                file: null,
            },
        })

        onMounted(() => {
            const { currentUser } = props;
            form.lesson_decision = props.renewalData.admin_decision ?? null;
            form.renewal_status = currentUser.resubscription_status;
            form.year_data = {
                file: currentUser.current_year_data.file,
                health_data: currentUser.current_year_data.health_data,
                total: currentUser.current_year_data.total,
                payments: currentUser.current_year_data.payments
            };
        })

        const renewalStatuses = ref([
            {label: 'Demande de réinscription en cours', value: 1},
            {label: 'Documents manquants (certificat, etc)', value: 3},
            {label: 'Paiement manquant', value: 4},
            {label: 'Réinscription validée', value: 2},
        ])

        const handleUpload = (file) => {
            form.year_data.file = file
        };

        const firstLessonChoice = computed(() => {
            const firstLesson = props.renewalData.lesson_choices[0];
            const lesson = props.lessons.find((l) => l.value === parseInt(firstLesson))
            return lesson.label;
        });

        const secondLessonChoice = computed(() => {
            if (props.renewalData.lesson_choices[2] !== null) {
                const secondLesson = props.renewalData.lesson_choices[1];
                const lesson = props.lessons.find((l) => l.value === parseInt(secondLesson))
                return lesson.label;
            }

            return '';
        })

        onMounted(() => {
            form.lesson_id = props.subscription.lesson_id;
            form.renewal_status = props.currentUser.resubscription_status;
            form.yearly_health_data = props.currentUser.current_year_data.health_data
        })

        const submit = () => {
            form.post(route('utilisateur.renewal.store'))
        }

        return {
            firstLessonChoice,
            secondLessonChoice,
            renewalStatuses,
            form,
            handleUpload,
            submit,
        }
    }
}
</script>
