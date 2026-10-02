<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AnnouncementBar from '@/components/AnnouncementBar.vue';
import ProductCard from '@/components/ProductCard.vue';
import ProductGallery from '@/components/ProductGallery.vue';
import SiteFooter from '@/components/SiteFooter.vue';
import SiteHeader from '@/components/SiteHeader.vue';
import { formatFulfillmentTime } from '@/utils/formatFulfillmentTime.js';
import { formatPrice } from '@/utils/formatPrice.js';

defineProps({
    product: { type: Object, required: true },
    relatedProducts: { type: Array, required: true },
    canonicalUrl: { type: String, required: true },
    metaDescription: { type: String, required: true },
});
</script>

<template>
    <Head :title="`${product.name} – ${product.category.label}`">
        <meta head-key="description" name="description" :content="metaDescription" />
        <link head-key="canonical" rel="canonical" :href="canonicalUrl" />
        <meta head-key="og:type" property="og:type" content="product" />
        <meta head-key="og:title" property="og:title" :content="`${product.name} – Aurelice Jewellery`" />
        <meta head-key="og:description" property="og:description" :content="metaDescription" />
        <meta head-key="og:url" property="og:url" :content="canonicalUrl" />
        <meta v-if="product.images.length" head-key="og:image" property="og:image" :content="product.images[0].url" />
    </Head>

    <AnnouncementBar />
    <SiteHeader />

    <main class="mx-auto flex max-w-7xl flex-col gap-10 px-4 py-12 md:px-8 md:py-16">
        <nav aria-label="Okruszki" class="text-[11px] tracking-[0.2em] uppercase text-burgundy/60">
            <ol class="flex flex-wrap gap-2">
                <li><Link href="/" class="hover:text-burgundy">Strona główna</Link></li>
                <li aria-hidden="true">/</li>
                <li><Link href="/produkty" class="hover:text-burgundy">Produkty</Link></li>
                <li aria-hidden="true">/</li>
                <li><Link :href="`/produkty/${product.category.value}`" class="hover:text-burgundy">{{ product.category.label }}</Link></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page" class="text-burgundy">{{ product.name }}</li>
            </ol>
        </nav>

        <div class="grid gap-10 md:grid-cols-2 md:gap-16">
            <ProductGallery :images="product.images" :product-name="product.name" />

            <div class="flex flex-col gap-6 md:pt-6">
                <Link
                    :href="`/produkty/${product.category.value}`"
                    class="text-[11px] font-medium tracking-[0.3em] uppercase text-burgundy/60 hover:text-burgundy"
                >
                    {{ product.category.label }}
                </Link>
                <h1 class="font-display text-4xl leading-tight md:text-5xl">{{ product.name }}</h1>
                <p class="text-lg font-light">{{ formatPrice(product.price) }}</p>
                <p class="text-xs tracking-[0.15em] uppercase text-burgundy/70">
                    Czas realizacji zamówienia: <span class="font-medium text-burgundy">{{ formatFulfillmentTime(product.fulfillment_days) }}</span>
                </p>

                <section v-if="product.description" class="border-t border-burgundy/10 pt-6" aria-labelledby="description-heading">
                    <h2 id="description-heading" class="text-[11px] font-medium tracking-[0.25em] uppercase">Opis</h2>
                    <p class="mt-4 text-sm leading-relaxed font-light whitespace-pre-line text-burgundy/85">{{ product.description }}</p>
                </section>

                <p class="border-t border-burgundy/10 pt-6 text-xs font-light leading-relaxed text-burgundy/70">
                    Każdy egzemplarz wykonujemy ręcznie z naturalnych kamieni i pereł, dlatego może nieznacznie różnić się od zdjęć.
                </p>
            </div>
        </div>

        <section v-if="relatedProducts.length" class="border-t border-burgundy/10 pt-12" aria-labelledby="related-heading">
            <h2 id="related-heading" class="text-center font-display text-3xl md:text-4xl">Zobacz również</h2>
            <ul class="mt-10 grid grid-cols-2 gap-x-4 gap-y-10 lg:grid-cols-4 lg:gap-x-6">
                <li v-for="related in relatedProducts" :key="related.id">
                    <ProductCard :product="related" />
                </li>
            </ul>
        </section>
    </main>

    <SiteFooter />
</template>
