<template>
	<o-inputitems v-bind="$props" :status-icon="false" />
</template>

<style lang="scss" scoped>
.o-inputit {
	&:deep(.o-inputit__container) {
		@apply rounded-md border-gray-300 gap-2;
		&:focus-within {
			@apply ring ring-opacity-50 border-indigo-300 ring-indigo-200;
		}
	}
	&:deep(.o-inputit__item) {
		@apply rounded m-0 bg-primary text-indigo-50 py-0.5 px-1 text-sm my-2;
	}
	&:deep(.o-inputit__item:first-child) {
		@apply ml-2;
	}
	&:deep(.o-input) {
		@apply rounded-md;
		&:focus {
			@apply outline-none border-none ring-0;
		}
	}
	&:deep(.o-acp__menu) {
		@apply border divide-y rounded;
	}
	&:deep(.o-acp__item) {
		@apply py-1 px-2;
		&:hover {
			@apply bg-gray-100 duration-200;
		}
	}
}
</style>
