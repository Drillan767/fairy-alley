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
                                    <jet-input-error :message="form.errors.title" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="title" value="Lien généré" />
                                    <jet-input id="title" type="text" class="mt-1 block w-full" :value="slug" disabled />
                                    <jet-input-error :message="form.errors.slug" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="summary" value="Résumé" />
                                    <jet-input id="summary" type="text" class="mt-1 block w-full" v-model="form.summary" required />
                                    <jet-input-error :message="form.errors.summary" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <jet-label for="content" value="Contenu de la page" />
                                    <wysiwyg v-model="form.content" :tiny="tiny" />
                                    <jet-input-error :message="form.errors.content" class="mt-2" />
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
import { emitter } from "@/Modules/emitter";

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
                medias: [],
            }),


        }
    },

    props: {
        tiny: String,
    },

    mounted () {
        emitter.on('image-added', (blob) => this.form.medias.push(blob))
    },

    methods: {
        submit (status) {
            console.log(this.images)
            this.form.published = status;
            this.form.images = this.images;
            this.form.slug = this.slug
            this.form.post(route('pages.store'));
        }
    },

    computed: {
        slug() {
            let str = this.form.title;
            str = str.replace(/^\s+|\s+$/g, ""); // trim
            str = str.toLowerCase();

            // remove accents, swap ñ for n, etc
            const from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
            const to = "aaaaaaeeeeiiiioooouuuunc------";

            for (let i = 0, l = from.length; i < l; i++) {
                str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
            }

            str = str
                .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
                .replace(/\s+/g, "-") // collapse whitespace and replace by -
                .replace(/-+/g, "-") // collapse dashes
                .replace(/^-+/, "") // trim - from start of text
                .replace(/-+$/, ""); // trim - from end of text

            return str;
        }
    }
}
</script>
