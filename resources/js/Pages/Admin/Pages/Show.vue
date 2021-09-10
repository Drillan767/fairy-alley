<template>
    <admin-layout title="Pages">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Listes des pages
            </h2>
        </template>

        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="mb-5" :class="{'flex justify-between': canEdit}">
                        <a :href="route('pages.index')" class="btn btn-primary">
                            Retour
                        </a>

                        <Link :href="route('pages.edit', {id: page.id})" class="btn" v-if="canEdit">
                            Editer
                        </Link>

                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <h1 class="text-2xl font-bold text-center mb-5">{{ page.title }}</h1>
                            <div v-html="page.content"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>

</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {Link} from "@inertiajs/inertia-vue3";

export default {
    components: {
        AdminLayout,
        Link,
    },

    props: {
        'page': {
            type: Object,
            required: true,
        },
        'user': {
            type: Object,
            required: false,
        }
    },

    computed: {
        canEdit () {
            return this.user?.roles.filter((role) => role.name === 'administrator').length > 0;
        }
    }
}
</script>
