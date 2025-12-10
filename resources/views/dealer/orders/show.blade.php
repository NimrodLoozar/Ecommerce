<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Back button -->
            <div class="mb-8">
                <a href="{{ route('dealer.orders.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Orders
                </a>
            </div>

            <!-- Page header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Order #{{ $order->order_number }}</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Placed on {{ $order->created_at->format('F d, Y \a\t g:i A') }}
                    </p>
                </div>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                        'confirmed' => 'bg-blue-50 text-blue-800 ring-blue-600/20',
                        'processing' => 'bg-indigo-50 text-indigo-800 ring-indigo-600/20',
                        'shipped' => 'bg-purple-50 text-purple-800 ring-purple-600/20',
                        'delivered' => 'bg-green-50 text-green-800 ring-green-600/20',
                        'completed' => 'bg-gray-50 text-gray-800 ring-gray-600/20',
                        'cancelled' => 'bg-red-50 text-red-800 ring-red-600/20',
                    ];
                    $colorClass = $statusColors[$order->status] ?? 'bg-gray-50 text-gray-800 ring-gray-600/20';
                @endphp
                <div class="mt-4 sm:mt-0">
                    <span
                        class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium ring-1 ring-inset {{ $colorClass }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            @if (session('success'))
                <div class="mt-6 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="size-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Main content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Order Items -->
                    <div class="rounded-lg border border-gray-200">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Order Items</h2>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach ($order->orderItems as $item)
                                <div class="px-6 py-4">
                                    <div class="flex gap-4">
                                        @if ($item->car->images->first())
                                            <img src="{{ Storage::url($item->car->images->first()->image_path) }}"
                                                alt="{{ $item->car->brand->name }} {{ $item->car->carModel->name }}"
                                                class="size-20 flex-shrink-0 rounded-lg object-cover">
                                        @else
                                            <div
                                                class="size-20 flex-shrink-0 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <svg class="size-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h3 class="text-sm font-medium text-gray-900">
                                                {{ $item->car->year }} {{ $item->car->brand->name }}
                                                {{ $item->car->carModel->name }}
                                            </h3>
                                            <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500">
                                                <span>{{ $item->car->exterior_color }}</span>
                                                <span>•</span>
                                                <span>{{ number_format($item->car->mileage) }} km</span>
                                                <span>•</span>
                                                <span>{{ ucfirst($item->car->fuel_type) }}</span>
                                            </div>
                                            <div class="mt-2 flex items-center justify-between">
                                                <p class="text-sm text-gray-500">
                                                    Quantity: <span
                                                        class="font-medium text-gray-900">{{ $item->quantity }}</span>
                                                </p>
                                                <p class="text-sm font-medium text-gray-900">
                                                    €{{ number_format($item->price, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="rounded-lg border border-gray-200">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Customer Information</h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->user->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->user->phone ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    @if ($order->shippingAddress)
                        <div class="rounded-lg border border-gray-200">
                            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                                <h2 class="text-base font-semibold text-gray-900">Shipping Address</h2>
                            </div>
                            <div class="px-6 py-4">
                                <address class="text-sm not-italic text-gray-900">
                                    <div>{{ $order->shippingAddress->street_address }}</div>
                                    @if ($order->shippingAddress->apartment)
                                        <div>{{ $order->shippingAddress->apartment }}</div>
                                    @endif
                                    <div>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}
                                        {{ $order->shippingAddress->postal_code }}</div>
                                    <div>{{ $order->shippingAddress->country }}</div>
                                </address>
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if ($order->notes)
                        <div class="rounded-lg border border-gray-200">
                            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                                <h2 class="text-base font-semibold text-gray-900">Customer Notes</h2>
                            </div>
                            <div class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Order Summary -->
                    <div class="rounded-lg border border-gray-200">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Order Summary</h2>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span
                                    class="font-medium text-gray-900">€{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-medium text-gray-900">€{{ number_format($order->tax, 2) }}</span>
                            </div>
                            @if ($order->delivery_fee > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Delivery Fee</span>
                                    <span
                                        class="font-medium text-gray-900">€{{ number_format($order->delivery_fee, 2) }}</span>
                                </div>
                            @endif
                            <div class="border-t border-gray-200 pt-3 flex justify-between">
                                <span class="text-base font-semibold text-gray-900">Total</span>
                                <span
                                    class="text-base font-semibold text-gray-900">€{{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="rounded-lg border border-gray-200">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Payment</h2>
                        </div>
                        <div class="px-6 py-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Method</span>
                                <span
                                    class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Status</span>
                                @php
                                    $paymentStatusColors = [
                                        'pending' => 'text-yellow-700',
                                        'paid' => 'text-green-700',
                                        'failed' => 'text-red-700',
                                        'refunded' => 'text-gray-700',
                                    ];
                                    $paymentColor = $paymentStatusColors[$order->payment_status] ?? 'text-gray-700';
                                @endphp
                                <span
                                    class="font-medium {{ $paymentColor }}">{{ ucfirst($order->payment_status) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Update Status -->
                    @if (!in_array($order->status, ['completed', 'cancelled']))
                        <div class="rounded-lg border border-gray-200">
                            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                                <h2 class="text-base font-semibold text-gray-900">Update Status</h2>
                            </div>
                            <div class="px-6 py-4">
                                <form action="{{ route('dealer.orders.update', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700">Order
                                            Status</label>
                                        <select name="status" id="status"
                                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                            <option value="pending"
                                                {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="confirmed"
                                                {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed
                                            </option>
                                            <option value="processing"
                                                {{ $order->status == 'processing' ? 'selected' : '' }}>Processing
                                            </option>
                                            <option value="shipped"
                                                {{ $order->status == 'shipped' ? 'selected' : '' }}>
                                                Shipped</option>
                                            <option value="delivered"
                                                {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered
                                            </option>
                                            <option value="completed"
                                                {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                                            </option>
                                            <option value="cancelled"
                                                {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                            </option>
                                        </select>
                                        @error('status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit"
                                        class="mt-4 w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        Update Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
