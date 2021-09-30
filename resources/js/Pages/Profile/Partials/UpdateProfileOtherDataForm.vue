<template>
    <jet-form-section @submitted="updateProfileOtherData">
        <template #title>
            Autres informations
        </template>

        <template #description>
            Inscrivez ici les informations qu'il vous semble important que l'organisatrice sache.
            Personne d'autre ne pourra les voir.
        </template>

        <template #form>
            <!-- Address -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="address1" value="Autres informations" />
                <wysiwyg v-model="form.other_data" />
                <jet-input-error :message="form.errors.other_data" class="mt-2" />
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
import JetInputError from '@/Jetstream/InputError.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Wysiwyg from '@/Jetstream/Wysiwyg.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'

export default {
    components: {
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        Wysiwyg,
    },

    props: ['user'],

    data () {
        return {
            form: this.$inertia.form({
                other_data: this.user.other_data,
            }),
        }
    },

    methods: {
        updateProfileOtherData() {
            this.form.post(route('user-profile-data.update', {user: this.user.id}), {
                errorBag: 'updateProfileOtherData',
                preserveScroll: true,
            });
        }
    }
}
</script>
