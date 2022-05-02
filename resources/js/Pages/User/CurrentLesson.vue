<template>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
        <div class="mt-8 font-semibold text-2xl text-gray-800 leading-tight">
            {{ headlines.title }}
        </div>

        <div class="mt-6 text-gray-500">
            {{ headlines.subtitle }}
        </div>
    </div>

    <div class="flex flex-col md:flex-row">
        <section class="text-gray-600 body-font w-full md:w-1/2">
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
                                    :attribute="attr"
                                >
                                    <span class="text-base" :class="canSubscribe(attr, day)">
                                        {{ attr.customData.lesson_title }}
                                    </span>
                                </popover-row>

                                <div v-html="recapSubscribe(attributes, day)"></div>

                            </div>
                        </template>
                    </calendar>
                    <div class="mt-4 text-gray-800">
                        <div v-for="(legend, i) in legends" class="legend" :key="i">
                            <div :class="legend.classes"></div>
                            <p>{{ legend.text }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="w-full md:w-1/2">
            <div class="container p-6 sm:px-20 mx-auto">
                <div class="flex-1">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-3">Vos prochains cours</h2>
                    <p class="leading-relaxed text-base">
                        Vous pouvez remplacer <span class="font-bold" :class="[availableReplacements > 0 ? 'text-green-600 ': 'text-red-600 ']">
                        {{ availableReplacements }}
                    </span> cours.
                    </p>
                </div>

                <div class="mt-5">
                    <div
                        v-for="(lesson, i) in nextLessons"
                        class="bg-white p-3 rounded-lg border mb-4"
                        :class="{
                            'border-green-400': lesson.action === 'join',
                            'border-red-400': lesson.action === 'leave'
                        }"
                        :key="i"
                    >
                        <div class="leading-relaxed text-base">
                            <span class="text-lg font-bold text-gray-800">{{ lesson.title }}</span>&nbsp;
                            <span class="inline-block md:inline leading-relaxed text-base">Le {{ lesson.time }}</span>
                            <div class="tag error" v-if="lesson.action === 'leave'">Annulé</div>
                            <div class="tag success" v-if="lesson.action === 'join'">Rejoint</div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>

</template>

<script>
import UserLayout from '@/Layouts/UserLayout.vue'
import {Link, usePage} from '@inertiajs/inertia-vue3';
import { Calendar, PopoverRow } from 'v-calendar';
import 'v-calendar/dist/style.css';
import { computed, ref, onMounted, toRaw } from "vue";
import Swal from "sweetalert2";
import registerLesson from "../../Modules/registerLesson.js";
import dayjs from "dayjs";
import {Inertia} from "@inertiajs/inertia";

export default {
    props: ['lesson', 'headlines', 'lessonDays', 'nextLessons'],
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
        const successClass = 'text-green-600';
        const page = usePage()

        const legends = ref([
            {
                classes: 'circle blue',
                text: 'Cours de gym normal.',
            },
            {
                classes: 'circle blue empty',
                text: 'Cours de gym annulé.',
            },
            {
                classes: 'circle blue orange border',
                text: 'Cours de gym disponible'
            },
            {
                classes: 'circle gray',
                text: 'Jour où des cours sont disponibles',
            },
            {
                classes: 'circle green',
                text: 'Ateliers',
            },
            {
                classes: 'circle orange',
                text: 'Conférences',
            },
            {
                classes: 'circle pink',
                text: 'circle pink'
            }
        ])

        onMounted(() => {
            attributes.value = props.lessonDays
        })

        const availableReplacements = computed(() => {
            return page.props.value.user.available_replacements;
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
            const { movements } = customData

            let availableSlots = 0
            movements.map((m) => {
                if (dayjs(m.lesson_time).isSame(selectedDay, 'day')) {
                    if (m.action === 'leave') availableSlots++;
                    if (m.action === 'join') availableSlots--;
                }
            })

            if (availableSlots > 0) {
                return successClass
            } else {
                let classes = errorClass
                if (customData.isSubscribed) {
                    classes += 'font-bold'
                }
                return classes
            }

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

            if (classes.includes(errorClass)) {
                return `<p class="${errorClass} ${defaultClasses}">Aucune place disponible pour le moment</p>`
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

                const result = await registerLesson(lessons, day, toRaw(availableReplacements))

                if (result) {
                    let payload = {};
                    result.forEach((r) => payload[r.key] = r.value)

                    Inertia.post(route('lesson-movement'), payload, {
                        resetOnSuccess: true,
                        onSuccess: () => attributes.value = props.lessonDays
                    });
                }
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
            availableReplacements,
        }
    }
}
</script>

<style lang="scss" scoped>
.tag {
    @apply inline-block text-white px-1 py-0.5 rounded ml-1;

    &.success {
        @apply bg-green-400;
    }

    &.error {
        @apply bg-red-400;
    }
}
.legend {
    display: grid;
    grid-template-columns: 20px auto;
    gap: 5px;

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
}

</style>
