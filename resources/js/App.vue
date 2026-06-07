<template>
    <div class="relative">
        <div v-if="loading" class="min-h-screen flex items-center justify-center bg-[#111111]">
            <div class="w-8 h-8 border-2 border-[#c9a84c] border-t-transparent rounded-full animate-spin"></div>
        </div>

        <Maintenance v-else-if="maintenanceMode" :socials="socials" />

        <template v-else>
            <NavBar :initials="heroData.name || 'Portfolio'" :active-section="activeSection" />
            <Hero
                :name="heroData.name"
                :title="heroData.title"
                :tagline="heroData.tagline"
                :photo="heroData.photo ? '/storage/' + heroData.photo : ''"
            />
            <About
                v-if="aboutData.is_visible !== false"
                :bio="aboutData.bio"
                :cv-url="aboutData.cv_path ? '/storage/' + aboutData.cv_path : ''"
                :skills="skills"
            />
            <Experience
                v-if="experiences.length"
                :experiences="experiences"
            />
            <Portfolio
                v-if="projects.length"
                :projects="projects.map(p => ({ ...p, image: p.image ? '/storage/' + p.image : '' }))"
            />
            <Services
                v-if="services.length"
                :services="services"
            />
            <Contact
                :email="contactData.email"
                :phone="contactData.phone"
                :location="contactData.location"
            />
            <Footer :name="heroData.name || 'Portfolio'" :socials="socials" />
        </template>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import NavBar from './components/sections/NavBar.vue';
import Hero from './components/sections/Hero.vue';
import About from './components/sections/About.vue';
import Experience from './components/sections/Experience.vue';
import Portfolio from './components/sections/Portfolio.vue';
import Services from './components/sections/Services.vue';
import Contact from './components/sections/Contact.vue';
import Footer from './components/sections/Footer.vue';
import Maintenance from './components/sections/Maintenance.vue';

const loading          = ref(true);
const maintenanceMode  = ref(false);
const heroData    = ref({});
const aboutData   = ref({});
const skills      = ref([]);
const experiences = ref([]);
const projects    = ref([]);
const services    = ref([]);
const contactData = ref({});
const socials     = ref([]);
const activeSection = ref('home');

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/portfolio');
        maintenanceMode.value = data.maintenance_mode ?? false;
        heroData.value    = data.hero    ?? {};
        aboutData.value   = data.about   ?? {};
        skills.value      = data.skills  ?? [];
        experiences.value = data.experiences ?? [];
        projects.value    = data.projects ?? [];
        services.value    = data.services ?? [];
        contactData.value = data.contact  ?? {};
        socials.value     = data.socials  ?? [];
    } catch (e) {
        console.error('Failed to load portfolio data', e);
    } finally {
        loading.value = false;
    }
    window.addEventListener('scroll', updateActiveSection);
});

onUnmounted(() => window.removeEventListener('scroll', updateActiveSection));

const sections = ['home', 'about', 'experience', 'portfolio', 'services', 'contact'];

function updateActiveSection() {
    const scrollY = window.scrollY + 100;
    for (let i = sections.length - 1; i >= 0; i--) {
        const el = document.getElementById(sections[i]);
        if (el && el.offsetTop <= scrollY) {
            activeSection.value = sections[i];
            break;
        }
    }
}
</script>
