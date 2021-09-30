<template>
    <jet-form-section @submitted="updateProfileCoordinates">
        <template #title>
            Coordonnées de l'utilisateur
        </template>

        <template #description>
            Mettez à jour vos coordonnées pour que l'on puisse facilement vous joindre.
        </template>

        <template #form>
            <!-- Address -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="address1" value="Adresse" />
                <jet-input id="address1" type="text" class="mt-1 block w-full" v-model="form.address1" autocomplete="address1" />
                <jet-input-error :message="form.errors.address1" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="address2" value="Complément d'adresse" />
                <jet-input id="address2" type="text" class="mt-1 block w-full" v-model="form.address2" autocomplete="address2" />
                <jet-input-error :message="form.errors.address2" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="zipcode" value="Code postal" />
                <jet-input id="zipcode" type="number" class="mt-1 block w-full" v-model="form.zipcode" autocomplete="zipcode" />
                <jet-input-error :message="form.errors.zipcode" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="city" value="Ville" />
                <jet-input id="city" type="text" class="mt-1 block w-full" v-model="form.city" autocomplete="city" />
                <jet-input-error :message="form.errors.city" class="mt-2" />
            </div>

            <!-- Phone -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="phone" value="Numéro de téléphone fixe" />
                <jet-input id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" />
                <jet-input-error :message="form.errors.phone" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="pro" value="Téléphone portable" />
                <jet-input id="pro" type="text" class="mt-1 block w-full" v-model="form.pro" />
                <jet-input-error :message="form.errors.pro" class="mt-2" />
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

    data () {
        return {
            form: this.$inertia.form({
                address1: this.user.address1,
                address2: this.user.address2,
                zipcode: this.user.zipcode,
                city: this.user.city,
                phone: this.user.phone,
                pro: this.user.pro,
            }),
        }
    },

    methods: {
        updateProfileCoordinates() {
            this.form.post(route('user-profile-coordinates.update', {user: this.user.id}), {
                errorBag: 'updateProfileCoordinates',
                preserveScroll: true,
            });
        }
    }
}
</script>
