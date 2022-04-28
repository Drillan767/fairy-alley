<template>
    <admin-layout title="Dashboard">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Administration
            </h1>
        </template>

        <div class="py-12">
            <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert" v-if="flash.success">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p class="ml-5">{{ flash.success }}</p>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <FullCalendar ref="fullCalendar" :options="calendarOptions">
<!--                        <template v-slot:eventContent='arg' v-if="arg.view.type === 'timeGridWeek'">-->
<!--                            <p>{{ arg.event.title }}</p>-->
<!--                        </template>-->
                    </FullCalendar>
                </div>
            </div>
        </div>
        <Detail :show="showModal" @close="closeModal" :details="details" :hour="lessonHour" />
    </admin-layout>
</template>

<script>
import '@fullcalendar/core/vdom';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import frLocale from '@fullcalendar/core/locales/fr';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Detail from "./LessonDetail.vue";
import { ref } from "vue";
import axios from 'axios'
import dayjs from "dayjs";
import utc from 'dayjs/plugin/utc';

export default {
    title: 'Administration',
    components: {
        AdminLayout,
        FullCalendar,
        Detail,
    },
    props: {
        flash: {
            type: Object,
            required: false
        }
    },

    setup() {
        const showModal = ref(false)
        const details = ref(null)
        const lessonHour = ref(null)
        const fullCalendar = ref(null)

        dayjs.extend(utc)

        const calendarOptions = {
            plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
            initialView: 'timeGridWeek',
            locale: frLocale,
            headerToolbar: {
                left: 'prev,next today',
                slotMaxTime: '23:00:00',
                slotMinTime: '08:00:00',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            eventClick: (info) => {
                const hour = dayjs(info.event.start)
                const lessonProps = info.event.extendedProps;

                axios.post(route('lesson.details', lessonProps))
                    .then((response) => {
                        lessonHour.value = hour
                        details.value = response.data
                        details.value.lesson_id = lessonProps.lesson_id
                        details.value.status = lessonProps.status
                        showModal.value = true
                    })
            },
            events: route('admin.lesson.list')
        }

        const closeModal = () => {
            showModal.value = false
            fullCalendar.value.getApi().refetchEvents()
        };

        return {
            calendarOptions,
            showModal,
            closeModal,
            lessonHour,
            details,
            fullCalendar,
        }
    },
}
</script>
