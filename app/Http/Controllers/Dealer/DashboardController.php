<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Inquiry;
use App\Models\Order;
use App\Models\TestDrive;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dealer dashboard with statistics.
     */
    public function index(): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403, 'You must have a dealer profile to access this page.');
        }

        // Get dealer's cars statistics
        $totalCars = Car::where('dealer_id', $dealer->id)->count();
        $availableCars = Car::where('dealer_id', $dealer->id)
            ->where('status', 'available')
            ->count();
        $soldCars = Car::where('dealer_id', $dealer->id)
            ->where('status', 'sold')
            ->count();

        // Get recent orders for dealer's cars
        $recentOrders = Order::whereHas('orderItems.car', function ($query) use ($dealer) {
            $query->where('dealer_id', $dealer->id);
        })
            ->with(['user', 'orderItems.car'])
            ->latest()
            ->take(5)
            ->get();

        // Calculate total revenue from completed orders
        $totalRevenue = Order::where('status', 'completed')
            ->whereHas('orderItems.car', function ($query) use ($dealer) {
                $query->where('dealer_id', $dealer->id);
            })
            ->sum('total');

        // Get pending inquiries
        $pendingInquiries = Inquiry::whereHas('car', function ($query) use ($dealer) {
            $query->where('dealer_id', $dealer->id);
        })
            ->where('status', 'new')
            ->count();

        // Get upcoming test drives
        $upcomingTestDrives = TestDrive::whereHas('car', function ($query) use ($dealer) {
            $query->where('dealer_id', $dealer->id);
        })
            ->where('status', 'confirmed')
            ->where('preferred_date', '>=', now())
            ->orderBy('preferred_date')
            ->take(5)
            ->with(['user', 'car'])
            ->get();

        // Get total views on dealer's cars
        $totalViews = Car::where('dealer_id', $dealer->id)->sum('views_count');

        return view('dealer.dashboard', compact(
            'dealer',
            'totalCars',
            'availableCars',
            'soldCars',
            'recentOrders',
            'totalRevenue',
            'pendingInquiries',
            'upcomingTestDrives',
            'totalViews'
        ));
    }
}
