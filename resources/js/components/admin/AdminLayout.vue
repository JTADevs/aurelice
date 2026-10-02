<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import BrandLogo from '@/components/BrandLogo.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const successMessage = computed(() => page.flash?.success);
const errorMessage = computed(() => page.flash?.error);

const navigation = [
    { label: 'Pulpit', href: '/admin', section: 'Admin/Dashboard' },
    { label: 'Produkty', href: '/admin/products', section: 'Admin/Products' },
    { label: 'Konta', href: '/admin/users', section: 'Admin/Users' },
];

function isActive(item) {
    return page.component.startsWith(item.section);
}
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <header class="bg-burgundy text-cream">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-4 md:px-8">
                <div class="flex items-center gap-4">
                    <BrandLogo />
                    <span class="hidden border-l border-cream/20 pl-4 text-[10px] tracking-[0.3em] uppercase sm:inline">Panel</span>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <Link :href="`/admin/users/${user.id}/edit`" class="hidden font-light text-cream/70 hover:text-cream md:inline" title="Moje konto">
                        {{ user.name }}
                    </Link>
                    <Link
                        href="/admin/logout"
                        method="post"
                        as="button"
                        class="border border-cream/40 px-4 py-2 tracking-[0.2em] uppercase transition-colors hover:bg-cream hover:text-burgundy"
                    >
                        Wyloguj
                    </Link>
                </div>
            </div>
            <nav class="border-t border-cream/15" aria-label="Panel administratora">
                <ul class="mx-auto flex max-w-7xl gap-8 px-4 md:px-8">
                    <li v-for="item in navigation" :key="item.href">
                        <Link
                            :href="item.href"
                            class="inline-block border-b py-3 text-[11px] tracking-[0.2em] uppercase transition-colors"
                            :class="isActive(item) ? 'border-cream' : 'border-transparent text-cream/60 hover:text-cream'"
                        >
                            {{ item.label }}
                        </Link>
                    </li>
                </ul>
            </nav>
        </header>

        <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-10 md:px-8 md:py-14">
            <p v-if="successMessage" class="mb-8 border border-burgundy/20 bg-white/60 px-4 py-3 text-sm" role="status">
                {{ successMessage }}
            </p>
            <p v-if="errorMessage" class="mb-8 border border-burgundy bg-burgundy px-4 py-3 text-sm text-cream" role="alert">
                {{ errorMessage }}
            </p>
            <slot />
        </main>
    </div>
</template>
