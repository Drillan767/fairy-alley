<template>
    <admin-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Créer un nouvel utilisateur
            </h1>
        </template>

        <div class="py-12">
            <div class="overflow-x-auto">
                <form @submit.prevent="submit">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert" v-if="flash.success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <p class="ml-5">{{ flash.success }}</p>
                        </div>
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                <h2 class="font-semibold text-l text-gray-700 leading-tight mb-5">
                                    Informations sur l'utilisateur
                                </h2>

                                <div class="grid grid-cols-6 gap-4">
                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label for="firstname" value="Prénom" class="required"/>
                                        <jet-input id="firstname" type="text" class="mt-1 block w-full" v-model="form.firstname" />
                                        <jet-input-error :message="form.errors.firstname" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label for="lastname" value="Nom de famille" class="required" />
                                        <jet-input id="lastname" type="text" class="mt-1 block w-full" v-model="form.lastname" />
                                        <jet-input-error :message="form.errors.lastname" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label for="email" value="Adresse email" class="required" />
                                        <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                                        <jet-input-error :message="form.errors.email" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <div class="flex gap-x-3">
                                            <div class="flex-1">
                                                <jet-label for="email" value="Date de naissance" />
                                                <jet-input id="birthday" type="date" class="mt-1 block w-full" v-model="form.birthday" />
                                                <jet-input-error :message="form.errors.birthday" class="mt-2" />
                                            </div>
                                            <div class="flex-1">
                                                <jet-label value="Genre" />
                                                <div class="flex mt-2 justify-around">
                                                    <div class="form-check">
                                                        <label class="form-check-label inline-block text-gray-800">
                                                            <input type="radio" name="gender" value="H" v-model="form.gender">
                                                            Homme
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <label class="form-check-label inline-block text-gray-800">
                                                            <input type="radio" name="gender" value="F" v-model="form.gender">
                                                            Femme
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label for="phone" value="Numéro de téléphone" />
                                        <jet-input id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" />
                                        <jet-input-error :message="form.errors.phone" class="mt-2" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label for="pro" value="Téléphone professionnel" />
                                        <jet-input id="pro" type="text" class="mt-1 block w-full" v-model="form.pro" />
                                        <jet-input-error :message="form.errors.pro" class="mt-2" />
                                    </div>

                                    <div class="col-span-6">
                                        <jet-label for="work" value="Profession" />
                                        <jet-input id="work" type="text" class="mt-1 block w-full" v-model="form.work" />
                                        <jet-input-error :message="form.errors.work" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-4">
                                        <jet-label for="family_situation" value="Situation familiale" class="required" />
                                        <jet-select id="family_situation" :choices="familySituations" v-model="form.family_situation" />
                                        <jet-input-error :message="form.errors.family_situation" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-2">
                                        <jet-label for="nb_children" value="Nombre d'enfants à charge" />
                                        <jet-input id="nb_children" type="number" class="mt-1 block w-full" v-model="form.nb_children" />
                                        <jet-input-error :message="form.errors.nb_children" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 mt-4">
                                        <jet-label for="lesson" value="Cours" class="required" />
                                        <jet-select id="lesson" :choices="lessonList" v-model="form.lesson" />
                                        <jet-input-error :message="form.errors.lesson" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 mt-4">
                                        <jet-label for="address" value="Adresse" />
                                        <jet-input id="address" type="text" class="mt-1 block w-full" v-model="form.address" />
                                        <jet-input-error :message="form.errors.address" class="mt-2" />
                                    </div>

                                    <div class="col-span-6">
                                        <jet-label for="address2" value="Complément d'adresse" />
                                        <jet-input id="address2" type="text" class="mt-1 block w-full" v-model="form.address2" />
                                        <jet-input-error :message="form.errors.address2" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                        <jet-label for="zipcode" value="Code postal" />
                                        <jet-input id="zipcode" type="text" class="mt-1 block w-full" v-model="form.zipcode" />
                                        <jet-input-error :message="form.errors.zipcode" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-6 lg:col-span-4">
                                        <jet-label for="city" value="Ville" />
                                        <jet-input id="city" type="text" class="mt-1 block w-full" v-model="form.city" />
                                        <jet-input-error :message="form.errors.city" class="mt-2" />
                                    </div>
                                </div>
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
import JetInput from '@/Jetstream/Input.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetSelect from '@/Jetstream/Select.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import { useForm } from "@inertiajs/inertia-vue3";
import JetTextarea from '@/Jetstream/Textarea.vue';
import { ref, computed } from "vue";

export default {
    title: 'Nouvel utilisateur',
    components: {
        AdminLayout,
        JetButton,
        JetLabel,
        JetInput,
        JetInputError,
        JetTextarea,
        JetCheckbox,
        JetSelect,
    },

    props: {
        lessons: Array,
        flash: {
            type: Object,
            required: false
        },
    },

    setup(props) {
        const form = useForm({
            firstname: '',
            lastname: '',
            email: '',
            birthday: '',
            phone: '',
            pro: '',
            work: '',
            gender: '',
            nb_children: 0,
            family_situation: '',
            lesson: null,
            address: '',
            address2: '',
            zipcode: '',
            city: '',
            health_issue: '',
            current_health_issue: '',
            medical_treatment: '',
            sports: '',
            objectives: '',
            other_data: '',
        });

        const lessonList = computed(() => {
            return props.lessons.map((lesson) => {
                return {
                    label: lesson.title,
                    value: lesson.id
                }
            })
        });

        const submit = () => {
            form.post(route('utilisateurs.store'))
        }

        const familySituations = ref([
            {label: 'Célibataire / Divorcé(e)', value: 'Célibataire / Divorcé(e)'},
            {label: 'Marié(e) / paxé(e)', value: 'Marié(e) / paxé(e)'},
            {label: 'Veuf / Veuve', value: 'Veuf / Veuve'},
        ]);

        return {
            form,
            familySituations,
            lessonList,
            submit,
        }
    }
}
</script>
