<?php

namespace App\Console\Commands;

use App\Models\HolidayPackageRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class BackfillHolidayPackageRequestMetadataCommand extends Command
{
    protected $signature = 'holiday-requests:backfill-metadata {--dry-run : Preview changes without writing to the database}';

    protected $description = 'Extract holiday type, priority, contact method, and GDPR consent from additional_notes into dedicated columns';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $config = holiday_package_request_config();
        $updated = 0;
        $skipped = 0;

        HolidayPackageRequest::query()
            ->whereNotNull('additional_notes')
            ->where('additional_notes', '!=', '')
            ->orderBy('id')
            ->chunkById(100, function ($requests) use ($config, $dryRun, &$updated, &$skipped) {
                foreach ($requests as $request) {
                    $parsed = $this->parseMetadata($request->additional_notes, $config);

                    if (! $parsed['has_metadata']) {
                        $skipped++;

                        continue;
                    }

                    $updates = [];

                    if (empty($request->holiday_types) && ! empty($parsed['holiday_types'])) {
                        $updates['holiday_types'] = $parsed['holiday_types'];
                    }

                    if ($this->shouldBackfillPriority($request->priority) && $parsed['priority'] !== null) {
                        $updates['priority'] = $parsed['priority'];
                    }

                    if ($this->shouldBackfillContactMethod($request->preferred_contact_method) && $parsed['preferred_contact_method'] !== null) {
                        $updates['preferred_contact_method'] = $parsed['preferred_contact_method'];
                    }

                    if ($request->gdpr_consent_at === null && $parsed['gdpr_consent_at'] !== null) {
                        $updates['gdpr_consent_at'] = $parsed['gdpr_consent_at'];
                    }

                    if ($parsed['clean_notes'] !== $request->additional_notes) {
                        $updates['additional_notes'] = $parsed['clean_notes'] !== '' ? $parsed['clean_notes'] : null;
                    }

                    if ($updates === []) {
                        $skipped++;

                        continue;
                    }

                    if ($dryRun) {
                        $this->line("[dry-run] {$request->reference_number}: ".json_encode($updates));
                    } else {
                        $request->update($updates);
                    }

                    $updated++;
                }
            });

        $mode = $dryRun ? 'Would update' : 'Updated';
        $this->info("{$mode} {$updated} request(s). Skipped {$skipped} without extractable metadata.");

        return self::SUCCESS;
    }

    /**
     * @return array{
     *     has_metadata: bool,
     *     holiday_types: list<string>,
     *     priority: ?string,
     *     preferred_contact_method: ?string,
     *     gdpr_consent_at: ?Carbon,
     *     clean_notes: string
     * }
     */
    private function parseMetadata(string $notes, array $config): array
    {
        $holidayTypes = [];
        $priority = null;
        $contactMethod = null;
        $gdprConsentAt = null;
        $hasMetadata = false;
        $keptLines = [];

        foreach (preg_split('/\r\n|\r|\n/', $notes) as $line) {
            $trimmed = trim($line);

            if (preg_match('/^Holiday Type:\s*(.+)$/i', $trimmed, $matches)) {
                $holidayTypes = $this->resolveHolidayTypeSlugs($matches[1], $config);
                $hasMetadata = true;

                continue;
            }

            if (preg_match('/^Priority:\s*(.+)$/i', $trimmed, $matches)) {
                $priority = $this->resolvePrioritySlug($matches[1], $config['option_labels']['priorities'] ?? []);
                $hasMetadata = true;

                continue;
            }

            if (preg_match('/^Preferred Contact Method:\s*(.+)$/i', $trimmed, $matches)) {
                $contactMethod = $this->resolveContactMethodSlug($matches[1], $config['option_labels']['contact_methods'] ?? []);
                $hasMetadata = true;

                continue;
            }

            if (preg_match('/^GDPR Consent:\s*(.+)$/i', $trimmed, $matches)) {
                try {
                    $gdprConsentAt = Carbon::parse(trim($matches[1]));
                    $hasMetadata = true;
                } catch (\Throwable) {
                    // Ignore unparseable consent timestamps.
                }

                continue;
            }

            $keptLines[] = $line;
        }

        $cleanNotes = trim(preg_replace("/\n{3,}/", "\n\n", implode("\n", $keptLines)) ?? '');

        return [
            'has_metadata' => $hasMetadata,
            'holiday_types' => $holidayTypes,
            'priority' => $priority,
            'preferred_contact_method' => $contactMethod,
            'gdpr_consent_at' => $gdprConsentAt,
            'clean_notes' => $cleanNotes,
        ];
    }

    /**
     * @return list<string>
     */
    private function resolveHolidayTypeSlugs(string $value, array $config): array
    {
        $allowed = $config['holiday_types'] ?? [];
        $labels = $config['option_labels']['holiday_types'] ?? [];
        $labelToSlug = array_flip($labels);
        $slugs = [];

        foreach (array_map('trim', explode(',', $value)) as $part) {
            if ($part === '') {
                continue;
            }

            if (in_array($part, $allowed, true)) {
                $slugs[] = $part;

                continue;
            }

            if (isset($labelToSlug[$part])) {
                $slugs[] = $labelToSlug[$part];

                continue;
            }

            $matched = collect($labels)->first(fn ($label, $slug) => Str::lower($label) === Str::lower($part));

            if ($matched !== null) {
                $slugs[] = array_search($matched, $labels, true);
            }
        }

        return array_values(array_unique(array_filter($slugs)));
    }

    private function resolvePrioritySlug(string $value, array $labels): ?string
    {
        $value = trim($value);
        $slug = Str::slug($value);

        if (isset($labels[$slug])) {
            return $slug;
        }

        $matched = collect($labels)->first(fn ($label, $key) => Str::lower($label) === Str::lower($value));

        return $matched !== null ? (string) array_search($matched, $labels, true) : null;
    }

    private function resolveContactMethodSlug(string $value, array $labels): ?string
    {
        $slug = Str::lower(trim($value));

        if (isset($labels[$slug])) {
            return $slug;
        }

        $matched = collect($labels)->first(fn ($label, $key) => Str::lower($label) === $slug || Str::lower($key) === $slug);

        return $matched !== null ? (string) array_search($matched, $labels, true) : null;
    }

    private function shouldBackfillPriority(string $current): bool
    {
        return $current === 'normal';
    }

    private function shouldBackfillContactMethod(string $current): bool
    {
        return $current === 'email';
    }
}
