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
                    <div class="flex justify-end mb-5">
                        <a :href="route('utilisateurs.create')" class="btn btn-primary">
                            Nouvel utilisateur
                        </a>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <vue-good-table
                                :columns="columns"
                                ref="vuegoodtable"
                                :rows="userList"
                                :search-options="searchOptions"
                                :sort-options="sortOption"
                                @on-sort-change="onSortChange"
                                @on-search="onSearch"
                                @on-column-filter="onColumnFilter"
                            >
                                <template #table-row="props">
                                    <div v-if="props.column.field === 'lesson'">
                                        <template v-if="props.row.subscription">
                                            {{ props.row.lesson_title }}
                                        </template>
                                        <template v-else>
                                            Aucun
                                        </template>
                                    </div>
                                    <div v-else-if="props.column.field === 'hour'">
                                        <template v-if="props.row.subscription">
                                            {{ props.row.subscription.selected_time }}

                                            <template v-if="props.row.subscription.fallback_time">
                                                <b>ou</b> {{ props.row.subscription.fallback_time }}
                                            </template>
                                        </template>
                                        <template v-else>
                                            N/A
                                        </template>
                                    </div>
                                    <div v-else-if="props.column.field === 'role'">
                                        {{ roles[props.row.role].display }}
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
import {computed, onMounted, ref, toRaw} from "vue";
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
        roles: Object,
        flash: {
            type: Object,
            required: false
        }
    },

    setup (props) {
        const userList = ref(props.users);
        const vuegoodtable = ref(null)

        const roleList = computed(() => {
            let list = [];
            let roles = toRaw(props.roles)
            for (const property in roles) {
                list.push({
                    value: property,
                    text: props.roles[property].display,
                })
            }

            return list;
        })

        const searchOptions = {
            enabled: true,
            placeholder: 'Rechercher...',
        };

        const sortOption = {
            enabled: true,
            initialSortBy: JSON.parse(localStorage.getItem('sort')),
        };

        onMounted(() => {
            let searchTerm = localStorage.getItem('globalSearch');

            if (searchTerm) {
                vuegoodtable.value.globalSearchTerm = searchTerm;
            }

        });

        const onSortChange = (params) => {
            const sortValue = params ? toRaw(params[0]) : null;
            if (sortValue) {
                const { type } = sortValue;
                if (type !== 'none') {
                    localStorage.setItem('sort', JSON.stringify(sortValue));
                } else {
                    localStorage.setItem('sort', "{}")
                }
            }
        };

        const onColumnFilter = (params) => {
            const filterValue = toRaw(params.columnFilters);
            const filters = Object.keys(filterValue);
            filters.forEach((f) => localStorage.setItem(f, filterValue[f]))
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
                label: 'Adresse e-mail',
                field: 'email',
            },
            {
                label: 'Téléphone',
                field: 'phone',
            },
            {
                label: 'Cours',
                field: 'lesson',
                filterOptions: {
                    enabled: true,
                    filterValue: localStorage.getItem('lesson'),
                    placeholder: 'Sélectionner...',
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
                            return data === undefined
                        } else {
                            return data && data.id === lessonId;
                        }
                    }
                }
            },
            {
                label: "Statut",
                field: 'role',
                filterOptions: {
                    enabled: true,
                    filterValue: localStorage.getItem('role'),
                    placeholder: 'Choisir',
                    filterDropdownItems: roleList.value,
                }
            },
            {
                label: 'Actions',
                field: 'actions'
            }
        ];

        const onSearch = (e) => {
            localStorage.setItem('globalSearch', e.searchTerm)
        }

        const deleteUser = (user) => {
            const feminine = {
                user: user.gender === 'F' ? 'utilisatrice' : 'utilisateur',
                archived: user.gender === 'F' ? 'archivée' : 'archivé',
                deleted: user.gender === 'F' ? 'supprimée' : 'supprimé',
                if: user.gender === 'F' ? 'si elle' : "s'il",
            };

            Swal.fire({
                icon: 'warning',
                title: "Supprimer l'utilisateur ?",
                showCancelButton: true,
                showDenyButton: true,
                cancelButtonText: 'Annuler',
                denyButtonText: 'Archiver',
                confirmButtonText: 'Supprimer',
                confirmButtonColor: '#DC2626',
                html: `<p>L'${feminine.user} ${user.full_name} est sur le point d'être ${feminine.deleted}.</p><br />
                        <p>Vous pouvez également archiver l'${feminine.user}, et l'accès à son compte lui sera désormais impossible.</p><br />
                        <p>Si vous décidez de sa suppression, cela supprimera également ses informations ainsi que ses documents liés ${feminine.if} en avait.</p><br />
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
                    } else if (result.isDenied) {
                        axios.post(route('utilisateurs.archive', {user: user.id}))
                            .then(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: `${user.full_name} a été ${feminine.archived} avec succès.`
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
            sortOption,
            onColumnFilter,
            deleteUser,
            onSortChange,
            onSearch,
            lessonList,
            columns,
            vuegoodtable,
        }
    }
}
</script>
