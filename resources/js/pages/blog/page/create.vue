<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Create Blog Page</h1>
	</header>

	<section class="flex flex-col sm:flex-row gap-6 md:gap-8">
		<header class="space-y-1 sm:max-w-2/5">
			<h3 class="font-medium text-lg">Page</h3>
			<small class="text-sm text-gray-500">
				General page contains your information without any grouping.
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
							v-if="image && image.type.includes('image')"
							:src="tempUrl()"
							alt="Avatar"
							class="h-32 w-auto object-cover border border-gray-300 p-1"
						/>
						<div
							v-else
							class="
								block
								border-2 border-dashed
								rounded-md
								p-8
								border-gray-300
								flex flex-col
								gap-1
								sm:px-12
								items-center
							"
						>
							<photograph-icon class="w-12 h-12 text-gray-500" />
							<p class="text-sm">Upload a file or drag and drop</p>
							<p class="text-gray-500 text-xs">PNG, JPG, GIF</p>
						</div>
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
import { useRouter } from 'vue-router';
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
const router = useRouter();
const { handleSubmit, setErrors } = useForm();
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

const onSubmit = handleSubmit((values) => {
	loading.value = true;
	api
		.post('/blog/page', serialize(values, { nullsAsUndefineds: true }))
		.then(({ data: success }) => {
			oruga.notification.open({
				message: success.message,
				rootClass: 'rounded-md p-4 text-sm bg-emerald-100 text-success',
				position: 'top',
				duration: 3000,
			});
			router.back();
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
	name: 'CreateBlogPage',
	metaInfo: {
		title: 'Blog: Create Page',
	},
};
</script>
