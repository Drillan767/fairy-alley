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

const props = defineProps({
    flash: {
        type: Object,
        required: false
    },
    users: Array,
    lessons: Array,
    renewals: Object,
})

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
    },
    {
        label: 'Adresse e-mail',
        field: 'email',
    },
    {
        label: 'Choix 1',
        field: 'choice1',
    },
    {
        label: 'Choix 2',
        field: 'choice2',
    },
])

const getLesson = (user_id, choice) => {
    const relatedRenewalInfos = props.renewals[`user_${user_id}`]

    if (relatedRenewalInfos) {
        const lessonId = relatedRenewalInfos.lesson_choices[choice]
        const relatedLesson = props.lessons.find((l) => l.id === parseInt(lessonId))
        return relatedLesson.title
    }
    else {
        return 'N/C';
    }
}

</script>
