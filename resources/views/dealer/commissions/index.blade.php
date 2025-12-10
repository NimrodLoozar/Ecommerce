<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Page header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Commission Reports</h1>
                    <p class="mt-2 text-sm text-gray-600">Track your earnings and commission history.</p>
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

            <!-- Summary Cards -->
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Total Earned -->
                <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="shrink-0 rounded-md bg-green-500 p-3">
                                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Total Earned (Paid)</dt>
                                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                                        €{{ number_format($totalEarned, 2) }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Commissions -->
                <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="shrink-0 rounded-md bg-yellow-500 p-3">
                                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Pending Payment</dt>
                                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                                        €{{ number_format($totalPending, 2) }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commissions list -->
            <div class="mt-8 flow-root">
                @if ($commissions->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto size-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No commissions yet</h3>
                        <p class="mt-1 text-sm text-gray-500">You haven't earned any commissions from sales.</p>
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
                                                Commission
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Rate
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
                                        @foreach ($commissions as $commission)
                                            <tr class="hover:bg-gray-50">
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                    <a href="{{ route('dealer.orders.show', $commission->order) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                        #{{ $commission->order->order_number }}
                                                    </a>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    <div class="font-medium text-gray-900">
                                                        {{ $commission->order->user->name }}</div>
                                                    <div class="text-gray-500">{{ $commission->order->user->email }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-gray-900">
                                                    €{{ number_format($commission->amount, 2) }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ $commission->commission_rate }}%
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                    @php
                                                        $statusColors = [
                                                            'pending' =>
                                                                'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                                            'paid' => 'bg-green-50 text-green-800 ring-green-600/20',
                                                            'cancelled' => 'bg-red-50 text-red-800 ring-red-600/20',
                                                        ];
                                                        $colorClass =
                                                            $statusColors[$commission->status] ??
                                                            'bg-gray-50 text-gray-800 ring-gray-600/20';
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $colorClass }}">
                                                        {{ ucfirst($commission->status) }}
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ $commission->created_at->format('M d, Y') }}
                                                </td>
                                                <td
                                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                    <a href="{{ route('dealer.commissions.show', $commission) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                        View<span class="sr-only">, commission for order
                                                            #{{ $commission->order->order_number }}</span>
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
                    @if ($commissions->hasPages())
                        <div class="mt-6">
                            {{ $commissions->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
