<script setup>
import ImagePlaceholder from './ImagePlaceholder.vue';
</script>

<template>
    <section id="o-nas" class="bg-burgundy text-cream">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-20 md:grid-cols-2 md:px-8 md:py-28 lg:gap-20">
            <div class="aspect-[4/5] md:order-2">
                <ImagePlaceholder label="Pracownia" tone="dark" />
            </div>
            <div class="flex flex-col gap-6 md:order-1">
                <p class="text-[11px] font-medium tracking-[0.3em] uppercase">O marce</p>
                <h2 class="font-display text-3xl leading-tight md:text-5xl">Biżuteria, która żyje z Tobą</h2>
                <p class="max-w-md text-sm font-light leading-relaxed text-cream/80">
                    Każdy element powstaje ręcznie w naszej pracowni. Wybieramy naturalne kamienie
                    i perły o niepowtarzalnych kształtach, aby każda sztuka była jedyna w swoim rodzaju.
                </p>
                <a
                    href="#"
                    class="w-fit border border-cream px-8 py-3 text-[11px] font-medium tracking-[0.25em] uppercase transition-colors hover:bg-cream hover:text-burgundy"
                >
                    Poznaj nas
                </a>
            </div>
        </div>
    </section>
</template>
