<template>
	<o-notification
		aria-close-label="Close notification"
		role="alert"
		v-bind="$props"
		closable
	>
		<slot />
	</o-notification>
</template>

<style lang="scss" scoped>
.o-notification {
	@apply rounded-md p-4 text-sm bg-indigo-100 text-primary;
	&--info {
		@apply bg-blue-100 text-info;
	}
	&--success {
		@apply bg-emerald-100 text-success;
	}
	&--warning {
		@apply bg-amber-100 text-warning;
	}
	&--danger {
		@apply bg-red-100 text-error;
	}
	&:deep(.o-notification__close) {
		@apply appearance-none;
		&:focus {
			@apply outline-none;
		}
	}
}
</style>
