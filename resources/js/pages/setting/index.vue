<template>
	<div class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Setting</h1>
	</div>

	<section class="flex flex-col sm:flex-row gap-6 md:gap-8">
		<header class="space-y-1 sm:max-w-2/5">
			<h3 class="font-medium text-lg">General Information</h3>
			<p class="text-sm text-gray-500">
				You can use this general information as additional information.
			</p>
		</header>

		<form
			class="flex-1 bg-white rounded-md shadow overflow-hidden"
			@submit="onSubmit"
		>
			<div class="px-4 py-5 sm:p-6 space-y-6">
				<oc-field
					label="Site name"
					:variant="siteNameError ? 'danger' : ''"
					:message="siteNameError"
				>
					<oc-input v-model="site_name" name="site_name" />
				</oc-field>
				<oc-field
					label="Site description"
					:variant="siteDescriptionError ? 'danger' : ''"
					:message="siteDescriptionError"
				>
					<oc-input v-model="site_description" name="site_description" />
				</oc-field>
				<oc-field
					label="Company name"
					:variant="companyNameError ? 'danger' : ''"
					:message="companyNameError"
				>
					<oc-input v-model="company_name" name="company_name" />
				</oc-field>
				<oc-field
					label="Company address"
					:variant="companyAddressError ? 'danger' : ''"
					:message="companyAddressError"
				>
					<oc-input
						v-model="company_address"
						type="textarea"
						name="company_address"
					/>
				</oc-field>
				<oc-field
					label="Company phone number"
					:variant="companyPhoneNumberError ? 'danger' : ''"
					:message="companyPhoneNumberError"
				>
					<oc-input
						v-model="company_phone_number"
						name="company_phone_number"
					/>
				</oc-field>
				<oc-field
					label="Company email"
					:variant="companyEmailError ? 'danger' : ''"
					:message="companyEmailError"
				>
					<oc-input v-model="company_email" name="company_email" />
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
import { useForm, useField } from 'vee-validate';
import { useProgrammatic } from '@oruga-ui/oruga-next';

import api from '@/plugins/api';
import OcField from '@/components/Field.vue';
import OcInput from '@/components/Input.vue';
import OcButton from '@/components/Button.vue';

const loading = ref(false);
const settings = ref({});

const { handleSubmit, setErrors } = useForm({ initialValues: settings });
const { value: site_name, errorMessage: siteNameError } = useField('site_name');
const { value: site_description, errorMessage: siteDescriptionError } =
	useField('site_description');
const { value: company_name, errorMessage: companyNameError } =
	useField('company_name');
const { value: company_address, errorMessage: companyAddressError } =
	useField('company_address');
const { value: company_phone_number, errorMessage: companyPhoneNumberError } =
	useField('company_phone_number');
const { value: company_email, errorMessage: companyEmailError } =
	useField('company_email');
const { oruga } = useProgrammatic();

const fetchData = () => {
	api
		.get('/setting')
		.then(({ data: { data } }) => {
			settings.value = Object.fromEntries(
				data.map((value) => [value.name, value.value]),
			);
		})
		.catch(() => {
			settings.value = {};
		});
};
fetchData();

const onSubmit = handleSubmit((values) => {
	loading.value = true;
	api
		.post('/setting', values)
		.then(({ data: success }) => {
			fetchData();
			oruga.notification.open({
				message: success.message,
				rootClass: 'rounded-md p-4 text-sm bg-emerald-100 text-success',
				position: 'top',
				duration: 3000,
			});
		})
		.catch(({ response: { data: failed } }) => {
			setErrors(failed.errors);
			oruga.notification.open({
				message: failed.message,
				rootClass: 'rounded-md p-4 text-sm bg-red-100 text-error',
				position: 'top',
				duration: 3000,
			});
		})
		.finally(() => {
			loading.value = false;
		});
});
</script>

<script>
export default {
	name: 'SettingIndex',
	metaInfo: {
		title: 'Setting',
	},
};
</script>
