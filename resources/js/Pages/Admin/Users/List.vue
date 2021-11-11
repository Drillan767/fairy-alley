<template>
    <admin-layout title="Utilisateurs en cours d'inscription">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Utilisateurs
            </h1>
        </template>

        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert" v-if="flash.success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="ml-5">{{ flash.success }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <vue-good-table
                                :columns="columns"
                                :rows="users"
                                :search-options="searchOptions"
                            >
                                <template #table-row="props">
                                    <div v-if="props.column.field === 'lesson'">
                                        <template v-if="props.row.lesson_id === null">
                                            Aucun
                                        </template>
                                        <template v-else>
                                            {{ props.row.lesson.title }}
                                        </template>
                                    </div>
                                    <div v-else-if="props.column.field === 'actions'" class="flex justify-end">
                                        <Link :href="route('utilisateurs.show', {utilisateur: props.row.id})">
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

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from '@inertiajs/inertia-vue3';
import 'vue-good-table-next/dist/vue-good-table-next.css'
import { VueGoodTable } from 'vue-good-table-next';
import {computed} from "vue";

export default {
    title: 'Tous les utilisateurs',
    components: {
        AdminLayout,
        Link,
        VueGoodTable,
    },

    props: {
        users: {
            type: Array,
            required: true,
        },

        lessons: Array,

        flash: {
            type: Object,
            required: false
        }
    },

    data () {
        return {
            searchOptions: {
                enabled: true,
                placeholder: 'Rechercher...',
            }
        }
    },

    setup (props) {

        const columns = [
            {
                label: 'Nom complet',
                field: 'full_name',
                sortable: true,
            },
            {
                label: 'Groupe',
                field: 'lesson',
                filterOptions: {
                    enabled: true,
                    placeholder: 'Cours',
                    filterDropdownItems: [
                        {
                            text: 'Aucun',
                            value: 0,
                        },
                        ...props.lessons.map((lesson) => {
                            return {text: lesson.title, value: lesson.id}
                        }),
                    ]
                }
            },
            {
                label: 'Dossier complet ?',
                field: 'subscription_complete',
                filterOptions: {
                    enabled: true,
                    placeholder: 'Statut',
                    filterDropdownItems: [
                        { value: 'n', text: 'Certificat manquant' },
                        { value: 'y', text: 'Paiement manquant / incomplet' },
                        { value: 'c', text: 'Signature absente' },
                        { value: 'n', text: 'Complet' }
                    ],
                }
            },
            {
                label: "Date d'inscription",
                field: 'created_at',
            },
            {
                label: 'Actions',
                field: 'actions'
            }
        ]

        function deleteUser(user) {

        }

        const lessonList = computed(() => {
            let lessons = [];
            lessons.push({text: 'Aucun', value: 0})

            props.lessons.forEach((lesson) => {
                lessons.push({text: lesson.title, value: lesson.id})
            })

            return lessons;
        })

        return {
            deleteUser,
            lessonList,
            columns,
        }
    }
}
</script>
