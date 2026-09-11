<script setup>
defineProps({
    eyebrow: { type: String, default: '' },
    title: { type: String, required: true },
    text: { type: String, default: '' },
});
</script>

<template>
    <div class="mx-auto flex max-w-2xl flex-col items-center gap-4 text-center">
        <p v-if="eyebrow" class="text-[11px] font-medium tracking-[0.3em] uppercase">{{ eyebrow }}</p>
        <h2 class="font-display text-3xl md:text-4xl">{{ title }}</h2>
        <p v-if="text" class="text-sm font-light leading-relaxed text-burgundy/80">{{ text }}</p>
    </div>
</template>
