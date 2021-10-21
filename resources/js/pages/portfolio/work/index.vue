<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Work</h1>
	</header>

	<datatable endpoint="portfolio" path="portfolio/work" title="work" with-trash>
		<o-table-column
			v-slot="{ row: { name } }"
			field="name"
			label="Name"
			sortable
		>
			{{ name }}
		</o-table-column>
		<o-table-column v-slot="{ row: { url } }" field="url" label="URL">
			<a :href="url" target="_blank" rel="noopener noreferrer">{{ url }}</a>
		</o-table-column>
		<o-table-column v-slot="{ row: { image } }" field="image" label="Image">
			<img class="h-9 w-auto object-cover" :src="image" />
		</o-table-column>
		<o-table-column v-slot="{ row: { id } }" field="action" label="Action">
			<router-link :to="{ name: 'portfolio-work-id', params: { id } }">
				<o-icon icon="eye" root-class="text-gray-500 hover:text-gray-700" />
			</router-link>
		</o-table-column>
	</datatable>
</template>

<script setup>
import Datatable from '@/components/Datatable.vue';
</script>

<script>
export default {
	name: 'PortfolioWorkIndex',
	metaInfo: {
		title: 'Portfolio: Work',
	},
};
</script>
