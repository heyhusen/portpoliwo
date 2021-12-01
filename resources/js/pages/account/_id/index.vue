<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Account Detail</h1>
	</header>

	<section class="flex flex-col sm:flex-row gap-6 md:gap-8">
		<header class="space-y-1 sm:max-w-2/5">
			<h3 class="font-medium text-lg">Personal Information</h3>
			<small class="text-sm text-gray-500">
				Use a valid email address where you can receive emails.
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
					label="E-Mail"
					:variant="emailError ? 'danger' : ''"
					:message="emailError"
				>
					<oc-input v-model="email" type="email" name="email" />
				</oc-field>
				<oc-field
					label="Password"
					:variant="passwordError ? 'danger' : ''"
					:message="passwordError"
				>
					<oc-input
						v-model="password"
						type="password"
						name="password"
						password-reveal
					/>
				</oc-field>
				<oc-field
					label="Repeat Password"
					:variant="repeatPasswordError ? 'danger' : ''"
					:message="repeatPasswordError"
				>
					<oc-input
						v-model="repeatPassword"
						type="password"
						name="password_repeat"
						password-reveal
					/>
				</oc-field>
				<oc-field
					label="Photo"
					addons-class="flex gap-2 items-center"
					:variant="photoError ? 'danger' : ''"
					:message="photoError"
				>
					<o-upload v-model="photo" name="photo" root-class="cursor-pointer">
						<img
							:src="
								photo && photo.type.includes('image') ? tempUrl() : user.avatar
							"
							alt="Avatar"
							class="rounded-full h-32 w-32 object-cover"
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
import { useForm, useField, defineRule } from 'vee-validate';
import { image } from '@vee-validate/rules';
import { useProgrammatic } from '@oruga-ui/oruga-next';
import { serialize } from 'object-to-formdata';

import api from '@/plugins/api';
import OcField from '@/components/Field.vue';
import OcInput from '@/components/Input.vue';
import OcButton from '@/components/Button.vue';

defineRule('image', image);

const user = ref({});
const loading = ref(false);
const route = useRoute();
const { handleSubmit, setErrors } = useForm({ initialValues: user });
const { value: name, errorMessage: nameError } = useField('name');
const { value: email, errorMessage: emailError } = useField('email');
const { value: password, errorMessage: passwordError } = useField('password');
const { value: repeatPassword, errorMessage: repeatPasswordError } =
	useField('password_repeat');
const { value: photo, errorMessage: photoError } = useField(
	'photo',
	{
		image: true,
	},
	{
		label: 'The photo',
	},
);
const { oruga } = useProgrammatic();

const fetchData = () => {
	api
		.get(`/account/${route.params.id}`)
		.then(({ data: { data } }) => {
			const { name, email, avatar, created_at, updated_at } = data;
			user.value = { name, email, avatar, created_at, updated_at };
		})
		.catch(() => {
			user.value = {};
		});
};
fetchData();

const onSubmit = handleSubmit((values) => {
	loading.value = true;
	api
		.post(
			`/account/${route.params.id}`,
			serialize(
				{ ...values, _method: 'PUT' },
				{
					nullsAsUndefineds: true,
				},
			),
		)
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
	name: 'AccountDetail',
	metaInfo: {
		title: 'Account Detail',
	},
};
</script>
