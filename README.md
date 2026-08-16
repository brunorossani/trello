# trello

A lightweight Trello‑like application built with Laravel and Vue, designed for fast prototyping and easy customization.

## Tech Stack
![Laravel](https://img.shields.io/badge/Laravel-11.x-f05340?logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?logo=vue.js)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)
![SQLite](https://img.shields.io/badge/SQLite-3-003B57?logo=sqlite)
![Node.js](https://img.shields.io/badge/Node.js-18.x-339933?logo=nodedotjs)

## Features
- Create and manage boards, lists, and cards with drag‑and‑drop.
- Real‑time updates using Laravel Echo and Vue reactivity.
- Team collaboration with role‑based permissions.
- Secure authentication powered by Laravel Fortify.
- Responsive UI for desktop and mobile devices.

## Installation

1. Clone the repository: `git clone https://github.com/elkiki99/trello.git`
2. Install dependencies: `composer install && npm install`
3. Copy the environment file: `cp .env.example .env`
4. Generate the application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Start the development server: `php artisan serve && npm run dev`
