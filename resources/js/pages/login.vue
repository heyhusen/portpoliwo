<template>
	<main class="bg-gray-50 min-h-screen grid place-content-center p-6 lg:py-8">
		<section class="max-w-md flex flex-col gap-8 items-center">
			<router-link :to="{ name: 'home' }">
				<Logo class="text-primary fill-current w-2/3 mx-auto" />
			</router-link>
			<h1 class="text-2xl md:text-4xl font-bold">Sign in to your account</h1>
			<form
				@submit="onSubmit"
				class="
					bg-white
					p-6
					shadow
					rounded-md
					flex flex-col
					self-stretch
					gap-4
					sm:gap-6
					md:p-8
					lg:p-10
				"
			>
				<oc-notification v-if="message" variant="danger">
					{{ message }}
				</oc-notification>
				<oc-field
					label="E-Mail"
					:variant="emailError ? 'danger' : ''"
					:message="emailError"
				>
					<oc-input type="email" name="email" v-model="email" />
				</oc-field>
				<oc-field
					label="Password"
					:variant="passwordError ? 'danger' : ''"
					:message="passwordError"
				>
					<oc-input
						type="password"
						name="password"
						v-model="password"
						password-reveal
					/>
				</oc-field>
				<oc-button
					native-type="submit"
					label="Sign in"
					variant="primary"
					class="mt-1 sm:mt-2"
				/>
			</form>
		</section>
	</main>
</template>

<script setup>
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import {
	Form as VeeForm,
	Field as VeeField,
	useForm,
	useField,
} from 'vee-validate';

import Logo from '@/components/Logo.vue';
import OcField from '@/components/Field.vue';
import OcInput from '@/components/Input.vue';
import OcButton from '@/components/Button.vue';
import OcNotification from '@/components/Notification.vue';

const state = useStore();
const router = useRouter();
const message = ref('');
const { handleSubmit, setErrors } = useForm();
const { value: email, errorMessage: emailError } = useField('email');
const { value: password, errorMessage: passwordError } = useField('password');

const onSubmit = handleSubmit((values) => {
	message.value = '';
	state
		.dispatch('auth/logIn', values)
		.then(() => {
			router.push({ name: 'home' });
		})
		.catch(({ response: { data } }) => {
			message.value = data.message;
			setErrors(data.errors);
		});
});
</script>

<script>
import store from '@/store';

export default {
	name: 'LogIn',
	beforeRouteEnter() {
		if (store.getters['auth/authenticated']) {
			return '/';
		}
	},
};
</script>
