<template>
	<footer class="bg-white border-t p-4 text-sm text-center sm:text-left">
		Copyright &copy; 2020-{{ year }} &middot; {{ appName }}
	</footer>
</template>

<script setup>
const today = new Date();
const year = today.getFullYear();

const { appName } = window.config;
</script>
