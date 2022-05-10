<template>
    <admin-layout title="Utilisateurs en cours d'inscription">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Renouveler votre abonnement
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-col md:flex-row">
                        <section class="text-gray-600 body-font w-full md:w-1/2">
                            <div class="container p-6 sm:px-20 mx-auto">
                                <div class="flex flex-col md:flex-row gap-2">
                                    <div class="mt-4" v-if="user.resubscription_status === null">
                                        <jet-label value="Sélectionnez le cours que vous souhaitez rejoindre l'année prochaine" />
                                        <jet-select :choices="lessons" v-model="form.lesson_id" />
                                        <jet-input-error :message="form.errors.lesson_id" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import JetInput from '@/Jetstream/Input.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import AdminLayout from "@/Layouts/AdminLayout.vue";
import JetSelect from '@/Jetstream/Select.vue';
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    title: 'Renouveler votre abonnement',
    props: ['lessons', 'user'],

    components: {
        AdminLayout,
        JetInput,
        JetLabel,
        JetInputError,
        JetButton,
        JetSelect,
    },

    setup (props) {
        const form = useForm({
            lesson_id: null,
        });
        console.log(props.user)

        return {
            form,
        }
    }
}
</script>
