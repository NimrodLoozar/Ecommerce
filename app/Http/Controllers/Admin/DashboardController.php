<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\DealerProfile;
use App\Models\Inquiry;
use App\Models\Order;
use App\Models\Review;
use App\Models\TestDrive;
use App\Models\TradeIn;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with system-wide statistics.
     */
    public function index(): View
    {
        // User statistics
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();
        $totalDealers = DealerProfile::where('is_approved', true)->count();
        $pendingDealers = DealerProfile::where('is_approved', false)->count();

        // Car statistics
        $totalCars = Car::count();
        $availableCars = Car::where('status', 'available')->count();
        $soldCars = Car::where('status', 'sold')->count();
        $featuredCars = Car::where('is_featured', true)->count();

        // Order statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $revenueThisMonth = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');

        // Review statistics
        $totalReviews = Review::count();
        $pendingReviews = Review::where('is_approved', false)->count();
        $averageRating = Review::where('is_approved', true)->avg('rating');

        // Inquiry statistics
        $totalInquiries = Inquiry::count();
        $newInquiries = Inquiry::where('status', 'new')->count();

        // Test drive statistics
        $totalTestDrives = TestDrive::count();
        $pendingTestDrives = TestDrive::where('status', 'pending')->count();

        // Trade-in statistics
        $totalTradeIns = TradeIn::count();
        $pendingTradeIns = TradeIn::where('status', 'pending')->count();

        // Recent activity
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();
        $recentReviews = Review::with(['user', 'car'])->latest()->take(5)->get();

        // Sales trend (last 7 days)
        $salesTrend = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersThisMonth',
            'totalDealers',
            'pendingDealers',
            'totalCars',
            'availableCars',
            'soldCars',
            'featuredCars',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue',
            'revenueThisMonth',
            'totalReviews',
            'pendingReviews',
            'averageRating',
            'totalInquiries',
            'newInquiries',
            'totalTestDrives',
            'pendingTestDrives',
            'totalTradeIns',
            'pendingTradeIns',
            'recentOrders',
            'recentUsers',
            'recentReviews',
            'salesTrend'
        ));
    }
}
