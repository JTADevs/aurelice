<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    variant: { type: String, default: 'logo' },
});

const variants = {
    logo: { src: '/images/brand/aurelice-logo.webp', width: 800, height: 303, class: 'h-12 w-auto md:h-16' },
    sygnet: { src: '/images/brand/aurelice-sygnet.webp', width: 400, height: 473, class: 'h-20 w-auto' },
};

const logo = variants[props.variant];
</script>

<template>
    <Link href="/" class="inline-flex" aria-label="Aurelice Jewellery – strona główna">
        <img :src="logo.src" :width="logo.width" :height="logo.height" :class="logo.class" alt="Logo Aurelice Jewellery" />
    </Link>
</template>
