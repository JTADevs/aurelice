<script setup>
import { Link } from '@inertiajs/vue3';
import { formatPrice } from '@/utils/formatPrice.js';
import ImagePlaceholder from './ImagePlaceholder.vue';

defineProps({
    product: { type: Object, required: true },
    headingLevel: { type: String, default: 'h3' },
});
</script>

<template>
    <article>
        <Link :href="`/produkt/${product.slug}`" class="group flex flex-col gap-3">
            <div class="aspect-square overflow-hidden bg-white">
                <div class="h-full w-full transition-transform duration-700 group-hover:scale-105">
                    <img
                        v-if="product.image"
                        :src="product.image.url"
                        :alt="`${product.name} – ${product.category.toLowerCase()} Aurelice Jewellery`"
                        :width="product.image.width"
                        :height="product.image.height"
                        loading="lazy"
                        class="h-full w-full object-cover"
                    />
                    <ImagePlaceholder v-else />
                </div>
            </div>
            <div class="flex flex-col gap-1 text-center">
                <component :is="headingLevel" class="text-xs tracking-wide group-hover:underline">{{ product.name }}</component>
                <span class="text-xs font-light text-burgundy/70">{{ formatPrice(product.price) }}</span>
            </div>
        </Link>
    </article>
</template>
