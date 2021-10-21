import { ref, computed } from 'vue';

const isMobileOpen = ref(false);
const isDesktopOpen = ref(true);

export default function useMenu() {
	const mobileToggle = () => {
		isMobileOpen.value = !isMobileOpen.value;
	};

	const desktopToggle = () => {
		isDesktopOpen.value = !isDesktopOpen.value;
	};

	return {
		isMobileOpen: computed(() => isMobileOpen.value),
		mobileToggle,
		isDesktopOpen: computed(() => isDesktopOpen.value),
		desktopToggle,
	};
}
