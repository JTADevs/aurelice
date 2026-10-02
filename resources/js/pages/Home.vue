<script setup>
import { Head } from '@inertiajs/vue3';
import AnnouncementBar from '@/components/AnnouncementBar.vue';
import BrandStorySection from '@/components/BrandStorySection.vue';
import CollectionsSection from '@/components/CollectionsSection.vue';
import HeroSection from '@/components/HeroSection.vue';
import NewArrivalsSection from '@/components/NewArrivalsSection.vue';
import NewsletterSection from '@/components/NewsletterSection.vue';
import SiteFooter from '@/components/SiteFooter.vue';
import SiteHeader from '@/components/SiteHeader.vue';

defineProps({
    collections: { type: Array, required: true },
    newArrivals: { type: Array, required: true },
});
</script>

<template>
    <Head title="Ręcznie wykonana biżuteria z naturalnych kamieni i pereł">
        <meta
            head-key="description"
            name="description"
            content="Aurelice Jewellery – ręcznie wykonana biżuteria z naturalnych kamieni i pereł. Naszyjniki, bransoletki i komplety tworzone w naszej pracowni."
        />
    </Head>
    <AnnouncementBar />
    <SiteHeader />
    <main>
        <HeroSection />
        <CollectionsSection :collections="collections" />
        <NewArrivalsSection :products="newArrivals" />
        <BrandStorySection />
        <NewsletterSection />
    </main>
    <SiteFooter />
</template>
