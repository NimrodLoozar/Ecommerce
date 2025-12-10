<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Back button -->
            <div class="mb-8">
                <a href="{{ route('dealer.commissions.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Commissions
                </a>
            </div>

            <!-- Page header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Commission Details</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Order #{{ $commission->order->id }} • {{ $commission->created_at->format('F d, Y') }}
                    </p>
                </div>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                        'approved' => 'bg-blue-50 text-blue-800 ring-blue-600/20',
                        'paid' => 'bg-green-50 text-green-800 ring-green-600/20',
                    ];
                    $colorClass = $statusColors[$commission->status] ?? 'bg-gray-50 text-gray-800 ring-gray-600/20';
                @endphp
                <div class="mt-4 sm:mt-0">
                    <span
                        class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium ring-1 ring-inset {{ $colorClass }}">
                        {{ ucfirst($commission->status) }}
                    </span>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Main content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Commission Summary -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Commission Summary</h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sale Amount</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                                        €{{ number_format($commission->sale_amount, 2) }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Commission Rate</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                                        {{ number_format($commission->commission_rate, 2) }}%
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Commission Amount</dt>
                                    <dd class="mt-1 text-2xl font-bold text-indigo-600">
                                        €{{ number_format($commission->commission_amount, 2) }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                                    <dd class="mt-1">
                                        <span
                                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $colorClass }}">
                                            {{ ucfirst($commission->status) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>

                            @if ($commission->status === 'paid' && $commission->paid_at)
                                <div class="mt-6 rounded-lg bg-green-50 p-4">
                                    <div class="flex">
                                        <div class="shrink-0">
                                            <svg class="size-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-green-800">Paid</h3>
                                            <div class="mt-2 text-sm text-green-700">
                                                <p>Payment received on {{ $commission->paid_at->format('F d, Y') }}</p>
                                                @if ($commission->payment_method)
                                                    <p class="mt-1">Method:
                                                        {{ ucfirst($commission->payment_method) }}</p>
                                                @endif
                                                @if ($commission->payment_reference)
                                                    <p class="mt-1">Reference: {{ $commission->payment_reference }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Order Items</h2>
                        </div>
                        <div class="px-6 py-4">
                            <ul role="list" class="divide-y divide-gray-200">
                                @foreach ($commission->order->orderItems as $item)
                                    <li class="flex gap-x-4 py-4">
                                        @if ($item->car && $item->car->images->first())
                                            <img src="{{ Storage::url($item->car->images->first()->image_path) }}"
                                                alt="{{ $item->car->brand->name }}"
                                                class="size-16 shrink-0 rounded-lg object-cover">
                                        @else
                                            <div class="size-16 shrink-0 rounded-lg bg-gray-200"></div>
                                        @endif
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between gap-x-4">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">
                                                        @if ($item->car)
                                                            {{ $item->car->year }} {{ $item->car->brand->name }}
                                                            {{ $item->car->carModel->name }}
                                                        @else
                                                            Car no longer available
                                                        @endif
                                                    </p>
                                                    <p class="mt-1 text-sm text-gray-500">
                                                        Quantity: {{ $item->quantity }}
                                                    </p>
                                                </div>
                                                <p class="text-sm font-semibold text-gray-900">
                                                    €{{ number_format($item->price * $item->quantity, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-6 border-t border-gray-200 pt-4">
                                <div class="flex items-center justify-between text-base font-semibold text-gray-900">
                                    <p>Order Total</p>
                                    <p>€{{ number_format($commission->order->total, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Information -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Customer</h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $commission->order->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="mailto:{{ $commission->order->user->email }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            {{ $commission->order->user->email }}
                                        </a>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Order Information</h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Order Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">#{{ $commission->order->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $commission->order->created_at->format('F d, Y') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Order Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($commission->order->status) }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ ucfirst($commission->order->payment_method) }}</dd>
                                </div>
                            </dl>

                            <a href="{{ route('dealer.orders.show', $commission->order) }}"
                                class="mt-4 block w-full rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                View Full Order
                            </a>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Commission Timeline</h2>
                        </div>
                        <div class="px-6 py-4">
                            <ul class="space-y-4">
                                <li class="flex gap-3">
                                    <div class="shrink-0">
                                        <div
                                            class="flex size-8 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Commission Created</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $commission->created_at->format('M d, Y g:i A') }}
                                        </p>
                                    </div>
                                </li>

                                @if ($commission->status === 'approved' || $commission->status === 'paid')
                                    <li class="flex gap-3">
                                        <div class="shrink-0">
                                            <div
                                                class="flex size-8 items-center justify-center rounded-full bg-green-100 text-green-600">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">Commission Approved</p>
                                            <p class="text-xs text-gray-500">Approved by admin</p>
                                        </div>
                                    </li>
                                @endif

                                @if ($commission->status === 'paid' && $commission->paid_at)
                                    <li class="flex gap-3">
                                        <div class="shrink-0">
                                            <div
                                                class="flex size-8 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">Payment Received</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $commission->paid_at->format('M d, Y g:i A') }}
                                            </p>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
