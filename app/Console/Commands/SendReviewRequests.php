<?php

namespace App\Console\Commands;

use App\Jobs\SendReviewRequestEmail;
use App\Models\Order;
use Illuminate\Console\Command;

class SendReviewRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:send-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send review request emails for completed orders (7 days after delivery)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sevenDaysAgo = now()->subDays(7)->toDateString();

        $orders = Order::where('status', 'completed')
            ->whereDate('updated_at', $sevenDaysAgo)
            ->with(['user', 'items.car'])
            ->get();

        $count = 0;

        foreach ($orders as $order) {
            // Check if user hasn't already reviewed all cars in the order
            $needsReview = false;

            foreach ($order->items as $item) {
                if (!$item->car->reviews()->where('user_id', $order->user_id)->exists()) {
                    $needsReview = true;
                    break;
                }
            }

            if ($needsReview) {
                SendReviewRequestEmail::dispatch($order);
                $count++;
            }
        }

        $this->info("Sent {$count} review request(s).");

        return Command::SUCCESS;
    }
}
