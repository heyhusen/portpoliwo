import { onMounted, ref } from 'vue';
import { useProgrammatic } from '@oruga-ui/oruga-next';
import api from '@/plugins/api';

export default function useDatatable(path) {
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
			.get(
				`${path}/?sort_field=${sortField.value}&sort_order=${sortOrder.value}&per_page=${perPage.value}&page=${page.value}`,
			)
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
	onMounted(fetchData);

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
		const selectedData = checkedRows.value.map(({ id }) => id);
		await api({
			method: 'delete',
			url: `/${path}`,
			data: {
				selectedData,
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

	return {
		data,
		total,
		loading,
		sortField,
		sortOrder,
		defaultSortOrder,
		page,
		perPage,
		checkedRows,
		onPageChange,
		onSort,
		convertDate,
		deleteData,
	};
}
