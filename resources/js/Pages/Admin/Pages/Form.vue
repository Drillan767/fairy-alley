<template>
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
</template>

<script>
import JetInput from '@/Jetstream/Input.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Wysiwyg from '@/Jetstream/Wysiwyg.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import { emitter } from "@/Modules/emitter";
import {useForm} from "@inertiajs/inertia-vue3";
import {computed, onMounted, ref} from "vue";

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
        JetInput,
        JetLabel,
        JetButton,
        JetSecondaryButton,
        JetInputError,
        Wysiwyg,
    },

    setup (props) {

        onMounted(() => emitter.on('image-added', (blob) => form.medias.push(blob)))

        const data = props.editing ? {
            ...props.page,
            imgFile: null,
            medias: [],
        } : {
            title: '',
            slug: '',
            illustration: '',
            imgFile: null,
            medias:  [],
            summary: '',
            content: '',
            published: false
        }

        const form = useForm(data)

        const illustrationPreview = ref(null);
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
            const path = props.editing ? route('pages.update', {page: props.page.id}) : route('pages.store');
            form.post(path);
        }

        function selectNewillustration () {
            document.querySelector('input[type="file"].hidden').click();
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
    },
}
</script>