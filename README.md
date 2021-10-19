# Portpoliwo

[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/hapakaien/portpoliwo/CI?label=CI&style=flat-square)](https://github.com/hapakaien/portpoliwo/actions) [![GitHub tag (latest SemVer)](https://img.shields.io/github/v/tag/hapakaien/portpoliwo?label=version&style=flat-square)](https://github.com/hapakaien/portpoliwo/tags)

Portpoliwo is a simple personal portfolio application. This application uses
[Laravel](https://github.com/laravel/laravel "Laravel") as back-end, and
[Vue.js](https://github.com/vuejs/vue "Vue.js") as SPA (front-end).

> This is my first Vue.js project.

## Features

- UUID as default primary database key
- SPA Authentication from [Sanctum](https://laravel.com/docs/7.x/sanctum#spa-authentication "Sanctum")
- Site settings, works, social media lists, etc.

## Setup

### Local

1. Clone this repository to your machine.

   ```bash
   git clone -b main --depth 1 --single-branch https://github.com/hapakaien/portpoliwo.git && cd portpoliwo 
   ```

2. Install dependencies.

    ```bash
    composer install && yarn
    ```

3. Run docker compose.

   ```bash
   docker-compose up -d
   ```

4. Set up application.

   ```bash
   php artisan app:install
   ```

5. Build Vue SPA.

   ```bash
   yarn dev
   ```

6. Run application, and visit <http://127.0.0.1:8000> in your web browser.

   ```bash
   php artisan serve
   ```

## Testing

### Laravel

1. Install dependencies.

   ```bash
   composer install
   ```

2. Remove `.env` file, if it exists.
3. Run test.

   ```bash
   php artisan test
   ```
