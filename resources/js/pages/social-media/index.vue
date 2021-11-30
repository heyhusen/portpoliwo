<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Social Media</h1>
	</header>

	<datatable endpoint="social-media">
		<o-table-column
			v-slot="{ row: { name } }"
			field="name"
			label="Name"
			sortable
		>
			{{ name }}
		</o-table-column>
		<o-table-column v-slot="{ row: { icon } }" field="icon" label="Icon">
			{{ icon }}
		</o-table-column>
		<o-table-column v-slot="{ row: { url } }" field="url" label="URL">
			<a
				:href="url"
				target="_blank"
				rel="noopener noreferrer"
				class="text-primary hover:underline"
			>
				{{ url }}
			</a>
		</o-table-column>
		<o-table-column v-slot="{ row: { id } }" field="action" label="Action">
			<router-link :to="{ name: 'social-media-id', params: { id } }">
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
	name: 'SocialMediaIndex',
	metaInfo: {
		title: 'Social Media',
	},
};
</script>
