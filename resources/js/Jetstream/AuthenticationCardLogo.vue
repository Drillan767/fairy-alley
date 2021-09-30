<template>
    <Link href="/">
        <img :src="logo" alt="logo" />
    </Link>
</template>

<script>

import { Link } from '@inertiajs/inertia-vue3';
import logo from '@/Static/logo.png'

export default {
    components: {
        Link,
    },

    data () {
        return {
            logo,
        }
    }
}
</script>
