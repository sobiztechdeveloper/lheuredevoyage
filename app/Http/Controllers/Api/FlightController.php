<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\SerpApi\SerpApiException;
use App\Http\Controllers\Controller;
use App\Services\FlightService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function __construct(
        protected FlightService $flightService,
    ) {}

    public function autocomplete(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('query', $request->input('q', '')));

        if (mb_strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            return response()->json($this->flightService->searchAirports($query));
        } catch (SerpApiException $exception) {
            return response()->json([
                'error' => $exception->userMessage(),
            ], $exception->getCode() >= 400 ? $exception->getCode() : 503);
        }
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $data = $this->flightService->searchFlights($request->all());

            return response()->json([
                'results' => $data['flights'] ?? [],
                'count' => count($data['flights'] ?? []),
            ]);
        } catch (SerpApiException $exception) {
            $status = match (true) {
                $exception->getCode() === 429 => 429,
                str_contains(strtolower($exception->getMessage()), 'no flights') => 404,
                default => 503,
            };

            return response()->json([
                'error' => $exception->userMessage(),
                'results' => [],
            ], $status);
        }
    }
}
