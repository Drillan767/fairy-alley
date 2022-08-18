<template>
    <admin-layout :title="`Renouvellement de l'abonnement de ${currentUser.full_name}`">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Renouvellement de l'abonnement de {{ currentUser.full_name }}
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
                                        <jet-label value="Choix n°1"/>
                                        <p>{{ firstLessonChoice }}</p>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Choix n°2"/>
                                        <p>{{ secondLessonChoice }}</p>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Cours de l'année précédente" />
                                        <p>{{ currentUser.current_year_data.last_year_class }}</p>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Décision de l'administration" />
                                        <jet-select :choices="lessons" v-model="form.lesson_decision" />
                                        <jet-input-error :message="form.errors.lesson_id" class="mt-2" />
                                    </div>

                                    <div class="col-span-6">
                                        <jet-label value="Données annuelles de santé" />
                                        <jet-textarea v-model="form.year_data.health_data" class="w-full" />
                                        <jet-input-error :message="form.errors.yearly_health_data" class="mt-2" />
                                    </div>

                                    <div class="col-span-6">
                                        <jet-label value="Document joint par l'utilisateur" />
                                        <jet-file-upload
                                            :currentFile="currentUser.current_year_data.file || null"
                                            @input="handleUpload"
                                        />

                                        <a
                                            target="_blank"
                                            v-if="currentUser.current_year_data.file"
                                            :href="currentUser.current_year_data.file.url"
                                            class="btn btn-sm btn-ghost mt-4"
                                        >
                                            Télécharger le document
                                        </a>
                                    </div>

                                    <h2 class="font-semibold text-l text-gray-700 leading-tight mb-2 col-span-6">
                                        Paiement
                                    </h2>

                                    <div class="col-span-6 sm:col-span-2">
                                        <jet-label value="Total" />
                                        <jet-input type="text" v-model="form.year_data.total" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-4 border-l pl-2">
                                        <template v-if="renewalData.payment === 'quarterly'">
                                            <p class="mb-4">La personne a souhaité un paiement en plusieurs fois.</p>
                                            <div
                                                v-for="(entry, index) in form.year_data.payments"
                                                :key="index"
                                                class="flex items-end"
                                            >
                                                <div class="flex gap-x2">
                                                    <div class="px-3">
                                                        <jet-label value="Montant reçu :"/>
                                                        <jet-input v-model="entry.amount" type="text" />
                                                        <jet-input-error :message="form.errors[`payments.${index}.date`]" class="mt-2"/>
                                                    </div>

                                                    <div class="px-3">
                                                        <jet-label value="Moyen de paiement :" class="mb-1"/>
                                                        <jet-select :choices="paymentMethods" v-model="entry.method" />
                                                        <jet-input-error :message="form.errors[`payments.${index}.method`]" class="mt-2"/>
                                                    </div>

                                                    <div class="px-3">
                                                        <jet-label value="Reçu le :"/>
                                                        <jet-input v-model="entry.date" type="date" />
                                                        <jet-input-error :message="form.errors[`payments.${index}.date`]" class="mt-2"/>
                                                    </div>

                                                    <div v-if="form.year_data.payments.length > 1" class="flex items-end">
                                                        <jet-secondary-button @click="removePayment(index)">Retirer</jet-secondary-button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-8" v-if="form.year_data.payments.length < 3">
                                                <jet-button type="button" @click="addPayment">Ajouter un paiement</jet-button>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="col-span-6">
                                        <jet-label value="Observations" />
                                        <jet-textarea v-model="form.year_data.observations" class="w-full" />
                                        <p class="text-sm">Inscrivez les indications pour aider la personne à compléter sa réinscription.</p>
                                        <jet-input-error :message="form.errors.observations" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Statut de la réinscription" />
                                        <jet-select :choices="renewalStatuses" v-model="form.renewal_status" />
                                        <jet-input-error :message="form.errors.renewal_status" class="mt-2" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <jet-label value="Statut de la réinscription" />
                                        <div class="flex gap-x-3 h-2/3">
                                            <label class="flex items-center">
                                                <jet-checkbox name="remember" v-model:checked="form.payment_complete" />
                                                <span class="ml-2 text-sm text-gray-600">Paiement reçu</span>
                                            </label>
                                            <label class="flex items-center">
                                                <jet-checkbox name="remember" v-model:checked="form.documents_complete" />
                                                <span class="ml-2 text-sm text-gray-600">Documents validés</span>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <jet-button>
                                        Enregistrer
                                    </jet-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </admin-layout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import JetInput from '@/Jetstream/Input.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetFileUpload from '@/Jetstream/FileUpload.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetSelect from '@/Jetstream/Select.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import { useForm } from "@inertiajs/inertia-vue3";
import JetTextarea from '@/Jetstream/Textarea.vue';
import {computed, onMounted, ref} from "vue";
import Swal from "sweetalert2";

const props = defineProps({
    currentUser: Object,
    subscription: Object,
    lessons: Array,
    renewalData: Object,
    flash: {
        type: Object,
        required: false
    },
})

const { currentUser } = props;

const form = useForm({
    lesson_decision: props.renewalData.admin_decision ?? null,
    documents_complete: props.renewalData.documents || false,
    payment_complete: props.renewalData.paid || false,
    renewal_status: currentUser.resubscription_status,
    user_id: currentUser.id,
    year_data: {
        file: currentUser.current_year_data.file,
        health_data: currentUser.current_year_data.health_data,
        total: currentUser.current_year_data.total,
        payments: currentUser.current_year_data.payments ?? [],
        observations: currentUser.current_year_data.observations,
    },
})

const paymentMethods = ref([
    {label: 'Espèces', value: 'Espèces'},
    {label: 'Virement', value: 'Virement'},
    {label: 'Chèque', value: 'Chèque'},
])

const renewalStatuses = ref([
    {label: 'En attente', value: 1},
    {label: 'Documents manquants (certificat, etc)', value: 3},
    {label: 'Paiement manquant', value: 4},
    {label: 'Paiement incomplet', value: 5},
    {label: 'Réinscription complète', value: 2},
])

const handleUpload = (file) => {
    form.year_data.file = file
};

const addPayment = () => {
    form.year_data.payments.push({date: '', amount: '', method: ''})
}

const removePayment = (index) => {
    form.year_data.payments.splice(index, 1)
}

const firstLessonChoice = computed(() => {
    const firstLesson = props.renewalData.lesson_choices[0];
    const lesson = props.lessons.find((l) => l.value === parseInt(firstLesson))
    return lesson.label;
});

const secondLessonChoice = computed(() => {
    if (props.renewalData.lesson_choices[1] !== null) {
        const secondLesson = props.renewalData.lesson_choices[1];
        const lesson = props.lessons.find((l) => l.value === parseInt(secondLesson))
        return lesson.label;
    }

    return '';
})

const submit = () => {
    let total = 0;
    form.year_data.payments.forEach((p) => total += parseInt(p.amount));

    if (props.renewalData.payment === "quarterly" && total !== form.year_data.total) {
        Swal.fire({
            icon: 'warning',
            title: 'Les mensualités ne correspondent pas avec le total',
            text: 'Il apparaît que les valeurs indiquées dans les mensualités ne correspondent pas au total défini. Confirmer malgré tout ?',
            showCancelButton: true,
            cancelButtonText: 'Annuler',
            confirmButtonText: 'Enregistrer',
        })
            .then((response) => {
                if (response.isConfirmed) form.post(route('utilisateur.renewal.store'))
            })
    }
    else {
        form.post(route('utilisateur.renewal.store'))
    }
}

</script>
