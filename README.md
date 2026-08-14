# trello

A lightweight Trello‑like application built with Laravel and Vue, designed for fast prototyping and easy customization.

## Tech Stack
![Laravel](https://img.shields.io/badge/Laravel-8.0-f05340?logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?logo=vue.js)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql)
![Node.js](https://img.shields.io/badge/Node.js-18.x-339933?logo=nodedotjs)

## Features
- Create and manage boards, lists, and cards with drag‑and‑drop.
- Real‑time updates using Laravel Echo and Vue reactivity.
- Team collaboration with role‑based permissions.
- Secure authentication powered by Laravel Fortify.
- Responsive UI for desktop and mobile devices.

## Installation

```bash
# 1. Clone the repository
git clone https://github.com/brunorossani/trello.git
cd trello

# 2. Install PHP dependencies
composer install

# 3. Install front‑end dependencies
npm install

# 4. Set up environment variables
cp .env.example .env
php artisan key:generate

# 5. Run database migrations
php artisan migrate

# 6. Build assets
npm run dev   # or npm run prod for production

# 7. Start the development server
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Contributing
Feel free to open issues or submit pull requests. Please follow the conventional commit style and ensure tests pass.

## License
This project is open‑source and available under the MIT License.