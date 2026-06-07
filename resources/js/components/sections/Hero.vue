<template>
    <section id="home" class="relative min-h-screen flex items-center bg-[#111111] pt-20">
        <div class="max-w-6xl mx-auto px-6 w-full">
            <div class="flex flex-col md:flex-row items-center gap-12 md:gap-20">
                <div class="flex-shrink-0">
                    <div class="w-64 h-64 md:w-72 md:h-72 rounded-full overflow-hidden border-4 border-[#333333] shadow-xl">
                        <img
                            v-if="photo"
                            :src="photo"
                            :alt="name"
                            class="w-full h-full object-cover"
                        />
                        <div v-else class="w-full h-full bg-[#222222] flex items-center justify-center">
                            <span class="text-6xl font-light text-[#555555]">{{ name.charAt(0) }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <p class="text-sm uppercase tracking-[0.3em] text-[#c9a84c] mb-4" style="font-family: 'Montserrat', sans-serif;">
                        Hello, I'm
                    </p>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-3" style="font-family: 'Montserrat', sans-serif;">
                        {{ name }}
                    </h1>
                    <h2 class="text-xl md:text-2xl text-gray-400 font-light mb-6" style="font-family: 'Poppins', sans-serif;">
                        {{ title }}
                    </h2>
                    <p class="text-gray-400 leading-relaxed mb-8 max-w-lg mx-auto md:mx-0" style="font-family: 'Poppins', sans-serif;">
                        {{ tagline }}
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                        <a
                            href="#contact"
                            @click.prevent="scrollTo('contact')"
                            class="px-8 py-3 bg-[#c9a84c] text-[#111111] text-xs uppercase tracking-widest hover:bg-[#b8943f] transition-colors duration-300 font-semibold"
                            style="font-family: 'Montserrat', sans-serif;"
                        >
                            Get In Touch
                        </a>
                        <a
                            href="#portfolio"
                            @click.prevent="scrollTo('portfolio')"
                            class="px-8 py-3 border border-gray-600 text-gray-300 text-xs uppercase tracking-widest hover:border-[#c9a84c] hover:text-[#c9a84c] transition-colors duration-300"
                            style="font-family: 'Montserrat', sans-serif;"
                        >
                            View My Work
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <a href="#about" @click.prevent="scrollTo('about')" class="text-gray-500 hover:text-[#c9a84c] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </a>
        </div>
    </section>
</template>

<script setup>
defineProps({
    name: { type: String, default: 'Your Name' },
    title: { type: String, default: 'Full Stack Developer' },
    tagline: { type: String, default: 'I build beautiful and functional web applications.' },
    photo: { type: String, default: '' },
});

function scrollTo(id) {
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
}
</script>
