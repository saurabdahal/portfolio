<template>
    <div class="min-h-screen bg-[#111111]">
        <nav class="fixed top-0 left-0 right-0 z-50 bg-[#111111]/95 backdrop-blur-sm border-b border-[#222222] py-4">
            <div class="max-w-4xl mx-auto px-6 flex items-center justify-between">
                <router-link
                    :to="slug ? '/blogs' : '/'"
                    class="text-xs uppercase tracking-widest text-gray-400 hover:text-[#c9a84c] transition-colors flex items-center gap-2"
                    style="font-family: 'Montserrat', sans-serif;"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ slug ? 'All Posts' : 'Back to Portfolio' }}
                </router-link>
                <a
                    :href="blogUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-xs uppercase tracking-widest text-gray-500 hover:text-[#c9a84c] transition-colors"
                    style="font-family: 'Montserrat', sans-serif;"
                >
                    Hashnode ↗
                </a>
            </div>
        </nav>

        <main class="pt-24 pb-16">
            <div class="max-w-4xl mx-auto px-6">
                <div v-if="loading" class="flex justify-center py-24">
                    <div class="w-8 h-8 border-2 border-[#c9a84c] border-t-transparent rounded-full animate-spin"></div>
                </div>

                <div v-else-if="error" class="text-center py-24">
                    <p class="text-gray-400 mb-6" style="font-family: 'Poppins', sans-serif;">{{ error }}</p>
                    <a
                        :href="blogUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-block px-8 py-3 bg-[#c9a84c] text-[#111111] text-xs uppercase tracking-widest hover:bg-[#b8943f] transition-colors font-semibold"
                        style="font-family: 'Montserrat', sans-serif;"
                    >
                        Read on Hashnode
                    </a>
                </div>

                <div v-else-if="!slug">
                    <SectionTitle title="All Posts" subtitle="Data engineering, cloud platforms, and more" />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <BlogCard v-for="post in posts" :key="post.id" :post="post" />
                    </div>
                </div>

                <article v-else class="border border-[#333333] bg-[#1a1a1a] p-8 md:p-12">
                    <header class="text-center mb-10 pb-8 border-b border-[#333333]">
                        <h1 class="text-2xl md:text-4xl font-bold text-white mb-4" style="font-family: 'Montserrat', sans-serif;">
                            {{ blogPost.title }}
                        </h1>
                        <p v-if="blogPost.brief" class="text-gray-400 mb-6" style="font-family: 'Poppins', sans-serif;">
                            {{ blogPost.brief }}
                        </p>
                        <img
                            v-if="coverImage"
                            :src="coverImage"
                            :alt="blogPost.title"
                            class="w-full rounded-lg mb-6 object-cover max-h-96"
                        />
                        <p class="text-sm text-gray-500" style="font-family: 'Poppins', sans-serif;">
                            {{ formatDate(blogPost.publishedAt) }} · {{ blogPost.readTimeInMinutes }} min read
                        </p>
                    </header>
                    <div class="blog-content" v-html="renderedContent"></div>
                </article>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useRoute } from 'vue-router';
import SectionTitle from '../components/ui/SectionTitle.vue';
import BlogCard from '../components/ui/BlogCard.vue';
import { fetchHashnodePosts, fetchHashnodePost } from '../composables/useHashnode';
import { cleanHashnodeHtml } from '../composables/cleanHashnodeHtml';
import { HASHNODE_BLOG_URL } from '../config/hashnode';

const route = useRoute();
const posts = ref([]);
const blogPost = ref({});
const loading = ref(true);
const error = ref('');
const blogUrl = HASHNODE_BLOG_URL;

const slug = computed(() => route.params.slug || null);

const coverImage = computed(() => blogPost.value?.coverImage?.url ?? null);

const renderedContent = computed(() => {
    const html = blogPost.value?.content?.html;
    return html ? cleanHashnodeHtml(html) : '';
});

function formatDate(dateString) {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}

async function loadPostsList() {
    loading.value = true;
    error.value = '';
    try {
        posts.value = await fetchHashnodePosts(24);
        if (!posts.value.length) error.value = 'No posts found';
    } catch (e) {
        error.value = e?.response?.data?.message || e?.message || 'Error loading posts';
    } finally {
        loading.value = false;
    }
}

async function loadSinglePost(postSlug) {
    loading.value = true;
    error.value = '';
    try {
        const post = await fetchHashnodePost(postSlug);
        if (post) {
            blogPost.value = post;
            window.scrollTo(0, 0);
        } else {
            error.value = 'Post not found';
        }
    } catch (e) {
        error.value = e?.response?.data?.message || e?.message || 'Error loading post';
    } finally {
        loading.value = false;
    }
}

watch(
    slug,
    async (value) => {
        if (value) await loadSinglePost(String(value));
        else await loadPostsList();
    },
    { immediate: true }
);
</script>
