<template>
    <modal :show="show" :closeable="true" @close="close">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <form @submit.prevent="submit">
                <h2 class="font-semibold text-center text-xl text-gray-800 leading-tight">{{ details.lesson }}</h2>
                <div class="mt-4">
                    <h3 class="font-semibold text-lg text-gray-800 loading-tight">
                        Membres inscrits pour cette session :
                    </h3>

                    <ul v-for="(user, i) in details.userList" :key="i" class="mt-2 list-disc">
                        <li class="ml-4">
                            {{ user.firstname}} {{ user.lastname }} ( {{ [user.phone, user.pro].filter(x => x).join(', ') }} )
                        </li>
                    </ul>
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
import { useForm } from "@inertiajs/inertia-vue3";
import {watch, ref} from "vue";

export default {
    emits: ['close'],
    components: {
        Modal,
        JetInput,
        JetButton,
        JetLabel,
        JetInputError,
    },

    props: {
        details: {
            lesson: '',
            userList: [],
        },
        show: {
            type: Boolean,
            default: false
        },
    },

    setup(props, {emit}) {

        let form = useForm({
            // type: '',
            // files: null,
        })

        function submit() {
            form.post(route('media.upload'), {
                onSuccess: () => {
                    form.reset();
                    emit('close');
                }
            });
        }

        return {
            form,
            submit,
        }
    },

    methods: {
        close() {
            this.$emit('close');
        },
    }
}
</script>
