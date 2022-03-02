<template>
    <modal :show="show" :closeable="true" @close="close">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <form @submit.prevent="submit">

                <div class="mt-4">
                    <jet-label value="Fichiers" />
                    <jet-file-upload :multiple="true" @input="handleUpload" />
                    <jet-input-error :message="form.errors.illustration" class="mt-2" />
                </div>

                <div class="mt-4">
                    <jet-label value="Type de fichier" />
                    <jet-select :choices="choices" v-model="form.type" />
                    <jet-input-error :message="form.errors.page_id" class="mt-2" />
                </div>

                <div class="mt-4 flex justify-end">
                    <jet-button type="submit">
                        Enregistrer
                    </jet-button>
                </div>
            </form>
        </div>
    </modal>
</template>

<script>
import Modal from "@/Jetstream/Modal.vue";
import JetInput from '@/Jetstream/Input.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetFileUpload from '@/Jetstream/FileUpload.vue';
import JetSelect from '@/Jetstream/Select.vue';
import {useForm} from "@inertiajs/inertia-vue3";
import { ref } from "vue";

export default {
    emits: ['close'],
    components: {
        Modal,
        JetInput,
        JetButton,
        JetLabel,
        JetInputError,
        JetFileUpload,
        JetSelect,
    },

    props: {
        show: {
            type: Boolean,
            default: false
        },
    },

    setup(props, {emit}) {

        let form = useForm({
            type: '',
            files: null,
        })

        function submit() {
            form.post(route('media.upload'), {
                onSuccess: () => {
                    form.reset();
                    emit('close');
                }
            });
        }

        const handleUpload = (files) => {
            console.log(files)
            form.files = files
        };

        const choices = ref([
            {label: 'Vidéos', value: 'video'},
            {label: 'Photos', value: 'photos'},
            {label: 'Musiques', value: 'musics'},
        ]);

        return {
            form,
            submit,
            handleUpload,
            choices,
        }
    },

    methods: {
        close() {
            this.$emit('close');
        },
    }
}
</script>
