## Portpoliwo

Portpoliwo is my simple personal portfolio application. This application uses [Laravel](https://github.com/laravel/laravel "Laravel") as backend, and [Vue.js](https://github.com/vuejs/vue "Vue.js") as SPA.

> This is my first Vue.js project.

## Features

- UUID as default primary database key
- SPA Authentication from [Sanctum](https://laravel.com/docs/7.x/sanctum#spa-authentication "Sanctum")
- Site settings, works, social media lists, etc.

## Installation
1. Run command `composer install` on terminal.
2. Copy `.env.example` as `.env` and fill `APP_*`, `DB_*`, and `MAIL_*` settings.
3. Run `php artisan app:install` in project terminal. 
4. Run `yarn install` and `yarn dev` for Vue development.
5. Run `php artisan serve` or direct access the app to `/public` folder.

## Credits

- [Ahmad Husen](https://github.com/husenisme)

## License

The Portpoliwo is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
