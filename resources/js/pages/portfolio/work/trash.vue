<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Deleted Work</h1>
	</header>

	<datatable endpoint="portfolio" path="portfolio/work" title="work" trashed>
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
	</datatable>
</template>

<script setup>
import Datatable from '@/components/Datatable.vue';
</script>

<script>
export default {
	name: 'PortfolioWorkDeleted',
	metaInfo: {
		title: 'Portfolio: Deleted Work',
	},
};
</script>
