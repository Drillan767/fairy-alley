<template>
    <admin-layout title="Pages">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Éditer "{{ page.title }}"
            </h1>
        </template>

        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="mb-5">
                        <a :href="route('pages.index')" class="btn btn-primary">
                            Retour
                        </a>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <Form :editing="true" :page="page" :tiny="tiny" />
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
    props: {
        page: {
            type: Object,
            required: false
        },
        editing: Boolean,
        tiny: String,
    },
    components: {
        Form,
        AdminLayout,
    },
}
</script>
