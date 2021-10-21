<template>
	<ValidationProvider v-slot="{ errors }" :name="name">
		<b-field
			:label="label"
			:type="{ 'is-danger': errors[0] }"
			:message="[message, ...errors]"
			:horizontal="horizontal"
			:grouped="grouped"
		>
			<b-input
				:size="size"
				:value="value"
				:type="type"
				:icon="icon"
				:icon-right="iconRight"
				:placeholder="placeholder ? placeholder : label"
				:password-reveal="type == 'password'"
				:expanded="expanded"
				@input="$emit('input', $event)"
			></b-input>
			<p class="control">
				<slot></slot>
			</p>
		</b-field>
	</ValidationProvider>
</template>

<script>
import { ValidationProvider } from 'vee-validate';

export default {
	name: 'FormInput',
	components: {
		ValidationProvider,
	},
	props: {
		label: {
			type: String,
			default: '',
		},
		size: {
			type: String,
			default: '',
		},
		name: {
			type: String,
			default: 'name',
		},
		type: {
			type: String,
			default: 'text',
		},
		value: {
			type: String,
			default: '',
		},
		icon: {
			type: String,
			default: '',
		},
		iconRight: {
			type: String,
			default: '',
		},
		placeholder: {
			type: String,
			default: '',
		},
		expanded: {
			type: Boolean,
			default: true,
		},
		grouped: {
			type: Boolean,
			default: false,
		},
		horizontal: {
			type: Boolean,
			default: false,
		},
		message: {
			type: String,
			default: '',
		},
	},
};
</script>
