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
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <div class="carousel">
                                <div class="carousel__slide">

                                    <template v-for="file in files">
                                        <a :href="file.src" data-fancybox="gallery" v-if="type === 'photos'">
                                            <img :src="file.src" :alt="file.src">
                                        </a>
                                        <a :href="file.src" data-fancybox="video-gallery" data-width="640" data-height="360" v-if="type === 'videos'">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M463.1 32h-416C21.49 32-.0001 53.49-.0001 80v352c0 26.51 21.49 48 47.1 48h416c26.51 0 48-21.49 48-48v-352C511.1 53.49 490.5 32 463.1 32zM111.1 408c0 4.418-3.582 8-8 8H55.1c-4.418 0-8-3.582-8-8v-48c0-4.418 3.582-8 8-8h47.1c4.418 0 8 3.582 8 8L111.1 408zM111.1 280c0 4.418-3.582 8-8 8H55.1c-4.418 0-8-3.582-8-8v-48c0-4.418 3.582-8 8-8h47.1c4.418 0 8 3.582 8 8V280zM111.1 152c0 4.418-3.582 8-8 8H55.1c-4.418 0-8-3.582-8-8v-48c0-4.418 3.582-8 8-8h47.1c4.418 0 8 3.582 8 8L111.1 152zM351.1 400c0 8.836-7.164 16-16 16H175.1c-8.836 0-16-7.164-16-16v-96c0-8.838 7.164-16 16-16h160c8.836 0 16 7.162 16 16V400zM351.1 208c0 8.836-7.164 16-16 16H175.1c-8.836 0-16-7.164-16-16v-96c0-8.838 7.164-16 16-16h160c8.836 0 16 7.162 16 16V208zM463.1 408c0 4.418-3.582 8-8 8h-47.1c-4.418 0-7.1-3.582-7.1-8l0-48c0-4.418 3.582-8 8-8h47.1c4.418 0 8 3.582 8 8V408zM463.1 280c0 4.418-3.582 8-8 8h-47.1c-4.418 0-8-3.582-8-8v-48c0-4.418 3.582-8 8-8h47.1c4.418 0 8 3.582 8 8V280zM463.1 152c0 4.418-3.582 8-8 8h-47.1c-4.418 0-8-3.582-8-8l0-48c0-4.418 3.582-8 7.1-8h47.1c4.418 0 8 3.582 8 8V152z"/>
                                            </svg>
                                            {{ file.src.split(/[\\/]/).pop() }}
                                        </a>
                                    </template>
                                </div>
                            </div>
                            <div class="images-wrapper">
<!--                                <div v-for="(file, i) in files" :key="i" class="media" @click="index = i">
                                    <div class="file" v-if="['videos', 'musiques'].includes(type)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="" fill="none" viewBox="0 0 24 24" stroke="currentColor" v-if="type === 'videos'">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="" fill="none" viewBox="0 0 24 24" stroke="currentColor" v-if="type === 'musique'">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                        </svg>
                                    </div>

                                    <img :src="file" alt="img" v-if="type === 'photos'">
                                    <p class="title">
                                        <template v-if="type === 'photos'">
&lt;!&ndash;                                            {{ file.split(/[\\/]/).pop() }}&ndash;&gt;
                                            Eul' fichier
                                        </template>
                                        <template v-else>
                                            Eul' fichier
&lt;!&ndash;                                            {{ file }}&ndash;&gt;
                                        </template>
                                    </p>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </admin-layout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Fancybox } from "https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.25/dist/fancybox.esm.js";
import "@fancyapps/ui/dist/fancybox.css";

import {onMounted, ref} from "vue";

export default {
    title () {
        return `Liste des ${this.type}`
    },
    props: ['files', 'type'],
    components: {
        AdminLayout,
    },

    setup() {
        const index = ref(null);

        onMounted(() => {
            Fancybox.bind('[data-fancybox="gallery"]', {
                caption: (fancybox, carousel, slide) => `${slide.index + 1} / ${carousel.slides.length}`
            })

            Fancybox.bind('[data-fancybox="video-gallery"]', {
                caption: (fancybox, carousel, slide) => `${slide.index + 1} / ${carousel.slides.length}`
            })
        });

        return {
            index
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
