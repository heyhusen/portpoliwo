<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Blog Category</h1>
	</header>

	<datatable endpoint="blog/category">
		<o-table-column
			v-slot="{ row: { title } }"
			field="title"
			label="Title"
			sortable
		>
			{{ title }}
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
			v-slot="{ row: { posts_count } }"
			field="posts_count"
			label="Posts"
			sortable
		>
			{{ posts_count }}
		</o-table-column>
		<o-table-column v-slot="{ row: { id } }" field="action" label="Action">
			<router-link :to="{ name: 'blog-category-id', params: { id } }">
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
	name: 'BlogCategoryIndex',
	metaInfo: {
		title: 'Blog: Category',
	},
};
</script>
