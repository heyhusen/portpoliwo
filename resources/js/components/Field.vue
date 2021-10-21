<template>
	<o-field v-bind="$props"><slot /></o-field>
</template>

<style lang="scss" scoped>
.o-field {
	@apply space-y-2;
	&:deep(.o-field__message) {
		@apply text-sm;
	}
	&:deep(.o-field__message-success) {
		@apply text-success;
	}
	&:deep(.o-field__message-info) {
		@apply text-info;
	}
	&:deep(.o-field__message-warning) {
		@apply text-warning;
	}
	&:deep(.o-field__message-danger) {
		@apply text-error;
	}
	&:deep(.o-input__icon-right) {
		@apply px-2;
	}
}
</style>
