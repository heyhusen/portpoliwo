const mix = require('laravel-mix')
const VueAutoRoutingPlugin = require('vue-auto-routing/lib/webpack-plugin')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
  resolve: {
    extensions: ['.js', '.vue'],
    alias: {
      '@': __dirname + '/resources',
    },
  },
  module: {
    rules: [
      {
        enforce: 'pre',
        exclude: /node_modules/,
        loader: 'eslint-loader',
        test: /\.(js|vue)?$/,
      },
    ],
  },
  output: {
    publicPath: '/',
    chunkFilename: 'js/components/[name].js',
  },
  plugins: [
    new VueAutoRoutingPlugin({
      pages: 'resources/js/pages',
      importPrefix: '@/js/pages/',
    }),
  ],
})

mix
  .js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')

if (mix.inProduction()) {
  mix.version()
}
