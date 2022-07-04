<template>
    <admin-layout title="Pages">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Listes des pages
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
                            Nouvelle page
                        </a>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <div class="pb-2.5 flex justify-end">
                                <button
                                    class="btn btn-sm mr-1"
                                    :class="{'btn-ghost': sort !== 'desc'}"
                                    @click="sortPages('desc')"
                                >
                                    Alphabetique
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                    </svg>

                                </button>
                                <button
                                    class="btn btn-sm mr-1"
                                    :class="{'btn-ghost': sort !== 'asc'}"
                                    @click="sortPages('asc')"
                                >
                                    Alphabetique
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                                    </svg>
                                </button>
                            </div>
                            <table class="min-w-max w-full table-auto">
                                <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Titre</th>
                                    <th class="py-3 px-6 text-left">Résumé</th>
                                    <th class="py-3 px-6 text-center">Statut</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                <tr
                                    class="border-b border-gray-200 hover:bg-gray-100 cursor-pointer"
                                    v-for="(page, i) in pageList"
                                    :key="i"
                                    @click="rowClick(page.id, $event)"
                                >
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ page.title }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left">
                                        <div class="flex items-center">
                                            <span>
                                                {{ truncateSummary(page.summary) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs" v-if="page.published">Publiée</span>
                                        <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs" v-else>Brouillon</span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center">

                                            <Link :href="route('pages.show', {slug: page.slug})">
                                                <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </div>
                                            </Link>

                                            <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110 cursor-pointer" @click="deletePage(page)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </div>
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

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/inertia-vue3';
import Swal from "sweetalert2";
import {onMounted, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    pages: {
        type: Array,
        required: true,
    },
    flash: {
        type: Object,
        required: false,
    }
})

const pageList = ref([])
const filter = ref('')
const sort = ref('')

const sortPages = (method) => {
    if (method === 'asc') {
        pageList.value.sort(((a, b) => a['title'] > b['title'] ? 1 : -1))
        localStorage.setItem('page_sort', 'asc');
        sort.value = 'asc'
    }
    else {
        pageList.value.sort(((a, b) => a['title'] < b['title'] ? 1 : -1))
        localStorage.setItem('page_sort', 'desc');
        sort.value = 'desc'
    }
}

const deletePage = (page) => {
    Swal.fire({
        icon: 'warning',
        title: "Supprimer la page ?",
        text: `La page intitulée "${page.title}" va être supprimée, et cette action est irréversible. Confirmer ?`,
        showCancelButton: true,
        cancelButtonText: 'Annuler',
        confirmButtonText: 'Supprimer',
        confirmButtonColor: '#DC2626'
    })
        .then((result) => {
            if (result.isConfirmed) {
                axios.delete(route('pages.destroy', {page: page.id}))
                    .then(() => {
                        pageList.value = pageList.value.filter(p => p.id !== page.id)
                        Swal.fire({
                            icon: 'success',
                            title: 'Page supprimée.',
                            text: 'La page a bien été supprimée'
                        })
                    })
            }
        })
}

const truncateSummary = (summary) => {
    const defaultLength = 70;
    if (summary.length > defaultLength) {
        return summary.slice(0, (defaultLength - 3)) + '...';
    } else {
        return summary;
    }
}

onMounted(() => {
    pageList.value = props.pages
    const sort = localStorage.getItem('page_sort');
    if (sort) {
        sortPages(sort);
    }
})

const rowClick = (id, e) => {
    if (!['svg', 'path'].includes(e.target.tagName)) {
        Inertia.visit(route('pages.edit', {id: id}))
    }
}

</script>
