<template>
    <div class="text-center mb-16">
        <h2 class="text-3xl font-bold uppercase tracking-widest text-[#484848] mb-4" style="font-family: 'Montserrat', sans-serif;">
            {{ title }}
        </h2>
        <div class="flex items-center justify-center gap-1">
            <div class="h-px w-16 bg-[#bcbcbc]"></div>
            <div class="h-px w-10 bg-[#bcbcbc]"></div>
        </div>
        <p v-if="subtitle" class="mt-4 text-[#767676] text-sm" style="font-family: 'Poppins', sans-serif;">
            {{ subtitle }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
});
</script>
