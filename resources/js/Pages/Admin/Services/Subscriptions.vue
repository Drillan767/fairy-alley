<template>
    <admin-layout title="Souscriptions">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Souscriptions
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
                            <table class="table table-striped w-full">
                                <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Nom</th>
                                    <th class="py-3 px-6 text-left">Service</th>
                                    <th class="py-3 px-6 text-left">Approuvé</th>
                                    <th class="py-3 px-6 text-left">Date de fin</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(subscription, i) in subscriptions" :key="i">
                                    <td>{{ subscription.user.full_name }}</td>
                                    <td>{{ subscription.service.title }}</td>
                                    <td>
                                        <svg v-if="subscription.accepted" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <svg v-if="!subscription.accepted" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </td>
                                    <td>{{ subscription.expires_at !== null ? dayjs(subscription.expires_at).format('DD/MM/YYYY') : 'N/A' }}</td>
                                    <td>
                                        <div class="cursor-pointer flex justify-end" @click="editSubscription(subscription)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
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

        <Form :show="showModal" @close="closeModal" :subscription="selectedSubscription" />
    </admin-layout>
</template>

<script setup>
import dayjs from 'dayjs'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Form from './EditSubscription.vue';
import { ref } from 'vue';

const selectedSubscription = ref(null);
const showModal = ref(false);

const props = defineProps({
    subscriptions: Array,
    flash: {
        type: Object,
        required: false
    }
})

const editSubscription = (subscription) => {
    console.log('oué')
    selectedSubscription.value = subscription;
    showModal.value = true;
}

const closeModal = () => {
    showModal.value = false;
    Swal.fire({
        icon: 'success',
        toast: true,
        title: 'Souscription mise à jour',
        text: 'La souscription au service a bien été mise à jour.',
        timerProgressBar: true,
        showConfirmButton: false,
        timer: 2000,
    })
}

</script>
