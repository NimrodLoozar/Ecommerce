<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Test Drive Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Info Message -->
            @if (session('info'))
                <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($testDrives->count() > 0)
                        <div class="space-y-4">
                            @foreach ($testDrives as $testDrive)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                                    <!-- Test Drive Header -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    Test Drive Booking
                                                </h3>
                                                @if ($testDrive->status === 'pending')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ring-1 ring-yellow-600/20">
                                                        Pending
                                                    </span>
                                                @elseif($testDrive->status === 'confirmed')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ring-1 ring-blue-600/20">
                                                        Confirmed
                                                    </span>
                                                @elseif($testDrive->status === 'completed')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ring-1 ring-green-600/20">
                                                        Completed
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ring-1 ring-red-600/20">
                                                        Cancelled
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-sm text-gray-600">
                                                Booked {{ $testDrive->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Car Information -->
                                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-4">
                                            @if ($testDrive->car->images->isNotEmpty())
                                                <img src="{{ Storage::url($testDrive->car->images->first()->image_path) }}"
                                                    alt="{{ $testDrive->car->brand->name }} {{ $testDrive->car->carModel->name }}"
                                                    class="w-24 h-24 object-cover rounded">
                                            @else
                                                <div
                                                    class="w-24 h-24 bg-gray-300 rounded flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900 mb-1">
                                                    {{ $testDrive->car->year }}
                                                    {{ $testDrive->car->brand->name }}
                                                    {{ $testDrive->car->carModel->name }}
                                                </h4>
                                                <div class="flex items-center gap-3 text-sm text-gray-600 mb-2">
                                                    <span>{{ $testDrive->car->fuel_type }}</span>
                                                    <span>•</span>
                                                    <span>{{ number_format($testDrive->car->mileage) }} km</span>
                                                    <span>•</span>
                                                    <span>{{ $testDrive->car->transmission }}</span>
                                                </div>
                                                <a href="{{ route('cars.show', $testDrive->car) }}"
                                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                    View Vehicle →
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Booking Details -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Preferred Date</p>
                                                <p class="text-sm text-gray-600">
                                                    {{ $testDrive->preferred_date->format('l, F d, Y') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-3">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-purple-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Preferred Time</p>
                                                <p class="text-sm text-gray-600">
                                                    {{ \Carbon\Carbon::parse($testDrive->preferred_time)->format('H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Confirmed Date (if confirmed) -->
                                    @if ($testDrive->status === 'confirmed' && $testDrive->confirmed_date)
                                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                            <div class="flex items-start gap-2">
                                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-blue-900">Booking Confirmed</p>
                                                    <p class="text-sm text-blue-700 mt-1">
                                                        Your test drive has been confirmed for
                                                        {{ $testDrive->confirmed_date->format('F d, Y \a\t H:i') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Message Preview -->
                                    @if ($testDrive->message)
                                        <div class="mb-4">
                                            <p class="text-sm font-medium text-gray-900 mb-1">Your Message:</p>
                                            <p class="text-sm text-gray-700 line-clamp-2">
                                                {{ $testDrive->message }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Admin Notes (if any) -->
                                    @if ($testDrive->admin_notes)
                                        <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                            <p class="text-sm font-medium text-gray-900 mb-1">Dealer Notes:</p>
                                            <p class="text-sm text-gray-700">
                                                {{ $testDrive->admin_notes }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <span>{{ $testDrive->phone }}</span>
                                            </div>
                                        </div>

                                        <!-- Cancel Button (only for pending/confirmed bookings) -->
                                        @if (in_array($testDrive->status, ['pending', 'confirmed']))
                                            <form action="{{ route('test-drives.update', $testDrive) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to cancel this test drive booking?');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-800 text-sm font-medium rounded-md transition">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Cancel Booking
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if ($testDrives->hasPages())
                            <div class="mt-6">
                                {{ $testDrives->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No test drive bookings yet</h3>
                            <p class="text-gray-600 mb-6">
                                You haven't booked any test drives. Browse our cars and schedule a test drive for
                                vehicles
                                you're interested in.
                            </p>
                            <a href="{{ route('cars.index') }}"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                                Browse Cars
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
