<?php

namespace App\Console\Commands;

use App\Jobs\SendAbandonedCartEmail;
use App\Models\Cart;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendAbandonedCartEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:send-abandoned-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to users who have abandoned their carts';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Finding abandoned carts...');

        // Find active carts that were updated between 1-7 days ago
        // and haven't received an abandoned cart email yet
        $abandonedCarts = Cart::where('status', 'active')
            ->whereHas('items') // Must have items
            ->whereBetween('updated_at', [
                Carbon::now()->subDays(7),
                Carbon::now()->subDay()
            ])
            ->whereDoesntHave('user', function ($query) {
                // Optional: exclude users who recently placed an order
                $query->whereHas('orders', function ($q) {
                    $q->where('created_at', '>=', Carbon::now()->subDays(7));
                });
            })
            ->with(['user', 'items.car.brand', 'items.car.carModel', 'items.car.images'])
            ->get();

        if ($abandonedCarts->isEmpty()) {
            $this->info('No abandoned carts found.');
            return self::SUCCESS;
        }

        $this->info("Found {$abandonedCarts->count()} abandoned cart(s).");

        $count = 0;
        foreach ($abandonedCarts as $cart) {
            // Dispatch email job
            SendAbandonedCartEmail::dispatch($cart);
            $count++;

            $this->line("Queued abandoned cart email for user: {$cart->user->email}");
        }

        $this->info("Successfully queued {$count} abandoned cart reminder(s).");

        return self::SUCCESS;
    }
}
