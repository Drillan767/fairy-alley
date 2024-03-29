<template>
    <modal :show="show" :closeable="true" @close="close">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <form @submit.prevent="submit">
                <div>
                    <jet-label for="title" value="Titre" />
                    <jet-input id="title" type="text" v-model="form.title" />
                    <jet-input-error :message="form.errors.title" class="mt-2" />
                </div>

                <div class="mt-4">
                    <jet-label for="description" value="Description" />
                    <jet-input id="description" type="text"  v-model="form.description" />
                    <jet-input-error :message="form.errors.description" class="mt-2" />
                </div>

                <div class="mt-4">
                    <jet-label for="description" value="Référence" />
                    <jet-input id="ref" type="text"  v-model="form.ref" />
                    <jet-input-error :message="form.errors.ref" class="mt-2" />
                </div>

                <div class="mt-4">
                    <jet-label for="type" value="Nature du service :" />
                    <label class="inline-flex items-center mr-6">
                        <input type="radio" class="form-radio" name="accountType" value="s" v-model="form.type" :checked="form.type === 's'">
                        <span class="ml-2">Service</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio" name="accountType" value="p" v-model="form.type" :checked="form.type === 'p'">
                        <span class="ml-2">Produit</span>
                    </label>
                    <jet-input-error :message="form.errors.type" class="mt-2" />
                </div>

                <div class="mt-4">
                    <jet-label value="Illustration" />
                    <jet-file-upload @input="handleUpload" :currentFile="service?.banner ?? null" />
                    <jet-input-error :message="form.errors.illustration" class="mt-2" />
                </div>

                <div class="mt-4">
                    <jet-label value="Page liée" />
                    <jet-select :choices="pagesList" v-model="form.page_id" />
                    <jet-input-error :message="form.errors.page_id" class="mt-2" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <jet-checkbox v-model="form.homepage" :checked="form.homepage" />
                        <span class="ml-2 text-sm text-gray-600">Afficher sur la page d'accueil</span>
                    </label>
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
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import {useForm} from "@inertiajs/inertia-vue3";
import {computed, watch} from "vue";

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
        JetCheckbox,
    },

    props: {
        show: {
            type: Boolean,
            default: false
        },

        editing: {
            type: Boolean,
            required: false,
        },

        service: {
            type: Object,
            required: false,
        },

        pages: Array,
    },

    setup(props, {emit}) {

        let form = useForm({
            title: '',
            description: '',
            ref: '',
            type: '',
            homepage: false,
            illustration: null,
            page_id: null,
        })

        watch(() => props.service, (fields) => {
            form.title = fields.title;
            form.description = fields.description;
            form.page_id = fields.page_id;
            form.type = fields.type;
            form.ref = fields.ref;
            form.homepage = fields.homepage;
        });

        function submit() {
            if (props.service) {
                form.transform((data) => ({
                    ...data,
                    _method: 'PUT',
                }))
                .post(route('services.update', {service: props.service.id}), {
                    onSuccess: () => success()
                });
            } else {
                form.post(route('services.store'), {
                    onSuccess: () => success()
                });
            }
        }

        function success() {
            form.reset();
            emit('close');
            window.location.reload();
        }

        const handleUpload = (file) => {
            form.illustration = file
        };

        const pagesList = computed(() => {
            let result = [];
            props.pages.forEach((page) => {
                result.push({
                    label: page.title,
                    value: page.id,
                })
            })

            return result;
        });

        return {
            form,
            submit,
            handleUpload,
            pagesList,
        }
    },

    methods: {
        close() {
            this.$emit('close');
        },
    }
}
</script>
