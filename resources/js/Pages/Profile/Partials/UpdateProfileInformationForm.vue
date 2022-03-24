<template>
    <jet-form-section @submitted="updateProfileInformation">
        <template #title>
            Informations utilisateur
        </template>

        <template #description>
            Mettez à jour vos informations, ainsi que votre adresse e-mail.
        </template>

        <template #form>
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="firstname" value="Prénom" />
                <jet-input id="firstname" type="text" class="mt-1 block w-full" v-model="form.firstname" autocomplete="firstname" />
                <jet-input-error :message="form.errors.firstname" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="lastname" value="Nom de famille" />
                <jet-input id="lastname" type="text" class="mt-1 block w-full" v-model="form.lastname" autocomplete="lastname" />
                <jet-input-error :message="form.errors.lastname" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="lastname" value="Genre" />
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="accountType" value="H" v-model="form.gender" :checked="form.gender === 'M'">
                    <span class="ml-2">Homme</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" class="form-radio" name="accountType" value="F" v-model="form.gender" :checked="form.gender === 'F'">
                    <span class="ml-2">Femme</span>
                </label>
                <jet-input-error :message="form.errors.gender" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                <jet-input-error :message="form.errors.email" class="mt-2" />
            </div>

            <!-- Birthday -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="birthday" value="Date d'anniversaire" />
                <jet-input id="birthday" type="date" class="mt-1 block w-full" v-model="form.birthday" />
                <jet-input-error :message="form.errors.birthday" class="mt-2" />
            </div>

        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Enregistré.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Enregistrer
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetButton from '@/Jetstream/Button.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
        },

        props: ['user'],

        data() {
            return {
                form: this.$inertia.form({
                    _method: 'PUT',
                    firstname: this.user.firstname,
                    lastname: this.user.lastname,
                    birthday: this.user.birthday,
                    gender: this.user.gender,
                    email: this.user.email,
                }),
            }
        },

        methods: {
            updateProfileInformation() {
                this.form.post(route('user-profile-information.update'), {
                    errorBag: 'updateProfileInformation',
                    preserveScroll: true,
                });
            },
        },
    }
</script>
