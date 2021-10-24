<template>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
        <div>
            <jet-application-logo class="block h-12 w-auto" />
        </div>

        <div class="mt-8 font-semibold text-2xl text-gray-800 leading-tight">
            {{ headlines.title }}
        </div>

        <div class="mt-6 text-gray-500">
            {{ headlines.subtitle }}
        </div>
    </div>

    <section class="text-gray-600 body-font">
        <div class="container px-5 py-12 mx-auto">
            <div class="p-4 w-full">
                <div class="flex border-2 mb-4 rounded-lg border-gray-200 border-opacity-50 p-8 sm:flex-row flex-col">
                    <div class="flex-1 pr-2">
                        <h2 class="text-gray-900 text-lg title-font font-medium mb-3">{{ subscription.lesson.title }}</h2>
                        <p class="leading-relaxed text-base">
                            {{ subscription.lesson.description }}
                        </p>
                        <p class="leading-relaxed text-base">
                            <b>Horaire(s) choisi(s) :&nbsp;</b>
                            <span>
                                {{ subscription.selected_time }}
                            </span>
                            <span v-if="subscription.fallback_time"> | {{ subscription.fallback_time }}</span>
                        </p>
                    </div>
                    <div class="flex items-center">
                        <Link :href="route('subscription.edit', {lesson: subscription.lesson.id})" class="btn btn-primary">
                            Modifier les informations
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout.vue'
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        UserLayout,
        Link
    },

    props: ['subscription', 'headlines'],

    setup (props) {

    }
}
</script>
