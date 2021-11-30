<template>
	<ValidationProvider v-slot="{ errors }" :name="name">
		<b-field
			:label="label"
			:type="{ 'is-danger': errors[0] }"
			:message="[message, ...errors]"
		>
			<b-taginput
				:value="value"
				:data="data"
				:loading="loading"
				autocomplete
				:allow-new="allowNew"
				:allow-duplicates="false"
				:field="field"
				:icon="icon"
				:placeholder="placeholder ? placeholder : label"
				:before-adding="beforeAdding"
				rounded
				@add="$emit('add', $event)"
				@typing="$emit('typing', $event)"
				@input="$emit('input', $event)"
			>
			</b-taginput>
		</b-field>
	</ValidationProvider>
</template>

<script>
import { ValidationProvider } from 'vee-validate';

export default {
	name: 'FormTagInput',
	components: {
		ValidationProvider,
	},
	props: {
		label: {
			type: String,
			default: 'Name',
		},
		name: {
			type: String,
			default: 'name',
		},
		data: {
			type: Array,
			default: () => [],
		},
		value: {
			type: Array,
			default: () => [],
		},
		loading: {
			type: Boolean,
			default: false,
		},
		field: {
			type: String,
			default: 'name',
		},
		icon: {
			type: String,
			default: '',
		},
		placeholder: {
			type: String,
			default: '',
		},
		allowNew: {
			type: Boolean,
			default: false,
		},
		beforeAdding: {
			type: Function,
			default: () => true,
		},
		message: {
			type: String,
			default: '',
		},
	},
};
</script>
