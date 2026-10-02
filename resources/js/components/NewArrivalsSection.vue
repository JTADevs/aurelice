<script setup>
import { Link } from '@inertiajs/vue3';
import ProductCard from './ProductCard.vue';
import SectionHeading from './SectionHeading.vue';

defineProps({
    products: { type: Array, required: true },
});
</script>

<template>
    <section v-if="products.length" id="nowosci" class="border-t border-burgundy/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-12 px-4 py-20 md:px-8 md:py-28">
            <SectionHeading
                eyebrow="Nowości"
                title="Fresh arrivals"
                text="Nowe formy, naturalne kamienie i detale dopracowane ręcznie. Poznaj najnowsze projekty Aurelice i znajdź coś dla siebie."
            />

            <ul class="grid grid-cols-2 gap-x-4 gap-y-10 lg:grid-cols-4 lg:gap-x-6">
                <li v-for="product in products" :key="product.id">
                    <ProductCard :product="product" />
                </li>
            </ul>

            <div class="flex justify-center">
                <Link
                    href="/produkty"
                    class="border border-burgundy px-8 py-3 text-[11px] font-medium tracking-[0.25em] uppercase transition-colors hover:bg-burgundy hover:text-cream"
                >
                    Sprawdź
                </Link>
            </div>
        </div>
    </section>
</template>
