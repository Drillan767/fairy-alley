<template>
    <admin-layout title="Utilisateurs en cours d'inscription">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Paramètres du site
            </h1>
        </template>

        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                       <SubscriptionRenewalForm
                           :start="settings.subscription_start"
                           :end="settings.subscription_end"
                       />
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SubscriptionRenewalForm from "./SubscriptionRenewalForm.vue";

export default {
    title: 'Éditer les paramètres',
    props: ['settings'],
    components: {
        AdminLayout,
        SubscriptionRenewalForm
    },
}
</script>

