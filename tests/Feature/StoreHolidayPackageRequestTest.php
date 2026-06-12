<?php

namespace Tests\Feature;

use App\Models\Airline;
use App\Models\HolidayPackageRequest;
use App\Models\Master\ContactMethod;
use App\Models\Master\RequestPriority;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreHolidayPackageRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'destination' => 'Maldives',
            'adults' => 2,
            'full_name' => 'Jane Traveler',
            'email' => 'jane@example.com',
            'phone' => '+41 79 000 00 00',
            'gdpr_consent' => '1',
            'priority' => 'normal',
            'preferred_contact_method' => 'email',
        ], $overrides);
    }

    private function seedPriorities(): void
    {
        RequestPriority::query()->create([
            'name' => 'Normal',
            'slug' => 'normal',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    private function seedContactMethods(): void
    {
        ContactMethod::query()->create([
            'name' => 'Email',
            'slug' => 'email',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    private function seedAirlines(): void
    {
        Airline::query()->create([
            'name' => 'Swiss',
            'code' => 'LX',
            'slug' => 'swiss',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    private function seedRequiredCatalogs(): void
    {
        $this->seedPriorities();
        $this->seedContactMethods();
        $this->seedAirlines();
    }

    public function test_submit_succeeds_when_priorities_exist_and_catalogs_are_complete(): void
    {
        $this->seedRequiredCatalogs();

        $response = $this->postJson(route('holiday-package-requests.store'), $this->validPayload());

        $response->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('holiday_package_requests', [
            'destination' => 'Maldives',
            'priority' => 'normal',
            'preferred_contact_method' => 'email',
        ]);
    }

    public function test_validation_fails_when_no_priorities_configured(): void
    {
        $this->seedContactMethods();
        $this->seedAirlines();

        $response = $this->postJson(route('holiday-package-requests.store'), $this->validPayload([
            'priority' => 'normal',
        ]));

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['priority']);

        $this->assertSame(
            'No active request priorities configured.',
            $response->json('errors.priority.0')
        );
    }

    public function test_validation_fails_when_no_contact_methods_configured(): void
    {
        $this->seedPriorities();
        $this->seedAirlines();

        $response = $this->postJson(route('holiday-package-requests.store'), $this->validPayload([
            'preferred_contact_method' => 'email',
        ]));

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['preferred_contact_method']);

        $this->assertSame(
            'No active contact methods configured.',
            $response->json('errors.preferred_contact_method.0')
        );
    }

    public function test_validation_fails_when_no_airlines_configured(): void
    {
        $this->seedPriorities();
        $this->seedContactMethods();

        $response = $this->postJson(route('holiday-package-requests.store'), $this->validPayload([
            'preferred_airline' => 'swiss',
        ]));

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['preferred_airline']);

        $this->assertSame(
            'No airlines configured.',
            $response->json('errors.preferred_airline.0')
        );
    }

    public function test_request_is_saved_correctly_when_all_catalogs_are_populated(): void
    {
        $this->seedRequiredCatalogs();

        $response = $this->postJson(route('holiday-package-requests.store'), $this->validPayload([
            'preferred_airline' => 'swiss',
            'priority' => 'normal',
            'preferred_contact_method' => 'email',
        ]));

        $response->assertOk();

        $request = HolidayPackageRequest::query()->first();

        $this->assertNotNull($request);
        $this->assertSame('normal', $request->priority);
        $this->assertSame('email', $request->preferred_contact_method);
        $this->assertSame('swiss', $request->preferred_airline);
        $this->assertSame('Maldives', $request->destination);
    }

    public function test_arbitrary_airline_value_is_rejected_when_airlines_exist(): void
    {
        $this->seedRequiredCatalogs();

        $response = $this->postJson(route('holiday-package-requests.store'), $this->validPayload([
            'preferred_airline' => 'not-a-real-airline',
        ]));

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['preferred_airline']);
    }
}
