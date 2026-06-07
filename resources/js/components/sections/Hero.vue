<template>
    <section id="home" class="min-h-screen flex items-center bg-white">
        <div class="max-w-6xl mx-auto px-6 w-full">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="flex-1 text-center md:text-left">
                    <p class="text-xs uppercase tracking-[0.3em] text-[#a1a1a1] mb-4" style="font-family: 'Montserrat', sans-serif;">
                        Hello, I'm
                    </p>
                    <h1 class="text-5xl md:text-6xl font-bold text-[#484848] mb-4 leading-tight" style="font-family: 'Montserrat', sans-serif;">
                        {{ name }}
                    </h1>
                    <h2 class="text-xl text-[#a1a1a1] mb-6 font-light" style="font-family: 'Poppins', sans-serif;">
                        {{ title }}
                    </h2>
                    <p class="text-[#767676] mb-10 max-w-md leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                        {{ tagline }}
                    </p>
                    <a
                        href="#portfolio"
                        @click.prevent="scrollTo('portfolio')"
                        class="inline-block px-8 py-3 bg-[#393939] text-white text-xs uppercase tracking-widest hover:bg-black transition-colors duration-300"
                        style="font-family: 'Montserrat', sans-serif;"
                    >
                        My Work
                    </a>
                </div>

                <div class="flex-shrink-0">
                    <div class="w-64 h-64 md:w-80 md:h-80 rounded-full overflow-hidden bg-[#f5f5f5] border-4 border-[#e8e8e8]">
                        <img
                            v-if="photo"
                            :src="photo"
                            :alt="name"
                            class="w-full h-full object-cover"
                        />
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <svg class="w-32 h-32 text-[#c8c8c8]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a
            href="#about"
            @click.prevent="scrollTo('about')"
            class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce text-[#bcbcbc] hover:text-[#484848] transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </a>
    </section>
</template>

<script setup>
defineProps({
    name: { type: String, default: 'Your Name' },
    title: { type: String, default: 'Full Stack Developer' },
    tagline: { type: String, default: 'I build elegant web applications with a focus on performance and user experience.' },
    photo: { type: String, default: '' },
});

function scrollTo(id) {
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
}
</script>
