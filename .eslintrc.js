module.exports = {
	env: {
		browser: true,
		es2021: true,
	},
	extends: [
		'eslint:recommended',
		'plugin:vue/vue3-recommended',
		'airbnb-base',
		'plugin:prettier/recommended',
	],
	parserOptions: {
		ecmaVersion: 2022,
		sourceType: 'module',
	},
	plugins: [],
	rules: {
		'import/no-extraneous-dependencies': [
			'error',
			{
				devDependencies: true,
			},
		],
		'vue/script-setup-uses-vars': 'error',
	},
};
