<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Blog Page Detail</h1>
	</header>

	<section class="flex flex-col sm:flex-row gap-6 md:gap-8">
		<header class="space-y-1 sm:max-w-2/5">
			<h3 class="font-medium text-lg">Page</h3>
			<small class="text-sm text-gray-500">
				General page contains your information without any grouping.
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
									: page.thumbnail
							"
							alt="Avatar"
							class="h-32 w-auto object-cover border border-gray-300 p-1"
						/>
					</o-upload>
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
import Tiptap from '@/components/Tiptap.vue';
import OcButton from '@/components/Button.vue';

defineRule('image', img);

const loading = ref(false);
const page = ref({});
const route = useRoute();
const { handleSubmit, setErrors } = useForm({
	initialValues: page,
});
const { value: title, errorMessage: titleError } = useField('title');
const { value: slug, errorMessage: slugError } = useField('slug');
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
const { oruga } = useProgrammatic();

const fetchData = () => {
	api
		.get(`/blog/page/${route.params.id}`)
		.then(({ data: { data } }) => {
			const { title, slug, content, thumbnail } = data;
			page.value = { title, slug, content, thumbnail };
		})
		.catch(() => {
			page.value = {};
		});
};
fetchData();

const onSubmit = handleSubmit((values) => {
	loading.value = true;
	api
		.post(
			`/blog/page/${route.params.id}`,
			serialize({ ...values, _method: 'PUT' }, { nullsAsUndefineds: true }),
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
	name: 'BlogPageDetail',
	metaInfo: {
		title: 'Blog: Page Detail',
	},
};
</script>
