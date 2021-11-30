<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Account</h1>
	</header>
	<datatable endpoint="account">
		<o-table-column
			v-slot="{ row: { name } }"
			field="name"
			label="Name"
			sortable
		>
			{{ name }}
		</o-table-column>
		<o-table-column
			v-slot="{ row: { email } }"
			field="email"
			label="E-Mail"
			sortable
		>
			{{ email }}
		</o-table-column>
		<o-table-column v-slot="{ row: { avatar } }" field="avatar" label="Photo">
			<img class="rounded-full h-9 w-9 object-cover" :src="avatar" />
		</o-table-column>
		<o-table-column v-slot="{ row: { id } }" field="action" label="Action">
			<router-link :to="{ name: 'account-id', params: { id } }">
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
	name: 'AccountIndex',
	metaInfo: {
		title: 'Account',
	},
};
</script>
