<template>
	<o-table v-bind="$props">
		<template v-for="(_, name) in $slots" #[name]="slotData">
			<slot :name="name" v-bind="slotData" />
		</template>
		<template #empty>
			<p class="text-center px-4 py-5 border-b">No data here yet.</p>
		</template>
	</o-table>
</template>

<style lang="scss" scoped>
.o-table__wrapper {
	@apply bg-white shadow rounded-md overflow-hidden;
	&--scrollable {
		@apply overflow-x-auto max-w-full;
	}
	&:deep(.o-table) {
		@apply min-w-full w-auto;
	}
	&:deep(.o-table thead) {
		@apply bg-gray-50 text-gray-500;
	}
	&:deep(.o-table tbody) {
		@apply bg-white;
	}
	&:deep(.o-table__th) {
		@apply px-6 py-3 text-left font-medium uppercase tracking-wider text-xs border-b border-gray-200 align-middle;
	}
	&:deep(.o-table__td) {
		@apply px-6 py-4 whitespace-nowrap border-b border-gray-200 align-middle;
	}
}
</style>
