<script setup>
import ImagePlaceholder from './ImagePlaceholder.vue';
import SectionHeading from './SectionHeading.vue';

const products = [
    { name: 'Naszyjnik Perła Barokowa', price: '420 zł', tone: 'light' },
    { name: 'Kolczyki Turmalin', price: '290 zł', tone: 'warm' },
    { name: 'Bransoletka Kwarc Różowy', price: '260 zł', tone: 'light' },
    { name: 'Pierścionek Księżyc', price: '340 zł', tone: 'warm' },
];
</script>

<template>
    <section id="nowosci" class="border-t border-burgundy/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-12 px-4 py-20 md:px-8 md:py-28">
            <SectionHeading
                eyebrow="Nowości"
                title="Fresh arrivals"
                text="Nowe formy, naturalne kamienie i ręcznie wykańczane detale. Odkryj najnowsze projekty tworzone w naszej pracowni."
            />

            <ul class="grid grid-cols-2 gap-x-4 gap-y-10 lg:grid-cols-4 lg:gap-x-6">
                <li v-for="product in products" :key="product.name">
                    <a href="#" class="group flex flex-col gap-3">
                        <div class="aspect-square overflow-hidden bg-white">
                            <div class="h-full w-full transition-transform duration-700 group-hover:scale-105">
                                <ImagePlaceholder :tone="product.tone" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-1 text-center">
                            <span class="text-xs tracking-wide">{{ product.name }}</span>
                            <span class="text-xs font-light text-burgundy/70">{{ product.price }}</span>
                        </div>
                    </a>
                </li>
            </ul>

            <div class="flex justify-center">
                <a
                    href="#"
                    class="border border-burgundy px-8 py-3 text-[11px] font-medium tracking-[0.25em] uppercase transition-colors hover:bg-burgundy hover:text-cream"
                >
                    Sprawdź
                </a>
            </div>
        </div>
    </section>
</template>
