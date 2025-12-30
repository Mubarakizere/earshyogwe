<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\User;
use App\Notifications\ContractExpiring;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckContractExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for contracts expiring within 30 days and notify HR/Boss';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expiring contracts...');

        $expiringContracts = Contract::where('status', 'active')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now(), now()->addDays(30)])
            ->with('worker')
            ->get();

        if ($expiringContracts->isEmpty()) {
            $this->info('No expiring contracts found.');
            return;
        }

        $bosses = User::role('boss')->get();
        // Ideally also notify users with 'manage contracts' permission, but Boss is safe for now

        $count = 0;
        foreach ($expiringContracts as $contract) {
            $daysLeft = now()->diffInDays($contract->end_date, false);
            
            // Avoid duplicate notifications logic could be added here (check if notified recently)
            // For MVP, we'll notify. In production, maybe check a 'last_notified_at' column or cache.
            
            Notification::send($bosses, new ContractExpiring($contract, (int)$daysLeft));
            $count++;
        }

        $this->info("Notified bosses about {$count} expiring contracts.");
    }
}
