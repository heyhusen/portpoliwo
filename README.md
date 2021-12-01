# Portpoliwo

[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/hapakaien/portpoliwo/CI?label=CI&style=flat-square)](https://github.com/hapakaien/portpoliwo/actions) ![GitHub](https://img.shields.io/github/license/hapakaien/portpoliwo?style=flat-square)

Portpoliwo is a simple headless CMS for personal portfolio site. This project
uses [Laravel](https://github.com/laravel/laravel "Laravel") as back-end, and
[Vue.js](https://github.com/vuejs/vue "Vue.js") as SPA (front-end).  
Here are some of the features it includes:

- Easy authentication with Laravel Sanctum
- Using Redis as session and cache driver
- Storage flexibility with S3-compatible object storage
- Replaced Laravel Mix with Vite for faster development
- Lightweight interface with Headless UI, Oruga UI and Windi CSS
- Good app performance with SVG icons from Heroicons and FontAwesome

## Motivation

As long as I've been using Laravel, I've always relied on jQuery for front-end
development. I rarely even use vanilla JavaScript, which seems to have
progressed a lot without me realizing it.

I admit I'm late to learn JavaScript in depth. But, isn't it better late than
never? :)  
This project is where I started learning modern vanilla JavaScript. My preferred
framework for this is Vue.js. I've always loved playing with CSS, and Vue loves
it too. Apart from that, it seems like Laravel is also easier to integrate with
Vue.js.

In essence, I use this project as a learning medium, especially Laravel and
Vue.js. I'll try to follow these two frameworks, and update the project as best
I can.

## Setup

If you are interested in trying Portpoliwo, you can do the following.

### Local

For local setup, you need at least 2 terminals.

1. Clone this repository to your machine.

   ```bash
   git clone -b main --depth 1 --single-branch https://github.com/hapakaien/portpoliwo.git && cd portpoliwo 
   ```

2. Install dependencies.

    ```bash
    composer install && pnpm install
    ```

3. Run docker compose, and wait until all container running perfectly.

   ```bash
   docker-compose up -d
   ```

4. Set up application.

   ```bash
   php artisan app:install
   ```

5. Start Vue development server.

   ```bash
   pnpm dev
   ```

6. Open second terminal, start Laravel development server, and visit <http://127.0.0.1:8000> in your web browser.

   ```bash
   php artisan serve
   ```

## Testing

### Laravel

1. Install dependencies.

   ```bash
   composer install
   ```

2. Run test.

   ```bash
   php artisan test --env=testing
   ```
