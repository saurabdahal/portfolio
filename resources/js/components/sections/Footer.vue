<template>
    <footer class="py-10 bg-[#2b2b2b] text-center">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex justify-center gap-6 mb-6">
                <a
                    v-for="social in socials"
                    :key="social.name"
                    :href="social.url"
                    target="_blank"
                    class="text-[#a1a1a1] hover:text-white transition-colors duration-200 text-xs uppercase tracking-widest"
                    style="font-family: 'Montserrat', sans-serif;"
                >
                    {{ social.name }}
                </a>
            </div>
            <p class="text-[#767676] text-xs" style="font-family: 'Poppins', sans-serif;">
                © {{ new Date().getFullYear() }} {{ name }}. All rights reserved.
            </p>
        </div>
    </footer>
</template>

<script setup>
defineProps({
    name: { type: String, default: 'Your Name' },
    socials: {
        type: Array,
        default: () => [
            { name: 'GitHub', url: 'https://github.com' },
            { name: 'LinkedIn', url: 'https://linkedin.com' },
            { name: 'Twitter', url: 'https://twitter.com' },
        ],
    },
});
</script>
