<template>
	<nav>
		<ul class="inline-flex gap-2">
			<li class="inline-flex items-center gap-2">
				<router-link
					:to="{ path: '/' }"
					class="text-gray-600 hover:text-gray-800"
				>
					<span class="sr-only">Home</span>
					<home-icon class="h-6 w-6 fill-current" />
				</router-link>
				<chevron-right-icon class="h-5 w-5 stroke-current text-gray-400" />
			</li>
			<li
				v-for="(item, key) of breadcrumbs()"
				:key="key"
				class="inline-flex items-center gap-2"
			>
				<router-link
					:to="{ path: item.path }"
					class="text-gray-600 hover:text-gray-800"
				>
					{{ item.name }}
				</router-link>
				<chevron-right-icon
					v-if="key !== breadcrumbs().length - 1"
					class="h-5 w-5 stroke-current text-gray-400"
				/>
			</li>
		</ul>
	</nav>
</template>

<script setup>
import { useRoute } from 'vue-router';
import { HomeIcon } from '@heroicons/vue/solid';
import { ChevronRightIcon } from '@heroicons/vue/outline';

const route = useRoute();

const breadcrumbs = () => {
	const breadcrumbLists = [];
	route.matched.forEach((item, i, { length }) => {
		const breadcrumb = {};
		breadcrumb.path = item.path;
		breadcrumb.name = item.path
			.split('/')
			.pop()
			.replace('-', ' ')
			.replace(/(^\w|\s\w)/g, (m) => m.toUpperCase());

		if (i === length - 1) {
			breadcrumb.path = route.path;
		}
		if (item.path.includes(':id')) {
			breadcrumb.name = 'Detail';
		}

		breadcrumbLists.push(breadcrumb);
		if (i === length - 1 && item.path === route.matched[i - 1].path) {
			breadcrumbLists.pop();
		}
	});
	return breadcrumbLists;
};
</script>
