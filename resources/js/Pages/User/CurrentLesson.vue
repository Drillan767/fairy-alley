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
                                <span :class="canSubscribe(attr, day)">
                                    {{ attr.customData.lesson_title }}
                                </span>

                            </popover-row>
<!--                            <p class="mt-2 font-bold text-center" :class="[canSubscribe(attributes, day) ? 'text-green-600' : 'text-red-600']">
                                {{ canSubscribe(attributes, day) ? 'Inscription possible': 'Inscription impossible' }}
                            </p>-->
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
import { computed, ref, onMounted, toRaw } from "vue";
import Swal from "sweetalert2";
import axios from 'axios'
import dayjs from "dayjs";

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

        onMounted(() => {
            attributes.value = props.lessonDays
        })

        /**
         * Will return false IF
         * - 6 people joined that lesson that day
         * - 6 people are waiting to be accepted in that lesson that day.
         *
         * @param attribute
         * @param day
         * @returns {string}
         */
        const canSubscribe = (attribute, day) => {
            const errorClass = 'text-red-600';
            const pendingClass = 'text-yellow-600';
            const successClass = 'text-green-600';

            const selectedDay = dayjs(toRaw(day.date));
            const customData = toRaw(attribute.customData);

            if (customData.isSubscribed) {
                return `${errorClass} font-bold`;
            } else if (customData.cancelled) {
                return errorClass;
            }

            const movements = customData.movements;
            if (movements.length) {
                // We filter by date selected and action being 'joined'
                const todaysMovements = movements.filter((m) => {
                    return dayjs(m.lesson_time).isSame(selectedDay, 'day') && m.action === 'joined'
                })

                if (todaysMovements.length >= 6) return errorClass;
            }

            const queues = toRaw(attribute.customData.queues);
            const todaysQueue = queues.find((q) => dayjs(q.datetime).isSame(selectedDay, 'day'))
            if (todaysQueue) {
                const { joining, leaving } = todaysQueue;

                if (joining.length === 6) return errorClass;
                if (joining.length === 0 && leaving.length > 0) return successClass
            }

            return pendingClass;
        }

        const groupBy = (xs, key) => {
            return xs.reduce(function(rv, x) {
                (rv[x[key]] = rv[x[key]] || []).push(x);
                return rv;
            }, {});
        };

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
            canSubscribe,
            attributes,
        }
    }
}
</script>

<style scoped>
.initial {
    @apply italic text-gray-400
}
</style>
