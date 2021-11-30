<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Blog Post</h1>
	</header>

	<datatable endpoint="blog" path="blog/post" title="blog post" with-trash>
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
		<o-table-column v-slot="{ row: { id } }" field="action" label="Action">
			<router-link :to="{ name: 'blog-post-id', params: { id } }">
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
	name: 'BlogPostIndex',
	metaInfo: {
		title: 'Blog: Post',
	},
};
</script>
