<template>
    <user-layout title="Renouveler votre abonnement">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Renouveler votre abonnement
            </h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert" v-if="flash.success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="ml-5">{{ flash.success }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <section class="text-gray-600 body-font">
                        <div class="container p-6 sm:px-20 mx-auto">
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                <div class="mb-4">
                                    <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                        Détail des cours
                                    </h2>
                                    <div class="prose max-w-none" v-html="tos.details"></div>
                                </div>

                                <div class="mb-4">
                                    <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                        Processus de réservation du cours
                                    </h2>

                                    <div class="prose max-w-none" v-html="tos.process"></div>
                                </div>

                                <div class="mb-4">
                                    <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                        Organisation des cours
                                    </h2>

                                    <div class="prose max-w-none" v-html="tos.organization"></div>
                                </div>

                                <div class="mb-4">
                                    <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                        Acceptation des conditions
                                    </h2>

                                    <div class="prose max-w-none" v-html="tos.conditions"></div>
                                </div>
                            </div>
                            <form class="mt-5" @submit.prevent="submit">
                                <div v-if="form.errors.length > 0" class="flex items-center bg-red-500 text-white text-sm font-bold px-4 py-3 mb-4" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <p>Veuillez corriger les erreurs dans le formulaire</p>
                                </div>

                                <div v-if="feedback !== ''" class="flex items-center bg-orange-500 text-white text-sm font-bold px-4 py-3 mb-4" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p>{{ feedback }}</p>
                                </div>

                                <h2 class="text-2xl text-gray-700 leading-tight mb-2">
                                    Choix du cours
                                </h2>
                                <div class="grid grid-cols-6 gap-4 mb-4">
                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Choix n°1" />
                                        <jet-select :choices="filteredLessons" v-model="form.schedule1"/>
                                        <jet-input-error :message="form.errors.schedule1" class="mt-2"/>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Choix n°2" />
                                        <jet-select :choices="filteredLessons" v-model="form.schedule2" />
                                        <jet-input-error :message="form.errors.schedule2" class="mt-2"/>
                                    </div>
                                </div>
                                <div>
                                    <jet-label for="health_data" value="Avez-vous des informations médicales dont vous voudriez nous faire part ?"/>
                                    <jet-textarea id="health_data" v-model="form.health_data" class="mt-1 block w-full"/>
                                    <jet-input-error :message="form.errors.health_data" class="mt-2"/>
                                </div>

                                <div class="mt-4">
                                    <div>
                                        <jet-label for="health_data" value="Certificat médical"/>
                                        <jet-file-upload
                                            @input="handleUpload"
                                            :current-file="currentYear.file || null"
                                        />
                                        <jet-input-error :message="form.errors.medical_certificate" class="mt-2"/>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <jet-label for="type" value="Moyen de paiement" />
                                    <label class="inline-flex items-center mr-6">
                                        <input type="radio" class="form-radio" value="full" v-model="form.payment_method" :checked="form.payment_method === 'full'">
                                        <span class="ml-2">À l'année ({{ settings.price_full }}€)</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio" value="quarterly" v-model="form.payment_method" :checked="form.payment_method === 'quarterly'">
                                        <span class="ml-2">Trimestriel (3 x {{ settings.price_quarterly}}€)</span>
                                    </label>
                                    <jet-input-error :message="form.errors.payment" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="content" value="Avez-vous des amies qui souhaitent s'inscrire ?"/>
                                    <div
                                        v-for="(entry, index) in form.invites"
                                        :key="index"
                                        class="flex mt-5"
                                    >
                                        <div class="flex flex-wrap w-4/5 -mx-3">
                                            <div class="flex-50 px-3 mb-2">
                                                <jet-label for="content" value="Prénom"/>
                                                <jet-input v-model="entry.firstname" type="text"/>
                                                <jet-input-error :message="form.errors[`invites.${index}.firstname`]" class="mt-2"/>
                                            </div>
                                            <div class="flex-50 px-3 mb-2">
                                                <jet-label for="content" value="Nom"/>
                                                <jet-input v-model="entry.lastname" type="text"/>
                                                <jet-input-error :message="form.errors[`invites.${index}.lastname`]"
                                                                 class="mt-2"/>
                                            </div>
                                            <div class="flex-50 px-3 mb-2">
                                                <jet-label for="content" value="Téléphone"/>
                                                <jet-input v-model="entry.phone" type="text"/>
                                                <jet-input-error :message="form.errors[`invites.${index}.phone`]" class="mt-2"/>

                                            </div>
                                            <div class="flex-50 px-3 mb-2">
                                                <jet-label for="content" value="Email"/>
                                                <jet-input v-model="entry.email"  type="email"/>
                                                <jet-input-error :message="form.errors[`invites.${index}.email`]"
                                                                 class="mt-2"/>
                                            </div>
                                            <div class="flex-100 px-3">
                                                <jet-label for="content" value="Cours souhaité"/>
                                                <jet-select :choices="lessons" v-model="entry.lid"/>
                                                <jet-input-error :message="form.errors[`invites.${index}.lid`]" class="mt-2" />
                                            </div>
                                        </div>

                                        <div class="flex justify-center items-center w-1/5">
                                            <div>
                                                <jet-secondary-button @click="remove(index)">Retirer</jet-secondary-button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-8 flex justify-center">
                                        <jet-button type="button" @click="add">Ajouter</jet-button>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div class="form-control">
                                        <label class="cursor-pointer">
                                            <input v-model="form.accepts" class="text-indigo-500 w-5 h-5 focus:ring-indigo-400 focus:ring-opacity-25 border border-gray-500 rounded" type="checkbox">
                                            <span class="label-text ml-5">
                                                Le participant accepte les conditions d’inscription ci-dessus
                                            </span>
                                        </label>
                                    </div>
                                    <jet-input-error :message="form.errors.accepts" class="mt-2"/>
                                </div>

                                <div class="mt-8 flex justify-end">
                                    <button class="btn btn-primary btn-active" type="submit" aria-pressed="true">
                                        {{ editing ? 'Mettre à jour' : 'Valider'}} ma réinscription
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </user-layout>
</template>

<script setup>
import JetInput from '@/Jetstream/Input.vue'
import JetTextarea from '@/Jetstream/Textarea.vue'
import JetSelect from '@/Jetstream/Select.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetFileUpload from '@/Jetstream/FileUpload.vue'
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import UserLayout from "@/Layouts/UserLayout.vue";
import {computed, onMounted, ref} from "vue";
import dayjs from "dayjs";

const props = defineProps({
    editing: {
        type: Boolean,
        default: false,
    },
    tos: Object,
    user: Object,
    lessons: Array,
    settings: Object,
    relatedRenewal: Object,
    yearData: Object,
    flash: {
        type: Object,
        required: false
    }
})

const user = usePage().props.value.user

const feedback = ref('');


const form = useForm({
    payment_method: '',
    schedule1: null,
    schedule2: null,
    medical_certificate: null,
    invites: props.editing ? props.user.subscription.invites : [],
    health_data: props.editing ? props.user.current_year_data?.health_data : '',
    accepts: props.editing,
})

onMounted(() => {
    if (user.resubscription_status) {
        const { relatedRenewal } = props;
        form.accepts = true;
        form.payment_method = relatedRenewal.payment;
        form.schedule1 = relatedRenewal.lesson_choices[0];
        form.schedule2 = relatedRenewal.lesson_choices[1];
        form.invites = relatedRenewal.invites;

        feedback.value = relatedRenewal.feedback;
    }
})

const currentYear = computed(() => {
    const date = dayjs();

    // Check if after september
    if (date.month() >= 8) {
        return `${date.year()} - ${date.add(1, 'year').year()}`
    } else {
        return `${date.subtract(1, 'year').year()} - ${date.year()}`;
    }
})

const filteredLessons = computed(() => {
    return props.lessons.filter((l) => l.gender.includes(props.user.gender))
});

const handleUpload = (file) => {
    form.medical_certificate = file
};

const add = () => {
    form.invites.push({firstname: '', lastname: '', email: '', phone: '', lid: ''})
}

const remove = (index) => {
    form.invites.splice(index, 1)
}

function submit() {
    form.post(route('renewal.update'))
}

</script>
