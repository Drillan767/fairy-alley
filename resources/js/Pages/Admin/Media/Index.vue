<template>
    <admin-layout title="Pages">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Listes des {{ type }}
            </h1>
        </template>
        <div class="py-12">
            <!-- component -->
            <div class="overflow-x-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert" v-if="flash.success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="ml-5">{{ flash.success }}</p>
                    </div>
                    <div class="flex justify-end mb-5">
                        <span @click="showModal = true" class="btn btn-primary">
                            Uploader des fichiers
                        </span>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <div class="carousel">
                                <div class="carousel__slide">

                                    <template v-for="file in files">
                                        <a :href="file.src" data-fancybox="gallery" v-if="type === 'photos'">
                                            <img :src="file.src" :alt="file.src">
                                        </a>
                                        <template v-if="type === 'videos'">
                                            <div data-fancybox="dialog" data-src="#video-content" class="cursor-pointer w-1/5">
                                                <svg
                                                    class="m-16"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        clip-rule="evenodd"
                                                        d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM12 23C18.0751 23 23 18.0751 23 12C23 5.92487 18.0751 1 12 1C5.92487 1 1 5.92487 1 12C1 18.0751 5.92487 23 12 23Z"
                                                        fill="currentColor"
                                                    />
                                                    <path d="M16 12L10 16.3301V7.66987L16 12Z" fill="currentColor" />
                                                </svg>
                                                <p class="text-center">{{ file.src.split(/[\\/]/).pop() }}</p>
                                            </div>

                                            <div id="video-content" style="display: none; max-width: 500px" v-if="type">
                                                <video :src="file.src" controls></video>
                                            </div>
                                        </template>

                                        <template v-if="type === 'musiques'">
                                            <div data-fancybox="dialog" data-src="#music-content" class="cursor-pointer w-1/5">
                                                <svg
                                                    class="m-16"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        clip-rule="evenodd"
                                                        d="M22 6.00086C22 3.54932 19.8148 1.6746 17.3918 2.04737L10.3918 3.1243C8.44044 3.4245 7 5.1035 7 7.07778V15.8407C6.54537 15.6248 6.0368 15.5039 5.5 15.5039C3.567 15.5039 2 17.0709 2 19.0039C2 20.9369 3.567 22.5039 5.5 22.5039C7.43296 22.5039 8.99994 20.937 9 19.004V7.07778C9 6.09064 9.72022 5.25114 10.6959 5.10104L17.6959 4.02412C18.9074 3.83773 20 4.77509 20 6.00086V12.8407C19.5454 12.6248 19.0368 12.5039 18.5 12.5039C16.567 12.5039 15 14.0709 15 16.0039C15 17.9369 16.567 19.5039 18.5 19.5039C20.433 19.5039 21.9999 17.937 22 16.004V6.00086ZM20 16.0039C20 15.1755 19.3284 14.5039 18.5 14.5039C17.6716 14.5039 17 15.1755 17 16.0039C17 16.8323 17.6716 17.5039 18.5 17.5039C19.3284 17.5039 19.9999 16.8323 20 16.0039ZM7 19.0039C7 18.1755 6.32843 17.5039 5.5 17.5039C4.67157 17.5039 4 18.1755 4 19.0039C4 19.8323 4.67157 20.5039 5.5 20.5039C6.32839 20.5039 6.99994 19.8323 7 19.0039Z"
                                                        fill="currentColor"
                                                    />
                                                </svg>
                                                <p class="text-center">{{ file.src.split(/[\\/]/).pop() }}</p>
                                            </div>

                                            <div id="music-content" style="display: none; max-width: 500px" v-if="type">
                                                <audio :src="file.src" controls></audio>
                                            </div>
                                        </template>

                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Form :show="showModal" @close="closeModal" />

    </admin-layout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Form from "./Form.vue";
import { Fancybox } from "https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.25/dist/fancybox.esm.js";
import "@fancyapps/ui/dist/fancybox.css";

import {onMounted, ref} from "vue";

export default {
    title () {
        return `Liste des ${this.type}`
    },

    props: {
        files: Array,
        type: String,
        flash: {
            type: Object,
            required: false,
        }
    },
    components: {
        AdminLayout,
        Form,
    },

    setup() {
        const index = ref(null);
        const showModal = ref(false)

        onMounted(() => {
            Fancybox.bind('[data-fancybox="gallery"]', {
                caption: (fancybox, carousel, slide) => `${slide.index + 1} / ${carousel.slides.length}`
            })

            Fancybox.bind('[data-fancybox="video-gallery"]', {
                caption: (fancybox, carousel, slide) => `${slide.index + 1} / ${carousel.slides.length}`
            })
        });

        const closeModal = () => showModal.value = false;

        return {
            index,
            showModal,
            closeModal
        }
    }
}
</script>

<style lang="scss" scoped>
.carousel__slide {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    width: 100%;

    a {
        width: 20%;
    }
}
</style>
