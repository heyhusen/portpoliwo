<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Portfolio Tag</h1>
	</header>

	<datatable endpoint="portfolio/tag">
		<o-table-column
			v-slot="{ row: { name } }"
			field="name"
			label="Name"
			sortable
		>
			{{ name }}
		</o-table-column>
		<o-table-column
			v-slot="{ row: { slug } }"
			field="slug"
			label="Slug"
			sortable
		>
			{{ slug }}
		</o-table-column>
		<o-table-column
			v-slot="{ row: { works_count } }"
			field="works_count"
			label="Works"
			sortable
		>
			{{ works_count }}
		</o-table-column>
		<o-table-column v-slot="{ row: { id } }" field="action" label="Action">
			<router-link :to="{ name: 'portfolio-tag-id', params: { id } }">
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
	name: 'PortfolioTagIndex',
	metaInfo: {
		title: 'Portfolio: Tag',
	},
};
</script>
