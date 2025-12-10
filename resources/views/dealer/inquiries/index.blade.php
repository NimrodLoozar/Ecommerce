<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Page header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Customer Inquiries</h1>
                    <p class="mt-2 text-sm text-gray-600">Manage customer questions and requests.</p>
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

            <!-- Inquiries list -->
            <div class="mt-8 flow-root">
                @if ($inquiries->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto size-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No inquiries yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Customers haven't submitted any inquiries about your
                            vehicles.</p>
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
                                                Customer
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Vehicle
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Subject
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
                                        @foreach ($inquiries as $inquiry)
                                            <tr class="hover:bg-gray-50">
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                    <div class="font-medium text-gray-900">{{ $inquiry->user->name }}
                                                    </div>
                                                    <div class="text-gray-500">{{ $inquiry->user->email }}</div>
                                                </td>
                                                <td class="px-3 py-4 text-sm text-gray-900">
                                                    <a href="{{ route('dealer.cars.show', $inquiry->car) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                        {{ $inquiry->car->year }} {{ $inquiry->car->brand->name }}
                                                        {{ $inquiry->car->carModel->name }}
                                                    </a>
                                                </td>
                                                <td class="px-3 py-4 text-sm text-gray-500">
                                                    <div class="max-w-xs truncate">{{ $inquiry->subject }}</div>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                    @php
                                                        $statusColors = [
                                                            'new' => 'bg-blue-50 text-blue-800 ring-blue-600/20',
                                                            'in_progress' =>
                                                                'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                                            'resolved' =>
                                                                'bg-green-50 text-green-800 ring-green-600/20',
                                                            'converted' =>
                                                                'bg-purple-50 text-purple-800 ring-purple-600/20',
                                                        ];
                                                        $colorClass =
                                                            $statusColors[$inquiry->status] ??
                                                            'bg-gray-50 text-gray-800 ring-gray-600/20';
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $colorClass }}">
                                                        {{ ucfirst(str_replace('_', ' ', $inquiry->status)) }}
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ $inquiry->created_at->format('M d, Y') }}
                                                </td>
                                                <td
                                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                    <a href="{{ route('dealer.inquiries.show', $inquiry) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                        View<span class="sr-only">, inquiry from
                                                            {{ $inquiry->user->name }}</span>
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
                    @if ($inquiries->hasPages())
                        <div class="mt-6">
                            {{ $inquiries->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
