<template>
	<div class="flex min-h-screen">
		<app-sidebar />
		<div class="flex flex-col flex-1">
			<app-header />
			<div
				v-if="route.name == 'home'"
				class="border-b px-4 py-6 bg-white flex gap-3 items-center shadow-sm"
			>
				<img
					:src="user.avatar"
					:alt="user.name"
					class="rounded-full w-14 sm:w-16 md:w-20 h-14 sm:h-16 md:h-20 object-cover"
				/>
				<div>
					<h1 class="text-xl sm:text-2xl md:text-3xl">
						Hello, <span class="text-primary font-bold">{{ user.name }}.</span>
					</h1>
					<p class="text-sm">I hope you are having a great day!</p>
				</div>
			</div>
			<div v-else class="border-b p-4 bg-white shadow-sm">
				<breadcrumb />
			</div>
			<main class="flex-1 p-4 md:p-6">
				<div class="max-w-7xl mx-auto grid grid-cols-1 gap-4 md:gap-6">
					<slot />
				</div>
			</main>
			<app-footer />
		</div>
	</div>
</template>

<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';

import AppHeader from '@/layouts/partials/Header.vue';
import AppSidebar from '@/layouts/partials/Sidebar.vue';
import AppFooter from '@/layouts/partials/Footer.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';

const route = useRoute();

const store = useStore();
const user = computed(() => store.getters['auth/user']);
</script>

<script>
export default {
	name: 'Layout',
};
</script>
