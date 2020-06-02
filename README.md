## Portpoliwo

Portpoliwo is simple personal portfolio application. This application uses [Laravel](https://github.com/laravel/laravel "Laravel") as the API server (backend) and [Quasar Framework](https://github.com/quasarframework/quasar "Quasar") as the API client (frontend).

This repository only contains backend code. If you want to see the frontend, you can see the [portpoliwo-client](https://github.com/husenisme/portpoliwo-client) repository.

## Features

- UUID as default primary database key
- Personal Access Token based Authentication from [Passport](https://laravel.com/docs/6.x/passport) with password reset and email verification.
- Categorized work with [Github GraphQL](https://developer.github.com/v4/) integration.
- Site settings, social media lists, etc.

## Demo

You can see my personal site at [husenis.me](http://husenis.me "husenis.me").

## Installation
1. Copy `.env.example` as `.env` and fill `APP_`, `CLIENT_URL`, `DB_`, `GITHUB_TOKEN`, and `MAIL_` settings.
2. Run `php artisan app:install` in project terminal. 
3. Run `php artisan serve` or direct access the app to `/public` folder.

## Credits

- [Laravel](https://github.com/laravel/laravel "Laravel")
- [Eloquid](https://github.com/datakrama/eloquid)
- [doctrine/dbal](https://github.com/doctrine/dbal)
- [fruitcake/laravel-cors](https://github.com/fruitcake/laravel-cors)
- [gmostafa/php-graphql-client](https://github.com/mghoneimy/php-graphql-client)
- [laravolt/avatar](https://github.com/laravolt/avatar)
- [ResponseBuilder](https://github.com/MarcinOrlowski/laravel-api-response-builder)
- [spatie/laravel-permission](https://github.com/spatie/laravel-permission)

## License

The Portfolio is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
