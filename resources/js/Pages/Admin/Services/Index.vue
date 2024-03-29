<template>
    <admin-layout title="Services">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Services
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
                    <div class="flex justify-end mb-5 gap-x-5">
                        <Link :href="route('services.subscriptions.index')" class="btn">
                            Souscriptions
                        </Link>
                        <span class="btn btn-primary" @click="showModal = true">
                            Nouveau service
                        </span>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <div v-if="orderChanged" class="flex items-center justify-between bg-blue-800 text-white text-sm font-bold px-4 py-3 mb-5">
                                <p>Sauvegarder l'ordre des services ?</p>
                                <button @click="updateServiceOrder">Enregistrer</button>
                            </div>
                            <table class="table table-striped w-full">
                                <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Titre</th>
                                    <th class="py-3 px-6 text-left">Référence</th>
                                    <th class="py-3 px-6 text-left">Page liée</th>
                                    <th class="py-3 px-6 text-center">Page d'accueil</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                                </thead>
                                <draggable v-model="servicesList" tag="tbody" item-key="id" @end="log">
                                    <template #item="{ element }">
                                        <tr class="cursor-move" @click="editService(element, $event)">
                                            <td>{{ element.title }}</td>
                                            <td>
                                                {{ element.ref }}
                                            </td>
                                            <td>
                                                <a :href="route('pages.show', {slug: element.page.slug})">
                                                    {{ element.page.title }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                {{ element.homepage ? 'Oui' : 'Non' }}
                                            </td>
                                            <td>
                                                <div class="flex justify-center">
                                                    <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110 cursor-pointer" @click="deleteService(element)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </draggable>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Form :show="showModal" :service="selectedService" @close="closeModal" :pages="pages"/>
    </admin-layout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Form from "./Form.vue";
import draggable from "vuedraggable";
import Swal from "sweetalert2";
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import { ref } from '@vue/reactivity';
import { onMounted } from '@vue/runtime-core';
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    services: Array,
        pages: Array,
        flash: {
            type: Object,
            required: false
        }
})

const orderChanged = ref(false)
const currentServiceOrder = ref([])
const servicesList = ref([])
const showModal = ref(false)
const selectedService = ref(null)
const dragging = ref(false)

onMounted(() => {
    servicesList.value = props.services
})

const addService = () => {
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
}

const log = () => {
    orderChanged.value = true
    currentOrder.value = servicesList.value.map((s) => {
        return {id: s.id, order: s.order}
    })
}

const updateServiceOrder = () => {
    axios.post(route('services.order'), currentOrder.value)
        .then(() => {
            orderChanged.value = false
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Ordre enregistré',
                showConfirmButton: false,
                timer: 1500
            })
        });
}

const editService = (service, e) => {
    if (!['svg', 'path'].includes(e.target.tagName)) {
        selectedService.value = service
        selectedService.value.file = selectedService.value.banner
        showModal.value = true
    }
}

const deleteService = (service) => {
    Swal.fire({
        icon: 'warning',
        title: 'Supprimer le service ?',
        text: `Le service intitulé "${service.title}" va être supprimé,`,
        showCancelButton: true,
        cancelButtonText: 'Annuler',
        confirmButtonText: 'Supprimer',
        confirmButtonColor: '#DC2626',
    })
    .then((result) => {
        if (result.isConfirmed) {
            axios.delete(route('services.destroy', {service: service.id}))
            .then(() => {
                servicesList.value = servicesList.value.filter((s) => s.id !== service.id)
                Swal.fire({
                    icon: 'success',
                    toast: true,
                    title: 'Service supprimé.',
                    text: 'Le service a bien été supprimée',
                    timerProgressBar: true,
                    showConfirmButton: false,
                    timer: 2000,
                })
            })
        }
    })
}

</script>
