<?php

namespace App\Services;

use App\Models\TravelDestination;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DestinationImportService
{
    public function __construct(
        protected DestinationService $destinationService,
        protected ActivityLogService $activityLog,
    ) {}

    /**
     * @return array{created: int, updated: int, skipped: int, errors: list<string>}
     */
    public function importFromCsv(UploadedFile $file, string $dataset): array
    {
        $mapping = config("destinations.import_mappings.{$dataset}");

        if (! $mapping) {
            throw new \InvalidArgumentException("Unknown import dataset: {$dataset}");
        }

        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            throw new \RuntimeException('Unable to read uploaded CSV file.');
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            throw new \RuntimeException('CSV file is empty.');
        }

        $headerMap = $this->normalizeHeaderMap($header);
        $columnMap = $this->resolveColumnIndexes($mapping['columns'], $headerMap);

        $stats = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => []];
        $rowNumber = 1;

        DB::transaction(function () use ($handle, $mapping, $columnMap, &$stats, &$rowNumber) {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                if ($this->isEmptyRow($row)) {
                    continue;
                }

                try {
                    $payload = $this->mapRow($row, $columnMap, $mapping['type']);

                    if (empty($payload['name'])) {
                        $stats['skipped']++;
                        continue;
                    }

                    $result = $this->upsertRow($payload);
                    $stats[$result]++;
                } catch (\Throwable $e) {
                    $stats['errors'][] = "Row {$rowNumber}: {$e->getMessage()}";
                }
            }
        });

        fclose($handle);

        $this->activityLog->log('destinations.imported', null, [
            'dataset' => $dataset,
            'stats' => $stats,
        ]);

        return $stats;
    }

    /**
     * @param  list<string|null>  $header
     * @return array<string, int>
     */
    protected function normalizeHeaderMap(array $header): array
    {
        $map = [];

        foreach ($header as $index => $label) {
            if ($label === null) {
                continue;
            }

            $map[Str::slug(strtolower(trim($label)), '_')] = $index;
        }

        return $map;
    }

    /**
     * @param  array<string, list<string>>  $columns
     * @param  array<string, int>  $headerMap
     * @return array<string, int|null>
     */
    protected function resolveColumnIndexes(array $columns, array $headerMap): array
    {
        $resolved = [];

        foreach ($columns as $field => $aliases) {
            $resolved[$field] = null;

            foreach ($aliases as $alias) {
                $key = Str::slug(strtolower($alias), '_');

                if (isset($headerMap[$key])) {
                    $resolved[$field] = $headerMap[$key];
                    break;
                }
            }
        }

        return $resolved;
    }

    /**
     * @param  list<string|null>  $row
     * @param  array<string, int|null>  $columnMap
     * @return array<string, mixed>
     */
    protected function mapRow(array $row, array $columnMap, string $type): array
    {
        $value = static function (string $field) use ($row, $columnMap): ?string {
            $index = $columnMap[$field] ?? null;

            if ($index === null || ! isset($row[$index])) {
                return null;
            }

            $cell = trim((string) $row[$index]);

            return $cell !== '' ? $cell : null;
        };

        $icao = $value('icao');
        $description = $icao ? "ICAO: {$icao}" : null;

        return [
            'type' => $type,
            'name' => $value('name'),
            'code' => $value('code'),
            'city' => $value('city'),
            'country' => $value('country'),
            'region' => $value('region'),
            'latitude' => $value('latitude'),
            'longitude' => $value('longitude'),
            'description' => $description,
            'is_active' => true,
            'sort_order' => 0,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function upsertRow(array $payload): string
    {
        $existing = null;

        if (! empty($payload['code'])) {
            $existing = TravelDestination::withTrashed()
                ->where('type', $payload['type'])
                ->where('code', $payload['code'])
                ->first();
        }

        if (! $existing) {
            $existing = TravelDestination::withTrashed()
                ->where('type', $payload['type'])
                ->where('name', $payload['name'])
                ->first();
        }

        $data = $this->destinationService->prepareForStorage($payload, (bool) ($payload['is_active'] ?? true));

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
            }

            $existing->update($data);

            return 'updated';
        }

        TravelDestination::query()->create($data);

        return 'created';
    }

    /**
     * @param  list<string|null>  $row
     */
    protected function isEmptyRow(array $row): bool
    {
        return collect($row)->filter(fn ($cell) => trim((string) $cell) !== '')->isEmpty();
    }
}
