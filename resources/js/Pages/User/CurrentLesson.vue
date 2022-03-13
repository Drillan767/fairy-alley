<template>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
        <div class="mt-8 font-semibold text-2xl text-gray-800 leading-tight">
            {{ headlines.title }}
        </div>

        <div class="mt-6 text-gray-500">
            {{ headlines.subtitle }}
        </div>
    </div>

    <section class="text-gray-600 body-font">
        <div class="container px-5 py-12 mx-auto">
            <div class="flex flex-col md:flex-row">
                <div>
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-3">{{ lesson.title }}</h2>
                    <p class="leading-relaxed text-base">
                        {{ lesson.description }}
                    </p>
                </div>

            </div>


            <div class="calendar mt-5">
                <calendar
                    :attributes="attributes"
                    :columns="breakpoint"
                    is-expanded
                    @dayclick="onDayClick"
                />
            </div>
        </div>
    </section>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout.vue'
import { Link } from '@inertiajs/inertia-vue3';
import { Calendar } from 'v-calendar';
import 'v-calendar/dist/style.css';
import Swal from "sweetalert2";

import {computed, ref} from "vue";

export default {
    props: ['lesson', 'headlines', 'attributes'],
    components: {
        UserLayout,
        Link,
        Calendar,
    },

    setup (props) {
        const onDayClick = (day) => {
            Swal.fire({
                title: day.ariaLabel
            })
            console.log(day)
        };

        const breakpoint = computed(() => {
            const width = window.innerWidth
            if (width >= 1200) return 4
            if (width >= 810) return 2
            return 1;
        })

        return {
            onDayClick,
            breakpoint,
        }
    }
}
</script>
