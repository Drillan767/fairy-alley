<template>
    <div class="md:p-5 md:px-20 bg-white border-b border-gray-200">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Services disponibles :</h2>

        <table class="w-full">
            <tbody class="border-b">
            <tr v-for="(service, i) in services" class="border-b" :key="i">
                <td class="text-sm text-gray-900 font-light px-3 py-2 whitespace-nowrap">{{ service.title }}</td>
                <td class="flex justify-end pr-4">
                    <button class="btn btn-sm" @click="subscribe(service)">Inscription</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</template>

<script setup>

import Swal from "sweetalert2";
const props = defineProps({
    services: Array,
})

const subscribe = (service) => {
    Swal.fire({
        icon: 'question',
        title: "Confirmer l'inscription ?",
        text: `Souhaitez-vous vous inscrire au service "${service.title}" ? Vous recevrez
        un email vous indiquant si oui ou non votre inscription a été retenue.`,
        confirmButtonText: 'Inscription'
    })
}
</script>
