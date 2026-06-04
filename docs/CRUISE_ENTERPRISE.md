# Cruise Enterprise Quotation Platform

L'Heure De Voyage — cruise **showcase** and **quote request** workflow (no cruise API, no payment gateway). Extends the existing `CruiseBookingRequest` module; reuses Quotes, Notifications, Activity Logs, Customer Dashboard, Status History, and private file storage.

## Migration

```bash
php artisan migrate
```

Migration: `2026_06_20_100000_upgrade_cruise_enterprise.php` (idempotent column checks for partial runs)

## Files created

| Path | Purpose |
|------|---------|
| `config/cruise.php` | Regions, cabin types, statuses, preferences, services, document types |
| `database/migrations/2026_06_20_100000_upgrade_cruise_enterprise.php` | Product + request schema |
| `app/Models/CruiseItineraryDay.php` | Itinerary rows |
| `app/Models/CruiseCabin.php` | Cabin types per cruise |
| `app/Models/CruiseGalleryImage.php` | Gallery |
| `app/Models/CruiseRequestDocument.php` | Private uploads |
| `app/Services/CruiseProductAdminService.php` | Admin 6-section save |
| `app/Services/CruiseDocumentService.php` | Private `local` disk files |
| `app/Http/Requests/StoreCruiseQuoteRequest.php` | 8-step wizard validation |
| `resources/views/admin/cruises/form.blade.php` | Admin product form |
| `resources/views/pages/publicView/cruise/show.blade.php` | Public cruise detail |
| `resources/views/pages/publicView/cruise/cruiseQuoteWizard.blade.php` | 8-step quote wizard |
| `docs/CRUISE_ENTERPRISE.md` | This document |

## Files modified (high level)

- `app/Models/Cruise.php`, `CruiseBookingRequest.php`, `CruiseBookingPassenger.php`
- `app/Services/CruiseBookingRequestService.php`, `QuoteService.php`
- `app/Http/Controllers/Admin/CruiseAdminController.php`, `CruiseBookingRequestAdminController.php`
- `app/Http/Controllers/CruiseController.php`, `CruiseBookingRequestController.php`
- `app/Http/Controllers/BookingRequestFileController.php`, `Admin/AdminBookingRequestFileController.php`
- `resources/views/components/cruise-booking-status.blade.php`
- `resources/views/pages/publicView/cruise/cruiseBookingConfirmation.blade.php`
- `routes/web.php`, `routes/admin.php`

## Routes added / changed

### Public

| Method | URI | Name |
|--------|-----|------|
| GET | `/cruises/request-quote` | `cruise.quote.wizard` |
| GET | `/cruises/request-quote/{slug}` | (pre-select cruise) |
| GET | `/cruises/{slug}` | `cruise.show` (custom detail view) |
| POST | `/cruise-booking-request` | `cruise.booking.store` |
| GET | `/booking-files/cruise/{id}/uploads/{doc}` | `booking-files.cruise.uploaded` |

`cruise.book` redirects to the quote wizard with search params.

### Admin

| Method | URI | Name |
|--------|-----|------|
| GET/POST/PUT | `/admin/cruises/create`, `edit`, `store`, `update` | Custom product CRUD |
| GET | `/admin/cruise-requests` | Operations list |
| POST | `/admin/cruise-requests/{id}/generate-quote` | Quote integration |
| GET | `/admin/booking-files/cruise/{id}/uploaded/{doc}` | `admin.booking-files.cruise.uploaded` |

Admin cruise documents: `voucher`, `invoice`, `ticket`, `boarding`, `excursion` + customer uploads.

## Database changes

### `cruises`

`cruise_code`, `cruise_line`, `ship_class`, capacity, region, ports, nights, short description, PDFs, included/not-included JSON, `sort_order`, `featured`, `status`.

### New tables

- `cruise_itinerary_days`
- `cruise_cabins`
- `cruise_gallery_images`
- `cruise_request_documents`

### `cruise_booking_requests`

`cruise_cabin_id`, lead passenger fields, emergency contact, preferences, `boarding_instructions_path`, `excursion_details_path`, privacy/terms timestamps.

### Status values (`config/cruise.statuses`)

`new`, `under_review`, `waiting_documents`, `quoted`, `accepted`, `rejected`, `voucher_sent`, `completed`, `cancelled`

Legacy map: `contacted` → `under_review`, `awaiting_customer` → `waiting_documents`, `confirmed` → `accepted`.

## Customer flow (8 steps)

1. Select cruise (+ departure date)
2. Cabin + adults/children/infants
3. Lead passenger
4. Additional passengers
5. Preferences (dining, bed, accessibility, celebration)
6. Emergency contact + special requests
7. Optional documents (passport, visa, insurance, other)
8. Review → **Request Cruise Quote**

## Quote integration

- Admin: **Generate Quote** on cruise request (existing quote module)
- On quote sent: request status → `quoted` (`QuoteService::syncCruiseRequestQuoted`)
- Customer accept/reject via existing signed quote URLs

## Testing checklist

- [ ] Admin: `/admin/cruises/create` — all 6 sections save (itinerary, cabins, PDFs, gallery)
- [ ] Public: `/cruises` list loads; filters/sort
- [ ] Public: `/cruises/{slug}` detail + **Request Cruise Quote**
- [ ] Wizard: `/cruises/request-quote/{slug}` — 8 steps, occupancy warning, submit
- [ ] Confirmation page copy (quote request, not booking)
- [ ] Admin: `/admin/cruise-requests` filters + detail + status timeline + internal notes
- [ ] Quote generate → status `quoted`; customer accept/reject
- [ ] Private downloads: customer + admin document routes
- [ ] Customer: **My Cruise Bookings** — reference, status, documents, quotes
- [ ] Notifications + emails on create, status, quote, voucher
- [ ] Mobile layout on wizard and detail
- [ ] `php artisan route:list` — no 404 on cruise routes
- [ ] No N+1 on list/detail (eager `itineraryDays`, `cabins`, `galleryImages`)

## Production readiness

| Area | Status |
|------|--------|
| Schema migration | Run in staging/production after backup |
| Private storage | `FILESYSTEM_DISK=local`; ensure `storage/app` not web-public |
| Policies / ownership | Booking request policies + signed confirmation URLs |
| Config | `config/cruise.php` — tune regions/status labels per client |
| Ops menu | Admin → Cruise Requests under Operations |
| No payment | UI uses “Request Cruise Quote” only |
| Activity log | `cruise_booking_request.created` and status changes |

## URLs to smoke-test

- http://127.0.0.1:8000/cruises/
- http://127.0.0.1:8000/admin/cruises/create
- http://127.0.0.1:8000/cruises/request-quote/{slug}
- http://127.0.0.1:8000/admin/cruise-requests
