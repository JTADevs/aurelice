<script setup>
import { Link } from '@inertiajs/vue3';
import ImagePlaceholder from './ImagePlaceholder.vue';
import SectionHeading from './SectionHeading.vue';

defineProps({
    collections: { type: Array, required: true },
});
</script>

<template>
    <section id="kolekcje" class="mx-auto flex max-w-7xl flex-col gap-12 px-4 py-20 md:px-8 md:py-28">
        <SectionHeading eyebrow="Kolekcje" title="Wybierz swoją formę" />

        <ul class="mx-auto grid w-full max-w-5xl gap-4 sm:grid-cols-3 md:gap-6">
            <li v-for="(collection, index) in collections" :key="collection.value">
                <Link :href="`/produkty/${collection.value}`" class="group flex flex-col gap-3">
                    <div class="aspect-[3/4] overflow-hidden">
                        <div class="h-full w-full transition-transform duration-700 group-hover:scale-105">
                            <img
                                v-if="collection.image"
                                :src="collection.image.url"
                                :alt="`${collection.label} Aurelice Jewellery – ${collection.product}`"
                                :width="collection.image.width"
                                :height="collection.image.height"
                                loading="lazy"
                                class="h-full w-full object-cover"
                            />
                            <ImagePlaceholder v-else :label="collection.label" :tone="index % 2 ? 'light' : 'warm'" />
                        </div>
                    </div>
                    <span class="text-center text-[11px] font-medium tracking-[0.25em] uppercase">{{ collection.label }}</span>
                </Link>
            </li>
        </ul>
    </section>
</template>
