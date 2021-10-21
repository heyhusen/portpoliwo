import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import WindiCSS from 'vite-plugin-windicss';
import path from 'path';

export default defineConfig(({ command }) => ({
	base: command === 'serve' ? '' : '/dist/',
	publicDir: false,
	build: {
		manifest: true,
		outDir: 'public/dist',
		emptyOutDir: true,
		rollupOptions: {
			input: 'resources/js/app.js',
		},
	},
	resolve: {
		alias: {
			'@': path.resolve(__dirname, './resources/js'),
		},
	},
	server: {
		strictPort: true,
	},
	plugins: [
		{
			name: 'blade',
			handleHotUpdate({ file, server }) {
				if (file.endsWith('.blade.php')) {
					server.ws.send({
						type: 'full-reload',
						path: '*',
					});
				}
			},
		},
		vue(),
		WindiCSS(),
	],
}));
