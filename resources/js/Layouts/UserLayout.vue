<template>
    <div>
        <Head>
            <title>{{ title ? `${title} | L'allée des Fées` : 'L\'allée des Fées' }}</title>
        </Head>

        <jet-banner />

        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center" @click="home">
                                <Link href="/">
                                    <jet-application-mark class="block h-9 w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <jet-nav-link :href="route('profile.index')" :active="route().current('profile.index')">
                                    Accueil
                                </jet-nav-link>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <jet-dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                {{ $page.props.user.full_name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Gestion du compte
                                        </div>

                                        <jet-dropdown-link :href="route('profile.show')">
                                            Profil
                                        </jet-dropdown-link>

                                        <div class="border-t border-gray-100"></div>

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <jet-dropdown-link as="button">
                                                Déconnexion
                                            </jet-dropdown-link>
                                        </form>
                                    </template>
                                </jet-dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <jet-responsive-nav-link href="#">
                            Accueil
                        </jet-responsive-nav-link>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div>
                                <div class="font-medium text-base text-gray-800">{{ $page.props.user.full_name }}</div>
                                <div class="font-medium text-sm text-gray-500">{{ $page.props.user.email }}</div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <jet-responsive-nav-link :href="route('profile.show')" :active="route().current('profile.show')">
                                Profil
                            </jet-responsive-nav-link>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <jet-responsive-nav-link as="button">
                                    Déconnexion
                                </jet-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"></slot>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script>
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'
import JetBanner from '@/Jetstream/Banner.vue'
import JetDropdown from '@/Jetstream/Dropdown.vue'
import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
import JetNavLink from '@/Jetstream/NavLink.vue'
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';
import Swal from "sweetalert2";
import axios from "axios";
import {Inertia} from "@inertiajs/inertia";

export default {
    props: {
        title: String,
    },

    components: {
        Head,
        JetApplicationMark,
        JetBanner,
        JetDropdown,
        JetDropdownLink,
        JetNavLink,
        JetResponsiveNavLink,
        Link,
    },

    data() {
        return {
            showingNavigationDropdown: false,
        }
    },

    mounted() {
        const user = this.$inertia.page.props.user;

        const urlParams = new URLSearchParams(window.location.search);

        /**

         */

        if (urlParams.get('password')) {
            Swal.fire({
                icon: 'error',
                title: 'Il est conseillé de changer votre mot de passe',
                showLoaderOnConfirm: true,
                html:
                `
                    <div class="text-left">
                        <div class="flex flex-col">
                            <label for="swal-input1" class="text-sm">Nouveau mot de passe</label>
                            <input type="password" placeholder="Nouveau mot de passe" id="swal-input1">
                        </div>
                        <div class="mt-4 flex flex-col">
                            <label for="swal-input2" class="text-sm">Répéter le mot de passe</label>
                            <input type="password" id="swal-input2" placeholder="Répéter le mot de passe">
                        </div>

                        <p class="text-sm mt-6">
                            Le mot de passe doit avoir au moins 8 caractères, un chiffre et une lettre en majuscule.
                            Les caractères spéciaux sont autorisés mais non requis.
                        </p>
                    </div>

                `,
                confirmButtonText: 'Enregistrer',
                preConfirm: () => {
                    const password = Swal
                        .getPopup()
                        .querySelector('[id="swal-input1"]').value;

                    const confirmedPassword = Swal
                        .getPopup()
                        .querySelector('[id="swal-input2"]').value;

                    if (!password || !confirmedPassword) {
                        Swal.showValidationMessage(`Les deux champs sont requis`)
                    }

                    return axios
                        .post(route('change.password'), {password: password, password_confirmation: confirmedPassword})
                        .catch((error) => {
                            Swal.showValidationMessage(error.response.data)
                        })
                        .then(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Mot de passe modifié.',
                                timer: 3000,
                                text: 'Mot de passe modifié avec succès !'
                            })
                                .then(() => Inertia.get(route('profile.index')))
                        })
                }
            })
        }
    },

    methods: {
        home() {
            this.$inertia.get(route('redirect.home'))
        },

        logout() {
            this.$inertia.post(route('logout'));
        },
    }
}
</script>
