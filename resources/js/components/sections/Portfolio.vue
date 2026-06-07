<template>
    <section id="portfolio" class="py-24 bg-[#1a1a1a]">
        <div class="max-w-6xl mx-auto px-6">
            <SectionTitle title="My Work" subtitle="A selection of recent projects" />

            <div class="flex justify-center gap-6 mb-12 flex-wrap">
                <button
                    v-for="cat in categories"
                    :key="cat"
                    @click="activeCategory = cat"
                    class="text-xs uppercase tracking-widest transition-colors duration-200 pb-1"
                    :class="activeCategory === cat
                        ? 'text-[#c9a84c] border-b border-[#c9a84c]'
                        : 'text-gray-500 hover:text-gray-300'"
                    style="font-family: 'Montserrat', sans-serif;"
                >
                    {{ cat }}
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <ProjectCard
                    v-for="project in filteredProjects"
                    :key="project.title"
                    :project="project"
                />
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed } from 'vue';
import SectionTitle from '../ui/SectionTitle.vue';
import ProjectCard from '../ui/ProjectCard.vue';

const props = defineProps({
    projects: {
        type: Array,
        default: () => [
            { title: 'E-Commerce Platform', category: 'Web', description: 'Full-stack e-commerce solution built with Laravel and Vue.js', url: '', image: '' },
            { title: 'Portfolio CMS', category: 'Web', description: 'Content management system for creative professionals', url: '', image: '' },
            { title: 'Mobile App UI', category: 'Design', description: 'UI/UX design for a fitness tracking mobile application', url: '', image: '' },
            { title: 'Dashboard Analytics', category: 'Web', description: 'Real-time analytics dashboard with interactive charts', url: '', image: '' },
            { title: 'Brand Identity', category: 'Design', description: 'Complete brand identity design for a tech startup', url: '', image: '' },
            { title: 'REST API', category: 'Backend', description: 'Scalable RESTful API built with Laravel for mobile apps', url: '', image: '' },
        ],
    },
});

const activeCategory = ref('All');

const categories = computed(() => {
    const cats = ['All', ...new Set(props.projects.map(p => p.category))];
    return cats;
});

const filteredProjects = computed(() => {
    if (activeCategory.value === 'All') return props.projects;
    return props.projects.filter(p => p.category === activeCategory.value);
});
</script>
