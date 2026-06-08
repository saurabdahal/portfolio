import axios from 'axios';
import { HASHNODE_HOST } from '../config/hashnode';
import { postsQuery, postBySlugQuery } from './useHashnodeQueries';

async function fetchGraphQL(query) {
    const { data } = await axios.post('/api/hashnode/graphql', { query });

    if (data.errors?.length) {
        throw new Error(data.errors[0].message || 'Hashnode API error');
    }

    if (data.message && !data.data) {
        throw new Error(data.message);
    }

    return data.data ?? data;
}

export async function fetchHashnodePosts(first = 12) {
    try {
        const data = await fetchGraphQL(postsQuery({ host: HASHNODE_HOST, first }));

        return (data?.publication?.posts?.edges ?? []).map((edge) => edge.node);
    } catch {
        const { data } = await axios.get('/api/hashnode/posts', { params: { first } });
        return data.posts ?? [];
    }
}

export async function fetchHashnodePost(slug, previewToken = null) {
    let post = null;

    if (! previewToken) {
        try {
            const data = await fetchGraphQL(postBySlugQuery({ host: HASHNODE_HOST, slug }));
            post = data?.publication?.post ?? null;
        } catch {
            // fall through to portfolio API
        }

        if (post?.content?.html) {
            return post;
        }
    }

    try {
        const params = previewToken ? { preview: previewToken } : {};
        const { data } = await axios.get(`/api/hashnode/posts/${slug}`, { params });
        const apiPost = data.post ?? null;

        if (!apiPost) {
            return post;
        }

        return {
            ...apiPost,
            ...post,
            title: post?.title ?? apiPost.title,
            brief: post?.brief ?? apiPost.brief,
            content: post?.content ?? apiPost.content,
            coverImage: post?.coverImage ?? apiPost.coverImage,
        };
    } catch {
        return post;
    }
}
