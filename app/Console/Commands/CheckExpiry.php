<?php

namespace App\Console\Commands;

use App\Events\ExpiryApproaching;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Console\Command;

class CheckExpiry extends Command
{
    protected $signature = 'check:expiry';
    protected $description = 'Send expiry notifications for medicines expiring within 7 days';

    public function handle()
    {
        // ✅ पहिलो प्रयोगकर्ताको ID (जो admin मानिन्छ)
        $userId = User::first()->id ?? 1; // यदि कुनै user छैन भने 1

        $medicines = Medicine::where('expiry_date', '<=', now()->addDays(7))
                             ->where('expiry_date', '>=', now()->toDateString())
                             ->get();

        foreach ($medicines as $medicine) {
            event(new ExpiryApproaching($medicine, $userId));
        }

        $this->info('Expiry notifications sent for ' . $medicines->count() . ' medicines.');
    }
}