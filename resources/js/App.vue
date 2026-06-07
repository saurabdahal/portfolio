<template>
    <div class="relative">
        <div v-if="loading" class="min-h-screen flex items-center justify-center bg-[#111111]">
            <div class="w-8 h-8 border-2 border-[#c9a84c] border-t-transparent rounded-full animate-spin"></div>
        </div>

        <Maintenance v-else-if="maintenanceMode" :socials="socials" />

        <router-view v-else />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Maintenance from './components/sections/Maintenance.vue';

const loading = ref(true);
const maintenanceMode = ref(false);
const socials = ref([]);

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/portfolio');
        maintenanceMode.value = data.maintenance_mode ?? false;
        socials.value = data.socials ?? [];
    } catch (e) {
        console.error('Failed to load portfolio data', e);
    } finally {
        loading.value = false;
    }
});
</script>
