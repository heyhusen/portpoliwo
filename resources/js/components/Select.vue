<template>
	<o-select v-bind="$props" placeholder="Select" expanded>
		<slot />
	</o-select>
</template>

<style lang="scss" scoped>
.o-ctrl-sel {
	&:deep(.o-sel) {
		@apply rounded-md shadow-sm border-gray-300 w-full;
		&:focus {
			@apply border-indigo-300 ring ring-indigo-200 ring-opacity-50;
		}
	}
	&:deep(.o-sel-iconspace-left) {
		@apply pl-8;
	}
	&:deep(.o-sel__icon-left) {
		@apply w-9;
	}
}
</style>
