<script setup>
import { ref } from 'vue';

const email = ref('');
</script>

<template>
    <section class="mx-auto flex max-w-xl flex-col items-center gap-6 px-4 py-20 text-center md:py-28">
        <p class="text-[11px] font-medium tracking-[0.3em] uppercase">Newsletter</p>
        <h2 class="font-display text-3xl md:text-4xl">Dołącz do #AureliceFriends</h2>
        <p class="text-sm font-light leading-relaxed text-burgundy/80">
            Odbierz 10% rabatu na pierwsze zamówienie i bądź na bieżąco z nowościami.
        </p>
        <form class="flex w-full flex-col gap-3 sm:flex-row" @submit.prevent>
            <label class="sr-only" for="newsletter-email">Adres e-mail</label>
            <input
                id="newsletter-email"
                v-model="email"
                type="email"
                placeholder="Twój adres e-mail"
                class="flex-1 border border-burgundy/30 bg-transparent px-4 py-3 text-sm font-light placeholder:text-burgundy/40 focus:border-burgundy focus:outline-none"
            />
            <button
                type="submit"
                class="bg-burgundy px-8 py-3 text-[11px] font-medium tracking-[0.25em] text-cream uppercase transition-opacity hover:opacity-90"
            >
                Zapisz się
            </button>
        </form>
    </section>
</template>
