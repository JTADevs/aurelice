<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import AnnouncementBar from '@/components/AnnouncementBar.vue';
import ProductCard from '@/components/ProductCard.vue';
import SiteFooter from '@/components/SiteFooter.vue';
import SiteHeader from '@/components/SiteHeader.vue';

const props = defineProps({
    category: { type: Object, default: null },
    categories: { type: Array, required: true },
    products: { type: Object, required: true },
    canonicalUrl: { type: String, required: true },
    ogImage: { type: String, default: null },
});

const descriptions = {
    naszyjniki: 'Ręcznie wykonane naszyjniki Aurelice z naturalnych kamieni i pereł. Subtelne formy tworzone w naszej pracowni.',
    bransoletki: 'Ręcznie wykonane bransoletki Aurelice z naturalnych kamieni i pereł. Poznaj najnowsze projekty z naszej pracowni.',
    komplety: 'Komplety biżuterii Aurelice – naszyjniki i bransoletki z naturalnych kamieni i pereł, wykonane ręcznie.',
};

const heading = computed(() => props.category?.label ?? 'Nowości');
const pageTitle = computed(() => (props.category ? `${props.category.label} – ręcznie wykonana biżuteria` : 'Nowości – wszystkie produkty'));
const description = computed(
    () =>
        descriptions[props.category?.value] ??
        'Wszystkie produkty Aurelice od najnowszych: ręcznie wykonane naszyjniki, bransoletki i komplety z naturalnych kamieni i pereł.',
);

const filters = computed(() => [
    { label: 'Nowości', href: '/produkty', isActive: props.category === null },
    ...props.categories.map((category) => ({
        label: category.label,
        href: `/produkty/${category.value}`,
        isActive: props.category?.value === category.value,
    })),
]);
</script>

<template>
    <Head :title="pageTitle">
        <meta head-key="description" name="description" :content="description" />
        <link head-key="canonical" rel="canonical" :href="canonicalUrl" />
        <meta head-key="og:type" property="og:type" content="website" />
        <meta head-key="og:title" property="og:title" :content="`${pageTitle} – Aurelice Jewellery`" />
        <meta head-key="og:description" property="og:description" :content="description" />
        <meta head-key="og:url" property="og:url" :content="canonicalUrl" />
        <meta v-if="ogImage" head-key="og:image" property="og:image" :content="ogImage" />
    </Head>

    <AnnouncementBar />
    <SiteHeader />

    <main class="mx-auto flex max-w-7xl flex-col gap-10 px-4 py-12 md:px-8 md:py-16">
        <nav aria-label="Okruszki" class="text-[11px] tracking-[0.2em] uppercase text-burgundy/60">
            <ol class="flex flex-wrap gap-2">
                <li><Link href="/" class="hover:text-burgundy">Strona główna</Link></li>
                <li aria-hidden="true">/</li>
                <li>
                    <Link v-if="category" href="/produkty" class="hover:text-burgundy">Produkty</Link>
                    <span v-else aria-current="page" class="text-burgundy">Produkty</span>
                </li>
                <template v-if="category">
                    <li aria-hidden="true">/</li>
                    <li aria-current="page" class="text-burgundy">{{ category.label }}</li>
                </template>
            </ol>
        </nav>

        <div class="flex flex-col items-center gap-4 text-center">
            <h1 class="font-display text-4xl md:text-5xl">{{ heading }}</h1>
            <p class="max-w-2xl text-sm font-light leading-relaxed text-burgundy/80">{{ description }}</p>
        </div>

        <nav aria-label="Filtruj produkty" class="border-y border-burgundy/10">
            <ul class="flex flex-wrap justify-center gap-x-8 gap-y-2 py-4 text-[11px] font-medium tracking-[0.22em] uppercase">
                <li v-for="filter in filters" :key="filter.href">
                    <Link
                        :href="filter.href"
                        preserve-scroll
                        class="border-b pb-1 transition-colors"
                        :class="filter.isActive ? 'border-burgundy' : 'border-transparent text-burgundy/60 hover:text-burgundy'"
                        :aria-current="filter.isActive ? 'page' : undefined"
                    >
                        {{ filter.label }}
                    </Link>
                </li>
            </ul>
        </nav>

        <section aria-labelledby="products-heading">
            <h2 id="products-heading" class="sr-only">Lista produktów</h2>

            <p v-if="products.data.length === 0" class="py-16 text-center text-sm font-light text-burgundy/70">
                W tej kategorii nie ma jeszcze produktów. Zajrzyj wkrótce.
            </p>

            <ul v-else class="grid grid-cols-2 gap-x-4 gap-y-10 md:grid-cols-3 lg:grid-cols-4 lg:gap-x-6">
                <li v-for="product in products.data" :key="product.id">
                    <ProductCard :product="product" />
                </li>
            </ul>
        </section>

        <nav
            v-if="products.last_page > 1"
            aria-label="Strony produktów"
            class="flex items-center justify-between border-t border-burgundy/10 pt-6 text-[11px] tracking-[0.2em] uppercase"
        >
            <Link v-if="products.prev_page_url" :href="products.prev_page_url" class="underline">Poprzednia</Link>
            <span v-else />
            <span class="text-burgundy/60">Strona {{ products.current_page }} z {{ products.last_page }}</span>
            <Link v-if="products.next_page_url" :href="products.next_page_url" class="underline">Następna</Link>
            <span v-else />
        </nav>
    </main>

    <SiteFooter />
</template>
