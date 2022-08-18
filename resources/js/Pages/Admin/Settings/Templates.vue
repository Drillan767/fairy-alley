<template>
    <admin-layout title="Template des emails">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Template des emails
            </h1>
        </template>

        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert" v-if="flash.success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="ml-5">{{ flash.success }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white">
                            <form @submit.prevent="submit">
                                <div>
                                    <h2 class="font-semibold text-l text-gray-800 leading-tight">Paiement incomplet ou manquant</h2>

                                    <div class="mt-4">
                                        <wysiwyg v-model="form.unpaid" :tiny="tiny" />
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h2 class="font-semibold text-l text-gray-800 leading-tight">Utilisateur non réinscrit</h2>

                                    <div class="mt-4">
                                        <wysiwyg v-model="form.unsubscribed" :tiny="tiny" />
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-end">
                                    <jet-button>Enregistrer</jet-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Wysiwyg from '@/Jetstream/Wysiwyg.vue';
import JetButton from '@/Jetstream/Button.vue';
import {useForm, usePage} from "@inertiajs/inertia-vue3";

const tiny = usePage().props.value.tiny

const props = defineProps({
    templates: Array,
    flash: {
        type: Object,
        required: false
    },
})

const form = useForm({
    unsubscribed: props.templates.unsubscribed,
    unpaid: props.templates.unpaid,
})

const submit = () => form.post(route('settings.template.post'))
</script>
