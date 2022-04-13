<template>
    <user-layout title="Dashboard">
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
                    <CurrentLesson :lesson="user.lesson" :headlines="headlines" :lesson-days="lessonDays" :next-lessons="nextLessons" />
                    <ServicesSuggestion :suggestions="user.suggestions" v-if="user.suggestions.length" />
                </div>
            </div>
        </div>
    </user-layout>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout.vue'
import Welcome from '@/Jetstream/Welcome.vue'
import CurrentLesson from "./CurrentLesson.vue";
import ServicesSuggestion from "./ServicesSuggestion.vue";

export default {
    title: 'Profil',
    props: {
        user: Object,
        lessonDays: Object,
        headlines: Object,
        nextLessons: Array,
        flash: {
            type: Object,
            required: false,
        }
    },

    components: {
        ServicesSuggestion,
        UserLayout,
        Welcome,
        CurrentLesson,
    },
}
</script>
