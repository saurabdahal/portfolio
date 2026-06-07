<template>
    <div class="mb-6" ref="barRef">
        <div class="flex justify-between mb-2">
            <span class="text-sm font-semibold uppercase tracking-wider text-[#484848]" style="font-family: 'Montserrat', sans-serif;">{{ name }}</span>
            <span class="text-sm text-[#a1a1a1]">{{ percentage }}%</span>
        </div>
        <div class="h-1.5 bg-[#c8c8c8] rounded-full overflow-hidden">
            <div
                class="h-full bg-[#484848] rounded-full transition-all duration-1000 ease-out"
                :style="{ width: animated ? percentage + '%' : '0%' }"
            ></div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

defineProps({
    name: { type: String, required: true },
    percentage: { type: Number, required: true },
});

const animated = ref(false);
const barRef = ref(null);

onMounted(() => {
    const observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                setTimeout(() => { animated.value = true; }, 200);
                observer.disconnect();
            }
        },
        { threshold: 0.3 }
    );
    if (barRef.value) observer.observe(barRef.value);
});
</script>
