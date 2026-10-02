<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/components/admin/AdminLayout.vue';
import { formatPrice } from '@/utils/formatPrice.js';

defineProps({
    products: { type: Object, required: true },
});

const productPendingDeletion = ref(null);

function destroy(product) {
    router.delete(`/admin/products/${product.id}`, {
        preserveScroll: true,
        onFinish: () => (productPendingDeletion.value = null),
    });
}
</script>

<template>
    <Head title="Produkty – panel administratora">
        <meta head-key="description" name="description" content="Zarządzanie produktami Aurelice Jewellery." />
        <meta head-key="robots" name="robots" content="noindex, nofollow" />
    </Head>

    <AdminLayout>
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-[11px] tracking-[0.3em] uppercase text-burgundy/60">Sklep</p>
                <h1 class="mt-3 font-display text-4xl md:text-5xl">Produkty</h1>
            </div>
            <Link
                href="/admin/products/create"
                class="bg-burgundy px-6 py-3 text-xs tracking-[0.25em] text-cream uppercase transition-opacity hover:opacity-90"
            >
                Dodaj produkt
            </Link>
        </div>

        <p v-if="products.data.length === 0" class="mt-12 border border-burgundy/15 p-8 text-sm font-light">
            Nie ma jeszcze żadnych produktów. Dodaj pierwszy, a pojawi się w sekcji „Nowości” na stronie głównej.
        </p>

        <ul v-else class="mt-12 divide-y divide-burgundy/10 border-y border-burgundy/10">
            <li v-for="product in products.data" :key="product.id" class="flex flex-wrap items-center gap-4 py-4">
                <img
                    v-if="product.thumbnail"
                    :src="product.thumbnail"
                    :alt="product.name"
                    width="64"
                    height="64"
                    loading="lazy"
                    class="size-16 object-cover"
                />
                <div v-else class="flex size-16 items-center justify-center bg-burgundy/5 text-[9px] tracking-[0.15em] uppercase text-burgundy/40">
                    Brak
                </div>

                <div class="flex min-w-40 flex-1 flex-col gap-1">
                    <Link :href="`/admin/products/${product.id}/edit`" class="text-sm hover:underline">{{ product.name }}</Link>
                    <span class="text-xs font-light text-burgundy/60">{{ product.category }} · {{ formatPrice(product.price) }}</span>
                </div>

                <span
                    class="px-3 py-1 text-[10px] tracking-[0.2em] uppercase"
                    :class="product.is_published ? 'bg-burgundy text-cream' : 'border border-burgundy/30 text-burgundy/60'"
                >
                    {{ product.is_published ? 'Opublikowany' : 'Ukryty' }}
                </span>

                <div class="flex items-center gap-4 text-[11px] tracking-[0.2em] uppercase">
                    <Link :href="`/admin/products/${product.id}/edit`" class="underline">Edytuj</Link>
                    <template v-if="productPendingDeletion === product.id">
                        <button type="button" class="font-medium underline" @click="destroy(product)">Na pewno usunąć?</button>
                        <button type="button" class="text-burgundy/60 underline" @click="productPendingDeletion = null">Anuluj</button>
                    </template>
                    <button v-else type="button" class="text-burgundy/60 underline" @click="productPendingDeletion = product.id">Usuń</button>
                </div>
            </li>
        </ul>

        <nav v-if="products.last_page > 1" class="mt-8 flex justify-between text-[11px] tracking-[0.2em] uppercase" aria-label="Strony listy produktów">
            <Link v-if="products.prev_page_url" :href="products.prev_page_url" class="underline">Poprzednia</Link>
            <span v-else />
            <span class="text-burgundy/60">Strona {{ products.current_page }} z {{ products.last_page }}</span>
            <Link v-if="products.next_page_url" :href="products.next_page_url" class="underline">Następna</Link>
            <span v-else />
        </nav>
    </AdminLayout>
</template>
