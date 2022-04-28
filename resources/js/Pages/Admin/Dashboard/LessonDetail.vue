<template>
    <modal :show="show" :closeable="true" @close="close" max-width="3xl">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h2 class="font-semibold text-center text-xl text-gray-800 leading-tight">
                {{ details.lesson }}
                <template v-if="details.status === 'locked'">(verrouillé)</template>
            </h2>
            <div class="mt-4">
                <h3 class="font-semibold text-lg text-gray-800 loading-tight">
                    Membres inscrits pour cette session ({{ details.userList.length }} personnes) :
                </h3>

                <ul v-for="(user, i) in details.userList" :key="i" class="mt-2 list-disc">
                    <li class="ml-4 flex justify-between">
                            <span>
                                {{ user.firstname}} {{ user.lastname }} ( {{ [user.phone, user.pro].filter(x => x).join(', ') }} )
                            </span>
                        <button @click="unsubscribe(user)" class="btn btn-xs btn-error text-white">
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M6.2253 4.81108C5.83477 4.42056 5.20161 4.42056 4.81108 4.81108C4.42056 5.20161 4.42056 5.83477 4.81108 6.2253L10.5858 12L4.81114 17.7747C4.42062 18.1652 4.42062 18.7984 4.81114 19.1889C5.20167 19.5794 5.83483 19.5794 6.22535 19.1889L12 13.4142L17.7747 19.1889C18.1652 19.5794 18.7984 19.5794 19.1889 19.1889C19.5794 18.7984 19.5794 18.1652 19.1889 17.7747L13.4142 12L19.189 6.2253C19.5795 5.83477 19.5795 5.20161 19.189 4.81108C18.7985 4.42056 18.1653 4.42056 17.7748 4.81108L12 10.5858L6.2253 4.81108Z"
                                    fill="currentColor"
                                />
                            </svg>
                        </button>
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
                        <jet-button @click.prevent="saveUsers">Enregistrer</jet-button>
                    </div>

                </div>
            </div>

            <div class="mt-8 flex gap-x-3 justify-end">
                <button @click.prevent="addUser" class="btn btn-sm btn-success">
                    Ajouter une personne
                </button>

                <button @click.prevent="lock(false)" class="btn btn-sm btn-success" v-if="details.status === 'locked'">
                    Déverrouiller le cours
                </button>
                <button @click.prevent="lock(true)" class="btn btn-sm btn-error" v-if="['ok', 'recovery'].includes(details.status)">
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
import {ref, toRaw} from "vue";
import dayjs from "dayjs";
import utc from 'dayjs/plugin/utc';
import {Inertia} from "@inertiajs/inertia";

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
            status: '',
            userList: [],
        },
        show: {
            type: Boolean,
            default: false
        },
    },

    setup(props, {emit}) {
        dayjs.extend(utc)
        const displayUserList = ref(false);
        const userList = ref([]);
        const addedUsers = ref([]);

        const toIsoDate = (date) => {
            return dayjs(date).utc(true).toISOString()
        }

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
                        Inertia.post(route('admin.unsubscribe'), {
                            user_id: user.id,
                            lesson: props.details.lesson_id,
                            date: toIsoDate(props.hour.toDate())
                        }, {
                            onSuccess: () => emit('close')
                        })
                    }
                })
        }

        const lock = (lockLesson) => {
            const action = lockLesson ? 'verrouillage' : 'déverrouillage';
            const verb = lockLesson ? 'verrouillé' : 'déverrouilé';
            const consequences =  lockLesson
                ? "la possibilité aux membres de s'inscrire de nouveau aux cours"
                : "l'incapacité des membres à s'y inscrire par la suite.";
            Swal.fire({
                icon: 'warning',
                title: `Confirmer le ${action} du cours ?`,
                showCancelButton: true,
                confirmButtonText: 'Confirmer',
                cancelButtonText: 'Annuler',
                html: `
                <p>Le cours "${props.details.lesson}" du ${props.hour.format('DD/MM/YYYY')} est sur le point d'être
                ${verb}, ce qui engendrera ${consequences} <br />
                Continuer ?</p>
                `
            })
                .then((response) => {
                    if (response.isConfirmed) {
                        Inertia.post(route('admin.lock'), {
                            lesson: props.details.lesson_id,
                            date: toIsoDate(props.hour.toDate()),
                            lock: lockLesson,
                        }, {
                            onSuccess: () => emit('close')
                        })
                    }
                })
        }

        const addUser = () => {
            if (displayUserList.value === false) {
                const date = toIsoDate(props.hour.toDate());
                axios.post(route('users'), {
                    lesson_id: props.details.lesson_id,
                    hour: date,
                })
                    .then(({data}) => {
                        displayUserList.value = true;
                        userList.value = data
                    });
            }
        }

        const saveUsers = () => {
            const users = userList.value.filter((u) => {
                return toRaw(addedUsers.value).includes(u.value)
            })

            let ulList = document.createElement('ul');
            users.forEach((u) => {
                const item = document.createElement('li')
                item.appendChild(document.createTextNode(u.name))
                ulList.appendChild(item)
            })

            Swal.fire({
                title: addedUsers.value.length > 1 ? "Confirmer l'ajout des utilisateurs ?" : "Confirmer l'ajout de l'utilisateur ?",
                html: `
                <p>Vous êtes sur le point d'ajouter la liste des utilisateurs suivants au cours du ${props.details.lesson}:</p>
                <br />
                ${ulList.innerHTML}
                <br />
                <p>Continuer ?</p>
                `,
                confirmButtonText: 'Confirmer',
                showCancelButton: true,
                cancelButtonText: 'Annuler',
            })
                .then((response) => {
                    if (response.isConfirmed) {
                        Inertia.post(route('admin.subscribe'), {
                            users: toRaw(addedUsers.value),
                            lesson: props.details.lesson_id,
                            date: toIsoDate(props.hour.toDate())
                        }, {
                            onSuccess: () => {
                                displayUserList.value = false;
                                addedUsers.value = []
                                emit('close')
                            }
                        })
                    }
                })
        }

        return {
            unsubscribe,
            displayUserList,
            userList,
            saveUsers,
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
