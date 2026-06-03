# Admin CMS — Implementation Plan (Completed)

## Phase 1 — Admin authentication (reused Breeze)

- `is_admin` on `users` table (existing)
- Middleware `admin` → [`EnsureUserIsAdmin`](app/Http/Middleware/EnsureUserIsAdmin.php)
- Admins log in via `/login` (same Breeze flow); redirect to `/admin/dashboard`
- Layout: [`resources/views/layouts/admin/`](resources/views/layouts/admin/)

## Phase 2 — Database & models

| Table | Model | Notes |
|-------|-------|-------|
| `website_settings` | `WebsiteSetting` | Singleton row, cached |
| `hero_sections` | `HeroSection` | Soft deletes, sort_order |
| `testimonials` | `Testimonial` | Soft deletes |
| `faqs` | `Faq` | Soft deletes |
| `contact_details` | `ContactDetail` | Singleton row, cached |

## Phase 3 — Admin CRUD

- Form requests in `app/Http/Requests/Admin/`
- Resource/single controllers in `app/Http/Controllers/Admin/`
- Image uploads via [`CmsImageUploader`](app/Services/CmsImageUploader.php)
- Routes in [`routes/admin.php`](routes/admin.php)

## Phase 4 — Frontend integration

- View composer shares `$siteSettings` and `$siteContact` on layout partials
- Partials in `resources/views/partials/cms/`
- Home, About, Contact, Footer driven from CMS data

## Phase 5 — Seed & deploy

```bash
php artisan migrate
php artisan db:seed --class=CmsSeeder
php artisan storage:link
```

## Out of scope (per requirements)

- AERTiCKET, booking engine, payments, B2B/VIP portals
