<script setup>
import { formatPrice } from '@/utils/formatPrice.js';
import ImagePlaceholder from './ImagePlaceholder.vue';

defineProps({
    product: { type: Object, required: true },
    headingLevel: { type: String, default: 'h3' },
});
</script>

<template>
    <!-- TODO: podlinkować kafelek do strony produktu, gdy powstanie -->
    <article class="group flex flex-col gap-3">
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
            <component :is="headingLevel" class="text-xs tracking-wide">{{ product.name }}</component>
            <span class="text-xs font-light text-burgundy/70">{{ formatPrice(product.price) }}</span>
        </div>
    </article>
</template>
