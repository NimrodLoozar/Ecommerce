<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-1">Welcome back, {{ auth()->user()->name }}!</h3>
                    <p class="text-gray-600">Here's an overview of your account activity.</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Orders</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('orders.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View orders →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Wishlist Items -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-red-100 rounded-lg">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Wishlist</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $wishlistCount }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('wishlist.index') }}"
                                class="text-sm text-red-600 hover:text-red-800 font-medium">
                                View wishlist →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Test Drives -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-indigo-100 rounded-lg">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Test Drives</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $testDriveCount }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('test-drives.index') }}"
                                class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                View bookings →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Inquiries -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Inquiries</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $inquiryCount }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('inquiries.index') }}"
                                class="text-sm text-green-600 hover:text-green-800 font-medium">
                                View inquiries →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                            <a href="{{ route('orders.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View all →
                            </a>
                        </div>

                        @if ($recentOrders->count() > 0)
                            <div class="space-y-4">
                                @foreach ($recentOrders as $order)
                                    <div
                                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <h4 class="text-sm font-semibold text-gray-900">
                                                    Order #{{ $order->id }}
                                                </h4>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                                        'processing' => 'bg-indigo-100 text-indigo-800',
                                                        'shipped' => 'bg-purple-100 text-purple-800',
                                                        'delivered' => 'bg-teal-100 text-teal-800',
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'cancelled' => 'bg-red-100 text-red-800',
                                                    ];
                                                    $colorClass =
                                                        $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                {{ $order->orderItems->count() }}
                                                {{ Str::plural('item', $order->orderItems->count()) }} •
                                                €{{ number_format($order->total, 2) }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $order->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="ml-4 text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                            View →
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">No orders yet</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Wishlist Preview -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Wishlist</h3>
                            <a href="{{ route('wishlist.index') }}"
                                class="text-sm text-red-600 hover:text-red-800 font-medium">
                                View all →
                            </a>
                        </div>

                        @if ($wishlistItems->count() > 0)
                            <div class="space-y-4">
                                @foreach ($wishlistItems as $item)
                                    <div
                                        class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                        @if ($item->car->images->first())
                                            <img src="{{ Storage::url($item->car->images->first()->image_path) }}"
                                                alt="{{ $item->car->brand->name }}"
                                                class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex-shrink-0"></div>
                                        @endif
                                        <div class="ml-4 flex-1">
                                            <h4 class="text-sm font-semibold text-gray-900">
                                                {{ $item->car->year }} {{ $item->car->brand->name }}
                                                {{ $item->car->carModel->name }}
                                            </h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                €{{ number_format($item->car->price, 2) }}
                                            </p>
                                        </div>
                                        <a href="{{ route('cars.show', $item->car) }}"
                                            class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                            View →
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">No saved cars</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('cars.index') }}"
                            class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Browse Cars</p>
                                <p class="text-xs text-gray-600">Find your dream car</p>
                            </div>
                        </a>

                        <a href="{{ route('orders.index') }}"
                            class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">My Orders</p>
                                <p class="text-xs text-gray-600">Track your purchases</p>
                            </div>
                        </a>

                        <a href="{{ route('wishlist.index') }}"
                            class="flex items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Wishlist</p>
                                <p class="text-xs text-gray-600">Your saved cars</p>
                            </div>
                        </a>

                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Profile Settings</p>
                                <p class="text-xs text-gray-600">Update your info</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
