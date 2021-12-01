<template>
	<Layout>
		<section class="flex flex-col gap-2">
			<h2 class="font-bold text-xl">Overview</h2>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
				<div
					v-if="data.portfolioWork"
					class="bg-white rounded-lg shadow overflow-hidden"
				>
					<div class="flex p-4 gap-4 items-center">
						<div class="bg-secondary p-2 rounded-lg">
							<archive-icon
								aria-hidden="true"
								class="text-pink-50 stroke-current h-10 w-10"
							/>
						</div>
						<div>
							<h3 class="text-gray-500">Total Portfolios</h3>
							<p class="text-5xl font-bold">{{ data.portfolioWork.count }}</p>
						</div>
					</div>
					<div class="bg-gray-50 py-3 px-4">
						<router-link
							:to="{ path: '/work' }"
							class="text-secondary hover:underline"
						>
							View all
						</router-link>
					</div>
				</div>
				<div v-else class="bg-white rounded-lg shadow overflow-hidden">
					<div class="flex p-4 gap-4 items-center">
						<o-skeleton
							height="56px"
							width="56px"
							root-class="w-auto rounded-lg overflow-hidden"
							animated
						/>
						<div class="flex-1 space-y-3">
							<o-skeleton animated />
							<o-skeleton height="49px" animated />
						</div>
					</div>
					<div class="bg-gray-50 py-3 px-4">
						<o-skeleton animated />
					</div>
				</div>
				<div
					v-if="data.blogPost"
					class="bg-white rounded-lg shadow overflow-hidden"
				>
					<div class="flex p-4 gap-4 items-center">
						<div class="bg-secondary p-2 rounded-lg">
							<clipboard-check-icon
								aria-hidden="true"
								class="text-pink-50 stroke-current h-10 w-10"
							/>
						</div>
						<div>
							<h3 class="text-gray-500">Total Articles</h3>
							<p class="text-5xl font-bold">{{ data.blogPost.count }}</p>
						</div>
					</div>
					<div class="bg-gray-50 py-3 px-4">
						<router-link
							:to="{ path: '/blog' }"
							class="text-secondary hover:underline"
						>
							View all
						</router-link>
					</div>
				</div>
				<div v-else class="bg-white rounded-lg shadow overflow-hidden">
					<div class="flex p-4 gap-4 items-center">
						<o-skeleton
							height="56px"
							width="56px"
							root-class="w-auto rounded-lg overflow-hidden"
							animated
						/>
						<div class="flex-1 space-y-3">
							<o-skeleton animated />
							<o-skeleton height="49px" animated />
						</div>
					</div>
					<div class="bg-gray-50 py-3 px-4">
						<o-skeleton animated />
					</div>
				</div>
				<div
					v-if="data.user"
					class="bg-white rounded-lg shadow overflow-hidden"
				>
					<div class="flex p-4 gap-4 items-center">
						<div class="bg-secondary p-2 rounded-lg">
							<users-icon
								aria-hidden="true"
								class="text-pink-50 stroke-current h-10 w-10"
							/>
						</div>
						<div>
							<h3 class="text-gray-500">Total Users</h3>
							<p class="text-5xl font-bold">{{ data.user.count }}</p>
						</div>
					</div>
					<div class="bg-gray-50 py-3 px-4">
						<router-link
							:to="{ path: '/account' }"
							class="text-secondary hover:underline"
						>
							View all
						</router-link>
					</div>
				</div>
				<div v-else class="bg-white rounded-lg shadow overflow-hidden">
					<div class="flex p-4 gap-4 items-center">
						<o-skeleton
							height="56px"
							width="56px"
							root-class="w-auto rounded-lg overflow-hidden"
							animated
						/>
						<div class="flex-1 space-y-3">
							<o-skeleton animated />
							<o-skeleton height="49px" animated />
						</div>
					</div>
					<div class="bg-gray-50 py-3 px-4">
						<o-skeleton animated />
					</div>
				</div>
			</div>
		</section>
	</Layout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useStore } from 'vuex';
import {
	ArchiveIcon,
	ClipboardCheckIcon,
	UsersIcon,
} from '@heroicons/vue/outline';
import api from '@/plugins/api';

const store = useStore();
const user = computed(() => store.getters['auth/user']);

const data = ref({});
api.get(`/`).then(({ data: { data: dashboard } }) => {
	data.value = dashboard;
});
</script>

<script>
export default {
	name: 'HomePage',
	metaInfo: {
		title: 'Home',
	},
};
</script>
