<template>
    <admin-layout title="Utilisateurs en cours d'inscription">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Services
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
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <form @submit.prevent="submit">
                                <div class="my-4">
                                    <jet-file-upload @input="handleUpload" />
                                    <jet-input-error :message="form.errors.file" class="mt-2" />
                                </div>
                                <div class="flex justify-end">
                                    <jet-button type="submit">
                                        Envoyer
                                    </jet-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import {useForm} from "@inertiajs/inertia-vue3";
import JetFileUpload from '@/Jetstream/FileUpload.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetButton from '@/Jetstream/Button.vue';
import AdminLayout from "@/Layouts/AdminLayout.vue";
export default {
    components: {
        AdminLayout,
        JetFileUpload,
        JetInputError,
        JetButton
    },

    props: {
        flash: {
            type: Object,
            required: false,
        }
    },

    setup() {
        const form = useForm({
            file: null,
        });

        const handleUpload = (file) => {
            form.file = file
        };

        function submit () {
            form.post(route('import.store'));
        }

        return {
            form,
            handleUpload,
            submit,
        }
    }
}
</script>
