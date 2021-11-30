<template>
	<o-input v-bind="$props" :status-icon="false" />
</template>

<style lang="scss" scoped>
.o-ctrl-input {
	&:deep(.o-input) {
		@apply rounded-md border-gray-300;
		&:focus {
			@apply ring ring-opacity-50 border-indigo-300 ring-indigo-200;
		}
	}
	&:deep(.o-input--sucess) {
		@apply border-emerald-300;
		&:focus {
			@apply border-emerald-300 ring-emerald-200;
		}
	}
	&:deep(.o-input--info) {
		@apply border-blue-300;
		&:focus {
			@apply border-blue-300 ring-blue-200;
		}
	}
	&:deep(.o-input--warning) {
		@apply border-amber-300;
		&:focus {
			@apply border-amber-300 ring-amber-200;
		}
	}
	&:deep(.o-input--danger) {
		@apply border-red-300;
		&:focus {
			@apply border-red-300 ring-red-200;
		}
	}
	&:deep(.o-input-iconspace-right) {
		@apply pr-8;
	}
}
</style>
