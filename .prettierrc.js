module.exports = {
	singleQuote: true,
	trailingComma: 'all',
	overrides: [
		{
			files: ['*.{html,json,md}'],
			options: {
				singleQuote: false,
			},
		},
	],
};
