<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/components/admin/AdminLayout.vue';
import UserForm from '@/components/admin/UserForm.vue';

const form = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/admin/users', {
        onError: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <Head title="Nowe konto – panel administratora">
        <meta head-key="description" name="description" content="Dodawanie konta administratora Aurelice Jewellery." />
        <meta head-key="robots" name="robots" content="noindex, nofollow" />
    </Head>

    <AdminLayout>
        <Link href="/admin/users" class="text-[11px] tracking-[0.2em] uppercase text-burgundy/60 hover:text-burgundy">← Konta</Link>
        <h1 class="mt-4 mb-10 font-display text-4xl md:text-5xl">Nowe konto</h1>

        <UserForm :form="form" submit-label="Dodaj konto" @submit="submit" />
    </AdminLayout>
</template>
