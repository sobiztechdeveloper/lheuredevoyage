<?php

namespace App\Console\Commands;

use App\Models\FlightSearch;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PruneFlightSearchesCommand extends Command
{
    protected $signature = 'flights:prune';

    protected $description = 'Prune expired SerpAPI and failed flight searches while preserving booked searches';

    public function handle(): int
    {
        $protectedIds = $this->protectedSearchIds();

        $anonymousIds = $this->candidateIds(
            provider: 'serpapi',
            olderThan: now()->subDays(7),
            userIdNull: true,
            protectedIds: $protectedIds,
        );

        $authenticatedIds = $this->candidateIds(
            provider: 'serpapi',
            olderThan: now()->subDays(30),
            userIdNull: false,
            protectedIds: $protectedIds,
        );

        $failedIds = $this->failedCandidateIds(
            olderThan: now()->subDays(3),
            protectedIds: $protectedIds,
        );

        $searchIds = $anonymousIds
            ->merge($authenticatedIds)
            ->merge($failedIds)
            ->unique()
            ->values();

        if ($searchIds->isEmpty()) {
            $this->info('No flight searches to prune.');
            Log::info('flights:prune completed', [
                'searches_deleted' => 0,
                'results_deleted' => 0,
                'storage_reclaimed_kb' => 0,
                'protected_search_count' => count($protectedIds),
            ]);

            return self::SUCCESS;
        }

        $resultsCount = DB::table('flight_search_results')
            ->whereIn('flight_search_id', $searchIds)
            ->count();

        $storageBytes = $this->estimateStorageBytes($searchIds);

        $deletedSearches = FlightSearch::query()
            ->whereIn('id', $searchIds)
            ->delete();

        $storageKb = round($storageBytes / 1024, 1);

        $summary = [
            'searches_deleted' => $deletedSearches,
            'results_deleted' => $resultsCount,
            'storage_reclaimed_kb' => $storageKb,
            'protected_search_count' => count($protectedIds),
            'anonymous_serpapi_deleted' => $anonymousIds->count(),
            'authenticated_serpapi_deleted' => $authenticatedIds->count(),
            'failed_deleted' => $failedIds->count(),
        ];

        Log::info('flights:prune completed', $summary);

        $this->info("Pruned {$deletedSearches} search(es), {$resultsCount} result(s), ~{$storageKb} KB reclaimed.");

        return self::SUCCESS;
    }

    /**
     * @return list<int>
     */
    private function protectedSearchIds(): array
    {
        $fromSearch = DB::table('flight_booking_requests')
            ->whereNotNull('flight_search_id')
            ->pluck('flight_search_id');

        $fromResults = DB::table('flight_booking_requests')
            ->join('flight_search_results', 'flight_search_results.id', '=', 'flight_booking_requests.flight_search_result_id')
            ->whereNotNull('flight_booking_requests.flight_search_result_id')
            ->pluck('flight_search_results.flight_search_id');

        return $fromSearch
            ->merge($fromResults)
            ->unique()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
    }

    /**
     * @param  list<int>  $protectedIds
     * @return Collection<int, int>
     */
    private function candidateIds(
        string $provider,
        \Illuminate\Support\Carbon $olderThan,
        bool $userIdNull,
        array $protectedIds,
    ): Collection {
        $query = FlightSearch::query()
            ->where('provider', $provider)
            ->where('created_at', '<', $olderThan);

        if ($userIdNull) {
            $query->whereNull('user_id');
        } else {
            $query->whereNotNull('user_id');
        }

        if ($protectedIds !== []) {
            $query->whereNotIn('id', $protectedIds);
        }

        return $query->pluck('id')->map(fn ($id) => (int) $id);
    }

    /**
     * @param  list<int>  $protectedIds
     * @return Collection<int, int>
     */
    private function failedCandidateIds(\Illuminate\Support\Carbon $olderThan, array $protectedIds): Collection
    {
        $query = FlightSearch::query()
            ->where('status', 'failed')
            ->where('created_at', '<', $olderThan);

        if ($protectedIds !== []) {
            $query->whereNotIn('id', $protectedIds);
        }

        return $query->pluck('id')->map(fn ($id) => (int) $id);
    }

    /**
     * @param  Collection<int, int>  $searchIds
     */
    private function estimateStorageBytes(Collection $searchIds): int
    {
        $bytes = 0;

        foreach (DB::table('flight_searches')->whereIn('id', $searchIds)->get(['search_response', 'search_payload']) as $search) {
            $bytes += strlen((string) ($search->search_response ?? ''));
            $bytes += strlen((string) ($search->search_payload ?? ''));
        }

        foreach (DB::table('flight_search_results')->whereIn('flight_search_id', $searchIds)->pluck('raw_offer') as $rawOffer) {
            $bytes += strlen((string) ($rawOffer ?? ''));
        }

        return $bytes;
    }
}
