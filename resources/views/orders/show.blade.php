<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order #{{ $order->order_number }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content - Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Status Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Order Status</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                <span
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                    @if ($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>

                            <!-- Order Timeline (if processing/completed) -->
                            @if ($order->status === 'processing' || $order->status === 'completed')
                                <div class="mt-6">
                                    <div class="relative">
                                        <div class="absolute left-4 top-0 h-full w-0.5 bg-gray-200"></div>
                                        <ul class="space-y-6 relative">
                                            <li class="flex gap-4">
                                                <div
                                                    class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">Order Placed</p>
                                                    <p class="text-sm text-gray-600">
                                                        {{ $order->created_at->format('M j, Y g:i A') }}</p>
                                                </div>
                                            </li>
                                            <li class="flex gap-4">
                                                <div
                                                    class="flex-shrink-0 w-8 h-8 rounded-full {{ $order->status === 'completed' ? 'bg-green-500' : 'bg-blue-500' }} flex items-center justify-center">
                                                    @if ($order->status === 'completed')
                                                        <svg class="w-5 h-5 text-white" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    @else
                                                        <div class="w-3 h-3 rounded-full bg-white animate-pulse"></div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">Processing</p>
                                                    <p class="text-sm text-gray-600">
                                                        @if ($order->status === 'completed')
                                                            Completed
                                                        @else
                                                            In progress
                                                        @endif
                                                    </p>
                                                </div>
                                            </li>
                                            @if ($order->status === 'completed')
                                                <li class="flex gap-4">
                                                    <div
                                                        class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-white" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900">Delivered</p>
                                                        <p class="text-sm text-gray-600">
                                                            {{ $order->completed_at->format('M j, Y g:i A') }}</p>
                                                    </div>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                            <div class="space-y-6">
                                @foreach ($order->items as $item)
                                    <div class="flex gap-4 pb-6 border-b border-gray-200 last:border-0 last:pb-0">
                                        <!-- Car Image -->
                                        <div class="flex-shrink-0">
                                            @if ($item->car->images->count() > 0)
                                                <img src="{{ asset('storage/' . $item->car->images->first()->image_path) }}"
                                                    alt="{{ $item->car->brand->name }} {{ $item->car->carModel->name }}"
                                                    class="w-32 h-32 object-cover rounded-lg">
                                            @else
                                                <div
                                                    class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none"
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
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <div>
                                                    <h4 class="text-lg font-semibold text-gray-900">
                                                        <a href="{{ route('cars.show', $item->car) }}"
                                                            class="hover:text-blue-600">
                                                            {{ $item->car->brand->name }}
                                                            {{ $item->car->carModel->name }}
                                                        </a>
                                                    </h4>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        {{ $item->car->year }} •
                                                        {{ number_format($item->car->mileage) }} km
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        {{ ucfirst($item->car->condition) }} •
                                                        {{ ucfirst($item->car->transmission) }}
                                                    </p>
                                                    @if ($item->car->color)
                                                        <p class="text-sm text-gray-600">
                                                            Color: {{ ucfirst($item->car->color) }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-lg font-semibold text-gray-900">
                                                        €{{ number_format($item->subtotal, 2) }}
                                                    </p>
                                                    @if ($item->quantity > 1)
                                                        <p class="text-sm text-gray-600">
                                                            €{{ number_format($item->price, 2) }} ×
                                                            {{ $item->quantity }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    @if ($order->shippingAddress)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h3>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p class="font-medium text-gray-900">
                                        {{ $order->shippingAddress->first_name }}
                                        {{ $order->shippingAddress->last_name }}
                                    </p>
                                    @if ($order->shippingAddress->company)
                                        <p>{{ $order->shippingAddress->company }}</p>
                                    @endif
                                    <p>{{ $order->shippingAddress->address_line1 }}</p>
                                    @if ($order->shippingAddress->address_line2)
                                        <p>{{ $order->shippingAddress->address_line2 }}</p>
                                    @endif
                                    <p>
                                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}
                                        {{ $order->shippingAddress->postal_code }}
                                    </p>
                                    <p>{{ $order->shippingAddress->country }}</p>
                                    @if ($order->shippingAddress->phone)
                                        <p class="mt-2">Phone: {{ $order->shippingAddress->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar - Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>

                            <!-- Price Breakdown -->
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span
                                        class="font-medium text-gray-900">€{{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                @if ($order->delivery_fee > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Delivery Fee</span>
                                        <span
                                            class="font-medium text-gray-900">€{{ number_format($order->delivery_fee, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax</span>
                                    <span class="font-medium text-gray-900">€{{ number_format($order->tax, 2) }}</span>
                                </div>
                                <div class="pt-3 border-t border-gray-200">
                                    <div class="flex justify-between">
                                        <span class="text-base font-semibold text-gray-900">Total</span>
                                        <span
                                            class="text-xl font-bold text-gray-900">€{{ number_format($order->total, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Information -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Payment Information</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Method</span>
                                        <span class="font-medium text-gray-900">
                                            @if ($order->payment_method === 'card')
                                                Credit Card
                                            @elseif($order->payment_method === 'bank_transfer')
                                                Bank Transfer
                                            @elseif($order->payment_method === 'cash')
                                                Cash on Delivery
                                            @else
                                                {{ ucfirst($order->payment_method) }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status</span>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if ($order->payment_status === 'paid') bg-green-100 text-green-800
                                            @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Billing Address (if different) -->
                            @if ($order->billingAddress && $order->billing_address_id !== $order->shipping_address_id)
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Billing Address</h4>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <p class="font-medium text-gray-900">
                                            {{ $order->billingAddress->first_name }}
                                            {{ $order->billingAddress->last_name }}
                                        </p>
                                        @if ($order->billingAddress->company)
                                            <p>{{ $order->billingAddress->company }}</p>
                                        @endif
                                        <p>{{ $order->billingAddress->address_line1 }}</p>
                                        @if ($order->billingAddress->address_line2)
                                            <p>{{ $order->billingAddress->address_line2 }}</p>
                                        @endif
                                        <p>
                                            {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }}
                                            {{ $order->billingAddress->postal_code }}
                                        </p>
                                        <p>{{ $order->billingAddress->country }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Order Notes -->
                            @if ($order->notes)
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Order Notes</h4>
                                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                                @if ($order->status === 'completed')
                                    <a href="{{ route('cars.index') }}"
                                        class="block w-full text-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Shop Again
                                    </a>
                                @endif
                                <a href="{{ route('orders.index') }}"
                                    class="block w-full text-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    View All Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
