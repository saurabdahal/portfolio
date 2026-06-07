<template>
    <section id="services" class="py-24 bg-[#111111]">
        <div class="max-w-6xl mx-auto px-6">
            <SectionTitle title="Services" subtitle="What I can do for you" />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    v-for="service in services"
                    :key="service.title"
                    class="text-center p-8 border border-[#333333] bg-[#1a1a1a] hover:border-[#c9a84c]/50 transition-colors duration-300 group"
                >
                    <div class="w-14 h-14 mx-auto mb-6 flex items-center justify-center text-[#c9a84c] group-hover:scale-110 transition-transform duration-300" v-html="service.icon"></div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-white mb-3" style="font-family: 'Montserrat', sans-serif;">
                        {{ service.title }}
                    </h3>
                    <p class="text-sm text-gray-400 leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                        {{ service.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import SectionTitle from '../ui/SectionTitle.vue';

defineProps({
    services: {
        type: Array,
        default: () => [
            {
                title: 'Web Development',
                description: 'Building fast, scalable and modern web applications tailored to your needs.',
                icon: `<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>`,
            },
            {
                title: 'UI / UX Design',
                description: 'Crafting intuitive and elegant user interfaces with a focus on user experience.',
                icon: `<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>`,
            },
            {
                title: 'API Development',
                description: 'Designing and building robust RESTful APIs to power your applications.',
                icon: `<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`,
            },
            {
                title: 'Consulting',
                description: 'Technical consulting to help you make the right architectural and technology decisions.',
                icon: `<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>`,
            },
        ],
    },
});
</script>
