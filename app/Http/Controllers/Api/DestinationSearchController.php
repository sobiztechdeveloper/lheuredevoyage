<?php

namespace App\Http\Controllers\Api;

use App\Enums\DestinationType;
use App\Http\Controllers\Controller;
use App\Services\DestinationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DestinationSearchController extends Controller
{
    public function __construct(
        protected DestinationService $destinationService,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('query', $request->input('q', '')));
        $types = $this->destinationService->resolveTypesFromRequest($request);

        if ($types === [] && $request->filled('context')) {
            $types = DestinationType::forContext((string) $request->input('context'));
        }

        $format = $request->input('format', 'default');

        return response()->json(
            $this->destinationService->apiSearchResults($query, $types, $format)
        );
    }
}
