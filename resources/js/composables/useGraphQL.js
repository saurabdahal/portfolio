import { ref } from 'vue';
import { HASHNODE_GQL_ENDPOINT } from '../config/hashnode';

export function useGraphQL({ endpoint = HASHNODE_GQL_ENDPOINT } = {}) {
    const data = ref(null);
    const loading = ref(false);
    const error = ref(null);

    async function fetchGraphQL(query) {
        loading.value = true;
        error.value = null;
        data.value = null;

        try {
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ query }),
            });
            const json = await res.json();
            if (json.errors?.length) {
                throw new Error(json.errors[0].message || 'Hashnode API error');
            }
            data.value = json.data;
        } catch (e) {
            error.value = e.message || 'Failed to fetch';
            throw e;
        } finally {
            loading.value = false;
        }

        return data.value;
    }

    return { data, loading, error, fetchGraphQL };
}
