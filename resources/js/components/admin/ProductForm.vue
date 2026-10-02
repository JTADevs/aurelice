<script setup>
import { computed, onBeforeUnmount, ref } from 'vue';
import { prepareImageForUpload } from '@/utils/prepareImageForUpload.js';

const props = defineProps({
    form: { type: Object, required: true },
    categories: { type: Array, required: true },
    submitLabel: { type: String, required: true },
});

const emit = defineEmits(['submit']);

const previews = ref([]);
const isPreparingImages = ref(false);

const imageErrors = computed(() =>
    Object.entries(props.form.errors)
        .filter(([key]) => key === 'images' || key.startsWith('images.'))
        .map(([, message]) => message),
);

async function addImages(event) {
    const files = Array.from(event.target.files ?? []);
    event.target.value = '';

    if (files.length === 0) {
        return;
    }

    isPreparingImages.value = true;

    try {
        for (const file of files) {
            const prepared = await prepareImageForUpload(file);
            props.form.images.push(prepared);
            previews.value.push({ file: prepared, url: URL.createObjectURL(prepared) });
        }
    } finally {
        isPreparingImages.value = false;
    }
}

function removeImage(index) {
    URL.revokeObjectURL(previews.value[index].url);
    previews.value.splice(index, 1);
    props.form.images.splice(index, 1);
}

function clearPreviews() {
    previews.value.forEach((preview) => URL.revokeObjectURL(preview.url));
    previews.value = [];
}

onBeforeUnmount(clearPreviews);

defineExpose({ clearPreviews });

const inputClass = 'border border-burgundy/30 bg-transparent px-4 py-3 text-sm outline-none focus:border-burgundy';
const labelClass = 'text-[11px] tracking-[0.2em] uppercase';
</script>

<template>
    <form class="flex flex-col gap-8" @submit.prevent="emit('submit')">
        <div class="grid gap-8 md:grid-cols-2">
            <div class="flex flex-col gap-2 md:col-span-2">
                <label for="name" :class="labelClass">Nazwa</label>
                <input id="name" v-model="form.name" type="text" required maxlength="120" :class="inputClass" />
                <p v-if="form.errors.name || form.errors.slug" class="text-xs" role="alert">{{ form.errors.name ?? form.errors.slug }}</p>
            </div>

            <div class="flex flex-col gap-2">
                <label for="category" :class="labelClass">Kategoria</label>
                <select id="category" v-model="form.category" required :class="inputClass">
                    <option value="" disabled>Wybierz kategorię</option>
                    <option v-for="category in categories" :key="category.value" :value="category.value">{{ category.label }}</option>
                </select>
                <p v-if="form.errors.category" class="text-xs" role="alert">{{ form.errors.category }}</p>
            </div>

            <div class="flex flex-col gap-2">
                <label for="price" :class="labelClass">Cena (zł)</label>
                <input id="price" v-model="form.price" type="text" inputmode="decimal" required placeholder="np. 249 lub 249,99" :class="inputClass" />
                <p v-if="form.errors.price" class="text-xs" role="alert">{{ form.errors.price }}</p>
            </div>

            <div class="flex flex-col gap-2">
                <label for="fulfillment_days" :class="labelClass">Czas realizacji zamówienia (dni)</label>
                <input
                    id="fulfillment_days"
                    v-model.number="form.fulfillment_days"
                    type="number"
                    inputmode="numeric"
                    min="1"
                    max="60"
                    step="1"
                    required
                    :class="inputClass"
                />
                <p v-if="form.errors.fulfillment_days" class="text-xs" role="alert">{{ form.errors.fulfillment_days }}</p>
            </div>

            <div class="flex flex-col gap-2 md:col-span-2">
                <label for="description" :class="labelClass">Opis</label>
                <textarea id="description" v-model="form.description" rows="6" maxlength="5000" :class="inputClass" />
                <p v-if="form.errors.description" class="text-xs" role="alert">{{ form.errors.description }}</p>
            </div>
        </div>

        <slot name="existing-images" />

        <div class="flex flex-col gap-3">
            <span :class="labelClass">Dodaj zdjęcia</span>
            <label
                class="flex cursor-pointer flex-col items-center gap-2 border border-dashed border-burgundy/30 px-6 py-8 text-center text-xs font-light transition-colors hover:border-burgundy"
            >
                <input type="file" accept="image/*" multiple class="sr-only" @change="addImages" />
                <span class="tracking-[0.2em] uppercase">Wybierz pliki</span>
                <span class="text-burgundy/60">Zdjęcia zostaną zmniejszone do 2000 px i zapisane jako WebP.</span>
            </label>
            <p v-if="isPreparingImages" class="text-xs text-burgundy/60" role="status">Przygotowuję zdjęcia…</p>
            <ul v-if="previews.length" class="grid grid-cols-3 gap-3 sm:grid-cols-5">
                <li v-for="(preview, index) in previews" :key="preview.url" class="flex flex-col gap-2">
                    <img :src="preview.url" :alt="`Nowe zdjęcie ${index + 1}`" class="aspect-square w-full object-cover" />
                    <button type="button" class="text-[10px] tracking-[0.2em] uppercase underline" @click="removeImage(index)">Usuń</button>
                </li>
            </ul>
            <p v-for="message in imageErrors" :key="message" class="text-xs" role="alert">{{ message }}</p>
        </div>

        <label class="flex items-center gap-3 text-sm">
            <input v-model="form.is_published" type="checkbox" class="size-4 accent-burgundy" />
            Opublikowany (widoczny w sklepie)
        </label>

        <div class="flex items-center gap-4">
            <button
                type="submit"
                class="bg-burgundy px-8 py-4 text-xs tracking-[0.25em] text-cream uppercase transition-opacity hover:opacity-90 disabled:opacity-50"
                :disabled="form.processing || isPreparingImages"
            >
                {{ submitLabel }}
            </button>
            <span v-if="form.progress" class="text-xs text-burgundy/60">Wysyłanie {{ form.progress.percentage }}%</span>
            <slot name="actions" />
        </div>
    </form>
</template>
