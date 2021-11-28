<template>
    <admin-layout title="Utilisateurs en cours d'inscription">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Premiers contacts
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
                    <div class="flex justify-end mb-5">
                        <a :href="route('pages.create')" class="btn btn-primary">
                            Nouvel utilisateur
                        </a>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <table class="min-w-max w-full table-auto">
                                <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-center">Prénom</th>
                                    <th class="py-3 px-6 text-center">Nom de famille</th>
                                    <th class="py-3 px-6 text-center">Email</th>
                                    <th class="py-3 px-6 text-center">Date du contact</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-gray-200 hover:bg-gray-100" v-for="(contact, i) in contacts" :key="i">
                                        <td class="py-3 px-6 text-center whitespace-nowrap">
                                            <span class="font-medium">{{ contact.firstname }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-center whitespace-nowrap">
                                            <span class="font-medium">{{ contact.lastname }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-center whitespace-nowrap">
                                            <span class="font-medium">{{ contact.email }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-center whitespace-nowrap">
                                            <span class="font-medium">{{ contact.created_at }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-center whitespace-nowrap">
                                            <div class="flex justify-center">
                                                <Link :href="route('premiers-contacts.index', {contact: contact.id})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </Link>

                                                <button @click.prevent="deleteContact(contact)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
import Swal from "sweetalert2";

export default {
    title: 'Premiers contacts',
    components: {
        AdminLayout,
        Link,
    },

    props: {
        contacts: {
            type: Array,
            required: true,
        },
        flash: {
            type: Object,
            required: false,
        }
    },

    setup(props) {
        const deleteContact = (contact) => {
            Swal.fire({
                icon: 'warning',
                title: `Supprimer le contact de ${contact.full_name} ?`,
                text: `Les informations liées à cette personne seront supprimées. Cette action est irréversible. Continuer ?`,
                showCancelButton: true,
                cancelButtonText: 'Annuler',
                confirmButtonText: 'Supprimer',
                confirmButtonColor: '#DC2626'
            })
                .then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(route('firstContact.destroy', {contact: contact.id}))
                            .then(() => {
                                this.pageList = this.pageList.filter(c => c.id !== contact.id)
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Contact supprimé.',
                                    text: 'Le premier contact a bien été supprimé'
                                })
                            })
                    }
                })
        }

        return {
            deleteContact,
        }
    }
}
</script>
