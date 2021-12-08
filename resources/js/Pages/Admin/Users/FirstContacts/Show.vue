<template>
    <admin-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Contact de {{ contact.firstname }} {{ contact.lastname }}
            </h1>
        </template>

        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <h2 class="font-semibold text-l text-gray-800 leading-tight">
                                Coordonnées de la personne
                            </h2>

                            <div class="grid grid-cols-6 gap-4 mt-5">
                                <div class="col-span-6 sm:col-span-3">
                                    <p class="block text-sm font-medium text-gray-800">Nom de famille</p>
                                    <p class="text-sm text-gray-700">{{ contact.lastname }}</p>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <p class="block text-sm font-medium text-gray-800">Prénom</p>
                                    <p class="text-sm text-gray-700">{{ contact.firstname }}</p>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <p class="block text-sm font-medium text-gray-800">Adresse e-mail</p>
                                    <p class="text-sm text-gray-700">{{ contact.email }}</p>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <p class="block text-sm font-medium text-gray-800">Date de naissance</p>
                                    <p class="text-sm text-gray-700">{{ contact.birthday }}</p>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <p class="block text-sm font-medium text-gray-800">Téléphone portable</p>
                                    <p class="text-sm text-gray-700">{{ contact.phone }}</p>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <p class="block text-sm font-medium text-gray-800">Téléphone fixe / professionnel</p>
                                    <p class="text-sm text-gray-700">{{ contact.pro }}</p>
                                </div>
                                <div class="col-span-6">
                                    <p class="block text-sm font-medium text-gray-800">Profession</p>
                                    <p class="text-sm text-gray-700">{{ contact.work }}</p>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <p class="block text-sm font-medium text-gray-800">Situation familiale</p>
                                    <p class="text-sm text-gray-700">{{ contact.family_situation }}</p>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <p class="block text-sm font-medium text-gray-800">Nombre d'enfants à charge</p>
                                    <p class="text-sm text-gray-700">{{ contact.nb_children }}</p>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-5">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <h2 class="font-semibold text-l text-gray-800 leading-tight">
                                Adresse
                            </h2>

                            <div class="grid grid-cols-6 gap-4 mt-5">
                                <div class="col-span-6">
                                    <p class="block text-sm font-medium text-gray-800">Adresse postale</p>
                                    <p class="text-sm text-gray-700">
                                        {{ contact.address }}
                                    </p>
                                    <p v-if="contact.address2" class="text-sm text-gray-700">
                                        {{ contact.address }}
                                    </p>
                                    <p class="text-sm text-gray-700">
                                        {{ contact.zipcode }} - {{ contact.city }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-5">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <h2 class="font-semibold text-l text-gray-800 leading-tight">
                                Questions posées
                            </h2>

                            <div class="grid grid-cols-6 gap-4 mt-5">
                                <div class="col-span-6">
                                    <p class="block text-sm font-medium text-gray-800">Avez-vous eu des problèmes de santé ?</p>
                                    <p class="text-sm text-gray-700">
                                        {{ contact.health_issues }}
                                    </p>
                                </div>

                                <div class="col-span-6">
                                    <p class="block text-sm font-medium text-gray-800">Avez-vous des problèmes de santé actuellement ? (certificat médical d’aptitude pour la gymnastique)</p>
                                    <p class="text-sm text-gray-700">
                                        {{ contact.current_health_issues }}
                                    </p>
                                </div>

                                <div class="col-span-6">
                                    <p class="block text-sm font-medium text-gray-800">Suivez-vous un traitement médical ? Si oui lequel ?</p>
                                    <p class="text-sm text-gray-700">
                                        {{ contact.medical_treatment }}
                                    </p>
                                </div>

                                <div class="col-span-6">
                                    <p class="block text-sm font-medium text-gray-800">Sports, loisirs, autres activités :</p>
                                    <p class="text-sm text-gray-700">
                                        {{ contact.sports }}
                                    </p>
                                </div>

                                <div class="col-span-6">
                                    <p class="block text-sm font-medium text-gray-800">Compléments d’information ?</p>
                                    <p class="text-sm text-gray-700">
                                        {{ contact.other_data }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    title () {
        return `Contact de ${this.contact.firstname} ${this.contact.lastname}`
    },
    props: ['contact'],
    components: {
        AdminLayout,
    }
}
</script>
