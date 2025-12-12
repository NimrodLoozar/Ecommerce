<?php

namespace App\Console\Commands;

use App\Models\Commission;
use App\Models\DealerProfile;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateMonthlyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:generate-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly commission reports for dealers';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Generating monthly commission reports...');

        $lastMonth = Carbon::now()->subMonth();
        $startDate = $lastMonth->copy()->startOfMonth();
        $endDate = $lastMonth->copy()->endOfMonth();

        $this->info("Generating reports for: {$startDate->format('F Y')}");

        // Get all active dealers
        $dealers = DealerProfile::where('status', 'approved')->with('user')->get();

        if ($dealers->isEmpty()) {
            $this->info('No active dealers found.');
            return self::SUCCESS;
        }

        $reportCount = 0;

        foreach ($dealers as $dealer) {
            // Get orders for this dealer in the date range
            $orders = Order::whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('items.car', function ($query) use ($dealer) {
                    $query->where('dealer_id', $dealer->id);
                })
                ->where('payment_status', 'paid')
                ->with(['items.car'])
                ->get();

            if ($orders->isEmpty()) {
                continue;
            }

            // Calculate total sales and commissions
            $totalSales = 0;
            $totalCommission = 0;

            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    if ($item->car->dealer_id === $dealer->id) {
                        $itemTotal = $item->price * $item->quantity;
                        $totalSales += $itemTotal;
                        
                        // Calculate commission based on dealer's commission rate
                        $commissionAmount = $itemTotal * ($dealer->commission_rate / 100);
                        $totalCommission += $commissionAmount;

                        // Check if commission record exists
                        $existingCommission = Commission::where('dealer_id', $dealer->id)
                            ->where('order_id', $order->id)
                            ->first();

                        if (!$existingCommission) {
                            // Create commission record
                            Commission::create([
                                'dealer_id' => $dealer->id,
                                'order_id' => $order->id,
                                'amount' => $commissionAmount,
                                'commission_rate' => $dealer->commission_rate,
                                'status' => 'pending',
                            ]);
                        }
                    }
                }
            }

            // Update dealer analytics
            if ($dealer->analytics) {
                $dealer->analytics->increment('total_sales', $totalSales);
                $dealer->analytics->increment('total_commission', $totalCommission);
            }

            $this->line("Generated report for dealer: {$dealer->company_name}");
            $this->line("  - Orders: {$orders->count()}");
            $this->line("  - Total Sales: €" . number_format($totalSales, 2));
            $this->line("  - Total Commission: €" . number_format($totalCommission, 2));

            $reportCount++;

            // Log the report generation
            Log::info('Monthly commission report generated', [
                'dealer_id' => $dealer->id,
                'dealer_name' => $dealer->company_name,
                'period' => $startDate->format('F Y'),
                'orders_count' => $orders->count(),
                'total_sales' => $totalSales,
                'total_commission' => $totalCommission,
            ]);
        }

        $this->info("Successfully generated {$reportCount} monthly report(s).");

        return self::SUCCESS;
    }
}
