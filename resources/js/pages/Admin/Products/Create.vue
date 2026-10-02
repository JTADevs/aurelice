<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/components/admin/AdminLayout.vue';
import ProductForm from '@/components/admin/ProductForm.vue';

defineProps({
    categories: { type: Array, required: true },
});

const form = useForm({
    name: '',
    category: '',
    price: '',
    fulfillment_days: 1,
    description: '',
    is_published: true,
    images: [],
});

function submit() {
    form.post('/admin/products', { forceFormData: true });
}
</script>

<template>
    <Head title="Nowy produkt – panel administratora">
        <meta head-key="description" name="description" content="Dodawanie produktu Aurelice Jewellery." />
        <meta head-key="robots" name="robots" content="noindex, nofollow" />
    </Head>

    <AdminLayout>
        <Link href="/admin/products" class="text-[11px] tracking-[0.2em] uppercase text-burgundy/60 hover:text-burgundy">← Produkty</Link>
        <h1 class="mt-4 mb-10 font-display text-4xl md:text-5xl">Nowy produkt</h1>

        <ProductForm :form="form" :categories="categories" submit-label="Dodaj produkt" @submit="submit" />
    </AdminLayout>
</template>
