<template>
	<o-button v-bind="$props"><slot /></o-button>
</template>

<style lang="scss" scoped>
.o-btn {
	@apply px-4 py-2 rounded-md duration-200 items-center bg-white text-gray-600 border border-gray-300;
	&:hover {
		@apply bg-gray-100;
	}
	&:focus {
		@apply outline-none;
	}
	&:deep(.o-btn__wrapper) {
		> * {
			@apply m-0;
		}
	}
	&--small {
		@apply py-1.5 px-3 text-sm;
	}
	&--primary {
		@apply bg-primary text-indigo-50 border-none;
		&:hover {
			@apply bg-indigo-700;
		}
	}
	&--info {
		@apply bg-info text-blue-50 border-none;
		&:hover {
			@apply bg-blue-700;
		}
	}
	&--success {
		@apply bg-success text-emerald-50 border-none;
		&:hover {
			@apply bg-emerald-700;
		}
	}
	&--warning {
		@apply bg-warning text-amber-50 border-none;
		&:hover {
			@apply bg-amber-700;
		}
	}
	&--danger {
		@apply bg-error text-red-50 border-none;
		&:hover {
			@apply bg-red-700;
		}
	}
	&--inverted {
		@apply bg-gray-600 text-white border-none;
		&:hover {
			@apply bg-gray-700;
		}
	}
	&--outlined-primary {
		@apply bg-white border-primary border text-primary;
		&:hover {
			@apply bg-primary text-indigo-50;
		}
	}
	&--outlined-danger {
		@apply bg-white border-error border text-error;
		&:hover {
			@apply bg-error text-red-50;
		}
	}
}
</style>
