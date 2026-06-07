<template>
    <section id="experience" class="py-24 bg-[#111111]">
        <div class="max-w-6xl mx-auto px-6">
            <SectionTitle title="Experience" />
            <div class="max-w-2xl mx-auto">
                <div
                    v-for="(item, index) in experiences"
                    :key="index"
                    class="relative flex gap-6 pb-12 last:pb-0"
                >
                    <div class="flex flex-col items-center">
                        <div class="w-3 h-3 rounded-full bg-[#c9a84c] border-2 border-[#111111] ring-2 ring-[#c9a84c] mt-1.5 flex-shrink-0"></div>
                        <div v-if="index < experiences.length - 1" class="flex-1 w-px border-l border-dashed border-[#444444] mt-2"></div>
                    </div>
                    <div class="flex-1 pb-2">
                        <span class="text-xs uppercase tracking-widest text-[#c9a84c] mb-1 block" style="font-family: 'Montserrat', sans-serif;">
                            {{ item.year }}
                        </span>
                        <h3 class="text-base font-semibold text-white mb-1" style="font-family: 'Montserrat', sans-serif;">
                            {{ item.title }}
                        </h3>
                        <p class="text-sm text-gray-400 mb-2 font-medium" style="font-family: 'Poppins', sans-serif;">
                            {{ item.company }}
                        </p>
                        <p class="text-sm text-gray-500 leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                            {{ item.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import SectionTitle from '../ui/SectionTitle.vue';

defineProps({
    experiences: {
        type: Array,
        default: () => [
            {
                year: '2023 — Present',
                title: 'Senior Full Stack Developer',
                company: 'Tech Company',
                description: 'Leading development of scalable web applications using Laravel and Vue.js. Managing a team of developers and ensuring code quality.',
            },
            {
                year: '2021 — 2023',
                title: 'Full Stack Developer',
                company: 'Digital Agency',
                description: 'Built and maintained client projects across various industries. Worked closely with designers to implement pixel-perfect UIs.',
            },
            {
                year: '2019 — 2021',
                title: 'Junior Web Developer',
                company: 'Startup',
                description: 'Developed frontend interfaces and RESTful APIs. Gained hands-on experience with modern web technologies.',
            },
        ],
    },
});
</script>
