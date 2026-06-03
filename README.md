# L'Heure De Voyage

Multi-product travel booking platform built with Laravel 13. Browse flights, hotels, cruises, rental cars, travel insurance, and holiday packages. Customers can register, manage bookings, and contact the agency. Admins manage catalog and messages from a dedicated panel.

## Requirements

- PHP 8.3+
- Composer
- Node.js 18+ (for Vite assets)
- SQLite (default) or MySQL

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run build
php artisan serve
```

Visit `http://localhost:8000`.

## Default accounts

| Role     | Email                      | Password  |
|----------|----------------------------|-----------|
| Admin    | admin@lheuredevoyage.com   | password  |
| Customer | customer@example.com       | password  |

- Admin panel login: `/admin/login` (then `/admin/dashboard`)
- Customer login: `/login` and register: `/register`
- Customer dashboard: `/my-dashboard`

## Features

- Dynamic catalog listings with search, detail, and booking pages
- User dashboard with bookings, wallet, notifications, profile, and settings
- Contact form stored in database
- **Admin CMS**: website settings, hero sections (drag-and-drop order), testimonials, FAQs, contact details
- Admin CRUD for catalog products, contact inquiries, and about page content

## Admin CMS routes

| URL | Purpose |
|-----|---------|
| `/admin/dashboard` | Admin home |
| `/admin/settings` | Company info, logo, favicon, social links, footer |
| `/admin/hero-sections` | Homepage hero slides |
| `/admin/testimonials` | Customer reviews |
| `/admin/faqs` | FAQ entries |
| `/admin/contact-details` | Office address, phone, map embed |

Uploads are stored under `storage/app/public/cms/` (run `php artisan storage:link`).

## Development

```bash
composer dev
```

Runs the PHP server, queue worker, logs, and Vite dev server concurrently.

## Tests

```bash
php artisan test
```
