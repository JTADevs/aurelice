<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/components/admin/AdminLayout.vue';
import UserForm from '@/components/admin/UserForm.vue';

const props = defineProps({
    user: { type: Object, required: true },
});

const isConfirmingDeletion = ref(false);

const form = useForm({
    name: props.user.name,
    username: props.user.username,
    email: props.user.email ?? '',
    current_password: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.put(`/admin/users/${props.user.id}`, {
        preserveScroll: true,
        onFinish: () => form.reset('current_password', 'password', 'password_confirmation'),
    });
}

function destroy() {
    router.delete(`/admin/users/${props.user.id}`);
}
</script>

<template>
    <Head :title="`${user.is_current ? 'Moje konto' : user.name} – panel administratora`">
        <meta head-key="description" name="description" content="Edycja konta administratora Aurelice Jewellery." />
        <meta head-key="robots" name="robots" content="noindex, nofollow" />
    </Head>

    <AdminLayout>
        <Link href="/admin/users" class="text-[11px] tracking-[0.2em] uppercase text-burgundy/60 hover:text-burgundy">← Konta</Link>
        <h1 class="mt-4 mb-10 font-display text-4xl md:text-5xl">{{ user.is_current ? 'Moje konto' : user.name }}</h1>

        <UserForm
            :form="form"
            submit-label="Zapisz zmiany"
            is-password-optional
            :requires-current-password="user.is_current"
            @submit="submit"
        >
            <template v-if="!user.is_current" #actions>
                <div class="ml-auto flex items-center gap-4 text-[11px] tracking-[0.2em] uppercase">
                    <template v-if="isConfirmingDeletion">
                        <button type="button" class="font-medium underline" @click="destroy">Na pewno usunąć konto?</button>
                        <button type="button" class="text-burgundy/60 underline" @click="isConfirmingDeletion = false">Anuluj</button>
                    </template>
                    <button v-else type="button" class="text-burgundy/60 underline" @click="isConfirmingDeletion = true">Usuń konto</button>
                </div>
            </template>
        </UserForm>
    </AdminLayout>
</template>
