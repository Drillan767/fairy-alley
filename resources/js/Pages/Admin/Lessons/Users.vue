<template>
    <admin-layout title="Pages">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Utilisateurs du cours "{{ lesson.title }}"
            </h1>
        </template>

        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <table class="min-w-max w-full table-auto">
                                <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Nom complet</th>
                                    <th class="py-3 px-6 text-left">Email</th>
                                    <th class="py-3 px-6 text-center">Téléphone</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="border-b border-gray-200 hover:bg-gray-100" v-for="(user, i) in lesson.users" :key="i">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ user.full_name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ user.email }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center whitespace-nowrap">
                                        <div class="flex justify-center">
                                            <span class="font-medium">{{ user.phone }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 flex justify-center whitespace-nowrap">
                                        <Link :href="route('utilisateurs.show', {utilisateur: user.id})">
                                            <div class="w-4 transform hover:text-purple-500 hover:scale-110">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </div>
                                        </Link>
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
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from "@inertiajs/inertia-vue3";

export default {
    title () {
        return `Utilisateurs du cours "${this.lesson.title}"`
    },

    props: ['lesson'],
    components: {
        AdminLayout,
        Link,
    }
}
</script>
