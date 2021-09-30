<template>
    <Link :href="'/'">
        <img src="/img/logo.png" alt="logo" />
    </Link>
</template>

<script>
    import { Link } from '@inertiajs/inertia-vue3';

    export default {
        components: {
            Link,
        },
    }
</script>
