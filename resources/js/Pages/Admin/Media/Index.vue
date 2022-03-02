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
                                            <div data-fancybox="dialog" data-src="#dialog-content" class="cursor-pointer w-1/5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                    <path d="M463.1 32h-416C21.49 32-.0001 53.49-.0001 80v352c0 26.51 21.49 48 47.1 48h416c26.51 0 48-21.49 48-48v-352C511.1 53.49 490.5 32 463.1 32zM111.1 408c0 4.418-3.582 8-8 8H55.1c-4.418 0-8-3.582-8-8v-48c0-4.418 3.582-8 8-8h47.1c4.418 0 8 3.582 8 8L111.1 408zM111.1 280c0 4.418-3.582 8-8 8H55.1c-4.418 0-8-3.582-8-8v-48c0-4.418 3.582-8 8-8h47.1c4.418 0 8 3.582 8 8V280zM111.1 152c0 4.418-3.582 8-8 8H55.1c-4.418 0-8-3.582-8-8v-48c0-4.418 3.582-8 8-8h47.1c4.418 0 8 3.582 8 8L111.1 152zM351.1 400c0 8.836-7.164 16-16 16H175.1c-8.836 0-16-7.164-16-16v-96c0-8.838 7.164-16 16-16h160c8.836 0 16 7.162 16 16V400zM351.1 208c0 8.836-7.164 16-16 16H175.1c-8.836 0-16-7.164-16-16v-96c0-8.838 7.164-16 16-16h160c8.836 0 16 7.162 16 16V208zM463.1 408c0 4.418-3.582 8-8 8h-47.1c-4.418 0-7.1-3.582-7.1-8l0-48c0-4.418 3.582-8 8-8h47.1c4.418 0 8 3.582 8 8V408zM463.1 280c0 4.418-3.582 8-8 8h-47.1c-4.418 0-8-3.582-8-8v-48c0-4.418 3.582-8 8-8h47.1c4.418 0 8 3.582 8 8V280zM463.1 152c0 4.418-3.582 8-8 8h-47.1c-4.418 0-8-3.582-8-8l0-48c0-4.418 3.582-8 7.1-8h47.1c4.418 0 8 3.582 8 8V152z"/>
                                                </svg>
                                                {{ file.src.split(/[\\/]/).pop() }}
                                            </div>

                                            <div id="dialog-content" style="display: none; max-width: 500px" v-if="type">
                                                <video :src="file.src" controls></video>
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
