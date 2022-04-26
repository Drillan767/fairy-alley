<template>
    <modal :show="show" :closeable="true" @close="close">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h2 class="font-semibold text-center text-xl text-gray-800 leading-tight">{{ details.lesson }}</h2>
            <div class="mt-4">
                <h3 class="font-semibold text-lg text-gray-800 loading-tight">
                    Membres inscrits pour cette session ({{ details.userList.length }} personnes) :
                </h3>

                <ul v-for="(user, i) in details.userList" :key="i" class="mt-2 list-disc">
                    <li class="ml-4 flex justify-between">
                            <span>
                                {{ user.firstname}} {{ user.lastname }} ( {{ [user.phone, user.pro].filter(x => x).join(', ') }} )
                            </span>
                        <button @click="unsubscribe(user)" class="btn btn-xs btn-error">Désinscrire</button>
                    </li>
                </ul>

                <div v-if="displayUserList" class="mt-4">
                    <jet-label value="Sélectionner une ou plusieurs personnes" />
                    <Multiselect
                        v-model="addedUsers"
                        mode="tags"
                        :close-on-select="false"
                        :options="userList"
                        track-by="name"
                        label="name"
                        placeholder="Sélectionner..."
                        :searchable="true"
                    >
                        <template v-slot:tag="{ option, handleTagRemove, disabled }">
                            <div class="multiselect-tag">
                                {{ option.name }}
                                <span
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                >
                                  <span class="multiselect-tag-remove-icon"></span>
                                </span>
                            </div>
                        </template>
                    </Multiselect>

                    <div class="flex justify-end mt-24">
                        <jet-button>Enregistrer</jet-button>
                    </div>

                </div>
            </div>

            <div class="mt-8 flex gap-x-3 justify-end">
                <button @click.prevent="addUser" class="btn btn-sm btn-success">
                    Ajouter une personne
                </button>
                <button class="btn btn-sm btn-error">
                    Verrouiller le cours
                </button>
            </div>
        </div>
    </modal>
</template>

<script>
import Modal from "@/Jetstream/Modal.vue";
import Multiselect from '@vueform/multiselect';
import JetLabel from "@/Jetstream/Label.vue";
import JetButton from "@/Jetstream/Button.vue";
import '@vueform/multiselect/themes/default.scss';
import Swal from 'sweetalert2';
import { ref } from "vue";
import dayjs from "dayjs";

export default {
    emits: ['close'],
    components: {
        Modal,
        Multiselect,
        JetLabel,
        JetButton,
    },

    props: {
        hour: null,
        details: {
            lesson: '',
            lesson_id: '',
            userList: [],
        },
        show: {
            type: Boolean,
            default: false
        },
    },

    setup(props, {emit}) {
        const displayUserList = ref(false);
        const userList = ref([]);
        const addedUsers = ref([]);

        const unsubscribe = (user) => {
            Swal.fire({
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Annuler',
                confirmButtonText: 'Confirmer',
                confirmButtonColor: '#f87272',
                title: 'Confirmer la désinscription ?',
                text: `Vous êtes sur le point de désinscrire ${user.full_name} du cours "${props.details.lesson}" du ${props.hour.format('DD/MM/YYYY')}.`
            })
                .then((response) => {
                    if (response.isConfirmed) {
                        axios.post(route('movement.flow'), {
                            user_id: user.id,
                            action: 'leave',
                            lesson_id: props.details.lesson_id,
                            lesson_time: props.hour.tz('Europe/Paris').toISOString(),
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

        const addUser = () => {
            axios.post(route('users'), {
                lesson_id: props.details.lesson_id,
                hour: props.hour.toISOString()
            })
                .then(({data}) => {
                    console.log(data)
                    // displayUserList.value = true;
                    // userList.value = data
                });
        }

        return {
            unsubscribe,
            displayUserList,
            userList,
            addedUsers,
            addUser,
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

<style lang="scss" scoped>
.multiselect-tags-search:focus {
    outline: none;
    --tw-ring-color: transparent;
}
.multiselect-tag {
    padding: 5px 8px;
    border-radius: 22px;
    background: #35495e;
    margin: 3px 3px 8px;

    i:before {
        color: #ffffff;
        border-radius: 50%;
    }
}

</style>
