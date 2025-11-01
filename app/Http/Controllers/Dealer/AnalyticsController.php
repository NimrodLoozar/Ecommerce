<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Inquiry;
use App\Models\Order;
use App\Models\TestDrive;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard for the dealer.
     */
    public function index(): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403, 'You must have a dealer profile to access this page.');
        }

        // Sales by month (last 12 months)
        $salesByMonth = Order::where('status', 'completed')
            ->whereHas('orderItems.car', function ($query) use ($dealer) {
                $query->where('dealer_id', $dealer->id);
            })
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top selling cars
        $topCars = Car::where('dealer_id', $dealer->id)
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->with(['brand', 'carModel'])
            ->take(10)
            ->get();

        // Most viewed cars
        $mostViewedCars = Car::where('dealer_id', $dealer->id)
            ->orderBy('views', 'desc')
            ->with(['brand', 'carModel'])
            ->take(10)
            ->get();

        // Inquiry conversion rate
        $totalInquiries = Inquiry::whereHas('car', function ($query) use ($dealer) {
            $query->where('dealer_id', $dealer->id);
        })->count();

        $convertedInquiries = Inquiry::whereHas('car', function ($query) use ($dealer) {
            $query->where('dealer_id', $dealer->id);
        })
            ->where('status', 'converted')
            ->count();

        $conversionRate = $totalInquiries > 0 ? ($convertedInquiries / $totalInquiries) * 100 : 0;

        // Test drive statistics
        $testDriveStats = TestDrive::whereHas('car', function ($query) use ($dealer) {
            $query->where('dealer_id', $dealer->id);
        })
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Revenue by category
        $revenueByCategory = Order::where('status', 'completed')
            ->whereHas('orderItems.car', function ($query) use ($dealer) {
                $query->where('dealer_id', $dealer->id);
            })
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('cars', 'order_items.car_id', '=', 'cars.id')
            ->join('categories', 'cars.category_id', '=', 'categories.id')
            ->where('cars.dealer_id', $dealer->id)
            ->select('categories.name', DB::raw('SUM(order_items.price * order_items.quantity) as revenue'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();

        return view('dealer.analytics.index', compact(
            'salesByMonth',
            'topCars',
            'mostViewedCars',
            'totalInquiries',
            'convertedInquiries',
            'conversionRate',
            'testDriveStats',
            'revenueByCategory'
        ));
    }
}
