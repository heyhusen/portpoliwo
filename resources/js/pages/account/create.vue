<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">Create Account</h1>
	</header>
	<section class="flex flex-col sm:flex-row gap-6 md:gap-8">
		<header class="space-y-1 sm:max-w-2/5">
			<h3 class="font-medium text-lg">Personal Information</h3>
			<p class="text-sm text-gray-500">
				Use a valid email address where you can receive emails.
			</p>
		</header>
		<form
			@submit="onSubmit"
			class="flex-1 bg-white rounded-md shadow overflow-hidden"
		>
			<div class="space-y-6 px-4 py-6 sm:p-6">
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
							v-if="photo && photo.type.includes('image')"
							:src="tempUrl()"
							alt="Avatar"
							class="rounded-full h-32 w-32 object-cover border border-gray-300"
						/>
						<vue-fontawesome
							v-else
							icon="user-circle"
							size="8x"
							class="fill-current text-gray-400"
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
import { useRouter } from 'vue-router';
import { defineRule, useForm, useField } from 'vee-validate';
import { image } from '@vee-validate/rules';
import { useProgrammatic } from '@oruga-ui/oruga-next';
import { serialize } from 'object-to-formdata';

import api from '@/plugins/api';
import OcField from '@/components/Field.vue';
import OcInput from '@/components/Input.vue';
import OcButton from '@/components/Button.vue';

defineRule('image', image);

const loading = ref(false);
const router = useRouter();
const { handleSubmit, setErrors } = useForm();
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

const onSubmit = handleSubmit((values) => {
	loading.value = true;
	api
		.post(
			'/account',
			serialize(values, {
				nullsAsUndefineds: true,
			}),
		)
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
	return URL.createObjectURL(photo.value);
};
</script>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
	name: 'CreateAccount',
	metaInfo: {
		title: 'Create Account',
	},
});
</script>
