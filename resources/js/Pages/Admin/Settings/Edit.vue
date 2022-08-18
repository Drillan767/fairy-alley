<template>
    <admin-layout title="Paramètres du site">
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
                           :price_full="settings.price_full"
                           :price_quarterly="settings.price_quarterly"
                           :which_year="settings.which_year"
                           :tos="settings.tos"
                       />

                        <CustomHolidays :holidays="settings.holidays" />
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SubscriptionRenewalForm from "./SubscriptionRenewalForm.vue";
import CustomHolidays from "./CustomHolidaysForm.vue";

const props = defineProps(['settings'])

</script>

