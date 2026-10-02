<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import BrandLogo from './BrandLogo.vue';

const navigation = [
    { label: 'Nowości', href: '/produkty' },
    { label: 'Naszyjniki', href: '/produkty/naszyjniki' },
    { label: 'Bransoletki', href: '/produkty/bransoletki' },
    { label: 'Komplety', href: '/produkty/komplety' },
    { label: 'O nas', href: '/#o-nas' },
];

const page = usePage();
const isMenuOpen = ref(false);

function isActive(item) {
    return page.url.split('?')[0] === item.href;
}
</script>

<template>
    <header class="bg-burgundy text-cream">
        <div class="mx-auto grid max-w-7xl grid-cols-3 items-center px-4 py-5 md:px-8 md:py-7">
            <button
                type="button"
                class="justify-self-start p-2 lg:hidden"
                :aria-expanded="isMenuOpen"
                aria-controls="mobile-menu"
                aria-label="Menu"
                @click="isMenuOpen = !isMenuOpen"
            >
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                    <path v-if="!isMenuOpen" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                    <path v-else stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
            <span class="hidden lg:block" />

            <div class="justify-self-center">
                <BrandLogo />
            </div>

            <div class="flex items-center gap-1 justify-self-end md:gap-3">
                <a href="#" class="p-2" aria-label="Szukaj">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                        <circle cx="11" cy="11" r="7" />
                        <path stroke-linecap="round" d="M20 20l-4-4" />
                    </svg>
                </a>
                <a href="#" class="hidden p-2 md:block" aria-label="Konto">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                        <circle cx="12" cy="8" r="4" />
                        <path stroke-linecap="round" d="M4 21c0-4 3.5-7 8-7s8 3 8 7" />
                    </svg>
                </a>
                <a href="#" class="p-2" aria-label="Koszyk">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                        <path d="M5 8h14l-1 12H6L5 8z" />
                        <path stroke-linecap="round" d="M9 8V6a3 3 0 016 0v2" />
                    </svg>
                </a>
            </div>
        </div>

        <nav class="hidden border-t border-cream/15 lg:block" aria-label="Kategorie">
            <ul class="mx-auto flex max-w-7xl justify-center gap-10 px-8 py-4 text-[11px] font-medium tracking-[0.22em] uppercase">
                <li v-for="item in navigation" :key="item.label">
                    <Link
                        :href="item.href"
                        class="border-b pb-1 transition-colors hover:text-cream"
                        :class="isActive(item) ? 'border-cream text-cream' : 'border-transparent text-cream/80'"
                        :aria-current="isActive(item) ? 'page' : undefined"
                    >
                        {{ item.label }}
                    </Link>
                </li>
            </ul>
        </nav>

        <nav v-show="isMenuOpen" id="mobile-menu" class="border-t border-cream/15 lg:hidden" aria-label="Menu mobilne">
            <ul class="flex flex-col px-6 py-4 text-xs font-medium tracking-[0.22em] uppercase">
                <li v-for="item in navigation" :key="item.label">
                    <Link
                        :href="item.href"
                        class="block py-3"
                        :class="isActive(item) ? 'text-cream' : 'text-cream/70'"
                        :aria-current="isActive(item) ? 'page' : undefined"
                        @click="isMenuOpen = false"
                    >
                        {{ item.label }}
                    </Link>
                </li>
            </ul>
        </nav>
    </header>
</template>
