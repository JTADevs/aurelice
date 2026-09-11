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
</script>

<template>
    <Head title="Ręcznie wykonana biżuteria z naturalnych kamieni i pereł">
        <meta
            head-key="description"
            name="description"
            content="Aurelice Jewellery – ręcznie wykonana biżuteria z naturalnych kamieni i pereł. Naszyjniki, kolczyki, bransoletki i pierścionki tworzone w naszej pracowni."
        />
    </Head>
    <AnnouncementBar />
    <SiteHeader />
    <main>
        <HeroSection />
        <CollectionsSection />
        <NewArrivalsSection />
        <BrandStorySection />
        <NewsletterSection />
    </main>
    <SiteFooter />
</template>
