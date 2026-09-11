<script setup>
defineProps({
    size: { type: String, default: 'md' },
});

const sizes = {
    sm: { name: 'text-2xl', tag: 'text-[8px] tracking-[0.35em]' },
    md: { name: 'text-3xl md:text-4xl', tag: 'text-[9px] tracking-[0.4em]' },
    lg: { name: 'text-5xl md:text-6xl', tag: 'text-[11px] tracking-[0.45em]' },
};
</script>

<template>
    <a href="/" class="inline-flex flex-col items-center leading-none" aria-label="Aurelice Jewellery – strona główna">
        <span class="font-display" :class="sizes[size].name">Aurelice</span>
        <span class="mt-1 font-sans font-light uppercase" :class="sizes[size].tag">Jewellery</span>
    </a>
</template>
