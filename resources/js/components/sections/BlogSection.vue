<template>
    <section id="blog" class="py-24 bg-[#111111]">
        <div class="max-w-6xl mx-auto px-6">
            <SectionTitle title="Latest Insights" subtitle="Thoughts on data engineering and cloud platforms" />

            <div v-if="loading" class="flex justify-center py-12">
                <div class="w-8 h-8 border-2 border-[#c9a84c] border-t-transparent rounded-full animate-spin"></div>
            </div>

            <div v-else-if="error" class="text-center py-8">
                <p class="text-gray-400 text-sm mb-4" style="font-family: 'Poppins', sans-serif;">{{ error }}</p>
                <router-link
                    to="/blogs"
                    class="inline-block px-8 py-3 border border-[#c9a84c] text-[#c9a84c] text-xs uppercase tracking-widest hover:bg-[#c9a84c] hover:text-[#111111] transition-colors duration-300"
                    style="font-family: 'Montserrat', sans-serif;"
                >
                    View All Posts
                </router-link>
            </div>

            <template v-else-if="posts.length">
                <div
                    class="blog-grid-3 mb-10"
                    :class="{ 'is-single': posts.length === 1, 'is-pair': posts.length === 2 }"
                >
                    <BlogCard v-for="post in posts" :key="post.id" :post="post" />
                </div>
                <div class="text-center">
                    <router-link
                        to="/blogs"
                        class="inline-block px-8 py-3 border border-[#c9a84c] text-[#c9a84c] text-xs uppercase tracking-widest hover:bg-[#c9a84c] hover:text-[#111111] transition-colors duration-300"
                        style="font-family: 'Montserrat', sans-serif;"
                    >
                        View All Posts
                    </router-link>
                </div>
            </template>

            <p v-else class="text-center text-gray-500 text-sm" style="font-family: 'Poppins', sans-serif;">
                No posts found.
            </p>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import SectionTitle from '../ui/SectionTitle.vue';
import BlogCard from '../ui/BlogCard.vue';
import { fetchHashnodePosts } from '../../composables/useHashnode';

const posts = ref([]);
const loading = ref(true);
const error = ref('');

onMounted(async () => {
    try {
        posts.value = await fetchHashnodePosts(3);
    } catch (e) {
        error.value = e?.response?.data?.message || e?.message || 'Failed to load blog posts';
    } finally {
        loading.value = false;
    }
});
</script>
