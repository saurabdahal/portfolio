import axios from 'axios';

export async function fetchHashnodePosts(first = 12) {
    const { data } = await axios.get('/api/hashnode/posts', { params: { first } });
    return data.posts ?? [];
}

export async function fetchHashnodePost(slug) {
    const { data } = await axios.get(`/api/hashnode/posts/${slug}`);
    return data.post ?? null;
}
