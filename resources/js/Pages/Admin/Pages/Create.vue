<template>
    <admin-layout title="Pages">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Listes des pages
            </h2>
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
                            <form>
                                <div>
                                    <jet-label for="title" value="Titre" />
                                    <jet-input id="title" type="text" class="mt-1 block w-full" v-model="form.title" required autofocus />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="title" value="Lien généré" />
                                    <jet-input id="title" type="text" class="mt-1 block w-full" v-model="form.slug" disabled />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="summary" value="Résumé" />
                                    <jet-input id="summary" type="text" class="mt-1 block w-full" v-model="form.summary" required />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="content" value="Contenu de la page" />
                                    <wysiwyg v-model="form.content" />
                                </div>

                                <div class="mt-8">
                                    <div class="flex justify-end">
                                        <jet-secondary-button type="submit" @click.prevent="submit(false)">
                                            Enregistrer comme brouillon
                                        </jet-secondary-button>
                                        <jet-button type="submit" @click.prevent="submit(true)" class="ml-2">
                                            Enregistrer et publier
                                        </jet-button>
                                    </div>
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
import AdminLayout from '@/Layouts/AdminLayout.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Wysiwyg from '@/Jetstream/Wysiwyg.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetInputError from '@/Jetstream/InputError.vue'

export default {
    components: {
        AdminLayout,
        JetInput,
        JetLabel,
        JetButton,
        JetSecondaryButton,
        JetInputError,
        Wysiwyg
    },

    data () {
        return {
            form: this.$inertia.form({
                title: '',
                slug: '',
                summary: '',
                content: '',
                published: false,
            })
        }
    },

    methods: {
        submit (status) {
            this.form.published = status;
            this.form.post(route('pages.store'));
        }
    }
}
</script>
