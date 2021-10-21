<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Social Media Detail</h1>
	</header>

	<section class="flex flex-col sm:flex-row gap-6 md:gap-8">
		<header class="space-y-1 sm:max-w-2/5">
			<h3 class="font-medium text-lg">Additional Information</h3>
			<small class="text-sm text-gray-500">
				Provide other information about social networks.
			</small>
		</header>

		<form
			@submit="onSubmit"
			class="bg-white rounded-md shadow overflow-hidden flex-1"
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
					label="Icon"
					:variant="iconError ? 'danger' : ''"
					:message="iconError"
				>
					<oc-select icon-pack="fab" :icon="icon" v-model="icon" name="icon">
						<option v-for="(item, key) of icons" :key="key" :value="item">
							{{ item }}
						</option>
					</oc-select>
				</oc-field>
				<oc-field
					label="URL"
					:variant="urlError ? 'danger' : ''"
					:message="urlError"
				>
					<oc-input v-model="url" name="url" />
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
const socialMedia = ref({});
const route = useRoute();
const { handleSubmit, setErrors } = useForm({ initialValues: socialMedia });
const { value: name, errorMessage: nameError } = useField('name');
const { value: icon, errorMessage: iconError } = useField('icon');
const { value: url, errorMessage: urlError } = useField('url');
const { oruga } = useProgrammatic();
const icons = [
	'behance',
	'dribbble',
	'facebook',
	'github',
	'gitlab',
	'linkedin',
	'twitter',
];

const fetchData = () => {
	api
		.get(`/social-media/${route.params.id}`)
		.then(({ data: { data } }) => {
			const { name, icon, url } = data;
			socialMedia.value = { name, icon, url };
		})
		.catch(() => {
			socialMedia.value = {};
		});
};
fetchData();

const onSubmit = handleSubmit((values) => {
	loading.value = true;
	api
		.put(`/social-media/${route.params.id}`, values)
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
	name: 'SocialMediaDetail',
	metaInfo: {
		title: 'Social Media Detail',
	},
};
</script>
