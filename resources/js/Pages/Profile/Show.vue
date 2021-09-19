<template>
    <user-layout title="Éditez vos informations">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Éditez vos informations
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div>
                    <update-profile-information-form :user="$page.props.user" />

                    <jet-section-border />
                </div>

                <div>
                    <update-profile-coordinates-form :user="$page.props.user" />

                    <jet-section-border />
                </div>

                <div>
                    <update-profile-other-data-form :user="$page.props.user" />

                    <jet-section-border />
                </div>

                <div>
                    <update-password-form class="mt-10 sm:mt-0" />

                    <jet-section-border />
                </div>

                <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                    <jet-section-border />

                    <delete-user-form class="mt-10 sm:mt-0" />
                </template>
            </div>
        </div>
    </user-layout>
</template>

<script>
    import UserLayout from '@/Layouts/UserLayout.vue'
    import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
    import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
    import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'
    import UpdateProfileCoordinatesForm from "@/Pages/Profile/Partials/UpdateProfileCoordinatesForm.vue"
    import UpdateProfileOtherDataForm from "@/Pages/Profile/Partials/UpdateProfileOtherDataForm.vue";

    export default {
        props: ['sessions'],

        components: {
            UpdateProfileOtherDataForm,
            UserLayout,
            DeleteUserForm,
            JetSectionBorder,
            UpdatePasswordForm,
            UpdateProfileInformationForm,
            UpdateProfileCoordinatesForm
        },
    }
</script>
