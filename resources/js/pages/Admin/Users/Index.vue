<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/components/admin/AdminLayout.vue';

defineProps({
    users: { type: Array, required: true },
});

const userPendingDeletion = ref(null);

function destroy(user) {
    router.delete(`/admin/users/${user.id}`, {
        preserveScroll: true,
        onFinish: () => (userPendingDeletion.value = null),
    });
}
</script>

<template>
    <Head title="Konta – panel administratora">
        <meta head-key="description" name="description" content="Zarządzanie kontami administratorów Aurelice Jewellery." />
        <meta head-key="robots" name="robots" content="noindex, nofollow" />
    </Head>

    <AdminLayout>
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-[11px] tracking-[0.3em] uppercase text-burgundy/60">Dostęp do panelu</p>
                <h1 class="mt-3 font-display text-4xl md:text-5xl">Konta</h1>
            </div>
            <Link
                href="/admin/users/create"
                class="bg-burgundy px-6 py-3 text-xs tracking-[0.25em] text-cream uppercase transition-opacity hover:opacity-90"
            >
                Dodaj konto
            </Link>
        </div>

        <ul class="mt-12 divide-y divide-burgundy/10 border-y border-burgundy/10">
            <li v-for="user in users" :key="user.id" class="flex flex-wrap items-center gap-4 py-4">
                <div class="flex min-w-48 flex-1 flex-col gap-1">
                    <Link :href="`/admin/users/${user.id}/edit`" class="text-sm hover:underline">
                        {{ user.name }}
                        <span v-if="user.is_current" class="ml-2 text-[10px] tracking-[0.2em] uppercase text-burgundy/60">(Ty)</span>
                    </Link>
                    <span class="text-xs font-light text-burgundy/60">
                        {{ user.username }}<template v-if="user.email"> · {{ user.email }}</template>
                    </span>
                </div>

                <div class="flex items-center gap-4 text-[11px] tracking-[0.2em] uppercase">
                    <Link :href="`/admin/users/${user.id}/edit`" class="underline">Edytuj</Link>
                    <template v-if="!user.is_current">
                        <template v-if="userPendingDeletion === user.id">
                            <button type="button" class="font-medium underline" @click="destroy(user)">Na pewno usunąć?</button>
                            <button type="button" class="text-burgundy/60 underline" @click="userPendingDeletion = null">Anuluj</button>
                        </template>
                        <button v-else type="button" class="text-burgundy/60 underline" @click="userPendingDeletion = user.id">Usuń</button>
                    </template>
                </div>
            </li>
        </ul>
    </AdminLayout>
</template>
