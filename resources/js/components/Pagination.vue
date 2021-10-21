<template>
	<o-pagination
		v-bind="$props"
		order="centered"
		aria-next-label="Next page"
		aria-previous-label="Previous page"
		aria-page-label="Page"
		aria-current-label="Current page"
	/>
</template>

<style lang="scss" scoped>
.o-pag {
	@apply rounded-lg border divide-x border-indigo-100 divide-indigo-100 items-stretch;
	&:deep(.o-pag__list) {
		@apply divide-x divide-indigo-100;
	}
	&:deep(.o-pag__link) {
		@apply px-3 py-1.5;
	}
	&:deep(.o-pag__link--current) {
		@apply bg-primary text-indigo-50;
	}
}
</style>
