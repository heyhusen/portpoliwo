import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

export default function useSignOut() {
	const store = useStore();
	const router = useRouter();

	const signOut = async () => {
		await store.dispatch('auth/logOut');
		await router.push({ name: 'login' });
	};

	return { signOut };
}
