<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CleanExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete carts older than 30 days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Cleaning expired carts...');

        // Delete carts that are older than 30 days
        $expiredDate = Carbon::now()->subDays(30);
        
        $deletedCount = Cart::where('updated_at', '<', $expiredDate)
            ->where('status', 'active')
            ->delete();

        if ($deletedCount === 0) {
            $this->info('No expired carts found.');
            return self::SUCCESS;
        }

        $this->info("Successfully deleted {$deletedCount} expired cart(s).");

        return self::SUCCESS;
    }
}
