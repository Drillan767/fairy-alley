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
                            <div class="text-xs text-gray-300 text-base font-semibold text-center">
                                {{ format(day.date, 'WWWW DD MMM YYYY') }}
                            </div>
                            <popover-row
                                v-for="attr in attributes"
                                :key="attr.key"
                                :attribute="attr">
                                <span class="text-base" :class="canSubscribe(attr, day)">
                                    {{ attr.customData.lesson_title }}
                                </span>
                            </popover-row>

                            <div v-html="recapSubscribe(attributes, day)"></div>

                        </div>
                    </template>
                </calendar>
                <div class="mt-4 text-gray-800">
                    <div v-for="(legend, i) in legends" class="flex gap-x-3 items-center" :key="i">
                        <div :class="legend.classes"></div>
                        <p>{{ legend.text }}</p>
                    </div>
                </div>
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
import registerLesson from "../../Modules/registerLesson.js";
import dayjs from "dayjs";
import {Inertia} from "@inertiajs/inertia";

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
        const errorClass = 'text-red-600';
        const pendingClass = 'text-yellow-600';
        const successClass = 'text-green-600';

        const legends = ref([
            {
                classes: 'circle blue',
                text: 'Jour où vous avez un cours de prévu',
            },
            {
                classes: 'circle green',
                text: 'Jour où vous avez annulé un cours pour en prendre un autre',
            },
            {
                classes: 'circle gray',
                text: 'Jour où des cours sont disponibles',
            },
            {
                classes: 'circle red',
                text: 'Cours annulés car férié ou manuellement par l\'administration',
            },
            {
                classes: 'circle red empty',
                text: 'Cours que vous avez vous-même annulés',
            },
        ])

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

            const selectedDay = dayjs(toRaw(day.date));
            const customData = toRaw(attribute.customData);

            if (customData.isSubscribed) {
                return `${errorClass} font-bold`;
            } else if (customData.cancelled) {
                return errorClass;
            }

            const movements = customData.movements;
            if (movements && movements.length) {
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

        const recapSubscribe = (attributes, day) => {
            const classes = [];
            attributes.forEach((attr) => {
                classes.push(canSubscribe(attr, day))
            })

            const defaultClasses = 'font-bold text-base text-center mt-4 mx-2'

            if (classes.includes(successClass)) {
                return `<p class="${successClass} ${defaultClasses}">Places disponibles</p>`
            }

            if (classes.includes(pendingClass)) {
                return `<p class="${pendingClass} ${defaultClasses}">Mise en liste d'attente disponible</p>`
            }

            if (classes.includes(errorClass)) {
                return `<p class="${errorClass} ${defaultClasses}">Aucune place disponible</p>`
            }
        }

        const onDayClick = async (day) => {
            const date = dayjs(day.id);

            if (date.isBefore(dayjs())) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ce cours est déjà passé !',
                    timer: 3000,
                })
            } else {
                const lessons = [];
                day.attributes.forEach((attr) => {
                    if (attr.customData.cancelled === false) {
                        lessons.push({
                            id: attr.customData.lesson_id,
                            title: attr.customData.lesson_title,
                            subscribed: attr.customData.isSubscribed,
                        })
                    }
                })

                const result = await registerLesson(lessons, day)
                if (result) {
                    let payload = {};
                    result.forEach((r) => payload[r.key] = r.value)

                    Inertia.post(route('lesson-movement', payload));
                }


                /*
                await Swal.fire({
                    icon: 'success',
                    title: 'Votre demande a bien été prise en compte',
                    text: 'Votre absence a correctement été enregistrée.'
                })
                 */
            }
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
            recapSubscribe,
            attributes,
            legends,
        }
    }
}
</script>

<style lang="scss" scoped>
.circle {
    height: 20px;
    width: 20px;
    border-radius: 50%;

    &.green { @apply bg-green-600; }
    &.blue { background-color: #3182ce; }
    &.gray { background-color: #718096; }
    &.red {
        background-color: #e53e3e;

        &.empty {
            background-color: white;
            border: solid 2px #e53e3e;
        }
    }
}
</style>
