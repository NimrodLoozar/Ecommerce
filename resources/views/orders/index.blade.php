<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($orders->count() > 0)
                <div class="space-y-6">
                    @foreach ($orders as $order)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <!-- Order Header -->
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Order #{{ $order->order_number }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Placed on {{ $order->created_at->format('F j, Y') }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <!-- Status Badge -->
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if ($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>

                                        <!-- View Details Button -->
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach ($order->items as $item)
                                        <div class="flex gap-4">
                                            <!-- Car Image -->
                                            <div class="flex-shrink-0">
                                                @if ($item->car->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $item->car->images->first()->image_path) }}"
                                                        alt="{{ $item->car->brand->name }} {{ $item->car->carModel->name }}"
                                                        class="w-24 h-24 object-cover rounded-lg">
                                                @else
                                                    <div
                                                        class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Car Details -->
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-base font-semibold text-gray-900">
                                                    {{ $item->car->brand->name }} {{ $item->car->carModel->name }}
                                                </h4>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ $item->car->year }} • {{ $item->car->mileage }} km •
                                                    {{ ucfirst($item->car->condition) }}
                                                </p>
                                                @if ($item->quantity > 1)
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        Quantity: {{ $item->quantity }}
                                                    </p>
                                                @endif
                                            </div>

                                            <!-- Price -->
                                            <div class="text-right">
                                                <p class="text-base font-semibold text-gray-900">
                                                    €{{ number_format($item->subtotal, 2) }}
                                                </p>
                                                @if ($item->quantity > 1)
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        €{{ number_format($item->price, 2) }} each
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Order Total -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm text-gray-600">
                                            <p>{{ $order->items->count() }}
                                                {{ Str::plural('item', $order->items->count()) }}</p>
                                            @if ($order->shippingAddress)
                                                <p class="mt-1">
                                                    Ship to: {{ $order->shippingAddress->city }},
                                                    {{ $order->shippingAddress->state }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">Total Amount</p>
                                            <p class="text-xl font-bold text-gray-900">
                                                €{{ number_format($order->total, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">No orders yet</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            You haven't placed any orders. Start shopping to see your orders here.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('cars.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Browse Cars
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
