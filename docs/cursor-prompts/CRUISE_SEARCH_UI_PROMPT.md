# Cursor AI prompt — Cruise homepage search (Month / Date / Cruise Line)

Copy everything below into a new Cursor chat when you need to extend or fix this feature.

---

## Prompt

```
Project: L'Heure De Voyage (Laravel 12). Cruise module is a quote-request platform (no API, no payments).

TASK: Cruise search UI must NOT use Journey Date / Return Date.

Replace the middle column of the Cruises tab on the homepage and on /cruises/search with:
1. Sailing From — `journey-date` (template date-picker, same as Flights Journey Date)
2. Sailing To — `return-date` (template date-picker, same as Flights Return Date)
3. Cruise Line — separate column, `cruise_line` dropdown

Use `resources/views/components/cruise-search-dates.blade.php` with classes `journey-date` / `return-date` and `journey-day-name` / `return-day-name`.

REUSE:
- Blade component: resources/views/components/cruise-search-dates.blade.php
- Backend: App\Services\CruiseSearchService (applyFilters, departureMonthOptions, cruiseLineOptions)
- CruiseController::catalogQuery() must call CruiseSearchService::applyFilters()
- Config: config/cruise.php → departure_month_horizon, duration_ranges

VIEWS TO KEEP IN SYNC:
- resources/views/pages/publicView/index.blade.php (Cruises tab #pills-6)
- resources/views/pages/publicView/cruise/cruiseList.blade.php

FRONTEND:
- public/assets/js/main.js — cruise-departure-month / cruise-departure-date handlers
- public/assets/css/style.css — .cruise-search-dates flex layout

QUOTE WIZARD:
- Pass departure_date, departure_month, cruise_line from search to cruise.quote.wizard
- CruiseBookingRequestService::buildContext() reads departure_date (and legacy journey-date)

DO NOT change Flights/Hotels search tabs. Do not rebuild the cruise module.

Verify: homepage Cruises tab → Search Now → /cruises/search?destination=&departure_month=&departure_date=&cruise_line=&adult=2
```

---

## Query parameters

| Param | Example | Backend |
|-------|---------|---------|
| `destination` | `mediterranean` or `Barcelona` | `CruiseSearchService::applyFilters` |
| `departure_month` | `2026-08` | Month filter (+ optional `available_months` JSON on cruises) |
| `departure_date` | `2026-08-15` | Passed to quote wizard; light catalog filter if `available_months` exists |
| `cruise_line` | `MSC Cruises` | `where('cruise_line', $line)` |
| `adult`, `children`, `infant` | `2`, `0`, `0` | Travelers (unchanged) |

## Admin note

Cruise lines in the dropdown come from `cruises.cruise_line` on active products. Add cruise lines in **Admin → Cruises → Create/Edit**.
