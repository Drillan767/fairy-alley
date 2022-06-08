<template>
    <user-layout title="Accueil">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Accueil
            </h1>
        </template>

        <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert" v-if="flash.success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <p class="ml-5">{{ flash.success }}</p>
        </div>

        <div class="flex items-center bg-red-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert" v-if="flash.error">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="ml-5">{{ flash.error }}</p>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-if="renewalWarning" class="flex p-4 mb-4 text-sm text-yellow-700 bg-yellow-100 p-6 sm:px-20 dark:bg-yellow-200 dark:text-yellow-800" role="alert">
                        <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <div class="flex justify-between w-full">
                            <p>
                                <span class="font-bold">Les réinscriptions commenceront le {{ renewalStart.format('DD/MM/YYYY') }}, dans {{ countDown }} jours.</span>
                                Veuillez vous réinscrire avant le {{ renewalEnd.format('DD/MM/YYYY') }} dernier délai.
                            </p>
                        </div>
                    </div>
                    <div
                        v-if="user.resubscription_status !== null"
                        class="flex p-4 mb-4 text-sm bg-yellow-100 p-6 sm:px-20 flex justify-between"
                        :class="{
                            'text-yellow-700 bg-yellow-100': renewalStatus.color === 'yellow',
                            'text-green-700 bg-green-100': renewalStatus.color === 'green',
                            'text-orange-700 bg-orange-100': renewalStatus.color === 'orange',
                        }"
                    >
                        <p>
                            <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            {{ renewalStatus.title }}
                        </p>
                        <p v-if="renewalStatus.showEdit">
                            <a :href="route('renewal.index')" class="text-yellow-700 bg-transparent border border-yellow-700 hover:bg-yellow-800 hover:text-white focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:border-yellow-800 dark:text-yellow-800 dark:hover:text-white">
                                Modifier votre réinscription
                            </a>
                        </p>
                    </div>
                    <div v-if="displayRenewal" class="flex p-4 mb-4 text-sm text-yellow-700 bg-yellow-100 p-6 sm:px-20 dark:bg-yellow-200 dark:text-yellow-800" role="alert">
                        <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <div class="flex justify-between w-full">
                            <p>
                                <span class="font-bold">Les réinscriptions sont ouvertes depuis maintenant {{  }}</span> Veuillez vous réinscrire avant le {{ renewalEnd.format('DD/MM/YYYY') }} dernier délai.
                            </p>
                            <p>
                                <a :href="route('renewal.index')" class="text-yellow-700 bg-transparent border border-yellow-700 hover:bg-yellow-800 hover:text-white focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:border-yellow-800 dark:text-yellow-800 dark:hover:text-white">
                                    Réinscription
                                </a>
                            </p>
                        </div>
                    </div>
                    <CurrentLesson :lesson="user.lesson" :headlines="headlines" :lesson-days="lessonDays" :next-lessons="nextLessons" />
<!--                    <ServicesSuggestion :suggestions="user.suggestions" v-if="user.suggestions.length" />-->
                    <ServiceList :services="services" />
                </div>
            </div>
        </div>
    </user-layout>
</template>

<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import CurrentLesson from "./CurrentLesson.vue";
import ServiceList from "./ServiceList.vue";
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter';
import isSameOrBefore from 'dayjs/plugin/isSameOrBefore';
import dayjs from "dayjs";
import {computed, ref} from "vue";

const props = defineProps({
    user: Object,
    lessonDays: Object,
    headlines: Object,
    nextLessons: Array,
    renewalStatus: Object,
    services: Array,
    flash: {
        type: Object,
        required: false,
    },
    settings: Object,
})

dayjs.extend(isSameOrAfter)
dayjs.extend(isSameOrBefore)

const renewalStart = ref(dayjs(props.settings.subscription_start ?? null))
const renewalEnd = ref(dayjs(props.settings.subscription_end ?? null))

const renewalWarning = computed(() => {
    if (renewalStart.value.isValid()) {
        const warning = renewalStart.value.subtract(15, 'days')
        return dayjs().isSameOrAfter(warning) && dayjs().isBefore(renewalStart.value)
    } else {
        return  false
    }
})

const displayRenewal = computed(() => {
    if (renewalStart.value.isValid() && renewalEnd.value.isValid()) {
        return props.user.resubscription_status === null
            && dayjs().isSameOrAfter(renewalStart.value)
            && dayjs().isSameOrBefore(renewalEnd.value)
    } else {
        return false
    }
})

const countDown = computed(() => {
    if (renewalStart.value.isValid()) {
        return renewalStart.value.diff(dayjs(), 'day')
    } else {
        return 0
    }
})

</script>
