<template>
    <admin-layout title="Pages">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Éditer "{{ page.title }}"
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
                                    <jet-input id="slug" type="text" class="mt-1 block w-full" :value="slug" disabled />
                                    <jet-input-error :message="form.errors.slug" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <!-- Page Illustration File Input -->
                                    <input type="file" class="hidden" ref="illustration" @change="updateillustrationPreview" />
                                    <jet-label for="illustration" value="Illustration pour la page" />

                                    <!-- Current Page Illustration -->
                                    <div class="mt-2" v-show="! illustrationPreview && form.illustration">
                                        <img :src="form.illustration" alt="illustration" class="rounded">
                                    </div>

                                    <!-- New Page Illustration Preview -->
                                    <div class="mt-2" v-show="illustrationPreview">
                                        <img class="rounded" :src="illustrationPreview" alt="illustration">
                                    </div>

                                    <jet-secondary-button class="mt-2 mr-2" type="button" @click.prevent="selectNewillustration">
                                        Ajouter une illustration
                                    </jet-secondary-button>

                                    <jet-input-error :message="form.errors.illustration" class="mt-2" />
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
                                        <jet-secondary-button type="submit" @click.prevent="submit(false)" :disabled="form.processing">
                                            Enregistrer comme brouillon
                                        </jet-secondary-button>
                                        <jet-button type="submit" @click.prevent="submit(true)" class="ml-2" :disabled="form.processing">
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
import JetInput from '@/Jetstream/Input.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Wysiwyg from '@/Jetstream/Wysiwyg.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { emitter } from "@/Modules/emitter";
import { Inertia } from "@inertiajs/inertia";
import {computed, ref} from 'vue'

import { useForm } from '@inertiajs/inertia-vue3'

export default {
    props: {
        page: {
            type: Object,
            required: false
        },
        editing: Boolean,
        tiny: String,
    },
    components: {
        AdminLayout,
        JetInput,
        JetLabel,
        JetButton,
        JetSecondaryButton,
        JetInputError,
        Wysiwyg,
    },

    setup (props) {
        const form = useForm({
            title: props.page.title,
            slug: props.page.slug,
            illustration: props.page.illustration,
            imgFile: null,
            summary: props.page.summary,
            content: props.page.content,
        })

        const illustrationPreview = ref(null);
        const illustrationInput = ref(null);
        const slug = computed(() => {
            let str = form.title;
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
        })

        function submit () {
            form.post(route('pages.update', {page: props.page.id}))
        }

        /*function submit (status) {
            this.form.published = status;
            this.form.images = this.images;
            this.form.imgFile = this.$refs.illustration.files[0];
            this.form.slug = this.slug

            console.log(this.$refs.illustration.files[0]);

            if (this.editing) {
                this.form.put(route('pages.update', {page: this.page.id}));
                // axios.put(), formData);
                // this.form.put(), {forceFormData: true})
            } else {
                this.form.post(route('pages.store'));
            }

        },*/

        function selectNewillustration () {
            document.querySelector('input[type="file"].hidden').click();
            // illustrationInput.click();
        }

        function updateillustrationPreview () {
            const illustration = document.querySelector('input[type="file"].hidden').files[0];

            if (! illustration) return;

            form.imgFile = illustration;
            this.illustrationPreview = URL.createObjectURL(illustration);
        }

        return {
            illustrationPreview,
            form,
            slug,
            submit,
            selectNewillustration,
            updateillustrationPreview,
        }
    }
}

/*export default {
    props: {
        page: {
            type: Object,
            required: false
        },
        editing: Boolean,
        tiny: String,
    },

    components: {
        JetInput,
        JetLabel,
        JetButton,
        JetSecondaryButton,
        JetInputError,
        Wysiwyg,
    },

    data () {
        return {
            form: this.$inertia.form({
                title: '',
                slug: '',
                imgFile: null,
                illustration: '',
                summary: '',
                content: '',
                published: false,
                medias: [],
            }),
            illustrationPreview: null,

        }
    },

    mounted () {

        if (this.editing) {
            this.form = this.$inertia.form({...this.page, imgFile: null, medias: []});
        }

        emitter.on('image-added', (blob) => this.form.medias.push(blob))
    },

    methods: {
        submit (status) {
            this.form.published = status;
            this.form.images = this.images;
            this.form.imgFile = this.$refs.illustration.files[0];
            this.form.slug = this.slug

            console.log(this.form.imgFile);

            if (this.editing) {
                this.form.put(route('pages.update', {page: this.page.id}));
                // axios.put(), formData);
                // this.form.put(), {forceFormData: true})
            } else {
                this.form.post(route('pages.store'));
            }

        },

        selectNewillustration() {
            this.$refs.illustration.click();
        },

        updateillustrationPreview() {
            const illustration = this.$refs.illustration.files[0];

            if (! illustration) return;

            this.form.imgFile = illustration;
            this.illustrationPreview = URL.createObjectURL(illustration);
        },
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
}*/
</script>
