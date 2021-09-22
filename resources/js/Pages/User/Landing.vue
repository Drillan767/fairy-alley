<template>
    <user-layout title="Dashboard">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Accueil
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <LessonList v-if="!subscribed" :data="data"/>
                </div>
            </div>
        </div>
    </user-layout>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout.vue'
import Welcome from '@/Jetstream/Welcome.vue'
import LessonList from "@/Pages/User/LessonList.vue";
import CurrentLesson from "@/Pages/User/CurrentLesson.vue";

export default {
    props: {
        subscribed: Boolean,
        data: Object|Array,
    },

    components: {
        UserLayout,
        Welcome,
        LessonList,
        CurrentLesson,
    },
}
</script>
