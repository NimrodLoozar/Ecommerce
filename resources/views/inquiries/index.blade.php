<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Inquiries') }}
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($inquiries->count() > 0)
                        <div class="space-y-4">
                            @foreach ($inquiries as $inquiry)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                                    <!-- Inquiry Header -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    {{ $inquiry->subject }}
                                                </h3>
                                                @if ($inquiry->status === 'new')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ring-1 ring-yellow-600/20">
                                                        New
                                                    </span>
                                                @elseif($inquiry->status === 'in_progress')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ring-1 ring-blue-600/20">
                                                        In Progress
                                                    </span>
                                                @elseif($inquiry->status === 'resolved')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ring-1 ring-green-600/20">
                                                        Resolved
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 ring-1 ring-gray-600/20">
                                                        Closed
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-sm text-gray-600">
                                                Sent {{ $inquiry->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Car Information -->
                                    @if ($inquiry->car)
                                        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                            <div class="flex items-center gap-4">
                                                @if ($inquiry->car->images->isNotEmpty())
                                                    <img src="{{ Storage::url($inquiry->car->images->first()->image_path) }}"
                                                        alt="{{ $inquiry->car->brand->name }} {{ $inquiry->car->carModel->name }}"
                                                        class="w-20 h-20 object-cover rounded">
                                                @else
                                                    <div
                                                        class="w-20 h-20 bg-gray-300 rounded flex items-center justify-center">
                                                        <svg class="w-10 h-10 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif

                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900">
                                                        {{ $inquiry->car->year }}
                                                        {{ $inquiry->car->brand->name }}
                                                        {{ $inquiry->car->carModel->name }}
                                                    </h4>
                                                    <p class="text-sm text-gray-600">
                                                        Regarding: Vehicle Inquiry
                                                    </p>
                                                    <a href="{{ route('cars.show', $inquiry->car) }}"
                                                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                        View Vehicle →
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Inquiry Message Preview -->
                                    <div class="mb-4">
                                        <p class="text-gray-700 line-clamp-2">
                                            {{ $inquiry->message }}
                                        </p>
                                    </div>

                                    <!-- Response Status -->
                                    @if ($inquiry->admin_notes)
                                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                            <div class="flex items-start gap-2">
                                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-blue-900">Response received</p>
                                                    <p class="text-sm text-blue-700 mt-1 line-clamp-2">
                                                        {{ $inquiry->admin_notes }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Contact Information -->
                                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            @if ($inquiry->phone)
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                    <span>{{ $inquiry->phone }}</span>
                                                </div>
                                            @endif

                                            @if ($inquiry->responded_at)
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4 text-green-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span class="text-green-600">Responded
                                                        {{ $inquiry->responded_at->diffForHumans() }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- View Details Button -->
                                        <button type="button"
                                            onclick="window.location.href='{{ route('cars.show', $inquiry->car) }}'"
                                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md transition">
                                            View Full Details
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if ($inquiries->hasPages())
                            <div class="mt-6">
                                {{ $inquiries->links() }}
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
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No inquiries yet</h3>
                            <p class="text-gray-600 mb-6">
                                You haven't submitted any inquiries. Browse our cars and ask questions about vehicles
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
