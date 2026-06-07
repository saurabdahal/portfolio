<template>
    <section id="about" class="py-24 bg-[#1a1a1a]">
        <div class="max-w-6xl mx-auto px-6">
            <SectionTitle title="About Me" />
            <div class="flex flex-col md:flex-row gap-16">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-white mb-4" style="font-family: 'Montserrat', sans-serif;">
                        Who am I?
                    </h3>
                    <p class="text-gray-400 leading-relaxed mb-6" style="font-family: 'Poppins', sans-serif;">
                        {{ bio }}
                    </p>
                    <a
                        v-if="cvUrl"
                        :href="cvUrl"
                        download
                        class="inline-block px-8 py-3 border border-[#c9a84c] text-[#c9a84c] text-xs uppercase tracking-widest hover:bg-[#c9a84c] hover:text-[#111111] transition-colors duration-300"
                        style="font-family: 'Montserrat', sans-serif;"
                    >
                        Download CV
                    </a>
                </div>

                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-white mb-6" style="font-family: 'Montserrat', sans-serif;">
                        My Skills
                    </h3>
                    <SkillBar
                        v-for="skill in skills"
                        :key="skill.name"
                        :name="skill.name"
                        :percentage="skill.percentage"
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import SectionTitle from '../ui/SectionTitle.vue';
import SkillBar from '../ui/SkillBar.vue';

defineProps({
    bio: {
        type: String,
        default: 'I am a passionate developer with a love for creating beautiful and functional web applications. With years of experience in both frontend and backend development, I bring ideas to life through clean, efficient code.',
    },
    cvUrl: { type: String, default: '' },
    skills: {
        type: Array,
        default: () => [
            { name: 'Laravel / PHP', percentage: 90 },
            { name: 'Vue.js', percentage: 85 },
            { name: 'HTML & CSS', percentage: 95 },
            { name: 'MySQL', percentage: 80 },
            { name: 'UI / UX Design', percentage: 75 },
        ],
    },
});
</script>
