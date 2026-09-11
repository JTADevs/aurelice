<script setup>
import BrandLogo from './BrandLogo.vue';

const columns = [
    { title: 'Sklep', links: ['Nowości', 'Naszyjniki', 'Kolczyki', 'Bransoletki', 'Pierścionki'] },
    { title: 'Informacje', links: ['O nas', 'Dostawa i zwroty', 'Pielęgnacja biżuterii', 'Kontakt'] },
    { title: 'Social', links: ['Instagram', 'Facebook', 'Pinterest'] },
];

const year = new Date().getFullYear();
</script>

<template>
    <footer class="bg-burgundy text-cream">
        <div class="mx-auto flex max-w-7xl flex-col gap-14 px-4 py-16 md:px-8">
            <div class="grid gap-12 md:grid-cols-[1.5fr_repeat(3,1fr)]">
                <div class="flex flex-col items-start gap-4">
                    <BrandLogo size="sm" />
                    <p class="max-w-xs text-xs font-light leading-relaxed text-cream/70">
                        Ręcznie wykonana biżuteria z naturalnych kamieni i pereł.
                    </p>
                </div>
                <div v-for="column in columns" :key="column.title" class="flex flex-col gap-4">
                    <h3 class="text-[11px] font-medium tracking-[0.25em] uppercase">{{ column.title }}</h3>
                    <ul class="flex flex-col gap-2.5">
                        <li v-for="link in column.links" :key="link">
                            <a href="#" class="text-xs font-light text-cream/70 transition-colors hover:text-cream">{{ link }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col gap-3 border-t border-cream/15 pt-6 text-[10px] tracking-[0.15em] text-cream/50 uppercase sm:flex-row sm:justify-between">
                <span>© {{ year }} Aurelice Jewellery</span>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-cream">Regulamin</a>
                    <a href="#" class="hover:text-cream">Polityka prywatności</a>
                </div>
            </div>
        </div>
    </footer>
</template>
