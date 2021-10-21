<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Deleted Blog Page</h1>
	</header>

	<datatable endpoint="blog/page" trashed>
		<o-table-column
			v-slot="{ row: { title } }"
			field="title"
			label="Title"
			sortable
		>
			{{ title }}
		</o-table-column>
		<o-table-column
			v-slot="{ row: { thumbnail } }"
			field="thumbnail"
			label="Thumbnail"
		>
			<img class="h-9 w-auto object-cover" :src="thumbnail" />
		</o-table-column>
	</datatable>
</template>

<script setup>
import Datatable from '@/components/Datatable.vue';
</script>

<script>
export default {
	name: 'BlogPageDeleted',
	metaInfo: {
		title: 'Blog: Deleted Page',
	},
};
</script>
