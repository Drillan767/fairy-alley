<template>
    <div class="md:p-5 md:px-20 bg-white border-b border-gray-200">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Services disponibles :</h2>

        <table class="w-full">
            <tbody class="border-b">
            <tr v-for="(service, i) in services" class="border-b" :key="i">
                <td class="tdClass">
                    {{ service.title }}
                </td>
                <td class="tdClass flex justify-end pr-4">
                    <template v-if="subscriptionStatus(service) === 'unsubscribed'">
                        <button class="btn btn-sm" @click="subscribe(service)">Inscription</button>
                    </template>
                    <template v-if="subscriptionStatus(service) === 'pending'">
                        <span class="text-orange-500">En attente</span>
                    </template>
                    <template v-if="subscriptionStatus(service) === 'subscribed'">
                        <span class="text-green-500">Expire le {{ getExpirationDate(service) }}</span>
                    </template>


                </td>
            </tr>
            </tbody>
        </table>
    </div>

</template>

<script setup>

import Swal from "sweetalert2";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";
import dayjs from "dayjs";

const props = defineProps({
    services: Array,
})

const user = usePage().props.value.user

const subscriptionStatus = (service) => {
    const found = service.subscriptions.find((s) => s.user_id === user.id)

    if (found) {
        return found.accepted ? 'subscribed' : 'pending'
    } else {
        return 'unsubscribed'
    }
}

const getExpirationDate = (service) => {
    const found = service.subscriptions.find((s) => s.user_id === user.id)
    return dayjs(found.expires_at).format('DD/MM/YYYY')
}

const subscribe = (service) => {
    Swal.fire({
        icon: 'question',
        title: "Confirmer l'inscription ?",
        text: `Souhaitez-vous vous inscrire au service "${service.title}" ? Vous recevrez
        un email vous indiquant si oui ou non votre inscription a été retenue.`,
        confirmButtonText: 'Inscription',
        showCancelButton: true,
        cancelButtonText: 'Annuler',
    })
        .then((decision) => {
            if (decision.isConfirmed) {
                Inertia.post(route('service.ask'), {service: service.id})
            }
        })
}
</script>

<style scoped>
.tdClass {
    @apply text-sm text-gray-900 font-light px-3 text-lg py-2 whitespace-nowrap;
}
</style>
