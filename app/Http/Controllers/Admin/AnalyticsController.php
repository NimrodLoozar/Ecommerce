<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        // Revenue analytics
        $revenueByMonth = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top selling cars across all dealers
        $topSellingCars = Car::withCount('orderItems')
            ->with(['brand', 'carModel'])
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();

        // Top dealers by revenue
        $topDealers = Order::where('status', 'completed')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('cars', 'order_items.car_id', '=', 'cars.id')
            ->join('dealer_profiles', 'cars.dealer_id', '=', 'dealer_profiles.id')
            ->select('dealer_profiles.business_name', DB::raw('SUM(order_items.price * order_items.quantity) as revenue'))
            ->groupBy('dealer_profiles.id', 'dealer_profiles.business_name')
            ->orderBy('revenue', 'desc')
            ->take(10)
            ->get();

        // User growth by month
        $userGrowth = User::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as user_count')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Cars by category
        $carsByCategory = Car::join('categories', 'cars.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('COUNT(*) as count'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('count', 'desc')
            ->get();

        // Cars by brand
        $carsByBrand = Car::join('brands', 'cars.brand_id', '=', 'brands.id')
            ->select('brands.name', DB::raw('COUNT(*) as count'))
            ->groupBy('brands.id', 'brands.name')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        return view('admin.analytics.index', compact(
            'revenueByMonth',
            'topSellingCars',
            'topDealers',
            'userGrowth',
            'carsByCategory',
            'carsByBrand'
        ));
    }
}
