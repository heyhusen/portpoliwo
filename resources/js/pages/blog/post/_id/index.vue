<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Blog Post Detail</h1>
	</header>

	<section class="flex flex-col sm:flex-row gap-6 md:gap-8">
		<header class="space-y-1 sm:max-w-2/5">
			<h3 class="font-medium text-lg">Post</h3>
			<small class="text-sm text-gray-500">
				A page contains your article, complete with grouping by category or tag.
			</small>
		</header>

		<form
			class="bg-white rounded-md shadow overflow-hidden flex-1"
			@submit="onSubmit"
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
				<oc-field
					label="Summary (Optional)"
					:variant="summaryError ? 'danger' : ''"
					:message="summaryError"
				>
					<oc-input v-model="summary" name="summary" type="textarea" />
				</oc-field>
				<oc-field
					label="Content"
					:variant="contentError ? 'danger' : ''"
					:message="contentError"
				>
					<tiptap v-model="content" name="content" />
				</oc-field>
				<oc-field
					label="Featured Image"
					addons-class="flex gap-2 items-center"
					:variant="imageError ? 'danger' : ''"
					:message="imageError"
				>
					<o-upload v-model="image" name="image" root-class="cursor-pointer">
						<img
							:src="
								image && image.type.includes('image')
									? tempUrl()
									: post.thumbnail
							"
							alt="Avatar"
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
						field="title"
						placeholder="Add a category"
						name="blog_category_id"
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
						field="title"
						placeholder="Add some tag"
						name="blog_tag_id"
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
import Tiptap from '@/components/Tiptap.vue';
import OcButton from '@/components/Button.vue';

defineRule('image', img);

const loading = ref(false);
const post = ref({});
const categories = ref([]);
const tags = ref([]);
const route = useRoute();
const { handleSubmit, setErrors } = useForm({ initialValues: post });
const { value: title, errorMessage: titleError } = useField('title');
const { value: slug, errorMessage: slugError } = useField('slug');
const { value: summary, errorMessage: summaryError } = useField('summary');
const { value: content, errorMessage: contentError } = useField('content');
const { value: image, errorMessage: imageError } = useField(
	'image',
	{
		image: true,
	},
	{
		label: 'The image',
	},
);
const { value: category, errorMessage: categoryError } =
	useField('blog_category_id');
const { value: tag, errorMessage: tagError } = useField('blog_tag_id');
const { oruga } = useProgrammatic();

const fetchData = () => {
	api
		.get(`/blog/${route.params.id}`)
		.then(({ data: { data } }) => {
			const {
				title,
				slug,
				summary,
				content,
				thumbnail,
				categories: blog_category_id,
				tags: blog_tag_id,
			} = data;
			post.value = {
				title,
				slug,
				summary,
				content,
				thumbnail,
				blog_category_id,
				blog_tag_id,
			};
		})
		.catch(() => {
			post.value = {};
		});
};
fetchData();

const getFilteredCategories = (text) => {
	categories.value = [];
	api
		.get('/blog/category', {
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
		.get('/blog/tag', {
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
			`/blog/${route.params.id}`,
			serialize(
				{
					...values,
					blog_category_id: category.value
						? category.value.map((item) => item.id)
						: null,
					blog_tag_id: tag.value ? tag.value.map((item) => item.id) : null,
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
	return URL.createObjectURL(image.value);
};
</script>

<script>
export default {
	name: 'BlogPostDetail',
	metaInfo: {
		title: 'Blog: Post Detail',
	},
};
</script>
