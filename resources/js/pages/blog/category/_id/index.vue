<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Blog Category Detail</h1>
	</header>

	<section class="flex flex-col sm:flex-row gap-6 md:gap-8">
		<header class="space-y-1 sm:max-w-2/5">
			<h3 class="font-medium text-lg">Category</h3>
			<small class="text-sm text-gray-500">
				Edit a category for your blog post, to make it more organized.
			</small>
		</header>

		<form
			@submit="onSubmit"
			class="bg-white rounded-md shadow overflow-hidden flex-1"
		>
			<div class="space-y-6 px-4 py-5 sm:p-6">
				<oc-field
					label="Title"
					:variant="titleError ? 'danger' : ''"
					:message="titleError"
				>
					<oc-input v-model="title" name="title" />
				</oc-field>
				<oc-field
					label="Slug (Optional)"
					:variant="slugError ? 'danger' : ''"
					:message="slugError"
				>
					<oc-input v-model="slug" name="slug" />
				</oc-field>
			</div>

			<div class="bg-gray-50 px-4 py-3 sm:px-6 text-right">
				<oc-button native-type="submit" variant="primary">
					<div class="inline-flex gap-2 items-center">
						<o-loading
							v-model:active="loading"
							:full-page="false"
							:overlay="false"
							root-class="static"
						/>
						<span>Save</span>
					</div>
				</oc-button>
			</div>
		</form>
	</section>
</template>

<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import { useField, useForm } from 'vee-validate';
import { useProgrammatic } from '@oruga-ui/oruga-next';

import api from '@/plugins/api';
import OcField from '@/components/Field.vue';
import OcInput from '@/components/Input.vue';
import OcSelect from '@/components/Select.vue';
import OcButton from '@/components/Button.vue';

const loading = ref(false);
const category = ref({});
const route = useRoute();
const { handleSubmit, setErrors } = useForm({ initialValues: category });
const { value: title, errorMessage: titleError } = useField('title');
const { value: slug, errorMessage: slugError } = useField('slug');
const { oruga } = useProgrammatic();

const fetchData = () => {
	api
		.get(`/blog/category/${route.params.id}`)
		.then(({ data: { data } }) => {
			const { title, slug } = data;
			category.value = { title, slug };
		})
		.catch(() => {
			category.value = {};
		});
};
fetchData();

const onSubmit = handleSubmit((values) => {
	loading.value = true;
	api
		.put(`/blog/category/${route.params.id}`, values)
		.then(({ data: success }) => {
			oruga.notification.open({
				message: success.message,
				rootClass: 'rounded-md p-4 text-sm bg-emerald-100 text-success',
				position: 'top',
				duration: 3000,
			});
			fetchData();
		})
		.catch(({ response: { data: failed } }) => {
			oruga.notification.open({
				message: failed.message,
				rootClass: 'rounded-md p-4 text-sm bg-red-100 text-error',
				position: 'top',
				duration: 3000,
			});
			setErrors(failed.errors);
		})
		.finally(() => {
			loading.value = false;
		});
});
</script>

<script>
export default {
	name: 'categoryDetail',
	metaInfo: {
		title: 'Blog: Category Detail',
	},
};
</script>
