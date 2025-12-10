<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Order;
use App\Models\TestDrive;
use App\Models\Wishlist;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard with statistics.
     */
    public function index(): View
    {
        $user = auth()->user();

        // Get order statistics
        $totalOrders = Order::where('user_id', $user->id)->count();
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['orderItems.car.brand', 'orderItems.car.carModel', 'orderItems.car.images'])
            ->latest()
            ->take(5)
            ->get();

        // Get wishlist statistics
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();
        $wishlistItems = Wishlist::where('user_id', $user->id)
            ->with(['car.brand', 'car.carModel', 'car.images'])
            ->latest()
            ->take(3)
            ->get();

        // Get test drive count
        $testDriveCount = TestDrive::where('user_id', $user->id)->count();

        // Get inquiry count
        $inquiryCount = Inquiry::where('user_id', $user->id)->count();

        return view('dashboard', compact(
            'totalOrders',
            'recentOrders',
            'wishlistCount',
            'wishlistItems',
            'testDriveCount',
            'inquiryCount'
        ));
    }
}
