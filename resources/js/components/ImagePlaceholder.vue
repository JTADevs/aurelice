<script setup>
defineProps({
    label: { type: String, default: '' },
    tone: { type: String, default: 'warm' },
});

const tones = {
    warm: 'from-[#e8dfd5] via-[#d9cbbd] to-[#c9b5a3]',
    light: 'from-[#efe9e3] via-[#e4dbd2] to-[#d6c8ba]',
    dark: 'from-[#5a1a26] via-[#440512] to-[#2d030c]',
};
</script>

<template>
    <div
        class="relative flex h-full w-full items-end justify-start overflow-hidden bg-linear-to-br"
        :class="tones[tone] ?? tones.warm"
        aria-hidden="true"
    >
        <span
            v-if="label"
            class="p-3 text-[10px] tracking-[0.2em] uppercase"
            :class="tone === 'dark' ? 'text-cream/50' : 'text-burgundy/40'"
        >
            {{ label }}
        </span>
    </div>
</template>
