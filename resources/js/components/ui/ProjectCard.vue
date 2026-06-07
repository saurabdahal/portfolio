<template>
    <div class="group relative overflow-hidden bg-[#222222] aspect-[4/3] border border-[#333333]">
        <img
            v-if="project.image"
            :src="project.image"
            :alt="project.title"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
        />
        <div v-else class="w-full h-full bg-[#222222] flex items-center justify-center">
            <span class="text-[#555555] text-4xl font-light">{{ project.title.charAt(0) }}</span>
        </div>
        <div class="absolute inset-0 bg-[#111111]/90 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center gap-3">
            <h3 class="text-white font-semibold uppercase tracking-wider text-sm" style="font-family: 'Montserrat', sans-serif;">
                {{ project.title }}
            </h3>
            <p class="text-gray-400 text-xs text-center px-4">{{ project.description }}</p>
            <a
                v-if="project.url"
                :href="project.url"
                target="_blank"
                class="w-10 h-10 rounded-full border border-[#c9a84c] flex items-center justify-center text-[#c9a84c] hover:bg-[#c9a84c] hover:text-[#111111] transition-colors duration-300 mt-1"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        </div>
    </div>
</template>

<script setup>
defineProps({
    project: { type: Object, required: true },
});
</script>
