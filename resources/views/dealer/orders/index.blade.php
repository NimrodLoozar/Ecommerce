<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Page header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Order Management</h1>
                    <p class="mt-2 text-sm text-gray-600">Manage customer orders for your vehicles.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('dealer.dashboard') }}"
                        class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <svg class="mr-2 size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        Back to Dashboard
                    </a>
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

            <!-- Orders list -->
            <div class="mt-8 flow-root">
                @if ($orders->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto size-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No orders yet</h3>
                        <p class="mt-1 text-sm text-gray-500">You haven't received any orders for your vehicles.</p>
                    </div>
                @else
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Order Number
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Customer
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Items
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Total
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Status
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Date
                                            </th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($orders as $order)
                                            <tr class="hover:bg-gray-50">
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                    <a href="{{ route('dealer.orders.show', $order) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                        #{{ $order->order_number }}
                                                    </a>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    <div class="font-medium text-gray-900">{{ $order->user->name }}
                                                    </div>
                                                    <div class="text-gray-500">{{ $order->user->email }}</div>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ $order->orderItems->count() }}
                                                    {{ Str::plural('item', $order->orderItems->count()) }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                                    €{{ number_format($order->total, 2) }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                    @php
                                                        $statusColors = [
                                                            'pending' =>
                                                                'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                                            'confirmed' => 'bg-blue-50 text-blue-800 ring-blue-600/20',
                                                            'processing' =>
                                                                'bg-indigo-50 text-indigo-800 ring-indigo-600/20',
                                                            'shipped' =>
                                                                'bg-purple-50 text-purple-800 ring-purple-600/20',
                                                            'delivered' =>
                                                                'bg-green-50 text-green-800 ring-green-600/20',
                                                            'completed' => 'bg-gray-50 text-gray-800 ring-gray-600/20',
                                                            'cancelled' => 'bg-red-50 text-red-800 ring-red-600/20',
                                                        ];
                                                        $colorClass =
                                                            $statusColors[$order->status] ??
                                                            'bg-gray-50 text-gray-800 ring-gray-600/20';
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $colorClass }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ $order->created_at->format('M d, Y') }}
                                                </td>
                                                <td
                                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                    <a href="{{ route('dealer.orders.show', $order) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                        View<span class="sr-only">, order
                                                            #{{ $order->order_number }}</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if ($orders->hasPages())
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
