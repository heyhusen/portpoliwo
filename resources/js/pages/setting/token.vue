<template>
	<header class="py-2">
		<h1 class="font-bold text-2xl sm:text-3xl">API Tokens</h1>
	</header>
	<section class="flex flex-col sm:flex-row gap-6 md:gap-8">
		<header class="space-y-1 sm:w-2/5">
			<h2 class="font-medium text-lg">Create API Token</h2>
			<p class="text-sm text-gray-500">
				API tokens allow third-party services to authenticate with our
				application on your behalf.
			</p>
		</header>
		<div
			class="
				space-y-6
				md:space-y-8
				flex-1
				px-4
				py-5
				sm:p-6
				bg-white
				rounded-md
				shadow
				overflow-hidden
			"
		>
			<form @submit="onSubmit">
				<oc-field
					label="Name:"
					:variant="tokenError ? 'danger' : ''"
					:message="tokenError"
				>
					<oc-input v-model="token" name="name" expanded />
					<oc-button native-type="submit" variant="primary">
						<div class="inline-flex gap-2 items-center">
							<o-loading
								v-model:active="loading"
								:full-page="false"
								:overlay="false"
								root-class="static"
							/>
							<span>Create</span>
						</div>
					</oc-button>
				</oc-field>
			</form>
			<div v-if="createdToken" class="space-y-2">
				<p>Make sure to copy your new API token now. You won’t be able to see it
				again!</p>
				<oc-notification variant="info" :closable="false">
					{{ createdToken }}
				</oc-notification>
			</div>
		</div>
	</section>
	<hr />
	<section class="flex flex-col sm:flex-row gap-6 md:gap-8">
		<header class="space-y-1 sm:w-2/5">
			<h2 class="font-medium text-lg">Manage API Tokens</h2>
			<p class="text-sm text-gray-500">
				You may delete any of your existing tokens if they are no longer needed.
			</p>
		</header>
		<div
			class="
				space-y-6
				flex-1
				px-4
				py-5
				sm:p-6
				bg-white
				rounded-md
				shadow
				overflow-hidden
			"
		>
			<p v-if="tokens.length < 1" class="text-center">No API token yet.</p>
			<ul v-else class="divide-y divide-gray-100">
				<li
					v-for="(item, key) of tokens"
					:key="key"
					class="flex py-3 first:pt-0 last:pb-0 items-center justify-between"
				>
					<span class="truncate">{{ item.name }}</span>
					<oc-button
						label="Delete"
						size="small"
						variant="danger"
						outlined
						@click="deleteToken(item.id)"
					/>
				</li>
			</ul>
		</div>
	</section>
</template>

<script setup>
import { ref } from 'vue';
import { useField, useForm } from 'vee-validate';
import { useProgrammatic } from '@oruga-ui/oruga-next';

import api from '@/plugins/api';
import OcField from '@/components/Field.vue';
import OcInput from '@/components/Input.vue';
import OcButton from '@/components/Button.vue';
import OcNotification from '@/components/Notification.vue';

const loading = ref(false);
const tokens = ref([]);
const createdToken = ref('');
const { handleSubmit, setErrors } = useForm();
const { value: token, errorMessage: tokenError } = useField('name');
const { oruga } = useProgrammatic();

const fetchData = () => {
	api
		.get('/setting/token')
		.then(({ data: { data } }) => {
			tokens.value = data;
		})
		.catch(() => {
			tokens.value = [];
		});
};
fetchData();

const onSubmit = handleSubmit((values) => {
	createdToken.value = '';
	loading.value = true;
	api
		.post('/setting/token', values)
		.then(({ data: success }) => {
			createdToken.value = success.data.token;
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
			token.value = '';
		});
});

const deleteToken = (id) => {
	api
		.delete(`/setting/token/${id}`)
		.then(({ data: success }) => {
			fetchData();
			oruga.notification.open({
				message: success.message,
				rootClass: 'rounded-md p-4 text-sm bg-amber-100 text-warning',
				position: 'top',
				duration: 4000,
			});
		})
		.catch(({ response: { data: failed } }) => {
			oruga.notification.open({
				message: failed.message,
				rootClass: 'rounded-md p-4 text-sm bg-red-100 text-error',
				position: 'top',
				duration: 3000,
			});
		});
};
</script>

<script>
export default {
	name: 'SettingToken',
	metaInfo: {
		title: 'Setting: API Token',
	},
};
</script>
