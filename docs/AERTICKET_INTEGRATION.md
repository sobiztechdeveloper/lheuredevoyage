# AERTiCKET Cockpit API Integration

## Overview

B2C flight flows use a **service-layer architecture** only. Controllers delegate to `FlightSearchOrchestrator`; all HTTP and parsing live under `App\Services\Aerticket`.

When `AERTICKET_ENABLED=false`, the existing **mock** `FlightSearchService` is used (unchanged behaviour).

## Configuration

Copy from `.env.example`:

| Variable | Purpose |
|----------|---------|
| `AERTICKET_ENABLED` | Toggle live API |
| `AERTICKET_API_URL` | Base URL from AERTiCKET (Server) |
| `AERTICKET_ACCESS_KEY` | Access Key |
| `AERTICKET_USERNAME` | Login Name |
| `AERTICKET_PASSWORD` | Login Password |
| `AERTICKET_AGENCY_CODE` | Agency code |
| `AERTICKET_ENDPOINT_*` | Override paths after receiving official API spec |

Endpoints are defined in `config/aerticket.php` and are **placeholders** until AERTiCKET delivers your Cockpit API paths.

## Services

| Service | Responsibility |
|---------|----------------|
| `AerticketAuthService` | Authenticate, cache token |
| `AerticketHttpClient` | HTTP, logging, errors |
| `AerticketFlightSearchService` | Search + offer detail |
| `AerticketFareRuleService` | Fare rules |
| `AerticketBookingService` | Create booking, availability |
| `AerticketTicketingService` | Issue ticket |
| `AerticketResponseMapper` | Normalize API JSON to internal shape |
| `FlightSearchOrchestrator` | Mock vs AERTiCKET routing |

## Logging

Every call writes to `aerticket_api_logs` (when `AERTICKET_LOG_REQUESTS=true`) and the application log channel. Passwords are redacted.

## Public routes

| Route | Description |
|-------|-------------|
| `POST /flights/search` | Search (mock or AERTiCKET) |
| `GET /flights/results/{flightSearch}` | Results list |
| `GET /flights/results/{flightSearch}/offer/{offerId}` | Offer detail |
| `GET /flights/results/{flightSearch}/offer/{offerId}/fare-rules` | Fare rules |

## After onboarding

1. Set credentials in `.env`
2. Set `AERTICKET_ENABLED=true`
3. Update `AERTICKET_ENDPOINT_*` values to match Cockpit API documentation
4. Adjust `AerticketResponseMapper` if field names differ
5. Test search → results → detail → fare rules in sandbox

## B2B

Not implemented in this phase. Booking/ticketing services are ready for future wiring but are not exposed on public routes yet.
