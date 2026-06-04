# Travel Insurance Enterprise Platform

L'Heure De Voyage — insurance product showcase and **quote request** workflow (no API, no payment gateway). Agents process requests manually and use the existing **Quote** module.

## Migration

Run once in each environment:

```bash
php artisan migrate
```

Migration: `2026_06_19_100000_upgrade_travel_insurance_enterprise.php`

## Files created

| Path | Purpose |
|------|---------|
| `config/insurance.php` | Plan types, purposes, statuses, document types, CMS block keys |
| `database/migrations/2026_06_19_100000_upgrade_travel_insurance_enterprise.php` | Schema upgrade |
| `app/Models/InsurancePlanBenefit.php` | Product benefits |
| `app/Models/InsurancePlanExclusion.php` | Product exclusions |
| `app/Models/InsurancePlanGalleryImage.php` | Product gallery |
| `app/Models/InsuranceRequestDocument.php` | Private customer uploads |
| `app/Models/InsuranceCmsBlock.php` | CMS blocks (seeded) |
| `app/Services/InsuranceProductAdminService.php` | Admin product save |
| `app/Services/InsuranceDocumentService.php` | Private file storage (`local` disk) |
| `app/Http/Requests/StoreInsuranceQuoteRequest.php` | Wizard validation |
| `resources/views/admin/insurances/form.blade.php` | 6-section product form |
| `resources/views/pages/publicView/travelInsurance/insuranceQuoteWizard.blade.php` | 7-step quote wizard |
| `resources/views/pages/publicView/travelInsurance/show.blade.php` | Public product detail |
| `docs/INSURANCE_ENTERPRISE.md` | This document |

## Files modified (high level)

- `app/Models/TravelInsurance.php`, `InsuranceBookingRequest.php`, `InsuranceBookingTraveler.php`
- `app/Services/InsuranceBookingRequestService.php`, `QuoteService.php`
- `app/Http/Controllers/Admin/TravelInsuranceAdminController.php`, `InsuranceBookingRequestAdminController.php`
- `app/Http/Controllers/InsuranceBookingRequestController.php`, `TravelInsuranceController.php`
- `app/Http/Controllers/BookingRequestFileController.php`, `Admin/AdminBookingRequestFileController.php`
- `app/Http/Controllers/UserInsuranceBookingRequestController.php`, `UserQuoteController.php`
- Admin & customer insurance views, `components/insurance-booking-status.blade.php`
- `routes/web.php`, `routes/admin.php`
- Removed duplicate `app/Models/travelInsurance.php` (case collision on Windows)

## Routes added / changed

### Public

| Method | URI | Name |
|--------|-----|------|
| GET | `/travelinsurances/request-quote` | `travelinsurance.quote.wizard` |
| GET | `/travelinsurances/request-quote/{slug}` | (pre-select plan) |
| POST | `/travelinsurance-booking-request` | `travelinsurance.booking.store` |
| GET | `/booking-files/insurance/{id}/uploads/{doc}` | `booking-files.insurance.uploaded` |

Legacy `booking-request` routes redirect to the wizard where applicable.

### Admin

| Method | URI | Name |
|--------|-----|------|
| GET/POST/PUT | `/admin/insurances/*` | Custom product CRUD (`form.blade.php`) |
| GET | `/admin/booking-files/insurance/{id}/uploaded/{doc}` | `admin.booking-files.insurance.uploaded` |

Document download supports: `policy`, `invoice`, `coverage`, `coverage_certificate`, `claim_instructions`.

## Database changes

### `travel_insurances`

Company, plan code/type, coverage amounts, eligibility, pricing fields, PDFs, logo, `sort_order`, etc.

### New tables

- `insurance_plan_benefits`
- `insurance_plan_exclusions`
- `insurance_plan_gallery_images`
- `insurance_request_documents`
- `insurance_cms_blocks` (seeded: FAQ, terms, claims, emergency, advice)

### `insurance_booking_requests`

`destination_country`, `purpose_of_travel`, risk flags, `privacy_accepted`, `terms_accepted_at`, `claim_instructions_path`, etc. Status values migrated to enterprise flow.

### `insurance_booking_travelers`

`is_primary`, `relationship`, `passport_expiry`

## Status flow

`new` → `under_review` → `waiting_customer_documents` → `quoted` → `accepted` / `rejected` → `policy_issued` → `completed` | `cancelled`

When a quote is **sent**, request status becomes **`quoted`** (via `QuoteService` + `InsuranceBookingRequestService`).

## Quote integration

Reuse existing admin quote UI:

- **Operations → Insurance Requests → Generate Quote**
- Send PDF / customer accept / reject unchanged
- Accept/reject syncs insurance request status

## Document storage

- **Customer uploads**: `storage/app/private` via `local` disk → `insurance_request_documents`
- **Admin PDFs**: `policy_path`, `invoice_path`, `coverage_document_path`, `claim_instructions_path` on `local` disk
- Served only through authenticated `booking-files` routes

## Testing checklist

- [ ] `php artisan migrate` on staging/production
- [ ] Admin: create/edit insurance plan (`/admin/insurances/create`)
- [ ] Public: list + product detail + **Request Quote** CTA
- [ ] 7-step wizard: all steps, plan selection, additional travelers, submit
- [ ] Confirmation copy says **quote request**, not purchase
- [ ] Admin: list filters (reference, status, destination, company, travel/submitted dates)
- [ ] Admin: detail (summary, travelers, risk, documents, timeline, internal notes)
- [ ] Generate quote → status `quoted`
- [ ] Customer: accept/reject quote → status `accepted` / `rejected`
- [ ] Admin document upload + secure download (customer + admin)
- [ ] Customer dashboard: list + detail (timeline, quotes, documents)
- [ ] Notifications / emails on new request and status change
- [ ] Mobile layout on wizard and detail pages
- [ ] No route 404s; check N+1 (`with()` on list/show)

## Production readiness

| Area | Status | Notes |
|------|--------|-------|
| Schema | Ready | Migration applied in dev |
| Security | Ready | Policies, form requests, ownership on files, soft deletes |
| Quote flow | Ready | Reuses existing module |
| Payments | N/A | By design |
| CMS blocks admin UI | Partial | Data seeded; optional admin CRUD not built — edit via DB/tinker or add later |
| Automated tests | Not added | Manual QA per checklist above |
| Wizard JS | Ready | Uses `cbw-form` + `catalog-booking-wizard.js` |

## Optional follow-ups

1. Admin UI for `insurance_cms_blocks` (FAQ, terms, claims, emergency, advice)
2. Expand admin product form with all coverage numeric fields from spec
3. Customer document upload step in wizard (post-submit upload in dashboard)
4. Feature tests for `StoreInsuranceQuoteRequest` and quote status sync
