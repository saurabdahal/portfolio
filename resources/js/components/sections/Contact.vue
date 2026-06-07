<template>
    <section id="contact" class="py-24 bg-[#f5f5f5]">
        <div class="max-w-6xl mx-auto px-6">
            <SectionTitle title="Get In Touch" subtitle="I'd love to hear from you" />
            <div class="flex flex-col md:flex-row gap-16">
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-[#484848] mb-6 uppercase tracking-wider" style="font-family: 'Montserrat', sans-serif;">
                        Contact Info
                    </h3>
                    <div class="space-y-5">
                        <div v-if="email" class="flex items-start gap-4">
                            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center text-[#484848]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-[#a1a1a1] mb-1" style="font-family: 'Montserrat', sans-serif;">Email</p>
                                <a :href="'mailto:' + email" class="text-sm text-[#767676] hover:text-[#484848] transition-colors" style="font-family: 'Poppins', sans-serif;">{{ email }}</a>
                            </div>
                        </div>
                        <div v-if="phone" class="flex items-start gap-4">
                            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center text-[#484848]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-[#a1a1a1] mb-1" style="font-family: 'Montserrat', sans-serif;">Phone</p>
                                <p class="text-sm text-[#767676]" style="font-family: 'Poppins', sans-serif;">{{ phone }}</p>
                            </div>
                        </div>
                        <div v-if="location" class="flex items-start gap-4">
                            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center text-[#484848]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-[#a1a1a1] mb-1" style="font-family: 'Montserrat', sans-serif;">Location</p>
                                <p class="text-sm text-[#767676]" style="font-family: 'Poppins', sans-serif;">{{ location }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-1">
                    <form @submit.prevent="submitForm" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Your Name"
                                required
                                class="w-full px-4 py-3 border border-[#d9d9d9] bg-white text-sm text-[#484848] placeholder-[#bcbcbc] focus:outline-none focus:border-[#484848] transition-colors"
                                style="font-family: 'Poppins', sans-serif;"
                            />
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="Your Email"
                                required
                                class="w-full px-4 py-3 border border-[#d9d9d9] bg-white text-sm text-[#484848] placeholder-[#bcbcbc] focus:outline-none focus:border-[#484848] transition-colors"
                                style="font-family: 'Poppins', sans-serif;"
                            />
                        </div>
                        <input
                            v-model="form.subject"
                            type="text"
                            placeholder="Subject"
                            class="w-full px-4 py-3 border border-[#d9d9d9] bg-white text-sm text-[#484848] placeholder-[#bcbcbc] focus:outline-none focus:border-[#484848] transition-colors"
                            style="font-family: 'Poppins', sans-serif;"
                        />
                        <textarea
                            v-model="form.message"
                            placeholder="Your Message"
                            rows="5"
                            required
                            class="w-full px-4 py-3 border border-[#d9d9d9] bg-white text-sm text-[#484848] placeholder-[#bcbcbc] focus:outline-none focus:border-[#484848] transition-colors resize-none"
                            style="font-family: 'Poppins', sans-serif;"
                        ></textarea>
                        <div v-if="success" class="text-sm text-green-600 py-2" style="font-family: 'Poppins', sans-serif;">
                            Message sent successfully! I'll get back to you soon.
                        </div>
                        <div v-if="error" class="text-sm text-red-500 py-2" style="font-family: 'Poppins', sans-serif;">
                            {{ error }}
                        </div>
                        <button
                            type="submit"
                            :disabled="sending"
                            class="px-8 py-3 bg-[#393939] text-white text-xs uppercase tracking-widest hover:bg-black transition-colors duration-300 disabled:opacity-50"
                            style="font-family: 'Montserrat', sans-serif;"
                        >
                            {{ sending ? 'Sending...' : 'Send Message' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import SectionTitle from '../ui/SectionTitle.vue';

defineProps({
    email: { type: String, default: 'hello@example.com' },
    phone: { type: String, default: '+1 234 567 890' },
    location: { type: String, default: 'New York, USA' },
});

const form = ref({ name: '', email: '', subject: '', message: '' });
const sending = ref(false);
const success = ref(false);
const error = ref('');

async function submitForm() {
    sending.value = true;
    error.value = '';
    success.value = false;
    try {
        await axios.post('/contact', form.value);
        success.value = true;
        form.value = { name: '', email: '', subject: '', message: '' };
    } catch (e) {
        error.value = 'Something went wrong. Please try again or email me directly.';
    } finally {
        sending.value = false;
    }
}
</script>
