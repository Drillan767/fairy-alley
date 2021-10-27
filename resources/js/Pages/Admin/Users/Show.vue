<template>
    <admin-layout title="Pages">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ user.full_name }}
            </h1>
        </template>

        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <form @submit.prevent="submit">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                <h2 class="font-semibold text-l text-gray-700 leading-tight mb-5">
                                    Informations sur l'utilisateur
                                </h2>
                                <div>
                                    <jet-label for="firstname" value="Prénom" />
                                    <jet-input id="firstname" type="text" class="mt-1 block w-full" v-model="form.firstname" />
                                    <jet-input-error :message="form.errors.firstname" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="lastname" value="Nom de famille" />
                                    <jet-input id="lastname" type="text" class="mt-1 block w-full" v-model="form.lastname" />
                                    <jet-input-error :message="form.errors.lastname" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="email" value="Adresse email" />
                                    <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                                    <jet-input-error :message="form.errors.email" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label value="Genre" />
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio" name="accountType" value="M" v-model="form.gender" :checked="form.gender === 'M'">
                                        <span class="ml-2">Homme</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="radio" class="form-radio" name="accountType" value="F" v-model="form.gender" :checked="form.gender === 'F'">
                                        <span class="ml-2">Femme</span>
                                    </label>
                                    <jet-input-error :message="form.errors.gender" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="birthday" value="Date de naissance" />
                                    <jet-input id="birthday" type="date" class="mt-1 block w-full" v-model="form.birthday" />
                                    <jet-input-error :message="form.errors.birthday" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="phone" value="Numéro de téléphone" />
                                    <jet-input id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" />
                                    <jet-input-error :message="form.errors.phone" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="pro" value="Téléphone professionnel" />
                                    <jet-input id="pro" type="text" class="mt-1 block w-full" v-model="form.pro" />
                                    <jet-input-error :message="form.errors.pro" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="address1" value="Adresse" />
                                    <jet-input id="address1" type="text" class="mt-1 block w-full" v-model="form.address1" />
                                    <jet-input-error :message="form.errors.address1" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="address2" value="Complément d'adresse" />
                                    <jet-input id="address2" type="text" class="mt-1 block w-full" v-model="form.address2" />
                                    <jet-input-error :message="form.errors.address2" class="mt-2" />
                                </div>

                                <div class="mt-4 flex justify-between">
                                    <div class="w-1/4">
                                        <jet-label for="zipcode" value="Code postal" />
                                        <jet-input id="zipcode" type="text" class="mt-1 block w-full" v-model="form.zipcode" />
                                        <jet-input-error :message="form.errors.zipcode" class="mt-2" />
                                    </div>

                                    <div class="flex-1 ml-4">
                                        <jet-label for="city" value="Ville" />
                                        <jet-input id="city" type="text" class="mt-1 block w-full" v-model="form.city" />
                                        <jet-input-error :message="form.errors.city" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="max-w-7xl mx-auto mt-5 sm:px-6 lg:px-8 mb-5" v-if="user.lesson">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                <h2 class="font-semibold text-l text-gray-700 leading-tight mb-5">
                                    Information sur le cours choisi
                                </h2>
                                <a class="btn btn-sm" :href="route('cours.edit', {cour: user.lesson.id})" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                    {{ user.lesson.title }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-5 mt-4 flex justify-end">
                        <jet-button type="submit">
                            Enregistrer
                        </jet-button>
                    </div>
                </form>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import { useForm } from "@inertiajs/inertia-vue3";

export default {
    props: ['user'],
    components: {
        AdminLayout,
        JetButton,
        JetInput,
        JetLabel,
        JetInputError
    },

    setup (props) {
        const form = useForm({
            ...props.user
        })

        return {
            form,
        }
    }
}
</script>
