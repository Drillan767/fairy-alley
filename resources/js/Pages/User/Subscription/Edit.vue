<template>
    <user-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Inscription au cours "{{ lesson.title }}"
            </h1>
        </template>

        <div class="py-12">
            <div class="overflow-x-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="mb-5">
                        <Link :href="route('profile.index')" class="btn btn-primary">
                            Retour
                        </Link>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <div class="mb-4">
                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Détail des cours
                                </h2>
                                <div class="prose max-w-none" v-html="details.details"></div>
                            </div>

                            <div class="mb-4">
                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Processus de réservation du cours
                                </h2>

                                <div class="prose max-w-none" v-html="details.process"></div>
                            </div>

                            <div class="mb-4">
                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Organisation des cours
                                </h2>

                                <div class="prose max-w-none" v-html="details.organization"></div>
                            </div>

                            <div class="mb-4">
                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Acceptation des conditions
                                </h2>

                                <div class="prose max-w-none" v-html="details.conditions"></div>
                            </div>

                            <Form :editing="true" :lesson="lesson" :lessons="lessons" :user="user" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </user-layout>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import UserLayout from '@/Layouts/UserLayout.vue'
import Form from "./Form.vue";

export default {
    props: ['lesson', 'lessons', 'user', 'details'],
    components: {
        UserLayout,
        Link,
        Form,
    }
}
</script>
