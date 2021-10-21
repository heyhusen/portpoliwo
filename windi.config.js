import { defineConfig } from 'windicss/helpers';
import defaultTheme from 'windicss/defaultTheme';
import forms from 'windicss/plugin/forms';
import typography from 'windicss/plugin/typography';

export default defineConfig({
	darkMode: 'class',
	extract: {
		include: ['resources/**/*.{js,php,vue}'],
	},
	theme: {
		extend: {
			colors: {
				primary: defaultTheme.colors.indigo[600],
				secondary: defaultTheme.colors.pink[600],
				info: defaultTheme.colors.blue[600],
				success: defaultTheme.colors.emerald[600],
				warning: defaultTheme.colors.amber[600],
				error: defaultTheme.colors.red[600],
			},
		},
	},
	plugins: [forms, typography],
});
