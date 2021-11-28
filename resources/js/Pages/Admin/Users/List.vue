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
                <div class="mx-auto sm:px-6 lg:px-8">
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
                                :rows="userList"
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
                                        <button @click="deleteUser(props.row)" v-if="props.row.role !== 'administrator'">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
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
import {computed, ref} from "vue";
import Swal from "sweetalert2";

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

    setup (props) {

        const userList = ref(props.users);
        const searchOptions = {
            enabled: true,
            placeholder: 'Rechercher...',
        };

        const columns = [
            {
                label: 'Prénom',
                field: 'firstname',
                sortable: true,
            },
            {
                label: 'Nom de famille',
                field: 'lastname',
                sortable: true,
            },
            {
                label: 'Genre',
                field: 'gender',
            },
            {
                label: 'Adresse e-mail',
                field: 'email',
            },
            {
                label: 'Téléphone',
                field: 'phone',
            },
            {
                label: 'Téléphone pro',
                field: 'pro',
            },
            {
                label: 'Cours',
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
                    ],
                    filterFn: (data, filterString) => {
                        const lessonId = parseInt(filterString);
                        if (lessonId === 0) {
                            return data === null;
                        } else {
                            return data && data.id === lessonId;
                        }
                    }
                }
            },
            {
                label: "Date d'inscription",
                field: 'created_at',
            },
            {
                label: "Statut de l'inscription",
                field: 'subscription_complete',
                filterOptions: {
                    enabled: true,
                    placeholder: 'Choisir',
                    filterDropdownItems: [
                        { value: 'n', text: 'Certificat manquant' },
                        { value: 'y', text: 'Paiement manquant / incomplet' },
                        { value: 'c', text: 'Signature absente' },
                        { value: 'n', text: 'Complet' }
                    ],
                }
            },
            {
                label: 'Notes',
                field: 'other_data',
            },
            {
                label: 'Actions',
                field: 'actions'
            }
        ];

        function deleteUser(user) {
            const feminine = {
                user: user.gender === 'F' ? 'utilisatrice' : 'utilisateur',
                deleted: user.gender === 'F' ? 'supprimée' : 'supprimé',
                if: user.gender === 'F' ? 'si elle' : "s'il",
            };

            Swal.fire({
                icon: 'warning',
                title: "Supprimer l'utilisateur ?",
                showCancelButton: true,
                cancelButtonText: 'Annuler',
                confirmButtonText: 'Supprimer',
                confirmButtonColor: '#DC2626',
                html: `<p>L'${feminine.user} ${user.full_name} est sur le point d'être ${feminine.deleted}.</p><br />
                        <p>Cela supprimera également ses informations ainsi que ses documents liés ${feminine.if} en avait.</p><br />
                        <p>Cette action est irreversible. Continuer ?</p>`
            })
                .then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(route('utilisateurs.destroy', {utilisateur: user.id}))
                        .then(() => {
                            this.userList = props.users.filter((u) => u.id !== user.id)
                            Swal.fire({
                                icon: 'success',
                                title: `${user.full_name} a été ${feminine.deleted} avec succès.`
                            })
                        })
                    }
                });
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
            userList,
            searchOptions,
            deleteUser,
            lessonList,
            columns,
        }
    }
}
</script>
