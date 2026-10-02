<script setup>
defineProps({
    form: { type: Object, required: true },
    submitLabel: { type: String, required: true },
    isPasswordOptional: { type: Boolean, default: false },
    requiresCurrentPassword: { type: Boolean, default: false },
});

const emit = defineEmits(['submit']);

const inputClass = 'border border-burgundy/30 bg-transparent px-4 py-3 text-sm outline-none focus:border-burgundy';
const labelClass = 'text-[11px] tracking-[0.2em] uppercase';
</script>

<template>
    <form class="flex max-w-2xl flex-col gap-8" @submit.prevent="emit('submit')">
        <div class="grid gap-8 md:grid-cols-2">
            <div class="flex flex-col gap-2 md:col-span-2">
                <label for="name" :class="labelClass">Imię i nazwisko</label>
                <input id="name" v-model="form.name" type="text" required maxlength="255" autocomplete="name" :class="inputClass" />
                <p v-if="form.errors.name" class="text-xs" role="alert">{{ form.errors.name }}</p>
            </div>

            <div class="flex flex-col gap-2">
                <label for="username" :class="labelClass">Login</label>
                <input
                    id="username"
                    v-model="form.username"
                    type="text"
                    required
                    maxlength="50"
                    autocomplete="off"
                    autocapitalize="none"
                    spellcheck="false"
                    :class="inputClass"
                />
                <p v-if="form.errors.username" class="text-xs" role="alert">{{ form.errors.username }}</p>
            </div>

            <div class="flex flex-col gap-2">
                <label for="email" :class="labelClass">E-mail (opcjonalnie)</label>
                <input id="email" v-model="form.email" type="email" maxlength="255" autocomplete="off" :class="inputClass" />
                <p v-if="form.errors.email" class="text-xs" role="alert">{{ form.errors.email }}</p>
            </div>
        </div>

        <fieldset class="grid gap-8 border-t border-burgundy/10 pt-8 md:grid-cols-2">
            <legend class="sr-only">Hasło</legend>
            <p v-if="isPasswordOptional" class="text-xs font-light text-burgundy/60 md:col-span-2">
                Zostaw pola hasła puste, jeśli nie chcesz go zmieniać. Nowe hasło musi mieć co najmniej 12 znaków.
            </p>

            <div v-if="requiresCurrentPassword" class="flex flex-col gap-2 md:col-span-2">
                <label for="current_password" :class="labelClass">Obecne hasło (tylko przy zmianie hasła)</label>
                <input
                    id="current_password"
                    v-model="form.current_password"
                    type="password"
                    autocomplete="current-password"
                    :class="inputClass"
                />
                <p v-if="form.errors.current_password" class="text-xs" role="alert">{{ form.errors.current_password }}</p>
            </div>

            <div class="flex flex-col gap-2">
                <label for="password" :class="labelClass">{{ isPasswordOptional ? 'Nowe hasło' : 'Hasło' }}</label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    :required="!isPasswordOptional"
                    minlength="12"
                    autocomplete="new-password"
                    :class="inputClass"
                />
                <p v-if="form.errors.password" class="text-xs" role="alert">{{ form.errors.password }}</p>
            </div>

            <div class="flex flex-col gap-2">
                <label for="password_confirmation" :class="labelClass">Powtórz hasło</label>
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    :required="!isPasswordOptional"
                    autocomplete="new-password"
                    :class="inputClass"
                />
            </div>
        </fieldset>

        <div class="flex flex-wrap items-center gap-4">
            <button
                type="submit"
                class="bg-burgundy px-8 py-4 text-xs tracking-[0.25em] text-cream uppercase transition-opacity hover:opacity-90 disabled:opacity-50"
                :disabled="form.processing"
            >
                {{ submitLabel }}
            </button>
            <slot name="actions" />
        </div>
    </form>
</template>
