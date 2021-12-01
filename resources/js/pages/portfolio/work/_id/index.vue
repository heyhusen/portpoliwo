<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Create Portfolio Work</h1>
	</header>

	<section class="flex flex-col sm:flex-row gap-6 md:gap-8">
		<header class="space-y-1 sm:max-w-2/5">
			<h3 class="font-medium text-lg">Work</h3>
			<small class="text-sm text-gray-500">
				A page contains your portfolio, complete with grouping by category or
				tag.
			</small>
		</header>

		<form
			class="bg-white rounded-md shadow overflow-hidden flex-1"
			@submit="onSubmit"
		>
			<div class="space-y-6 px-4 py-5 sm:p-6">
				<oc-field
					label="Name"
					:variant="nameError ? 'danger' : ''"
					:message="nameError"
				>
					<oc-input v-model="name" name="name" />
				</oc-field>
				<oc-field
					label="Description"
					:variant="descriptionError ? 'danger' : ''"
					:message="descriptionError"
				>
					<oc-input v-model="description" name="description" type="textarea" />
				</oc-field>
				<oc-field
					label="URL"
					:variant="urlError ? 'danger' : ''"
					:message="urlError"
				>
					<oc-input v-model="url" name="url" />
				</oc-field>
				<oc-field
					label="Featured Photo"
					addons-class="flex gap-2 items-center"
					:variant="photoError ? 'danger' : ''"
					:message="photoError"
				>
					<o-upload v-model="photo" name="photo" root-class="cursor-pointer">
						<img
							:src="
								photo && photo.type.includes('image') ? tempUrl() : work.image
							"
							alt="Photo"
							class="h-32 w-auto object-cover border border-gray-300 p-1"
						/>
					</o-upload>
				</oc-field>
				<oc-field
					label="Category"
					:variant="categoryError ? 'danger' : ''"
					:message="categoryError"
				>
					<oc-inputitem
						v-model="category"
						autocomplete
						:data="categories"
						field="name"
						placeholder="Add a category"
						name="category_id"
						:allow-new="false"
						maxitems="1"
						@typing="getFilteredCategories"
					/>
				</oc-field>
				<oc-field
					label="Tag"
					:variant="tagError ? 'danger' : ''"
					:message="tagError"
				>
					<oc-inputitem
						v-model="tag"
						autocomplete
						:data="tags"
						field="name"
						placeholder="Add some tag"
						name="tag_id"
						:allow-new="false"
						@typing="getFilteredTags"
					/>
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
import { useField, useForm, defineRule } from 'vee-validate';
import { image as img } from '@vee-validate/rules';
import { serialize } from 'object-to-formdata';
import { useProgrammatic } from '@oruga-ui/oruga-next';
import { PhotographIcon } from '@heroicons/vue/outline';

import api from '@/plugins/api';
import OcField from '@/components/Field.vue';
import OcInput from '@/components/Input.vue';
import OcInputitem from '@/components/Inputitem.vue';
import OcButton from '@/components/Button.vue';

defineRule('image', img);

const loading = ref(false);
const work = ref({});
const categories = ref([]);
const tags = ref([]);
const route = useRoute();
const { handleSubmit, setErrors } = useForm({ initialValues: work });
const { value: name, errorMessage: nameError } = useField('name');
const { value: description, errorMessage: descriptionError } =
	useField('description');
const { value: url, errorMessage: urlError } = useField('url');
const { value: photo, errorMessage: photoError } = useField(
	'photo',
	{
		image: true,
	},
	{
		label: 'The photo',
	},
);
const { value: category, errorMessage: categoryError } =
	useField('category_id');
const { value: tag, errorMessage: tagError } = useField('tag_id');
const { oruga } = useProgrammatic();

const fetchData = () => {
	api
		.get(`/portfolio/${route.params.id}`)
		.then(({ data: { data } }) => {
			const {
				name,
				description,
				url,
				image,
				categories: category_id,
				tags: tag_id,
			} = data;
			work.value = { name, description, url, image, category_id, tag_id };
		})
		.catch(() => {
			work.value = {};
		});
};
fetchData();

const getFilteredCategories = (text) => {
	categories.value = [];
	api
		.get('/portfolio/category', {
			params: {
				search: text,
				sort_field: 'created_at',
				sort_order: 'desc',
			},
		})
		.then(({ data: { data: fetchedCategories } }) => {
			fetchedCategories.forEach((item) => categories.value.push(item));
		});
};

const getFilteredTags = (text) => {
	tags.value = [];
	api
		.get('/portfolio/tag', {
			params: {
				search: text,
				sort_field: 'created_at',
				sort_order: 'desc',
			},
		})
		.then(({ data: { data: fetchedTags } }) => {
			fetchedTags.forEach((item) => tags.value.push(item));
		});
};

const onSubmit = handleSubmit((values) => {
	loading.value = true;
	api
		.post(
			`/portfolio/${route.params.id}`,
			serialize(
				{
					...values,
					category_id: category.value
						? category.value.map((item) => item.id)
						: null,
					tag_id: tag.value ? tag.value.map((item) => item.id) : null,
					_method: 'PUT',
				},
				{ nullsAsUndefineds: true },
			),
		)
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

const tempUrl = () => {
	return URL.createObjectURL(photo.value);
};
</script>

<script>
export default {
	name: 'PortfolioWorkDetail',
	metaInfo: {
		title: 'Portfolio: Work Detail',
	},
};
</script>
