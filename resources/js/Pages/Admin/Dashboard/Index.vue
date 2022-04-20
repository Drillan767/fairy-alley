<template>
    <admin-layout title="Dashboard">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Administration
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <FullCalendar :options="calendarOptions" />
                </div>
            </div>
        </div>
        <Detail :show="showModal" @close="closeModal" :details="details" />
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

export default {
    title: 'Administration',
    components: {
        AdminLayout,
        FullCalendar,
        Detail,
    },

    setup() {
        const showModal = ref(false)
        const details = ref(null)

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
                        details.value = response.data
                        details.value.hour = hour.format('DD/MM/YYYY')
                        details.value.lesson_time = hour.toISOString()
                        details.value.lesson_id = lessonProps.lesson_id
                        showModal.value = true
                    })
            },
            events: route('admin.lesson.list')
        }

        const closeModal = () => showModal.value = false;

        return {
            calendarOptions,
            showModal,
            closeModal,
            details,
        }
    },
}
</script>
