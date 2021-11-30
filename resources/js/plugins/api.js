import axios from 'axios';

const config = {
	headers: {
		'X-Requested-With': 'XMLHttpRequest',
		Accept: 'application/json',
	},
	withCredentials: true,
};

const request = axios.create(config);

const api = axios.create({
	...config,
	baseURL: '/api/',
});

export { request };
export default api;
