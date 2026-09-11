<script setup>
import ImagePlaceholder from './ImagePlaceholder.vue';
</script>

<template>
    <section class="relative min-h-[70vh] overflow-hidden md:min-h-[80vh]">
        <div class="absolute inset-0">
            <ImagePlaceholder label="Zdjęcie hero" />
        </div>
        <div class="absolute inset-0 bg-linear-to-t from-burgundy/45 via-burgundy/10 to-transparent" />

        <div class="relative mx-auto flex min-h-[70vh] max-w-7xl flex-col items-center justify-center gap-6 px-6 py-24 text-center text-cream md:min-h-[80vh] md:px-8">
            <p class="text-[11px] font-medium tracking-[0.3em] uppercase">Nowa kolekcja</p>
            <h1 class="max-w-3xl font-display text-4xl leading-tight md:text-6xl">
                Ręcznie wykonana biżuteria z naturalnych kamieni i pereł
            </h1>
            <p class="max-w-xl text-sm font-light leading-relaxed md:text-base">
                Minimalistyczne formy, które zmieniają się wraz z każdym gestem.
            </p>
            <a
                href="#kolekcje"
                class="mt-2 border border-cream px-8 py-3 text-[11px] font-medium tracking-[0.25em] uppercase transition-colors hover:bg-cream hover:text-burgundy"
            >
                Odkryj kolekcje
            </a>
        </div>
    </section>
</template>
