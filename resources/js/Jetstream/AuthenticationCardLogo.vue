<template>
    <Link :href="'/'">
        <img :src="`${$page.props.BASE_URL}/img/logo.png`" alt="logo" />
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
