<template>
    <article class="border border-[#333333] bg-[#1a1a1a] p-6 h-full flex flex-col hover:border-[#c9a84c]/50 transition-colors duration-300">
        <p class="text-sm font-semibold uppercase tracking-wider text-white mb-3 flex-1" style="font-family: 'Montserrat', sans-serif;">
            {{ post.title }}
        </p>
        <p class="text-xs text-gray-500 mb-3" style="font-family: 'Poppins', sans-serif;">
            {{ formatDate(post.publishedAt) }}
        </p>
        <p class="text-sm text-gray-400 mb-5 line-clamp-3 flex-1" style="font-family: 'Poppins', sans-serif;">
            {{ post.brief }}
        </p>
        <div class="flex items-center justify-between mt-auto pt-4 border-t border-[#333333]">
            <span class="text-xs text-gray-500" style="font-family: 'Poppins', sans-serif;">
                {{ post.readTimeInMinutes }} min read
            </span>
            <router-link
                :to="`/blogs/${post.slug}`"
                class="text-xs uppercase tracking-widest text-[#c9a84c] hover:text-[#b8943f] transition-colors"
                style="font-family: 'Montserrat', sans-serif;"
            >
                Read More →
            </router-link>
        </div>
    </article>
</template>

<script setup>
defineProps({
    post: { type: Object, required: true },
});

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}
</script>
