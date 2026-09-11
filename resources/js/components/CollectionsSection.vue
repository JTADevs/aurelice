<script setup>
import ImagePlaceholder from './ImagePlaceholder.vue';
import SectionHeading from './SectionHeading.vue';

const collections = [
    { name: 'Naszyjniki', tone: 'warm' },
    { name: 'Kolczyki', tone: 'light' },
    { name: 'Bransoletki', tone: 'warm' },
    { name: 'Pierścionki', tone: 'light' },
];
</script>

<template>
    <section id="kolekcje" class="mx-auto flex max-w-7xl flex-col gap-12 px-4 py-20 md:px-8 md:py-28">
        <SectionHeading eyebrow="Kolekcje" title="Wybierz swoją formę" />

        <ul class="grid grid-cols-2 gap-4 md:gap-6 lg:grid-cols-4">
            <li v-for="collection in collections" :key="collection.name">
                <a href="#" class="group flex flex-col gap-3">
                    <div class="aspect-[3/4] overflow-hidden">
                        <div class="h-full w-full transition-transform duration-700 group-hover:scale-105">
                            <ImagePlaceholder :label="collection.name" :tone="collection.tone" />
                        </div>
                    </div>
                    <span class="text-center text-[11px] font-medium tracking-[0.25em] uppercase">{{ collection.name }}</span>
                </a>
            </li>
        </ul>
    </section>
</template>
