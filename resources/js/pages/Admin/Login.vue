<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import BrandLogo from '@/components/BrandLogo.vue';

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/admin/login', {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="Logowanie do panelu">
        <meta head-key="description" name="description" content="Logowanie do panelu administratora Aurelice Jewellery." />
        <meta head-key="robots" name="robots" content="noindex, nofollow" />
    </Head>

    <div class="flex min-h-screen flex-col">
        <header class="flex justify-center bg-burgundy py-8 text-cream">
            <BrandLogo />
        </header>

        <main class="flex flex-1 items-start justify-center px-4 py-16">
            <section class="w-full max-w-sm" aria-labelledby="login-heading">
                <p class="text-center text-[11px] tracking-[0.3em] uppercase text-burgundy/60">Panel administratora</p>
                <h1 id="login-heading" class="mt-3 text-center font-display text-4xl">Zaloguj się</h1>

                <form class="mt-10 flex flex-col gap-6" @submit.prevent="submit">
                    <div class="flex flex-col gap-2">
                        <label for="username" class="text-[11px] tracking-[0.2em] uppercase">Login</label>
                        <input
                            id="username"
                            v-model="form.username"
                            type="text"
                            name="username"
                            autocomplete="username"
                            autocapitalize="none"
                            spellcheck="false"
                            required
                            autofocus
                            class="border border-burgundy/30 bg-transparent px-4 py-3 text-sm outline-none focus:border-burgundy"
                            :aria-invalid="Boolean(form.errors.username)"
                            aria-describedby="username-error"
                        />
                        <p v-if="form.errors.username" id="username-error" class="text-xs text-burgundy" role="alert">
                            {{ form.errors.username }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="password" class="text-[11px] tracking-[0.2em] uppercase">Hasło</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            required
                            class="border border-burgundy/30 bg-transparent px-4 py-3 text-sm outline-none focus:border-burgundy"
                            :aria-invalid="Boolean(form.errors.password)"
                            aria-describedby="password-error"
                        />
                        <p v-if="form.errors.password" id="password-error" class="text-xs text-burgundy" role="alert">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <label class="flex items-center gap-3 text-xs font-light">
                        <input v-model="form.remember" type="checkbox" name="remember" class="size-4 accent-burgundy" />
                        Zapamiętaj mnie
                    </label>

                    <button
                        type="submit"
                        class="mt-2 bg-burgundy px-6 py-4 text-xs tracking-[0.25em] text-cream uppercase transition-opacity hover:opacity-90 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        Zaloguj
                    </button>
                </form>
            </section>
        </main>
    </div>
</template>
