<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/components/admin/AdminLayout.vue';

defineProps({
    productCount: { type: Number, required: true },
    publishedProductCount: { type: Number, required: true },
});
</script>

<template>
    <Head title="Pulpit – panel administratora">
        <meta head-key="description" name="description" content="Panel administratora Aurelice Jewellery." />
        <meta head-key="robots" name="robots" content="noindex, nofollow" />
    </Head>

    <AdminLayout>
        <p class="text-[11px] tracking-[0.3em] uppercase text-burgundy/60">Panel administratora</p>
        <h1 class="mt-3 font-display text-4xl md:text-5xl">Witaj, {{ $page.props.auth.user.name }}</h1>

        <section class="mt-12 flex flex-wrap items-end justify-between gap-6 border border-burgundy/15 p-8" aria-labelledby="products-heading">
            <div>
                <h2 id="products-heading" class="font-display text-2xl">Produkty</h2>
                <p class="mt-3 text-sm font-light text-burgundy/70">
                    W sklepie: {{ productCount }}, w tym opublikowanych: {{ publishedProductCount }}.
                </p>
            </div>
            <Link
                href="/admin/products"
                class="border border-burgundy px-6 py-3 text-[11px] tracking-[0.25em] uppercase transition-colors hover:bg-burgundy hover:text-cream"
            >
                Zarządzaj produktami
            </Link>
        </section>
    </AdminLayout>
</template>
