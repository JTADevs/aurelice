<script setup>
import { ref } from 'vue';

const count = ref(0);
</script>

<template>
    <main class="flex min-h-screen flex-col items-center justify-center gap-6 bg-neutral-50 text-neutral-900">
        <h1 class="text-4xl font-semibold tracking-tight">Aurelice</h1>
        <p class="text-neutral-500">Laravel 13 + Vue 3 + MySQL</p>
        <button
            class="rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-700"
            @click="count++"
        >
            Kliknięto {{ count }} razy
        </button>
    </main>
</template>
