<template>
    <component :is="roleList.includes('subscriber') ? 'UserLayout' : 'AdminLayout'" title="Éditez vos informations">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Éditez vos informations
            </h1>
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
    </component>
</template>

<script>
    import UserLayout from '@/Layouts/UserLayout.vue'
    import AdminLayout from "@/Layouts/AdminLayout.vue";
    import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
    import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
    import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'
    import UpdateProfileCoordinatesForm from "@/Pages/Profile/Partials/UpdateProfileCoordinatesForm.vue"
    import UpdateProfileOtherDataForm from "@/Pages/Profile/Partials/UpdateProfileOtherDataForm.vue";

    export default {
        title: 'Éditez vos informations',
        props: ['sessions'],

        components: {
            UpdateProfileOtherDataForm,
            UserLayout,
            AdminLayout,
            DeleteUserForm,
            JetSectionBorder,
            UpdatePasswordForm,
            UpdateProfileInformationForm,
            UpdateProfileCoordinatesForm
        },

        computed: {
            roleList() {
                let roles = [];
                this.$page.props.user.roles.forEach((role) => {
                    roles.push(role.name);
                })

                return roles;
            }
        }
    }
</script>
