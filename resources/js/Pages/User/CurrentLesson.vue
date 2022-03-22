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
        <div class="container p-6 sm:px-20 mx-auto">
            <div class="flex flex-col md:flex-row gap-2">
                <div class="flex-1">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-3">{{ lesson.title }}</h2>
                    <p class="leading-relaxed text-base">
                        {{ lesson.description }}
                    </p>
                </div>
            </div>

            <div class="calendar mt-5">
                <!-- "text-xs text-gray-300 font-semibold text-center" -->
                <calendar
                    :attributes="attributes"
                    :columns="breakpoint"
                    @dayclick="onDayClick"
                >
                    <template #day-popover="{day, format, masks, attributes}">
                        <div>
                            <div class="text-xs text-gray-300 font-semibold text-center">
                                {{ format(day.date, 'WWWW DD MMM YYYY') }}
                            </div>
                            <popover-row
                                v-for="attr in attributes"
                                :key="attr.key"
                                :attribute="attr">
                                <span :class="{'font-bold': attr.customData.isSubscribed}">
                                    {{ attr.customData.lesson_title }}
                                </span>

                            </popover-row>
                            <div class="mt-2 font-bold text-green-600 text-center">
                                Inscription possible
                            </div>
                        </div>
                    </template>
                </calendar>
            </div>
        </div>
    </section>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout.vue'
import { Link } from '@inertiajs/inertia-vue3';
import { Calendar, PopoverRow } from 'v-calendar';
import 'v-calendar/dist/style.css';
import {computed, ref, onMounted} from "vue";
import Swal from "sweetalert2";
import axios from 'axios'

export default {
    // multistep: https://sweetalert2.github.io/recipe-gallery/queue-with-back-button.html
    props: ['lesson', 'headlines', 'lessonDays'],
    components: {
        UserLayout,
        Link,
        Calendar,
        PopoverRow
    },

    setup (props) {
        const thatDaysLessons = ref([]);
        const attributes = ref([]);
        const initial = ref(true);

        onMounted(() => {
            attributes.value = props.lessonDays
        })

        const onDayClick = (day) => {
            /*
            attributes.value = attributes.value.filter((attribute) => !attribute.hasOwnProperty('customData'))

            axios
                .post(route('lesson-detail'), {date: day.id})
                .then((response) => {
                    thatDaysLessons.value = [];
                    initial.value = false
                    response.data.forEach((lesson) => {
                        thatDaysLessons.value.push({
                            title: lesson.title,
                            lid: lesson.id,
                            subtitle: 'Aucune place disponible'
                        })
                    })
                })

            attributes.value.push({
                popover: {
                    label: day.ariaLabel,
                },
                customData: {
                    generated: true,
                },
                dates: day.date,
                highlight: 'orange',
            })
        */
        };

        const breakpoint = computed(() => {
            const width = window.innerWidth
            if (width >= 810) return 2
            return 1;
        })

        return {
            onDayClick,
            breakpoint,
            thatDaysLessons,
            attributes,
            initial,
        }
    }
}
</script>

<style scoped>
.initial {
    @apply italic text-gray-400
}
</style>
