<template>
    <admin-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Utilisateurs inscrits pour "{{ lesson.title }}" ({{ users.length }} personnes)
            </h1>
        </template>

        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="mb-5">
                        <a :href="route('cours.edit', {id: lesson.id})" class="btn btn-primary">
                            Retour
                        </a>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <table class="min-w-max w-full table-auto">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-6 text-left">
                                            Nom
                                        </th>
                                        <th class="py-3 px-6 text-center">
                                            Paiements
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                    <tr
                                        v-for="(user, i) in users"
                                        :key="i"
                                        class="border-b border-gray-200 hover:bg-gray-100"
                                    >
                                        <td class="py-3 px-6 text-left whitespace-nowrap">
                                            {{ user.full_name }}
                                        </td>

                                        <td>
                                            <div class="flex">
                                                <div class="w-1/2 border-r" v-if="user.current_year_data.payments.length">
                                                    <ul >
                                                        <li v-for="(p, i) in user.current_year_data.payments" :key="i">
                                                            - {{ p.amount }} € reçu par {{ p.method }} le {{ humanDate(p.paid_at) }}
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div v-else class="w-1/2 border-r">
                                                    <p>Aucun paiement reçu pour l'instant</p>
                                                </div>

                                                <div class="flex items-center pl-2 w-1/2">

                                                    <p v-if="user.current_year_data.total">
                                                        <b>Total : </b>
                                                        <span>
                                                            {{ getTotal(user.current_year_data.payments) }} / {{ user.current_year_data.total }} €
                                                        </span>
                                                    </p>
                                                    <p v-else>Montant à définir</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-for="i in missingRows" :key="i" class="border-b border-gray-200 hover:bg-gray-100">
                                        <td colspan="2" class="py-3 px-6">&zwnj;</td>
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
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {computed} from "vue";
import dayjs from "dayjs";

const props = defineProps(['lesson', 'users'])

const missingRows = computed(() => {
    return props.users.length < 10
    ? 10 - props.users.length
    : 0
})

const humanDate = (date) => {
    return dayjs(date).format('DD/MM/YYYY')
}

const getTotal = (payments) => {
    let total = 0;
    payments.forEach((p) => total += parseInt(p.amount))

    return total;
}
</script>
