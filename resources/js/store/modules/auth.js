import api, { request } from '@/plugins/api';

export default {
	namespaced: true,

	state() {
		return {
			authenticated: false,
			user: null,
		};
	},

	getters: {
		authenticated(state) {
			return state.authenticated;
		},
		user(state) {
			return state.user;
		},
	},

	mutations: {
		setAuthenticated(state, value) {
			state.authenticated = value;
		},
		setUser(state, value) {
			state.user = value;
		},
	},

	actions: {
		async logIn({ dispatch }, credentials) {
			await request.get('/sanctum/csrf-cookie');
			await request.post('/login', credentials);

			return dispatch('me');
		},

		async logOut({ dispatch }) {
			await request.get('/sanctum/csrf-cookie');
			await request.post('/logout');

			return dispatch('me');
		},

		me({ commit }) {
			return api
				.get('/account/me')
				.then(({ data: { data } }) => {
					commit('setAuthenticated', true);
					commit('setUser', data);
				})
				.catch(() => {
					commit('setAuthenticated', false);
					commit('setUser', null);
				});
		},
	},
};
