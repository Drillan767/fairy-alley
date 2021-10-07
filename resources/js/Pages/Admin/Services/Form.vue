<template>
    <modal :show="show" :closeable="true" @close="close">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <form @submit.prevent="submit">
                <div>
                    <jet-label for="title" value="Titre" />
                    <jet-input id="title" type="text" class="mt-1 block w-full" v-model="form.title" />
                    <jet-input-error :message="form.errors.title" class="mt-2" />
                </div>

                <div class="mt-4">
                    <jet-label for="description" value="Description" />
                    <jet-input id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
                    <jet-input-error :message="form.errors.description" class="mt-2" />
                </div>

                <div class="mt-4">
                    <jet-label value="Illustration" />
                    <jet-file-upload v-model="form.illustration" />
                    <jet-input-error :message="form.errors.illustration" class="mt-2" />
                </div>

                <div class="mt-4">
                    <jet-label value="Page liée" />
                    <jet-select :choices="pages" v-model="form.page_id" />
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
            default: false
        },
        pages: Array,
    },

    setup(props) {

        const form = useForm({
            title: '',
            description: '',
            illustration: null,
            page_id: null,
        })

        function submit() {
            form.post(route('services.store'));
        }

        return {
            form,
            submit
        }
    },

    methods: {
        close() {
            this.$emit('close')
        },

        submit() {

        }
    }
}
</script>
