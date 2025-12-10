<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Back button -->
            <div class="mb-8">
                <a href="{{ route('dealer.dashboard') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <!-- Page header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Test Drive Bookings</h1>
                    <p class="mt-2 text-sm text-gray-600">Manage test drive requests for your vehicles</p>
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

            <!-- Test Drives Table -->
            <div class="mt-8 flow-root">
                @if ($testDrives->count() > 0)
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Customer
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Vehicle
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Preferred Date
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Preferred Time
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Contact
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($testDrives as $testDrive)
                                            <tr class="hover:bg-gray-50">
                                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                    <div class="font-medium text-gray-900">{{ $testDrive->name }}</div>
                                                    @if ($testDrive->user)
                                                        <div class="text-gray-500">{{ $testDrive->user->email }}</div>
                                                    @endif
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                    <div class="font-medium text-gray-900">
                                                        {{ $testDrive->car->year }}
                                                        {{ $testDrive->car->brand->name }}
                                                        {{ $testDrive->car->carModel->name }}
                                                    </div>
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                    {{ $testDrive->preferred_date->format('M d, Y') }}
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                    {{ $testDrive->preferred_time->format('H:i') }}
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                    @php
                                                        $statusColors = [
                                                            'pending' =>
                                                                'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                                            'confirmed' => 'bg-blue-50 text-blue-800 ring-blue-600/20',
                                                            'completed' =>
                                                                'bg-green-50 text-green-800 ring-green-600/20',
                                                            'cancelled' => 'bg-red-50 text-red-800 ring-red-600/20',
                                                        ];
                                                        $colorClass =
                                                            $statusColors[$testDrive->status] ??
                                                            'bg-gray-50 text-gray-800 ring-gray-600/20';
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $colorClass }}">
                                                        {{ ucfirst($testDrive->status) }}
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                    <div>{{ $testDrive->email }}</div>
                                                    <div>{{ $testDrive->phone }}</div>
                                                </td>
                                                <td
                                                    class="relative whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                                    <a href="{{ route('dealer.test-drives.show', $testDrive) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                        View<span class="sr-only">, Test Drive
                                                            #{{ $testDrive->id }}</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if ($testDrives->hasPages())
                        <div class="mt-6">
                            {{ $testDrives->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No test drive bookings</h3>
                        <p class="mt-1 text-sm text-gray-500">No customers have requested test drives yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
