<template>
    <nav
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        :class="isSticky ? 'bg-[#111111]/95 backdrop-blur-sm shadow-lg shadow-black/20 py-3 border-b border-[#222222]' : 'bg-transparent py-5'"
    >
        <div class="max-w-6xl mx-auto px-6 flex items-center justify-between">
            <a href="#home" @click.prevent="navigate('home')" class="text-lg font-bold uppercase tracking-widest text-white" style="font-family: 'Montserrat', sans-serif;">
                {{ initials }}
            </a>

            <ul class="hidden md:flex items-center gap-8">
                <li v-for="item in navItems" :key="item.id">
                    <a
                        :href="item.href"
                        @click.prevent="navigate(item.id)"
                        class="text-xs uppercase tracking-widest transition-colors duration-200 hover:text-[#c9a84c]"
                        :class="activeSection === item.id ? 'text-[#c9a84c] font-semibold' : 'text-gray-400'"
                        style="font-family: 'Montserrat', sans-serif;"
                    >
                        {{ item.label }}
                    </a>
                </li>
            </ul>

            <button @click="menuOpen = !menuOpen" class="md:hidden text-gray-300">
                <svg v-if="!menuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div
            class="md:hidden bg-[#1a1a1a] border-t border-[#333333] overflow-hidden transition-all duration-300"
            :class="menuOpen ? 'max-h-96' : 'max-h-0'"
        >
            <ul class="px-6 py-4 flex flex-col gap-4">
                <li v-for="item in navItems" :key="item.id">
                    <a
                        :href="item.href"
                        @click.prevent="navigate(item.id); menuOpen = false"
                        class="text-xs uppercase tracking-widest text-gray-400 hover:text-[#c9a84c] transition-colors duration-200"
                        style="font-family: 'Montserrat', sans-serif;"
                    >
                        {{ item.label }}
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

defineProps({
    initials: { type: String, default: 'Portfolio' },
    activeSection: { type: String, default: 'home' },
});

const isSticky = ref(false);
const menuOpen = ref(false);

const navItems = [
    { id: 'home', label: 'Home', href: '#home' },
    { id: 'about', label: 'About', href: '#about' },
    { id: 'experience', label: 'Experience', href: '#experience' },
    { id: 'portfolio', label: 'Work', href: '#portfolio' },
    { id: 'services', label: 'Services', href: '#services' },
    { id: 'blog', label: 'Blog', href: '#blog' },
    { id: 'contact', label: 'Contact', href: '#contact' },
];

function navigate(id) {
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
}

function handleScroll() {
    isSticky.value = window.scrollY > 60;
}

onMounted(() => window.addEventListener('scroll', handleScroll));
onUnmounted(() => window.removeEventListener('scroll', handleScroll));
</script>
