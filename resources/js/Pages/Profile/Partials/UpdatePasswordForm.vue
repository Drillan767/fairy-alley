<template>
    <jet-form-section @submitted="updatePassword">
        <template #title>
            Mot de passe
        </template>

        <template #description>
            Assurez-vous que votre mot de passe soit long et aléatoire pour rester sécurisé.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="current_password" value="Mot de passe actuel" />
                <jet-input id="current_password" type="password"  v-model="form.current_password" ref="current_password" autocomplete="current-password" />
                <jet-input-error :message="form.errors.current_password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="password" value="Nouveau mot de passe" />
                <jet-input id="password" type="password"  v-model="form.password" ref="password" autocomplete="new-password" />
                <jet-input-error :message="form.errors.password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="password_confirmation" value="Confirmer le mot de passe" />
                <jet-input id="password_confirmation" type="password"  v-model="form.password_confirmation" autocomplete="new-password" />
                <jet-input-error :message="form.errors.password_confirmation" class="mt-2" />
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
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
        },

        data() {
            return {
                form: this.$inertia.form({
                    current_password: '',
                    password: '',
                    password_confirmation: '',
                }),
            }
        },

        methods: {
            updatePassword() {
                this.form.put(route('user-password.update'), {
                    errorBag: 'updatePassword',
                    preserveScroll: true,
                    onSuccess: () => this.form.reset(),
                    onError: () => {
                        if (this.form.errors.password) {
                            this.form.reset('password', 'password_confirmation')
                            this.$refs.password.focus()
                        }

                        if (this.form.errors.current_password) {
                            this.form.reset('current_password')
                            this.$refs.current_password.focus()
                        }
                    }
                })
            },
        },
    }
</script>
