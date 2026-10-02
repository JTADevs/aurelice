<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/components/admin/AdminLayout.vue';
import ProductForm from '@/components/admin/ProductForm.vue';

const props = defineProps({
    product: { type: Object, required: true },
    categories: { type: Array, required: true },
});

const productForm = ref(null);
const isConfirmingDeletion = ref(false);

function priceForInput(grosze) {
    return grosze % 100 === 0 ? String(grosze / 100) : (grosze / 100).toFixed(2).replace('.', ',');
}

const form = useForm({
    name: props.product.name,
    category: props.product.category,
    price: priceForInput(props.product.price),
    fulfillment_days: props.product.fulfillment_days,
    description: props.product.description ?? '',
    is_published: props.product.is_published,
    images: [],
});

function submit() {
    form.transform((data) => ({ ...data, _method: 'put' })).post(`/admin/products/${props.product.id}`, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.images = [];
            productForm.value?.clearPreviews();
        },
    });
}

function makeMainImage(image) {
    router.patch(`/admin/products/${props.product.id}/images/${image.id}`, {}, { preserveScroll: true });
}

function destroyImage(image) {
    router.delete(`/admin/products/${props.product.id}/images/${image.id}`, { preserveScroll: true });
}

function destroyProduct() {
    router.delete(`/admin/products/${props.product.id}`);
}
</script>

<template>
    <Head :title="`${product.name} – panel administratora`">
        <meta head-key="description" name="description" content="Edycja produktu Aurelice Jewellery." />
        <meta head-key="robots" name="robots" content="noindex, nofollow" />
    </Head>

    <AdminLayout>
        <Link href="/admin/products" class="text-[11px] tracking-[0.2em] uppercase text-burgundy/60 hover:text-burgundy">← Produkty</Link>
        <div class="mt-4 mb-10 flex flex-wrap items-end justify-between gap-4">
            <h1 class="font-display text-4xl md:text-5xl">{{ product.name }}</h1>
            <a
                v-if="product.is_published"
                :href="`/produkt/${product.slug}`"
                target="_blank"
                rel="noopener"
                class="text-[11px] tracking-[0.2em] uppercase underline"
            >
                Zobacz w sklepie
            </a>
        </div>

        <ProductForm ref="productForm" :form="form" :categories="categories" submit-label="Zapisz zmiany" @submit="submit">
            <template #existing-images>
                <section class="flex flex-col gap-3" aria-labelledby="images-heading">
                    <h2 id="images-heading" class="text-[11px] tracking-[0.2em] uppercase">Zdjęcia produktu</h2>
                    <p v-if="product.images.length === 0" class="text-xs font-light text-burgundy/60">Produkt nie ma jeszcze zdjęć.</p>
                    <ul v-else class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-6">
                        <li v-for="(image, index) in product.images" :key="image.id" class="flex flex-col gap-2">
                            <img
                                :src="image.url"
                                :alt="`${product.name} – zdjęcie ${index + 1}`"
                                :width="image.width"
                                :height="image.height"
                                loading="lazy"
                                class="aspect-square w-full object-cover"
                            />
                            <span v-if="index === 0" class="text-[10px] tracking-[0.2em] uppercase">Zdjęcie główne</span>
                            <button v-else type="button" class="text-left text-[10px] tracking-[0.2em] uppercase underline" @click="makeMainImage(image)">
                                Ustaw jako główne
                            </button>
                            <button type="button" class="text-left text-[10px] tracking-[0.2em] text-burgundy/60 uppercase underline" @click="destroyImage(image)">
                                Usuń
                            </button>
                        </li>
                    </ul>
                </section>
            </template>

            <template #actions>
                <div class="ml-auto flex items-center gap-4 text-[11px] tracking-[0.2em] uppercase">
                    <template v-if="isConfirmingDeletion">
                        <button type="button" class="font-medium underline" @click="destroyProduct">Na pewno usunąć produkt?</button>
                        <button type="button" class="text-burgundy/60 underline" @click="isConfirmingDeletion = false">Anuluj</button>
                    </template>
                    <button v-else type="button" class="text-burgundy/60 underline" @click="isConfirmingDeletion = true">Usuń produkt</button>
                </div>
            </template>
        </ProductForm>
    </AdminLayout>
</template>
