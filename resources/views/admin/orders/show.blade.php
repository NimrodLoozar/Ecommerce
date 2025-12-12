<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order #{{ $order->order_number }}
            </h2>
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-medium text-gray-900">Order Details</h3>
                                @if ($order->status === 'pending')
                                    <span
                                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif ($order->status === 'confirmed')
                                    <span
                                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Confirmed
                                    </span>
                                @elseif ($order->status === 'processing')
                                    <span
                                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Processing
                                    </span>
                                @elseif ($order->status === 'shipped')
                                    <span
                                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        Shipped
                                    </span>
                                @elseif ($order->status === 'delivered')
                                    <span
                                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-teal-100 text-teal-800">
                                        Delivered
                                    </span>
                                @elseif ($order->status === 'completed')
                                    <span
                                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                @elseif ($order->status === 'cancelled')
                                    <span
                                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Cancelled
                                    </span>
                                @endif
                            </div>

                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Order Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">#{{ $order->order_number }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('F d, Y H:i') }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($order->payment_method) }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                                    <dd class="mt-1">
                                        @if ($order->payment_status === 'paid')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Paid
                                            </span>
                                        @elseif ($order->payment_status === 'pending')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Failed
                                            </span>
                                        @endif
                                    </dd>
                                </div>

                                @if ($order->notes)
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Order Notes</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $order->notes }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                            <div class="space-y-4">
                                @foreach ($order->orderItems as $item)
                                    <div class="flex items-center py-4 border-b border-gray-100 last:border-0">
                                        <div class="flex-shrink-0 h-20 w-20">
                                            @php
                                                $coverImage = $item->car->getCoverImage();
                                            @endphp
                                            @if ($coverImage)
                                                <img src="{{ asset($coverImage) }}" alt="{{ $item->car->title }}"
                                                    class="h-20 w-20 rounded object-cover">
                                            @else
                                                <div
                                                    class="h-20 w-20 bg-gray-200 rounded flex items-center justify-center">
                                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('cars.show', $item->car) }}"
                                                    class="hover:text-blue-600">
                                                    {{ $item->car->title }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-500">
                                                {{ $item->car->brand->name ?? 'N/A' }} •
                                                {{ $item->car->carModel->name ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                Qty: {{ $item->quantity }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-gray-900">
                                                €{{ number_format($item->price, 2) }}
                                            </p>
                                            @if ($item->quantity > 1)
                                                <p class="text-xs text-gray-500">
                                                    €{{ number_format($item->price * $item->quantity, 2) }} total
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Summary -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <dl class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <dt class="text-gray-600">Subtotal</dt>
                                        <dd class="font-medium text-gray-900">
                                            €{{ number_format($order->orderItems->sum(fn($item) => $item->price * $item->quantity), 2) }}
                                        </dd>
                                    </div>
                                    @if ($order->tax > 0)
                                        <div class="flex justify-between text-sm">
                                            <dt class="text-gray-600">Tax</dt>
                                            <dd class="font-medium text-gray-900">€{{ number_format($order->tax, 2) }}</dd>
                                        </div>
                                    @endif
                                    @if ($order->shipping_cost > 0)
                                        <div class="flex justify-between text-sm">
                                            <dt class="text-gray-600">Shipping</dt>
                                            <dd class="font-medium text-gray-900">€{{ number_format($order->shipping_cost, 2) }}
                                            </dd>
                                        </div>
                                    @endif
                                    <div class="flex justify-between text-base font-semibold pt-2 border-t border-gray-200">
                                        <dt>Total</dt>
                                        <dd class="text-blue-600">€{{ number_format($order->total, 2) }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Update Status -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Order Status</h3>
                            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="mb-6">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Order Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                                        required>
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>
                                            Confirmed</option>
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                                            Processing</option>
                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>
                                            Shipped</option>
                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>
                                            Delivered</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Update Status
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Customer</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                                    @if ($order->user->phone)
                                        <p class="text-sm text-gray-500">{{ $order->user->phone }}</p>
                                    @endif
                                </div>
                                <div class="pt-3 border-t border-gray-200">
                                    <a href="{{ route('admin.users.show', $order->user) }}"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        View Customer Profile →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    @if ($order->shippingAddress)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Address</h3>
                                <address class="text-sm text-gray-600 not-italic">
                                    {{ $order->shippingAddress->address_line_1 }}<br>
                                    @if ($order->shippingAddress->address_line_2)
                                        {{ $order->shippingAddress->address_line_2 }}<br>
                                    @endif
                                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}
                                    {{ $order->shippingAddress->postal_code }}<br>
                                    {{ $order->shippingAddress->country->name ?? $order->shippingAddress->country }}
                                </address>
                            </div>
                        </div>
                    @endif

                    <!-- Billing Address -->
                    @if ($order->billingAddress && $order->billing_address_id !== $order->shipping_address_id)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Billing Address</h3>
                                <address class="text-sm text-gray-600 not-italic">
                                    {{ $order->billingAddress->address_line_1 }}<br>
                                    @if ($order->billingAddress->address_line_2)
                                        {{ $order->billingAddress->address_line_2 }}<br>
                                    @endif
                                    {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }}
                                    {{ $order->billingAddress->postal_code }}<br>
                                    {{ $order->billingAddress->country->name ?? $order->billingAddress->country }}
                                </address>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Information -->
                    @if ($order->payments->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment History</h3>
                                <div class="space-y-3">
                                    @foreach ($order->payments as $payment)
                                        <div class="text-sm">
                                            <div class="flex justify-between mb-1">
                                                <span class="text-gray-600">{{ ucfirst($payment->payment_method) }}</span>
                                                <span class="font-medium text-gray-900">
                                                    €{{ number_format($payment->amount, 2) }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">
                                                    {{ $payment->created_at->format('M d, Y H:i') }}
                                                </span>
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
