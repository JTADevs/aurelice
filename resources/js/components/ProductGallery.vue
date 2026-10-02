<script setup>
import { computed, nextTick, onBeforeUnmount, ref } from 'vue';
import ImagePlaceholder from './ImagePlaceholder.vue';

const props = defineProps({
    images: { type: Array, required: true },
    productName: { type: String, required: true },
});

const SWIPE_THRESHOLD = 50;

const selectedIndex = ref(0);
const isPreviewOpen = ref(false);
const closeButton = ref(null);
const touchStartX = ref(null);

const selectedImage = computed(() => props.images[selectedIndex.value]);
const hasMultipleImages = computed(() => props.images.length > 1);

function showImage(index) {
    const count = props.images.length;
    selectedIndex.value = (index + count) % count;
}

function showPrevious() {
    showImage(selectedIndex.value - 1);
}

function showNext() {
    showImage(selectedIndex.value + 1);
}

function onTouchStart(event) {
    touchStartX.value = event.changedTouches[0].clientX;
}

function onTouchEnd(event) {
    if (touchStartX.value === null || !hasMultipleImages.value) {
        return;
    }

    const distance = event.changedTouches[0].clientX - touchStartX.value;
    touchStartX.value = null;

    if (Math.abs(distance) < SWIPE_THRESHOLD) {
        return;
    }

    distance > 0 ? showPrevious() : showNext();
}

function onPreviewKeydown(event) {
    if (event.key === 'Escape') {
        closePreview();
    } else if (event.key === 'ArrowLeft') {
        showPrevious();
    } else if (event.key === 'ArrowRight') {
        showNext();
    }
}

async function openPreview() {
    if (!selectedImage.value) {
        return;
    }

    isPreviewOpen.value = true;
    document.body.style.overflow = 'hidden';
    document.addEventListener('keydown', onPreviewKeydown);
    await nextTick();
    closeButton.value?.focus();
}

function closePreview() {
    isPreviewOpen.value = false;
    document.body.style.overflow = '';
    document.removeEventListener('keydown', onPreviewKeydown);
}

onBeforeUnmount(() => {
    if (isPreviewOpen.value) {
        closePreview();
    }
});
</script>

<template>
    <div class="flex w-full max-w-lg flex-col gap-4 justify-self-center md:justify-self-end">
        <div class="group relative aspect-[4/5] overflow-hidden bg-white" @touchstart.passive="onTouchStart" @touchend="onTouchEnd">
            <button
                v-if="selectedImage"
                type="button"
                class="block h-full w-full cursor-zoom-in"
                aria-label="Powiększ zdjęcie"
                @click="openPreview"
            >
                <img
                    :key="selectedImage.id"
                    :src="selectedImage.url"
                    :alt="`${productName} – zdjęcie ${selectedIndex + 1} z ${images.length}`"
                    :width="selectedImage.width"
                    :height="selectedImage.height"
                    fetchpriority="high"
                    class="h-full w-full object-cover"
                />
            </button>
            <ImagePlaceholder v-else />

            <template v-if="hasMultipleImages">
                <button
                    type="button"
                    class="absolute top-1/2 left-3 flex size-10 -translate-y-1/2 items-center justify-center bg-cream/85 text-burgundy transition-opacity hover:bg-cream md:opacity-0 md:group-hover:opacity-100 md:focus-visible:opacity-100"
                    aria-label="Poprzednie zdjęcie"
                    @click="showPrevious"
                >
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5l-7 7 7 7" />
                    </svg>
                </button>
                <button
                    type="button"
                    class="absolute top-1/2 right-3 flex size-10 -translate-y-1/2 items-center justify-center bg-cream/85 text-burgundy transition-opacity hover:bg-cream md:opacity-0 md:group-hover:opacity-100 md:focus-visible:opacity-100"
                    aria-label="Następne zdjęcie"
                    @click="showNext"
                >
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <span class="absolute right-3 bottom-3 bg-cream/85 px-2 py-1 text-[10px] tracking-[0.2em]" aria-hidden="true">
                    {{ selectedIndex + 1 }} / {{ images.length }}
                </span>
            </template>
        </div>

        <ul v-if="hasMultipleImages" class="grid grid-cols-5 gap-2" aria-label="Zdjęcia produktu">
            <li v-for="(image, index) in images" :key="image.id">
                <button
                    type="button"
                    class="block aspect-square w-full overflow-hidden border transition-opacity"
                    :class="index === selectedIndex ? 'border-burgundy' : 'border-transparent opacity-70 hover:opacity-100'"
                    :aria-label="`Pokaż zdjęcie ${index + 1}`"
                    :aria-pressed="index === selectedIndex"
                    @click="showImage(index)"
                >
                    <img
                        :src="image.url"
                        :alt="`${productName} – miniatura ${index + 1}`"
                        :width="image.width"
                        :height="image.height"
                        loading="lazy"
                        class="h-full w-full object-cover"
                    />
                </button>
            </li>
        </ul>

        <div
            v-if="isPreviewOpen"
            class="fixed inset-0 z-50 flex flex-col bg-burgundy/95 text-cream"
            role="dialog"
            aria-modal="true"
            :aria-label="`Podgląd zdjęć: ${productName}`"
            @click.self="closePreview"
        >
            <div class="flex items-center justify-between px-4 py-4 md:px-8">
                <span class="text-[11px] tracking-[0.25em] uppercase">{{ selectedIndex + 1 }} / {{ images.length }}</span>
                <button ref="closeButton" type="button" class="p-2" aria-label="Zamknij podgląd" @click="closePreview">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>

            <div class="relative flex min-h-0 flex-1 items-center justify-center px-4 md:px-20" @click.self="closePreview" @touchstart.passive="onTouchStart" @touchend="onTouchEnd">
                <img
                    :key="selectedImage.id"
                    :src="selectedImage.url"
                    :alt="`${productName} – zdjęcie ${selectedIndex + 1} z ${images.length}`"
                    :width="selectedImage.width"
                    :height="selectedImage.height"
                    class="max-h-full max-w-full object-contain"
                />

                <template v-if="hasMultipleImages">
                    <button
                        type="button"
                        class="absolute top-1/2 left-2 flex size-12 -translate-y-1/2 items-center justify-center hover:bg-cream/10 md:left-6"
                        aria-label="Poprzednie zdjęcie"
                        @click="showPrevious"
                    >
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5l-7 7 7 7" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        class="absolute top-1/2 right-2 flex size-12 -translate-y-1/2 items-center justify-center hover:bg-cream/10 md:right-6"
                        aria-label="Następne zdjęcie"
                        @click="showNext"
                    >
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </template>
            </div>

            <ul v-if="hasMultipleImages" class="flex justify-center gap-2 overflow-x-auto px-4 py-4" aria-label="Miniatury w podglądzie">
                <li v-for="(image, index) in images" :key="image.id" class="shrink-0">
                    <button
                        type="button"
                        class="block size-14 overflow-hidden border md:size-16"
                        :class="index === selectedIndex ? 'border-cream' : 'border-transparent opacity-60 hover:opacity-100'"
                        :aria-label="`Pokaż zdjęcie ${index + 1}`"
                        :aria-pressed="index === selectedIndex"
                        @click="showImage(index)"
                    >
                        <img :src="image.url" alt="" :width="image.width" :height="image.height" loading="lazy" class="h-full w-full object-cover" />
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>
