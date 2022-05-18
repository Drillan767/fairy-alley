<template>
    <div class="p-20 md:p-5 bg-white border-b border-gray-200">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Ces services vous sont suggérés :</h2>

        <div class="grid grid-cols-5 gap-3 mt-4">
            <div v-for="(service, i) in suggestions" class="bg-gray-50 md:bg-white md:shadow-xl rounded-lg mb-6" :key="i">
                <a :href="route('pages.show', {slug: service.page.slug})">
                    <div class="md:flex-shrink-0">
                        <img :src="service.thumbnail.url" :alt="service.title" class="object-cover h-full w-full rounded-lg rounded-b-none">
                    </div>
                </a>

                <div class="py-1">
                    <div class="p-4">
                        <h3 class="truncate font-bold mb-2 md:mt-4 text-2xl text-gray-800 tracking-normal">
                            {{ service.title }}
                        </h3>
                    </div>

                    <div class="justify-end p-2">
                        <a :href="route('pages.show', {slug: service.page.slug})" class="subscribe">
                            Demande d'inscription
                        </a>
                        <a :href="route('pages.show', {slug: service.page.slug})" class="more">
                            En savoir plus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['suggestions']
}
</script>

<style>
.subscribe, .more {
    @apply text-white rounded-full px-2 py-1 block text-center mb-4;
}

.subscribe {
    @apply bg-purple-500 hover:bg-purple-600;
}

.more {
    @apply bg-green-500 hover:bg-green-600
}
</style>
