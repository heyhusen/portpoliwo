<template>
	<div
		class="flex flex-col sm:flex-row gap-4 sm:justify-between sm:items-center"
	>
		<oc-input />
		<div class="flex flex-col sm:flex-row gap-4">
			<oc-button
				v-if="withTrash"
				tag="router-link"
				label="Trash"
				size="small"
				outlined
				:to="{ path: `/${path}/trash` }"
			/>
			<oc-button
				v-if="trashed"
				:label="`Restore ${title}`"
				size="small"
				:disabled="!checkedRows.length"
				outlined
				@click="restoreData()"
			/>
			<oc-button
				:label="`Delete ${title}`"
				size="small"
				:disabled="!checkedRows.length"
				outlined
				@click="deleteData()"
			/>
			<oc-button
				v-if="!trashed"
				tag="router-link"
				:label="`Create ${title}`"
				size="small"
				variant="primary"
				:to="{ path: `/${path}/create` }"
			/>
		</div>
	</div>
	<oc-table
		v-model:checked-rows="checkedRows"
		:data="data"
		:loading="loading"
		:total="total"
		:per-page="perPage"
		:default-sort-direction="defaultSortOrder"
		:default-sort="[sortField, sortOrder]"
		checkable
		paginated
		scrollable
		backend-sorting
		@sort="onSort"
	>
		<o-table-column
			v-slot="{ row: { created_at } }"
			field="created_at"
			label="Created At"
			sortable
			centered
		>
			{{ convertDate(created_at) }}
		</o-table-column>
		<slot />
		<template #pagination>
			<div class="px-4 py-2 flex items-center justify-between text-gray-500">
				<span class="text-sm">
					Showing {{ total ? perPage * (page - 1) + 1 : 0 }} to
					{{ perPage * page >= total ? total : perPage * page }} of
					{{ total }} results
				</span>
				<oc-pagination
					v-model:current="page"
					:total="total"
					:per-page="perPage"
					range-before="1"
					range-after="1"
					@change="onPageChange"
				/>
			</div>
		</template>
	</oc-table>
</template>

<script setup>
import { ref, toRefs } from 'vue';
import { useProgrammatic } from '@oruga-ui/oruga-next';

import api from '@/plugins/api';

import OcButton from '@/components/Button.vue';
import OcInput from '@/components/Input.vue';
import OcTable from '@/components/Table.vue';
import OcPagination from '@/components/Pagination.vue';

const props = defineProps({
	endpoint: {
		type: String,
		default: '',
		required: true,
	},
	path: {
		type: String,
		default: (prop) => {
			return prop.endpoint;
		},
		required: false,
	},
	title: {
		type: String,
		default: (prop) => {
			return prop.path.replace('-', ' ').replace('/', ' ');
		},
		required: false,
	},
	withTrash: {
		type: Boolean,
		default: false,
		required: false,
	},
	trashed: {
		type: Boolean,
		default: false,
		required: false,
	},
});

const { trashed, endpoint } = toRefs(props);
const data = ref([]);
const total = ref(0);
const loading = ref(false);
const sortField = ref('created_at');
const sortOrder = ref('desc');
const defaultSortOrder = ref('desc');
const page = ref(1);
const perPage = ref(10);
const checkedRows = ref([]);

const { oruga } = useProgrammatic();

const fetchData = () => {
	loading.value = true;
	api
		.get(`${endpoint.value}`, {
			params: {
				sort_field: sortField.value,
				sort_order: sortOrder.value,
				per_page: perPage.value,
				page: page.value,
				deleted: trashed.value ? true : null,
			},
		})
		.then(({ data: { data: fetchedData } }) => {
			data.value = fetchedData.data;
			let currentTotal = fetchedData.total;
			if (fetchedData.total / perPage.value > 1000) {
				currentTotal = perPage.value * 1000;
			}
			total.value = currentTotal;
		})
		.catch((error) => {
			data.value = [];
			total.value = 0;
			throw error;
		})
		.finally(() => {
			loading.value = false;
		});
};
fetchData();

const onPageChange = (changedPage) => {
	page.value = changedPage;
	fetchData();
};

const onSort = (changedField, changedOrder) => {
	sortField.value = changedField;
	sortOrder.value = changedOrder;
	fetchData();
};

const convertDate = (date) => {
	return date ? new Date(date).toLocaleString() : 'unknown';
};

const deleteData = async () => {
	await api({
		method: 'delete',
		url: `/${endpoint.value}`,
		data: {
			selectedData: checkedRows.value.map(({ id }) => id),
			permanent: trashed.value ? true : null,
		},
	})
		.then(({ data: success }) => {
			oruga.notification.open({
				message: success.message,
				rootClass: 'rounded-md p-4 text-sm bg-amber-100 text-warning',
				position: 'top',
				duration: 3000,
			});
		})
		.catch(({ response: { data: failed } }) => {
			oruga.notification.open({
				message: failed.message,
				rootClass: 'rounded-md p-4 text-sm bg-red-100 text-error',
				position: 'top',
				duration: 3000,
			});
		})
		.finally(() => {
			checkedRows.value = [];
		});
	fetchData();
};

const restoreData = async () => {
	await api
		.post(`/${endpoint.value}/restore`, {
			selectedData: checkedRows.value.map(({ id }) => id),
		})
		.then(({ data: success }) => {
			oruga.notification.open({
				message: success.message,
				rootClass: 'rounded-md p-4 text-sm bg-amber-100 text-warning',
				position: 'top',
				duration: 3000,
			});
		})
		.catch(({ response: { data: failed } }) => {
			oruga.notification.open({
				message: failed.message,
				rootClass: 'rounded-md p-4 text-sm bg-red-100 text-error',
				position: 'top',
				duration: 3000,
			});
		})
		.finally(() => {
			checkedRows.value = [];
		});
	fetchData();
};
</script>
