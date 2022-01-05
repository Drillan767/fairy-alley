<template>
    <admin-layout title="Pages">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Éditer "{{ lesson.title }}"
            </h1>
        </template>

        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="mb-5">
                        <a :href="route('cours.index')" class="btn btn-primary">
                            Retour
                        </a>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <Form :editing="true" :lesson="lesson" :tiny="tiny" :holidays="holidays" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import Form from "./Form.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    title () {
        return `Éditer "${this.lesson.title}"`
    },
    props: {
        lesson: {
            type: Object,
            required: false
        },
        holidays: Array,
        editing: Boolean,
        tiny: String,
    },
    components: {
        Form,
        AdminLayout,
    },
}
</script>
