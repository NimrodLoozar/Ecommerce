<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Back button -->
            <div class="mb-8">
                <a href="{{ route('dealer.test-drives.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Test Drives
                </a>
            </div>

            <!-- Page header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Test Drive Details</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Requested on {{ $testDrive->created_at->format('F d, Y \a\t g:i A') }}
                    </p>
                </div>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                        'confirmed' => 'bg-blue-50 text-blue-800 ring-blue-600/20',
                        'completed' => 'bg-green-50 text-green-800 ring-green-600/20',
                        'cancelled' => 'bg-red-50 text-red-800 ring-red-600/20',
                    ];
                    $colorClass = $statusColors[$testDrive->status] ?? 'bg-gray-50 text-gray-800 ring-gray-600/20';
                @endphp
                <div class="mt-4 sm:mt-0">
                    <span
                        class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium ring-1 ring-inset {{ $colorClass }}">
                        {{ ucfirst($testDrive->status) }}
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
                <div class="lg:col-span-2 space-y-6">
                    <!-- Booking Details -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Booking Information</h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Preferred Date</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                                        {{ $testDrive->preferred_date->format('l, F d, Y') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Preferred Time</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                                        {{ $testDrive->preferred_time->format('H:i') }}
                                    </dd>
                                </div>
                            </dl>

                            @if ($testDrive->message)
                                <div class="mt-6">
                                    <dt class="text-sm font-medium text-gray-500">Customer Message</dt>
                                    <dd class="mt-2 text-sm text-gray-900 whitespace-pre-line">{{ $testDrive->message }}
                                    </dd>
                                </div>
                            @endif

                            @if ($testDrive->admin_notes)
                                <div class="mt-6 rounded-lg bg-blue-50 p-4">
                                    <dt class="text-sm font-medium text-blue-900">Internal Notes</dt>
                                    <dd class="mt-2 text-sm text-blue-700 whitespace-pre-line">
                                        {{ $testDrive->admin_notes }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Update Status Form -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Update Booking Status</h2>
                        </div>
                        <div class="px-6 py-4">
                            <form action="{{ route('dealer.test-drives.update', $testDrive) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="space-y-4">
                                    <!-- Status -->
                                    <div>
                                        <label for="status"
                                            class="block text-sm font-medium text-gray-900">Status</label>
                                        <select name="status" id="status" required
                                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                            <option value="pending"
                                                {{ $testDrive->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="confirmed"
                                                {{ $testDrive->status == 'confirmed' ? 'selected' : '' }}>Confirmed
                                            </option>
                                            <option value="completed"
                                                {{ $testDrive->status == 'completed' ? 'selected' : '' }}>Completed
                                            </option>
                                            <option value="cancelled"
                                                {{ $testDrive->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                            </option>
                                        </select>
                                        @error('status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Notes -->
                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-900">Internal
                                            Notes</label>
                                        <textarea name="notes" id="notes" rows="4"
                                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                            placeholder="Add any notes about this test drive booking...">{{ old('notes', $testDrive->admin_notes) }}</textarea>
                                        @error('notes')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">These notes are for internal use only and
                                            won't be shared with the customer.</p>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex justify-end">
                                        <button type="submit"
                                            class="rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                            Update Booking
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Information -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Customer Information</h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $testDrive->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="mailto:{{ $testDrive->email }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            {{ $testDrive->email }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="tel:{{ $testDrive->phone }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            {{ $testDrive->phone }}
                                        </a>
                                    </dd>
                                </div>
                                @if ($testDrive->user)
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-sm font-medium text-gray-500">Registered User</dt>
                                        <dd class="mt-1 text-sm text-gray-900">Yes</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Vehicle Information -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Vehicle</h2>
                        </div>
                        <div class="px-6 py-4">
                            @if ($testDrive->car->images->first())
                                <img src="{{ Storage::url($testDrive->car->images->first()->image_path) }}"
                                    alt="{{ $testDrive->car->brand->name }} {{ $testDrive->car->carModel->name }}"
                                    class="mb-4 w-full rounded-lg">
                            @endif
                            <h3 class="text-sm font-medium text-gray-900">
                                {{ $testDrive->car->year }} {{ $testDrive->car->brand->name }}
                                {{ $testDrive->car->carModel->name }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ $testDrive->car->exterior_color }} •
                                {{ number_format($testDrive->car->mileage) }} km
                            </p>
                            <p class="mt-2 text-lg font-semibold text-gray-900">
                                €{{ number_format($testDrive->car->price, 2) }}
                            </p>
                            <a href="{{ route('dealer.cars.show', $testDrive->car) }}"
                                class="mt-4 block w-full rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                View Vehicle
                            </a>
                        </div>
                    </div>

                    <!-- Booking Timeline -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Booking Timeline</h2>
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
                                        <p class="text-sm font-medium text-gray-900">Booking Created</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $testDrive->created_at->format('M d, Y g:i A') }}
                                        </p>
                                    </div>
                                </li>

                                @if ($testDrive->confirmed_date)
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
                                            <p class="text-sm font-medium text-gray-900">Booking Confirmed</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $testDrive->confirmed_date->format('M d, Y g:i A') }}
                                            </p>
                                        </div>
                                    </li>
                                @endif

                                @if ($testDrive->status === 'completed')
                                    <li class="flex gap-3">
                                        <div class="shrink-0">
                                            <div
                                                class="flex size-8 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">Test Drive Completed</p>
                                            <p class="text-xs text-gray-500">Successfully completed</p>
                                        </div>
                                    </li>
                                @endif

                                @if ($testDrive->status === 'cancelled')
                                    <li class="flex gap-3">
                                        <div class="shrink-0">
                                            <div
                                                class="flex size-8 items-center justify-center rounded-full bg-red-100 text-red-600">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">Booking Cancelled</p>
                                            <p class="text-xs text-gray-500">No longer active</p>
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
