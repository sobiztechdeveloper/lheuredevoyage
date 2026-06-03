<?php

namespace App\Console\Commands;

use App\Models\Quote;
use App\Services\QuoteNotificationService;
use App\Services\QuoteService;
use Illuminate\Console\Command;

class ExpireQuotesCommand extends Command
{
    protected $signature = 'quotes:expire';

    protected $description = 'Mark overdue quotes as expired and notify customers';

    public function handle(QuoteService $quoteService, QuoteNotificationService $notifications): int
    {
        $candidates = Quote::query()
            ->whereDate('valid_until', '<', now()->toDateString())
            ->whereNotIn('status', ['accepted', 'rejected', 'expired'])
            ->get();

        $count = 0;
        foreach ($candidates as $quote) {
            $previous = $quote->status;
            $quote = $quoteService->expireIfNeeded($quote);
            if ($quote->status === 'expired' && $previous !== 'expired') {
                $notifications->notifyExpired($quote);
                $count++;
            }
        }

        $this->info("Expired {$count} quote(s).");

        return self::SUCCESS;
    }
}
