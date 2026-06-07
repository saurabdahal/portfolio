<template>
    <div>
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
        <Experience v-if="experiences.length" :experiences="experiences" />
        <Portfolio
            v-if="projects.length"
            :projects="projects.map(p => ({ ...p, image: p.image ? '/storage/' + p.image : '' }))"
        />
        <Services v-if="services.length" :services="services" />
        <BlogSection />
        <Contact
            :email="contactData.email"
            :phone="contactData.phone"
            :location="contactData.location"
        />
        <Footer :name="heroData.name || 'Portfolio'" :socials="socials" />
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import NavBar from '../components/sections/NavBar.vue';
import Hero from '../components/sections/Hero.vue';
import About from '../components/sections/About.vue';
import Experience from '../components/sections/Experience.vue';
import Portfolio from '../components/sections/Portfolio.vue';
import Services from '../components/sections/Services.vue';
import BlogSection from '../components/sections/BlogSection.vue';
import Contact from '../components/sections/Contact.vue';
import Footer from '../components/sections/Footer.vue';

const route = useRoute();
const heroData = ref({});
const aboutData = ref({});
const skills = ref([]);
const experiences = ref([]);
const projects = ref([]);
const services = ref([]);
const contactData = ref({});
const socials = ref([]);
const activeSection = ref('home');

const sections = ['home', 'about', 'experience', 'portfolio', 'services', 'blog', 'contact'];

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/portfolio');
        heroData.value = data.hero ?? {};
        aboutData.value = data.about ?? {};
        skills.value = data.skills ?? [];
        experiences.value = data.experiences ?? [];
        projects.value = data.projects ?? [];
        services.value = data.services ?? [];
        contactData.value = data.contact ?? {};
        socials.value = data.socials ?? [];
    } catch (e) {
        console.error('Failed to load portfolio data', e);
    }

    window.addEventListener('scroll', updateActiveSection);

    if (route.hash) {
        await nextTick();
        scrollToSection(route.hash.replace('#', ''));
    }
});

onUnmounted(() => window.removeEventListener('scroll', updateActiveSection));

function scrollToSection(id) {
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
}

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
