<template>
    <admin-layout title="Utilisateurs en phase de réinscription">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Utilisateurs en phase de réinscription
            </h1>
        </template>

        <div class="py-12">
            <div class="overflow-x-auto">
                <div class="mx-auto sm:px-6 lg:px-8">
                    <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert" v-if="flash.success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="ml-5">{{ flash.success }}</p>
                    </div>
                    <div class="flex items-center justify-end mb-5">
                        <a :href="route('utilisateurs.create')" class="btn btn-error">
                            Valider la nouvelle année
                        </a>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <vue-good-table
                                :rows="users"
                                :columns="columns"
                            >
                                <template #table-row="props">
                                    <div v-if="props.column.field === 'email'">
                                        <a :href="`mailto:${props.row.email}`" class="text-blue-700 underline">
                                            {{ props.row.email }}
                                        </a>
                                    </div>
                                    <div v-if="props.column.field === 'choice1'">
                                        {{ getLesson(props.row.id, 0) }}
                                    </div>
                                    <div v-else-if="props.column.field === 'choice2'">
                                        {{ getLesson(props.row.id, 1) }}
                                    </div>
                                    <div v-else-if="props.column.field === 'decision'">
                                        {{ findLesson(props.row.id) }}
                                    </div>
                                    <div v-else-if="props.column.field === 'resubscription_status'">
                                        {{ getStatus(props.row.resubscription_status) }}
                                    </div>
                                    <div v-else-if="props.column.field === 'action'" class="flex justify-center">
                                        <Link :href="route('utilisateur.renewal.show', {user: props.row.id})" v-if="props.row.resubscription_status !== null">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                    </div>
                                </template>
                            </vue-good-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import 'vue-good-table-next/dist/vue-good-table-next.css';
import { VueGoodTable } from 'vue-good-table-next';
import {ref, defineProps} from "vue";
import {Link} from "@inertiajs/inertia-vue3";

const props = defineProps({
    flash: {
        type: Object,
        required: false
    },
    users: Array,
    lessons: Array,
    renewals: Object,
})

const status = ref([
    {
        text: 'Aucun',
        value: null,
    },
    {
        text: 'En attente',
        value: 1,
    },
    {
        text: 'Réinscription complète',
        value: 2,
    },
    {
        text: 'Documents manquants',
        value: 3,
    },
    {
        text: 'Paiement manquant',
        value: 4,
    },
])

const columns = ref([
    {
        label: 'Nom de famille',
        field: 'lastname',
        sortable: true,
    },
    {
        label: 'Prénom',
        field: 'firstname',
        sortable: true,
        width: '100px',
    },
    {
        label: 'Adresse e-mail',
        field: 'email',
    },
    {
        label: 'Statut',
        field: 'resubscription_status',
        filterOptions: {
            placeholder: 'Sélectionner...',
            filterValue: '1',
            enabled: true,
            filterDropdownItems: [
                ...status.value
            ],
            filterFn: (data, filterString) => {
                if (filterString === 'Aucun') {
                    return data === null
                } else {
                    return data === parseInt(filterString)
                }
            }
        }
    },

    {
        label: 'Choix 1',
        field: 'choice1',
    },
    {
        label: 'Choix 2',
        field: 'choice2',
    },
    {
        label: 'Décision',
        field: 'decision',
    },
    {
        label: 'Documents validés',
        field: 'document',
    },
    {
        label: 'Paiement reçu',
        field: 'payment',
    },
    {
        label: 'Action',
        field: 'action',
    }
])

const getLesson = (user_id, choice) => {
    const relatedRenewalInfos = props.renewals[`user_${user_id}`]

    if (relatedRenewalInfos ) {
        const lessonId = relatedRenewalInfos.lesson_choices[choice]

        if (lessonId) {
            const relatedLesson = props.lessons.find((l) => l.id === parseInt(lessonId))
            return relatedLesson.title
        }

        return 'Aucun';
    }
    else {
        return 'N/C';
    }
}

const findLesson = (user_id) => {
    const relatedRenewalInfos = props.renewals[`user_${user_id}`]

    if (relatedRenewalInfos ) {
        // admin_decision
        const lessonId = relatedRenewalInfos['admin_decision'];

        if (lessonId) {
            return props.lessons.find((l) => l.id === parseInt(lessonId)).title
        }

        return '';

    }

    return '';
    // props.lessons.find((l) => l.id === lid).title
}

const getStatus = (user_status) => status.value.find((s) => s.value === user_status).text

</script>
