<template>
    <modal :show="show" :closeable="true" @close="close">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h2 class="font-semibold text-center text-xl text-gray-800 leading-tight">{{ details.lesson }}</h2>
            <div class="mt-4">
                <h3 class="font-semibold text-lg text-gray-800 loading-tight">
                    Membres inscrits pour cette session :
                </h3>

                <ul v-for="(user, i) in details.userList" :key="i" class="mt-2 list-disc">
                    <li class="ml-4 flex justify-between">
                            <span>
                                {{ user.firstname}} {{ user.lastname }} ( {{ [user.phone, user.pro].filter(x => x).join(', ') }} )
                            </span>
                        <button @click="unsubscribe(user)" class="btn btn-xs btn-error">Désinscrire</button>
                    </li>
                </ul>
            </div>

            <div class="mt-8 flex justify-end">
                <button class="btn btn-sm btn-error">
                    Verrouiller le cours
                </button>
            </div>
        </div>
    </modal>
</template>

<script>
import Modal from "@/Jetstream/Modal.vue";
import Swal from 'sweetalert2';
import {watch, ref} from "vue";

export default {
    emits: ['close'],
    components: {
        Modal,
    },

    props: {
        details: {
            lesson: '',
            hour: '',
            lesson_time: '',
            lesson_id: '',
            userList: [],
        },
        show: {
            type: Boolean,
            default: false
        },
    },

    setup(props, {emit}) {

        const unsubscribe = (user) => {
            Swal.fire({
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Annuler',
                confirmButtonText: 'Confirmer',
                confirmButtonColor: '#f87272',
                title: 'Confirmer la désinscription ?',
                text: `Vous êtes sur le point de désinscrire ${user.full_name} du cours "${props.details.lesson}" du ${props.details.hour}.`
            })
                .then((response) => {
                    if (response.isConfirmed) {
                        axios.post(route('movement.flow'), {
                            user_id: user.id,
                            action: 'leave',
                            lesson_id: props.details.lesson_id,
                            lesson_time: props.details.lesson_time,
                            by_admin: true,
                        })
                            .then((response) => {
                                emit('close')
                                Swal.fire({
                                    icon: 'success',
                                    toast: true,
                                    position: 'top-end',
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                    timer: 2000,
                                })
                            })
                    }
                })
        }

        const lock = () => {
            Swal.fire({
                icon: 'warning',
                title: 'Confirmer la désinscription ?',
                showCancelButton: true,

                text: `Vous êtes sur le point de désinscrire ${user.full_name} du cours "${props.details.title}"`
                // html: `<pre><code>${user}</code></pre>`
            })
        }

        return {
            unsubscribe,
            lock,
        }
    },

    methods: {
        close() {
            this.$emit('close');
        },
    }
}
</script>
